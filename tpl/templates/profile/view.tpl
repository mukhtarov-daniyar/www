<?
	$MODULE_ = getFullXCodeByPageId($CFG->pid);

?>




<style>
h2.profile  { margin-bottom:30px !important;}
h2.profile .clear { clear:both}
h2.profile .left { float:left}
h2.profile .right { float:right}
h2.profile .right .obj div{ margin-right:20px; display:inline-block; font-size:22px;}
h2.profile .right .obj span{ font-size:12px; text-transform: none;font-family:'segoeui'; padding-left:2px; }

.tabbable #myTab { text-align:center;font-family:'segoeui'; font-size:11px; text-transform:uppercase; padding-left:10px;}
.tabbable #myTab li { margin-left:10px; margin-right:10px;}
.tabbable #myTab li a{ background:#EFF3F6; color:#46474B; padding:7px 10px;}
.tabbable #myTab li.active a{ background:#fff; color:#F54142}




.tsort_1 > thead > tr > th.sel,
.tsort_2 > thead > tr > th.sel,
.tsort_3 > thead > tr > th.sel,
.tsort_4 > thead > tr > th.sel {background: url('https://lh3.googleusercontent.com/JOVAJ_X2eG14J9wpYCCNtovtYngk_9_QK7ZI8mYRi2OaQxYN35m0DpABI_w9UeDIRiYfu2trp8TBV82_pl8pvq5I7dtUU4WTT0To1yIhI5_wMo5c3_fsYwGOmrUDZvkggMEiS6g2p4g_pmCGPIACf1M0wwPZTGFnuJ6IyGyoUfr4MqabG_8gcuAelg7dxMML3f-qhrj6TpbvFEOk4S6YjXCBNCEVzmBtnf6Zsbh3iBtxYY9iYM7DoGQ0l6y2RYyMlBnE38flZNZnbEI3yBcrQ_oeTcNLNeX_OXQDlIaQpIs24woeFDo3dUNqoP8rAIjShaLME_o0sVzE3X3YfHWrIbaP5yRxkkY6Y8iKByZzaqMuiGhxqn0xfXKw_xQWBuRwkhyg9TB8FUe02rdQAtDlfCZDhZvlPFzuQZfo9LGwu1YuDC9dCnifwM0eWvltJQkn7DKlodkJdIiDl1DvfxrRQdgB8BThSSt6Ll2Hv_keE0CbHn6MLIP8E6i7ZbrH6dTWeFyaBbI2GfrGvHBS9je1T8xeV-hwP9SGO2U9XdDkXA=w7-h5-no') 50% 0 no-repeat;
}
.tsort_1 > thead > tr > th.sel.asc,
.tsort_2 > thead > tr > th.sel.asc,
.tsort_3 > thead > tr > th.sel.asc,
.tsort_4 > thead > tr > th.sel.asc {background-image:url('https://lh3.googleusercontent.com/SriDaBGVSHwn0jr8-o-oU5kIC3kg2IKg4nXxmo0JWG7IJnGjWFqlnhUJ3BmVkGGrGyRxTw2XYQ4DkgkbwRhhnZBRT_lb1Ka-TQqQOZlAc7STBckQi7SxsKw9CLmXut_Jva90oCYjE2XjFGDFCxOucoVrwrILXr45v3Pg4Z546dFhykZKVmU5CwItvVSPSQAWx7Py2tVrFlL04uC90jZMJIFp0SmZgmE7vJjJQglLC3YxHMFQDVST2JEkTOeXUTovcHlsgRTeB1ltAOFbe3ZokL01u004JBjEYTvPpA1-5mJhrbfadGtLpvpDg2TEYItps1Jber8P_rAb_kmBYGHXjXacoA_gpbA6QsNOEj4lJ_QBdG8OdnWdGTbrfkZJW8j_m7bPF8Te6W0UPBT5_L0OrEVFnxtLTqPOwDthogMNDlu3rOuwe0QGgjIQVpG6YEHWPNH4A93uP5k6LjpxEbmvopEpp07lN_2FlOp_bsWA70t_PAttx5AJ9rR7kqrHfOxWUJQEenRTQp73oIIfnQ8xAfhK9RoNXKhrbipJEA=w7-h5-no');background-position:50% 100%;
}

</style>

<h2 class="profile">
	<div class="left">
    	<img alt="" src="/tpl/img/new/icon/8.png">Профиль: <?=$data['name']?>
    </div>
    <div class="right">
        <div class="obj">
            <div><? $id = $data['id']; $response = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}news WHERE visible=1 AND manager_id = {$id} AND page_id = 868 "); echo count($response);?><span>записи</span></div>
            <div><? $response = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND user_id = {$id} AND status_taks = 0"); echo count($response);?><span>заметки</span></div>
            <div><? $response = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND doer = {$id} AND status_taks = 1 ORDER BY cdate DESC"); echo count($response);?><span>задач</span></div>
        </div>
    </div>
    <div class="clear"></div>
