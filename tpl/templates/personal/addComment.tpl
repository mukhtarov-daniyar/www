<?
     $o = $CFG->FORM->getFullForm();
     $e = $CFG->FORM->getFailInputs();
      
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');

?>

<div class="addcoment_body">
    <form method="POST" id="comment-form" autocomplete="off">
        <input type="hidden" name="module_id" value="<?=$CFG->pid?>" />
        <input type="hidden" name="page_id" value="<?=$o->id?>" />
        <input type="hidden" name="user_act" value="add_comment" />
        <input type="hidden" name="attach_files_image" value="" />
        <input type="hidden" name="attach_files_music" value="" />

        <input type="hidden" name="attach_files" value="" />
        <textarea placeholder="Написать заметку" <?=$e['text']?> name="text"><?=$o['text']?></textarea>
        <div id="textareaFeedback" class="ps_1"></div>

        <input type="hidden" name="task" id="task" value="0" />

        <br clear="all" />
        
        <div class="window-task">

            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Дедлайн. Крайний срок исполнения задачи.</th>
                    <th>Исполнитель задачи</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="text" name="off-task" value="<? echo date('Y-m-d 19:00:00');?>" id="off-task" /></td>                              
                    <td><select class="selectpicker show-tick manager" name="doer" data-live-search="true" ><option value="0">Выберите исполнителя</option> <option value="<?=$CFG->USER->USER_ID;?>" selected="selected"><?=$CFG->USER->USER_NAME;?> </option> <? $users = SelectDataArray('users', 0, 'id DESC'); for ($i=0; $i<sizeof($users); $i++){ if($users[$i]->id == $CFG->USER->USER_ID) continue; ?> <option value="<?=$users[$i]->id;?>"><?=$users[$i]->name;?></option> <? } ?></select></td>
                  </tr>
                </tbody>
              </table>
            </div>  
            
        </div>

        <input type="submit" value="<?=$CFG->Locale['send']?>" id="coment" />
        
        <div id="file_upload" title="Можно прикрепить аттач"></div>
        <div id="reminder" data-rel="<?=$CFG->_GET_PARAMS[0];?>" title="Можно напомнить менеджеру об этой активности, втч и себе"></div>
        


    </form>
    
<br clear="all">

    <div class="input-form__music">
    </div>

    <div class="input-form__reminder">
    
    </div>

    <div class="input-form__attachments-image">
    
    </div>
    
            
    <div class="input-form__attachments_files">


    </div>
    
            

    
</div>

<script>
	if( document.getElementById('file_upload'))
	{
		var btn = $('#file_upload');

		 new AjaxUpload(btn, 
		  {
		   data: {'pid_id' : '<?=$CFG->_GET_PARAMS[0];?>'},
		   name: 'file',
		   action: '/static/file_upload/',

		   onSubmit: function() 
		   {

				$(document).ready(function(){
					$("#myModalBox").modal('show');
				});
				
				$(".modal-body").html('<h4 class="modal-title"><center>Идет загрузка файла, подождите пожалуйста...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');     

				
			},
			onComplete: function(file, response) 
			{
    			
				response = $.parseJSON(response);

				if( typeof response.med == 'undefined' || response.med == null )
				{
					return false;
				}
				
			
				switch(response.type)
					{
						case 'audio' :
						
							$('input[name=attach_files_music]').val(jQuery('input[name=attach_files_music]').val() + ',' + response.big);
							$('.input-form__music').append('<div class="add_file"><a href="' + response.big + '" target="_blank" class="cancel-attachment-music">' + response.name + '</a></div>');

						break;
						
						case 'image' :
							$('input[name=attach_files_image]').val(jQuery('input[name=attach_files_image]').val() + ',' + response.big);
							$('.input-form__attachments-image').append('<div class="add_img"><a href="#" class="cancel-attachment-img"><img src="' + response.med + '"/></a></div>');
						break;
						
						case 'other' :
							$('input[name=attach_files]').val($('input[name=attach_files]').val() + ',' + response.big);
							$('.input-form__attachments_files').append('<div class="add_file"><a href="' + response.big + '" target="_blank" class="cancel-attachment-doc">' + response.name + '</a></div>');
						break;
					}
					
				$(document).ready(function(){
					$("#myModalBox").modal('hide');
				});
				
				$(".modal-body").html('');     
				
				e.preventDefault();
		   }
		 }); 
		  

	}
	
	
		$('a.cancel-attachment-music').live("click", function(e)
		{
			var url = $(this).attr('href');
			
			var value = jQuery('input[name=attach_files_music]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');
			
			$('input[name=attach_files_music]').val(value);
			$(this).remove();
			
			e.preventDefault();
		});		
	
		$('a.cancel-attachment-doc').live("click", function(e)
		{
			var url = $(this).attr('href');
			
			var value = jQuery('input[name=attach_files]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');
			
			$('input[name=attach_files]').val(value);
			$(this).remove();
			
			e.preventDefault();
		});		

		
		$('a.cancel-attachment-img').live("click", function(e)
		{
			var url = $(this).find('img').attr('src');
			var value = jQuery('input[name=attach_files_image]').val().split(',');
				value.splice( jQuery.inArray(url, value), 1 );
				value.join(',');
			
			$('input[name=attach_files_image]').val(value);
			$(this).remove();
			
			e.preventDefault();
		});		


</script>




