	</div>  
      
</div>




<footer class="container-fluid  navbar-fixed-bottom">
	<div class="row">
		 <div class="col-md-3">
         	<a target="_blank" href="http://led.ru/"><img alt="" class="home" src="/tpl/img/logo-info.png"></a>
         </div>
         
		 <div class="col-md-9">
         	<p></p>
         </div>
	</div>           
</footer>

<script type="text/javascript">

	var s = location.href;
	url = s.split('#')[0];

	/* Заметки к записям. */
	jQuery(document).ready(function()
	{
		jQuery('#comment-form').live("submit", function()
		{	

			jQuery.ajax(
			{				
				url: url + '/status',
				data: jQuery(this).serialize(),
				type: 'POST',
				beforeSend: function() 
				{					
					onLoadInterfaceComment(1);
				},
				success: function(status) 
				{
					onLoadInterfaceComment(0);
				
					if(status.success == true)
					{
						$('.content').load(url + '/json');
						$("html, body").stop().animate({scrollTop: $('.comment-block').offset().top + 'px'}, 500);
					}
								
					jQuery('.alert').html(status.message);
				}
			});			
		
			return false;
		});
		
		function onLoadInterfaceComment(status)
		{
			switch(status)
			{
				case 0 :
				
					$('#coment').show();
					$('#comment-form textarea').removeProp("disabled");
					$('.ajax-loader').remove();

					
					jQuery('.alert').animate({'opacity':'show'}, 1000);					
					jQuery('.alert').animate({'opacity':'hide'}, 4000);	
					
				break;
				
				case 1 :
					$('#comment-form textarea').prop("disabled", true);	
					$('#coment').hide();			
					$('#coment').after('<img class="ajax-loader" style=" float:right;margin-top:10px;" src="/tpl/img/loading.gif" />');
				break;
			}
		}	

	});
	/* Заметки к записям. End*/
	
	
	/* Добавить дополнительный ящик */
	$('.before').live('click', function(e)
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






	/* Кнопка в шапке, поставить задачу */
	$('#add_taks_head').live('click', function(e)
	{
		var id = $(this).attr('data-id');

		if(id == 0)
		{
		
			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});
						
			$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Менеджеру необходимо в профайле указать запись где будут устанавливать задачи.</center></h4></div>');

		}
		else
		{
			window.location.href = "/record/" + id + "#add_taks";
		}

		

		e.preventDefault();			
	});
	/* Кнопка в шапке, поставить задачу */






	/* Добавить напоменания */
	$('#reminder').live('click', function(e)
	{	
		var id = $(this).attr('data-rel');
		
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});
		
		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Создать напоминание</h4></div>');
		$(".modal-body").append('<p><input type="text" class="form-control name" placeholder="Описание"></p>');
		
		$(".modal-body").append('<div id="form-control-Feedback"></div>');
		
		
		$(".modal-body").append('<p><input type="text" class="form-control Ondate" value="<?=date('Y-m-d');?>">');
		
		$(".modal-body").append('<select class="selectpicker show-tick time" title="Выберите время"><option value="0" selected>00:00</option><option value="1">01:00</option><option value="2">02:00</option><option value="3">03:00</option><option value="4">04:00</option><option value="5">05:00</option><option value="6">06:00</option><option value="7">07:00</option><option value="8">08:00</option><option value="9">09:00</option><option value="10">10:00</option><option value="11">11:00</option><option value="12">12:00</option><option value="13">13:00</option><option value="14">14:00</option><option value="15">15:00</option><option value="16">16:00</option><option value="17">17:00</option><option value="18">18:00</option><option value="19">19:00</option><option value="20">20:00</option><option value="21">21:00</option><option value="22">22:00</option><option value="23">23:00</option></select>');		

		$(".modal-body").append('<select class="selectpicker show-tick manager list-view-manager" multiple="multiple" data-live-search="true"  title="Выберите менеджера"><option value="0">Выберите менеджер</option><? $users = SelectDataArray('users', 0, 'id DESC'); for ($i=0; $i<sizeof($users); $i++){ ?> <option value="<?=$users[$i]->id;?>"><?=$users[$i]->name;?></option> <? } ?></select>');

		$(".modal-body").append('<select class="selectpicker show-tick alertS" title="Оповещение"><option value="0" selected>Оповещение E-mail</option><option value="1">Оповещение E-mail + СМС</option></select></p>');		

		$(".modal-body").append('<p><button type="button" class="btn btn-primary" style="display:none" >Сохранить</button></p><br clear="all">');

		$('.selectpicker').selectpicker();
		
		isotope();

		
			$('.form-control.name').keyup(function()
			{
				var curLength = $('.form-control.name').val().length;         //(2)
			
				var minLength = 10;
				
				var remaning = minLength - curLength;
				
				
				
				if (curLength > minLength)                                           //(5)
				{
					$('#form-control-Feedback').removeClass('warning');
					$('.form-control.name').removeClass('warning');
					$('.modal-body .btn.btn-primary').show();

					
					$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
					{	
						var text = $('input.form-control.name').val();
						var date = $('input.form-control.Ondate').val();
						var time = $('select.selectpicker.time').val();
						var manager = $('.list-view-manager').val();
						var status = $('select.selectpicker.alertS').val();

						if(manager == null)
						{
							alert("Выберите менеджера");	
						}
						else
						{												
							$.ajax
							({
	
								url: "/static/reminder/", 
								type: "POST",      
								data: {"coment_id": id, "text": text, "date": date, "time": time , "manager": manager , "status": status },
								cache: true,			
									beforeSend: function() 
									{
										$(".modal-body").html('<h4 class="modal-title"><center>Создание напоминания, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
									},
									success: function(response) 
									{
										response = $.parseJSON(response);
										
										$('input[name=reminder]').val($('input[name=reminder]').val() + ',' + response.coment_id);
										$('.input-form__reminder').append('<div class="reminder"><strong>' + response.date_start + ' в ' + response.time_start + ' - ' + response.name + '</strong></div>');
			
										
										$(document).ready(function(){
											$("#myModalBox").modal('hide');
										});
										
										$('.modal-body').html('');
										
									}	
							});
							e.preventDefault();	
							
						}
					});
						
				}
				else
				{
					$('#form-control-Feedback').addClass('warning');
					$('.form-control.name').addClass('warning');
					$('.modal-body .btn.btn-primary').hide();
				}
				
					if(minLength >= curLength )
					{
						$('#form-control-Feedback').html(remaning + ' осталось символов');	
					}
					else
					{
						$('#form-control-Feedback').html('');
					}
			});
		
	});	
	/* Добавить напоменания. End*/	


	/* Задача выполнена. Отправляем на модерацию. */
	$('.upstatustaks').live('click', function(e)
	{
		var id = $(this).attr('data-id');
		
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Ваш результат</h4></div>');
		$(".modal-body").append('<p><textarea class="form-control text" rows="2" style="margin-bottom:15px;"></textarea> <div id="textareaFeedback" class="ps_1"></div></p>');
		$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Отправить</button></p>');
		
		
			/* Да. Отправить введеный текст */
			$('.btn.submit').live('click', function(e)
			{	
				var textarea = $('.form-control.text').val();
				
				$.ajax
				({
					url: "/static/upstatusresponse/", 
					type: "POST",      
					data: {"record": id, "text": textarea,},
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
								$('.content').load(url + '/json');

							}							
							if(response.status == 0)
							{
								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');	
								
							}
							
						}
				});
				
				e.preventDefault();
			
			});			

		e.preventDefault();			
	});
	/* Задача выполнена. Отправляем на модерацию. End */


	/* Задача выполнена. Модерируем. */
	$('.upmodernstatustaks').live('click', function(e)
	{
		var id = $(this).attr('data-id');
		
		$.ajax
		({
			url: "/static/upmodernstatustaks/", 
			type: "POST",      
			data: {"record": id},
			cache: true,			
				beforeSend: function() 
				{	
					$(document).ready(function(){
						$("#myModalBox").modal('show');
					});
								
					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 
				},
				success: function(response) 
				{
					
					var str = url + '/json';
					
					$('.content').load(str);

					$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Вы подтвердили выполнение задачи!</center></h4></div>');

				}
		});

		e.preventDefault();			
	});
	/* Задача выполнена. Модерируем. End*/
	

	/* Сформировать данные */
	$('.mailogo').live('click', function(e)
	{
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});
					
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$(".modal-body").append('<h4 class="modal-title"><center>Выберите какие данные сформировать?</center></h4>');
		$(".modal-body").append('<center class="datares"><button style="margin-top:10px; margin-right:10px;" data-rel="1" type="button" class="btn btn-default">E-mail</button> <button style="margin-top:10px; margin-left:10px;"  data-rel="2" type="button" class="btn btn-default">Телефоны</button></center>');


			/* Да. Удалить заметку */
			$('.datares > button').live('click', function(e)
			{	
				var type = $(this).attr('data-rel');

				if($('.content').load(location.href + '&add=' + type +'/json'))
				{
					window.location.reload();
				}
				
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 

			});
		
		e.preventDefault();			
	});
	/* Сформировать данные. End */



	/* Удаление заметки */
	$('.DeteleComent').live('click', function(e)
	{
		var id = $(this).attr('id');
		    
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});

		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите удалить заметку?</center></h4>');
		$(".modal-body").append('<center><button style="margin-top:10px;" type="button" class="btn hover">Да</button> <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

			/* Да. Удалить заметку */
			$('.btn.hover').live('click', function(e)
			{	
				$.ajax
				({
					url: "/static/detelecoment/", 
					type: "POST",      
					data: {"record": id},
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
								$('.content').load(url + '/json');
								
								$(document).ready(function(){
									$("#myModalBox").modal('hide');
								});
								
								$('.modal-body').html('');

							}							
							if(response.status == 0)
							{
								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');
																
								$(document).ready(function(){
									$("#myModalBox").modal('hide');
								});
								
								$('.modal-body').html('');
							}
							
						}
				});
				
				e.preventDefault();
			
			});

		e.preventDefault();			
	});
	/* Удаление заметки. End */

	/* Удаление записи */
	$('.delete').live('click', function(e)
	{
		var id = $(this).attr('data-rel');
			
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});
					
		$(".modal-body").html('<h4 class="modal-title">Опишите причину по которой вы хотите удалить запись. <br><span style="font-size: 14px;">Если Вы хотите удалить одну из дублируемых записей, убедитесь что вся информация из удаляемой записи скопирована и помещена в уже существующею запись!</span></h4>');
		$(".modal-body").append('<textarea class="form-control" rows="2" style="margin-top:7px;" ></textarea> <button style="margin-top:10px;" type="submit" class="btn btn-primary submit">Отправить</button>');

	
			$('.btn.btn-primary.submit').live('click', function(e)

			{	
				var text = $('textarea.form-control').val();
				
				$.ajax
				({
					url: "/static/record/", 
					type: "POST",      
					data: {"record": id,"text": text, },
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
								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <br clear="all"><center><h3 class="modal-title">Ваша заявка на удаление записи отправлена модератору базы, в ближайшее время она будет рассмотрена!</h3></center>');
							}
							if(response == 0)
							{
								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <br clear="all"><center><h3 class="modal-title">Заявка, не отправлена :(<br> Что то пошло не так. Перезагрузите страницу и попробуйте снова.</h3></center>');
							}
							
						}
				});
				
				e.preventDefault();
			
			});
		
		e.preventDefault();

	});
	/* Удаление записи. End */



	$('a.off').live('click', function(e)
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
		
		
		

	/* Всплывающая подсказка. */
	var timeout,
	proccessing = false;
	onTimeout = function()
	{
		
		$(".loadnemes").html('<img src="/tpl/img/loading-names.gif"> Идет загрузка');
		$(".loadnemes").show(100);
		
		proccessing = true;
		
		$.ajax
		({
			url: "/static/name/", 
			type: "POST",      
			data: {"name": $(".namevalue").val()},
			cache: true,			
			success: function(response)
			{	
				if(response == 0)
				{
					$(".loadnemes").html('<span style="color:#0026FF;">Совпадений не найдено!</span>');
					$('.loadnemes').animate({'opacity':'hide'}, 4000);		
				}
				else
				{
					$(".loadnemes").html(response);


					$('.loadnemes').on('click', 'li', function(e)
					{
						 e.preventDefault();
 
 						var html = $(this).html();
 
						$(".namevalue").val(html);
						$(".loadnemes").hide(100);

					});

				}
				
				 proccessing = false;
			}
		});
		
	};
	
	$(".namevalue").keydown('input', function(e)
	{
		// если идет аякс запрос, завершаем функцию, не даем пройти дальше
		if( proccessing == true )
		{
		return;
		}
		
		// отключаю таймер
		clearTimeout(timeout);
		
		if($(".namevalue").val().length > 2)
		{
		// заного включаю
		timeout = setTimeout(onTimeout, 1000);
		}
	});
	
	/* Всплывающая подсказка. End */
	



	
	$("#imgLoad").hide();
	$("#imgLoadS").hide();
	
	/* Фильтр. Скрыть интересы при типе конкурента 6 */
	$('#type option:selected').each(function()
	{  
		if($(this).val() == 6)
		{
			$("#bought").val(null);
			$("#bought").hide();
		}
	});
	/* Фильтр. Скрыть интересы при типе конкурента 6. End */
	

	/* Фильтр. Скрыть интересы при типе конкурента 6 */
	$("#type").change(function()
	{
		if($(this).val() == 6)
		{
			$("#bought").val(null);
			$("#bought").hide();
		}
		else
		{
			$("#bought").show();	
		}
	});
	/* Фильтр. Скрыть интересы при типе конкурента 6. End */




	/* Фильтр. Выбор местонахождение */
	$("#country").change(function()
	{
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
		
</script>    



<script>
$(document).ready(function () 
{
	var navbox = $('.nav-tabs');

    navbox.on('click', 'a', function (e) 
	{
      var $this = $(this);
	  
      e.preventDefault();
      window.location.hash = $this.attr('href');
      $this.tab('show');
	  
	  $("html, body").stop().animate({scrollTop: $('#view-notes').offset().top + 'px'}, 500);
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
</script>



<script type="text/javascript">
jQuery(function(){

jQuery.fn.getTitle = function() { // Copy the title of every IMG tag and add it to its parent A so that fancybox can show titles
	var arr = jQuery("a.fancybox");
	jQuery.each(arr, function() {
		var title = jQuery(this).children("img").attr("title");
		jQuery(this).attr('title',title);
	})
}

// Supported file extensions
var thumbnails = jQuery("a:has(img)").not(".nolightbox").filter( function() { return /\.(jpe?g|png|gif|bmp)$/i.test(jQuery(this).attr('href')) });

thumbnails.addClass("fancybox").attr("data-rel","fancybox").getTitle();
jQuery("a.fancybox").fancybox({
	'cyclic': false,
	'autoScale': true,
	'padding': 10,
	'opacity': true,
	'speedIn': 100,
	'speedOut': 100,
	'changeSpeed': 100,
	'overlayShow': true,
	'overlayOpacity': "0.8",
	'overlayColor': "#000000",
	'titleShow': true,
	'titlePosition': 'inside',
	'enableEscapeButton': true,
	'showCloseButton': true,
	'showNavArrows': true,
	'hideOnOverlayClick': true,
	'hideOnContentClick': false,
	'width': 560,
	'height': 340,
	'transitionIn': "fade",
	'transitionOut': "fade",
	'centerOnScroll': true
});


})
</script>




<script type="text/javascript">



	function isotope() 
	{
		
		$.datepicker.regional['ru'] = {monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'], monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'], dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'], dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'], dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'], dateFormat: 'dd-mm-yy', initStatus: '', isRTL: false, firstDay:1};
	   
		
		var type  = {changeMonth: true,changeYear: true,maxDate:0,yearRange: "-70:+0",dateFormat: "yy-mm-dd"};
		var TypePrevious  = {changeYear: true,changeMonth: true,dateFormat: "yy-mm-dd"};
		var TypePreviousOn  = { changeYear: true, changeMonth: true,minDate: 0, dateFormat: "yy-mm-dd"};

/* $('#off-task').datepicker({ changeYear: true, changeMonth: true,minDate: 0 ,dateFormat: 'yy-mm-dd', onSelect: function(datetext){ var d = new Date(); datetext=datetext+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds(); $('#off-task').val(datetext);}, });
		 */
			
		$('#off-task').datepicker({ changeYear: true, changeMonth: true,minDate: 0 ,dateFormat: 'yy-mm-dd 19:00:00' });
		
			$("#cdatestart").datepicker(type);
			$("#cdateoff").datepicker(type);
			
			$("#type-vse-date").datepicker(type);
			$("#name_client_cdata").datepicker(type);
			$(".form-control.Ondate").datepicker(TypePrevious);
			

			 $.datepicker.setDefaults($.datepicker.regional['ru']);
	}  
	 
	isotope();
	

</script>

<script type="text/javascript" src="/tpl/js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="/tpl/js/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="/tpl/js/jquery.fancybox-1.3.4.js"></script>

<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-body">
      </div>

    </div>
  </div>
</div>




</body>
</html>

<? $CFG->STATUS->SHOWSTATUS(); $CFG->FORM->CLEARSTATUS();?>
<? $CFG->STATUS->CLEARSTATUS(); $CFG->STATUS->__destruct; ?>

<!--<?php $size_ = memory_get_usage()/1024/1024; echo " ".round($size_, 2). " Mb | "; echo $db->QUERYCOUNT.' queries mysql';?>-->