$(function() {

	// Filter by rubric
	//
	$('#filter_by_rubric').change(function() {
		$(this).submit();
	});

	// Change enabled
	//
	$('.change-enabled').click(function() {
		APP.changeEnabled($(this), 'posts');
	});

	// Delete record
	//
	$('.delete-posts').click(function(e){
		if ( ! confirm(APP.msg.confirm.delete_post)) {
			return false;
		}
	});

	// Publication date
	//
	if ($('#pubdate').length) {
		$('#pubdate').datepicker({
			format: 'yyyy-mm-dd'
		}).on('changeDate', function(e) {
			$('#pubdate').datepicker('hide');
		});
	}

	// Tabs
	//
	$('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	// SEO features
	//
	if ($('#meta_d').length)
		$('#meta_d').limiter(APP.META_D, $('#chars_meta_d'));

	if ($('#meta_k').length)
		$('#meta_k').limiter(APP.META_K, $('#chars_meta_k'));

	// Post tag manager
	//
	if ($('#arr_tags').length)
		APP.tm($('#arr_tags'), jsondata_post, populate_post);


	var current_action = $('input[name="current_action"]').val();

	// Upload image
	//
	if (current_action == 'add') {
		APP.imageUpload($('#fimage_post'), 'posts', 'fimage_post_upload_add');
	}
	if (current_action == 'edit') {
		APP.imageUpload($('#fimage_post'), 'posts', 'fimage_post_upload_edit');
	}

	// Rotate Image
	//
	$('#response').on('click', '.fimage_post-rotate-left', function(){
		APP.rotateImage($(this), 'posts', 'fimage_post_rotate_' + current_action, 'ACW');
	})
	$('#response').on('click', '.fimage_post-rotate-right', function(){
		APP.rotateImage($(this), 'posts', 'fimage_post_rotate_' + current_action, 'CW');
	})

	// Delete Image
	//
	$('#response').on('click', '.fimage_post-delete-image', function(){
		if ( ! confirm(APP.msg.confirm.delete_image)) {
			return false;
		}
		APP.deleteImage($(this), 'posts', 'fimage_post_delete_' + current_action);
	})
});
