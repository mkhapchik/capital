$(document).ready(function(){
	$("input[type='submit'], button, a.button").button();
	$( 'a' ).tooltip({});
	
	$('body').on('click', 'input.date', function(){
		$(this).datepicker('destroy');
		$(this).datepicker({dateFormat: "dd.mm.yy"}).datepicker( "show" );
	});
	
});

