$(function() {

	// Change enabled
	//
	$('.change-enabled').click(function() {
		APP.mpttChangeEnabled($(this), 'rubric');
	});

	// Delete record
	//
	$('.delete-rubrics').click(function(e){
		if ( ! confirm(APP.msg.confirm.delete_rubric)) {
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

});