</h2>
<?
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>
<div class="tabbable">
    <ul class="nav nav-tabs" id="myTab">
      <li class="active"><a href="#user" data-toggle="tab">Профиль</a></li>
      <li><a href="#home" data-toggle="tab">Активность</a></li>
      <li><a href="#deal" data-toggle="tab">Сделки</a></li>
      <? if(big_access('view_money') || $data['id'] == $CFG->USER->USER_ID ) {?> <li><a href="#money" data-toggle="tab">Финансы</a></li> <? } ?>
      <li><a href="#notifications" data-toggle="tab">Извещения</a></li>
      <? if($CFG->USER->USER_BOSS == 1 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 133 || $CFG->USER->USER_ID == 86) {?><li><a href="#access" data-toggle="tab">Доступы</a></li><? }?>
      <? if($CFG->USER->USER_BOSS == 1 || $CFG->USER->USER_ID == 1) {?><li><a href="#histery" data-toggle="tab">История</a></li><? }?>
			<? if($CFG->USER->USER_ID == $data['id'] || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85  || $CFG->USER->USER_ID == 536) { ?><li><a href="#product_boss" data-toggle="tab">Товарный БОСС</a></li> <? } ?>
      <!--li><a href="#birthday" data-toggle="tab">Дни рождения клиентов</a></li!-->
    </ul>


    <div class="white">
    	<div class="tab-content">



       <div class="tab-pane" id="histery">
            <article class="vacancies_body row">
                <div class="table-responsive">
                    <?	$response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}histery WHERE user_id='{$data[id]}' ORDER BY cdate DESC limit 50");  ?>
                    <table class="users activS">
                      <tbody>
                        <tr>
                            <th><strong>Дата</strong></th>
                            <th><strong>Адрес</strong></th>
                        </tr>
                               <?
                                $response = array_map("unserialize", array_unique( array_map("serialize", $response) ));

                                for ($i=0; $i<sizeof($response); $i++)
                                    {
                                    	$o = $response[$i];
                               ?>
                        <tr>
                            <td><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></td>
                            <td><a href="<?=$o->url;?>" target="_blank"><?=$o->url;?></a></td>
                        </tr>
                      <? } ?>
                     </tbody>

                    </table>

                </div>
            </article>
        </div>





            <div class="tab-pane active" id="user">
             <div class="row tab">
                <div class="col-md-12">
                    <h3>Общая информация</h3>
                         <div class="row info">
                            <div class="col-md-4">
                            	<div class="avatar">
                                	<img id="upSAvatar" src="<? echo makePreviewName($data['avatar'], 200, 200, 0);?>" alt="" />
                                </div>
                                <div class="obj">
                                    <p>Компания</p>
                                    <p class="gray"><? $users = SelectDataRowOArray("company", $data["user_id"]); echo $users->name;?></p>
                                </div>
                                <div class="obj">
                                    <p>Должность</p>
                                    <p class="gray"><? echo $data["position"];?></p>
                                </div>
                                <div class="obj">
                                    <p>Куратор</p>
                                    <p class="gray"><? $users = SelectDataRowOArray("users", $data["curator"],0,1); echo $users->name;?></p>
                                </div>
                                <div class="obj">
                                    <p>Регистрация</p>
                                    <p class="gray"><?=dateSQL2TEXT($data['cdate'], "DD.MM.YYYY, hh:mm")?></p>
                                </div>
                                <div class="obj">
                                    <p>Был(а) на сайте</p>
                                    <p class="gray"><?=dateSQL2TEXT($data['vdate'], "DD.MM.YYYY, hh:mm")?></p>
                                </div>

																<div class="obj">
                                    <p>Дополнительно</p>
                                    <p class="gray"><a href="/taks/<?=$data['id'];?>" style="color:#F94342; text-decoration:underline">Мои задачи</a></p>
                                </div>

								<? if($data['enddate'] != '') {?>
                                <div class="obj">
                                    <p>Был(а) уволен</p>
                                    <p class="gray"><?=dateSQL2TEXT($data['enddate'], "DD.MM.YYYY, hh:mm")?></p>
                                </div>
                                <? } ?>

                            </div>

                            <div class="col-md-8">

                                <div class="col-md-12">
                                    <div class="obj">
                                        <p>Ф.И.О.</p>
                                        <p class="gray"><strong><?=$data['name']?></strong></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="obj">
                                        <p>Обо мне</p>
                                        <p class="gray"><strong><?=$data['info']?></strong></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="obj">
                                        <p>Баланс</p>
                                        <p class="gray"><? $m = date('m')*1;  $year = date('Y')*1; echo SelectDataRowOMonth($year, $m, $data['id']); ?> <?=$CFG->USER->USER_CURRENCY;?></span></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="obj">
                                        <p>Пол</p>
                                        <p class="gray"><? $sex = array(0=>'Не указан', 1=>'Мужской', 2=>'Женский'); echo $sex[$data['pauls']]; ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="obj">
                                        <p>Город</p>
                                        <p class="gray"><? $city = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' and id='{$data['city']}' ORDER BY pos DESC"); echo $city->name;?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="obj">
                                        <p>Дата рождения</p>
                                        <p class="gray"><?=dateSQL2TEXT($data["dob"],"DD.MM.YY");?></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="obj">
                                        <p>E-mail</p>
                                        <p class="gray"><?=$data['email']?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="obj">
                                        <p>Телефон</p>
                                        <p class="gray"><a href="https://web.whatsapp.com/send?phone=<?=$data['mobile']?>" style="color:#F94342; text-decoration:underline" target="_blank"><?=$data['mobile']?></a></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="obj">
                                        <p>Страница для премирование</p>
                                        <p class="gray"><? $news = SelectDataRowOArray("news", $data["taks_id"]); ?><a href="<?=getFullXCodeByPageId($news->page_id);?><?=$news->id?>" style="color:#F94342; text-decoration:underline"><?=$news->name_company;?></a></p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="obj">
                                        <p>Страница для ОС</p>
                                        <p class="gray"><? $news = SelectDataRowOArray("news", $data["os_id"]); ?><a href="<?=getFullXCodeByPageId($news->page_id);?><?=$news->id?>" style="color:#F94342; text-decoration:underline"><?=$news->name_company;?></a></p>
                                    </div>
                                 </div>

                                <div class="col-md-12">
                                    <div class="obj">
                                        <p>Должностные инструкции</p>
                                        <p class="gray"><? $news = SelectDataRowOArray("news", $data["official_id"]); ?><a href="<?=getFullXCodeByPageId($news->page_id);?><?=$news->id?>" style="color:#F94342; text-decoration:underline"><?=$news->name_company;?></a></p>
                                    </div>
                                 </div>


                                <div class="col-md-12">
                                    <div class="obj">
                                        <p>Страница физ. лицо</p>
                                        <p class="gray"><a href="/person/<?=$data["chl_id"];?>" target="_blank" style="color:#F94342; text-decoration:underline"><? echo SelectData_live("face", $data["chl_id"]);?></a></p>
                                    </div>
                                 </div>




                                <div class="col-md-12 edit">
                                	<? if(($data['id'] == $CFG->USER->USER_ID) || $CFG->USER->USER_ID == 1 || $data['user_id'] == $CFG->USER->USER_ID){?><br><a href="/profile/edit/<?=$data['id'];?>">Редактировать</a><br clear="all"><? } ?>
                                </div>

                                <div class="col-md-12 edit">
                                	<? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 86 || $CFG->USER->USER_ID == 90 || $CFG->USER->USER_ID == 153){?><br><a href="#" class="off_crm">Отключить систему</a><br clear="all"><br clear="all"><? } ?>
                                </div>


                        	</div>
                        </div>
                </div>


            </div>
		</div>





       <div class="tab-pane" id="notifications">
            <article class="vacancies_body row">
                <div class="table-responsive">
                    <?	$response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}accessrecord WHERE autor_id='{$data[id]}'  AND visible = 1  ORDER BY cdate DESC limit 10");  ?>
                    <table class="users notifications">
                      <tbody>
                        <tr>
                            <th><strong>№ записи</strong></th>
                            <th><strong>Дата</strong></th>
                            <th><strong>Компания</strong></th>
                            <th><strong>Текст заметка</strong></th>
                        </tr>
                               <?
                                $response = array_map("unserialize", array_unique( array_map("serialize", $response) ));

                                for ($i=0; $i<sizeof($response); $i++)
                                    {
                                    	$o = $response[$i];
                               ?>
                        <tr>
                            <td><a href="<? echo getFullXCodeByPageIdUrl($o->page_id);?><?=$o->page_id;?>/"><strong>*<?=$o->page_id?></strong></a></td>
                            <td style="white-space:nowrap"><a href="<?=getFullXCodeByPageIdUrl($o->page_id);?><?=$o->page_id;?>/"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></a></td>
                            <td><a href="<?=getFullXCodeByPageIdUrl($o->page_id);?><?=$o->page_id;?>/"><? $name = SelectDataRowOArray('news', $o->page_id);  echo $name->name_company;?></a></td>
                            <td><a href="<?=getFullXCodeByPageIdUrl($o->page_id);?><?=$o->page_id;?>/#<?=$o->parent_id?>"><? $text = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE id='{$o->parent_id}'"); echo utf8_substr(strip_tags($text->text), 0, 120) ;?>...</a></td>
                        </tr>


                      <? } ?>
                     </tbody>

                    </table>

										<div class="col-md-12 more" style="border-top:solid 1px #ccc; padding-top:20px; margin-bottom:50px;">
	                  	<div class="load_notifications" data-id="<?=$data[id];?>" style="padding:7px 20px;">Загрузить еще</div>
	                  	<div class="static notifications">Загружено  <span class="rebut_notifications">10</span>
											</div>
	                  </div>

                </div>
            </article>
        </div>



