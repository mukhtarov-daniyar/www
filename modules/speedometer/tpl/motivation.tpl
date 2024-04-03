<?

$data = $CFG->FORM->getFullForm();
$CFG->oPageInfo->html_title = 'Продажи за день';
?>


<h2><img alt="" src="/tpl/img/new/icon/10_red.png">Мотивации</h2>
<br>
<div class="row tabs">
    <div class="obj">
        <h1><a href="<?=sklad_act();?>&tabs=1&counts=2">Наличный склад</a></h1>
    </div>
    <div class="obj">
        <h1><a href="/speedometer/nomenclature_view/?cdate=0">Все реализации</a></h1>
    </div>

    <? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85) {?>
    <div class="obj">
        <h1><a href="/speedometer/cashbox/">Кассы</a></h1>
    </div>
		<? } ?>

    <div class="obj">
        <h1><a href="/speedometer/analysis/<?=date('m');?>/wp/ASC">Анализ по  номенклатуре</a></h1>
    </div>


    <div class="obj">
        <h1><a href="/speedometer/gkgd/">GKGD</a></h1>
    </div>
</div>

<style type="text/css">
table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:12px; text-align:center; margin-bottom:20px; margin-top:50px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}
</style>



<div class="white">

  <table class="price">
    <tr>
      <th>Менеджер</th>
      <th>Январь</th>
      <th>Февраль</th>
      <th>Март</th>
      <th>Апрель</th>
      <th>Май</th>
    </tr>

<?
  $l = getSQLArrayO("SELECT  id, name, vdate, visible, avatar, status, access, dismissed FROM {$CFG->DB_Prefix}users WHERE user_id='{$CFG->USER->USER_DIRECTOR_ID}' AND visible = 1 order by name,vdate DESC");
  for ($i=0; $i<sizeof($l); $i++)
  {
      if($NEWS->MotivationDot('01', $l[$i]->id) == 0 && $NEWS->MotivationDot('02', $l[$i]->id) == 0) continue;

    ?>
    <tr>
      <td><?=$l[$i]->name;?></td>
      <td>
        <span>Продажи за месяц</span> <strong style="color:#f00"><?=$NEWS->Motivation('01', $l[$i]->id);?> тг</strong><br>
        <span>Мотивация за месяц</span> <strong style="color:#f00"><?=$NEWS->MotivationDot('01', $l[$i]->id);?> тг</strong>
      </td>
      <td>
        <span>Продажи за месяц</span> <strong style="color:#f00"><?=$NEWS->Motivation('02', $l[$i]->id);?> тг</strong><br>
        <span>Мотивация за месяц</span> <strong style="color:#f00"><?=$NEWS->MotivationDot('02', $l[$i]->id);?> тг</strong>
      </td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <?
  }
?>




  <table>


</div>


<div style="clear:both"></div>
