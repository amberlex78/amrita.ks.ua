$(function() {

	// Datepicker for Date of birth
	//
	$('#dob').datepicker({
		format: 'yyyy-mm-dd'
	}).on('changeDate', function(e) {
		$('#dob').datepicker('hide');
	});

});
