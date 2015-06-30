$(document).ready(function(){
	$("input[type='submit'], button, a.button").button();
	$( 'a' ).tooltip({});
	
	$('body').on('click', 'input.date', function(){
		
		$(this).datepicker('destroy');
		$(this).datepicker({dateFormat: "dd.mm.yy"}).datepicker( "show" ).datepicker( "setDate", $(this).val());
		
		return false;
	});
	
	$('body').on('change', 'select', function(){
		setSelectStyle($(this))
	});
	
	$('select').each(function(){
		setSelectStyle($(this));
	});
	
});

function setSelectStyle(sel)
{
	if(sel.val().length==0) sel.addClass('empty');
	else sel.removeClass('empty');
}
