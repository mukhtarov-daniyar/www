<div class="white">

        <article class="vacancies_body row" id="<?=$o->id;?>">
            <div class="col-md-12 back">
                <a href="/alimzhanov-history/"><i class="glyphicon glyphicon-arrow-left"></i> Вернуться к списку</a>
            </div>
            
            
         <div class="col-md-12">
         	<div class="col-md-6 record-static">
                <table class="view_3">
                  <tr>
                    <td><p>Идентификатор записи (ID)</p></td>
                    <td><p><strong style="font-size:18px">*<?=$o->id;?></strong></p></td>
                  </tr>
                <? if($o->logo_company ==! "") {?>
                  <tr>
                    <td><p>Изображение</p></td>
                    <td><p><a href="<?=$o->logo_company;?>" class="change" title="<?=$o->name_company;?>" target="_blank"><img id="upAvatar" src="<?=$o->logo_company_mini;?>" alt="" /></a></p></td>
                  </tr>
               <? }?>
               
                  <tr>
                    <td><p>Название компании</p></td>
                    <td><p><?=$o->name_company;?></p></td>
                  </tr>

               
                  
                  
                  <style>
				  	.about td{ text-align:left !important; font-family:'Helvetica_r' !important;}
				  	.about td h3 { padding:0; margin:0; font-size:18px; text-transform:uppercase; margin-bottom:10px; margin-top:10px;}
				  	.about td .obj {font-size:12px; padding-bottom:10px; border-bottom:dotted 1px #D3D3D3; margin-bottom:10px; margin-left:30px;}
				  	.about td .obj p {font-size:14px; font-family:'Helvetica_medium' !important; }
				  </style>
                  
                  <tr class="about">
                    <td colspan="2">
                   
                <? if($o->info ==! "") {?>
                    <div class="obj"> 
                    	<p>Информация</p>                   
                       <?=replace_r_n_br($o->info)?>
                    </div>
				<? } ?>                  
                  
                             
                  
                    </td>
                  </tr>
                              
                  
                  
              
                <? if($o->attach_files ==! "") {?>                      
                  <tr>
                    <td><p>Прикрепленные фото</p></td>
                    <td><p>
                    
                          <div class="att_file">  
                            <div class="input-form__attachments"> 
                                
                                <div class="input-form__attachments-image">
                                    <? 
                                        if ($o->attach_files != "" || $o->attach_files != 0)
                                        {
                                            $images = explode(",", $o->attach_files_mini);
                                            $images_big = explode(",", $o->attach_files);

                                            
                                            for($n=0; $n<sizeof($images); $n++)
                                            {
                                                if($images[$n] != "" || $images[$n] != 0)
                                                {
                                                    ?><a href="<?=$images_big[$n]?>" target="_blank"><img class="image" src="<?=$images[$n];?>"/></a> <?
                                                }
                                            }
                                        }
                                    ?>
                               </div>
                                 
                            </div>        
                           </div>                        
                    </p></td>
                  </tr>
                <? }?>
                  
                  
                <? if($o->attach_files_doc ==! "") {?>                      
                  <tr>
                    <td><p>Прикрепленные файлы</p></td>
                    <td><p>
                    
                          <div class="att_file">  
                            <div class="input-form__attachments"> 
                                <div class="input-form__attachments-files">
                                
                                    <? 
                                        if ($o->attach_files_doc != "" || $o->attach_files_doc != 0)
                                        {
                                            $doc = explode(",", $o->attach_files_doc);
                                            
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
                                
                            </div>        
                           </div>                        
                    </p></td>
                  </tr>
                  
                <? }?>
    
                  
          
                  <tr>
                    <td><p>Заполняющий менеджер</p></td>
                    <td><p><? $resp = SelectDataRowOArray('users',$o->manager_id,0);?><a href="/profile/view/<?=$resp->id?>/" target="_blank"><?=$resp->name?></a></p></td>
                  </tr>
                  
                  <? if($o->edate == !null) {?>
                  <tr>
                    <td><p>Дата редактирования записи</p></td>
                    <td><p><?=dateSQL2TEXT($o->edate, "DD.MM.YY, hh:mm")?></p></td>
                  </tr>
                  <? }?>
                  
                  <tr>
                    <td><p>Дата создания записи</p></td>
                    <td><p><?=dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></p></td>
                  </tr>
                  
                  <tr>
                    <td><p>Количество просмотров</p></td>
                    <td><p><?=$o->view;?></p></td>
                  </tr>

                </table>            
            </div>
            
         	<div class="col-md-6 comment-block-position">
            
            	<p><strong style="display:block; font-size:16px; margin-bottom:10px; padding-top:10px;">Рабочие заметки от менеджеров</strong> </p>
            
                <div class="comment-block">
                    <div class="comment-messages">		
                        <?
                            $COM = new Comments($CFG->oPageInfo->id);
                            $COM->setPostId($o->id);
                            $COM->showFormComment();
                            $COM->getList();
                            $COM->setLang($CFG->SYS_LANG);
                            $COM->onPostComment();		
                        ?>
                    </div>
                </div>            
            </div>
         </div>
            

        <br clear="all">  
		<br clear="all">
    	<?
        
        
        $resp = SelectDataRowOArray('users',$o->manager_id, 0);

       
            if($o->manager_id == $CFG->USER->USER_ID || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 219
            
            || 
            	($CFG->USER->USER_DIRECTOR_ID == $resp->user_id && $CFG->USER->USER_BOSS == 1)
            )
            { 
                if($CFG->oPageInfo->xcode == 'alimzhanov-history')
                {
                	echo '<center><a href="/profile/edit_alimzhanov/'.$o->id.'/" class="btn">Редактировать</a></center>';
                }

            } ?>
    
    
           
           
        <?
            if(($CFG->USER->USER_STATUS == 1) || $CFG->USER->USER_ID == 85)
            {
                echo '<br><center><a href="#" class="btn delete" data-id="'.$o->id.'">Удалить</a></center>';
            }
        ?>          
           
           
    
        </article>


</div>