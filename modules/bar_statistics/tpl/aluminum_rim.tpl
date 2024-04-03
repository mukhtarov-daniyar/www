

<style>
	.yellow { background:#F9FFB2}
	.blue { background:#E2F1FF}
	.green { background:#F2FFF5}
	.siren { background: #FF00F2; color: #fff !important; font-weight: bold;}


	table.price {  width:60%;  border-collapse: collapse; font-size:14px; text-align:center; margin-bottom:20px; margin-top:10px;  font-family: 'segoeui'; }
	table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top;  color:#000}
	table.price th {color:#fff; border: 1px solid #000;background: #F84241; padding:0; margin:0;  padding:5px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb'; cursor: pointer;}


	table.price2 {  width:100%;  border-collapse: collapse; font-size:14px; text-align:center; margin-bottom:20px; margin-top:10px;  font-family: 'segoeui'; margin-bottom: 50px;}
	table.price2 td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top;  color:#000;}
	table.price2 th {color:#fff; border: 1px solid #000;background: #F84241; padding:0; margin:0;  padding:5px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb'; cursor: pointer;}
	table.price2 .right  { text-align: right;}


	input.name ,  input.count { text-align: center; border: 0}
	input.count { width: 60px;}
	input.name { width: 100%;}


 .cdatestart { width: 1px; height: 1px; border: 0; opacity: 0;}
</style>



<h2><img alt="" src="/tpl/img/new/icon/bar_red.png">Склад Алматы РиМ за <?=$_GET['cdate'];?></h2>
<br>
<div class="row tabs">
	<div class="obj">
		<h1><a href="/salary/?month=<?=date("m", strtotime("-1 month"))*1;?>&year=<?=date('Y')?>">Финасововый анализ</a></h1>
	</div>

	<? if($CFG->USER->USER_EXPENSES_LIL == 1) {?>
  <div class="obj">
      <h1><a href="/speedometer/lil/020440010420">Прибыльность Сделок</a></h1>
  </div>
  <? } ?>

	<div class="obj">
		<h1><a href="/bar_statistics/?year=<?=date('Y')?>&month=<?=date('m')?>">Molotok.kz Алматы</a></h1>
	</div>

	<div class="obj">
		<h1 class="active"><a href="/bar_statistics/aluminum_rim/?year=<?=date('Y')?>&month=<?=date('m')?>">Алматы РиМ</a></h1>
	</div>

	<div class="obj">
		<h1><a href="/bar_statistics/pavlodar_asem/?year=<?=date('Y')?>&month=<?=date('m')?>">Павлодар Асем</a></h1>
	</div>

</div>


<?
$warehouse_id = getSQLRowO("SELECT * FROM my_data_1c_warehouse WHERE `name` LIKE 'Алматы Рим' ");


$data_start = new DateTime($_GET['year'].'-'.$_GET['month']);
$m_last = date('t', $data_start->getTimestamp());

$url_1 = '/speedometer/nomenclature_view/?warehouse_nomenclature[]='.$warehouse_id->id.'&monthstart='.$_GET['year'].'-'.$_GET['month'].'-01 00:01:01'.'&monthend='.$_GET['year'].'-'.$_GET['month'].'-'.$m_last.' 23:59:59';

$url_2 = '/speedometer/nomenclature_view/?warehouse_nomenclature[]='.$warehouse_id->id.'&monthstart='.$_GET['year'].'-'.$_GET['month'].'-01 00:01:01'.'&monthend='.$_GET['year'].'-'.$_GET['month'].'-'.$m_last.' 23:59:59'.'&buch=0';

$url_3 = '/speedometer/nomenclature_view/?warehouse_nomenclature[]='.$warehouse_id->id.'&monthstart='.$_GET['year'].'-'.$_GET['month'].'-01 00:01:01'.'&monthend='.$_GET['year'].'-'.$_GET['month'].'-'.$m_last.' 23:59:59'.'&buch=1';
?>


 <div class="white">
	 <table class="price2">
		<tbody>
		 <tr>
			 <th class="right">Наименование</th>
			 <th>Всего, тг</th>
			 <th>ВП, тг</th>
			 <th>50% прибыли</th>
		 </tr>

		 <tr>
			 <td class="right"><strong>УУ Продажи Алюминия со склада Алматы РиМ</strong></td>
			 <td><a href="<?=$url_2;?>" target="_blank"><? $d_price[0] = $asi->rim_uu->price; echo number_sum($d_price[0]);?></a></td>
			 <td><? $d_profit[0] = $asi->rim_uu->profit; echo number_sum($d_profit[0]);?></td>
			 <td><? echo number_sum($d_profit[0]/2);?></td>
		 </tr>

		 <tr>
			 <td class="right"><strong>БУ Продажи Алюминия со склада Алматы РиМ</strong></td>
			 <td><a href="<?=$url_3;?>" target="_blank"><? $d_price[1] = $asi->rim_bu->price; echo number_sum($d_price[1]);?></a></td>
			 <td><? $d_profit[1] = $asi->rim_bu->profit; echo number_sum($d_profit[1]);?></td>
			 <td><? echo number_sum($d_profit[1]/2);?></td>
		 </tr>


		 <tr>
			 <td class="right"><strong>Итого</strong></td>
			 <td><a href="<?=$url_1;?>" target="_blank"><? echo number_sum(array_sum($d_price)); ?></a></td>
			 <td><? echo number_sum(array_sum($d_profit)); ?></td>
			 <td><? echo number_sum(array_sum($d_profit)/2); ?></td>
		 </tr>

		</tbody>
		</table>


<br>
	<div class="col-md-12">
		<form method="get" class="redirect" action="/bar_statistics/aluminum_rim/">
			Выберите год:

			<select name="year" class="selectpicker show-tick year">
					<option value="2022" <? if($_GET['year'] == '2022') { echo 'selected="selected"';}?>>2022</option>
					<option value="<?=date('Y');?>" <? if($_GET['year'] == date('Y')) { echo 'selected="selected"';}?>>2023</option>
			</select><br><br>

			Выберите месяц:
			<select name="month" class="selectpicker show-tick month">
					<option value="01" <? if($_GET['month'] == '01') { echo 'selected="selected"';}?>>Январь</option>
					<option value="02" <? if($_GET['month'] == '02') { echo 'selected="selected"';}?>>Февраль</option>
					<option value="03" <? if($_GET['month'] == '03') { echo 'selected="selected"';}?>>Март</option>
					<option value="04" <? if($_GET['month'] == '04') { echo 'selected="selected"';}?>>Апрель</option>
					<option value="05" <? if($_GET['month'] == '05') { echo 'selected="selected"';}?>>Май</option>
					<option value="06" <? if($_GET['month'] == '06') { echo 'selected="selected"';}?>>Июнь</option>
					<option value="07" <? if($_GET['month'] == '07') { echo 'selected="selected"';}?>>Июль</option>
					<option value="08" <? if($_GET['month'] == '08') { echo 'selected="selected"';}?>>Август</option>
					<option value="09" <? if($_GET['month'] == '09') { echo 'selected="selected"';}?>>Сентябрь</option>
					<option value="10" <? if($_GET['month'] == '10') { echo 'selected="selected"';}?>>Октябрь</option>
					<option value="11" <? if($_GET['month'] == '11') { echo 'selected="selected"';}?>>Ноябрь</option>
					<option value="12" <? if($_GET['month'] == '12') { echo 'selected="selected"';}?>>Декабрь</option>
			</select>

			<button type="submit" class="btn reject">Выбрать</button>
		</form>
	</div>
	<br>	<br>	<br>	<br><br><br>

 </div>





<script>
$(document).on('click','form.redirect button.btn.reject',function(e)
{
	var year = $('form.redirect .year option:selected').val();
	var month = $('form.redirect .month option:selected').val();



	$('#myModalBox').modal({backdrop: 'static', keyboard: false});
	$(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
	document.location.href = "/bar_statistics/aluminum_rim/?year=" + year + "&month=" + month;

	e.preventDefault();
});

</script>
