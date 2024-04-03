<? 
	$MODULE_ = getFullXCodeByPageId($CFG->pid); 
	
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/comments.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');

    
    $sql  = "SELECT * FROM {$CFG->DB_Prefix}pages WHERE xcode='alimzhanov-history' and sys_language='{$CFG->SYS_LANG}'";
	$pid = getSQLRowO($sql);
    
    $CFG->oPageInfo->html_title = 'Добавить запись';

?>

<? echo showHeader2('Добавить запись');?>

 <div class="white">  

<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="add_alimzhanov" />
    
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
        <input type="text" name="name_company" placeholder="Введите компании" value="<?=$data['name_company']?>"<?=$e['name_company']?>>
    </div>


    <div class="kriteri" style="width:700px;">
        <p>Информация</p>  
        <textarea style="width:550px; height:30px; font-size:14px;" name="info"<?=$e['info']?>><?=replace_r_n($data['info']);?></textarea>
    </div>

    <p>&nbsp;</p>

    


        
    <input type="hidden" name="cdate" value="<?=sqlDateNow()?>" />
    <input type="hidden" name="sys_language" value="<?=$CFG->SYS_LANG?>" />
    <input type="hidden" name="visible" value="1" />

    <input type="submit" value="Сохранить" class="btn  btn-danger" style="margin-left:400px; margin-top:15px;">

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

	if( document.getElementById('upAvatar'))
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