

<div class="white">

        <article class="vacancies_body row" id="<?=$o->id;?>">


<script>
	$(function(){ $('[data-toggle="tooltip"]').tooltip(); });
</script>

         <div class="col-md-12" style="padding-bottom:150px;">
         	<div class="col-md-7 record">

            <div class="object">
                <div class="title">
                    <h3><a onclick="history.back(); return false;"><i class="glyphicon glyphicon-arrow-left"></i> Назад</a> &nbsp; &nbsp; <strong style="color:#F84241">Компании</strong> <span>*<?=$o->id;?></span></h3>
                </div>

                <div class="col-md-5 avatar">
                    <? if($o->logo_company ==! "") {?>
                        <a href="<?=$o->logo_company;?>"  class="quickbox" data-fancybox="images"><img src="<?=$o->logo_company_mini;?>" alt="" title="Просмотров: <?=$o->view;?>" /></a>
                   <? } else {?>
                		<a href="/tpl/img/noavatar.png"  class="quickbox" data-fancybox="images"><img src="/tpl/img/noavatar.png" alt="" title="Просмотров: <?=$o->view;?>"/></a>
                	<? }?>
                </div>


                <div class="col-md-7 contact">
                    <div class="obj">
                        <p>Название</p>
                        <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;"><?=$o->name_company;?> <? if($o->access_id == 1) {?>(<span style="color:#F74140">Название</span>)<? } ?> </strong></p>
                    </div>
                    <br clear="all">
                 </div>



                 <? if($o->type_company_id ==! "") {?>
                <div class="col-md-12 textS">
                    <div class="obj">
                        <p>Отрасль клиента:</p>
                        <p class="gray"><strong><?
                       	// $array = explode(",", $o->type_company_id);
                		$array = getSQLArrayO("SELECT name FROM {$CFG->DB_Prefix}type_company WHERE visible=1 AND id IN({$o->type_company_id}) ");
                        for ($y=0; $y<sizeof($array); $y++) {?>  <? echo $array[$y]->name;?>  <br> <? } ?></strong></p>
                    </div>
                 </div>
                 <? } ?>

                 <br clear="all">

				<? if($o->city_id ==! 0) {?>
                <div class="col-md-5 gran">
                    <div class="obj">
                        <p>Город</p>
                        <p class="gray"><strong><?=SelectData('city',$o->city_id);?></strong></p>
                    </div>
                </div>
                <? } ?>


				<? if($o->insta ==! "") {?>
                <div class="col-md-7 contact">
                    <div class="obj">
                        <p>Инстаграм</p>
                        <p class="gray"><a style="margin-bottom:0; overflow: hidden" href="<?=$o->insta;?>" target="_blank"><strong><?=$o->insta;?></strong></a></p>
                    </div>

                </div>
                <? } ?>

                <br clear="all">


                <div class="col-md-12 textS">
                    <div class="obj">

                        <? if($o->info ==! "") {?>
                            <p>Информация</p>
                            <p class="text">
                                <strong>
                                    <?=replace_r_n_br($o->info)?>
                                </strong>
                            </p>
                         <? } ?>

                         <? if($o->history ==! "") {?>
                         	<br clear="all">
                            <p>Историческая справка. Нюансы. Особенности</p>
                            <p class="text">
                                <strong>
                                    <?=replace_r_n_br($o->history)?>
                                </strong>
                            </p>
                         <? } ?>

                        <? if($o->contact ==! "") {?>
                        	<br clear="all">
                            <p>Контакты</p>
                            <p class="text">
                                <strong>
                                    <?=replace_r_n_br($o->contact)?>
                                </strong>
                            </p>
                        <? } ?>



                                    <p>Приказы:</p>
                            <p class="text orders">
 							<?
                                $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}orders WHERE page_id='{$o->id}' order by id DESC");
                                for ($i=0; $i<sizeof($sql); $i++)
								{
                                   $cnt = $i+1;
                                   $resp = SelectDataRowOArray('users',$sql[$i]->user_id,0);
                                   $text = $resp->name.' - '.dateSQL2TEXT($sql[$i]->cdate, "DD.MM.YY");
                                   echo '<span class="number_order" data-toggle="tooltip" data-placement="left" title="'.$text.'" data-original-title="'.$text.'">'.$sql[$i]->text.'</span>';
                                }
                            ?>
                           <strong><a href="#" class="add_orders">+ Создать приказ</a></strong></p>

                            <form class="orders">
                            	<input type="text" class="orders_text" value="" placeholder="Текст приказа">
                                <input type="button" class="save_orders" value="СОХРАНИТЬ">
                            </form>







                        <style>
						.text.deal a{ display:block;  margin-bottom:7px; }
						.text.deal { display:block; width:100%; overflow:hidden}
						.orders { display:none; width:100%; margin-top:5px; clear:both }
						.orders span.number_order { display:block; font-size:13px; color:#666;padding:10px 5px; padding-top:5px; padding-bottom:10px;}


						.orders input.orders_text{ border:solid 1px #CCC;  font-size:12px; color:#999; padding:2px 5px; width:180px; }
						.orders input.save_orders{ border:0;  font-size:12px; color:#fff; padding:3px 5px; background:#F84241 }
						</style>





                        	<br clear="all">
                            <p>Сделки:</p>
                            <p class="text deal">
                            <? $sql = getSQLArrayO("SELECT id, page_id, parent_id, name_company, cdate, access_deal, price FROM {$CFG->DB_Prefix}news WHERE visible = 1 AND parent_id = '{$o->id}' order by cdate DESC");

                                for ($i=0; $i<sizeof($sql); $i++)
								{



                                    ?>


                                    <a href="<?=getFullXCodeByPageId($sql[$i]->page_id);?><?=$sql[$i]->id;?>" data-toggle="tooltip" data-placement="bottom" title="<?=$sql[$i]->name_company;?> - <? echo dateSQL2TEXT($sql[$i]->cdate, "DD.MM.YY")?>" data-original-title="<?=$sql[$i]->name_company;?> - <? echo dateSQL2TEXT($sql[$i]->cdate, "DD.MM.YY")?>">
                                    <? if($sql[$i]->access_deal == NULL) { echo '<span class="icon-eye-open"></span>';} else {echo '<span class="icon-eye-close"></span>';}?>
                                    <?=$sql[$i]->name_company; if($sql[$i]->price > 0) { echo ', '.number_sum($sql[$i]->price).' '.$CFG->USER->USER_CURRENCY;}  $sum_arr_deal[] = $sql[$i]->price;  ?>

                                    - <? echo dateSQL2TEXT($sql[$i]->cdate, "DD.MM.YY")?></a><?
                                }



                                $summ_deal = array_sum($sum_arr_deal);
                                ?>

                                <strong>
                                    <a href="#" class="add_deal">+ Добавить новую</a>
                                </strong>


                              <?  if($summ_deal > 0) {?>
                                Общая сумма сделок: <span style="color:red; font-family: 'segoeui_sb'; font-size:14px;"><?=number_sum($summ_deal).' '.$CFG->USER->USER_CURRENCY;?></span><br>
                              <? } ?>


                                  <?
                                  $day_30 = date("Y-01-01 00:00:00");	$current = 	date("Y-m-d 23:59:59");
                                  $str = "  AND (cdate >= '{$day_30}') AND (cdate <= '{$current}')  ";
                                  $res = getSQLRowO("SELECT  SUM(price*count) FROM my_data_1c_nomenclature WHERE buyer LIKE '%*{$o->id}%' {$str} AND visible = 1 ");
                                  $buyer = $res->{'SUM(price*count)'};
                                  if($buyer > 0){?><br> Общая сумма по реал. этого года:  <span style="color:red; font-family: 'segoeui_sb'; font-size:14px;"><?=number_sum($buyer).' '.$CFG->USER->USER_CURRENCY;?></span><br>
                                  <a href="/speedometer/nomenclature_view/?search=&buyer=*<?=$o->id;?>">-> Открыть реализации</a>
                                  <? } ?>




                            </p>


<br clear="all">







							<script>
					$('.add_orders').on('click', function(e)
					{
						$('.add_orders').animate({'opacity':'hide'}, 200);
						$('.orders').animate({'opacity':'show'}, 200);

						e.preventDefault();
					});

		$('.orders .save_orders').on('click', function (e)
		{
			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});

			var text = $('.orders_text').val();

			if(text == '')
			{
				$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
				$(".modal-body").append('<h4 class="modal-title"><center>Введите текст.</center></h4>');
			}
			else
			{
				$.ajax
					({
						url: "/static/add_orders/",
						type: "POST",
						data: {"text": text , "id": <?=$o->id?> ,  },
						cache: true,
							beforeSend: function()
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{
								var s = location.href;
								url = s.split('#')[0];
								$('.content').load(url + '/json');

								setTimeout(function() {

								$(document).ready(function()
								{
									$("#myModalBox").modal('hide');
								});}, 5000);
							}
					});

			}

            e.preventDefault();
        });


							</script>





                    </div>
                </div>
                <br clear="all">

                              <style type="text/css">
							  .user .card { background:#FFF; width:92%; margin-left:3%; margin-bottom:10px;}
							  .user .card h4{ padding:0; margin:0; display:block; width:100%; background:#999999; color:#FFF; font-size:16px; padding:5px 10px;font-family: 'segoeui_sb';}
							  .user .card .avatar{ display:inline-block; padding:2%; vertical-align:top; padding-top:10px; width:25%; text-align:center;}
							  .user .card .avatar img{ width:100% !important;  border-radius:50%; border:#CCC 1px solid}
							  .user .card .intro{ display:inline-block; vertical-align:top; width:73%; margin-top:5px; font-family: 'segoeui';}
							  .user .card .intro p{ padding:0; margin:0; padding-top:2px; font-size:13px; font-family: 'segoeui_sb'; white-space:nowrap; overflow:hidden}
							  .user .card .intro p span{ color:#333; display: inline-block; width:65px;}
							  .user .card .intro p span.name{ width: auto;}
							  .user .card .intro p, .user .card .intro p a { color:#428bca}
							  .user .card .intro a.cashback { color:#7EC149; display:block; margin-top:10px;}

							  .user .card .intro .edit{ text-align:right; margin-top:2px; padding-right:5px; margin-bottom:5px;}
							  .user .card .intro .edit a{ font-size:12px; color:#F8403E}
							  </style>


                <?  if($o->client_id != ''){personArray($o->client_id);} ?>


                <div class="col-md-12 user">
                	<div style="font-size:8px;  padding:5px">
                    	<div style="display:inline-block; width:7%; text-align:center; vertical-align: middle"><img src="/tpl/img/strelka.gif" style="width:100%"></div>
                        <div style="display:inline-block; text-align: center;  width:92%; vertical-align:top">Если Ваше имя в этой ячейке это значит что клиент закреплен за Вами.<br>Это значит, что Вы ответственны за этого клиента, чтобы он раз в <br>3 недели получал от нас свежую, полезную информацию - МАРКЕТИНГ!!!</div>
                        </div>

                	<div class="obj">
                    	<p>Менеджер</p>
                    	<p class="gray"><strong><? $resp = SelectDataRowOArray('users',$o->manager_id,0);?><a href="/profile/view/<?=$resp->id?>/" target="_blank"><?=$resp->name;?></a></strong></p>
                    </div>

                    <div class="obj">
                        <? if($o->edate == !null) {?>
                        <p>Дата ред.</p>
                        <p class="gray"><strong><?=dateSQL2TEXT($o->edate, "DD.MM.YY, hh:mm")?></strong></p>
                        <? }?>
                    </div>

                  	<div class="obj">
                    	<p>Дата созд.</p>
                    	<p class="gray"><strong><?=dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></strong></p>
                    </div>

                </div>
                <br clear="all"><br clear="all">

                <? $res = getSQLRowO("SELECT id FROM my_news_history WHERE page_id={$o->id} AND type = 'news' "); if($res->id > 0) { ?>
                <div class="col-md-12 user">
                	<div class="obj" style="width:95%">
                    	<p>Последняя правка:</p>
                    	<p class="gray"><strong><? $NEWS->getUp($o->id, 'record');?></strong></p>
                    </div>
                </div><br clear="all"><br clear="all">
                <? } ?>

				<div class="col-md-12 userS">
                  <? if($o->attach_files ==! "") {?>
                    <div class="obj">
                        <p>Файлы</p>
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
                   </div>
                <? }?>


                <? if($o->attach_files_doc ==! "") {?>
                    <div class="obj">
                        <p>Файлы</p>
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
                                                    ?><p class="gray"><strong><a href="<?=$doc[$n];?>" class="cancel-attachment_doc" target="_blank"><?=basename($doc[$n]);?></a></strong></p><?
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                          </div>
                    </div>
                    <br clear="all">
                <? }?>
               </div>

                <?

                	//include("./modules/news/tpl/cashback.tpl");
                	//include("./modules/news/tpl/price.tpl");

                ?>


  				<div class="edit_buttom">
     			<?
                	EditRecord('record', $o->id);
                    DeleteRecord($o->id, $o->manager_id, $CFG->USER->USER_ID);
                ?>
                </div>
            </div>

            </div>







         	<div class="col-md-5 comment-block-position">
            	<h2 class="title">РАБОЧИЕ ЗАМЕТКИ ОТ МЕНЕДЖЕРОВ</h2>
                <div class="comment-block">

                    <div class="comment-messages">
                    <div class="comment-empty"></div>

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



        </article>


</div>

<style>
.erors { color:#F00 !important; display:block; margin-bottom:10px;}
.form-control.name.error { border:solid 1px #FF0000 !important}
.modal-body .bootstrap-select { width:220px !important; font-family:'segoeui' !important;}
.modal-title{font-family:'segoeui' !important;}

</style>
