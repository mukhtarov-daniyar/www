

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

<?

if(isset($arr))
{
	$cn = 0;
	foreach ($arr as  $val)
	{
		$rcnt[$cn] = $val->count;
		$cn ++;
	}
}

$rcntsum = array_sum($rcnt);
?>

<h2><img alt="" src="/tpl/img/new/icon/bar_red.png">Склад Molotok.kz Алматы за <?=$_GET['cdate'];?></h2>
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
		<h1 class="active"><a href="/bar_statistics/?year=<?=date('Y')?>&month=<?=date('m')?>">Molotok.kz Алматы</a></h1>
	</div>

	<div class="obj">
		<h1><a href="/bar_statistics/aluminum_rim/?year=<?=date('Y')?>&month=<?=date('m')?>">Алматы РиМ</a></h1>	</div>

	<div class="obj">
		<h1><a href="/bar_statistics/pavlodar_asem/?year=<?=date('Y')?>&month=<?=date('m')?>">Павлодар Асем</a></h1>
	</div>

</div>

<?
	$warehouse_id = getSQLRowO("SELECT * FROM my_data_1c_warehouse WHERE `name` LIKE 'Алматы Molotok.kz' ");

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
			 <th>Доходы филиала</th>
			 <th>Всего</th>
			 <th>Прибыльность</th>
		 </tr>

		 <tr>
			 <td class="right yellow"><strong>Продажи свои со склада Molotok.kz Алматы</strong></td>
			 <td class="yellow"><a href="<?=$url_1;?>" target="_blank"><? $d_price[0] = $asi->dina->price; echo number_sum($d_price[0]);?></a></td>
			 <td class="yellow"><? $d_profit[0] = $asi->dina->profit; echo number_sum($d_profit[0]);?></td>
		 </tr>
		 <tr>
			 <td class="right yellow"><strong>Бонус 10%</strong></td>
			 <td class="yellow"><? if($asi->dina->profit > $rcntsum){ $bonus[0] = ($asi->dina->profit/100)*10; echo number_sum($bonus[0]); } else { echo 0; $bonus[0] = 0;} ?></td>
			 <td class="yellow"></td>
		 </tr>

		 <tr>
			 <td class="right blue"><strong>Продажи свои с других складов</strong></td>
			 <td class="blue"><a href="<?=$url_2;?>" target="_blank"><? $d_price[1] = $asi->dina_alle->price; echo number_sum($d_price[1]);?></a></td>
			 <td class="blue"><? $d_profit[1] = $asi->dina_alle->profit; echo number_sum($d_profit[1]);?></td>
		 </tr>
		 <tr>
			 <td class="right blue"><strong>Бонус 10%</strong></td>
			 <td class="blue"><? if($asi->dina_alle->profit > $rcntsum){ $bonus[1] = ($asi->dina_alle->profit/100)*10; echo number_sum($bonus[1]); } else { echo 0; $bonus[1] = 0;} ?></td>
			 <td class="blue"></td>
		 </tr>


		 <tr>
			 <td class="right"><strong>Продажа менеджеров со склада Molotok.kz Алматы</strong></td>
			 <td><a href="<?=$url_3;?>" target="_blank"><? $d_price[2] = $asi->alle->price; echo number_sum($d_price[2]);?></a></td>
			 <td><? $d_profit[2] = $asi->alle->profit; echo number_sum($d_profit[2]);?></td>
		 </tr>
		 <tr>
			 <td class="right"><strong>Бонус 0%</strong></td>
			 <td><? if($asi->alle->profit > $rcntsum){ $bonus[2] = ($asi->alle->profit/100)*0; echo number_sum($bonus[2]);} else { echo 0; $bonus[2] = 0;} ?></td>
			 <td></td>
		 </tr>

		 <tr>
			 <td class="right"><strong>Итого Продажи</strong></td>
			 <td><? echo number_sum(array_sum($d_price)); ?></td>
			 <td><? echo number_sum(array_sum($d_profit)); ?></td>
		 </tr>

		 <tr>
			 <td class="right"><strong>Итого Бонусы</strong></td>
			 <td><? echo number_sum(array_sum($bonus)); ?></td>
			 <td></td>
		 </tr>

		 <tr>
			 <td colspan="3" class="right"><strong>&nbsp;</strong></td>
		 </tr>


		 <tr>
			 <td class="right"><strong>План по прибыльности</strong></td>
			 <td colspan="2" class="siren"><?=number_sum($rcntsum);?></td>
		 </tr>

		 <?
		 	$profit = array_sum($d_profit);
			if($profit - $rcntsum >  0)
			{
				?>
				<tr>
	 			 <td class="right"><strong>Прибыльность месяца</strong></td>
	 			 <td colspan="2"><?=number_sum($profit - $rcntsum);?></td>
	 		 </tr>
				<?
			}
			else
			{
				?>
				<tr>
				 <td class="right"><strong>Убыточность месяца</strong></td>
				 <td colspan="2"><?=number_sum($profit - $rcntsum );?></td>
			 </tr>
				<?
			}
		 ?>


		</tbody>
		</table>



	 <form class="tableform" method="post">
			 <table class="price">
				 <input type="hidden" name="act" value="on">
				 <tbody class="head">
					<?


					if( $arr != false)
					{
						$cnt = 0;
						foreach ($arr as  $value)
						{
							?>
							<tr class="cnt_<?=$cnt;?>">
								<td><input type="text" class="name" name="names[]->types_<?=$cnt;?>" value="<?=$value->name;?>"></td>
								<td>
									<input type="text" class="count" name="count[]->count_<?=$cnt;?>" value="<?=number_sum($value->count);?>">
									<? if(date('Y-m-d') == $_GET['cdate']) {?><a data-id="<?=$cnt;?>" href="#" class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="right" title="Удалить критерий"></a><? } ?>
								</td>
							</tr>
							<?
							$sum_count[$cnt] = $value->count;
							$cnt ++;
						}
					}
					else
					{
							echo '<tr><td colspan="2"><strong>Нет данных за выбранный период!</strong></td></tr>';
					}
					?>
				 </tbody>

				 <? if(date('Y-m') == $_GET['cdate']) {?>
				 <tr>
					 <td><strong></strong></td>
					 <td><a href="#" class="save_form btn btn-sm">Сохранить</a></td>
				 </tr>

				 <tr>
					 <td><strong>Новый критерий</strong></td>
					 <td><? if($CFG->USER->USER_ID == 133 || $CFG->USER->USER_ID == 1 ) {?><a href="#" class="add_tr"><strong>Добавить +</strong></a><? } ?></td>
				 </tr>
				 <? } ?>

				 <tr>
	 					<td><strong>Итого</strong></td>
	 					<td class="siren"><strong><? echo number_sum(array_sum($sum_count));?></strong></td>
	 			</tr>



	 		  </table>
	 </form>
