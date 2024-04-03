<h2><img alt="" src="/tpl/img/new/icon/auto_r.png">Авто в базе (<?=$cnt;?>)</h2>

<? 		include("./modules/edrive/tpl/filter.tpl"); ?>

<div class="white">
  <style>
  table.table.person_table tbody tr:hover{ background:#EFEFEF; cursor: pointer;}
  table.table.person_table img{ width: 54px; height: 54px; border-radius: 50%;}
  table.table.person_table th, table.table.person_table td{ text-align: center; vertical-align: middle; padding:2px 5px;}
  table.table.person_table th{ font-weight: 100;     font-size: 15px;font-family: 'segoeui_sb';}
  table.table.person_table td span{ display: block; color: #999999; }
  table.table.person_table td a{  color: #F8403E; text-decoration: none;}
  </style>

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

  <table class="table price tsort_1">
    <thead>
      <tr>
        <th scope="col">№</th>
        <th scope="col">Автомобиль</th>
        <th scope="col">Город</th>
        <th scope="col">Скоростной порт</th>
        <th scope="col">Владелец</th>
      </tr>
    </thead>
    <tbody>
      <?
       for ($i=0; $i<sizeof($l); $i++)
       {
         $car_id = $l[$i]->car_id;
         $sql = getSQLRowO("SELECT * FROM my_edrive_car WHERE id = '{$car_id}' ORDER BY id DESC ");
         $data = getSQLRowO("SELECT * FROM my_edrive_car_model WHERE id = '{$sql->model_id}' ORDER BY id DESC ");
         ?>
         <tr>
           <td scope="col"><?=$i+1;?></td>
           <td scope="col"><?=$data->name;?> <?=$l[$i]->auto;?></td>
           <td scope="col"><?=$l[$i]->city;?></td>
           <td scope="col"><?=$l[$i]->port;?></td>
           <td scope="col">

             <div class="row">
               <div class="col-md-8"><a href="/person/<?=$l[$i]->client_id;?>" target="_blank"><?=$l[$i]->name;?> <?  $pieces = explode(" ", $l[$i]->fio);  echo $pieces[1]; ?></a></div>
               <div class="col-md-4"><a href="/edrive/edit/<?=$l[$i]->id;?>"><i title="Редактировать эту запись?" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon-pencil" data-id="<?=$l[$i]->id;?>" ></i></a>

                 <div class="col-md-4"><a href="/edrive/add/dell/<?=$l[$i]->id;?>"><i title="Удалить эту запись?" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon-trash" data-id="<?=$l[$i]->id;?>" ></i></a>

</div>
             </div>



           </td>
         </tr>
         <?
       }
       ?>
    </tbody>
  </table>

  <? if( $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1) {?>
  <div class="static" style="width:100%; display:block; text-align:center">
    <br><a class="btn btn-warning exp" href="<?=request_url();?>&exel=1">Выгрузить в Эксель</a>
  </div>
  <? } ?>

</div>





<script>


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


  <script>
  $(document).on('click','table.table.person_table tbody tr',function(e)
  {
     e.preventDefault();
  });

  </script>
