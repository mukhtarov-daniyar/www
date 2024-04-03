<? $id = $sql[$i]->id; ?>
  <tr data-id="<?=$id;?>">
    <td style="font-size:11px"><? $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}users WHERE id='{$sql[$i]->user_id}'"); echo $o->name;?>, <?	$num = getSQLRowO("SELECT namber, name FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE id='{$sql[$i]->namber}'");?> <div class="mobile"><?=$num->namber;?></div> <strong><?=$num->name;?></strong></td>
    <td>
      <div class="block">
          <span class="extremum-click">Показать текст рассылки</span>
          <div class="extremum-slide"><? echo '<div class="ran_text">'.$sql[$i]->text.'</div>';	?></div>
      </div>
    </td>

    <td><a href="<?=$sql[$i]->img;?>" class="quickbox" data-fancybox="images"><img src="<?=$sql[$i]->img;?>" style=" width:50px;"></a></td>
    <td><?=$sql[$i]->cdate;?></td>
    <td style="word-break: break-all; "><?=$sql[$i]->subject;?></td>
    <td><span style="color:#6CC17A"> <? $one = getSQLRowO("SELECT COUNT(id) as id FROM {$CFG->DB_Prefix}tmp_whatsapp_rss WHERE whatsapp_id='{$id}' "); echo $one->id;?></span> /

    <span style="color:#F00"><? $send = getSQLRowO("SELECT COUNT(id) as id FROM {$CFG->DB_Prefix}tmp_whatsapp_rss WHERE whatsapp_id='{$id}' AND visible = 1 "); echo $send->id; ?> </span>
    <br>
      <a class="quickbox wp_id" data-id="<?=$id;?>" href="#res_data">Статистика</a><br>
      <? if($sql[$i]->user_id == $CFG->USER->USER_ID || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 133) {?>
      <a href="/whatsapp_new/edit/<?=$id;?>">Редактировать</a><br>
      <a class="delete_wp" data-id="<?=$id;?>" href="#">Остановить и удалить</a>
      <? } ?>

    </td>
  </tr>
