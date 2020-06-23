$(function() {

	// Filter by role
	//
	$('#filter_by_role').change(function() {
		$(this).submit();
	});

	// Delete record
	//
	$('.delete-user').click(function(e){
		if ( ! confirm(APP.msg.confirm.delete_user)) {
			return false;
		}
	})

	// Datepicker for Date of birth
	//
	$('#dob').datepicker({
		format: 'yyyy-mm-dd'
	}).on('changeDate', function(e) {
		$('#dob').datepicker('hide');
	});

	var current_action = $('input[name="current_action"]').val();

	// Upload
	//
	if (current_action == 'add') {
		APP.imageUpload($('#fimage_avatar'), 'user', 'fimage_avatar_upload_add');
		APP.imageUpload($('#fimage_logo'), 'user', 'fimage_logo_upload_add', 'response_logo');
	}
	if (current_action == 'edit') {
		APP.imageUpload($('#fimage_avatar'), 'user', 'fimage_avatar_upload_edit');
		APP.imageUpload($('#fimage_logo'), 'user', 'fimage_logo_upload_edit', 'response_logo');
	}

	// Rotate
	//
	$('#response').on('click', '.fimage_avatar-rotate-left', function(){
		APP.rotateImage($(this), 'user', 'fimage_avatar_rotate_' + current_action, 'ACW');
	})
	$('#response').on('click', '.fimage_avatar-rotate-right', function(){
		APP.rotateImage($(this), 'user', 'fimage_avatar_rotate_' + current_action, 'CW');
	})
	$('#response_logo').on('click', '.fimage_logo-rotate-left', function(){
		APP.rotateImage($(this), 'user', 'fimage_logo_rotate_' + current_action, 'ACW');
	})
	$('#response_logo').on('click', '.fimage_logo-rotate-right', function(){
		APP.rotateImage($(this), 'user', 'fimage_logo_rotate_' + current_action, 'CW');
	})

	// Delete
	//
	$('#response').on('click', '.fimage_avatar-delete-image', function(){
		if ( ! confirm(APP.msg.confirm.delete_image)) {
			return false;
		}
		APP.deleteImage($(this), 'user', 'fimage_avatar_delete_' + current_action);
	})
	$('#response_logo').on('click', '.fimage_logo-delete-image', function(){
		if ( ! confirm(APP.msg.confirm.delete_image)) {
			return false;
		}
		APP.deleteImage($(this), 'user', 'fimage_logo_delete_' + current_action);
	})

});
