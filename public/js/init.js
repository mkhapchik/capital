$(document).ready(function(){
	$("input[type='submit'], button, a.button").button();
	$( 'a' ).tooltip({});
	$('input.date').datepicker({
		dateFormat: "dd.mm.yy",
		showOn: "button",
		buttonImage: "../img/icon_calendar.png",
		buttonImageOnly: true,
		buttonText: "Выбор даты"
	});
});