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
	
	$('body').on('blur', ".currency", function(){
		currency_eval($(this));
	});
});

function setSelectStyle(sel)
{
	if(sel.val().length==0) sel.addClass('empty');
	else sel.removeClass('empty');
}

function currency_eval(currency)
{
	var val = eval(currency.val().replace(/[,]+/g,'.').replace(/[^0-9\.+-/*()]/g,'0'))+'';
	
	if(!(val.indexOf('.')+1)) val+='.00';
	else if(val.indexOf('.')==val.length) val+='00';
	
	currency.val(val);
}
