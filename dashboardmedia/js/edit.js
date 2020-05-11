jQuery(document).ready(function($) {
	/*
	 * jQuery Datepicker plugin
	 */
	jQuery('.dashboard_datetime').datepicker({
		dateFormat : 'yy-mm-dd',
		yearRange : '1900:2050',
		changeMonth : true,
		changeYear : true,
	});
	
	$('.dashboard-dropdown').select2();
	
	/*
	 * js code for 
	 * bootstrap tooltip
	 */
	$('.form-group label').tooltip();
});
