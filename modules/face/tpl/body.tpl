<style>
article.vacancies_body .gray a { margin:0; font-size:13px;font-family:'segoeui_sb'; }
</style>


<div class="white">

        <article class="vacancies_body row" id="<?=$o->id;?>">

        <script>
        	$(function(){ $('[data-toggle="tooltip"]').tooltip(); });
        </script>


         <div class="col-md-12" style="padding-bottom:150px;">
         	<div class="col-md-12 record">

            <div class="object">
                <div class="title">
                    <h3><a onclick="history.back(); return false;"><i class="glyphicon glyphicon-arrow-left"></i> НАЗАД</a>
                      <span>
                        <div class="options">
                          <a href="#" ><i title="Отправить контакт на WhatsApp" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon-share-alt wp_send_VCF"  data-id="<?=$o->id;?>"></i></a>
                          <a href="/profile/edit_person/<?=$o->id;?>/"><i title="Редактировать лицо" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon-edit" ></i></a>
                          <a href="<?=$o->id;?>"><i title="Удалить лицо" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon-trash delete_face" data-id="<?=$o->id;?>"></i></a>
                        </div>
                      </span>
                    </h3>
                </div>


                <div class="col-md-3 avatar">
                    <? if($o->img ==! "") {?>
                        <a href="<?=$o->img;?>" class="quickbox" data-fancybox="images"><img src="<?=$o->img;?>" style=" border-radius:0" alt="" /></a>
                   <? } else {?>
                		<a href="/tpl/img/noavatar.png" class="quickbox" data-fancybox="images"><img src="/tpl/img/noavatar.png" alt="" /></a>
                	<? }?>
                </div>

                <div class="col-md-9 textS">

               		<? if($o->name ==! "") {?>
                    <div class="obj">
                        <p>Физ. лицо - Обращение (для маркетинга и тел. книги):</p>
                        <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;"><span style="color:#B200FF;"><?=$o->name;?></span></strong></p>
                    </div>
                    <? } ?>

               		<? if($o->name_other ==! "") {?>
                    <div class="obj">
                        <p>Подробнее - ИФ, бла - бла. Портретик человека:</p>

                          <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;"><?  $n = explode(" ", $o->name_other);
                            $n_cnt = 0;
                            foreach($n as $value)
                            {
                              if($n_cnt == 1)
                              {
                                echo '<span style="color:#B200FF;">'.$value.'</span> ';
                              }
                              else
                              {
                                echo $value.' ';
                              }
                              $n_cnt ++;
                            } ?></strong></p>
                    </div>
                    <? } ?>

                    <style>
                      .obj.wp a{ display: inline-block  !important;}
                      .copy_mobile { font-size: 12px;     font-family: 'segoeui' !important; text-decoration: underline;}
                    </style>

                    <? if($o->mobile ==! "") {?>

                    <div class="obj wp">
                        <p>Мобильный</p>
                        <p class="gray"><a href="tel:<?=$o->mobile;?>"><?=$o->mobile;?></a>  <a class="copy_mobile" data-id="<?=$o->mobile;?>">copy</a></p>
                    </div>
                    <? } ?>

                    <? if($o->whatsapp ==! "") {?>
                    <div class="obj wp">
                        <p>Whatsapp</p>
                        <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;"><a href="https://web.whatsapp.com/send?phone=<?=$o->whatsapp;?>" target="_blank"><?=$o->whatsapp;?></a></strong>  <a class="copy_mobile" data-id="<?=$o->whatsapp;?>">copy</a></p>
                    </div>
                    <? } ?>

                    <? if($o->international ==! "") {?>
                    <div class="obj wp">
                        <p>Международный</p>
                        <p class="gray"><stron><a href="tel:<?=$o->international;?>"><?=$o->international;?></a></strong>  <a class="copy_mobile" data-id="<?=$o->international;?>">copy</a></p>
                    </div>
                    <? } ?>

                    <? if($o->email ==! "") {?>
                    <div class="obj">
                        <p>E-mail</p>
                        <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;"><?=$o->email;?></strong></p>
                    </div>
                    <? } ?>

                    <? if($o->city_id ==! 0) {?>
                    <div class="obj">
                        <p>Город</p>
                        <p class="gray"><strong><?=SelectData('city',$o->city_id);?></strong></p>
                    </div>
                    <? } ?>

                    <? if($o->floor ==! 0) {?>
                    <div class="obj">
                        <p>Пол</p>
                        <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;">
                        <?
                        	$floor[1]->name = 'Мужской';
               				$floor[2]->name = 'Женский';

                            echo  $floor[$o->floor]->name; ?></strong></p>
                    </div>
                    <? } ?>

                    <? if($o->bcdate ==! 0) {?>
                    <div class="obj">
                        <p>День рождения</p>
                        <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;"><? echo date('d/m/Y', $o->bcdate); ?></strong></p>
                    </div>
                    <? } ?>

                    <? if($o->cashback ==! 0) {?>
                    <div class="obj">
                        <p>Cashback</p>
                        <p class="gray"><strong style="font-family:'segoeui_sb'; line-height:10px;"><a href="/cashback/<?=$o->mobile;?>" target="_blank">открыть историю начислений</a></strong></p>
                    </div>
                    <? } ?>

                    <?
                    $sql =  getSQLArrayO("SELECT * FROM my_edrive WHERE client_id='{$o->id}' ");

                    if($sql != NULL) { ?>

                    <div class="obj">
                        <p>Электромобиль</p>
                        <p class="gray">
                          <?
                          foreach($sql as $value)
                          {
                             $sp =  getSQLRowO("  SELECT  my_edrive_car_model.name AS model, my_edrive_car.* FROM my_edrive_car LEFT JOIN my_edrive_car_model ON my_edrive_car_model.id=my_edrive_car.model_id WHERE my_edrive_car.id='{$value->car_id}'  ORDER BY id DESC  ");
                             echo '<strong>'.$sp->name.'</strong><br>';
                          }
                          ?>
                        </p>
                    </div>
                    <? }?>
                 </div>



				<? if($o->info ==! "") {?>
                <div class="col-md-12 textS">
                    <div class="obj">
                        <p>О клиенте, шифровки</p>
                    	<p class="text"><strong> <?=replace_r_n_br($o->info)?> </strong></p>
                    </div>
                </div>
                <? } ?>



                <div class="col-md-12 textS">
                	<?  if($o->marketing_id ==! "" || $o->marketing_id ==! 0) {?>
                    <div class="obj ">
                        <p>Маркетинг план:</p>
                        <p class="gray"><strong><?  $array = explode(",", $o->marketing_id);  for ($y=0; $y<sizeof($array); $y++) {?>  <?=SelectData('type_company_portrait',$array[$y]);?>  <br> <? } ?></strong></p>
                    </div>
                    <? }?>

					<?  $sql = getSQLArrayO("select * from my_news where find_in_set('{$o->id}',client_id) <> 0 AND page_id = 868 AND visible = 1 "); if(count($sql) != 0) { ?>
                    <div class="obj">
                        <p>Привязан к компании:</p>
                        <p class="gray"><strong>
                        <?
                            //select * FROM my_news WHERE AND page_id = 868 and (client_id LIKE '%{$o->id},%'|| client_id LIKE '%,{$o->id},'|| client_id LIKE '%{$o->id}')
                            for($x=0;$x<sizeof($sql);$x++)
                            {
                                 ?>
                                 	<a href="/record/<?=$sql[$x]->id;?>" data-id="" style="display:inline-block"><?=$sql[$x]->name_company;?></a><br>
                                 <?
                            }
                        ?>
                        </strong></p>
                    </div>
                    <? } ?>

                    <? if(count($sql) == 0){?>
                    	<br>
                        &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;<button type="button" class="btn  btn-success" data-id="<? echo $o->id;?>"><span class="glyphicon glyphicon-plus"></span> создать компанию</button>
                        <span style="font-size:12px; display:block;font-family: 'segoeui_sb';">&nbsp; &nbsp;у этого физ. лица нет привязанной компании</span>
                        <br>
                    <? }  ?>

                    <? $sql = getSQLArrayO("select * from my_face_to_face where page_id ={$o->id} and visible = 1 ");
                      $sqls = getSQLArrayO("select * from my_face_to_face where parent_id ={$o->id} and visible = 1 ");

                      if(count($sql) > 0 || count($sqls) > 0 ) {

                         ?>
                    <div class="obj">
                        <p>Связанное лицо:</p>
                        <p class="gray"><strong>
                        <?
                            for($x=0;$x<sizeof($sql);$x++)
                            {
                                $datas = getSQLRowO("SELECT name FROM my_face WHERE id = {$sql[$x]->parent_id} ");
                                if($sql[$x]->parent_id == $o->id) continue;
                               ?>
                                  <a href="/person/<?=$sql[$x]->parent_id;?>" data-id="" style="display:block"><?=$datas->name;?></a>
                               <?
                            }


                            for($x=0;$x<sizeof($sqls);$x++)
                            {
                                $datass = getSQLRowO("SELECT name FROM my_face WHERE id = {$sqls[$x]->page_id} ");
                                if($sqls[$x]->page_id == $o->id) continue;
                               ?>
                                  <a href="/person/<?=$sqls[$x]->page_id;?>" data-id="" style="display:block"><?=$datass->name;?></a>
                               <?
                            }

                        ?>
                        </strong></p>
                    </div>
                  <? } ?>




                 </div>





                <div class="col-md-12 user">
                	<div class="obj">
                    	<p>Бенефициар клиента:</p>
                    	<p class="gray"><strong>
                        <? $resp = SelectDataRowOArray('users',$o->manager_id,0);?>
                        <a href="/profile/view/<?=$resp->id?>/" target="_blank"><?=$resp->name;?></a>
                        </strong></p>
                    </div>

                 	<? if($o->edate == !null) {?>
                    <div class="obj">
                        <p>Дата ред.</p>
                        <p class="gray"><strong><? echo date('d/m/Y H:i', $o->edate); ?></strong></p>
                    </div>
                    <? }?>

                    <? if($o->cdate == !null) {?>
                  	<div class="obj">
                    	<p>Дата созд.</p>
                    	<p class="gray"><strong><? echo date('d/m/Y H:i', $o->cdate); ?></strong></p>
                    </div>
                    <? }?>

                  <? if($o->skidka_led > 0) {?>
                  <div class="obj">
                    	<p>Коэффициент для сайта LED.RU:</p>
                    	<p class="gray"><strong><? echo $o->skidka_led;?></strong></p>
                  </div>
                  <? } ?>

                </div>


            	<? $res = getSQLRowO("SELECT id FROM my_news_history WHERE page_id={$o->id} AND type = 'face'"); if($res->id > 0) { ?>
                <div class="col-md-12 user">
                	<div class="obj">
                    	<p>Последняя правка:</p>
                    	<p class="gray"><strong><? $FACE->getUp($o->id, 'face');?></strong></p>
                    </div>
                </div>
                <? } ?>

                <br clear="all"><br clear="all">


            </div>

            </div>

              <?

              if($o->international == NULL)
              {

                  $data = $FACE->getMobileUser($o->mobile);
                  include("./modules/face/tpl/discount.tpl");

              }

              ?>


            	<div class="col-md-12 comment-block-position">
                <div class="comment-block">

                    <div class="comment-messages">
                    <div class="comment-empty"></div>
                        <?
                            $COM = new Comments($CFG->oPageInfo->id);
                            $COM->setPostId($o->id);
                            $COM->getList();
                            $COM->setLang($CFG->SYS_LANG);
                            $COM->onPostComment();
                        ?>
                    </div>
                </div>
            </div>




        </article>


</div>


<script>
	$(document).on('click','.btn.btn-success',function(e)
	{
		var id = $(this).attr('data-id');


		$.ajax
		({
			url: "/static/client_id_record/",
			type: "POST",
			data: {"id": id},
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				if(response >  0)
				{
					$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Запись создана.</h4></div>');

					setTimeout(function() {location.href = '/record/'+response.replace(/\s/g,'');}, 1);
				}
				else
				{
					$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
				}
			}

		});


	});
</script>
