$(function() {

	// Change enabled
	//
	$('.change-enabled').click(function() {
		APP.changeEnabled($(this), 'static');
	});

	// Delete record
	//
	$('.delete-static').click(function(e){
		if ( ! confirm(APP.msg.confirm.delete_static)) {
			return false;
		}
	});
	
	// Tab
	//
	$('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})

	// SEO features
	//
	if ($('#meta_d').length)
		$('#meta_d').limiter(APP.META_D, $('#chars_meta_d'));

	if ($('#meta_k').length)
		$('#meta_k').limiter(APP.META_K, $('#chars_meta_k'));


	var current_action = $('input[name="current_action"]').val();
	
	// Upload image
	//
	if (current_action == 'add') {
		APP.imageUpload($('#fimage_static'), 'static', 'fimage_static_upload_add');
	}
	if (current_action == 'edit') {
		APP.imageUpload($('#fimage_static'), 'static', 'fimage_static_upload_edit');
	}

	// Rotate Image
	//
	$('#response').on('click', '.fimage_static-rotate-left', function(){
		APP.rotateImage($(this), 'static', 'fimage_static_rotate_' + current_action, 'ACW');
	})
	$('#response').on('click', '.fimage_static-rotate-right', function(){
		APP.rotateImage($(this), 'static', 'fimage_static_rotate_' + current_action, 'CW');
	})

	// Delete Image
	//
	$('#response').on('click', '.fimage_static-delete-image', function(){
		if ( ! confirm(APP.msg.confirm.delete_image)) {
			return false;
		}
		APP.deleteImage($(this), 'static', 'fimage_static_delete_' + current_action);
	})
});
