
var record_count_hidden = $('.record_count_hidden').html();
$('.record_count').html(record_count_hidden);

$('.day').scrollbar();

$('.mobile').inputmask("+7-999-999-99-99");
$('.international_mobile').inputmask({
  mask: '+99-9-999-999-99-99',
  placeholder: ' ',
  showMaskOnHover: false,
  showMaskOnFocus: false,
  onBeforePaste: function (pastedValue, opts) {
    var processedValue = pastedValue;
    return processedValue;
  }
});



$(document).ready(function()
{
  var searchValue = "!!!";
  $(".customer .row").each(function()
  {
    if($(this).html().indexOf(searchValue) > -1)
    {
      $(this).addClass('reds');
    }
  });


  $('input.providerS').keyup(function()
  {
    $('input.providerV').val('');
    var provider  = $(this).val();
      $.ajax
  		({
  			url: "/speedometer/provider/",
  			type: "POST",
  			data: {"provider": provider},
  			cache: true,
  			success: function(response)
  			{
             $('.rec_provider').html(response);
  			}
  		});
  });

  $(document).on('click','ul.provider_rec li',function(e)
  {
    var html = $(this).html();
    var id = $(this).attr('data-id');

    $('input.providerS').val(html);
    $('input.providerV').val(id);
    $('.rec_provider').html('');
    $('.alert').html("Нажмите на кнопку «Поиск», для отображения информации.");
    $('.alert').animate({'opacity':'show'}, 1000);
    $('.alert').animate({'opacity':'hide'}, 6000);
  });



  // Подсказка при поиске в header
  $('input#q').keyup(function()
  {
    $('.rec_buyerS').html('');
    var buyer  = $(this).val();
    var curLength = buyer.length;

    if(curLength > 0)
    {
       $.ajax
       ({
         url: "/static/inputQ/",
         type: "POST",
         data: {"buyer": buyer},
         cache: true,
         success: function(response)
         {
           if(response == 0)
           {
             $('.rec_buyerS').html('<div class="mask_none">Ничего не найдено!</div>');
           }
           else {
             $('.rec_buyerS').html(response);
           }
         }
       });
    }

  });
});



$(document).ready(function(){
    $( document ).on( 'focus', ':input', function(){
        $( this ).attr( 'autocomplete', 'off' );
    });
});



$('a.quickbox').fancybox({
  afterLoad : function(instance, current) {
    var pixelRatio = window.devicePixelRatio || 1;

    if ( pixelRatio > 1.5 ) {
      current.width  = current.width  / pixelRatio;
      current.height = current.height / pixelRatio;
    }
  }
});


/* поиск в шапке. включение расширеного поиска*/
$('input.option').on('click', function()
{
	if(  $('input.option').hasClass('shos') )
	{
		$('input.option').removeClass('shos');
		$('input.option').val('off');
	}
	else
	{
		$('input.option').addClass('shos');
		$('input.option').val('on');
	}

});
/* поиск в шапке. включение расширеного поиска*/

/* поиск в шапке*/
$('.data_form_search').on('click', function(e)
{
	var input = $('input#q').val();

	var curLength = $('input#q').val().length;

	if(curLength > 1)
	{
		location.href = '/search/'+input;
	}
	else
	{
		$('.alert').html("Введите поисковое слово!");
		$('.alert').animate({'opacity':'show'}, 1000);
		$('.alert').animate({'opacity':'hide'}, 4000);
	}

	event.preventDefault ();
});
/* поиск в шапке*/



$('.reply').on('click', function(e)
{
	var id = $(this).attr('data-id');

	$("#reply_" + id).animate({'scrollTop': 10}, 400, "easeInSine");
	$(".addcoment_body").appendTo("#reply_" + id);


	e.preventDefault();
});


$('.users .data').on('click', function(e)
{
	if(  $('.users .old').hasClass('show') )
	{
		$('.users .old').removeClass('show');
		$('.users .data').removeClass('show');
	}
	else
	{
		$('.users .old').addClass('show');
		$('.users .data').addClass('show');
	}

	e.preventDefault();
});


$('.container_new aside .btn-open').on('click', function(e)
{
	if(  $('.container_new aside').hasClass('showS') )
	{
		$('.container_new aside').removeClass('showS');
	}
	else
	{
		$('.container_new aside').addClass('showS');
	}

	e.preventDefault();
});



$('.selectpicker.status').change(function ()
{
	var selectedText = $(this).find("option:selected").val();
	var id = $(this).attr('data-id');

		$.ajax
		({
			url: "/static/user_status/",
			type: "POST",
			data: {"user_id": id, "user_status": selectedText},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

				$(".modal-body").append('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Статус пользователя изменен.</h4></div>');
			}

		});
});



$('.selectpicker.user_op').change(function ()
{
	var selectedText = $(this).find("option:selected").val();
	var id = $(this).attr('data-id');

	if(selectedText == 1)
	{
		$.ajax
		({
			url: "/static/user_op/",
			type: "POST",
			data: {"user_id": id},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				$("#myModalBox").modal('hide');
				setTimeout(function() {window.location.reload();}, 0,1);
			}

		});
	}

	if(selectedText == 2)
	{
		$.ajax
		({
			url: "/static/user_dismissed/",
			type: "POST",
			data: {"user_id": id},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				$("#myModalBox").modal('hide');
				setTimeout(function() {window.location.reload();}, 0,1);
			}

		});
	}


});



