/* Кнопка в шапке, поставить задачу */
$(document).on('click','#add_taks_head',function(e)
{
	var id = $(this).attr('data-id');

	if(id == 0)
	{

		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Менеджеру необходимо в профайле указать запись где будут устанавливать задачи.</center></h4></div>');

	}
	else
	{
		window.location.href = "/record/" + id + "#add_taks";
	}
	e.preventDefault();
});
/* Кнопка в шапке, поставить задачу */



/* Задача выполнена. Отправляем на модерацию. */
$(document).on('click','.upstatustaks',function(e)
{
	var id = $(this).attr('data-id');
	$('#myModalBox').modal({backdrop: 'static', keyboard: false});
	$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Ваш результат</h4></div>');
	$(".modal-body").append('<p><textarea class="form-control text" rows="2" style="margin-bottom:15px;"></textarea> <div id="textareaFeedback" class="ps_1"></div></p>');
	$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Отправить</button></p>');


		/* Да. Отправить введеный текст */
		$('.btn.submit').on('click', function(e)
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
		});
	e.preventDefault();
});
/* Задача выполнена. Отправляем на модерацию. End */




/* Продлить время задачи */
$(document).on('click','.updatestaks',function(e)
{
	var id = $(this).attr('data-id');

	$('#myModalBox').modal({backdrop: 'static', keyboard: false});
	$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Продлить время выполнения задачи</h4></div>');
	$(".modal-body").append('<p><input type="text" name="off-task" value="" id="off-task" /></p>');
	$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Изменить</button></p>');
	isotope();
	

	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();
	today = yyyy + '-' + mm + '-' + dd + ' 19:00:00';

	$('input#off-task').val(today);

			
	$('.btn.submit').on('click', function(e)
	{
		var input = $('input#off-task').val();
		
		if(input != '')
		{
			$.ajax
			({
				url: "/taks/updatestaks/",
				type: "POST",
				data: {"input": input, "id": id,},
				cache: true,
				beforeSend: function()
				{
					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				},
				success: function(response)
				{
					if(response == 1)
					{
						setTimeout(function() {window.location.reload();}, 0,1);
					}
					
				}
			});

		}
	});


	e.preventDefault();
});
/* Продлить время задачи End*/


