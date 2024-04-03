
        <article class="vacancies_body row" id="<?=$o->id;?>">

                <div class="table-responsive">
                <br clear="all">
                    <table class="view">

                    
                    <? if($o->logo_company ==! "") {?>
                      <tr>
                        <td><p>Изображение</p></td>
                        <td><p><a href="<?=$o->logo_company;?>" class="change" title="<?=$o->name_company;?>" target="_blank"><img id="upAvatar" src="<?=$o->logo_company_mini;?>" alt="" /></a></p></td>
                      </tr>
                   <? }?>
                   
                      <tr>
                        <td><p>Название компании</p></td>
                        <td><p><strong><?=$o->name_company;?></strong></p></td>
                      </tr>


                      
                       <tr id="add_taks">
                        <td><p>Базовая заметка о клиенте. <br>Историческая справка. <br>Контактные данные.</p></td>
                        <td><p><?=replace_r_n_br($o->info)?></p></td>
                      </tr>
                      
                       <tr>
                        <td style="vertical-align:text-top"><p>Рабочие заметки <br>от менеджеров</p></td>
                        <td>

                        
                            
                            <div class="comment-block">
                                <div class="comment-messages">		
                                    <?
                                        $COM = new Comments(868);
                                        $COM->setPostId($CFG->USER->PERSONAL_PAGE);
                                        $COM->setTemplateForm('./tpl/templates/personal/addComment.tpl');
                                        $COM->showFormComment();
                                        $COM->getList();
                                        $COM->setLang($CFG->SYS_LANG);
                                        $COM->onPostComment();		
                                    ?>
                                </div>

                            </div>
                        
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
                        <td colspan="2"><p>&nbsp;</p><p>&nbsp;</p></td>
                      </tr>
                      <tr>
                        <td><p>Заполняющий менеджер</p></td>
                        <td><p><? $resp = SelectDataRowOArray('users',$o->manager_id,0);?><strong><?=$resp->name?></strong></p></td>
                      </tr>
                      
                      <? if($o->edate == !null) {?>
                      <tr>
                        <td><p>Дата редактирования записи</p></td>
                        <td><p><?=dateSQL2TEXT($o->edate, "DD MN YYYY, hh:mm")?></p></td>
                      </tr>
                      <? }?>
                      
                      <tr>
                        <td><p>Дата создания записи</p></td>
                        <td><p><?=dateSQL2TEXT($o->cdate, "DD MN YYYY, hh:mm")?></p></td>
                      </tr>
                    </table>
                </div>        

 
    
        </article>
