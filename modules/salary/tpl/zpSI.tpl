<h2><img alt="" src="/tpl/img/new/icon/grafic_red.png">Финасововый анализ</h2>
<br>
<div class="row tabs">
	<div class="obj">
		<h1 class="active"><a href="/salary/?month=<?=date("m", strtotime("-1 month"))*1;?>&year=<?=date('Y')?>">Финасововый анализ</a></h1>
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
		<h1><a href="/bar_statistics/aluminum_rim/?year=<?=date('Y')?>&month=<?=date('m')?>">Алматы РиМ</a></h1>
	</div>

	<div class="obj">
		<h1><a href="/bar_statistics/pavlodar_asem/?year=<?=date('Y')?>&month=<?=date('m')?>">Павлодар Асем</a></h1>
	</div>

</div>

<div class="white">
<div class="row">

	<div class="col-md-12">
		<form method="get">
			Выберите месяц:
			<select name="month" class="selectpicker show-tick">
					<option value="1" <? if($_GET['month'] == 1) { echo 'selected="selected"';}?>>Январь</option>
					<option value="2" <? if($_GET['month'] == 2) { echo 'selected="selected"';}?>>Февраль</option>
					<option value="3" <? if($_GET['month'] == 3) { echo 'selected="selected"';}?>>Март</option>
					<option value="4" <? if($_GET['month'] == 4) { echo 'selected="selected"';}?>>Апрель</option>
					<option value="5" <? if($_GET['month'] == 5) { echo 'selected="selected"';}?>>Май</option>
					<option value="6" <? if($_GET['month'] == 6) { echo 'selected="selected"';}?>>Июнь</option>
					<option value="7" <? if($_GET['month'] == 7) { echo 'selected="selected"';}?>>Июль</option>
					<option value="8" <? if($_GET['month'] == 8) { echo 'selected="selected"';}?>>Август</option>
					<option value="9" <? if($_GET['month'] == 9) { echo 'selected="selected"';}?>>Сентябрь</option>
					<option value="10" <? if($_GET['month'] ==10) { echo 'selected="selected"';}?>>Октябрь</option>
					<option value="11" <? if($_GET['month'] == 11) { echo 'selected="selected"';}?>>Ноябрь</option>
					<option value="12" <? if($_GET['month'] == 12) { echo 'selected="selected"';}?>>Декабрь</option>
		  </select>

			&nbsp; &nbsp; &nbsp; год:
			<select name="year" class="selectpicker show-tick">
			<?
			for($y=2015;$y<=date('Y');$y++)
			{
				?>
					<option value="<?=$y;?>" <? if($_GET['year'] == $y) { echo 'selected="selected"';} elseif (!isset($_GET['year']) && $y == date('Y')) { echo 'selected="selected"';} ?>><?=$y;?></option>
				<?
			}
			?>
		  </select>

			<button class="btn">Выбрать</button>
		</form>
	</div>

