$(document).ready(function(){
	$('table tbody tr td.finish').dblclick(function(){
		selector($(this).attr('id'));
	})

});

function selector(id)
{
	$('table').find('tr').click( function(){
		var index = $(this).index()+1;
	});

	
	$('table').before('<div style="position:absolute;" id="selector_yes_' + id +'">Si</div>');
	$('table').before('<div style="position:absolute;" id="selector_no_' + id +'">No</div>');
}