
        <article class="vacancies_body row" id="<?=$o->id;?>">
            <div class="col-md-12 back">
                <a onclick="history.back(); return false;"><i class="glyphicon glyphicon-arrow-left"></i> <?=$CFG->Locale["back"];?></a>
            </div>
                <div class="table-responsive">
                <br clear="all">
                    <table class="view">
                    
                      <tr>
                        <td><p>Индификатор записи</p></td>
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

                    <? if($o->type_company_id > 0) {?>
                      <tr>
                        <td><p>Тип компании</p></td>
                        <td><p><? 
                        
                        	$array = explode(",", $o->type_company_id);                
                            for ($y=0; $y<sizeof($array); $y++)
                            {
                                echo $str = SelectData('type_company', $array[$y]).'<br>'; 
                            }

                        ?></p></td>
                      </tr>
                   <? }?>


                    <? if($o->site) {?>
                      <tr>
                        <td><p>Сайт</p></td>
                        <td><p><a href="http://<?=$o->site?>" target="_blank" style=" color:#09F; text-decoration:underline">http://<?=$o->site?></a></p></td>
                      </tr>
                   <? }?>



                      <tr>
                        <td><p>Почтовый ящик</p></td>
                        <td><p><?=$o->email;?></p></td>
                      </tr>

                      <tr>
                        <td><p>Дополнительный почтовый ящик</p></td>
                        <td><p><?=$o->other_email;?></p></td>
                      </tr>                      
                      
                      <? if($o->tel) {?>
                      <tr>
                        <td><p>Телефон</p></td>
                        <td><p><?=$o->tel?></p></td>
                      </tr>
                   <? }?>

                    
                      
                      
                      <tr>
                        <td><p>Город</p></td>
                        <td><p><?=SelectData('city',$o->city_id);?></p></td>
                      </tr>
                      <tr>
                        <td><p>ФИО генерального директора/учредителя/хозяина</p>
                            <p style="font-size:12px;">Мобильный</p>
                            <p style="font-size:12px;">Почта</p>
                            <p style="font-size:12px;">Дата рождения</p></td>
                        <td><p><?=$o->name_director;?></p>
                            <p style="font-size:12px;"><?=$o->name_director_mobile;?></p>
                            <p style="font-size:12px;"><?=$o->name_director_email;?></p>
                            <p style="font-size:12px;"><?=dateSQL2TEXT($o->name_director_cdata, "DD MN YYYY")?></p></td>
                      </tr>
                      <tr>
                        <td><p>ФИО контактирующего/заинтересованного лица</p>
                            <p style="font-size:12px;">Мобильный</p>
                            <p style="font-size:12px;">Почта</p>
                            <p style="font-size:12px;">Дата рождения</p></td>
                        <td><p><?=$o->name_client;?></p>
                            <p style="font-size:12px;"><?=$o->name_client_mobile;?></p>
                            <p style="font-size:12px;"><?=$o->name_client_email;?></p>
                            <p style="font-size:12px;"><?=dateSQL2TEXT($o->name_client_cdata, "DD MN YYYY")?></p></td>
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
                                        $COM = new Comments($CFG->oPageInfo->id);
                                        $COM->setPostId($o->id);
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
                        <td><p><? $resp = SelectDataRowOArray('users',$o->manager_id,0);?><a href="/profile/view/<?=$resp->id?>/" target="_blank"><?=$resp->name?></a></p></td>
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

		<br clear="all">
    	<?
        
        
        $resp = SelectDataRowOArray('users',$o->manager_id, 0);
       
      
       
            if($o->manager_id == $CFG->USER->USER_ID || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 143 || $CFG->USER->USER_ID == 219
            
            || 
            	($CFG->USER->USER_DIRECTOR_ID == $resp->user_id && $CFG->USER->USER_BOSS == 1)
            )
            { ?>
                <center><a href="/profile/edit_office/<?=$o->id?>/" class="btn">Редактировать</a></center>
           <?} ?>
    
    

        <?
            if(($CFG->USER->USER_STATUS == 1) || $CFG->USER->USER_ID == 143)
            {
                $url = rtrim($_SERVER['REQUEST_URI'], "/");
                echo '<br><center><a href="'.$url.'/delete" class="btn">Удалить</a></center>';
            }
        ?>          
           
           
    
        </article>
