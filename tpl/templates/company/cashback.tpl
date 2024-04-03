<br>
<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Бухгалтерия</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>


<div class="white">
<h2>История начисления кешбека</h2>
<style>
table.price {  width:100%; margin:0 auto; border-collapse: collapse; text-align:center; margin-bottom:20px; margin-top:20px;  font-family: 'segoeui'; text-transform:uppercase}
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align:middle; font-size:12px; color:#000;}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:15px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle}
table.price tr.class_6 { background: #F4DCDC}

</style>


  <table class="price">
    <tr>
      <th>Запись</th>
      <th>Сумма</th>
      <th>Текст</th>
      <th>Мобильный</th>
      <th>Дата</th>
    </tr>

 <?

            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE visible='1' AND user_id = '{$CFG->USER->USER_ID}' ");

            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pid .= $sql[$i]->id.', ';
            }


            $and_id = trim($pid, ", ");

            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE visible=1 AND user_id IN({$and_id})  order by cdate DESC");

            for($y=0; $y<sizeof($sql); $y++)
            {
               ?>

             <tr class=" class_<?=$sql[$y]->status ?>">
              <td><strong>

              <?
              	$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$sql[$y]->page_id}'");  $pr = $o;
              	if($o->page_id == 868)
                	$url = 'record';
                else
                	$url = 'deal';

              ?>
              <a href="/<?=$url;?>/<?=$sql[$y]->page_id;?>#<?=$sql[$y]->comment_id;?>" target="_blank">*<?=$o->name_company;?></a>
              </strong></td>
              <td><?=$sql[$y]->price;?></td>
              <td style="width:30%"><? $o = getSQLRowO("SELECT text FROM {$CFG->DB_Prefix}comments WHERE id='{$sql[$y]->comment_id}'"); echo utf8_substr($o->text, 0, 50).'...'; ?></td>
              <td><a href="/cashback/<?=$sql[$y]->mobile;?>/" target="_blank"><?=$sql[$y]->mobile; $res = unserialize($pr->name_client); echo ', '.$res[0]; ?></a></td>
              <td style="width:11%"><?=dateSQL2TEXT($sql[$y]->cdate, "DD MN YYYY hh:mm");?></td>
            </tr>

 <? } ?>

	</table>

</div>
