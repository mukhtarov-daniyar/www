
<?
	$MODULE_ = getFullXCodeByPageId($CFG->pid);

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');

    $CFG->oPageInfo->html_title = 'Добавить физ. лицо';

?>

<? echo showHeader2('Добавить физ. лицо');?>

<div class="white">


<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="add_person" />

    <input type='hidden' name='img' value='<?=$e['logo_company']?>' id="logo_input" />

    <input type="hidden" name="page" value="1012" />
	<input type="hidden" name="manager" value="<?=$CFG->USER->USER_ID?>" />

    <a href="#" class="change">
        <div class="boxPhotoProfile">
        	<img id="upAvatar" src="/tpl/img/noavatar.jpg" alt="" />
        </div>
    </a>

    <div class="kriteri" style="width:100%">
        <span>Физ. лицо - Обращение (для маркетинга и тел. книги):</span>
        <input type="text" id="face" name="name" placeholder="Имя" value="<?=$data['name']?>"<?=$e['name']?>>
        <button type="button" class="btn btn-default btn-sm face" style="position:relative; top:-3px;"><span class="glyphicon glyphicon-chevron-right"></span></button>
    </div>

    <div class="kriteri">
        <span>Подробнее - ИФ, бла - бла. Портретик человека:</span>
        <input type="text" name="name_other" id="other_face" placeholder="Имя Фамилия" value="<?=$data['name_other']?>"<?=$e['name_other']?>>
    </div>

    <div class="kriteri tel" style="width:100%">
        <span>Мобильный номер:</span>
        <input type="text" name="mobile" placeholder="Мобильный номер" class="mobile face_mobile" value="<?=$data['mobile']?>"<?=$e['mobile']?>>
        <button type="button" class="btn btn-default btn-sm mobs" style="position:relative; top:-3px;"><span class="glyphicon glyphicon-chevron-right"></span></button>
				<div class="img_status"></div>

         <label style="display:block; font-weight:100">Получатель кешбека <input type="checkbox" name="cashback" class="mobile_cashback" value="0" style="width:auto"></label>
    </div>

    <div class="kriteri wp">
        <span>Whatsapp номер:</span>
        <input type="text" name="whatsapp" placeholder="Whatsapp номер" class="mobile whatsapp_mobile" value="<?=$data['whatsapp']?>"<?=$e['whatsapp']?>>
    </div>

    <div class="kriteri">
        <span>Международный номер (Не Казахстан и не Россия):</span>
        <input type="text" name="international" placeholder="Международный номер" class="international_mobile" value="<?=$data['international']?>"<?=$e['international']?>>
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
        <input type="text" name="email" placeholder="E-mail" value="<?=$data['email']?>"<?=$e['email']?>>
    </div>
     <div class="kriteri">
        <span>Город:</span>
        <select name="city"<?=$e['city']?> class="selectpicker show-tick city" data-live-search="true">
        	 <option value="0" selected>Любой</option>
		       <?
		        $city = SelectDataArray('city', 1, 'name ASC');
		        for($i=0;$i<sizeof($city);$i++)
		        {
		            //($CFG->USER->USER_CITY == $city[$i]->id) ? $sel = "selected" : $sel = "";
                     ?>
		            <option value="<?=$city[$i]->id?>"<?=$sel?>><?=translit($city[$i]->name);?></option>
		      <? } ?>
        </select>
				<? include($_SERVER['DOCUMENT_ROOT']."/tpl/templates/click_event/city.tpl"); ?>
    </div>

     <div class="kriteri">
        <span>Пол:</span>
       <select name="floor" class="selectpicker show-tick floor">
            <?
                $floor[0]->id = 1; $floor[0]->name = 'Мужской';
                $floor[1]->id = 2; $floor[1]->name = 'Женский';

                for($i=0;$i<sizeof($floor);$i++)
                {?>
                    <option value="<?=$floor[$i]->id;?>"><?=$floor[$i]->name?></option>
              <? } ?>
        </select>
				<? include($_SERVER['DOCUMENT_ROOT']."/tpl/templates/click_event/floor.tpl"); ?>
    </div>

    <div class="kriteri">
        <span>Дата рождения:</span>
        <input type="tel" class="bcdate" name="bcdate" placeholder="День рождения" value="<?=$data['bcdate']?>"<?=$e['bcdate']?>>
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



     <div class="kriteri">
        <span>Маркетинг план:</span>
        <select name="marketing_id[]"<?=$e['type_company_portrait']?> class="selectpicker" multiple="multiple" data-live-search="true">
           <?
           $type_company = AndDataArray('type_company_portrait', 'page_id', 868, 1, $order = 'id ASC');

           for($i=0;$i<sizeof($type_company);$i++)
           { ?>
                <option value="<?=$type_company[$i]->id?>"<? $array = explode(",", $data['type_company_id'] );   for($z=0;$z<sizeof($array);$z++){ if($array[$z] == $type_company[$i]->id) { echo " selected"; } else{echo "";} } ?>> <?=$type_company[$i]->name;?></option>
          <? }?>
        </select>
    </div>

		<? if($CFG->USER->USER_BOSS == 1 || $CFG->USER->USER_ID == 1 ) {?>

		<hr>
		<div class="kriteri">

			  <label style="display:block; font-weight:100">
					Доступ к сайту LED.RU
					<input type="checkbox" name="cashback" class="skidka_led_op <? if($data['skidka_led']*1 > 0) {echo 'shos';}?>" value="" style="width:auto" <? if($data['skidka_led']*1 > 0) {echo 'checked';}?>></label>

					<div class="skidka_input" <? if($data['skidka_led']*1 > 0) { echo ' style="display:block"';} else {echo ' style="display:none"';} ?>>
						<span style="display:inline-block">Коэффициент для LED.RU:</span>
						<input type="tel" style="width:60px; display:inline-block" class="skidka_led" name="skidka_led" value="0"<?=$e['skidka_led']?>>

                       	<br><? include($_SERVER['DOCUMENT_ROOT']."/tpl/templates/click_event/skidka_led.tpl"); ?>
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
		$('input.skidka_led').val(1.15);
		$('.skidka_input').show();
	}

});
</script>
<style>
.add_client {font-family:'Helvetica_b'; cursor:pointer; text-transform:uppercase; color:#666; font-size:14px; display:block; margin-bottom:10px; text-decoration:underline}
</style>


    <div class="kriteri" style="width:700px;">
    	<span>Любая информация которая может пригодится.</span>
        <textarea style="width:550px; height:30px; font-size:14px;" name="info"<?=$e['info']?>><?=replace_r_n($data['info']);?></textarea>
    </div>




    <input type="submit" value="Сохранить" class="btn  btn-danger" style="margin-left:400px; margin-top:15px;">
	</form>


<script type="text/javascript">
$(document).on('click','.btn-danger',function(e)
{
	var other_face = $('#other_face').val().split(' ');
	
	if(other_face[1]  === undefined || other_face[1] == '' )
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false});
		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Не было соблюдено фиолетовое правило :(</h4></div>');
		e.preventDefault();
		return false;
	}


	var city = $('.selectpicker.show-tick.city').val();
	if(city == 0)
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false});
		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Вы не указали город :(</h4></div>');
		e.preventDefault();
	}

});

$(document).on('click','.mobile_copy_click',function(e)
{
	var id = $('.mobile_copy').val();

	$('.whatsapp_copy').val(id)


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


	if( document.getElementById('upAvatar'))
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
        	$(".client_mask").append('<div class="kriteri"><span>Обращение (для маркетинга и тел. книги):</span><input type="text" name="data_name[]" placeholder="" value=""></div>');
			$(".client_mask").append('<div class="kriteri child"><span>Подробнее... ФИО, бла - бла. Портретик человека:</span><input type="text" name="data_other[]" placeholder="" value=""></div>');
        	$(".client_mask").append('<div class="kriteri child"><span>Моб. для Whatsapp:</span><input type="text" class="mobile" name="data_mobile[]" placeholder="Мобильный телеФон" value=""></div>');
       	 	$(".client_mask").append('<div class="kriteri child"><span>Почтовый ящик:</span><input type="text" name="data_email[]" placeholder="Введите почтовый ящик" value=""></div>');

			 $(".mobile").mask("+7-999-999-99-99");

			e.preventDefault();
		});


</script>
