<style>
.price .up_sum, .price .up_count { font-size: 10px;}
</style>

<table class="price tsort_<?=$res->id;?>">
<thead>
  <tr>
    <th>Название</th>
		<th>Единица</th>
    <th>Тип затраты</th>
    <th>Количество<br><span class="up_count"></span></th>
    <th>Цена</th>
		<th>&nbsp;&nbsp;&nbsp;Сумма&nbsp;&nbsp;&nbsp;<br><span class="up_sum"></span></th>
		<th>&nbsp;&nbsp;&nbsp;Дата&nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
	</thead>
	<tbody>
	<?

		foreach($res->ДанныеПоПоступлению as $val)
		{
      if($val->Номенклатура != '') {$val->Номенклатура = $val->Номенклатура;} else {$val->Номенклатура = $val->Комментарий;}
			?>
			<tr>
				<td><?=$val->Номенклатура;?></td>
				<td><?=$val->ЕдиницаИзмерения;?></td>
        <td><?=$SPEED->TypeLilOpres($val->Type);?></td>
				<td><?=number_sum($val->Количество); $countS[] = $val->Количество; ?></td>
				<td><?=number_sum($val->Цена); $priceS[] = $val->Цена;?></td>
				<td><?=number_sum($val->Сумма); $priceSS[] = $val->Сумма;?></td>
				<td><?=date("Y-m-d H:i", strtotime($val->Дата));?></td>
			</tr>
			<?
		}
	?>
  </tbody>
	<tr>
		<td></td>
		<td></td>
    <td></td>
		<td class="down_count"><strong><? echo number_sum(array_sum($countS));?></strong></td>
		<td><!--strong><? echo number_sum(array_sum($priceS));?></strong!--></td>
		<td class="down_sum"><strong><? echo number_sum(array_sum($priceSS));?></strong></td>
		<td></td>
	</tr>
</table>

<div class="row open_deal_windows">
	Открыть сделку: <a href="/search/*<?=$res->id;?>" target="_blank"><?=$res->Сделка;?></a>
</div>

<br><br><br>

<script>
  $('.tsort_<?=$res->id;?>').tsort();
  //Если сайт загрузился - выполняем скрипт
  docReady(function()
  {
    // Расходы ЛИЛ /speedometer/lil/  - перенос кол и суммы на верх
    document.querySelector(".up_sum").innerHTML = document.querySelector(".down_sum").innerHTML;
    document.querySelector(".up_count").innerHTML = document.querySelector(".down_count").innerHTML;
  });

  function docReady(fn)
  {
      if (document.readyState === "complete" || document.readyState === "interactive")
      {
          setTimeout(fn, 1);
      } else
      {
          document.addEventListener("DOMContentLoaded", fn);
      }
  }

</script>
