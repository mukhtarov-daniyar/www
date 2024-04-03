<style>

.btn-warning.exp { display: block; margin: 0 auto; margin-top: 20px; width: 200px;font-family: 'segoeui_sb';}
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
.id_product.show{ background: #F2BCBC !important}
.id_product:hover, .period:hover { background: #FFE5EB}
.count_real { text-align: center;}
.period  { display: inline-block; width: auto; text-align: center; border: 0; padding: 0; margin: 0; float: left;}
.modal-body .bootstrap-select.manager_ruk, .modal-body .bootstrap-select.manager_ruk_2  { width: 220px !important}
.modal-body .bootstrap-select.manager_ruk a, .modal-body .bootstrap-select.manager_ruk_2 a { color:#000}
.modal-body bottom.save_ruk{  background:#F84241; color: #fff; padding: 5px 15px; text-transform: uppercase; margin: 5px 0; display: inline-block; cursor: pointer;}
.select_ruk a { color: #0094FF !important; display: block;}



</style>


<div class="white">

		<?
		$sql = getSQLRowO("SELECT COUNT(id) FROM my_data_1c_card  WHERE user_id = '{$CFG->USER->USER_ID}' ");
		$count_card = $sql->{'COUNT(id)'};
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a  class="href_card" href="/speedometer/card_list">Корзина заказа</a>';
		?>

		 <a  class="refresh_1C" href="#">Обновить данные <img alt="" src="/tpl/img/new/icon/1c.png"></a>
		<!--a  class="standards" href="<?=request_url();?>&standards=1"><img alt="" src="/tpl/img/standards.png"> Нормативы</a!-->
		<!--a  class="standards" href="/speedometer/standards/"><? if($_COOKIE['standards'] == 1 || $_COOKIE['standards'] =='') {echo 'Отобразить товары с 0';}  else {echo 'Отобразить товары в наличии';} ?></a!-->
    <br clear="all">

		<style>
		.row.th {  color: #fff; line-height: 16px; background:#F84241; padding: 0; margin: 0;  font-family: 'segoeui_sb'; padding: 10px 0; font-size: 12px; z-index: 1001}
		.row.th a{ color: #fff}
		.row.th .sp{  padding: 0; margin: 0; vertical-align: bottom; padding: 3px; text-align: center;}
		.row.th .sp.right{ text-align: right;}

		.row.td { background:#fff; padding: 0; margin: 0;     font-family: 'segoeui'; font-size: 13px; border-bottom: 1px solid #ccc; padding: 3px 0;}
		.row.td:hover { background:#EAEAEA; cursor: pointer;}
		.row.td .sp{  padding: 0; margin: 0;  text-align: center; padding: 3px;}
		.row.td .sp.right{ text-align: right;}
		.row.td .sp.left{ text-align: left;}
		.row.th.fixed { position: fixed; top: 0}
		span.min { display: block; font-size:10px !important; text-decoration: underline;}
		.tooltip-inner {white-space:pre-wrap;}
		.glyphicon-plus.card { color: #707070; font-size: 12px;}
		</style>


		<div class="row th name">
			<div class="col-md-5 sp"><? echo $SPEED->getOrder('<br>Наименование', 'name'); ?></div>
			<div class="col-md-1 sp"><? echo $SPEED->getOrder('<br>Кол-во', 'counts'); ?><span class="min"><?=number_sum($l['row']['row']);?></span></div>
			<div class="col-md-1 sp"><br>Единица</div>
			<div class="col-md-1 sp right"><? echo $SPEED->getOrder('Цена розница', 'price*1'); ?></div>
			<div class="col-md-1 sp right"><? echo $SPEED->getOrder('Сумма розница', 'prices'); ?><span class="min"><?=number_sum($l['row']['price']);?></span></div>
			<div class="col-md-1 sp right"><? echo $SPEED->getOrder('Цена закуп.', 'total*1'); ?></div>
			<div class="col-md-1 sp right"><? echo $SPEED->getOrder('Сумма закуп.', 'totals'); ?><span class="min"><?=number_sum($l['row']['total']);?></span></div>
            <div class="col-md-1 sp psng_head" style="cursor: pointer" title="Продажи с начало текущего года"  data-toggle="tooltip" data-placement="bottom"><? echo $SPEED->getOrder('ПСНГ', 'sale_year'); ?> <span class="min"><?=number_sum($l['row']['sale_year']);?></span></div>
		</div>

		<?
			$cnt = 1;
			foreach($l['item'] as $res)
			{
				?>
					<div class="row td id_product product_<?=$res->id_product;?>" data-id="<?=$res->id_product;?>" data-vendor="<?=$res->vendor;?>">
						<div class="col-md-5 sp left"><a href="#" data-id="<?=request_url();?>&remove_position%5B%5D=<?=$res->id;?>" class="remove_position"><!--span class="glyphicon glyphicon-minus"></span!--></a> &nbsp; <?=$cnt;?>. <?=$res->name;?> <? echo $SPEED->getImg($res->id_product); ?> <img src="/tpl/img/new/save.png" data-vendor="<?=$res->vendor;?>" title="Артикул: <?=$res->vendor;?>"  data-toggle="tooltip" data-placement="right" data-original-title="Артикул: <?=$res->vendor;?>" class="vendor">

							&nbsp;&nbsp;&nbsp;

							<div class="input_id_product <?=$res->id_product;?>" data-id="<?=$res->id_product;?>">
								<i class="glyphicon glyphicon-plus card" data-id="<?=$res->id_product;?>"  title="Добавить в корзину заказа"  data-toggle="tooltip" data-placement="top"></i>
								<div class="arrow-5 arrow-5-left" id="<?=$res->id_product;?>"><input type="number" value="" placeholder="0" class="input_val_id_product <?=$res->id_product;?>"><button data-id="<?=$res->id_product;?>">ок</button></div>
							</div>

							&nbsp;&nbsp;&nbsp;

						</div>

						<div class="col-md-1 sp"><?=intval($res->counts*100)/100; ?></div>
						<div class="col-md-1 sp"><?=$res->type;?></div>
						<div class="col-md-1 sp right"><?=number_sum($res->price);?></div>
						<div class="col-md-1 sp right"><?=number_sum($res->prices);?></div>
						<div class="col-md-1 sp right"><?=number_sum($res->total);?></div>
						<div class="col-md-1 sp right"><?=number_sum($res->totals);?></div>

						<a class="sale_year" href="/speedometer/nomenclature_view/?search=<?=$res->name;?>&monthstart=<?=date('Y-01-01 H:i:s');?>&monthend=<?=date('Y-m-d H:i:s');?>" target="_blank"><div class="col-md-1 sp "><? echo number_sum($res->sale_year);?></div></a>
					</div>
				<? $cnt ++;
			}
		?>

		<div class="row td">
			<div class="col-md-5 sp left"><strong>Итого</strong></div>
			<div class="col-md-1 sp"><strong><?=number_sum($l['row']['row']);?></strong></div>
			<div class="col-md-1 sp"></div>
			<div class="col-md-1 sp right"></div>
			<div class="col-md-1 sp right"><strong><?=number_sum($l['row']['price']);?></strong></div>
			<div class="col-md-1 sp right"></div>
			<div class="col-md-1 sp right"><strong><?=number_sum($l['row']['total']);?></strong></div>
      <div class="col-md-1 sp"><strong><?=number_sum($l['row']['sale_year']);?></strong></div>
		</div>

		<a class="btn btn-warning exp more" href="#" data-id="0">Загрузить еще</a>

		  <div class="static" style="width:100%; display:block; text-align:center"> Всего <span><?=number_sum($l['row']['run']);?></span>	&nbsp; &nbsp; &nbsp;   Загружено  <span class="rebut"><?=number_sum($l['row']['runS']);?></span> </div>
		  <br clear="all"><br clear="all">
		  <a class="btn btn-warning exp" href="<?=request_url();?>&exel=1">Выгрузить в Эксель</a>
		  <br clear="all">
		  <br clear="all">
