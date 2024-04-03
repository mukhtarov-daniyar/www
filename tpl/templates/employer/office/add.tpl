<?
	$MODULE_ = getFullXCodeByPageId($CFG->pid);

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');


    $sql  = "SELECT * FROM {$CFG->DB_Prefix}pages WHERE xcode='office' ";
	$pid = getSQLRowO($sql);

    $CFG->oPageInfo->html_title = 'Добавить служебную запись';

?>

<? echo showHeader2('Добавить служебную запись');?>

 <div class="white">

<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="add_office" />

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
        <span>Название записи:</span>
        <input type="text" name="name_company" placeholder="Введите название записи" value="<?=$data['name_company']?>"<?=$e['name_company']?>>
    </div>


     <div class="kriteri">
        <span>Тип записи:</span>
        <select name="type_company[]"<?=$e['bought']?> class="selectpicker type" multiple="multiple">
           <?
           $type_company = AndDataArray('type_company', 'page_id', 976, 1, $order = 'id DESC');
           for($i=0;$i<sizeof($type_company);$i++)
           { ?>
                <option value="<?=$type_company[$i]->id?>"<? $array = explode(",", $data['type_company_id'] );   for($z=0;$z<sizeof($array);$z++){ if($array[$z] == $type_company[$i]->id) { echo " selected"; } else{echo "";} } ?>> <?=$type_company[$i]->name?></option>
          <? }?>
        </select>
    </div>

		<div class="kriteri city_div">
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
	 </div>

	 <style>
	 .city_div { display: none;}
	 </style>


    <div class="kriteri" style="width:700px;">
        <p style="color:#FF0000; line-height:14px; font-size:14px;font-family:'Helvetica_medium';">Информация</p>
        <textarea style="width:550px; height:30px; font-size:14px;" name="info"<?=$e['info']?>><?=replace_r_n($data['info']);?></textarea>
    </div>

    <p>&nbsp;</p>


    <input type="hidden" name="cdate" value="<?=sqlDateNow()?>" />
    <input type="hidden" name="visible" value="1" />

    <input type="submit" value="Сохранить" class="btn  btn-danger" style="margin-left:400px; margin-top:15px;">

	</form>



</div>


<script type="text/javascript">


	$('.selectpicker.type').change(function()
	{
	    var selected = $(this).val();
	    if ($.inArray("10011980", selected) !== -1)
			{
				$(".city_div").show();
				$('.selectpicker.city').selectpicker();
	    }
			else
			{
				$('.selectpicker.city').val('0');
				$(".city_div").hide();
				$(".city_div .dropdown-menu li").removeClass('selected');
				$(".city_div .dropdown-menu li").removeClass('active');
				$(".city_div .dropdown-menu li:first").addClass('active');
				$(".city_div .dropdown-menu li:first").addClass('selected');
				$(".city_div .btn-group.bootstrap-select button .pull-left").html('Любой');
				$('.selectpicker.city').selectpicker();
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

</script>
