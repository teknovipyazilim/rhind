$(document).ready(function(){

	$( "div.value-slider" ).each(function( index ) {
		var target= $(this).attr('target');
		var min= $(this).attr('min')*1;
		var max= $(this).attr('max')*1;
		var step= $(this).attr('step')*1;
		var value= $(this).val()*1;

		$(this).slider(
		{
			min: min , 
			max: max , 
			step: step , 
			value : value,
			slide: function(event, ui) 
			{
				$(target).val(ui.value);
				//console.log(ui);
				//slider_change(target , ui.value);
				$(target).change();
			}
		});
	});	
	$(document).on('click','.btn-calculate', function(){
		startCalculate();
	});
	$(document).on('change','.calc-trigger', function(){
		startCalculate();
	});
	
	function startCalculate()
	{
		var url = 'index.php?route=ahmes/ajax/calculate';
		var post = {};
		$('.product-price').html('Wait...');
		$('.price-tax').html('Wait...');
		var form = $('#product-form').serialize();
		ajaxJson(url, form);	
	}

	function ajaxJson(url, form)
	{
		$.ajax({
		  method: "POST",
		  url: url,
		  data: form
		})
		.done(function( result ) {
			console.log(result);
			if ( typeof result.action !== 'undefined'  ) {
				window[result.action](result);
			}
		})
		.fail(function() {
			return -1;
		})
		.always(function() {
			//alert( "always" );
		});	
	}
startCalculate();
})
	
	function calculate(result)
	{
		console.log(result.list);
		$('.product-price').html(result.list.total);
		$('.price-tax').html(result.list.tax);
		if(result.list.skin.thumb!='')
			{
				$('#image').attr('src', result.list.skin.thumb);
				$('#image').attr('data-largeimg', result.list.skin.popup);
				$('#image').parents('a').attr('href', result.list.skin.popup);
				//changeZoomImage(result.list.skin.thumb, result.list.skin.popup, 0);
				//$('.zm-viewer img').attr('src', result.list.skin.popup);
			}
	}