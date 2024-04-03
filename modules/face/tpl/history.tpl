<style>
article.vacancies_body .gray a { margin:0; font-size:13px;font-family:'segoeui_sb'; }
.col-md-12.history  .obj { padding:10px;}
.col-md-12.history  .obj h3 { border-bottom:solid 1px #666666; padding:5px 0; margin:0;}
.manager_edit { display:block; padding:10px !important; padding-bottom:0 !important}
.col-md-12.history  .obj .data{ margin-bottom:7px;}
.col-md-12.history  .obj .data span.name { display:block; font-size:12px; margin-bottom:7px;}
.col-md-12.history  .obj .data img { display:block; margin:0 auto; width: auto; height:150px; margin-top:10px;margin-bottom:10px; border-radius:5px;}
.col-md-12.history  .obj .data a { color:#F84241}

</style>

<script>
	$(function(){ $('[data-toggle="tooltip"]').tooltip(); });
</script>

<? 
	$sql = getSQLRowO("SELECT * FROM my_news_history WHERE id = {$CFG->_GET_PARAMS[2]} and type = 'face' ");
    $o = unserialize($sql->data);
    $CFG->oPageInfo->html_title = $o->name;
 ?>



<div class="white">

        <article class="vacancies_body row" id="<?=$o->id;?>">

         <div class="col-md-12" style="padding-bottom:150px;">
         	<div class="col-md-12 record">

            <div class="object">
                <div class="title">
                    <h3><a href="/person/<?=$CFG->_GET_PARAMS[0];?>"><i class="glyphicon glyphicon-arrow-left"></i> ВЕРНУТЬСЯ К ОРИГИНАЛУ</a> <span><?=$o->mobile;?></span></h3>
                </div>

                <div class="col-md-12 history">
                    <div class="col-md-6">
                    	<div class="obj">
                        	<h3>Есть сейчас</h3>
                            <?
                            	$res = getSQLRowO("SELECT * FROM my_face WHERE id = {$CFG->_GET_PARAMS[0]}  ");
            					echo $FACE->printData($res->img, $o->img, 'img');
                                echo $FACE->printData($res->name, $o->name, 'Имя');
                                echo $FACE->printData($res->name_other, $o->name_other, 'Подробное имя');
                                echo $FACE->printData($res->email, $o->email, 'Email');
                                echo $FACE->printData($res->mobile, $o->mobile, 'Мобильный');
                                echo $FACE->printData($res->whatsapp, $o->whatsapp, 'Whatsapp');
                                echo $FACE->printData($res->marketing_id, $o->marketing_id, 'marketing');
                                echo $FACE->printData($res->manager_id, $o->manager_id, 'manager');
                                echo $FACE->printData($res->bcdate, $o->bcdate, 'bcdate');
                            ?>
                            
                    	</div>
                    </div>

                    <div class="col-md-6">
                    	<div class="obj">
                        	<h3>Было до <? echo date('d/m/Y H:i', $sql->times); ?></h3>
                            <?
                            	echo $FACE->printData($o->img, $res->img, 'img');
                                echo $FACE->printData($o->name, $res->name, 'Имя');
                                echo $FACE->printData($o->name_other, $res->name_other, 'Подробное имя');
                                echo $FACE->printData($o->email, $res->email, 'Email');
                                echo $FACE->printData($o->mobile, $res->mobile, 'Мобильный');
                                echo $FACE->printData($o->whatsapp, $res->whatsapp, 'Whatsapp');
                                echo $FACE->printData($o->marketing_id, $res->marketing_id, 'marketing');
                                echo $FACE->printData($o->manager_id, $res->manager_id, 'manager');
                                echo $FACE->printData($o->bcdate, $res->bcdate, 'bcdate');
                            ?>
                    	</div>
                    </div>

				</div>
				
                <div class="manager_edit">
                	<? $res = getSQLRowO("SELECT name FROM my_users WHERE id={$sql->user_id}"); ?>
                	<strong>Изменения вносил</strong>: <a href="/profile/view/<?=$sql->user_id;?>" target="_blank"><?=$res->name;?></a>
                </div>


            
    
                <br clear="all">

  				
            </div>

            </div>





        </article>


</div>

