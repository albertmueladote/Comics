$(document).ready(function(){

	$('table tbody tr td.finish').dblclick(function(e){
		selector($(this).attr('id'), e, 'yn');
		finish_event();

	})

	$('table tbody tr td.missing').dblclick(function(e){
		selector($(this).attr('id'), e, 'input');
		missing_event();
	})

	$('table tbody tr td.status').dblclick(function(e){
		selector($(this).attr('id'), e, 'select');
		missing_event();
	})
});

function selector(id, e, type)
{

	$('.update ').remove();

	$('table td').css('padding', '5px 10px');
	$('#' + id).css('padding', '0');
	$('#' + id).css('position', 'relative');

	switch(type){
		case 'yn':
			selector_yes_no(id);
			break;
		case 'input':
			selector_input(id);
			break;
		case 'select':
			selector_select(id);
			break;
		default:
			break;
	}
}

function selector_yes_no(id)
{	
	var div1 = $('<div>')
		.attr('class', "update selector_yes")
		.attr('id', 'selector_yes_' + id)
		.attr('data-value', 1)
		.css({
	    	"display": "none",
	    	"position": "absolute",
	        "left": -5 + 'px',
	        "bottom": -30 + 'px'
	    });

	var div2 = $('<div>')
		.attr('class', "update selector_no")
		.attr('id', 'selector_no_' + id)
		.attr('data-value', 0)
		.css({
	    	"display": "none",
	    	"position": "absolute",
	    	"right": -5 + 'px',
	        "bottom": -30 + 'px'
	    });

	$('#' + id).append(div1);
	$('#' + id).append(div2);

	$('#selector_yes_' + id + ', #selector_no_' + id).show("slow", function(){});
}

function selector_input(id)
{
	var input = $('<input>')
		.attr('class', "update input_missing")
		.attr('id', 'input_' + id)
		.attr('data-value', 'submit_' + id)
		.css({
	    	"display": "none",
	    	"position": "absolute",
	        "left": 0 + 'px',
	        "width":'60%',
	        "height": '100%',
	        "left": 0,
			"top": 0,
			"border": 'none'
	    });
	   var button = $('<div>')
		.attr('class', "update submit_missing")
		.attr('id', 'submit_' + id)
		.css({
	    	"display": "none",
	    	"position": "absolute",
	        "right": '-6px',
	        "top": '-6px',
	    });

	$('#' + id).append(input);
	$('#' + id).append(button);

	$('#input_' + id).show("slow", function(){});
	$('#submit_' + id).show("slow", function(){});
	$('#input_' + id).focus();
}

function selector_select(id)
{
	$.ajax({
        url: 'controller/ajax/status.php',
        dataType: "json",
        data: {
            id : id
        },
        success: function(result) {
        	var select = $('<select>')
				.attr('class', "update select_status")
				.attr('id', 'select_' + id)
				.css({
			    	"display": "none",
			    	"position": "absolute",
			        "left": 0,
			        "top": 0,
			        "width":'100%',
			        "height": '100%'
			    });

			$('#' + id).append(select);

			$.each(result.data, function(value, label){
				$("#select_" + id).append(new Option(label.title, value));
			});

			$("#select_" + id).show(function(){});
        },
    });
}

function finish_event()
{
	$('.selector_yes, .selector_no').click(function(){
		finish($(this));
	});
}

function finish(element)
{
	$.ajax({
        url: 'controller/ajax/finish.php',
        dataType: "json",
        data: {
            id : element.attr('id'),
            value : element.attr('data-value')
        },
        success: function(result) {
        	if(result.result){
				refresh(result.id);
			}
			if(result.alert){
				alert(result.alert);
			}
        },
    });
}

function missing_event()
{
	$('.submit_missing').click(function(){
		missing($('.input_missing[data-value="' + $(this).attr('id') + '"]'));
	});
}


function missing(element)
{
	$.ajax({
        url: 'controller/ajax/missing.php',
        dataType: "json",
        data: {
            id : element.attr('id'),
            value : element.val()
        },
        success: function(result) {
        	if(result.result){
				refresh(result.id);
			}
			if(result.alert){
				alert(result.alert);
			}
        },
    })
}

function refresh(id)
{
	$.ajax({
        url: 'controller/ajax/refresh.php',
        dataType: "json",
        data: {
            id : id
        },
        success: function(result) {
        	if(result.result){
			    $.each(result.data, function(key, value){
			    	$('#' + key + '_' + id).html(value);
			    });
			}
        },
    });
}