<style>
.access_body { display:block; margin-left:40px !important; margin-top:20px !important; margin-bottom:20px !important;}
.access_body label { display:block; cursor:pointer; font-family: 'segoeui'; font-weight:100}
.access_body label.checkbo.act {font-family: 'segoeui_sb';}
.access_body .obj { display:block; margin-bottom:10px;}
</style>

       <div class="tab-pane" id="access">
            <article class="vacancies_body row">
                <div class="table-responsive">
       				<div class="access_body">
                    <form class="access" method="POST" enctype="multipart/form-data">
                    	<input type="hidden" id="user_id" name="type_0" value="<?=$data['id']?>" />


                        <!--div class="obj">
                            Видеть «Клиентские записи»
                            <select name="view_record_access" class="selectpicker show-tick" id="view_record_access">
                                <option value="0" <? if($data['view_record_access'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_record_access'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>


                        <div class="obj">
                            Видеть «Служебные записи»
                            <select name="view_office_access" class="selectpicker show-tick" id="view_office_access">
                                <option value="0" <? if($data['view_office_access'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_office_access'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div!-->


												<div class="obj">
                            Видеть «Склад и реализации компании»
                            <select name="view_warehouse" class="selectpicker show-tick" id="view_warehouse">
															<option value="0" <? if($data['view_warehouse'] == 0) { echo 'selected';}?>>Отключено</option>
															<? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1) {?>
								              <option value="1" <? if($data['view_warehouse'] == 1) { echo 'selected';}?>>Включено</option>
															<? }?>
                            </select>
                        </div>

												<div class="obj">
                            Видеть «Расходы ЛИЛ»
                            <select name="view_expenses_lil" class="selectpicker show-tick" id="view_expenses_lil">
															<option value="0" <? if($data['view_expenses_lil'] == 0) { echo 'selected';}?>>Отключено</option>
															<? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1) {?>
								              <option value="1" <? if($data['view_expenses_lil'] == 1) { echo 'selected';}?>>Включено</option>
															<? }?>
                            </select>
                        </div>


											 <hr>

                         <div class="obj">
                             Функция переноса заметок «В сделки-компании-служебные»
                             <select name="transfer_access" class="selectpicker show-tick" id="transfer_access">
                                 <option value="0" <? if($data['transfer_access'] == 0) { echo 'selected';}?>>Отключено</option>
                                 <option value="1" <? if($data['transfer_access'] == 1) { echo 'selected';}?>>Включено</option>
                             </select>
                         </div>

                        <div class="obj">
                            Множественный выбор заметок «Transfer | Удалить»
                            <select name="note_select_access" class="selectpicker show-tick" id="note_select_access">
                                <option value="0" <? if($data['note_select_access'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['note_select_access'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>


                        <div class="obj">
                            Видеть «Cделки»
                            <select name="view_deal_access" class="selectpicker show-tick" id="view_deal_access">
                                <option value="0" <? if($data['view_deal_access'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_deal_access'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>


                        <div class="obj">
                            Может удалять клиенские, служебные, сделки, физ-лица.
                            <select name="view_delete" class="selectpicker show-tick" id="view_delete">
                                <option value="0" <? if($data['view_delete'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_delete'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

												<div class="obj">
                            Доступ к отправке Whatsapp сообщений
                            <select name="view_whatsapp" class="selectpicker show-tick" id="view_whatsapp">
                                <option value="0" <? if($data['view_whatsapp'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_whatsapp'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

						<div class="obj">
                            Видеть руководителя продаж в "Упр.учете"
                            <select name="view_ruk" class="selectpicker show-tick" id="view_ruk">
                                <option value="0" <? if($data['view_ruk'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_ruk'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                        <div class="obj">
                            Видеть ФИНАНСЫ сотрудников своей компании в их профилях
                            <select name="view_money" class="selectpicker show-tick" id="view_money">
                                <option value="0" <? if($data['view_money'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_money'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                         <div class="obj">
                            Видеть запись "ПРЕМИРОВАНИЕ" сотрудников своей компании
                            <select name="view_prize" class="selectpicker show-tick" id="view_prize">
                                <option value="0" <? if($data['view_prize'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_prize'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                         <div class="obj">
                            Видеть все скрытые ЗАПИСИ
                            <select name="view_record" class="selectpicker show-tick" id="view_record">
                                <option value="0" <? if($data['view_record'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_record'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                         <div class="obj">
                            Видеть все скрытые ЗАМЕТКИ
                            <select name="view_note" class="selectpicker show-tick" id="view_note">
                                <option value="0" <? if($data['view_note'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_note'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                         <div class="obj">
                            Видеть раздел "Финасововый анализ"
                            <select name="view_salary" class="selectpicker show-tick" id="view_salary">
                                <option value="0" <? if($data['view_salary'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_salary'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                         <div class="obj">
                            Видеть раздел "ГосЗакупки"
                            <select name="view_goszakup" class="selectpicker show-tick" id="view_goszakup">
                                <option value="0" <? if($data['view_goszakup'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_goszakup'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>


                         <div class="obj">
                            Только - Премирование, Обязанности, ОС. Гручик
                            <select name="view_loader" class="selectpicker show-tick" id="view_loader">
                                <option value="0" <? if($data['view_loader'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_loader'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                         <div class="obj">
                            Видит раздел Заводы-Поставщики
                            <select name="view_factories" class="selectpicker show-tick" id="view_factories">
                                <option value="0" <? if($data['view_factories'] == 0) { echo 'selected';}?>>Отключено</option>
                                <option value="1" <? if($data['view_factories'] == 1) { echo 'selected';}?>>Включено</option>
                            </select>
                        </div>

                    	<input type="submit" value="Обновить" class="btn send_data">
                    </form>
                    </div>
                </div>
            </article>
        </div>


<script type="text/javascript">

		$(document).on('click','input.send_data',function(e)
		{
			var user_id = $('.access > #user_id').val();
			var view_money = $('select#view_money').val();
			var view_prize = $('select#view_prize').val();
			var view_record = $('select#view_record').val();
			var view_note = $('select#view_note').val();
			var view_loader = $('select#view_loader').val();
			var view_factories = $('select#view_factories').val();
			var view_deal_access = $('select#view_deal_access').val();
			var note_select_access = $('select#note_select_access').val();
			var view_delete = $('select#view_delete').val();
			var view_whatsapp = $('select#view_whatsapp').val();
			var view_warehouse = $('select#view_warehouse').val();
			var view_expenses_lil = $('select#view_expenses_lil').val();
			var view_ruk = $('select#view_ruk').val();
			var view_salary = $('select#view_salary').val();
			var view_goszakup = $('select#view_goszakup').val();
			var transfer_access = $('select#transfer_access').val();






			$.ajax
			({
				url: "/static/send_data_user/",
				type: "POST",
				data: {"user_id": user_id, "view_money": view_money, "view_prize": view_prize, "view_record": view_record, "view_note": view_note, "view_loader": view_loader, "view_factories": view_factories, "view_deal_access": view_deal_access, "view_delete": view_delete, "view_whatsapp": view_whatsapp, "note_select_access": note_select_access, "view_warehouse": view_warehouse, "view_ruk": view_ruk, "view_salary": view_salary, "view_goszakup": view_goszakup, "view_expenses_lil": view_expenses_lil, "transfer_access": transfer_access},
				cache: true,
				beforeSend: function()
				{
					$(document).ready(function(){
						$("#myModalBox").modal('show');
					});

					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				},
				success: function(response)
				{
					if(response == 1)
					{
						$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Данные пользователя изменены.</h4></div>');
					}
					else
					{
						$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка :(</h4></div>');
					}
				}

			});

			 e.preventDefault();

		});


</script>



        <div class="tab-pane" id="home">
            <article class="vacancies_body row">
                <div class="table-responsive">
									<br>

									<ul class="nav nav-pills" style="text-align:center">
	                  <li class="active"><a data-toggle="tab" href="#home_act_1">Вся активность</a></li>
										<li><a data-toggle="tab" href="#home_act_2">Отчетная работа</a></li>
	                </ul>

									<div class="tab-content">

									<div id="home_act_1" class="tab-pane fade in active">
										<br clear="all">
                    <?
											$response = getSQLArrayO("SELECT id, page_id, text, cdate FROM {$CFG->DB_Prefix}comments WHERE user_id='{$data[id]}' AND visible = 1 AND access = '0' ORDER BY cdate DESC limit 10");
                    ?>
                    <table class="users activS">
                      <tbody>
                        <tr>
                            <th><strong>№ записи</strong></th>
                            <th><strong>Компания</strong></th>
                            <th><strong>Дата заметки</strong></th>
                            <th><strong>Текст заметка</strong></th>
                        </tr>
                           <?

                           $cnt = 0;
                           $response = array_map("unserialize", array_unique( array_map("serialize", $response) ));

                            for ($i=0; $i<sizeof($response); $i++)
                            {
                                    $o = $response[$i];
                                    if($o->page_id)
                                    {
                                    $cnt++;
                                    //if($i > 60) break;
                                    $z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$o->page_id}'");

                           ?>
                        <tr>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><strong>*<?=$o->page_id?></strong></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><? $name = SelectDataRowOArray('news', $o->page_id);  echo $name->name_company;?></a></td>
                            <td style="white-space:nowrap"><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><? echo utf8_substr(strip_tags($o->text), 0, 120);?></a></td>
                        </tr>

                       <? }

                       } ?>
                     </tbody>
                    </table>

										<div class="col-md-12 more" style="border-top:solid 1px #ccc; padding-top:20px; margin-bottom:50px;">
                        <div class="load_activ" data-id="<?=$data["id"]?>" style="padding:7px 20px;">Загрузить еще</div>
                        <div class="static"> Всего <span><? $response = getSQLRowO("SELECT count(id) FROM {$CFG->DB_Prefix}comments WHERE user_id='{$data[id]}' AND visible = 1 AND access = '0' ");
														echo $response->{'count(id)'};?></span> &nbsp; &nbsp; &nbsp; Загружено  <span class="rebut">10</span>
                        </div>
                    </div>

										<br clear="all">
									</div>

									<div id="home_act_2" class="tab-pane ">
										<br clear="all">
										<?
											$response = getSQLArrayO("SELECT id, page_id, text, cdate FROM {$CFG->DB_Prefix}comments WHERE user_id='{$data[id]}' AND visible = 1 AND access = '0' AND important = 1 ORDER BY cdate DESC limit 10");
                    ?>
                    <table class="users activS_important">
                      <tbody>
                        <tr>
                            <th><strong>№ записи</strong></th>
                            <th><strong>Компания</strong></th>
                            <th><strong>Дата заметки</strong></th>
                            <th><strong>Текст заметка</strong></th>
                        </tr>
                           <?

                           $cnt = 0;
                           $response = array_map("unserialize", array_unique( array_map("serialize", $response) ));

                            for ($i=0; $i<sizeof($response); $i++)
                            {
                                    $o = $response[$i];
                                    if($o->page_id)
                                    {
                                    $cnt++;
                                    //if($i > 60) break;
                                    $z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$o->page_id}'");
                           ?>
                        <tr>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><strong>*<?=$o->page_id?></strong></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><? $name = SelectDataRowOArray('news', $o->page_id);  echo $name->name_company;?></a></td>
                            <td style="white-space:nowrap"><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$o->page_id;?>#comment-post_<?=$o->id;?>" target="_blank"><? echo utf8_substr(strip_tags($o->text), 0, 120);?></a></td>
                        </tr>

                       <? }

                       } ?>
                     </tbody>
                    </table>

										<div class="col-md-12 more_important" style="border-top:solid 1px #ccc; padding-top:20px; margin-bottom:50px;">
                        <div class="load_activ_important" data-id="<?=$data["id"]?>" style="padding:7px 20px;">Загрузить еще</div>
                        <div class="static"> Всего <span><? $response = getSQLRowO("SELECT count(id) FROM {$CFG->DB_Prefix}comments WHERE user_id='{$data[id]}' AND visible = 1 AND access = '0'  AND important = 1   ");
														echo $response->{'count(id)'};?></span> &nbsp; &nbsp; &nbsp; Загружено  <span class="rebut">10</span>
                        </div>
                    </div>


									</div>

									</div>





                </div>
            </article>
        </div>


        <div class="tab-pane" id="alarm">
            <article class="vacancies_body row">
                <div class="table-responsive">
                    <?
                    $one = sqlDateNow();
                    $day = dateSQL2TEXT($one, "YYYY-MM-DD");

                    $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}reminder WHERE user_id='{$data[id]}'  AND date_start >= '{$day}' ORDER BY date_start ASC ");

                    ?>
                    <table class="users activS">
                      <tbody>
                        <tr>
                            <th><strong>Запись</strong></th>
                            <th><strong>Дата/Время напоминания</strong></th>
                            <th><strong>Текст</strong></th>
                            <th><strong>Исполнители</strong></th>
                        </tr>
                               <?

                                for ($i=0; $i<sizeof($response); $i++)
                                    {
                                    	$o = $response[$i];

                                       $com = SelectDataRowOArray("comments", $o->coment_id);  $pige_id = $com->page_id;
                               ?>
                        <tr>
                            <td style="white-space:nowrap"><a href="/record/<?=$pige_id;?>/"><strong>*<?=$pige_id;?></strong></a></td>
                            <td style="white-space:nowrap;color: #CA1B56; font-family: 'Helvetica_r';"><a href="/record/<?=$pige_id;?>/"><?=$o->date_start?>, <?=$o->time_start?>:00</a></td>
                            <td style=" color: #468847; font-family: 'Helvetica_r';"><a href="/record/<?=$pige_id;?>/"><? echo utf8_substr(strip_tags($o->text), 0, 120);?></a></td>
                            <td>


                          <? $arr = unserialize($o->manager_id);
                        for ($z=0; $z<sizeof($arr); $z++)
                        {
                        	$res = SelectDataRowOArray("users", $arr[$z], 0);
                            ?><a href="/profile/view/<?=$res->id;?>"  style="color:#428bca ; font-size:14px"><?=$res->name?></a> <?
                        }

                         ?>
                            </td>
                        </tr>

                       <?

                       } ?>
                     </tbody>

                    </table>



                </div>
            </article>
        </div>


        <div class="tab-pane" id="profile">
            <article class="vacancies_body row">
                <div class="table-responsive">
                    <?
                        $res = AndDataArray("news", "manager_id", $data["id"], 1, "cdate DESC LIMIT 60");

                        for($y=0;$y<sizeof($res);$y++)
                        {

                            $in .= $res[$y]->id.',';
                        }

                        $andid = trim($in, ",");

                        if($andid == !"")
                        {
                             $final = " AND page_id IN ({$andid})";
                             $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE visible=1 {$final} ORDER BY cdate DESC LIMIT 120");
                        }
                    ?>



                    <table class="users">
                      <tbody>
                        <tr>
                            <th><strong>№ записи</strong></th>
                            <th><strong>Компания</strong></th>
                            <th><strong>Дата заметки</strong></th>
                            <th><strong>Текст заметка</strong></th>
                            <th><strong>Автор</strong></th>
                        </tr>

                               <?
                                for ($i=0; $i<sizeof($response); $i++)
                                    {
                                        $o = $response[$i];

                                        if($o->user_id == $data["id"]) continue;
                                ?>
                        <tr>
                            <td><a href="/record/<?=$o->page_id;?>/"><strong>*<?=$o->page_id?></strong></a></td>
                            <td><a href="/record/<?=$o->page_id;?>/"><? $name = SelectDataRowOArray('news', $o->page_id);  print_r($name->name_company);?></a></td>
                            <td><a href="/record/<?=$o->page_id;?>/"><?=dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></a></td>
                            <td><a href="/record/<?=$o->page_id;?>/"><?=utf8_substr(strip_tags($o->text), 0, 70);?>...</a></td>
                            <td><a href="/profile/view/<?=$o->user_id;?>#view-notes"  style="text-decoration:underline; font-weight:600"><?=SelectData('users', $o->user_id, 0);?></a></td>
                        </tr>

                       <? } ?>

                        </tbody>
                    </table>
                </div>
            </article>
        </div>


        <div class="tab-pane" id="money">
            <article class="vacancies_body row">
                <div class="table-responsive">

                <br clear="all">
                <br clear="all">

                <ul class="nav nav-pills" style="text-align:center">
                  <li class="active"><a data-toggle="tab" href="#menu1">Самоначисления</a></li>
									<li><a data-toggle="tab" href="#menu4">Менеджерское АН 1С</a></li>
									<?	$asboss = getSQLRowO("SELECT user_id FROM my_data_1c_nomenclature_ruk WHERE user_id ='{$CFG->_GET_PARAMS[1]}'");
										if($asboss->user_id == $CFG->_GET_PARAMS[1] && $CFG->USER->USER_ID == $asboss->user_id || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 133  || $CFG->USER->USER_ID == 85  || $CFG->USER->USER_ID == 86 || $CFG->USER->USER_ID == 683 || $CFG->USER->USER_ID == 536 || $CFG->USER->USER_ACCOUNTING == $CFG->USER->USER_ID	|| $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID)	{	?>	<li><a data-toggle="tab" href="#menu5">Руководящее АН 1С</a></li> <? } ?>

                  <li><a data-toggle="tab" href="#menu3">Минусы</a></li>
                  <li><a data-toggle="tab" href="#menu2">Статистика</a></li>
                  <li>
                    &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp;
                     <div class="btn-group">
                       <button class="btn dropdown-toggle" data-toggle="dropdown">
                       <strong> <?
                       if($_COOKIE['year_money'] > 0)  	{ echo $_COOKIE['year_money']; $year = $_COOKIE['year_money']*1; }  else  { echo date('Y'); $year = date('Y')*1;  } ?></strong> <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu year_money">
<?
for($y = 2016; $y <= date('Y'); $y++){
?>
                        <li><a href="#" data-id="<?=$y; ?>" data-user="<?=$CFG->_GET_PARAMS[1]?>"><?=$y; ?></a></li>
<?
}
?>
                      </ul>
                    </div>
                  </li>
                  <li>
                    &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp;
                     <div class="btn-group">
                       <button class="btn dropdown-toggle" data-toggle="dropdown">
                       <strong>
                       <?

                       if($_COOKIE['premium'])
                       {
                       		$m = $_COOKIE['premium'];
                            $month = $m*1;

                       		if(is_numeric($CFG->_GET_PARAMS[3]))
                            {
                            	$m = $CFG->_GET_PARAMS[3];
                                $month = $m*1;
                            }
                       }
                       else
                       {
                       		$m = date('m');
                            $month = $m*1;

                       		if(is_numeric($CFG->_GET_PARAMS[3]))
                            {
                            	$m = $CFG->_GET_PARAMS[3];
                                $month = $m*1;
                            }

                        }

                        echo MonthNames($month);

                        ?>


                        </strong> <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu premium">
                        <li><a href="#" data-id="01" data-user="<?=$CFG->_GET_PARAMS[1]?>">Январь</a></li>
                        <li><a href="#" data-id="02" data-user="<?=$CFG->_GET_PARAMS[1]?>">Февраль</a></li>
                        <li><a href="#" data-id="03" data-user="<?=$CFG->_GET_PARAMS[1]?>">Март</a></li>
                        <li><a href="#" data-id="04" data-user="<?=$CFG->_GET_PARAMS[1]?>">Апрель</a></li>
                        <li><a href="#" data-id="05" data-user="<?=$CFG->_GET_PARAMS[1]?>">Май</a></li>
                        <li><a href="#" data-id="06" data-user="<?=$CFG->_GET_PARAMS[1]?>">Июнь</a></li>
                        <li><a href="#" data-id="07" data-user="<?=$CFG->_GET_PARAMS[1]?>">Июль</a></li>
                        <li><a href="#" data-id="08" data-user="<?=$CFG->_GET_PARAMS[1]?>">Август</a></li>
                        <li><a href="#" data-id="09" data-user="<?=$CFG->_GET_PARAMS[1]?>">Сентябрь</a></li>
                        <li><a href="#" data-id="10" data-user="<?=$CFG->_GET_PARAMS[1]?>">Октябрь</a></li>
                        <li><a href="#" data-id="11" data-user="<?=$CFG->_GET_PARAMS[1]?>">Ноябрь</a></li>
                        <li><a href="#" data-id="12" data-user="<?=$CFG->_GET_PARAMS[1]?>">Декабрь</a></li>
                      </ul>
                    </div>
                  </li>





                <div class="tab-content">

                  <div id="menu1" class="tab-pane active">
                    <br clear="all">

                    <?
                        $t = date('t'); // Количество дней в указанном месяце
                        $month_clear = date('m')*1;
                        $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);

                        $res = getSQLArrayO("SELECT count FROM {$CFG->DB_Prefix}money_list WHERE visible =1 AND user_id > 0 AND manager_id = {$data[id]} AND cdate >= '{$year}-{$m}-01 00:00:00' AND cdate <= '{$year}-{$m}-{$number} 23:59:59' AND type = 1 ORDER BY cdate DESC");

                        for ($y=0; $y<sizeof($res); $y++)
                        {
                            if(($res[$y]->count == $company->note) || ($res[$y]->count == $company->record) || $res[$y]->count == 0 || $res[$y]->count == 20 || $res[$y]->count == 30) continue;
                            {
                                $countDOWNS[] = $res[$y]->count;
                                $arrayDOWNS[] = array_sum($countDOWNS);
                            }
                         }
                         $itogoDOWN = array_reverse($arrayDOWNS);
                    ?>

                    <table class="users tsort_1">
                    	<thead>
                      		<tr>
                        		<th><strong>Операция</strong></th>
                        		<th><strong>Дата</strong></th>
                        		<th><strong>Описание</strong></th>
                        		<th><strong><?=$itogoDOWN[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
                       		</tr>
                      	</thead>
                        <tbody>
                        <?
                        	$t = date('t'); // Количество дней в указанном месяце
                            $month_clear = date('m')*1;

                            $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);
                           	$query = "SELECT * FROM {$CFG->DB_Prefix}money_list WHERE visible = 1 AND user_id > 0 AND manager_id = {$data[id]} AND cdate >= '{$year}-{$m}-01 00:00:00' AND cdate <= '{$year}-{$m}-{$number} 23:59:59' AND type = 1 ORDER BY cdate DESC ";
                            $o = getSQLArrayO($query);

                            for ($y=0; $y<sizeof($o); $y++)
                            {
                            	if(($o[$y]->count == $company->note) || ($o[$y]->count == $company->record) || $o[$y]->count == 0 || $o[$y]->count == 20 || $o[$y]->count == 30) continue;

                                $z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$o[$y]->page_id}'");
                                ?>
                                  <tr style="background:#FFF7FC">
                                    <td style=" width:25%; font-size:13px;"> Самоначисление в записи<strong><a href="<?=getFullXCodeByPageIdUrl($o[$y]->page_id);?><?=$o[$y]->page_id;?>#comment-post_<?=$o[$y]->coment_id;?>" ><?=$z->name_company;?></a></strong></td>
                                    <td style=" width:10%; font-size:13px; color:#468847"><? echo dateSQL2TEXT($o[$y]->cdate, "DD.MM.YY, hh:mm");?></td>
                                    <td style=" width:55%; font-size:13px;"> <? $array = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE id='{$o[$y]->coment_id}' "); echo utf8_substr(strip_tags($array->text), 0, 150); ?> <a href="#"  data-id="<?=$o[$y]->coment_id;?>" class="detelecoment" style="color: #428bca;">удалить</a></td>
                                    <td style=" width:10%; font-size:13px;"><? echo $o[$y]->count; $sb[] = $o[$y]->count; $arrayS[] = array_sum($sb); ?></td>
                                  </tr>
                                <?
                            }
                        ?>
                        </tbody>
                          <tr>
                            <th><strong></strong></th>
                            <th><strong></strong></th>
                            <th><strong>Итого</strong></th>
                            <th><strong><? $itogS = array_reverse($arrayS); echo $itogS[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
                         </tr>

                     </table>
                 </div>

								 <div id="menu4" class="tab-pane">
									 <br clear="all">
									 <?
						 					$t = date('t'); // Количество дней в указанном месяце
						 					$month_clear = date('m')*1;
						 					$number = cal_days_in_month(CAL_GREGORIAN, $m, $year);


						 					$res = getSQLArrayO("SELECT count FROM {$CFG->DB_Prefix}money_list WHERE visible =1 AND user_id > 0 AND manager_id = {$data[id]} AND cdate >= '{$year}-{$m}-01 00:00:00' AND cdate <= '{$year}-{$m}-{$number} 23:59:59' AND type = 3 ORDER BY cdate DESC");

						 					for ($y=0; $y<sizeof($res); $y++)
						 					{
						 							if(($res[$y]->count == $company->note) || ($res[$y]->count == $company->record) || $res[$y]->count == 0 || $res[$y]->count == 20 || $res[$y]->count == 30) continue;
						 							{
						 									$countDOWNSA[] = $res[$y]->count;
						 									$arrayDOWNSA[] = array_sum($countDOWNSA);
						 							}
						 					 }
						 					 $itogoDOWNA = array_reverse($arrayDOWNSA);
						 			?>


									 <table class="users tsort_2">
                                     <thead>
										 <tr>
											 <th><strong>Операция</strong></th>
											 <th><strong>Дата</strong></th>
											 <th><strong>Описание</strong></th>
											 <th><strong><?=$itogoDOWNA[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
											</tr>
                                            </thead>
                        					<tbody>
											 <?
												 $t = date('t'); // Количество дней в указанном месяце
													 $month_clear = date('m')*1;

													 $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);
													 $query = "SELECT * FROM {$CFG->DB_Prefix}money_list WHERE visible = 1 AND user_id > 0 AND manager_id = {$data[id]} AND cdate >= '{$year}-{$m}-01 00:00:00' AND cdate <= '{$year}-{$m}-{$number} 23:59:59' AND type = 3 ORDER BY cdate DESC ";
													 $o = getSQLArrayO($query);

													 for ($y=0; $y<sizeof($o); $y++)
													 {
														 if(($o[$y]->count == $company->note) || ($o[$y]->count == $company->record) || $o[$y]->count == 0 || $o[$y]->count == 20 || $o[$y]->count == 30) continue;

															 $z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$o[$y]->page_id}'");

															 $array = getSQLRowO("SELECT page_id,text FROM {$CFG->DB_Prefix}comments WHERE id='{$o[$y]->coment_id}' ");
															 $name = getSQLRowO("SELECT name_company FROM {$CFG->DB_Prefix}news WHERE id='{$array->page_id}' ");

															 ?>
																 <tr style="background:#FFF7FC">
																	 <td style=" width:25%; font-size:13px;">Самоначисление в записи<strong><a href="<?=getFullXCodeByPageIdUrl($array->page_id); echo $array->page_id;?>#comment-post_<?=$o[$y]->coment_id;?>" ><?=$name->name_company;?></a></strong></td>
																	 <td style=" width:10%; font-size:13px; color:#468847"><? echo dateSQL2TEXT($o[$y]->cdate, "DD.MM.YY, hh:mm");?></td>
																	 <td style=" width:55%; font-size:13px;"> <? echo utf8_substr(strip_tags($array->text), 0, 150); ?> <a href="#"  data-id="<?=$o[$y]->coment_id;?>" class="detelecoment" style="color: #428bca;">удалить</a></td>
																	 <td style=" width:10%; font-size:13px;"><? echo $o[$y]->count;  $sbA[] = $o[$y]->count;  $arraySA[] = array_sum($sbA);   ?></td>
																 </tr>
															 <?
													 }
											 ?>
                                             </tbody>
												 <tr>
													 <th><strong></strong></th>
													 <th><strong></strong></th>
													 <th><strong>Итого</strong></th>
													 <th><strong><? $itogSA = array_reverse($arraySA); echo $itogSA[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
												</tr>

										</table>
								 </div>


								 <?		if($asboss->user_id == $CFG->_GET_PARAMS[1] && $CFG->USER->USER_ID == $asboss->user_id || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 133 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 86 || $CFG->USER->USER_ID == 683 || $CFG->USER->USER_ID == 536 || $CFG->USER->USER_ACCOUNTING == $CFG->USER->USER_ID	|| $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID)	{	?>
								 <div id="menu5" class="tab-pane">
									 <br clear="all">
									 <?
						 					$t = date('t'); // Количество дней в указанном месяце
						 					$month_clear = date('m')*1;
						 					$number = cal_days_in_month(CAL_GREGORIAN, $m, $year);


						 					$res = getSQLArrayO("SELECT count FROM {$CFG->DB_Prefix}money_list WHERE visible =1 AND user_id > 0 AND manager_id = {$data[id]} AND cdate >= '{$year}-{$m}-01 00:00:00' AND cdate <= '{$year}-{$m}-{$number} 23:59:59' AND type = 4 ORDER BY cdate DESC");

						 					for ($y=0; $y<sizeof($res); $y++)
						 					{
						 							$countDOWNSAB[] = $res[$y]->count;
						 							$arrayDOWNSAB[] = array_sum($countDOWNSAB);
						 					 }
						 					 $itogoDOWNAB = array_reverse($arrayDOWNSAB);
						 			?>


									 <table class="users tsort_3">
                                     <thead>
										 <tr>
											 <th><strong>Операция</strong></th>
											 <th><strong>Дата</strong></th>
											 <th><strong>Описание</strong></th>
											 <th><strong><?=$itogoDOWNAB[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
											</tr>
                                            <thead>
                                            <tbody>
											 <?
												 $t = date('t'); // Количество дней в указанном месяце
													 $month_clear = date('m')*1;

													 $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);
													 $query = "SELECT * FROM {$CFG->DB_Prefix}money_list WHERE visible = 1 AND user_id > 0 AND manager_id = {$data[id]} AND cdate >= '{$year}-{$m}-01 00:00:00' AND cdate <= '{$year}-{$m}-{$number} 23:59:59' AND type = 4 ORDER BY cdate DESC ";
													 $o = getSQLArrayO($query);

													 for ($y=0; $y<sizeof($o); $y++)
													 {
														 if(($o[$y]->count == $company->note)) continue;

															 $z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$o[$y]->page_id}'");

															 $array = getSQLRowO("SELECT page_id,text FROM {$CFG->DB_Prefix}comments WHERE id='{$o[$y]->coment_id}' ");
															 $name = getSQLRowO("SELECT name_company FROM {$CFG->DB_Prefix}news WHERE id='{$array->page_id}' ");

															 ?>
																 <tr style="background:#FFF7FC">
																	 <td style=" width:25%; font-size:13px;">Самоначисление в записи<strong><a href="<?=getFullXCodeByPageIdUrl($array->page_id); echo $array->page_id;?>#comment-post_<?=$o[$y]->coment_id;?>" ><?=$name->name_company;?></a></strong></td>
																	 <td style=" width:10%; font-size:13px; color:#468847"><? echo dateSQL2TEXT($o[$y]->cdate, "DD.MM.YY, hh:mm");?></td>
																	 <td style=" width:55%; font-size:13px;"> <? echo utf8_substr(strip_tags($array->text), 0, 150); ?> <a href="#"  data-id="<?=$o[$y]->coment_id;?>" class="detelecoment" style="color: #428bca;">удалить</a></td>
																	 <td style=" width:10%; font-size:13px;"><? echo $o[$y]->count;  $sbAB[] = $o[$y]->count;  $arraySAB[] = array_sum($sbAB);   ?></td>
																 </tr>
															 <?
													 }
											 ?><tbody>
												 <tr>
													 <th><strong></strong></th>
													 <th><strong></strong></th>
													 <th><strong>Итого</strong></th>
													 <th><strong><? $itogSAB = array_reverse($arraySAB); echo $itogSAB[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
												</tr>

										</table>
								 </div>

<? } ?>







                  <div id="menu3" class="tab-pane ">
                    <br clear="all">

                    <?
                        $t = date('t'); // Количество дней в указанном месяце
                        $month_clear = date('m')*1;
                        $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);

                        $res = getSQLArrayO("SELECT count FROM {$CFG->DB_Prefix}money_minus_list WHERE visible =1 AND manager_id = {$data[id]} AND cdate >= '{$year}-{$m}-01 00:00:00' AND cdate <= '{$year}-{$m}-{$number} 23:59:59' ORDER BY cdate DESC");
                        for ($y=0; $y<sizeof($res); $y++)
                        {

                                $countDOWNSH[] = $res[$y]->count;  $arrayDOWNSH[] = array_sum($countDOWNSH);

                         }
                         $itogoDOWN = array_reverse($arrayDOWNSH);
                    ?>

                    <table class="users tsort_4">
                    <thead>
                      <tr>
                        <th><strong>Запись</strong></th>
                        <th><strong>Дата</strong></th>
                        <th><strong>Описание</strong></th>
                        <th><strong>Сумма&nbsp;минуса<br><?=$itogoDOWN[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
                       </tr>
                        </thead>
                         <tbody>
                        <?

                            $day = date('t')*1;
                            $month_clear = date('m')*1;

                            $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);

                           	$query = "SELECT * FROM {$CFG->DB_Prefix}money_minus_list WHERE visible = 1 AND manager_id = {$data[id]} AND (cdate >= '{$year}-{$m}-01 00:00:00') AND (cdate <= '{$year}-{$m}-{$number} 23:59:59') ORDER BY cdate DESC ";
                            $o = getSQLArrayO($query);

                            for ($y=0; $y<sizeof($o); $y++)
                            {
																 $array = getSQLRowO("SELECT page_id,text,id FROM {$CFG->DB_Prefix}comments WHERE id='{$o[$y]->coment_id}' ");
																 $name = getSQLRowO("SELECT name_company FROM {$CFG->DB_Prefix}news WHERE id='{$array->page_id}' ");

                                ?>
                                  <tr style="background:#FFF7FC">
                                    <td style=" width:10%; font-size:13px;"><strong><a href="<?=getFullXCodeByPageIdUrl($array->page_id); echo $array->page_id;?>#comment-post_<?=$array->id;?>" ><?=$name->name_company;?></a> </strong></td>
                                    <td style=" width:20%; font-size:13px; color:#468847"><? echo dateSQL2TEXT($o[$y]->cdate, "DD.MM.YY, hh:mm");?></td>
                                    <td style=" width:50%; font-size:13px;"><a href="<?=getFullXCodeByPageIdUrl($array->page_id); echo $array->page_id;?>#comment-post_<?=$array->id;?>" ><? echo utf8_substr(strip_tags($array->text), 0, 150); ?></a> <? if( ($CFG->USER->USER_ID == 85) or ($CFG->USER->USER_ID == 1)) { ?> <a href="#" data-id="<?=$o[$y]->id;?>" class="delpenalty" style="color: #428bca;">удалить минус</a> <?  } ?></td>
                                    <td style=" width:20%; font-size:13px;"><? echo $o[$y]->count; $sbS[] = $o[$y]->count;  $arraySH[] = array_sum($sbS);   ?></td>
                                  </tr>
                                <?
                            }
                        ?>
                        </tbody>
                          <tr>
                            <th><strong></strong></th>
                            <th><strong></strong></th>
                            <th><strong>Итого</strong></th>
                            <th><strong>- <? $itogoSH = array_reverse($arraySH); echo $itogoSH[0];?> <?=$CFG->USER->USER_CURRENCY;?></strong></th>
                         </tr>

                     </table>

                 </div>

                  <div id="menu2" class="tab-pane ">
                    <br clear="all">
                    <br clear="all">

                    <table class="users" >
                      <tr>
                        <th style="width:20%">Самоначисления</th>
												<th style="width:20%">Менеджерское АН 1С</th>
												<? if($asboss->user_id == $CFG->_GET_PARAMS[1] && $CFG->USER->USER_ID == $asboss->user_id || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 133 || $CFG->USER->USER_ID == 86 || $CFG->USER->USER_ID == 85  || $CFG->USER->USER_ID == 536 || $CFG->USER->USER_ACCOUNTING == $CFG->USER->USER_ID	|| $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID)	{	?><th style="width:20%">Руководящее АН 1С</th> <? } ?>
                        <th style="width:20%">Минусы</th>
                        <th style="width:20%">Итого</th>
                      </tr>
                      <tr style="font-family:'segoeui_sb'; color: #F54142;">
                        <td style="font-size:16px;"><? echo $itogS[0];?> <?=$CFG->USER->USER_CURRENCY;?></td>
												<td style="font-size:16px;"><? echo $itogSA[0];?> <?=$CFG->USER->USER_CURRENCY;?></td>
												<? if($asboss->user_id == $CFG->_GET_PARAMS[1] && $CFG->USER->USER_ID == $asboss->user_id || $CFG->USER->USER_ID == 1  || $CFG->USER->USER_ID == 133 || $CFG->USER->USER_ID == 86 || $CFG->USER->USER_ID == 85  || $CFG->USER->USER_ID == 536 || $CFG->USER->USER_ACCOUNTING == $CFG->USER->USER_ID	|| $CFG->USER->USER_ACCOUNTING_CHIEF == $CFG->USER->USER_ID)	{	?>	<td style="font-size:16px;"><? echo $itogSAB[0];?> <?=$CFG->USER->USER_CURRENCY;?></td> <? } ?>
                        <td style="font-size:16px; color:#47464B">- <? echo $itogoSH[0];?> <?=$CFG->USER->USER_CURRENCY;?></td>
                        <td style="font-size:16px;"><? echo ($itogS[0]+$itogSA[0]+$itogSAB[0])-$itogoSH[0]?> <?=$CFG->USER->USER_CURRENCY;?></td>
                      </tr>
                    </table>
                    <br clear="all">
                    <br clear="all">

                     <? if( ($CFG->USER->USER_BOSS == 1) or ($CFG->USER->USER_ID == 1) or ($CFG->USER->USER_ID == 536) or ($CFG->USER->USER_ID == 565) or ($CFG->USER->USER_ID == 86) or ($CFG->USER->USER_ID == 547) or ($CFG->USER->USER_ID == 133)  )  {?>



                    <a class="view-money"><?=$data['name']?> <? if(($itogSAB[0]+$itogSA[0]+$itogS[0])-$itogoSH[0] > 0) { echo 'выдаем';} else { echo 'удерживаем';} ?> за <?

                       if($_COOKIE['premium'])
                       {
                       		$m = $_COOKIE['premium'];
													$month = $m*1;

                       		if(is_numeric($CFG->_GET_PARAMS[3]))
                          {
                            	$m = $CFG->_GET_PARAMS[3];
                              $month = $m*1;
                          }
                       }
                       else
                       {
                       		$m = date('m');
                          $month = $m*1;

                       		if(is_numeric($CFG->_GET_PARAMS[3]))
                          {
                            $m = $CFG->_GET_PARAMS[3];
                            $month = $m*1;
                          }
                        }

												if($_COOKIE['year_money'])
											  {
											 		$yearS = $_COOKIE['year_money'];
											  }
											  else
											  {
											 		$yearS = date('Y');
											 	}

                          echo $yearS.' г. '; 	echo MonthNames($month).' ';  echo number_sum(($itogSAB[0]+$itogSA[0]+$itogS[0])-$itogoSH[0])?> <?=$CFG->USER->USER_CURRENCY;
												 if(($itogSAB[0]+$itogSA[0]+$itogS[0])-$itogoSH[0] < 0) { echo '. Удержали?';}
												?></a>


                     <?  } ?>

                    <br clear="all">
                    <br clear="all">
                    <br clear="all">
                    <br clear="all">
                  </div>
                </div>

                </div>
            </article>
        </div>



         <div class="tab-pane" id="deal">
            <article class="vacancies_body row">
                <div class="table-responsive">

                <br clear="all">
                <br clear="all">

                <ul class="nav nav-pills" style="text-align:center">
                  <li class="active"><a data-toggle="tab" href="#deal_1">В работе</a></li>
									<li><a data-toggle="tab" href="#deal_3">На модерации</a></li>
                  <li><a data-toggle="tab" href="#deal_2">Закрытые</a></li>
                </ul>

                    <div class="tab-content">
                        <div id="deal_1" class="tab-pane fade in active">
                        	<br clear="all">
                            <table class="users" >
                              <tr>
                                <th style="width:25%">Номер</th>
                                <th style="width:25%">Сумма</th>
                                <th style="width:25%">Название</th>
                                <th style="width:25%">Дата</th>
                              </tr>
                              <?
																$res = array();
                                $resp = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible=1 AND open=0 AND manager_id = {$data[id]} AND page_id = 1000 order by cdate  DESC");
																foreach($resp as $datas)
																{
																	$z = getSQLRowO("SELECT page_id FROM my_news WHERE id='{$datas->parent_id}'");
																	//if($z->page_id == 976) continue;
																	$res[] = $datas;
																}

                                for ($i=0; $i<sizeof($res); $i++)
                                {
                                    $o = $res[$i];

                                    ?>
                                    <tr>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><strong>*<?=$o->id;?></strong></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><?=number_sum($o->price);?> <?=$CFG->USER->USER_CURRENCY;?></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><?=$o->name_company;?></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm");?></a></td>
                                    </tr>
                                    <?
                                }
                              ?>
                            </table>
                            <br clear="all">
                            <br clear="all">
                        </div>

                        <div id="deal_2" class="tab-pane ">
                        	<br clear="all">
                            <table class="users" >
                              <tr>
                                <th style="width:25%">Номер</th>
                                <th style="width:25%">Сумма</th>
                                <th style="width:25%">Название</th>
                                <th style="width:25%">Дата</th>
                              </tr>
                              <?
																$res = array();
                                $resp = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible=1 AND open=3 AND manager_id = {$data[id]} AND page_id = 1000 order by cdate  DESC");

																foreach($resp as $datas)
																{
																	$z = getSQLRowO("SELECT page_id FROM my_news WHERE id='{$datas->parent_id}'");
																	if($z->page_id == 976) continue;
																	$res[] = $datas;
																}

                                for ($i=0; $i<sizeof($res); $i++)
                                {
                                    $o = $res[$i];
                                    ?>
                                    <tr>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><strong>*<?=$o->id;?></strong></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><?=number_sum($o->price);?> <?=$CFG->USER->USER_CURRENCY;?></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><?=$o->name_company;?></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm");?></a></td>
                                    </tr>
                                    <?
                                }
                              ?>
                            </table>
                            <br clear="all">
                            <br clear="all">
                        </div>


                        <div id="deal_3" class="tab-pane ">
													<br clear="all">
                            <table class="users" >
                              <tr>
                                <th style="width:25%">Номер</th>
                                <th style="width:25%">Сумма</th>
                                <th style="width:25%">Название</th>
                                <th style="width:25%">Дата</th>
                              </tr>
                              <?
																$res = array();
                                $resp = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible=1 AND open IN (2,1)  AND manager_id = {$data[id]} AND page_id = 1000 order by cdate  DESC");

																foreach($resp as $datas)
																{
																	$z = getSQLRowO("SELECT page_id FROM my_news WHERE id='{$datas->parent_id}'");
																	if($z->page_id == 976) continue;
																	$res[] = $datas;
																}

                                for ($i=0; $i<sizeof($res); $i++)
                                {
                                    $o = $res[$i];
                                    ?>
                                    <tr>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><strong>*<?=$o->id;?></strong></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><?=number_sum($o->price);?> <?=$CFG->USER->USER_CURRENCY;?></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><?=$o->name_company;?></a></td>
                                    <td><a target="_blank" href="/deal/<?=$o->id;?>"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm");?></a></td>
                                    </tr>
                                    <?
                                }
                              ?>
                            </table>
                            <br clear="all">
                            <br clear="all">
                        </div>



                    </div>

                </div>
            </article>
        </div>




				<div class="tab-pane" id="product_boss">
					 <article class="vacancies_body row">
							 <div class="table-responsive">
								 	<? include('tab/product-boss.tpl'); ?>
							 </div>
					 </article>
			 </div>








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
		   action: '/static/user_avatar/',
		   autoSubmit: true,
		   onComplete: function(file, response)
		   {
			response = $.parseJSON(response);

			if( typeof response.med == 'undefined' || response.med == null )
			{
			 return false;
			}

			/ set view /
			$('#upSAvatar').attr('src', response.med);

		   }
		  });
	}

</script>

<script>
(function($){
	$.fn.tsort=function(){
		var
			v=function(e,i){return $(e).children('td').eq(i).text()},
			c=function(i){return function(a,b){var k=v(a,i),m=v(b,i);return $.isNumeric(k)&&$.isNumeric(m)?k-m:k.localeCompare(m)}};
		this.each(function(){
			var
				th=$(this).children('thead').first().find('tr > th'),
				tb=$(this).children('tbody').first();

			th.click(function(){
				var r=tb.children('tr').toArray().sort(c($(this).index()));
				th.removeClass('sel'),$(this).addClass('sel').toggleClass('asc');
				if($(this).hasClass('asc'))r=r.reverse();
				for(var i=0;i<r.length;i++)tb.append(r[i])
			})
		})
	}
})(jQuery);

$( document ).ready(function()
{
	$('.tsort_1').tsort();
	$('.tsort_2').tsort();
	$('.tsort_3').tsort();
	$('.tsort_4').tsort();
});
</script>
