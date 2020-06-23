var APP = {

	/**
	 * Ajax directory
	 */
	AJAX_URL: '/ajax/',

	/**
	 * Uploaders generator
	 * http://www.ajaxload.info
	 */
	AJAX_IMG_LOADER_16: '<img src="/media/backend/img/ajax-loader-16.gif" />',
	AJAX_IMG_LOADER_24: '<img src="/media/backend/img/ajax-loader-24.gif" />',

	/**
	 * Max characters for meta description and meta keywords
	 */
	META_D: 255,
	META_K: 255,

	/**
	 * Text messages
	 */
	msg: {
		caption: {
			yes: 'Yes',
			no:  'No',
			on:  'On',
			off: 'Off'
		},
		err: {
			transmission: 'Transmission data error!'
		},
		confirm: {
			delete_static:  'Delete page?',
			delete_page:    'Delete page?',
			delete_image:   'Delete image?',
            delete_user:    'Delete user?',
            delete_rubric:  'Delete rubric?',
			delete_post:    'Delete post?',
            delete_article: 'Delete article?',
			delete_category:'Delete category?',
			delete_product: 'Delete product?',
			delete_tag:     'Delete tag?'
		},
		info: {
		}
	},

	/**
	 * Flash Messages
	 * @param message
	 * @param type (error || success || info)
	 * @param time
	 */
	alert: function(message, type, time) {
		var time = time || 3000
		$('.top-right').notify({
			message: { html: message },
			fadeOut: { delay: time },
			type: type
		}).show();
	},

	/**
	 * Change status on 0 or 1 for field
	 * @param el
	 * @param controller
	 * @param field
	 */
	changeEnabled: function(el, controller, field)
	{
		var field = field || 'enabled';
		var cl = el.attr('class');
		el.addClass('icon-spinner icon-spin').find('i').attr('class', 'icon-none');
		$.ajax({
			dataType: 'json',
			type: 'post',
			url: APP.AJAX_URL + controller + '/change_' + field,
			data: { id: el.attr('id') },
			success: function(data) {
				el.removeClass();
				if (data.success) {
					if (data.enabled) {
						el.addClass('change-' + field + ' icon-large text-success icon-ok-sign');
					} else {
						el.addClass('change-' + field + ' icon-large text-error icon-ok-sign');
					}
					APP.alert(data.message, 'success');
					return true;
				} else {
					el.addClass(cl);
					APP.alert(data.message, 'error');
				}
			},
			error: function() {
				el.removeClass().addClass(cl);
				APP.alert(APP.msg.err.transmission, 'error');
			}
		});
	},

	/**
	 * Change status for mptt on 0 or 1 for field
	 * @param el
	 * @param controller
	 * @param field
	 */
	mpttChangeEnabled: function(el, controller, field)
	{
		var field = field || 'enabled';
		var cl = el.attr('class');
		el.addClass('icon-spinner icon-spin').find('i').attr('class', 'icon-none');
		$.ajax({
			dataType: 'json',
			type: 'post',
			url: APP.AJAX_URL + controller + '/change_' + field,
			data: { id: el.attr('id') },
			success: function(data) {
				if (data.success) {
					APP.alert(data.message, 'success');
					location.reload();
				} else {
					el.removeClass().addClass(cl);
					APP.alert(data.message, 'error');
				}
			},
			error: function() {
				el.removeClass().addClass(cl);
				APP.alert(APP.msg.err.transmission, 'error');
			}
		});
	},

	/**
	 * Upload image
	 * @param el
	 * @param controller  ajax controller
	 * @param action      ajax action
	 * @param inner       id for response div
	 */
	imageUpload: function(el, controller, action, inner)
	{
		var inner = inner || 'response';
		var id = el.attr('rel') || 'new';

		el.liteUploader({
			script: APP.AJAX_URL + controller + '/' + action + '/' + id,
			before: function () {
				$('#' + inner).html(APP.AJAX_IMG_LOADER_24);
			},
			success: function (response) {
				$('#' + inner).html(response);
			}
		});
	},

	/**
	 * Rotate uploaded image
	 * @param el
	 * @param controller
	 * @param action
	 */
	rotateImage: function(el, controller, action, direction) {
		var id = el.parent().parent().attr('id');
		$.ajax({
			dataType: 'json',
			type: 'post',
			url: APP.AJAX_URL + controller + '/' + action,
			data: {
				id: id,
				direction: direction
			},
			success: function(data) {
				if (data.success) {
					$('img[rel="' + parseInt(id) + '-' + id + '"]').attr('src', data.src);
					APP.alert(data.message, 'success');
				} else {
					APP.alert(data.message, 'error');
				}
			},
			error: function() {
				APP.alert(APP.msg.err.transmission, 'error');
			}
		});
	},

	/**
	 * Delete uploaded image
	 * @param el
	 * @param controller
	 * @param action
	 */
	deleteImage: function(el, controller, action) {
		var id = el.parent().parent().attr('id');
		$.ajax({
			dataType: 'json',
			type: 'post',
			url: APP.AJAX_URL + controller + '/' + action,
			data: { id: id },
			success: function(data) {
				if (data.success) {
					el.parent().parent().remove();
					APP.alert(data.message, 'success');
				} else {
					APP.alert(data.message, 'error');
				}
			},
			error: function() {
				APP.alert(APP.msg.err.transmission, 'error');
			}
		});
	},

	/**
	 * Tag manager
	 *
	 * @param el
	 * @param json
	 * @param populate
	 *
	 * @see http://soliantconsulting.github.io/tagmanager/index.html
	 */
	tm: function(el, jsondata, populate) {
		el.tagmanager({
			initialCap: false,
			delimiterChars: [ 13, 44, 188 ],
			tagFieldName: 'arr_tags[]',
			validateHandler: function(tagManager, tag, isImport) {
				if (isImport) return tag;

				var index = $.inArray(tag, tagManager.tagStrings);
				if (index != -1) {
					$('#' + tagManager.tagIds[index]).fadeOut().fadeIn();
					return false;
				}
				return tag;
			}
		}).typeahead({
			source: jsondata,
			suppressKeyPressRepeat: true,
			updater: function(tag) {
				this.$element.data('tagmanager').create([ tag ]);
				this.hide();
			}
		});

		if (populate !== null)
			el.data('tagmanager').populate(populate);
	}

};

$(function() {

	// Flash Messages (notification)
	// Vars sets in views/backend/v_message.php
	//
	if ((typeof notification_text != 'undefined') && (typeof notification_type != 'undefined')) {
		APP.alert(notification_text, notification_type);
	}

	// Tooltip for links, actions
	//
	$('[rel=tooltip]').tooltip();
});
