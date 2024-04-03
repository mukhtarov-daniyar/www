<?
    $CFG->oPageInfo->html_title = $data['name_company'];
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>

<? echo showHeader2('Редактировать компанию: '.$data['name_company']);?>

 <div class="white">

<div class="text" style="padding-right:0; padding-left:13px;">

	<form method="POST" enctype="multipart/form-data"  action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="edit_vacancy" />

    <input type='hidden' name='logo_company' value='<?=$data['logo_company']?>' id="logo_input" />
    <input type='hidden' name='logo_company_mini' value='<?=$data['logo_company_mini']?>' id="logo_min_input" />

    <a href="#" class="change">
        <div class="boxPhotoProfile">
        	<img id="upAvatar" src="<? if($data['logo_company_mini'] ==!"") {echo $data['logo_company_mini'];} else echo "/tpl/img/noavatar.jpg";?>" alt="" />
        </div>
    </a>

	<hr>

    <div class="kriteri">
        <span>Название компании:</span>
        <input type="text" name="name_company" placeholder="Введите компании" value="<?=apost(($data['name_company']));?>" <?=$e['name_company']?>>
    </div>


        <hr>
             <div class="kriteri">
                <span>Пользователь:</span>
                <select name="manager" <?=$e['type_company']?> class="selectpicker show-tick manager"  data-live-search="true">
                     <option value="0" selected>Любой</option>
               <?
                if($CFG->USER->USER_BOSS == 1)
                {
                	$manager = SelectDataParent('users', 'user_id', $CFG->USER->USER_DIRECTOR_ID);
                }
                if($CFG->USER->USER_STATUS == 0 || $CFG->USER->USER_BOSS == 1 && $CFG->USER->USER_STATUS == 2 || $CFG->USER->USER_BOSS == 0 && $CFG->USER->USER_STATUS == 2 || ($data['manager_id'] == $CFG->USER->USER_ID))
                {
                	$manager = SelectDataArray('users', 0, 'name ASC');
                }
                else
                {
                	$manager = SelectDataArray('users', 0, 'name ASC');
                }

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
        <span>Отрасль клиента:</span>
        <select name="type_company[]"<?=$e['type_company']?> class="selectpicker type_company" multiple="multiple" data-live-search="true">
           <?
           $type_company = AndDataArray('type_company', 'page_id', 868, 1, $order = 'pos, id ASC');

           for($i=0;$i<sizeof($type_company);$i++)
           { ?>
                <option value="<?=$type_company[$i]->id?>"<? $array = explode(",", $data['type_company_id'] );   for($z=0;$z<sizeof($array);$z++){ if($array[$z] == $type_company[$i]->id) { echo " selected"; } else{echo "";} } ?>> <?=$type_company[$i]->name?></option>
          <? }?>
        </select>
        <? include("/tpl/templates/click_event/type_company.tpl"); ?>
    </div>


    <div class="kriteri">
        <span>Инстаграм:</span>
        <input type="text" name="insta" value="<?=$data['insta']?>"<?=$e['insta']?> placeholder="Введите акаунт instagram" value="">
    </div>


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
        	<?  if($data["client_id"] != ''){personArrayAdd($data["client_id"]);} ?>
        </div>

        <div class="kriteri" id="add_client_id">
        	<a href="#">+ Добавить новый контакт</a>
        </div>


  <script>

	$(document).on('click','.none_client',function(e)
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

		var id = $(this).attr('data-id');

		$.ajax
		({
			url: "/static/client_id_dell/",
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

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Добавить физ. лицо</h4></div>');

		$(".modal-body").append('<p><input type="text" class="form-control name mobile" value="" placeholder="Начните вводить телефон или ФИО..."></p>');
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
					//$(".add_new_profile").hide();

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

								$.ajax({url: "/static/updata_client_id/", type: "POST", data: {"id": id, "page_id": <?=$CFG->_GET_PARAMS[1];?>, },cache: true	});

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
        <p style="color:#FF0000; line-height:14px; font-size:14px;font-family:'Helvetica_medium';">О клиенте. Шифровки.</p>
        <textarea style="width:550px; height:30px; font-size:14px;" name="info" <?=$e['info']?>><?=replace_r_n($CFG->FORM->FORM["info"])?></textarea>
    </div>

     <div class="kriteri">
        <span>Историческая справка.</span>
        <textarea style="width:550px; height:30px; font-size:14px;" name="history" <?=$e['history']?>><?=replace_r_n($CFG->FORM->FORM["history"])?></textarea>
    </div>

     <div class="kriteri">
        <span>Контактные данные.</span>
        <textarea style="width:550px; height:30px; font-size:14px;" name="contact" <?=$e['contact']?>><?=replace_r_n($CFG->FORM->FORM["contact"])?></textarea>
    </div>



	<p>&nbsp;</p>



    <input type="submit" value="Сохранить" class="btn btn-danger" style="margin-left:350px; margin-top:15px;">


      <input type="hidden" name="attach_files" value="<?=$data['attach_files'];?>" />
      <input type="hidden" name="attach_files_mini" value="<?=$data['attach_files_mini'];?>" />
      <input type="hidden" name="attach_files_doc" value="<?=$data['attach_files_doc'];?>" />

    <!--div class="kriteri2">
        <span>Адрес сайт:</span>
            <div class="input-group">
                <span class="input-group-addon">http://</span>
                <input type="text" name="site" class="form-control" value="<?=$data['site']?>"<?=$e['site']?>>
            </div>
    </div!-->

    <!--div class="kriteri">
        <span>Телефон:</span>
        <input type="text" name="tel" placeholder="Введите номер телефона" value="<?=$data['tel']?>"<?=$e['tel']?>>
    </div!-->


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
		$('.mobile_cashback').val('off');
	}
	else
	{
		$('.mobile_cashback').addClass('shos');
		$('.mobile_cashback').val('on');
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
			document.getElementById('logo_min_input').value = response.med;

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
