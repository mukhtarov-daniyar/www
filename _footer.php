<?

$users_all = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 ORDER BY name ASC");

 if($CFG->USER->USER_ID > 0) { ?>
    </div>
</div>
<? } ?>


<br clear="all">


<div class="btn-down"><span class="glyphicon glyphicon-chevron-up"></span></div>




<style>
#comment-form { z-index:1000001 !important}
.fancybox-container{background:rgba(0,0,0,0.8);}
</style>


<script type="text/javascript" src="/tpl/js/jquery.cookie.js"></script>
<script type="text/javascript">

$(document).on('click','.price_input',function(e)
{
  var price_input = document.querySelector('.price_input');
  price_input.addEventListener('keyup', function ()
  {
      var key = number_format(this.value.replace(/[^\dA-Z]/g, ''), '', '', ' ');
      document.querySelector('.price_input').value = key;
  });
});

function number_format( number, decimals, dec_point, thousands_sep )
{
	var i, j, kw, kd, km;

	// input sanitation & defaults
	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 2;
	}
	if( dec_point == undefined ){
		dec_point = ",";
	}
	if( thousands_sep == undefined ){
		thousands_sep = ".";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

	if( (j = i.length) > 3 ){
		j = j % 3;
	} else{
		j = 0;
	}

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	//kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


	return km + kw + kd;
}


