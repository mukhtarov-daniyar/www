
$(function(){ $('[data-toggle="tooltip"]').tooltip(); });


var cnt = 2;

$(document).on('click','.exp.more',function(e)
{
	var more = cnt*100;

	$.ajax
		({
			url: "/speedometer/more_default/"+window.location.search+'&cnt='+more,
			type: "GET",
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				document.title = 'Идет загрузка данных...';
			},
			success: function(response)
			{
				cnt ++;
				$("#myModalBox").modal('hide');
				//$(".count_1c").html(more);
				$(".more_default").html(response);
				$("html, body").stop().animate({scrollTop: $('.SPDOWN').offset().top - 0 + 'px'}, 500);
				document.title = 'Список товаров 1С на складе - База Клиентов';
			}
		});
		e.preventDefault();
});



$(document).on('click','.input_id_product button',function(e)
{
		var id = $(this).attr('data-id');

		var vals = $('.input_val_id_product.'+id).val();

		if(vals > 0)
		{
			$.post( "/speedometer/card/", { vals: vals, id: id } )  .done(function( data )
			{
					if(data == 'Товар добавлен в корину заказа!')
					{
						$('.id_product.product_'+id).addClass('show');
					}
					$('.alert').html(data);
					$('.alert').animate({'opacity':'show'}, 1000);
					$('.alert').animate({'opacity':'hide'}, 2000);
			});
		}
		$('.input_id_product #'+id).animate({'opacity':'hide', }, 1000).removeClass('show');
		return false;
});


$(document).on('click','.input_val_id_product',function(e)
{
		return false;
});

$(document).on('click','.input_id_product',function(e)
{
		var id = $(this).attr('data-id');

		if(  $('.input_id_product #'+id).hasClass('show') )
		{
			$('.input_id_product #'+id).removeClass('show');
		}
		else
		{
			$('.input_id_product #'+id).addClass('show');
		}

		e.preventDefault();
		return false;
});


$(document).on('click','.form-check-input',function(e)
{
	var id = $(this).val();

	if(id == '')
	{
		alert('Что то пошло не так:(');
	}
	else
	{
		$.ajax
		({
			url: "/speedometer/audit/",
			type: "POST",
			data: {"id": id},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				$("#myModalBox").modal('hide');

				var obj = $.parseJSON(response);

				if(obj.returns == 1)
				{
					alert(obj.text);
					$( '.product_' +  obj.product_id + ' .form-check-label').html( '<input checked="checked" type="checkbox" value="">' );
				}
				else
				{
					alert(obj.text);
				}
			}
		});
	}

	e.preventDefault();
	return false;
});


$(document).on('click','a.remove_position',function(e)
{
	var id = $(this).attr('data-id');
	location.href = id;

	e.preventDefault();
	return false;
});

$(document).on('click','.select_ruk a',function(e)
{
	 $('.selectpicker.manager_ruk').val($(this).attr('data-id'));
	 $('.selectpicker.manager_ruk').selectpicker('refresh');
	e.preventDefault();
});

$(document).on('click','.select_ruk_2 a',function(e)
{
	 $('.selectpicker.manager_ruk_2').val($(this).attr('data-id'));
	 $('.selectpicker.manager_ruk_2').selectpicker('refresh');
	e.preventDefault();
});


/* Руководитель продаж данного товара */
$(document).on('click','bottom.save_ruk',function(e)
{
	var id = $(this).attr('data-id');
	var val = $('.selectpicker.manager_ruk').val();
	var bu = $('input.bu').val();
	var uu = $('input.uu').val();

	var val_2 = $('.selectpicker.manager_ruk_2').val();
	var bu_2 = $('input.bu_2').val();
	var uu_2 = $('input.uu_2').val();


	if(val == 0)
	{
		alert('Назначьте руководителя');
	}
	else
	{
		$.ajax
				({
					url: "/speedometer/ruk/",
					type: "POST",
					data: {"id": id, "val": val, "bu": bu, "uu": uu, 		"val_2": val_2, "bu_2": bu_2, "uu_2": uu_2},
					cache: true,
					beforeSend: function()
					{
						$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
						$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
					},
					success: function(response)
					{
							$('.row.td.id_product.product_'+id).click();
					}
				});
	}
	e.preventDefault();
});

$(document).on('click','.quickbox',function(e)
{
			return false;
});


$(document).on('click','img.vendor',function(e)
{
	var vendor = $(this).attr('data-vendor');
	navigator.clipboard.writeText(vendor);
	$('.alert').html('Артикул: '+vendor+', cкопирован!');
	$('.alert').animate({'opacity':'show'}, 1000);
	$('.alert').animate({'opacity':'hide'}, 4000);
	return false;
});



$(document).on('click','.id_product',function(e)
{
  var id = $(this).attr('data-id');
	var vendor = $(this).attr('data-vendor');
  var text = $(this).text();
	$('#myModalBox').modal('show') ;
  $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><center><h4 class="modal-title">Информация о товаре</h4></center></div>');
  $(".modal-body").append('<p><strong>Артикул товара:</strong> '+vendor+'<p>');
	$(".modal-body").append('<p><strong>Идентификатор товара:</strong> '+id+'<p>');

  $.ajax
  		({
  			url: "/static/data_1C_id/",
  			type: "POST",
  			data: {"id": id},
        async:false,
  			cache: true,
  			success: function(response)
  			{
           $(".modal-body").append('<p>'+response+'<p>');
           isotope();
  			}

  		});

			 $('.selectpicker.manager_ruk').selectpicker('refresh');
			 $('.selectpicker.manager_ruk_2').selectpicker('refresh');

      $('.period').datepicker({
       dateFormat: 'dd.mm.yy',
          onSelect: function (date)
          {
            oneC_ostatok(id, date);
          }
      })

      var datas = $(".period").val();
      oneC_ostatok(id, datas);

});

function oneC_ostatok(id, datas)
{
    $.ajax
    ({
      url: "/static/data_1C_ostatok/",
      type: "POST",
      async:false,
      data: {"id": id,"datas": datas},
      cache: true,
      success: function(response)
      {
          $(".period_num").html(response);

          var count_real =   $(".count_real").html();

          var x = (response-count_real)/response;

          if(x == '-Infinity')
          {
            $(".coefficient").html('<span style="color:#F00">В этот период не было товара</span>');
          }
          else
          {
            $(".coefficient").html(x.toFixed(3));
          }

          isotope();
      }
    });
}



$(document).ready(function(e)
{
	$(document).on('scroll', function(e)
	{
		if( $(document).scrollTop() > 350 )
		{
					$( ".row.th" ).addClass( "fixed" );

					var wdt = $( ".row.td" ).width();
					$( ".row.th" ).width(wdt);
		}
		else
		{
				$( ".row.th" ).removeClass( "fixed" );
		}
	});
});

$(document).on('click','.refresh_1C',function(e)
{
	$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
	$(".modal-body").html('<h4 class="modal-title"><center>Идет загрузка данных с 1С, дождитесь завершения....</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

	$.ajax
	({
		url: "/static/data_1C",
		type: "GET",
		cache: true,
		success: function(response)
		{
			if (typeof response == 'undefined' || response == 0)
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Произошла ошибка, дождитесь перезагрузки страници и попробуйте еще раз :(</center></h4>');
				setTimeout(function() {window.location.reload();}, 1000);
			}
			else
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Новые данные загружены. Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				setTimeout(function() {window.location.reload();}, 1000);
			}
		}
	});


});

$(document).on('click','a.sale_year',function(e)
{
	e.preventDefault();
	window.open($(this).attr('href'), "_blank");
	return false;
});
