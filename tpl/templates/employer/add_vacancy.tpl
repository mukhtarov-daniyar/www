
<?
	$MODULE_ = getFullXCodeByPageId($CFG->pid);

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');


    $sql  = "SELECT * FROM {$CFG->DB_Prefix}pages WHERE xcode='record' ";
	$pid = getSQLRowO($sql);

    $CFG->oPageInfo->html_title = 'Добавить новую компанию';
?>

<? echo showHeader2('Добавить новую компанию');?>

<div class="white">


<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="add_vacancy" />

    <input type='hidden' name='logo_company' value='<?=$e['logo_company']?>' id="logo_input" />
    <input type='hidden' name='logo_company_mini' value='<?=$e['logo_company_mini']?>' id="logo_min_input" />

    <input type="hidden" name="page" value="<?=$pid->id?>" />
	<input type="hidden" name="manager" value="<?=$CFG->USER->USER_ID?>" />

    <a href="#" class="change">
        <div class="boxPhotoProfile">
        	<img id="upAvatar" src="/tpl/img/noavatar.jpg" alt="" />
        </div>
    </a>

    <div class="kriteri">
        <span>Название компании:</span>
        <input type="text" name="name_company" placeholder="Название компании" value="<?=$data['name_company']?>"<?=$e['name_company']?>>
    </div>

     <div class="kriteri">
        <span>Отрасль клиента:</span>
        <select name="type_company[]"<?=$e['bought']?> class="selectpicker" multiple="multiple" data-live-search="true">
           <?
           $type_company = AndDataArray('type_company', 'page_id', 868, 1, $order = 'pos, id ASC');

           for($i=0;$i<sizeof($type_company);$i++)
           { ?>
                <option value="<?=$type_company[$i]->id?>"<? $array = explode(",", $data['type_company_id'] );   for($z=0;$z<sizeof($array);$z++){ if($array[$z] == $type_company[$i]->id) { echo " selected"; } else{echo "";} } ?>> <?=$type_company[$i]->name;?></option>
          <? }?>
        </select>
    </div>


        <div class="kriteri">
            <span>Инстаграм:</span>
            <input type="text" name="insta" class="website" placeholder="Введите акаунт instagram" value="">
        </div>





     <div class="kriteri">
        <span>Укажите город:</span>
        <select name="city"<?=$e['city']?> class="selectpicker show-tick city" data-live-search="true">
        	 <option value="0" selected>Любой</option>
       <?
        $city = SelectDataArray('city', 1, 'name ASC');
        for($i=0;$i<sizeof($city);$i++)
        {	//|| $CFG->USER->USER_CITY == $city[$i]->id
            ($data['city'] == $city[$i]->id ) ? $sel = "selected" : $sel = ""; ?>
            <option value="<?=$city[$i]->id?>"<?=$sel?>><?=translit($city[$i]->name);?></option>
      <? } ?>
        </select>
				<? include("/tpl/templates/click_event/city.tpl"); ?>
    </div>

    <div class="kriteri" style="width:700px;">
    	<span>О клиенте. Шифровки.</span>
        <textarea style="width:550px; height:30px; font-size:14px;" name="info"<?=$e['info']?>><?=replace_r_n($data['info']);?></textarea>
    </div>

    <div class="kriteri" style="width:700px;">
    	<span>Историческая справка. Нюансы. Особенности</span>
        <textarea style="width:550px; height:30px; font-size:14px;" name="history"<?=$e['history']?>><?=replace_r_n($data['history']);?></textarea>
    </div>

    <div class="kriteri" style="width:700px;">
    	<span>Контактные данные.</span>
        <textarea style="width:550px; height:30px; font-size:14px;" name="contact"<?=$e['contact']?>><?=replace_r_n($data['contact']);?></textarea>
    </div>

    <input type="hidden" name="cdate" value="<?=sqlDateNow()?>" />
    <input type="hidden" name="sys_language" value="<?=$CFG->SYS_LANG?>" />
    <input type="hidden" name="visible" value="1" />

    <input type="submit" value="Сохранить" class="btn  btn-danger" style="margin-left:400px; margin-top:15px;">

	</form>

</div>

<script type="text/javascript">

$(document).on('click','.btn-danger',function(e)
{
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
		$('.mobile_cashback').val('off');
	}
	else
	{
		$('.mobile_cashback').addClass('shos');
		$('.mobile_cashback').val('on');
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
			document.getElementById('logo_min_input').value = response.med;
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
