<div class="white">

        <article class="vacancies_body row" id="<?=$o->id;?>">


<script>
	$(function(){ $('[data-toggle="tooltip"]').tooltip(); });
</script>

<? $data = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id = '{$o->parent_id}' "); ?>

         <div class="col-md-12" style="padding-bottom:150px;">

           <? if($o->open > 0) {  ?>
             <div class="alert-danger" style="padding:10px 20px;">
                 <strong><? echo '*'.$o->id;?></strong>
                 <? if($o->open == 3) {  echo 'Эта сделка закрыта!'; } ?>
                 <? if($o->open == 2) {  echo 'Эта сделка закрыта!'; } ?>
                 <? if($o->open == 1) {  echo 'Эта сделка закрыта!'; } ?>
              </div>
           <? } ?>

         	<div class="col-md-7 record">

            <div class="object">
                <div class="title">
                    <h3><a href="<?=getFullXCodeByPageId($data->page_id);?><?=$data->id;?>/"><i class="glyphicon glyphicon-arrow-left"></i> <?=$data->name_company;?> </a> <span>*<?=$data->id;?></span></h3>
                </div>

                <div class="title">
                  <div class="obj">
                      <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px; font-size: 15px;">Сделка *<?=$o->id;?>: <?=$o->name_company;?><? if($o->access_id == 1) {?>(<span style="color:#F74140"><?=$CFG->Locale['fp69'];?></span>)<? } ?>  </strong> </p>
                  </div>
                </div>

                <div class="col-md-5 avatar">
                    <? if($data->logo_company_mini != "") { ?>
                        <a href="<?=$data->logo_company;?>" class="fancybox"><img src="<?=$data->logo_company_mini;?>" alt="" title="Просмотров: <?=$data->view;?>" /></a>
                   <? } else {?>
                		<a href="/tpl/img/noavatar.png" class="fancybox"><img src="/tpl/img/noavatar.png" alt="" title="Просмотров: <?=$data->view;?>"/></a>
                	<? }?>
                </div>

                <div class="col-md-7 contact">


                    <? if($o->price > 0) {?>
                    <div class="obj">
                        <p>Сумма сделки</p>
                        <p class="gray"><strong><? echo  number_sum($o->price); echo ' '.$CFG->USER->USER_CURRENCY;?></strong></p>
                    </div>
                    <br clear="all">
                    <? } ?>

                 </div>


                <br clear="all"><br clear="all">




						<?
                        	$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}orders WHERE page_id='{$data->id}' order by id DESC");
                        	if(count($sql) > 0 ) {
                        ?>
 						<div class="col-md-12 textS">
                    		<div class="obj">

                            <p>Приказы:</p>
                            <p class="text orders">
 							<?
                                for ($i=0; $i<sizeof($sql); $i++)
								{
                                   $cnt = $i+1;
                                   $resp = SelectDataRowOArray('users',$sql[$i]->user_id,0);
                                   $text = $resp->name.' - '.dateSQL2TEXT($sql[$i]->cdate, "DD.MM.YY");
                                   echo '<span class="number_order" data-toggle="tooltip" data-placement="left" title="'.$text.'" data-original-title="'.$text.'">'.$sql[$i]->text.'</span>';
                                }
                            ?> </p>


                         <style>
						.text.deal a{ display:block; white-space:nowrap; margin-bottom:7px; }
						.text.deal { display:block; width:100%; overflow:hidden}
						.orders { display:none; width:100%; margin-top:5px; clear:both }
						.orders span.number_order { display:block; font-size:13px; color:#666;padding:10px 5px; padding-top:5px; padding-bottom:10px;}


						.orders input.orders_text{ border:solid 1px #CCC;  font-size:12px; color:#999; padding:2px 5px; width:180px; }
						.orders input.save_orders{ border:0;  font-size:12px; color:#fff; padding:3px 5px; background:#F84241 }
						</style>


						</div>
					</div>
                    <? } ?>




                <br clear="all"><br clear="all">


                <div class="col-md-12 user">
                	<div class="obj">
                    	<p>Автор сделки</p>
                    	<p class="gray"><strong><? $resp = SelectDataRowOArray('users',$o->manager_id,0);?><a href="/profile/view/<?=$resp->id?>/" target="_blank"><?=translit($resp->name);?></strong></a></p>
                    </div>

                    <div class="obj">
                        <? if($data->edate == !null) {?>
                        <p><?=$CFG->Locale['fp83'];?></p>
                        <p class="gray"><strong><?=dateSQL2TEXT($o->edate, "DD.MM.YY, hh:mm")?></strong></p>
                        <? }?>
                    </div>

                  	<div class="obj">
                    	<p><?=$CFG->Locale['fp84'];?></p>
                    	<p class="gray"><strong><?=dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm")?></strong></p>
                    </div>

                </div>

                <? if($o->access_deal != NULL){?>
                <div class="col-md-12 textS">
                	<div class="obj" style="width:100%">
                    	<p>Доступ к сделки:</p>
                    	<p class="lend" style="white-space:normal;">
                        <?
                            $men = explode(",", $o->access_deal);
                            for($z=0; $z<sizeof($men); $z++)
                            {
                                 $resp = SelectDataRowOArray('users',$men[$z],0);
                                 ?>
                                    <a href="/profile/view/<?=$resp->id?>/" style=" display:inline-block;"><? if($z > 0) echo ' &nbsp;'; echo $resp->name;?> </a>
                                 <?
                            }
                        ?>
                        </p>
                    </div>
                </div>
              <? } ?>


                <? if($DEAL->getObjectJsonData($CFG->_GET_PARAMS[0]) > 0 && $CFG->USER->USER_EXPENSES_LIL == 1 ) {?>
                <div class="col-md-12 textS">
                  <div class="obj">
                    <p></p>
                    <p class="text"><a href="/speedometer/lil/#data_<?=$CFG->_GET_PARAMS[0];?>" target="_blank" data-id="<?=$o->id;?>" style=" color:#000; border-bottom:1px #000 solid">Расход по сделке: <strong><? echo $DEAL->getObjectJsonData($CFG->_GET_PARAMS[0]); echo $CFG->USER->USER_CURRENCY; ?></strong></a></p>
                  </div>
                </div>
                <? }?>

                <br clear="all">

                <?
                    include("./modules/news/tpl/price.tpl");
                ?>

                <br clear="all">   <br clear="all">


                <div class="edit_buttom">
                	<?  if( ($CFG->USER->USER_ID == 85  && $o->open == 2) ){ ?>
     				<a href="/static/on_alarm_boss/<?=$o->id;?>"  data-id="<?=$o->id;?>">Одобрить</a>
                    <? } ?>

                	<?  if( ($CFG->USER->USER_ID == $CFG->USER->USER_ACCOUNTING_CHIEF  && $o->open == 1) ){ ?>
     				<a href="/static/on_alarm_black/<?=$o->id;?>"  data-id="<?=$o->id;?>">Одобрить</a>
                    <? } ?>

                	<?  if( ($CFG->USER->USER_ID == $CFG->USER->USER_ACCOUNTING_CHIEF  && $o->open == 1 ||  ($CFG->USER->USER_ID == 85  && $o->open == 2) ) ){ ?>
     				<a href="#" class="off_alarm_black"  data-id="<?=$o->id;?>">НЕ Одобрить</a>
                    <? } ?>

                	<?  if( ($CFG->USER->USER_ID == 1  && $o->open == 0) ||  ($o->manager_id == $CFG->USER->USER_ID  && $o->open == 0)){ ?>
     				<a href="#" class="open_deal" data-id="<?=$o->id;?>">Закрыть сделку</a>
                    <? } ?>


                	<?  if( $CFG->USER->USER_ID == 1 ||  $CFG->USER->USER_ID == 85 ||  $CFG->USER->USER_ID == $CFG->USER->USER_ACCOUNTING_CHIEF || $CFG->USER->USER_ID == 566 || $o->manager_id == $CFG->USER->USER_ID){ ?>
     				<a href="#" class="edit_deal" data-id="<?=$o->id;?>"  data-price="<?=$o->price;?>" data-direction="<?=$o->name_company;?>">Редакт.</a>
                    <? } ?>


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



<style type="text/css">
.modal-body .bootstrap-select { width:220px !important; font-family:'segoeui' !important;}
.modal-title{font-family:'segoeui' !important;}
</style>



<script>
	$('a.off_alarm_black').on('click', function(e)
	{
		var id = $(this).attr('data-id');

		$("#myModalBox").modal('show');


		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Почему НЕ одобряете? </h4></div>');
		$(".modal-body").append('<p><textarea class="form-control text" rows="2" style="margin-bottom:15px;"></textarea></p>');
		$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Отправить</button></p>');

			/* Да. Отправить введеный текст */
			$('.modal-body').off('click').on('click', '.btn.submit', function(e)
			{
				var textarea = $('.form-control.text').val();

				$.ajax
				({
					url: "/static/off_alarm_black/",
					type: "POST",
					data: {"id": id, "text": textarea,},
					cache: true,
						beforeSend: function()
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{
							if(response == 1)
							{
								$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Сделка не одобрена!</center></h4></div>');
								setTimeout(function() {window.location.reload();}, 1000);
							}
							if(response == 0)
							{
								$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
							}
						}
				});

			});
		e.preventDefault();
	});


	$('a.open_deal').on('click', function(e)
	{
		var id = $(this).attr('data-id');


		$.ajax
		({
			url: "/static/open_deal/",
			type: "POST",
			data: {"id": id},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				if(response == 1)
				{
					$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Ваша сделка закрыта!</center></h4></div>');
					setTimeout(function() {window.location.reload();}, 1000);
				}
				if(response == 0)
				{
					$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
				}

			}
		});

		e.preventDefault();
	});




 $('a.edit_deal').on('click', function (e)
		{
            var id = $(this).attr('data-id');
            var direction = $(this).attr('data-direction');
            var price = $(this).attr('data-price');

			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});

			$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Редактировать название сделки</h4></div>');
			$(".modal-body").append('<p><input type="hidden" class="form-control id" value="' +  id  + '"></p>');
			$(".modal-body").append('<p><input type="text" class="form-control direction" value="' +  direction  + '">');

			$(".modal-body").append('<p><input type="text" class="form-control price" value="' +  price  + '">');

			$(".modal-body").append('<p><select class="selectpicker show-tick manager list-view-manager" multiple="multiple" data-live-search="true"  title="Выберите менеджера"><option value="0">Выберите менеджер</option><? $users = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 ORDER BY name ASC");  for ($i=0; $i<sizeof($users); $i++){
				$rio = explode(",", $o->access_deal);

			?> <option value="<?=$users[$i]->id;?>"<? 	for($r=0; $r<sizeof($rio); $r++) { if($rio[$r] == $users[$i]->id) {echo ' selected';} } ?>><?=$users[$i]->name;?></option> <? } ?></select></p>');


			$(".modal-body").append('<p><button type="button" class="btn btn-primary">Сохранить</button></p><br clear="all">');

			$('.selectpicker').selectpicker();

					$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
					{
						var direction_real = $('input.form-control.direction').val();
						var price_real = $('input.form-control.price').val();
						var manager = $('.list-view-manager').val();

							$.ajax
							({
								url: "/static/edit_deal/",
								type: "POST",
								data: {"id": id, "manager": manager, "text": direction_real, "edit_price": price_real},
								cache: true,
									beforeSend: function()
									{
										$(".modal-body").html('<h4 class="modal-title"><center>Сохраняем...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
									},
									success: function(response)
									{

										if(response == 1)
										{
											$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');

											setTimeout(function() {window.location.reload();}, 1000);

										}
										if(response == 0)
										{
											$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
										}
									}
							});

					});


            e.preventDefault();
        });
</script>
