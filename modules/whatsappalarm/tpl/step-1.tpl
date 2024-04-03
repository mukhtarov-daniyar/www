<h2>Whatsapp Inbox</h2>


    
<div class="white">       
 <div class="tab-pane active" id="my-task">
                <article class="vacancies_body row"> 
                                   <table class="users tasks">
                                    <tr>  
                                        <th><strong>Дата</strong></th>
                                        <th><strong>Текст</strong></th>
                                        <th><strong>Запись</strong></th>
                                        <th><strong>Статус</strong></th>
                                    </tr>
                                           
                                <?
                                    $sql = getSQLArrayO("SELECT * FROM my_alarm_whatsapp WHERE visible='1' AND user_id = {$CFG->_GET_PARAMS[0]}  AND status = 0 order by id DESC ");
                                    
                                    for ($i=0; $i<sizeof($sql); $i++)
                                    {	
                                        ?>
                                            <tr <? if($sql[$i]->status == 0) {echo 'class="offtask"';}?>>
                                                <td class="offtask"><? echo dateSQL2TEXT($sql[$i]->cdate, "DD.MM.YY hh:mm");?></td>
                                                <td class="text"><a href="/count_alarm_whatsapp/record/<?=$sql[$i]->page_id;?>/<?=$sql[$i]->id;?>"><?=$sql[$i]->text;?></a></td>
                                                <td class="offtask"><a href="/count_alarm_whatsapp/record/<?=$sql[$i]->page_id;?>/<?=$sql[$i]->id;?>"><? $page_id = getSQLRowO("SELECT name_company FROM my_news WHERE id='{$sql[$i]->page_id}' "); echo $page_id->name_company; ?></a></td>
                                                <td class="text"><? if($sql[$i]->status == 0) {echo ' <a href="/count_alarm_whatsapp/record/'.$sql[$i]->page_id.'/'.$sql[$i]->id.'">Прочитать</a> ';} else {echo 'Прочитано';}; ?></td>
                                            </tr>
                                    
                                        <?
                                    }
                                ?>      

                                </table>
                                              
                </article>
            </div>
</div>
