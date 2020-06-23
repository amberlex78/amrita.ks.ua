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
		},
		info: {
		}
	},

	/**
	 * Flash Messages
	 * @param message
	 * @param type (error || success || info)
	 *
	 * http://sandbox.scriptiny.com/tinybox2/
	 */
	alert: function(message, type) {
		TINY.box.show({
			html: message,
			animate: false,
			close: false,
			mask: false,
			boxid: type,
			autohide: 3,
			top: 0
			//left: 0
		});
	}
};

$(function() {

	// Flash Messages (notification)
	// Vars sets in views/frontend/v_message.php
	//
	if ((typeof notification_text != 'undefined') && (typeof notification_type != 'undefined')) {
		APP.alert(notification_text, notification_type);
	}

	// Init Cart
	//
	$.Cart.init();

	// Captcha refresh
	//
	$('.captcha-refresh').click(function(e) {
		var id = Math.floor(Math.random() * 1000000);
		$('img.captcha').attr('src', '/captcha/default?id=' + id);
	});


	$('input[name="ns"]').val('ok');

	$('#do_submit').click(function() {
		$('#frm_contact')
			.attr('action', CONTACT_ACTION)
			.submit();
	})

});
