<?

// Удалить одну запись
if($CFG->_GET_PARAMS[1] == 'del' && $CFG->_GET_PARAMS[2] > 0)
{
	$CFG->DB->query("DELETE FROM my_data_1c_card WHERE id = '{$CFG->_GET_PARAMS[2]}' ");
	exit;
}

// Удалить все записи
if($CFG->_GET_PARAMS[1] == 'del_list' )
{
	$CFG->DB->query("DELETE FROM my_data_1c_card WHERE user_id = '{$CFG->USER->USER_ID}' ");
	$CFG->STATUS->OK = true;
	$CFG->STATUS->MESSAGE = "Корзина очищена!";
	redirect($_SERVER['HTTP_REFERER']);
	exit;
}

// Удалить все записи
if($CFG->_GET_PARAMS[1] == 'exel' )
{
 if(count($l) > 0)
 {
	 set_time_limit(600);
	 $objPHPExcel = new PHPExcel();
	 $objPHPExcel->getProperties()->setCreator("forSign Kazakhstan");
	 $objPHPExcel->getProperties()->setLastModifiedBy("forSign Kazakhstan");
	 $objPHPExcel->getProperties()->setTitle("XLSX Document");
	 $objPHPExcel->getProperties()->setSubject("Export XLSX Document");
	 $objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F98383');
	 $objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->setWrapText(true);

	 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
	 $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);


	 $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Наименование');
	 $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Кол-во');

	 $cnt = 2;

	 foreach ($l as $value)
	 {

		 $objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", $value->name);
		 $objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", $value->vals); $countS[] = $value->vals;

		 $cnt ++;
	 }

	 $mul = $cnt+2;
	 $objPHPExcel->getActiveSheet()->SetCellValue("A$mul", 'Итого');
	 $objPHPExcel->getActiveSheet()->SetCellValue("B$mul", array_sum($countS));

	 $objPHPExcel->getActiveSheet()->setTitle('Корзина');
	 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	 $name = 'card_'.date("m_y").".xlsx";

	 ob_clean();

	# Output headers.
	header("Set-Cookie: fileDownload=true; path=/");
	header("Cache-Control: private");
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header("Content-Disposition: attachment; filename=".$name."");
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter->save('php://output');
	$objPHPExcel->disconnectWorksheets();

	die();


 }

	redirect($_SERVER['HTTP_REFERER']);
	exit;
}





?>


<h2><img alt="" src="/tpl/img/new/icon/10_red.png">Корзина</h2>
<br>

<div class="row tabs">
    <div class="obj">
        <h1 class="active"><a href="<?=sklad_act();?>">Наличный склад</a></h1>
    </div>

    <div class="obj">
        <h1><a href="/speedometer/nomenclature_view/?cdate=0">Все реализации</a></h1>
    </div>

		<? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85) {?>
    <div class="obj">
        <h1 ><a href="/speedometer/cashbox/">Кассы</a></h1>
    </div>
		<? } ?>

    <div class="obj">
        <h1><a href="/speedometer/analysis/<?=date('m');?>/wp/ASC">Анализ по номенклатуре</a></h1>
    </div>

    <!--div class="obj">
        <h1><a href="/speedometer/motivation/">Мотивации</a></h1>
    </div!-->

    <div class="obj">
        <h1><a href="/speedometer/gkgd/">GKGD</a></h1>
    </div>
</div>

<style type="text/css">
table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:12px; text-align:center; margin-bottom:20px; margin-top:10px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb'; cursor: pointer;}

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


<div class="white">

<br>




	<table class="price tsort_1">
		<thead>
	    <tr>
	      <th>Товар</th>
				<th>Артикул</th>
	      <th>Кол-во</th>
	      <th>Действие</th>
	    </tr>
			</thead>
			<tbody>
			<?


			if(count($l) > 0)
			{
				foreach($l as $res)
				{
					?>
					<tr>
						<td><?=$res->name; ?></td>
						<td><?=$res->vendor; ?></td>
						<td><?=$res->vals; $arr[] = $res->vals; ?></td>
						<td><a href="#" data-id="<?=$res->id;?>" class="del_card_id glyphicon glyphicon-trash"></a></td>
					</tr>
					<?
				}
			}
			?>
			</tbody>
			<tr>
				<td></td>
				<td></td>
				<td><strong><?=number_sum(array_sum($arr));?></strong></td>
				<td></td>
			</tr>
	  <table>

	<a class="btn btn-warning exp" href="/speedometer/card_list/del_list/">Очистить корзину</a>
	<a class="btn btn-warning exp" href="/speedometer/card_list/exel/">Выгрузить в Эксель</a>

</div>

<script>

$(document).on('click','.del_card_id',function(e)
{
		var id = $(this).attr('data-id');
		$.get( "/speedometer/card_list/del/"+ id);

		$('#myModalBox').modal({backdrop: 'static', keyboard: false});
		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Товар удален! Обновляем страницу...</center></h4></div>');
	   setTimeout(function() {window.location.reload();}, 1000);
		e.preventDefault();
});

$(function(){ $('[data-toggle="tooltip"]').tooltip(); });

(function($){
	$.fn.tsort=function(){
		var
			v=function(e,i){return $(e).children('td').eq(i).text()},
			c=function(i){return function(a,b){var k=v(a,i),m=v(b,i);return $.isNumeric(k)&&$.isNumeric(m)?k-m:k.localeCompare(m)}};
		this.each(function(){
			var
				th=$(this).children('thead').first().find('tr > th'),
				tb=$(this).children('tbody').first();

			th.click(function(){
				var r=tb.children('tr').toArray().sort(c($(this).index()));
				th.removeClass('sel'),$(this).addClass('sel').toggleClass('asc');
				if($(this).hasClass('asc'))r=r.reverse();
				for(var i=0;i<r.length;i++)tb.append(r[i])
			})
		})
	}
})(jQuery);

$( document ).ready(function()
{
	$('.tsort_1').tsort();
});
</script>