<style>
	table.price .yellow { background:#F9FFB2}
	table.price .blue { background:#E2F1FF}
	table.price .green { background:#F2FFF5}
</style>


	<div class="col-md-12">
		<table class="price tsort_1">
			<thead>
		    <tr>
					<th>№</th>
					<th>ФИО</th>

					<th>Оклад БУ+УУ</th>
		      <th>Оклад БУ</th>
					<th>Взносы<br>ОСМС</th>
					<th>ИПН</th>
					<th>ОПВ</th>
					<th>Аванс БУ<br>касса</th>
					<th>Аванс БУ<br>прочее</th>
					<th>К опате БУ</th>

		      <th>Оклад УУ</th>
					<th>Аванс УУ</th>
					<th>К опате УУ</th>

					<th>Отработанно<br>часов</th>

		    </tr>
				</thead>
				<tbody>
				<?
					$cnt = 1;
					foreach($l as $res)
					{

						if($res['Конечное_сальдо'] == 0) continue;

						?>
						<tr>
							<th><?=$cnt;?></th>
							<th><?=$SALARY->getLast($res['ФизЛицо']);?></th>
							<td><?=number_sum($SALARY->getLastZP($SALARY->getLast($res['ФизЛицо']), 'salary_total'));  $ZP[] = $SALARY->getLastZP($SALARY->getLast($res['ФизЛицо']), 'salary_total');?></td>
							<td class="yellow"><strong><?=number_sum($res['ОкладТариф']); $ZP_BU[] = $res['ОкладТариф'];?></strong></td>
							<td class="yellow"><?=number_sum($res['Взносы_ОСМС']); $osms[] = $res['Взносы_ОСМС'];?></td>
							<td class="yellow"><?=number_sum($res['ИПН']); $ipn[] = $res['ИПН']; ?></td>
							<td class="yellow"><?=number_sum($res['Вычет_ОПВ']); $opv[] = $res['Вычет_ОПВ'];?></td>
							<td class="yellow"><?=number_sum($res['Выплачено_из_кассы_БУХ_авансы_выданные']);?></td>

							<!-- Аванс прочее БУ !-->
							<td class="yellow">
								<?
										echo number_sum($res['ОкладТариф']+$res['Компенсация_отпуска']-($res['Взносы_ОСМС']+$res['ИПН']+$res['Вычет_ОПВ']+$res['Конечное_сальдо']));
											$a_k[] = $res['ОкладТариф']+$res['Компенсация_отпуска']-($res['Взносы_ОСМС']+$res['ИПН']+$res['Вычет_ОПВ']+$res['Конечное_сальдо']);

								?>
							</td>

							<td class="yellow"><?=number_sum($res['Конечное_сальдо']); $BN[] = $res['Конечное_сальдо']; ?></td>

							<td class="blue"><?=number_sum($SALARY->getLastZP($SALARY->getLast($res['ФизЛицо']), 'salary_uu')); $ZP_UU[] = $SALARY->getLastZP($SALARY->getLast($res['ФизЛицо']), 'salary_uu');?></td>

							<!-- Аванс УУ !-->
							<td class="blue"><?=number_sum($res['Выплачено_из_кассы_УПР_авансы_выданные']); $avans_uu[] = $res['Выплачено_из_кассы_УПР_авансы_выданные'];?></td>

							<!-- К оплате УУ !-->
							<td class="blue">
								<?


								echo number_sum(
					$SALARY->getLastZP_MINUS_UU($SALARY->getLast($res['ФизЛицо']), $SALARY->getLastZP($SALARY->getLast($res['ФизЛицо']), 'salary_uu') - $res['Выплачено_из_кассы_УПР_авансы_выданные'], $_GET['month'], $_GET['year'] )
																);



								$riliS_uu[] = $rili_uu;	?></td>

							<td class="green"><?=$res['Отработано_дней']*8;?></td>

						</tr>
						<?
						$cnt++;
					}
					?>
			</tbody>
				<tr>
					<td></td>
					<td><strong>Всего</strong></td>
					<td></td>
					<td><?=number_sum(array_sum($ZP_BU));?></td>
					<td><?=number_sum(array_sum($osms));?></td>
					<td><?=number_sum(array_sum($ipn));?></td>
					<td><?=number_sum(array_sum($opv));?></td>
					<td></td>
					<td><?=number_sum(array_sum($a_k));?></td>
					<td></td>
					<td><?=number_sum(array_sum($ZP_UU));?></td>
					<td><?=number_sum(array_sum($avans_uu));?></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<td></td>
					<td><strong>Итого</strong></td>
					<td><strong><?=number_sum(array_sum($ZP));?></strong></td>
					<td></td>
					<td colspan="3"><strong><?=number_sum(array_sum($osms)+array_sum($ipn)+array_sum($opv));?></strong></td>
					<td></td>
					<td></td>
					<td><strong><?=number_sum(array_sum($BN));?></strong></td>
					<td></td>
					<td></td>
					<td><strong><?=number_sum(array_sum($riliS_uu));?></strong></td>
					<td>Часов <sup><small>5</small></sup> <?=$l[0]['ДнейПоПятидневке']*8;?></td>
				</tr>

		  </table>
	</div>
</div>
</div>

<style type="text/css">
table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:11px; text-align:center; margin-bottom:20px; margin-top:10px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top;  color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; padding:0; margin:0;  padding:5px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb'; cursor: pointer;}

.tsort_1 > thead > tr > th.sel,
.tsort_2 > thead > tr > th.sel,
.tsort_3 > thead > tr > th.sel,
.tsort_4 > thead > tr > th.sel {background: #F77171  url('https://lh3.googleusercontent.com/JOVAJ_X2eG14J9wpYCCNtovtYngk_9_QK7ZI8mYRi2OaQxYN35m0DpABI_w9UeDIRiYfu2trp8TBV82_pl8pvq5I7dtUU4WTT0To1yIhI5_wMo5c3_fsYwGOmrUDZvkggMEiS6g2p4g_pmCGPIACf1M0wwPZTGFnuJ6IyGyoUfr4MqabG_8gcuAelg7dxMML3f-qhrj6TpbvFEOk4S6YjXCBNCEVzmBtnf6Zsbh3iBtxYY9iYM7DoGQ0l6y2RYyMlBnE38flZNZnbEI3yBcrQ_oeTcNLNeX_OXQDlIaQpIs24woeFDo3dUNqoP8rAIjShaLME_o0sVzE3X3YfHWrIbaP5yRxkkY6Y8iKByZzaqMuiGhxqn0xfXKw_xQWBuRwkhyg9TB8FUe02rdQAtDlfCZDhZvlPFzuQZfo9LGwu1YuDC9dCnifwM0eWvltJQkn7DKlodkJdIiDl1DvfxrRQdgB8BThSSt6Ll2Hv_keE0CbHn6MLIP8E6i7ZbrH6dTWeFyaBbI2GfrGvHBS9je1T8xeV-hwP9SGO2U9XdDkXA=w7-h5-no') 50% 0 no-repeat;
}
.tsort_1 > thead > tr > th.sel.asc,
.tsort_2 > thead > tr > th.sel.asc,
.tsort_3 > thead > tr > th.sel.asc,
.tsort_4 > thead > tr > th.sel.asc {background: #F77171 url('https://lh3.googleusercontent.com/SriDaBGVSHwn0jr8-o-oU5kIC3kg2IKg4nXxmo0JWG7IJnGjWFqlnhUJ3BmVkGGrGyRxTw2XYQ4DkgkbwRhhnZBRT_lb1Ka-TQqQOZlAc7STBckQi7SxsKw9CLmXut_Jva90oCYjE2XjFGDFCxOucoVrwrILXr45v3Pg4Z546dFhykZKVmU5CwItvVSPSQAWx7Py2tVrFlL04uC90jZMJIFp0SmZgmE7vJjJQglLC3YxHMFQDVST2JEkTOeXUTovcHlsgRTeB1ltAOFbe3ZokL01u004JBjEYTvPpA1-5mJhrbfadGtLpvpDg2TEYItps1Jber8P_rAb_kmBYGHXjXacoA_gpbA6QsNOEj4lJ_QBdG8OdnWdGTbrfkZJW8j_m7bPF8Te6W0UPBT5_L0OrEVFnxtLTqPOwDthogMNDlu3rOuwe0QGgjIQVpG6YEHWPNH4A93uP5k6LjpxEbmvopEpp07lN_2FlOp_bsWA70t_PAttx5AJ9rR7kqrHfOxWUJQEenRTQp73oIIfnQ8xAfhK9RoNXKhrbipJEA=w7-h5-no') no-repeat;background-position:50% 100%; }
.btn-warning.exp {  display: block;  margin: 0 auto;  margin-top: 20px;  width: 200px;  font-family: 'segoeui_sb';}
</style>
