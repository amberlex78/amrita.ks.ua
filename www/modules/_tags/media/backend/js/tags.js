$(function() {

	// Filter tags
	//
	$('#frm_filter_tags').change(function() {
		$(this).submit();
	});

	// Delete record
	//
	$('.delete-tags').click(function(e){
		if ( ! confirm(APP.msg.confirm.delete_tag)) {
			return false;
		}
	});

});