<?

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');

    $CFG->oPageInfo->html_title = $data['name'];
?>

<? echo showHeader2('Редактировать: '.$data['name']);?>

 <div class="white">

<div class="text" style="padding-right:0; padding-left:13px;">

	<form method="POST" enctype="multipart/form-data"  action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="edit_person" />

    <input type='hidden' name='img' value='<?=$data['img']?>' id="logo_input" />

    <a href="#" class="change">
        <div class="boxPhotoProfile">
        	<img id="upAvatar" src="<? if($data['img'] ==!"") {echo $data['img'];} else echo "/tpl/img/noavatar.jpg";?>" alt="" />
        </div>
    </a>

	<hr>

    <div class="kriteri" style="width:100%">
        <span>Физ. лицо - Обращение (для маркетинга и тел. книги):</span>
        <input type="text" id="face" name="name" placeholder="Физ. лицо" value="<?=apost(($data['name']));?>" <?=$e['name']?>>
        <button type="button" class="btn btn-default btn-sm face" style="position:relative; top:-3px;"><span class="glyphicon glyphicon-chevron-right"></span></button>
    </div>

    <div class="kriteri">
        <span>Подробнее - ИФ, бла - бла. Портретик человека:</span>
        <input type="text" id="other_face" name="name_other" placeholder="Физ. лицо" value="<?=apost(($data['name_other']));?>" <?=$e['name_other']?>>
    </div>



		<?	if($data['international'] =='') {?>
    <div class="kriteri tel" style="width:100%">
        <span>Мобильный номер:</span>
        <input type="tel" name="mobile" placeholder="Мобильный номер" class="mobile face_mobile" value="<?=apost(($data['mobile']));?>" <?=$e['mobile']?>>
        <button type="button" class="btn btn-default btn-sm mobs" style="position:relative; top:-3px;"><span class="glyphicon glyphicon-chevron-right"></span></button>
        <label style="display:block; font-weight:100">Получатель кешбека <input type="checkbox" name="cashback" class="mobile_cashback <? if($data['cashback'] == 1) {echo 'shos';}?>" value="<?=$data['cashback'];?>" style="width:auto" <? if($data['cashback'] == 1) {echo 'checked';}?>></label>
    </div>

    <div class="kriteri wp">
        <span>Whatsapp номер:</span>
        <input type="tel" name="whatsapp" placeholder="Whatsapp номер" class="mobile whatsapp_mobile" value="<?=apost(($data['whatsapp']));?>" <?=$e['whatsapp']?>>
    </div>
		<? }?>

		<div class="kriteri">
        <span>Международный номер (Не Казахстан и не Россия):</span>
        <input type="tel" name="international" placeholder="Международный номер" class="international_mobile" value="<?=apost(($data['international']));?>" <?=$e['international']?>>
    </div>

		<script>
			const log = document.querySelector(".international_mobile");

			log.onkeydown = logKey;

			function logKey(e)
			{
				var curStr = log.value.replace(/[-]/g, "");

				if(curStr.trim().length >= 0)
				{
					document.querySelector(".kriteri .whatsapp_mobile").value = '';
					document.querySelector(".kriteri.wp").style.display = "none";

					document.querySelector(".kriteri .face_mobile").value = '';
					document.querySelector(".kriteri.tel").style.display = "none";
				}
			}
		</script>



    <div class="kriteri">
        <span>E-mail:</span>
        <input type="text" name="email" placeholder="E-mail" value="<?=apost(($data['email']));?>" <?=$e['email']?>>
    </div>

     <div class="kriteri">
        <span>Город:</span>
        <select name="city" <?=$e['city']?> class="selectpicker show-tick city" data-live-search="true">
           <option value="0" selected>Любой</option>
           <?
            $city = SelectDataArray('city', 1, 'name ASC');
            for($i=0;$i<sizeof($city);$i++)
            {
                ($data['city_id'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
          <? } ?>
        </select>
				<? include("/tpl/templates/click_event/city.tpl"); ?>
    </div>


    <div class="kriteri">
        <span>Дата рождения:</span>
		    <input type="tel" class="bcdate" name="bcdate" placeholder="День рождения" value="<? if($data['bcdate']*1 > 0) { echo date('Y-m-d', $data['bcdate']); }?>"<?=$e['bcdate']?>>
    </div>


    <div class="kriteri">
        <span>Пол:</span>
       <select name="floor" class="selectpicker show-tick floor">
            <option value="0">Любой</option>
            <?
                $floor[0]->id = 1; $floor[0]->name = 'Мужской';
                $floor[1]->id = 2; $floor[1]->name = 'Женский';

                for($i=0;$i<sizeof($floor);$i++)
                {
                    if($data['floor'] == $floor[$i]->id) { $sel = 'selected';} else {$sel = '';}
                ?>
                    <option value="<?=$floor[$i]->id;?>" <?=$sel?>><?=$floor[$i]->name?></option>
              <? } ?>
        </select>
				<? include("/tpl/templates/click_event/floor.tpl"); ?>
    </div>


	<script type="text/javascript">
        $(document).on('click','.btn.face',function(e)
        {
            var face = $('#face').val();
            $('#other_face').val(face);
            $('.alert').html('Скопировано');
            $('.alert').animate({'opacity':'show'}, 1000);
            $('.alert').animate({'opacity':'hide'}, 4000);
        });

        $(document).on('click','.btn.mobs',function(e)
        {
            var face = $('.face_mobile').val();
            $('.whatsapp_mobile').val(face);
            $('.alert').html('Скопировано');
            $('.alert').animate({'opacity':'show'}, 1000);
            $('.alert').animate({'opacity':'hide'}, 4000);
        });
    </script>

        <hr>
             <div class="kriteri">
                <span>Пользователь:</span>
                <select name="manager" <?=$e['type_company']?> class="selectpicker show-tick manager"  data-live-search="true">
                     <option value="0" selected>Любой</option>
                       <?

                        $manager = SelectDataArray('users', 0, 'name ASC');

                        for($i=0;$i<sizeof($manager);$i++)
                        {
                            ($data['manager_id'] == $manager[$i]->id) ? $sel = " selected" : $sel = ""; ?>
                            <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=$manager[$i]->name?></option>
                      <? } ?>
                </select>
								<? include($_SERVER['DOCUMENT_ROOT']."/tpl/templates/click_event/manager.tpl"); ?>
            </div>
        <hr>


     <div class="kriteri">
        <span>Маркетинг план:</span>
        <select name="marketing_id[]"<?=$e['marketing_id']?> class="selectpicker marketing" multiple="multiple" data-live-search="true">
           <?
           $type_company_portrait = AndDataArray('type_company_portrait', 'page_id', 868, 1, $order = 'pos, id ASC');
           for($i=0;$i<sizeof($type_company_portrait);$i++)
           { ?>
                <option value="<?=$type_company_portrait[$i]->id?>"<? $array = explode(",", $data['marketing_id'] );   for($z=0;$z<sizeof($array);$z++){ if($array[$z] == $type_company_portrait[$i]->id) { echo " selected"; } else{echo "";} } ?>> <?=$type_company_portrait[$i]->name?></option>
          <? }?>
        </select>
				<? include("/tpl/templates/click_event/marketing_id.tpl"); ?>
    </div>


		<hr>
		<style>
      .add_client {font-family:'Helvetica_b'; cursor:pointer; text-transform:uppercase; color:#666; font-size:14px; display:block; margin-bottom:10px; text-decoration:underline;}
			.kriteri .contact { position:relative; vertical-align:top; text-align:center; display: inline-block; width:24%; padding:5px px; margin-bottom:10px; cursor:pointer}
			.kriteri .contact img{ border-radius:50%; width:44px; width:44px;}
			.kriteri .contact span.name{ font-family:'segoeui_sb'; color: #878787; font-size:10px}
			.kriteri .contact span.mobiles{ font-family:'segoeui_b'; color:#333; font-size:10px}

			.kriteri .contact .mask img{ display:none}
			.kriteri .contact .mask .name{ display:none}
			.kriteri .contact .mask .mobiles{ display:none}


			.kriteri .contact:hover .mask { display:none; text-align:center}
			.kriteri .contact:hover .mask { display:block; background:#fff; width:100%; height:100%; position:absolute; top:0; left:0; cursor:pointer;}
			.kriteri .contact:hover .mask img{ display: block; margin:0 auto}
			.kriteri .contact:hover .mask .name{ display: block; margin:0 auto}
			.kriteri .contact:hover .mask .mobiles{ display: block; margin:0 auto}

			.modal-body .bootstrap-select { width:100% !important}

			#add_client_id a{font-family:'segoeui_b'; color:#333; font-size:16px }
        </style>



			<div class="kriteri contact_id">
				<span>Связанное лицо: (Брат, Сват, Жена, Партнер и т д)</span>
					<? FaceArrayAdd($CFG->_GET_PARAMS[1]);?>
			</div>
      <div class="kriteri" id="add_client_id">
      	<a href="#">+ Добавить лицо</a>
      </div>

			<script>

			$(document).on('click','.none_client',function(e)
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false});
				var id = $(this).attr('data-id');
				$.ajax
				({
					url: "/person/client_id_dell/",
					type: "POST",
					data: {"id": id, "page_id": <?=$CFG->_GET_PARAMS[1];?>, },
					cache: true,
					success: function(response)
					{
						$(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						setTimeout(function() {window.location.reload();}, 1000);
					}
				});
			});


			$(document).on('click','#add_client_id',function(e)
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false});

				$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Добавить связанное лицо</h4></div>');

				$(".modal-body").append('<p><input type="text" class="form-control name mobile" value="" placeholder="Начните вводить телефон или имя..."></p>');
				$(".modal-body").append('<p class="add_new_profile"><a href="/profile/add_person/ "target="_blank">+ создать новое физ. лицо<a></p>');
				$(".modal-body").append('<div id="form-control-Feedback"></div>');

				$(".modal-body").append('<p><button type="button" class="btn btn-primary" style="display:none">Сохранить</button></p><br clear="all">');
				$('.selectpicker').selectpicker();


				$('input.form-control.name').keyup(function()
				{
						var curLength = $('input.form-control.name').val().length;
						var curText = $('input.form-control.name').val();

						if (curLength > 2)
						{
							$('input.form-control.name').removeClass('warning');
							$('#form-control-Feedback').html('');
							$(".btn.btn-primary").show();

							$.ajax
							({
								url: "/static/client_id_search/",
								type: "POST",
								data: {"text": curText},
								cache: true,
								beforeSend: function()
								{
								},
								success: function(response)
								{
									if(response != 0) {$("#form-control-Feedback").html('<div class="loadnemes"> '+response+' </div>');	}else{$("#form-control-Feedback").html('Ничего не найдено :(');}

									$('.modal-body').off('click').on('click', 'a.a_go', function(e)
									{
										var id = $(this).attr('data-id');
										var mobile = $(this).attr('data-mobile');
										var name = $(this).attr('data-name');
										var avatar = $(this).attr('data-avatar');

										$(".user .contact_id").append('<div class="contact"><img src="'+avatar+'"> <span class="name">'+name+'</span><span class="mobiles">'+mobile+'</span><div class="mask"><img src="/tpl/img/not_grey.jpg"> <span class="name">Открепить?</span><span class="mobiles">'+mobile+'</span> </div></div>');

										$.ajax({url: "/person/face_to_face", type: "POST", data: {"id": id, "page_id": <?=$CFG->_GET_PARAMS[1];?>, },cache: true	});

										$(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
										setTimeout(function() {window.location.reload();}, 1000);
										e.preventDefault();
									});
								}

							});

							$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
							{

							});
						}
						else
						{
							$('input.form-control.name').addClass('warning');
							$('#form-control-Feedback').text('Минимум 3 символа...');
						}
				});
			e.preventDefault();
			});
		  </script>



		<? if($CFG->USER->USER_BOSS == 1 || $CFG->USER->USER_ID == 1 ) {?>

		<hr>
		<div class="kriteri">

			  <label style="display:block; font-weight:100">
					Доступ к сайту LED.RU
					<input type="checkbox" name="cashback" class="skidka_led_op <? if($data['skidka_led']*1 > 0) {echo 'shos';}?>" value="" style="width:auto" <? if($data['skidka_led']*1 > 0) {echo 'checked';}?>></label>

					<div class="skidka_input" <? if($data['skidka_led']*1 > 0) { echo ' style="display:block"';} else {echo ' style="display:none"';} ?>>
						<span style="display:inline-block">Коэффициент для LED.RU:</span>
						<input type="tel" style="width:60px; display:inline-block" class="skidka_led" name="skidka_led" value="<?=$data['skidka_led']?>"<?=$e['skidka_led']?>>
                         <br><? include("/tpl/templates/click_event/skidka_led.tpl"); ?>
					</div>

    	</div>
		<hr>
	<? } ?>


<script>

$('.skidka_led_op').on('click', function()
{
	if(  $('.skidka_led_op').hasClass('shos') )
	{
		$('.skidka_led_op').removeClass('shos');
		$('input.skidka_led').val(0);
		$('.skidka_input').hide();
	}
	else
	{
		$('.skidka_led_op').addClass('shos');
		$('input.skidka_led').val(1.112);
		$('.skidka_input').show();
	}

});
</script>


     <div class="kriteri">
        <p style="color:#FF0000; line-height:14px; font-size:14px;font-family:'Helvetica_medium';">Любая информация которая может пригодится.</p>
        <textarea style="width:550px; height:30px; font-size:14px;" name="info" <?=$e['info']?>><?=replace_r_n($CFG->FORM->FORM["info"])?></textarea>
    </div>

	<p>&nbsp;</p>



    <input type="submit" value="Сохранить" class="btn btn-danger" style="margin-left:350px; margin-top:15px;">
	</form>

</div>
</div>
<script type="text/javascript">


$(document).on('click','.someClass .mobile_copy_click',function(e)
{
	var id = $(this).attr('data-id');

	var copy = $('.data_fio_'+id+' input.mobile_copy').val();

	$('.data_fio_'+id+' input.whatsapp_copy').val(copy);

	$('.alert').html('Скопировать');
	$('.alert').animate({'opacity':'show'}, 1000);
	$('.alert').animate({'opacity':'hide'}, 4000);

});


$('.mobile_cashback').on('click', function()
{
	if(  $('.mobile_cashback').hasClass('shos') )
	{
		$('.mobile_cashback').removeClass('shos');
		$('.mobile_cashback').val(0);
	}
	else
	{
		$('.mobile_cashback').addClass('shos');
		$('.mobile_cashback').val(1);
	}

});

	if( document.getElementById('upAvatar') )
	{
		var btn = $('#upAvatar');

		 new AjaxUpload(btn,
		  {
		   data: {'user_act' : 'upload_avatar'},
		   name: 'avatar',
		   	action: '/static/avatar/',
		   autoSubmit: true,
		   onComplete: function(file, response)
		   {
			response = $.parseJSON(response);

			if( typeof response.med == 'undefined' || response.med == null )
			{
			 return false;
			}

			/ set view /
			$('#upAvatar').attr('src', response.med);

			/ set value /
			document.getElementById('logo_input').value = response.big;

		   }
		  });
	}




		$('.add_client').on('click', function(e)
		{
				var count = $('.someClass').length;

				$(".client_mask").append('<div class="someClass data_fio_'+count+'"><div class="kriteri"><span>Обращение (для маркетинга и тел. книги)</span><input type="text" name="data_name[]" placeholder="" value=""></div><div class="kriteri child"><span>Подробнее... ФИО, бла - бла. Портретик человека</span><input type="text" name="data_other[]" placeholder="" value=""></div><div class="kriteri child"><span style=" display:inline-block;">Моб. Базовый</span><span style=" display:inline-block; margin-left:127px;">Моб. Whatsapp </span><br><input type="text" class="mobile mobile_copy" name="data_mobile[]" placeholder="Мобильный базовый телеФон" value="" style="width:41%;"> <span class="glyphicon glyphicon-circle-arrow-right mobile_copy_click" data-id="'+count+'" style="display: inline-block; font-size:23px; cursor:pointer; margin-left:15px; margin-right:15px; vertical-align: top"></span><input type="text" name="data_whatsapp[]" placeholder="Номер Whatsapp" value="<?=$data_whatsapp[$y];?>" class="mobile whatsapp_copy" style="width:41%;"></div><div class="kriteri child"><span>Почтовый ящик:</span><input type="text" name="data_email[]" placeholder="Введите почтовый ящик" value=""></div><div class="kriteri child"><a href="#" class="del_fio" data-id="'+count+'" style=" display:block; text-align:right">Удалить контактное лицо</a></div></div>');


			 	$(".mobile").mask("+7-999-999-99-99");

			e.preventDefault();
		});


		$(document).on('click','.del_fio',function(e)
		{
        	var id = $(this).attr('data-id');
			var count = $('.someClass').length;

			if(count == 1)
			{

			}
			else
			{
				$(".data_fio_" + id).remove();
			}


			e.preventDefault();
		});




</script>
