<div id="comment-post_<?=$o->id?>"></div>

<?


	$corent = sqlDateNow();


    if($o->task == 1)
    {
    	$status_taks = $o->status_taks;

        if(($status_taks == 1) && $corent > $o->offtask)
        {
        	$offtask = ' offtask';
         }
     }
     else
     	$status_taks = 0;
?>

<div class="comment-block__comment-post task_<?=$status_taks; ?> <?=$offtask?> comment-post_size-<?=$o->level?>" id="comment-post_<?=$o->id?>">
    <div class="data_user">

        <div class="col-md-12 name">
            <? $x = getSQLRowO("SELECT id, name, avatar FROM {$CFG->DB_Prefix}users WHERE id='{$o->user_id}'");  ?>
            <div class="col-md-2 avatar">
                <a href="/profile/view/<?=$x->id;?>"><img src="<?=makePreviewName($x->avatar,70,70,0);?>"></a>
            </div>
            <div class="col-md-9 name">
                <a href="/profile/view/<?=$x->id;?>"><?=$x->name;?></a>
                <span class="cdate"><?=dateSQL2TEXT($o->cdate, "DD.MM.YY, H:i ")?>

                    <textarea style="width:0px; height:0px; overflow:hidden; position:absolute; right:0; bottom:0; border:0;" id="textarea-example_<? echo $o->id;?>"><? $xcode = getSQLRowO("SELECT xcode FROM my_pages WHERE id='{$_POST[pid]}'");	echo request_url_home(); ?>/<? echo $xcode->xcode;?>/<? echo $_POST['id']?>#<? echo $o->id;?></textarea>

                    <textarea style="width:0px; height:0px; overflow:hidden; position:absolute; right:0; bottom:0; border:0;" id="text-example_<? echo $o->id;?>"><? echo $o->text;?></textarea>

                    <?
                    $news = SelectDataRowOArray('news', $o->page_id);
                    $director_id = SelectDataRowOArray('users', $o->user_id, 0);
                    echo deteleComent($o->id, $o->cdate, $o->user_id, $news->type_company_id, $director_id->user_id);


										 ?>

                    <a class="btn-clipboard_<? echo $o->id;?>" data-clipboard-target="#text-example_<? echo $o->id;?>">COPY TEXT</a>
                    <a class="btn-clipboard_<? echo $o->id;?>" data-clipboard-target="#textarea-example_<? echo $o->id;?>">LINK</a>

                    <script type="text/javascript">
											$('.btn-clipboard_<? echo $o->id;?>').on('click', function(e)
											{
												new ClipboardJS('.btn-clipboard_<? echo $o->id;?>');

												$('.alert').html('Скопировано');
												$('.alert').animate({'opacity':'show'}, 1000);
												$('.alert').animate({'opacity':'hide'}, 4000);

												e.preventDefault();
											});
                    </script>


                </span>
            </div>


            <?

                if($o->cashback > 0)
                {
                    echo "<div class='col-md-12 money blue'>";
                        echo "<div class='status blue'>";
                        	$cashback = getSQLRowO("SELECT price FROM {$CFG->DB_Prefix}cashback WHERE id='{$o->cashback}'");
                            echo "Начислен КЕШБЕК: ".$cashback->price.' '.$CFG->USER->USER_CURRENCY;
                        echo "</div>";
                    echo "</div>";
                }


                if($o->premiumplus)
            	{
                	$plus = explode(",", $o->premiumplus);

                    $plus = array_filter($plus);

                    foreach ($plus as $value)
                    {
                        $respon = getSQLRowO("SELECT count, manager_id FROM {$CFG->DB_Prefix}money_list WHERE id='{$value}'");

                        echo "<div class='col-md-12 money red'>";
                            echo "<div class='status red'>";
                                echo "<img src='/tpl/img/new/red_status.png'> <a href='/profile/view/".$respon->manager_id."' target='_blank'>".SelectData_live('users', $respon->manager_id)."</a>, Премия + ".($respon->count)."   ".$CFG->USER->USER_CURRENCY."";
                            echo "</div>";
                        echo "</div>";
					}
                 }
             ?>




        <?
            if($o->premiumminus)
            {
                $prem = explode(",", $o->premiumminus);

                if(count($prem) >= 1)

                for ($y=0; $y<sizeof($prem); $y++)
                {
                   if($prem[$y] == "" || $prem[$y] == "0" ) continue;

                    $res = getSQLRowO("SELECT user_id, manager_id, count  FROM {$CFG->DB_Prefix}money_minus_list WHERE id='{$prem[$y]}'");

                    echo "<div class='col-md-12 money premiumminus'>";
                        echo "<div class='status premiumminus'>";
                            echo "<img src='/tpl/img/new/premiumminus_status.png'> <a href='/profile/view/".$res->manager_id."' target='_blank'>".SelectData_live('users', $res->manager_id, 0)."</a>, минус - ".$res->count."   ".$CFG->USER->USER_CURRENCY."";
                        echo "</div>";
                    echo "</div>";
                }
            }
        ?>





        </div>
        <div style="clear:both"></div>
        <div class="comment-post-body" <? if($o->whatsapp == 1) { echo '  style="background:#8FBCB5 !important" ';} if($o->whatsapp == 2) { echo '  style="background:#D7EFEA !important" ';}?>>

        <? if($CFG->USER->USER_NOTE_SELECT) { ?>
        <input type="checkbox" class="form-check-input" name="approve[]" value="<?=$o->id;?>" id="exampleCheck1_<?=$o->id;?>">
        <label class="form-check-label" for="exampleCheck1_<?=$o->id;?>">
            <?=nl2br($o->text);?>
        </label>
        <? } else echo nl2br($o->text); ?>








			<?  $WP = new Whatsapp();
            	if($o->whatsapp == 1 && $o->whatsapp_namber != '')
                {

                	$namber = getSQLRowO("SELECT namber,name FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber= '{$o->whatsapp_from}' " ); ?>
                		<hr style="padding:0; margin:0; margin-top:5px; border:0; border-top:#333 1px dashed">
                    	<div style="font-size:10px; display: block; font-family: 'segoeui_b';">Исходящее с номера <a href="tet:<?=$o->whatsapp_from;?>" class=" mobile_copy"><?=$namber->name;?></a> на номер <?=$WP->real_tel_3($o->whatsapp_namber);?></div>
                	<?
                }
            ?>

			<?
            	if($o->whatsapp == 2 && $o->whatsapp_namber != '')
                {
                	$namber = getSQLRowO("SELECT namber,name FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber= '{$o->whatsapp_from}' " );
                    ?>
                		<hr style="padding:0; margin:0; margin-top:5px; border:0; border-top:#333 1px dashed">
                    	<div style="font-size:10px; display: block; font-family: 'segoeui_b';">Входящее с номера  <? $WP->real_tel_3($o->whatsapp_namber); ?> на номер <a href="tet:<?=$o->whatsapp_from;?>" target="_blank"  data-toggle="tooltip" data-placement="bottom" title="<?=$o->whatsapp_from;?>" data-original-title="<?=$o->whatsapp_from;?>" class="mobile_copy"><?=$namber->name;?></a>

                        </div>

                	<?
                }
            ?>



            <?

            $music = explode(",", $o->attach_files_music);

            for ($y=0; $y<sizeof($music); $y++)
            {
                if($music[$y] == "" || $music[$y] == "0") continue;

                ?>

                <? $data = SelectDataRowOArray("attachments", $music[$y], 0); ?>

				<script type="text/javascript">
                $(document).ready(function(){

                    $("#jquery_jplayer_<?=$music[$y];?>").jPlayer({
                        ready: function(event) {
                            $(this).jPlayer("setMedia", {
                                title: "<?=$data->original?>",
                                <? if($data->type == "aac") echo "aac"; else echo "mp3";?>: "/<?=$data->text;?>"
                            });
                        },
                        play: function() { // To avoid multiple jPlayers playing together.
                            $(this).jPlayer("pauseOthers");
                        },
                        swfPath: "/tpl/player/jplayer",
                        supplied: "mp3, oga, m4a",
                        wmode: "window",
                        useStateClassSkin: true,
                        cssSelectorAncestor: "#jp_container_<?=$music[$y];?>",
                        autoBlur: false,
                        smoothPlayBar: true,

                        keyEnabled: true,
                        remainingDuration: true,
                        toggleDuration: true
                    });
                });
                </script>





                <div id="jp_container_<?=$music[$y];?>" class="jp-audio" role="application" aria-label="media player">
                    <div class="jp-type-single">
                        <div id="jquery_jplayer_<?=$music[$y];?>" class="jp-jplayer"></div>
                        <div class="jp-details">
                           <a href="/<?=$data->text;?>" target="_blank">Скачать аудиофайл <? if($data->type == "aac") echo "aac"; else echo "mp3";?></a>
                        </div>
                        <div class="jp-gui jp-interface">
                            <div class="jp-controls">
                                <button class="jp-play" role="button" tabindex="0">play</button>
                                <button class="jp-stop" role="button" tabindex="0">stop</button>
                            </div>
                            <div class="jp-progress">
                                <div class="jp-seek-bar">
                                    <div class="jp-play-bar"></div>
                                </div>
                            </div>
                            <div class="jp-volume-controls">
                                <button class="jp-mute" role="button" tabindex="0">mute</button>
                                <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                                <div class="jp-volume-bar">
                                    <div class="jp-volume-bar-value"></div>
                                </div>
                            </div>
                            <div class="jp-time-holder">
                                <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                                <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                                <div class="jp-toggles">
                                    <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                                </div>
                            </div>
                        </div>
                        <div class="jp-no-solution">
                            <span>Update Required</span>
                            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
                        </div>
                    </div>
                </div>




                <?
            }
            ?>



            <?

    		$GAL = new Gallery();

            $img = explode(",", $o->attachments_image);

            if(count($img) > 1)  echo '<div class="hrs"></div>';

            for ($y=0; $y<sizeof($img); $y++)
            {
                if($img[$y] == "" || $img[$y] == "0") continue;

                $img_url = str_replace('big', 'med', $img[$y]);

                if (file_exists('.'.$img_url))
                {
                    echo '<div class="file-galery"><a class="quickbox" data-fancybox="images" href="'.$img[$y].'"><img src="'.$img_url.'"></a></div>';
                }
                else
                {
                    echo '<div class="file-galery"><a class="quickbox" data-fancybox="images" href="'.$img[$y].'"><img src="'.makePreviewName($img[$y], 100, 100, 2).'"></a></div>';
                }

            }
            ?>





            <?
                $res = explode(",", $o->attachments_file);

                if(count($res) > 1)  echo '<div class="hrs"></div>';

                for ($y=0; $y<sizeof($res); $y++)
                {
                    if($res[$y] == "" || $res[$y] == "0") continue;

                    $obj = SelectDataRowOArray('attachments', $res[$y], 0);
                    ?>
                        <a href="/<?=$obj->text;?>" class="file-attachments" target="_blank"><?=$obj->original;?>.<?=$obj->type;?></a>
                    <?
                }
            ?>


            <?
                $res = explode(",", $o->commercial);

                if(count($res) > 1)  echo '<div class="hrs"></div>';

                for ($y=0; $y<sizeof($res); $y++)
                {
                    if($res[$y] == "" || $res[$y] == "0") continue;
                    $string = str_replace(' ', '', $res[$y]);
                    ?>
                        <a href="/static/down_commercial/<?=$string;?>" class="file-attachments" target="_blank">Счет на оплату №<?=$string;?></a> | <a href="/static/commercial/<?=$string;?>" target="_blank" style="font-size:12px">Открыть без скачивания</a><br>
                    <?
                }
            ?>


            <?
                $res = explode(",", $o->reminder);

                if(count($res) > 1)  echo '<div class="hrs"></div>';

                $times[0] = '00:00';	$times[1] = '01:00';	$times[2] = '02:00';	$times[3] = '03:00';	$times[4] = '04:00';	$times[5] = '05:00';	$times[6] = '06:00';	$times[7] = '07:00';	$times[8] = '08:00';	$times[9] = '09:00';	$times[10] = '10:00';	$times[11] = '10:00';	$times[12] = '12:00';	$times[13] = '13:00';	$times[14] = '14:00';	$times[15] = '15:00';	$times[16] = '16:00';	$times[17] = '17:00';	$times[18] = '18:00';	$times[19] = '19:00';	$times[20] = '20:00';	$times[21] = '21:00';	$times[22] = '22:00';	$times[23] = '23:00';


                for ($y=0; $y<sizeof($res); $y++)
                {
                    if($res[$y] == "" || $res[$y] == "0") continue;

                    $obj = SelectDataRowOArray('reminder', $res[$y], 0);

                    ?>
                        <div class="block_reminder"><strong><?=dateSQL2TEXT($obj->date_start, "DD.MM.YY");?> г. <?=$times[$obj->time_start];?></strong> - <?=$obj->text;?> <span>(<? $arr = unserialize($obj->manager_id);
                        for ($z=0; $z<sizeof($arr); $z++)
                        {
                        	$response = SelectDataRowOArray("users", $arr[$z], 0);
                            ?><a href="/profile/view/<?=$response->id;?>"  style="color:#BD2149 ; font-size:12px"><?=$response->name?></a> <?
                        }

                         ?>)</span></div>
                    <?
                }
            ?>







		<!--?  ?!-->






            <?

            if($o->accessrecord != '')
            {
            	replycommentIMG($o->id, $o->page_id);
            }




            if($o->task == 1)
            {
            	?>
                <div class="col-md-12 my-taks">
                    <div class="col-md-4"><strong>Исполнитель</strong></div>
                    <div class="col-md-4"><strong>Дедлайн</strong></div>
                    <div class="col-md-4"><strong>Статус</strong></div>

                    <div class="col-md-4"><? $doer = SelectDataRowOArray('users', $o->doer, 0);?> <a href="/taks/<?=$doer->id;?>"><?=$doer->name;?></a></div>
                    <div class="col-md-4 offtask"><?=dateSQL2TEXT($o->offtask, "DD.MM.YY")?></div>
                    <div class="col-md-4 offtask">
                    <strong>

                    <?

                        	if(($o->status_taks == 1) && $corent < $o->offtask)
                            {
                            	echo '<span class="starttask">В работе</span>';
                            }

                            if(($o->status_taks == 1) && $corent > $o->offtask)
                            {
                            	echo '<span class="starttask">Просрочено</span>';
                            }

                            if($o->status_taks == 2)
                            {
                            	echo '<span class="moder">На модерации</span>';
                            }

                            if($o->status_taks == 3)
                            {
                            	echo '<span class="offtask">Выполнено</span>';
                            }




                        ?>

                    </strong></div>
                </div>

                <div style="clear:both"></div>
            <?
             }
            ?>
<a href="#" id="<?=$o->id?>" class="comment-post__reply-button">ответить</a>
        </div>




        <?
            if(!$o->access == 0)
            {
                $access = explode(",", $o->access);

                if(count($access) >= 1)

                echo '<div class="hrs" style="border-bottom: solid #fff 1px"></div>';

                echo '<div>Это скрытая заметка: ';

                for ($y=0; $y<sizeof($access); $y++)
                {
                   if($access[$y] == "" || $access[$y] == "0") continue;

                    echo " <a href='/profile/view/".$access[$y]."' target='_blank'>".SelectData('users', $access[$y], 0)."</a> ";
                }

                echo '</div> ';
            }
        ?>

    </div>


   <div id="replay_<?=$o->id?>" class="rr"></div>


</div>

<?

if($o->response && $o->task = 1)
{ ?>
    <div class="response comment-post">
    <? $doer = SelectDataRowOArray('users', $o->doer, 0);?> <a href="/taks/<?=$doer->id;?>"><?=$doer->name;?></a>
    <?=$o->response;?>
    </div>

<?
	}
?>