document.addEventListener('DOMContentLoaded', function()
{
  var str = window.location.href;
  var pos = str.lastIndexOf('comment-post_');

  if(pos > 0)
  {
    var names = str;
    var re = /\s*_\s*/;
    var nameList = str.split(re);

    if(nameList[1] > 0)
    {
      console.log(nameList[1]);
      document.querySelector("#comment-post_" + nameList[1]).style.backgroundColor = "#F7CFCF";
      document.querySelector("#comment-post_" + nameList[1] + " .comment-post-body").style.backgroundColor = "#F7CFCF";
      //

    }
  }
});



  $(function(){ $('[data-toggle="tooltip"]').tooltip(); });

  // Оправить лицо себе на Воцап
  $(document).on('click','.wp_send_VCF',function(e)
  {
    var id = $(this).attr('data-id');
    $('#myModalBox').modal({backdrop: 'static', keyboard: false});
    $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Отправить номер клиента на Whatsapp</h4></div>');

    $(".modal-body").append('<p class="text-danger"><strong>Прежде чем отправить контакт из системы, вам необходимо написать любое сообщение на Whatsapp номер с которого планируете делать отправку!!!</strong></p>');


    $(".modal-body").append('<p><strong>Номер от кого будем отправлять контакт</strong></p>');

    <? $wp_moble = getSQLArrayO("SELECT id,namber,name FROM my_tmp_whatsapp_namber WHERE visible = 1 " ); ?>

    $(".modal-body").append('<select data-focus-on="true" class="selectpicker show-tick manager list-view-manager pase" title="Выберите Whatsapp номер"><option value="0">Выберите Whatsapp номер</option><? foreach ($wp_moble as $value) { ?> <option value="<?=$value->id;?>"><?=$value->name;?> - <?=$value->namber;?></option>  <? }?> </select>');
    $('.selectpicker').selectpicker();

    $(".modal-body").append('<p><strong>На какой номер будет отправлен контакт</strong></p>');
    $(".modal-body").append('<p><input type="text" value="<?=$CFG->USER->USER_MOBILE;?>" class="form-control mobile" placeholder="Мобильный" autofocus></p>');
    $(".modal-body").append('<p><button type="button" class="btn btn-primary send">Отправить</button></p>');
    $('.form-control.mobile').inputmask("+7-999-999-99-99");

    $('.modal-body').off('click').on('click', 'button.btn.btn-primary.send', function(e)
  	{
      var wp = $('.modal-body .selectpicker.pase').val();

      if(wp == 0){alert('Укажите Whatsapp комер от кого будем отправлять контакт!');    return false;}

      var mobile = $('input.form-control.mobile').val();
      $.ajax
      ({
        url: "/whatsapp/send_contact/",
        type: "POST",
        data: {"id": id, "mobile": mobile, "wp": wp},
        cache: true,
          beforeSend: function()
          {
            $(".modal-body").html('<h4 class="modal-title"><center>Отправляем контакт, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
          },
          success: function(response)
          {
            if(response == 1)
            {
              $(".modal-body").html('<div class="modal-header"><h4 class="modal-title">Контакт отправлен на номер '+mobile+'</h4></div>');
              setTimeout(function() {window.location.reload();}, 1000);
            }
            else
            {
              $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Произошла ошибка, попробойте позже или обратитесь к Администратору</h4></div>');
            }
          }
      });
    });
    return false;
  });
  // Оправить лицо себе на Воцап


	/* Добавить напоменания */
	$('#reminder').on('click', function(e)
	{
		var id = $(this).attr('data-rel');

		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Создать напоминание</h4></div>');
		$(".modal-body").append('<p><input type="text" class="form-control name" placeholder="Описание" autofocus></p>');

		$(".modal-body").append('<div id="form-control-Feedback"></div>');


		$(".modal-body").append('<p><input type="text" class="form-control Ondate" value="<?=date('Y-m-d');?>">');

		$(".modal-body").append('<select class="selectpicker show-tick time" title="Выберите время"><option value="0">00:00</option><option value="1">01:00</option><option value="2">02:00</option><option value="3">03:00</option><option value="4">04:00</option><option value="5">05:00</option><option value="6">06:00</option><option value="7">07:00</option><option value="8">08:00</option><option value="9">09:00</option><option value="10">10:00</option><option value="11" selected>11:00</option><option value="12">12:00</option><option value="13">13:00</option><option value="14">14:00</option><option value="15">15:00</option><option value="16">16:00</option><option value="17">17:00</option><option value="18">18:00</option><option value="19">19:00</option><option value="20">20:00</option><option value="21">21:00</option><option value="22">22:00</option><option value="23">23:00</option></select>');

		$(".modal-body").append('<br><br> <select class="selectpicker show-tick manager list-view-manager" multiple="multiple" data-live-search="true"  title="Выберите менеджера"><option value="0">Выберите менеджер</option><?  for ($i=0; $i<sizeof($users_all); $i++){ ?> <option value="<?=$users_all[$i]->id;?>"><?=$users_all[$i]->name;?></option> <? } ?></select>');

		$(".modal-body").append('<select class="selectpicker show-tick alertS" title="Оповещение"><option value="2">Уведомление в системе</option><option value="3">Уведомление в системе + whatsapp</option><option value="4">Уведомление в системе + whatsapp + email</option></select></p>');

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




	/* Задача выполнена. Модерируем. */
  $(document).on('click','.upmodernstatustaks',function(e)
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
					$('#myModalBox').modal({backdrop: 'static', keyboard: false});
					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				},
				success: function(response)
				{
					$.ajax
					({
						url: "/static/upmodernstatustaks_send/",
						type: "POST",
						data: {"user_id": <?=$CFG->USER->USER_ID;?>},
						cache: true,
							beforeSend: function()
							{

								$(".modal-body").html('<h4 class="modal-title"><center>Обновляем содержимое..</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

							},
							success: function(response)
							{
								$("#my-user-taks-ok").html(response);

								$("#myModalBox").modal('hide');


							}
					});
				}
		});

		e.preventDefault();
	});
	/* Задача выполнена. Модерируем. End*/



	/* Перемия плюс */
	$('#premium').on('click', function(e)
	{
		var page_id = $(this).attr('data-id');

		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Введите сумму премии</h4></div>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name price_input"  placeholder="Сумма" required autofocus="autofocus"></p>');
		$(".modal-body").append('<div id="form-control-Feedback"></div>');
		$(".modal-body").append('<select class="selectpicker show-tick manager list-view-manager pase"  data-live-search="true"  title="Выберите менеджера"><? for ($i=0; $i<sizeof($users_all); $i++){ ($CFG->USER->USER_ID == $users_all[$i]->id) ? $sel = " selected" : $sel = "";  ?> <option value="<?=$users_all[$i]->id;?>"<?=$sel?>><?=$users_all[$i]->name;?></option> <? } ?></select>');
		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');
		$('.selectpicker').selectpicker();

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var text = $('input.form-control.name').val();
			var manager = $('.list-view-manager').val();
      var digitsOnly = text.replace(/\D/g, '');

			if(manager == null || digitsOnly == '')
			{
				alert("Укажите все данные");
			}
			else
			{

				if(digitsOnly  > 0)
				{
				$.ajax
				({
					url: "/static/premium_plus/",
					type: "POST",
					data: {"text": digitsOnly, "manager": manager , "page_id": page_id },
					cache: true,
						beforeSend: function()
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Создаем плюс...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{

							if(response == 0)
							{
								alert("Введите сумму!");
								$("#myModalBox").modal('hide');
								$('.modal-body').html('');
							}
							else
							{
								response = $.parseJSON(response);

                if(response.accessrecord)
                {
                  $('input[name=accessrecord]').val($('input[name=accessrecord]').val() + response.accessrecord);
                }

								$('input[name=premium]').val($('input[name=premium]').val() + ',' + response.id);
								$('.input-form__reminder').append('<div class="premium_minus">Для <strong>' + response.manager_id + '</strong> выписан плюс <strong>' + response.count + ' <?=$CFG->USER->USER_CURRENCY;?></strong></div>');

								$(document).ready(function(){
									$("#myModalBox").modal('hide');
								});

								$('.modal-body').html('');

							}
						}
				});

				}
				else
				{
					alert("Сумма - введена буквами!");
				}
			}
		});

		e.preventDefault();

	});
  /* Перемия плюс */


	/* Отрицательная мотивация. */
	$('#premium_minus').on('click', function(e)
	{
		var page_id = $(this).attr('data-id');

		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Введите сумму минуса</h4></div>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name price_input"  placeholder="Сумма" required></p>');
		$(".modal-body").append('<div id="form-control-Feedback"></div>');
		$(".modal-body").append('<select class="selectpicker show-tick manager list-view-manager pase"  data-live-search="true"  title="Выберите менеджера"><? for ($i=0; $i<sizeof($users_all); $i++){ ($CFG->USER->USER_ID == $users_all[$i]->id) ? $sel = " selected" : $sel = "";  ?> <option value="<?=$users_all[$i]->id;?>"<?=$sel?>><?=$users_all[$i]->name;?></option> <? } ?></select>');
		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');
		$('.selectpicker').selectpicker();

		$('.form-control.name').attr('autofocus', 'autofocus');

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var text = $('input.form-control.name').val();
      var digitsOnly = text.replace(/\D/g, '');
			var manager = $('.list-view-manager').val();

			if(manager == null || digitsOnly == '')
			{
				alert("Укажите все данные");
			}
			else
			{
        if(digitsOnly  > 0)
        {
  				$.ajax
  				({
  					url: "/static/premium_minus/",
  					type: "POST",
  					data: {"text": digitsOnly, "manager": manager , "page_id": page_id },
  					cache: true,
  						beforeSend: function()
  						{
  							$(".modal-body").html('<h4 class="modal-title"><center>Создаем минус...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  						},
  						success: function(response)
  						{

  							if(response == 0)
  							{
  								alert("Сумма минуса - введена буквами!");

  								$(document).ready(function(){
  									$("#myModalBox").modal('hide');
  								});

  								$('.modal-body').html('');
  							}
  							else
  							{
  								response = $.parseJSON(response);

                  if(response.accessrecord)
                  {
                    $('input[name=accessrecord]').val($('input[name=accessrecord]').val() + response.accessrecord);
                  }


                  $('input[name=premium_minus]').val($('input[name=premium_minus]').val() + ',' + response.id);

  								$('.input-form__reminder').append('<div class="premium_minus">Для <strong>' + response.manager_id + '</strong> выписан минус <strong>' + response.count + ' <?=$CFG->USER->USER_CURRENCY;?></strong></div>');

  								$(document).ready(function(){
  									$("#myModalBox").modal('hide');
  								});

  								$('.modal-body').html('');

  							}
  						}
  				});
        }
        else
        {
          alert("Введите сумму!");
        }
			}
		});

		e.preventDefault();
	});
	/* Отрицательная мотивация. */




	/* Задача НЕ выполнена. Модерируем. */
	$('.notdone').on('click', function(e)
	{
		var id = $(this).attr('data-id');

		$.ajax
		({
			url: "/static/notdone/",
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
					$.ajax
					({
						url: "/static/upmodernstatustaks_send/",
						type: "POST",
						data: {"user_id": <?=$CFG->USER->USER_ID;?>},
						cache: true,
							beforeSend: function()
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Обновляем содержимое..</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

							},
							success: function(response)
							{
								$("#my-user-taks-ok").html(response);

								$("#myModalBox").modal('hide');
							}
					});
				}
		});

		e.preventDefault();
	});
	/* Задача выполнена. Модерируем. End*/


