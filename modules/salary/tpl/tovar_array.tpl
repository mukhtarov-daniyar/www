<h2><img alt="" src="/tpl/img/new/icon/grafic_red.png">Бюджетирование</h2>
<style>

a.refresh_1C { font-size:16px; display: inline-block; float:right; margin-top: 5px; margin-bottom: 10px;     font-family: 'segoeui_sb'; text-transform: uppercase;color: #000; text-align: right;}
a.refresh_1C img{ width: 30px; position: relative; top:-2px;vertical-align: middle; display: inline-block;}

a.standards { font-size:16px; display: inline-block; float:left; margin-top: 5px; margin-bottom: 10px;     font-family: 'segoeui_sb'; color: #000; text-align: right;}
a.standards img{ width: 30px; position: relative; top:-2px;vertical-align: middle; display: inline-block;}

table.price {  width:100%; margin:0 auto; margin-top:10px; border-collapse: collapse; font-size:12px; text-align: left; margin-bottom:20px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align: left;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}
.array_count { display: none}

table.price td.right { text-align: right;}
table.price th.center { text-align: center;}

table.price a { color: #fff}
table.price span.min { white-space: nowrap;display: block; font-size: 10px; }


form.aps { display:inline-block; float:left;}
#group { float:left; margin-bottom:10px; display:inline-block;}
.aps .search { padding:5px; position: relative; top:2px;}
.aps .send { padding:5px; position: relative; top:2px;}
.bootstrap-select { width: 200px !important;}
.id_product,.period { cursor: pointer;}
.id_product:hover, .period:hover { background: #FFE5EB}
.count_real { text-align: center;}
.period  { display: inline-block; width: auto; text-align: center; border: 0; padding: 0; margin: 0; float: left;}
.modal-body .bootstrap-select.manager_ruk  { width: 220px !important}
.modal-body .bootstrap-select.manager_ruk a { color:#000}
.modal-body bottom.save_ruk{  background:#F84241; color: #fff; padding: 5px 15px; text-transform: uppercase; margin: 5px 0; display: inline-block; cursor: pointer;}
.select_ruk a { color: #0094FF !important; display: block;}

	@media screen and (min-width:100px) and (max-width:768px)
	{

	.row.th {padding: 0 !important}

	.row.th.name div:nth-child(1) br{ display: none;}
	.row.th.name div:nth-child(2) br{ display: none;}

	.row.th.name div{ display: inline-block; }
	.row.th.name div:nth-child(1) { width: 40%; font-size: 12px; vertical-align: top; padding-top:10px;}
	.row.th.name div:nth-child(2) {width: 20%;  vertical-align: top; padding-top:10px;}
	.row.th.name div:nth-child(3) { display: none;}
	.row.th.name div:nth-child(4) { display: none;}
	.row.th.name div:nth-child(5) {width: 19%; }
	.row.th.name div:nth-child(6) { display: none;}
	.row.th.name div:nth-child(7) {width: 18%;}
	.row.th.name div:nth-child(8) { display: none;}
}

</style>

		<style>
		.row.th {  color: #fff; line-height: 16px; background:#F84241; padding: 0; margin: 0;  font-family: 'segoeui_sb'; padding: 10px 0; font-size: 12px; z-index: 1001}
		.row.th a{ color: #fff; text-decoration:underline}
		.row.th .sp{  padding: 0; margin: 0; vertical-align: bottom; padding: 3px; text-align: center;}
		.row.th .sp.right{ text-align: right;}
		.row.th span.ddt { display:block; font-size:10px}

		.row.td { background:#fff; padding: 0; margin: 0;     font-family: 'segoeui'; font-size: 13px; border-bottom: 1px solid #ccc; padding: 10px 0;}
		.row.td:hover { background:#EAEAEA; cursor: pointer;}
		.row.td .sp{  padding: 0; margin: 0;  overflow: hidden; text-align: center; padding: 3px;}
		.row.td .sp.right{ text-align: right;}
		.row.td .sp.left{ text-align: left;}

		.row.td img{ width:60px; padding:0 10px; border-radius:50%;}

		.row.th.fixed { position: fixed; top: 0}
		span.min { display: block; font-size:10px !important; text-decoration: underline;}
		.tooltip-inner {
    white-space:pre-wrap;
}
		</style>

<?

if($CFG->_GET_PARAMS[1] == "" || $CFG->_GET_PARAMS[1] == "ASC" )
{
	$link_type = 'DESC';
}
else
{
	$link_type = 'ASC';
}
?>


<div class="white">

		<br><br>
		<div class="row th name">
			<div class="col-md-4 sp"><a href="/salary/name/<?=$link_type;?>">Работник <? if($CFG->_GET_PARAMS[0] =='name') { if($CFG->_GET_PARAMS[1] == 'ASC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes"></span>';} if($CFG->_GET_PARAMS[1] == 'DESC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';} }?></a></div>
			<div class="col-md-3 sp"><a href="/salary/position/<?=$link_type;?>">Должность <? if($CFG->_GET_PARAMS[0] =='position') { if($CFG->_GET_PARAMS[1] == 'ASC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes"></span>';} if($CFG->_GET_PARAMS[1] == 'DESC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';} }?></a></div>
			<div class="col-md-1 sp right"><a href="/salary/salary_total/<?=$link_type;?>">Фактический оклад <? if($CFG->_GET_PARAMS[0] =='salary_total') { if($CFG->_GET_PARAMS[1] == 'ASC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes"></span>';} if($CFG->_GET_PARAMS[1] == 'DESC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';} }?></a><span class="ddt salary_total"></span></div>
			<div class="col-md-2 sp right"><a href="/salary/salary_bu/<?=$link_type;?>">Оклад по БУ <? if($CFG->_GET_PARAMS[0] =='salary_bu') { if($CFG->_GET_PARAMS[1] == 'ASC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes"></span>';} if($CFG->_GET_PARAMS[1] == 'DESC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';} }?></a><span class="ddt salary_bu"></span></div>
            <div class="col-md-2 sp right"><a href="/salary/salary_total-salary_bu/<?=$link_type;?>">Оклад по УУ <? if($CFG->_GET_PARAMS[0] =='salary_total-salary_bu') { if($CFG->_GET_PARAMS[1] == 'ASC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes"></span>';} if($CFG->_GET_PARAMS[1] == 'DESC'){ echo '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';} }?></a><span class="ddt salary_total-salary_bu"></span></div>
		</div>

		<?
        	//print_r($l);

			$cnt = 1;
			foreach($l as $res)
			{
            	if($res->salary_total == 0) continue;
            	?>
                    <div class="row td name">
                        <div class="col-md-4 sp left"><strong><?=$cnt;?>. <a href="/profile/view/<?=$res->id;?>"><img src="<?=$res->avatar;?>"><?=$res->name;?></a></strong></div>
                        <div class="col-md-3 sp left"><strong><?=$res->position;?></strong></div>
                        <div class="col-md-1 sp right"><strong><?=number_sum($res->salary_total); $total[] = $res->salary_total; ?></strong></div>
                        <div class="col-md-2 sp right"><strong><?=number_sum($res->salary_bu);  $bu[] = $res->salary_bu; ?></strong></div>
                        <div class="col-md-2 sp right"><strong><?=number_sum($res->salary_total-$res->salary_bu);  $uu[] = $res->salary_total-$res->salary_bu; ?></strong></div>
                    </div>
				<? $cnt++;

            }
         ?>

		<div class="row td">
			<div class="col-md-4 sp"></div>
			<div class="col-md-3 sp right"><strong>Итого</strong></div>
			<div class="col-md-1 sp right"><strong class="salary_total_sum"><? echo number_sum(array_sum($total));?></strong></div>
			<div class="col-md-2 sp right"><strong class="salary_bu_sum"><? echo number_sum(array_sum($bu));?></strong></div>
            <div class="col-md-2 sp right"><strong class="salary_total-salary_bu_sum"><? echo number_sum(array_sum($uu));?></strong></div>
		</div>
</div>

<script>
	$('.salary_total').html($('.salary_total_sum').html());
	$('.salary_bu').html($('.salary_bu_sum').html());
	$('.salary_total-salary_bu').html($('.salary_total-salary_bu_sum').html());
</script>
