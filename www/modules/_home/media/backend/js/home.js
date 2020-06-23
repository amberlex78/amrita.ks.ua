$(function() {

	// SEO features
	//
	if ($('#meta_d').length)
		$('#meta_d').limiter(APP.META_D, $('#chars_meta_d'));

	if ($('#meta_k').length)
		$('#meta_k').limiter(APP.META_K, $('#chars_meta_k'));

});