$('.selectpicker.year').change(function ()
{

	var selectedText = $(this).find("option:selected").val();

	$('button.year_ok').on('click', function(e)
	{
		$.cookie("year", selectedText);
		setTimeout(function() {window.location.reload();}, 1);

		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

		$(".modal-body").html('<h4 class="modal-title"><center>Дождитесь перезагрузки страницы....</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
	});
});



//Кнопка выбор компаний в кассе
$('.selectpicker.company').change(function ()
{
	var selectedText = $(this).find("option:selected").val();

  $('button.company_ok').show();

	$('button.company_ok').on('click', function(e)
	{
		$.cookie("company", selectedText);

    var tend = $.datepicker.formatDate('yy-mm-dd', new Date());
    var cdate = $.datepicker.formatDate('yy-mm-01', new Date());
    //var url = '/accounting/list_view/' + selectedText + '/?year='+year+'&cdate='+cdate;
    var url = '/accounting/list_view/' + selectedText + '/?&monthstart='+cdate+'&monthend='+tend;

		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		$(".modal-body").html('<h4 class="modal-title"><center>Дождитесь перезагрузки страницы....</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

    setTimeout(function() {   location.href = url;  }, 3);
	});
});


jQuery(function(f){
    var element = f('.navbar-inverse');
    f(window).scroll(function(){
        element['fade'+ (f(this).scrollTop() < 100 ? 'In': 'Out')](500);
    });
});



  $('a.turn').on('click', function(e)
    {
      if(  $('.content').hasClass('show') )
      {
        $('.content, aside').removeClass('show');
        $.cookie("show", 0);

      }
      else
      {
        $('.content, aside').addClass('show');
        $.cookie("show", 1);
      }
      e.preventDefault();
  });


  $('a.buttom').on('click', function (e)
  {
    var id = $(this).attr('data-id');

    $("#form_dropdown_new_field_58313").val(id);

    $("html, body").stop().animate({scrollTop: $('#reg_partner_design').offset().top - 80 + 'px'}, 500);
    e.preventDefault();
  });


  $('a.modal-desc').on('click', function (e)
  {

    var name = $(this).attr('data-name');
    var desc = $(this).attr('data-title');

    $('#myModalBox').modal({backdrop: 'static', keyboard: false});

    $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">' + name + '</h4></div>');
    $(".modal-body").append('<p>' + desc + '</p>');

          e.preventDefault();
    });



    $(window).scroll(function (event)
  	{

  		var table_height = $(".col-md-7.record").height();

  		var window_height = $(document).scrollTop();


  		if(window_height > table_height || ($( window ).width() < 700))
  		{
  			$('.col-md-7.record').addClass('block');
  			$('.col-md-5.comment-block-position').addClass('block');
  		}
  		else
  		{
  			$('.col-md-7.record').removeClass('block');
  			$('.col-md-5.comment-block-position').removeClass('block');
  		}
  	});

  	$(document).ready(function(e)
  	{
  		var btn_dwn = $('.btn-down');

  		$(btn_dwn).hide();

  		/* btn down */
  		$(document).on('scroll', function(e)
  		{
  			if( $(document).scrollTop() > 100 )
  			{
  				$(btn_dwn).fadeIn(300);
  			}
  			else
  			{
  				$(btn_dwn).fadeOut(300);
  			}
  		});

  		$(btn_dwn).on('click', function(e)
  		{
  			$("html, body").animate({'scrollTop': 10}, 400, "easeInSine");
  		});

  	});




  	var s = location.href;
  	url = s.split('#')[0];

  	/* Заметки к записям. */
  	jQuery(document).ready(function()
  	{


  		var postreplyid = '#' + 'replay_';


  		$('.comment-post__reply-button').on("click", function()
  		{

  			if($(this).hasClass('active'))
  			{
  				$(this).html('Ответить');
  				$('.comment-post__reply-button').removeClass('active');

  				$('#pcomment_value').val(0);
  				$('.comment-block__input-form').insertAfter('.comment-empty');
  				$('#' + 'replay_' + this.id).removeClass("active");
  			}
  			else
  			{
  				$(this).html('Отмена');
  				$(this).addClass('active');

  				$('input[name=pcomment]').val(this.id)
  				$('.comment-block__input-form').insertAfter(postreplyid + this.id);
  				$('#' + 'replay_' + this.id).addClass("active");
  			}
  			return false;
  		});






  		$('#comment-form').on("submit", function()
  		{
  			var countText = $(".cops").val().length;

  			if(countText == 0)
  			{
  				$('#myModalBox').modal({backdrop: 'static', keyboard: false});
  				$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Нам нужно написать что нибудь :(</h4></div>');

  			}
  			else
  			{

  				$.ajax(
  				{
  					url: url + '/status',
  					data: $(this).serialize(),
  					type: 'POST',
  					beforeSend: function()
  					{
  						onLoadInterfaceComment(1);
  					},
  					success: function(status)
  					{
  						onLoadInterfaceComment(1);

  						if(status.success == true)
  						{
  							$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  								$('.content').load(url + '/json',function()
  								{
  									onLoadInterfaceComment(0);

  									$("html, body").stop().animate({scrollTop: $('.comment-block').offset().top + 'px'}, 500);
  								});

  						}

  						$('.alert').html(status.message);
  					}
  				});
  			}

  			return false;
  		});

  		function onLoadInterfaceComment(status)
  		{
  			switch(status)
  			{
  				case 0 :

  					$('#comment-form textarea').prop("disabled", true);
  					$('#coment').hide();

  					$(".modal-body").html('<h4 class="modal-title"><center>Заметка успешно сохранена...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

  					setTimeout(function() {	 document.location.href= url;  }, 100);

  				break;

  				case 1 :
  					$('#comment-form textarea').prop("disabled", true);
  					$('#coment').hide();

  					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

  					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

  				break;
  			}
  		}

  	});
  	/* Заметки к записям. End*/


  	/* Добавить дополнительный ящик */
  	$('.before').on('click', function(e)
  	{

  			if($('.before').hasClass('show') )
  			{
  				$('.before').removeClass('show');
  				$(".other_email_input").fadeOut(200);
  			}
  			else
  			{
  				$('.before').addClass('show');
  				$(".other_email_input").fadeIn(200);
  			}
  	});
  	/* Добавить дополнительный ящик. End */


  	$('.reminderhtml .closes').on('click', function(e)
  	{
  		var id = $(this).attr('data-id');

  		$.ajax
  		({
  			url: "/static/reminderhtml/",
  			type: "POST",
  			data: {"id": id},
  			cache: true,
  			beforeSend: function()
  			{
  				$('#myModalBox').modal({backdrop: 'static', keyboard: false});

  				$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  			},
  			success: function(response)
  			{
  				if(response == 1)
  				{
  					$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');
  					setTimeout(function() {window.location.reload();}, 1000);
  				}
  				if(response == 0)
  				{
  					$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
  				}

  			}
  		});
  	});


  	/* Загрузить список поиска */
  	$(document).ready(function()
  	{
  		var num = 10;

  		$('.load_search').on('click', function(e)
  		{
  			var id = $(this).attr('data-id');
  			var serch = $(this).attr('data-direction');

  			$.ajax
  			({
  				url: "/static/load_search/",
  				type: "POST",
  				data: {"user_id": id, "search": serch, "num": num },
  				cache: true,
  				beforeSend: function()
  				{
  					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

  					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  				},
  				success: function(response)
  				{

  					if(response == 0)
  					{

  						$("#myModalBox").modal('hide');


  						$('.alert').html("Ничего не найдено");
  						$('.alert').animate({'opacity':'show'}, 1000);
  						$('.alert').animate({'opacity':'hide'}, 4000);
  					}
  					else
  					{
  						$(document).ready(function(){
  							$("#myModalBox").modal('hide');
  						});

  						num = num  + 10;

  						$('.res_' + id).append(response);

  						$("html, body").stop().animate({scrollTop: $('.rest_' + id).offset().top - 0 + 'px'}, 500);
  					}
  				}
  			});
  		});
  	});
  	/*  Загрузить список поиска. End */






  	/* Загрузить еще список менеджеров */

      $(document).on('click','.load_id',function(e)
  		{
  			var id = $(this).attr('data-id');
        var b_num = $(this).attr('data-num');

  			$.ajax
  			({
  				url: "/static/manager/",
  				type: "POST",
  				data: {"user_id": id, "num": b_num },
  				cache: true,
  				beforeSend: function()
  				{
  					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

  					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  				},
  				success: function(response)
  				{

    					$(document).ready(function(){
    						$("#myModalBox").modal('hide');
    					});

    					$('.list.user_' + id).html(response);
  				}

  			});

  		});

  	/*  Загрузить еще список менеджеров. End */



  	/* Загрузить еще. Моя активность */
  	$(document).ready(function()
  	{
  		var num = 10;
  		$('.load_activ').on('click', function(e)
  		{
  			var id = $(this).attr('data-id');

  			$.ajax
  			({
  				url: "/static/load_activ/",
  				type: "POST",
  				data: {"user_id": id, "num": num },
  				cache: true,
  				beforeSend: function()
  				{
  					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

  					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  				},
  				success: function(response)
  				{
  					$(document).ready(function(){
  						$("#myModalBox").modal('hide');
  					});

  					$('table.users.activS').append(response);

  					num = num  + 10;

  					$('.static > .rebut').html(num);

  					$("html, body").stop().animate({scrollTop: $('.SPDOWN').offset().top - 0 + 'px'}, 500);

  				}

  			});
  		});
  	});
  	/* Загрузить еще. Моя активность. End*/



      	/* Загрузить еще. Мои извещения */
      	$(document).ready(function()
      	{
      		var num = 10;
      		$('.load_notifications').on('click', function(e)
      		{
      			var id = $(this).attr('data-id');

      			$.ajax
      			({
      				url: "/static/load_notifications/",
      				type: "POST",
      				data: {"user_id": id, "num": num },
      				cache: true,
      				beforeSend: function()
      				{
      					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

      					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
      				},
      				success: function(response)
      				{
      					$(document).ready(function(){
      						$("#myModalBox").modal('hide');
      					});

      					$('table.notifications').append(response);

      					num = num  + 10;

      					$('.static.notifications > .rebut_notifications').html(num);

      					$("html, body").stop().animate({scrollTop: $('.SPDOWN').offset().top - 0 + 'px'}, 500);

      				}

      			});
      		});
      	});
      	      	/* Загрузить еще. Мои извещения */




    /* Загрузить еще. Важнаям моя активность */
  	$(document).ready(function()
  	{
  		var numi = 10;
  		$('.load_activ_important').on('click', function(e)
  		{
  			var id = $(this).attr('data-id');

  			$.ajax
  			({
  				url: "/static/load_activ_important/",
  				type: "POST",
  				data: {"user_id": id, "num": numi },
  				cache: true,
  				beforeSend: function()
  				{
  					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

  					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  				},
  				success: function(response)
  				{
  					$(document).ready(function(){
  						$("#myModalBox").modal('hide');
  					});

  					$('table.users.activS_important').append(response);

  					numi = numi  + 10;

  					$('.more_important > .rebut').html(numi);

  					$("html, body").stop().animate({scrollTop: $('.SPDOWN').offset().top - 0 + 'px'}, 500);

  				}

  			});
  		});
  	});
  	/* Загрузить еще. Важнаям моя активность */


    // Множественое удаление заметок
    $(document).on('click','.del_note',function(e)
    {
      var type = $(this).attr('data-direction');
      var values = []; {	$('.form-check-input:checked').each(function() {	values.push($(this).val());	});

      if(values.length == 0){return false;}

      $('#myModalBox').modal({backdrop: 'static', keyboard: false});
      $(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
      $(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите удалить заметки ('+values.length+' шт.)?</center></h4>');
      $(".modal-body").append('<center><button style="margin-top:10px;" type="button" class="btn hover">Да</button> <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

    /* Да. Удалить заметку */
      $('.btn.hover').on('click', function(e)
      {
        $.ajax
        ({
          url: "/static/checked/",
          type: "POST",
          data: {"id": values, "type": type},
          cache: true,
          success: function(response)
          {
            if(response == 1)
            {
              $(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
              setTimeout(function() {window.location.reload();}, 1000);
            }

            if(response == 0)
            {
              $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Ничего не выбрано!</h4></div>');
            }
          }
        });

      });
      }
    });
    // Множественое удаление заметок

    // Удалить артикул в скидке
    $(document).on('click','.del_face_vender',function(e)
    {
      var id = $(this).attr('data-id');

      $.ajax
      ({
        url: "/static/del_face_vender/",
        type: "POST",
        data: {"id": id},
        cache: true,
          beforeSend: function()
          {
            $('#myModalBox').modal({backdrop: 'static', keyboard: false});
            $(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
          },
          success: function(response)
          {
            if(response == 1)
            {
              $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');
              setTimeout(function() {window.location.reload();}, 1000);
            }
          }
      });

      e.preventDefault();
    });
    // Удалить артикул в скидке



    // TRANSFER заметок
    $('.edit_comment').on('click', function(e)
    {
      var id = $(this).attr('data-id');

      $('#myModalBox').modal({backdrop: 'static', keyboard: false});

      $.ajax
      ({
        url: "/static/edit_comment/",
        type: "POST",
        data: {"id": id},
        cache: true,
        beforeSend: function()
        {
          $(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
        },
        success: function(response)
        {
          if(response == 0)
          {
            $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Создайте сделку :(</h4></div>');
          }
          else
          {

            $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Перенос заметки</h4></div>');
            $(".modal-body").append('<p><input type="hidden" class="form-control id" value="' +  id  + '"></p>');

            $(".modal-body").append('<select class="selectpicker show-tick time" title="Выберите сделку">' +  response  + '</select>');


            $(".modal-body").append('<p><button type="button" class="btn btn-primary">Продолжить</button></p><br clear="all">');

            $('.selectpicker').selectpicker();

            $('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
            {
              var id = $('input.form-control.id').val();
              var deal = $('.selectpicker.time').val();

                $.ajax
                ({
                  url: "/static/edit_comment_2/",
                  type: "POST",
                  data: {"id": id, "deal": deal},
                  cache: true,
                    beforeSend: function()
                    {
                      $(".modal-body").html('<h4 class="modal-title"><center>Переносим...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
                    },
                    success: function(response)
                    {

                      if(response == 1)
                      {
                        $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');

                        setTimeout(function() {window.location.reload();}, 1000);

                      }
                      if(response == 0)
                      {
                        $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
                      }
                    }
                });

            });


          }

        }
      });

      e.preventDefault();

    });
    // TRANSFER заметок



    $(document).ready(function ()
    {
    	var navbox = $('.nav-tabs');

        navbox.on('click', 'a', function (e)
    	{
          var $this = $(this);

          e.preventDefault();
          window.location.hash = $this.attr('href');
          $this.tab('show');


        });

        function refreshHash()
    	{
    		navbox.find('a[href="'+window.location.hash+'"]').tab('show');

        }

        $(window).bind('hashchange', refreshHash);


        if(window.location.hash)
    	{

          refreshHash();
        }

    });










    	/* Сформировать данные */
    	$('.mailogo.archiv').on('click', function(e)
    	{
    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');


    		$(".modal-body").append('<h4 class="modal-title"><center>Выберите данные которые необходимо сформировать.</center></h4>');

    		$(".modal-body").append('<center class="archivS"> <button style="margin-top:10px; margin-left:10px;"  data-rel="6" type="button" class="btn btn-default">Телефоны в формате .VCF</button>   <button style="margin-top:10px; margin-left:10px;"  data-rel="2" type="button" class="btn btn-default">Список E-mail</button>	<button style="margin-top:10px; margin-left:10px;"  data-rel="5" type="button" class="btn btn-default">Печать</button>	</center>');


    			$('.archivS > button').on('click', function(e)
    			{

    				var id = $(this).attr('data-rel');

    				if(id == 6)
    				{
    					//window.open(location.href+'&add='+id, '_blank');
    				}

    				if(id == 5 || id == 2)
    				{
    					window.open(location.href+'&add='+id, '_blank');
    				}
    				else
    				{

    					window.location.href = location.href+'&add='+id+'/json';
    					/*

    					if($('.content').load(location.href+'&add='+id+'/json'))
    					{
    						$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
    						window.location.reload();
    					}
    					*/
    				}

    			});

    		e.preventDefault();
    	});
    	/* Сформировать данные. End */


    	/* Заметка для всех */
    	$('.mailogo.remark').on('click', function(e)
    	{
    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
    		$(".modal-body").append('<h4 class="modal-title"><center>Текст заметки для всех запсией.</center></h4>');
    		$(".modal-body").append('<textarea class="form-control" rows="2" style="margin-top:7px;" ></textarea>');
    		$(".modal-body").append('<center class="markS"><button style="margin-top:10px; margin-left:10px;"  data-rel="2" type="button" class="btn btn-default">Отправить</button></center>');


    			$('.markS > button').on('click', function(e)
    			{
    				var text = $('textarea.form-control').val();

    				$.ajax
    				({
    					url: location.href+'&add=3/json',
    					type: "GET",
    					data: {"text": text},
    					cache: true,
    						beforeSend: function()
    						{
    							$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
    						},
    						success: function(response)
    						{
    							response = $.parseJSON(response);

    							if(response.status == 1)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">' + response.text + '</h3></center>');
    								//$('.content').load(url + '/json');
    								window.location.reload();
    							}
    							if(response.status == 0)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');
    								//$('.content').load(url + '/json');
    								window.location.reload();
    							}


    						}
    				});

    				e.preventDefault();



    				/* if($('.content').load(location.href+'&add=3/json'))
    				{
    					//$(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
    					//window.location.reload();
    				}
    				*/



    			});

    		e.preventDefault();
    	});
    	/*  Заметка для всех. End */



    	/* Удаление физ. лица */
    	$('.delete_face').on('click', function(e)
    	{
    		var id = $(this).attr('data-id');

    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
    		$(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите удалить физ. лицо?</center></h4>');
    		$(".modal-body").append('<center><button style="margin-top:10px;    padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Да</button> &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

    			$('.btn.btn-primary.submit').on('click', function(e)
    			{
    				var text = $('textarea.form-control').val();

    				$.ajax
    				({
    					url: "/static/dell_face/",
    					type: "POST",
    					data: {"record": id },
    					cache: true,
    						beforeSend: function()
    						{
    							$('.form-control').removeProp("disabled");
    							$('button.btn btn-primary').removeProp("disabled");
    						},
    						success: function(response)
    						{
    							if(response == 1)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">Физ. лицо удалено!</h3></center>');
    								window.location.href = "/person/";
    							}
    							if(response == 0)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <br clear="all"><center><h3 class="modal-title">Физ. лицо не удалено :(<br> Что то пошло не так. Перезагрузите страницу и попробуйте снова.</h3></center>');
    							}

    						}
    				});

    				e.preventDefault();
    			});

    		e.preventDefault();
    	});
    	/* Удаление физ. лица End */



    	/* Удаление записи */
    	$('.delete').on('click', function(e)
    	{
    		var id = $(this).attr('data-id');

    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
    		$(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите удалить запись?</center></h4>');
    		$(".modal-body").append('<center><button style="margin-top:10px;    padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Да</button> &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

    			$('.btn.btn-primary.submit').on('click', function(e)
    			{
    				var text = $('textarea.form-control').val();

    				$.ajax
    				({
    					url: "/static/record/",
    					type: "POST",
    					data: {"record": id },
    					cache: true,
    						beforeSend: function()
    						{
    							$('.form-control').removeProp("disabled");
    							$('button.btn btn-primary').removeProp("disabled");
    						},
    						success: function(response)
    						{
    							if(response == 1)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <br clear="all"><center><h3 class="modal-title">Ваша запись удалена!</h3></center>');
    								window.location.href = "/";
    							}
    							if(response == 0)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <br clear="all"><center><h3 class="modal-title">Запись не удалена :(<br> Что то пошло не так. Перезагрузите страницу и попробуйте снова.</h3></center>');
    							}

    						}
    				});

    				e.preventDefault();
    			});

    		e.preventDefault();
    	});
    	/* Удаление записи. End */


    	/* Удаление сделки */
    	$('.delete_deal').on('click', function(e)
    	{
    		var id = $(this).attr('data-id');

    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
    		$(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите удалить сделку?</center></h4>');
    		$(".modal-body").append('<center><button style="margin-top:10px;    padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Да</button> &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

    			$('.btn.btn-primary.submit').on('click', function(e)

    			{
    				var text = $('textarea.form-control').val();

    				$.ajax
    				({
    					url: "/static/record_deal/",
    					type: "POST",
    					data: {"record": id },
    					cache: true,
    						beforeSend: function()
    						{
    							$('.form-control').removeProp("disabled");
    							$('button.btn btn-primary').removeProp("disabled");
    						},
    						success: function(response)
    						{
    							if(response == 1)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">Ваша сделка удалена!</h3></center>');
    								history.go(-1);
    							}
    							if(response == 0)
    							{
    								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">Сделка не удалена :(<br>Необходимо сначало удалить все заметки.</h3></center>');
    							}

    						}
    				});

    				e.preventDefault();

    			});

    		e.preventDefault();

    	});
    	/* Удаление сделки. End */



        /* Загрузить заметки */
      	$(document).ready(function()
      	{
      		var num = 20;
      		$('.comment-more').on('click', function(e)
      		{
      			var id = $(this).attr('data-id');
            var pid = $(this).attr('data-pid');
      			$.ajax
      			({
      				url: "/comments",
      				type: "POST",
      				data: {"id": id, "pid": pid, "num": num },
      				cache: true,
      				beforeSend: function()
      				{
      					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

      					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
      				},
      				success: function(response)
      				{
                if(response == '')
                {
                  $(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
          				$(".modal-body").append('<h4 class="modal-title"><center>Больше нет заметок :(</center></h4>');
                  $(".comment-more").remove();


                }
                else
                {
      	             $(document).ready(function(){	$("#myModalBox").modal('hide');		});
                  	 $('.comment-item').append(response);
                     num = num  + 20;
                }
      					//$("html, body").stop().animate({scrollTop: $('.SPDOWN').offset().top - 0 + 'px'}, 500);
      				}
      			});
      		});
      	});
        /* Загрузить заметки */


      	/* Удаление заметки */
        $(document).on('click','.detelecoment',function(e)
      	{
      		var id = $(this).attr('data-id');

      		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

      		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
      		$(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите удалить заметку?</center></h4>');
      		$(".modal-body").append('<center><button style="margin-top:10px;    padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Да</button> &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

      			$('.btn.btn-primary.submit').on('click', function(e)
      			{
              $(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

              $.ajax
      				({
      					url: "/static/detelecoment/",
      					type: "POST",
      					data: {"record": id },
      					cache: true,

      						success: function(response)
      						{
                    response = $.parseJSON(response);

      							if(response.status == 1)
      							{
      								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">Ваша заметка удалена!</h3></center>');
      										setTimeout(function() {window.location.reload();}, 1);
      							}
      							else
      							{
      								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">Заметка не удалена :(<br> Что то пошло не так. Перезагрузите страницу и попробуйте снова.</h3></center>');
      							}

      						}
      				});

      				e.preventDefault();
      			});

      		e.preventDefault();
      	});
      	/* Удаление заметки. End */




    	/* Выбор месяца. Премия статистика*/
    	$('.dropdown-menu.premium>li>a').on('click', function(e)
    	{
    		var id = $(this).attr('data-id');
    		var user = $(this).attr('data-user');

    		$.cookie("premium", id);

    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<h4 class="modal-title"><center>Необходимо перезагрузить страницу. Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

    		setTimeout(function() {window.location.reload();}, 1);

    		e.preventDefault();

    	});
    	/* Выбор месяца. Премия статистика. End */


    	/* Выбор года. Премия статистика*/
    	$('.dropdown-menu.year_money>li>a').on('click', function(e)
    	{
    		var id = $(this).attr('data-id');
    		var user = $(this).attr('data-user');

    		$.cookie('year_money', null);

    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<h4 class="modal-title"><center>Необходимо перезагрузить страницу. Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

    		$.cookie("year_money", id);

    		setTimeout(function() {window.location.reload();}, 1);

    		e.preventDefault();

    	});
    	/* Выбор года. Премия статистика. End */


    	/* off crm */
    	$('.off_crm').on('click', function(e)
    	{
    		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

    		$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

    		$.ajax({url: "/static/off_crm/"});

    		setTimeout(function() {location.href = '/';}, 3000);

    		e.preventDefault();

    	});
    	/* off crm  */








    	/* Установить задачу */
    	$('a.off').on('click', function(e)
    	{
    		if(  $('.task').hasClass('show') )
    		{
    		 	$('.task').removeClass('show');
    			$('.window-task').removeClass('show');
    			$('input#task').val(0);
    		}
    		else
    		{
    			$('.task').addClass('show');
    			$(".window-task").addClass('show');
    			$('input#task').val(1);

    			$('.selectpicker').selectpicker();
    			isotope();

    				$('#comment-form > textarea').keyup(function()
    				{
    					var curLength = $('#comment-form > textarea').val().length;         //(2)

    					var minLength = 100;

    					var remaning = minLength - curLength;


    					if (curLength > minLength)                                           //(5)
    					{
    						$('#textareaFeedback').removeClass('warning');
    						$('#comment-form > textarea').removeClass('warning');
    					}
    					else
    					{
    						$('#textareaFeedback').addClass('warning');
    						$('#comment-form > textarea').addClass('warning');
    					}


    					if(minLength >= curLength )
    						$('#textareaFeedback').html(remaning);
    					else
    						$('#textareaFeedback').html('');
    				});
    		}

    		e.preventDefault();

    	});
    	/* Установить задачу. End */






    $('.auth').on('click', function(e)
    	{
    		if(  $('.login').hasClass('show') )
    		{
    		 	$('.login').removeClass('show');
    		}
    		else
    		{
    			$('.login').addClass('show');
    		}

    		e.preventDefault();

    	});




    	/* Количество вводимых символов для textarea. */
    	$(function()
    	{
    		$('#textarea').keyup(function()
    		{
    			var curLength = $('#textarea').val().length;         //(2)

    			var minLength = 80;

    			var remaning = minLength - curLength;


    			if (curLength > minLength)                                           //(5)
    			{
    				$('#textareaFeedback').removeClass('warning');
    				$('#textarea').removeClass('warning');
    				$('.btn-danger').show();
    			}
    			else
    			{
    				$('#textareaFeedback').addClass('warning');
    				$('#textarea').addClass('warning');
    				$('.btn-danger').hide();
    			}


    			if(minLength >= curLength )
    				$('#textareaFeedback').html(remaning + ' осталось символов');
    			else
    				$('#textareaFeedback').html('');
    		})
    	})
    		/* End. Количество вводимых символов для textarea. */






    	$("#imgLoad").hide();
    	$("#imgLoadS").hide();


    	/* Фильтр. Скрыть интересы при типе конкурента 6 */
    	$('#type option:selected').each(function()
    	{
    		if($(this).val() == 6)
    		{
    			$("#type").val(6);
    			$("#type_company").hide();
    		}
    	});
    	/* Фильтр. Скрыть интересы при типе конкурента 6. End */



    	/* Фильтр. Выбор местонахождение */
    	$("#country").change(function()
    	{
    		if($(this).val() == 0)
    		{
    			$("form.response").append('<div class="fdfdfd"><input name="city" type="hidden" value="0"></div>');
    		} else {	$(".fdfdfd").html('');	}

    		$("#imgLoad").show();
    		$(".selajax").hide();
    		$(".okcity").hide();

    		$.ajax
    		({
    			url: "/static/city/",
    			type: "POST",
    			data: {"country": $(this).val()},
    			cache: true,
    			success: function(response)
    			{
    				if(response == 0)
    				{
    					$("#result").html('');
    					$("#imgLoad").hide();
    				}
    				else
    				{
    					$(".selajax").hide();
    					$("#result").html(response);
    					$('.selectpicker').selectpicker();
    					$("#imgLoad").hide();

    				}
    			}
    		});
    	});
    	/* Фильтр. Выбор местонахождение. End */


    	/* Фильтр. Выбор менеджеров */
    	$("#manager").change(function()
    	{
    		$("#imgLoadCompany").show();
    		$("#resultCompany").hide();
    		$(".refresh").hide();

    		$.ajax
    		({
    			url: "/static/company/",
    			type: "POST",
    			data: {"company": $(this).val()},
    			cache: true,
    			success: function(response)
    			{
    				if(response == 0)
    				{
    					$("#resultCompany").html('');
    					$(".refresh").html('');
    					$("#imgLoadCompany").hide();
    				}
    				else
    				{
    					$(".refresh").html('');
    					$("#resultCompany").show();
    					$("#resultCompany").html(response);
    					$('.selectpicker').selectpicker();
    					$("#imgLoadCompany").hide();

    				}
    			}
    		});

    	});
    	/* Фильтр. Выбор менеджеров . End */






    	/* Фильтр. Выбор периода */
    	$("#cdate").change(function()
    	{
    		$("#imgLoadS").show();
    		$("#cdatehide").hide();

    		$.ajax
    		({
    			url: "/static/cdate/",
    			type: "POST",
    			data: {"position": $(this).val()},
    			cache: true,
    			success: function(response)
    			{
    				if(response == 0)
    				{

    					$("#imgLoadS").hide();
    					$("#resultS").html(response);
    					isotope();

    				}
    				else
    				{

    					$("#imgLoadS").hide();
    					$("#resultS").html(response);
    					isotope();
    				}
    			}
    		});

    	});
    	/* Фильтр. Выбор периода. End */




      	/* Удаляем штраф админом. Обновляем блок. End*/
      	$('.delpenalty').on('click', function(e)
      	{
      		var id = $(this).attr('data-id');

      		$.ajax
      		({
      			url: "/static/delpenalty/",
      			type: "POST",
      			data: {"record": id},
      			cache: true,
      				beforeSend: function()
      				{
      					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

      					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
      				},
      				success: function(response)
      				{
      					window.location.reload();
      				}
      		});

      		e.preventDefault();
      	});
      	/* Удаляем штраф админом. Обновляем блок. End*/






    $(document).on('click','.form-check-input',function(e)
    {
      $(".select_all_div").html('<a href="#" onclick="do_this()" class="select_all">Выбрать все</a>');
      $(".select_all_div").append('<a class="tran_zm transfer" data-direction="transfer">Transfer</a>');
      $(".select_all_div").append('<a class="del_zm del_note" data-direction="delete">Удалить</a>');
    });

    //Выбор заметок checked
    function do_this()
    {
          var checkboxes = document.getElementsByName('approve[]');

          if($(".select_all").html() == 'Выбрать все')
          {
              for (var i in checkboxes)
              {
                  checkboxes[i].checked = 'FALSE';
              }
              $(".select_all_div").html('<a href="#" onclick="do_this()" class="select_all">Выбрано</a>');
              $(".select_all").html('Выбрано');
              $(".select_all_div").append('<a class="tran_zm transfer" data-direction="transfer">Transfer</a>');
              $(".select_all_div").append('<a class="del_zm del_note" data-direction="delete">Удалить</a>');
          }
          else
          {
              for (var i in checkboxes)
              {
                  checkboxes[i].checked = '';
              }
              $(".select_all_div").html('<a href="#" onclick="do_this()" class="select_all">Выбрать все</a>');
              $(".select_all").html('Выбрать все');
              $('.select_all_div .del_zm').remove();
              $('.select_all_div .tran_zm').remove();
          }
          e.preventDefault();
      }
      //Выбор заметок checked
      //Выбор заметок checked, отправка на обработчик
      $('.select_all').on('click', function(e)
      { e.preventDefault(); });


      //мой профиль. скопировать сумму самоначисления
      $('.view-money').on('click', function(e)
      {
        var text = $('a.view-money').text();

        navigator.clipboard.writeText(text).then(() => {	$('.alert').html('Текст скопирован');	$('.alert').animate({'opacity':'show'}, 1000);	$('.alert').animate({'opacity':'hide'}, 4000);	  })
        .catch(err => {console.log('Something went wrong', err); });
        e.preventDefault();
      });


      //Заметка. скопировать ссылку
      $('.copy-link').on('click', function(e)
      {
        var link = $(this).attr('data-id');
        navigator.clipboard.writeText(link).then(() => {	$('.alert').html('Ссылка скопирована!');	$('.alert').animate({'opacity':'show'}, 1000);	$('.alert').animate({'opacity':'hide'}, 4000);	  })
        .catch(err => {console.log('Something went wrong', err); });
        e.preventDefault();
      });

      //Заметка. скопировать ссылку
      $('.copy_mobile').on('click', function(e)
      {
        var link = $(this).attr('data-id');
        navigator.clipboard.writeText(link).then(() => {	$('.alert').html('Номер телефона скопирован!');	$('.alert').animate({'opacity':'show'}, 1000);	$('.alert').animate({'opacity':'hide'}, 4000);	  })
        .catch(err => {console.log('Something went wrong', err); });
        e.preventDefault();
      });


      //Заметка. скопировать текст заметки
      $('.copy-text').on('click', function(e)
      {
        var id = $(this).attr('data-id');
        var text = $('#comment-post_'+id+' .view-text').html();
        var result = text.replace(/(<br>)*/g, '');
        navigator.clipboard.writeText(result).then(() => {	$('.alert').html('Текс замеки скопирован!');	$('.alert').animate({'opacity':'show'}, 1000);	$('.alert').animate({'opacity':'hide'}, 4000);	  })
        .catch(err => {console.log('Something went wrong', err); });
        e.preventDefault();
      });



    function isotope()
  	{
  		$.datepicker.regional['ru'] = {monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'], monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'], dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'], dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'], dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'], dateFormat: 'dd-mm-yy', initStatus: '', isRTL: false, firstDay:1};

  		var type  = {changeMonth: true,changeYear: true,maxDate:0,yearRange: "-70:+0",dateFormat: "yy-mm-dd"};
  		var TypePrevious  = {changeYear: true,changeMonth: true,dateFormat: "yy-mm-dd"};
      var TypePreviousOn  = { changeYear: true, changeMonth: true,minDate: 0, dateFormat: "yy-mm-dd"};
      var TypePeriod  = { dateFormat: "dd-mm-yy"};
      var TypeCashbox  ={changeMonth: true,changeYear: true,maxDate:0,yearRange: "-70:+0",dateFormat: "dd.mm.yy"};

  /* $('#off-task').datepicker({ changeYear: true, changeMonth: true,minDate: 0 ,dateFormat: 'yy-mm-dd', onSelect: function(datetext){ var d = new Date(); datetext=datetext+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds(); $('#off-task').val(datetext);}, });
  		 */

  		$('#off-task').datepicker({ changeYear: true, changeMonth: true,minDate: 0 ,dateFormat: 'yy-mm-dd 19:00:00' });

  		$("#cdatestart").datepicker(type);
  		$("#cdateoff").datepicker(type);
  		$("#type-vse-date").datepicker(type);
  		$("#name_client_cdata").datepicker(type);
  		$(".form-control.Ondate").datepicker(TypePrevious);
      $(".bcdate").datepicker(type);
      $("#cashbox").datepicker(TypeCashbox);


  		$.datepicker.setDefaults($.datepicker.regional['ru']);
  	}





    	isotope();