<? if($CFG->_GET_PARAMS[0] > 0 && $CFG->oPageInfo->xcode == 'record' || $CFG->_GET_PARAMS[0] > 0 && $CFG->oPageInfo->xcode == 'deal' || $CFG->_GET_PARAMS[0] > 0 && $CFG->oPageInfo->xcode == 'office') { ?>

/* Создание сделки */
$('.add_deal').on('click', function(e)
{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Введите название сделки</h4></div>');
		$(".modal-body").append('<p><input style="width:440px " type="text" class="form-control name"  placeholder="Например «Продажа прожекторов FF50»" required autofocus></p>');
		$(".modal-body").append('<div class="erors"></div>');

		$(".modal-body").append('<p><select class="selectpicker show-tick manager list-view-manager" multiple="multiple" data-live-search="true"  title="Выберите менеджера"><option value="0">Выберите менеджер</option><? for ($i=0; $i<sizeof($users_all); $i++){ ?> <option value="<?=$users_all[$i]->id;?>"><?=$users_all[$i]->name;?></option> <? } ?></select></p>');
		$(".modal-body").append('<p><input style="width:160px" type="tel" class="form-control price"  placeholder="Сумма сделки" required></p>');

		$(".modal-body").append('<button type="submit" class="btn btn-primary">Продолжить</button>');

		$('.selectpicker').selectpicker();

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var text = $('input.form-control.name').val();
			var price = $('input.form-control.price').val();
			var manager = $('.list-view-manager').val();

				if(text  == '')
				{
					$('.modal-body .form-control.name').addClass('error');
					$('.modal-body .erors').html('Введите название сделки!');

				}
				else
				{
					<? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[0]}'"); ?>

					$.ajax
					({
						url: "/static/add_deal/",
						type: "POST",
						data: {"text": text, "price": price, "manager": manager, "name_company": "<? echo htmlspecialchars($o->name_company);?>", "id": <? echo $CFG->_GET_PARAMS[0];?>},
						cache: true,
							beforeSend: function()
							{
								$('#myModalBox').modal({backdrop: 'static', keyboard: false});

								$(".modal-body").html('<h4 class="modal-title"><center>Создаем сделку..</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{
								location.href = response;
							}
					});

				}

		});
	e.preventDefault();
});
/* Создание сделки. End*/
<? } ?>
</script>