<br>
	<div class="col-md-12">
		<form method="get" class="redirect" action="/bar_statistics/">
			Выберите месяц:
			<select name="month" class="selectpicker show-tick">
					<option value="<?=date('Y').'-01';?>" <? if($_GET['cdate'] == date('Y').'-01') { echo 'selected="selected"';}?>>Январь</option>
					<option value="<?=date('Y').'-02';?>" <? if($_GET['cdate'] == date('Y').'-02') { echo 'selected="selected"';}?>>Февраль</option>
					<option value="<?=date('Y').'-03';?>" <? if($_GET['cdate'] == date('Y').'-03') { echo 'selected="selected"';}?>>Март</option>
					<option value="<?=date('Y').'-04';?>" <? if($_GET['cdate'] == date('Y').'-04') { echo 'selected="selected"';}?>>Апрель</option>
					<option value="<?=date('Y').'-05';?>" <? if($_GET['cdate'] == date('Y').'-05') { echo 'selected="selected"';}?>>Май</option>
					<option value="<?=date('Y').'-06';?>" <? if($_GET['cdate'] == date('Y').'-06') { echo 'selected="selected"';}?>>Июнь</option>
					<option value="<?=date('Y').'-07';?>" <? if($_GET['cdate'] == date('Y').'-07') { echo 'selected="selected"';}?>>Июль</option>
					<option value="<?=date('Y').'-08';?>" <? if($_GET['cdate'] == date('Y').'-08') { echo 'selected="selected"';}?>>Август</option>
					<option value="<?=date('Y').'-09';?>" <? if($_GET['cdate'] == date('Y').'-09') { echo 'selected="selected"';}?>>Сентябрь</option>
					<option value="<?=date('Y').'-10';?>" <? if($_GET['cdate'] == date('Y').'-10') { echo 'selected="selected"';}?>>Октябрь</option>
					<option value="<?=date('Y').'-11';?>" <? if($_GET['cdate'] == date('Y').'-11') { echo 'selected="selected"';}?>>Ноябрь</option>
					<option value="<?=date('Y').'-12';?>" <? if($_GET['cdate'] == date('Y').'-12') { echo 'selected="selected"';}?>>Декабрь</option>
			</select>

			<button type="submit" class="btn reject">Выбрать</button>
		</form>
	</div>
	<br>	<br>	<br>	<br>

 </div>





<script>
$(document).on('click','form.redirect button.btn.reject',function(e)
{
	var cdate = $('form.redirect option:selected').val();

	$('#myModalBox').modal({backdrop: 'static', keyboard: false});
	$(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
	document.location.href = "/bar_statistics/?cdate=" + cdate;

	e.preventDefault();
});


$(document).on('click','.save_form',function(e)
{
			var str = $( "form.tableform" ).serialize();

			$.ajax(
			{
				url: url + '/bar_statistics/',
				data: str,
				type: 'POST',
				beforeSend: function()
  			{
  				$('#myModalBox').modal({backdrop: 'static', keyboard: false});
  				$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
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

		e.preventDefault();
});


function getRandomInt(min, max)
{
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min)) + min;
}


$(document).on('click','.add_tr',function(e)
{
	  var random = getRandomInt(1000, 5000);

		var tr =
		'<tr class="cnt_'+random+'">' +
			'<td><input type="text" name="names[]->types_'+random+'" value="Название критерия"></td>' +
			'<td>' +
				'<input type="text" name="count[]->count_'+random+'" value="0">' +
				'<a data-id="'+random+'" href="#" class="glyphicon glyphicon-remove" data-toggle="tooltip" data-placement="right" title="Удалить критерий"></a>' +
			'</td>' +
		'</tr>';

		$('.price tbody.head').append(tr);

		e.preventDefault();
});



$(document).on('click','a.glyphicon',function(e)
{
		var id = $(this).attr('data-id');
		$('tr.cnt_' + id).remove();
		e.preventDefault();
});


</script>
