<? 
   
   
    $CFG->oPageInfo->html_title = $data['name'];
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>

<div class="row tab">
    <div class="col-md-6">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/" class="active">Моя компания</a></h1>
    </div>    	
    <div class="col-md-6">
        <h1 class="active"><a href="/ru/registration/employer/">Я работодатель</a></h1>
    </div>
</div>



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
        <input type="text" name="name_company" placeholder="Введите компании" value="<?=ecrane($data['name_company']);?>" <?=$e['name_company']?>>
    </div>


        <? if($o->manager_id == $CFG->USER->USER_ID || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85){ ?>
        <hr>
             <div class="kriteri">
                <span>Пользователь:</span>
                <select name="manager" <?=$e['type_company']?> class="selectpicker show-tick"  data-live-search="true">
                     <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
               <? 
                $manager = SelectDataArray('users', 0, 'name ASC');
                for($i=0;$i<sizeof($manager);$i++)
                {	
                    ($data['manager_id'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=$manager[$i]->name?></option>
              <? } ?>
                </select>
            </div>
        <hr> 
        <?} ?>

     <div class="kriteri">
        <span>Тип компании:</span>
        <select name="type_company" <?=$e['type_company']?> class="selectpicker show-tick" id="type">
        	 <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
       <? 
        $type_company = SelectDataArray('type_company', 1, 'id ASC');
        for($i=0;$i<sizeof($type_company);$i++)
        {	
            ($data['type_company_id'] == $type_company[$i]->id) ? $sel = "selected" : $sel = ""; ?>
            <option value="<?=$type_company[$i]->id?>"<?=$sel?>><?=$type_company[$i]->name?></option>
      <? } ?>
        </select>
    </div>
    
     <div class="kriteri" id="bought">
        <span>Интересы компании:</span>
        <select name="bought[]"<?=$e['bought']?> class="selectpicker" multiple="multiple">
        
       <? 
        
        $bought = SelectDataArray("bought");
        for($i=0;$i<sizeof($bought);$i++)
        {	 
             ?>

		<option value="<?=$bought[$i]->id?>"<? $array = unserialize($data["bought"]); for($z=0;$z<sizeof($array);$z++){ if($array[$z] == $bought[$i]->id) { echo " selected"; } else{echo "";} } ?>>
        
           <?
            	if($bought[$i]->page_id == 0) {echo ' -';} else{ echo ' - - ';}
                if($bought[$i]->page_id > 1) {echo ' - - ';}              
            ?>
        
       <?=$bought[$i]->name?></option>
      <? 	
	    }?>
        
        </select>
    </div>

    <div class="kriteri">
        <span>Почтовый ящик:</span>
        <input type="text" name="email" placeholder="Введите почтовый ящик" value="<?=$data['email']?>"<?=$e['email']?>>
    </div>
    
    <div class="kriteri">
        <span>Дополнительный почтовый ящик:</span>
        <input type="text" name="other_email" placeholder="Введите дополнительный почтовый ящик" value="<?=$data['other_email']?>"<?=$e['other_email']?>>
    </div>
    
     <div class="kriteri">
        <span>Город:</span>
        <select name="city" <?=$e['city']?> class="selectpicker show-tick" data-live-search="true">
        	 <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
       <? 
        $city = SelectDataArray('city', 1, 'name ASC');
        for($i=0;$i<sizeof($city);$i++)
        {	
            ($data['city_id'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
            <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
      <? } ?>
        </select>
    </div>
 
     <div class="kriteri">
        <span>Заметка о клиенте:</span>
        <textarea style="width:700px; height:250px; font-size:14px;" name="info" <?=$e['info']?>><?=replace_r_n($CFG->FORM->FORM["info"])?></textarea>
    </div>
    
	<p>&nbsp;</p>

    <div class="kriteri">
        <span>ФИО генерального директора/учредителя/хозяина:</span>
        <input type="text" name="name_director" placeholder="ФИО генерального директора" value="<?=ecrane($data['name_director'])?>"<?=$e['name_director']?>>
    </div>
		
    <div class="kriteri child">
        <span>Мобильный:</span>
        <input type="text" name="name_director_mobile" placeholder="Мобильный телефон" value="<?=$data['name_director_mobile']?>"<?=$e['name_director_mobile']?> class="mobile">
    </div>
    
    <div class="kriteri child">
        <span>Почтовый ящик:</span>
        <input type="text" name="name_director_email" placeholder="Введите почтовый ящик" value="<?=$data['name_director_email']?>"<?=$e['name_director_email']?>>
    </div>
		
    <div class="kriteri child">
        <span>Дата рождения:</span>
        <input type="text" name="name_director_cdata" placeholder="Введите дату рождения" value="<?=$data['name_director_cdata']?>"<?=$e['name_director_cdata']?> id="type-vse-date">
    </div>
 
 
 
 
 
 
	<br clear="all">   
    <div class="kriteri">
        <span>ФИО покупающего/контактирующего/заинтересованного лица:</span>
        <input type="text" name="name_client" placeholder="ФИО покупающего лица" value="<?=ecrane($data['name_client'])?>"<?=$e['name_client']?>>
    </div>
    <div class="kriteri child">
        <span>Мобильный:</span>
        <input type="text" name="name_client_mobile" placeholder="Мобильный телефон" value="<?=$data['name_client_mobile']?>"<?=$e['name_client_mobile']?> class="mobile">
    </div>
    
    <div class="kriteri child">
        <span>Почтовый ящик:</span>
        <input type="text" name="name_client_email" placeholder="Введите почтовый ящик" value="<?=$data['name_client_email']?>"<?=$e['name_client_email']?>>
    </div>
		
    <div class="kriteri child">
        <span>Дата рождения:</span>
        <input type="text" name="name_client_cdata" placeholder="Введите дату рождения" value="<?=$data['name_client_cdata']?>"<?=$e['name_client_cdata']?> id="name_client_cdata">
    </div>    
 
    <input type="submit" value="Сохранить" class="btn btn-danger" style="margin-left:350px; margin-top:15px;">


      <input type="hidden" name="attach_files" value="<?=$data['attach_files'];?>" />
      <input type="hidden" name="attach_files_mini" value="<?=$data['attach_files_mini'];?>" />
      <input type="hidden" name="attach_files_doc" value="<?=$data['attach_files_doc'];?>" />



	</form>

    <div class="att_file">  
        <div class="input-form__attachments"> 
            <div class="input-form__attachments-files">
            
            	<? 
                    if ($data["attach_files_doc"] != "" || $data["attach_files_doc"] != 0)
                    {
                        $doc = explode(",", $data["attach_files_doc"]);
                        
                        for($n=0; $n<sizeof($doc); $n++)
                        {
                            if($doc[$n] != "" || $doc[$n] != 0)
                            {                         	
                                ?><a href="<?=$doc[$n];?>" class="cancel-attachment_doc" target="_blank"><?=basename($doc[$n]);?></a> <?
                            }
                        }
                    }
				?>            
            
            </div>
            
            <div class="input-form__attachments-image">
            	<? 
                    if ($data["attach_files"] != "" || $data["attach_files"] != 0)
                    {
                        $images = explode(",", $data["attach_files_mini"]);
                        
                        for($n=0; $n<sizeof($images); $n++)
                        {
                            if($images[$n] != "" || $images[$n] != 0)
                            {
                                ?><a href="#" class="cancel-attachment"><img class="image" src="<?=$images[$n];?>"/></a> <?
                            }
                        }
                    }
				?>
             </div>
        </div>
    
        <div class="status_off">Неверный тип файла!!!</div>
        
        <br clear="all">
        
        <form id="form-upload">
            <input type="hidden" name="user_act" value="attachment_to_comment" />
            <input class="input-form__input-file" type="file" name="comment-file" multiple data-url="/sys_comment.php" />
        </form>
      
        <div class="status_bar">Идет загрузка файла. Подождите....</div>
        <div class="status_intro">Внимание!!! Файлы прикрепляються в формате JPG PNG ZIP RAR DOC DOCX PDF XMS </div>
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