(function( $ ) {
	
	//Initializing jQuery UI Datepicker
	$( '#_shapla_event_start' ).datepicker({
		dateFormat: 'MM dd, yy',
		changeMonth: true,
		changeYear: true,
		onClose: function( selectedDate ){
			$( '#_shapla_event_start' ).datepicker( 'option', 'minDate', selectedDate );
		}
	});
	$( '#_shapla_event_end' ).datepicker({
		dateFormat: 'MM dd, yy',
		changeMonth: true,
		changeYear: true,
		onClose: function( selectedDate ){
			$( '#_shapla_event_end' ).datepicker( 'option', 'maxDate', selectedDate );
		}
	});
	$( '#_shapla_portfolio_date' ).datepicker({
		dateFormat: 'MM dd, yy',
		changeMonth: true,
		changeYear: true,
		onClose: function( selectedDate ){
			$( '#_shapla_portfolio_date' ).datepicker( 'option', 'maxDate', selectedDate );
		}
	});

})( jQuery );