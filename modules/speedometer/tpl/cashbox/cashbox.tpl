<?


if(isset($_GET['cdate']))
{
	$cdate = $_GET['cdate'];
}
else
{
	$cdate = date('d.m.Y');
}

$l = $SPEED->Сashbox($cdate);

?>


<h2><img alt="" src="/tpl/img/new/icon/10_red.png">Касса</h2>
<br>

<div class="row tabs">
    <div class="obj">
        <h1><a href="<?=sklad_act();?>">Наличный склад</a></h1>
    </div>

    <div class="obj">
        <h1><a href="/speedometer/nomenclature_view/?cdate=0">Все реализации</a></h1>
    </div>

    <? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 926 || $CFG->USER->USER_ID == 565) {?>
    <div class="obj">
        <h1 class="active"><a href="/speedometer/cashbox/">Кассы</a></h1>
    </div>
		<? } ?>

    <div class="obj">
        <h1><a href="/speedometer/analysis/<?=date('Y');?>/<?=date('m');?>/wp/ASC">Анализ по  номенклатуре</a></h1>
    </div>

    <!--div class="obj">
        <h1><a href="/speedometer/motivation/">Мотивации</a></h1>
    </div!-->

		<!--div class="obj">
        <h1><a href="/speedometer/gkgd/">GKGD</a></h1>
    </div!-->


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
<form method="get">Выберите дату: <input type="text" name="cdate" value="<?=$cdate;?>" id="cashbox"> <button>ок</button></form>


	<table class="price tsort_1">
		<thead>
	    <tr>
	      <th>Название</th>
				<th>Тип</th>
				<th>Валюта</th>
	      <th>Сумма Остаток</th>
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
						<td class="customer"><?=$res['БанковскийСчетКасса'];?></a></td>
						<td><?=$res['ВидДенежныхСредств'];?></td>
						<td><?=$res['Валюта'];?></td>
						<td><?=number_sum($res['СуммаОстаток']); $arrB[] = (int)$res['СуммаОстаток'];?></td>
					</tr>
					<?
				}
			}
			?>
			</tbody>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td><strong><?=number_sum(array_sum($arrB));?></strong></td>
			</tr>
	  <table>
</div>


<style>
.customer.reds {background: #FAFF9E; webkit-animation: rainbow 2s linear 2s infinite;animation: rainbow 2s linear 2s infinite;}
@-webkit-keyframes rainbow {	0% {background: #FAFF9E;}	50% {background: #fff}	100% {background: #FAFF9E;}}
@keyframes rainbow {	0% {background: #FAFF9E;}	50% {background: #fff}	100% {background: #FAFF9E;} }
</style>


<script>

var searchValue = "!!!";
$("td.customer").each(function()
{
	console.log($(this).html());

  if($(this).html().indexOf(searchValue) > -1)
  {
    $(this).addClass('reds');
  }
});


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
			c=function(i){return function(a,b)
				{
				var k = v(a,i).replace(/[ ]/g, ""), m = v(b,i).replace(/[ ]/g, "");
				return $.isNumeric(k)&&$.isNumeric(m)?k-m:k.localeCompare(m)}
			};
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
