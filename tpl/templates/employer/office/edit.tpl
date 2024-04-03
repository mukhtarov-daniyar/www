<?
    $CFG->oPageInfo->html_title = $data['name_company'];
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>

<? echo showHeader2('Редактировать запись: '.$data['name_company']);?>

<div class="white">

<div class="text" style="padding-right:0; padding-left:13px;">

	<form method="POST" enctype="multipart/form-data"  action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="edit_office" />

    <input type='hidden' name='logo_company' value='<?=$data['logo_company']?>' id="logo_input" />
    <input type='hidden' name='logo_company_mini' value='<?=$data['logo_company_mini']?>' id="logo_min_input" />

    <a href="#" class="change">
        <div class="boxPhotoProfile">
        	<img id="upAvatar" src="<? if($data['logo_company_mini'] ==!"") {echo $data['logo_company_mini'];} else echo "/tpl/img/noavatar.jpg";?>" alt="" />
        </div>
    </a>

	<hr>

    <div class="kriteri">
        <span>Название записи:</span>
        <input type="text" name="name_company" placeholder="Введите название записи" value="<?=apost(($data['name_company']));?>" <?=$e['name_company']?>>
    </div>


        <?
        $us = $data['manager_id'];
        $sql = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$us}' ");

        if($data['manager_id'] == $CFG->USER->USER_ID || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_BOSS == 1 || $CFG->USER->USER_BOSS == 0 && $CFG->USER->USER_STATUS == 2 || $sql->id == ''){ ?>
        <hr>
             <div class="kriteri">
                <span>Пользователь:</span>
                <select name="manager" <?=$e['type_company']?> class="selectpicker show-tick"  data-live-search="true">
                     <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
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
            </div>
        <hr>
        <?} ?>


     <div class="kriteri">
        <span>Местопривязки:</span>
        <select name="page"<?=$e['parent']?> class="selectpicker page_id">
           <?

           $parent[0] = 976; $parent_name[0] = 'Служебная запись';
           $parent[1] = 868; $parent_name[1] = 'Клиентская запись';

           for($i=0;$i<sizeof($parent);$i++)
           { ?>
                <option value="<?=$parent[$i]?>" <? if($data['page_id'] == $parent[$i]) { echo " selected"; } else{echo "";}  ?>> <?=$parent_name[$i]?></option>
          <? }?>
        </select>
    </div>


     <div class="kriteri type_company">
        <span>Тип записи:</span>
        <select name="type_company[]"<?=$e['bought']?> class="selectpicker pager_page_id type" multiple="multiple">
           <?
           $type_company = AndDataArray('type_company', 'page_id', 976, 1, $order = 'id DESC');
           for($i=0;$i<sizeof($type_company);$i++)
           { ?>
                <option value="<?=$type_company[$i]->id?>"<? $array = explode(",", $data['type_company_id'] );   for($z=0;$z<sizeof($array);$z++){ if($array[$z] == $type_company[$i]->id) { echo " selected"; } else{echo "";} } ?>> <?=$type_company[$i]->name?></option>
          <? }?>
        </select>
    </div>

      <?
        $types = explode(",", $data['type_company_id']);
        if (in_array("10011980", $types)) //Если выбрана категория локации eDrive.kz
        {
          ?>
          <div class="kriteri city_div">
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
         </div>
          <?
        }
        else
        {
          ?>
          <div class="kriteri city_div">
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
         </div>
         <style>.city_div { display: none;} </style>
          <?
        }
      ?>



<script>
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
</script>

     <div class="kriteri">
        <p style="color:#FF0000; line-height:14px; font-size:14px;font-family:'Helvetica_medium';">Информация</p>
        <textarea style="width:550px; height:30px; font-size:14px;" name="info" <?=$e['info']?>><?=replace_r_n($CFG->FORM->FORM["info"])?></textarea>
    </div>

	<p>&nbsp;</p>


    <input type="submit" value="Сохранить" class="btn btn-danger" style="margin-left:350px; margin-top:15px;">
	</form>


<br><br><br><br><br>
</div>
</div>

<script type="text/javascript">

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

</script>



<script type="text/javascript">
$('.selectpicker.page_id').change(function ()
{
	var selectedText = $(this).find("option:selected").val();

	$.ajax
	({
		url: "/static/page_id_cat/",
		type: "POST",
		data: {"page_id": selectedText},
		cache: true,
		beforeSend: function()
		{
			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});

			$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
		},
		success: function(response)
		{
			if(response == 0)
			{
				$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Что то пошло не так, перезагрузите страницу и попробуйте занова!</h4></div>');
			}
			else
			{

				$(".kriteri.type_company").html(response);

				$('.selectpicker').selectpicker();

				$(".modal-body").html('');

				$(document).ready(function()
				{
					$("#myModalBox").modal('hide');
				});
			}
		}

	});
});
</script>