<script type="text/javascript">


// TRANSFER заметок

// TRANSFER заметок
$(document).on('click','.transfer-note',function(e)
{
  var id = $(this).attr('data-id');

  $('#myModalBox').modal({backdrop: 'static', keyboard: false});

  $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Укажите номер записи: </h4></div>');
  $(".modal-body").append('<p><input type="text" class="form-control note" value="" placeholder="Например: *5555"></p>');
  $(".modal-body").append('<div class="content_modal">');
  $(".modal-body").append('</div>');
  $(".modal-body").append('<div class="info_modal_trans hide">Вы точно хотите сюда перенести все заметки данной ветки?<br> <button type="button" class="btn btn-danger">Да</button> &nbsp; <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">Нет</button></div>');

    $('.form-control.note').keyup(function()
    {
      $('.modal-dialog .mask_none').remove();
      $('.modal-dialog .info_modal_trans').addClass('hide');
      setTimeout(function()
      {
      var curVal = $('.form-control.note').val();
      var curLength = $('.form-control.note').val().length;         //(2)

      if(curLength > 1)
      {
        $.ajax
            ({
              url: "/static/inputN/",
              type: "POST",
              data: {"note": curVal},
              cache: true,
              success: function(response)
              {
                response = $.parseJSON(response);

                if(response.status == 1)
  							{
                  $('.modal-dialog .mask_none').remove();
                  $(".content_modal").append(response.text);
                  $('.modal-dialog .info_modal_trans').removeClass('hide');

                  $('.modal-body').off('click').on('click', '.info_modal_trans button.btn.btn-danger', function(e)
                  {
                    $.ajax
                		({
                			url: "/static/inputGlobalT/",
                			type: "POST",
                			data: {"note": id, "record": curVal},
                			cache: true,
                			beforeSend: function()
                			{
                				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
                			},
                			success: function(response)
                			{
                        if(response == 1)
            							{
            								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">Заметки перенесены в запись '+curVal+'</h3></center>');
            								setTimeout(function() {window.location.reload();}, 1);
            							}
            							if(response == 0)
            							{
            								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <br clear="all"><center><h3 class="modal-title">Физ. лицо не удалено :(<br> Что то пошло не так. Перезагрузите страницу и попробуйте снова.</h3></center>');
            							}
                			}
                		});
                  });

  							}
  							if(response.status == 0)
  							{
                   $('.modal-dialog .info_modal_trans').addClass('hide');
                   $('.modal-dialog .mask_none').remove();
  							   $(".content_modal").append(response.text);
  							}
              }
            });
      }

  }, 100);
    });



  // $('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)

  /*
  $.ajax
      ({
        url: "/static/inputGlobalT/",
        type: "POST",
        data: {"note": id},
        cache: true,
        success: function(response)
        {

        }
      });
  */
});



$(document).on('click','.transfer',function(e)
{
  var type = $(this).attr('data-direction');
  var values = [];
  {
    $('.form-check-input:checked').each(function() {	values.push($(this).val());	});
    <? if($CFG->_GET_PARAMS[0]) {?>var page_id = '<?=$CFG->_GET_PARAMS[0];?>';<? }?>

    if(values.length == 0){return false;}

      $.ajax
      ({
        url: "/static/checked/",
        type: "POST",
        data: {"id": values, "page_id": page_id, "type": type},
        cache: true,
        success: function(response)
        {
            $('#myModalBox').modal({backdrop: 'static', keyboard: false});

            if(response == 0)
            {
              $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Создайте сделку :(</h4></div>');
            }
            else
            {
              $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Перенос '+values.length+' заметок в сделку: </h4></div>');
              $(".modal-body").append('<p><select class="selectpicker show-tick time" title="Выберите сделку">' +  response  + '</select></p>');
              $(".modal-body").append('<p><button type="button" class="btn btn-primary">Продолжить</button></p>');
              $('.selectpicker').selectpicker();

              $('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
              {
                var id = $('input.form-control.id').val();
                var deal = $('.selectpicker.time').val();

                  $.ajax
                  ({
                    url: "/static/checked/",
                    type: "POST",
                    data: {"page_id": page_id, "deal": deal, "id": values, "type": 'transfer_2'},
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

  }
    e.preventDefault();
});
// TRANSFER заметок

</script>

<script type="text/javascript" src="/tpl/js/common.js?att=<?=date('Ymd');?>"></script>

<script type="text/javascript" src="/tpl/js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="/tpl/js/taks.js"></script>


<div id="myModalBox" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-body">
      </div>

    </div>
  </div>
</div>



<div class="SPDOWN"></div>


</body>
</html>
<? $CFG->STATUS->SHOWSTATUS(); $CFG->FORM->CLEARSTATUS();?>
<? $CFG->STATUS->CLEARSTATUS(); $CFG->STATUS->__destruct; ?>



<!-- ОЗУ <?php

global $testing_satart;
$size_ = memory_get_usage()/1024/1024; echo " ".round($size_, 2). " Mb | Количество запросов в базу "; echo $CFG->DB->totalQueries();  echo ' | Время выполнения скрипта: '.round(microtime(true) - $testing_satart, 4).' сек.';


//print_r($CFG->DB->queries);
 ?>



-->

<? echo '';



?>
