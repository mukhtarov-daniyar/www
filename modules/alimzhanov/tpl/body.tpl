<style>

.col-md-7.record { float:left; display:block;transition:0.4s; }
.col-md-5.comment-block-position { float:left; padding-left:10px;}

.col-md-7.record.block { display:none;transition:0.4s; }
.col-md-5.comment-block-position.block {padding-left:10px; display:block; min-width:50%; width:100% !important;  float: right;transition:0.3s;transform: translate(0,90px);}
.col-md-5.comment-block-position.block .comment-block{ width:70%; margin:0 auto}

.record .object { background:#F8F9FB;}
.record .object .title  { display:block; border-bottom:1px #E1E7E7 solid; margin:10px;}

.record .object .title h3 { text-transform:uppercase; font-family:'segoeui_sb'; font-size:16px; display:block; padding:0; margin:0;  text-align:left; padding-top:10px;padding-bottom:10px;}
.record .object .title h3 a{ color:#43484E}
.record .object .title span { float:right; position:relative; top:3px;color:#43484E}

.record .object .avatar img { width:80%; height: auto; border-radius:50%;}
.record .object .avatar { text-align:center}

.record .object .contact { padding-right:10px;}
.record .object .gran { padding-right:20px; padding-left:10px;}
.record .object .textS { padding-right:20px; padding-left:10px;}

.record .object .obj  { margin-top:0px;}
.record .object .obj p { padding:0; margin:0 !important;  text-align:left; padding:5px !important; padding-left:0; padding-top:0; font-size:13px; display:block; width:100%; color:#75767B}
.record .object .obj p.gray { background:#fff; border-radius:5px; padding-top:5px; padding-left:10px; padding-bottom:5px; font-size:14px; max-height:105px; overflow:hidden; font-family:'segoeui'; color:#3F414E; font-weight:100}
.record .object .obj p.text { background:#fff; border-radius:5px; padding-top:5px; padding-left:10px !important; padding-bottom:5px; font-size:14px;font-family:'segoeui'; color:#3F414E; font-weight:100 }
.record .object .obj p strong{font-size:13px; font-family:'segoeui'; color:#3F414E; font-weight:100}

.record .object  .col-md-12.user .obj{ width:32%; display:inline-block; padding-left:10px;}
.record .object  .col-md-12.user .obj p.gray {overflow:hidden; white-space:nowrap}
.record .object  .col-md-12.user .obj p.gray a{ padding:0; margin:0; font-size:13px; font-family:'segoeui'; color:#F74140; font-weight:100}

.record .object  .col-md-12.userS .obj{ width:100%; display:block; padding-left:10px; padding-right:10px;}
.record .object  .col-md-12.userS .obj p.gray a{ padding:0; margin:0; font-size:13px; font-family:'segoeui'; color:#F74140; font-weight:100}

.record .object .edit_buttom { text-align:right; padding-right:10px; padding-bottom:20px; display:block}
.record .object .edit_buttom a { background:#F74140; color:#fff; padding:5px 10px; border-radius:5px;  font-size:13px; font-family:'segoeui'; margin-left:5px; }

.tooltip div:nth-child(2n) { padding:5px 10px !important; }
.comment-block__comment-post{ margin-top:12px !important; display:block; width:100%; height:auto; }

.col-md-12.name { display:block; margin-bottom:5px; white-space:nowrap}
.col-md-12.name .avatar{ text-align:left; width:60px;}
.col-md-12.name .avatar img{ width:70%; margin-left:10%; border: solid 1px #E7E7E7; border-radius:50%; vertical-align:middle;}

.col-md-12.name .name{ text-align:left; display:block; white-space:nowrap}
.col-md-12.name .name span{display:block; white-space:nowrap}
.col-md-12.name .name a{font-family:'segoeui_sb'; font-size:14px; color:#3F434F; display:block; padding-top:7px; text-transform:uppercase; vertical-align:middle; line-height:15px; }

.money .status{ display:inline-block; border-radius:3px; font-family:'segoeui'; font-size:12px; padding:2px 10px; padding-left:5px; color:#fff; vertical-align:middle }
.money .status a{ color:#fff;font-family:'segoeui'; font-size:12px;}
.money .status img{ vertical-align:top; position:relative; top:3px;}

.money .status.red{ background:#A3B022;  width:auto; margin-top:2px; margin-left:60px;}
.money .status.premiumminus{background:#404A4C;  width:auto; margin-top:2px; margin-left:60px;}
.money .status.premiumminus a{ display: inline-block; padding:0; white-space:nowrap}


.col-md-12.name .name span.cdate{ text-align:left;font-family:'segoeui'; padding-top:3px; font-size:12px; color:#76767E;}
.col-md-12.name .name span.cdate a{ display:inline-block !important; line-height:none !important; font-size:11px !important; padding:0 !important;  font-family:'segoeui'; text-decoration:underline; color:#76767E; vertical-align:top; padding-left:5px !important;}

.col-md-12.name .data { text-align:center;}
.col-md-12.name .data .detele{ padding-left:5px;padding-right:5px;color:#76767E; }
.col-md-12.name .data .detele a{ color:#F84241; font-family:'segoeui'; padding-top:2px; font-size:12px; }

.comment-post-body { text-align:left;font-family:'segoeui'; margin-top:50px; clear:both; font-size:13px; line-height:15px; border-radius:10px; padding:5px 10px !important; padding-bottom:12px !important;  background:#F8F9FB; color:#434949}

.comment-block__comment-post.task_1  .comment-post-body { background:#FEDADA;}
.comment-block__comment-post.task_2  .comment-post-body { background:#FFF4D4; }
.comment-block__comment-post.task_3  .comment-post-body { background:#B2EDCF; }


.comment-block__comment-post.comment-post_size-2 { margin-left:20%; width:80%; }
.comment-block__comment-post.comment-post_size-3 { margin-left:20%; width:80% }
.comment-block__comment-post.comment-post_size-4 { margin-left:30%; width:70% }

.comment-block__comment-post.comment-post_size-2 .comment-post__reply-button { display:none}
.comment-block__comment-post.comment-post_size-3 .comment-post__reply-button { display:none}
.comment-block__comment-post.comment-post_size-4 .comment-post__reply-button { display:none}

.comment-post-body { position:relative}
.comment-post__reply-button { display:block; text-align:right;  margin-top:5px;}
.rr.active { display:block; margin-top:5px; margin-bottom:10px;}

.comment-block__comment-post.comment-post_size-2 .money .status.red{ margin-left:0 !important; }

</style>  
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