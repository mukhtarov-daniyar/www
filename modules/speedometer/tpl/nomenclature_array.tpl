<style>
table.price {  width:100%; margin:0 auto; margin-top:10px; border-collapse: collapse; font-size:12px; text-align: left; margin-bottom:20px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align: left;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}


table.price td.right { text-align: right;}
table.price th.center { text-align: center;}

table.price a { color: #fff}
table.price span.min { white-space: nowrap;display: block; font-size: 10px; }

a.refresh_1C { font-size:16px; display: inline-block; float:right; margin-top: 5px; margin-bottom: 10px;     font-family: 'segoeui_sb'; text-transform: uppercase;color: #000; text-align: right;}

a.refresh_1C img{ width: 30px; position: relative; top:-2px;vertical-align: middle; display: inline-block;}
form.aps { display:inline-block; float:left;}
#group { float:left; margin-bottom:10px; display:inline-block;}
.aps .search { padding:5px; position: relative; top:2px;}
.aps .send { padding:5px; position: relative; top:2px;}
.bootstrap-select { width: 200px !important;}
.id_product,.period { cursor: pointer;}
.id_product:hover, .period:hover { background: #FFE5EB}
.count_real { text-align: center;}
.period  { display: inline-block; width: auto; text-align: center; border: 0; padding: 0; margin: 0; float: left;}
</style>



<div class="white">


	  <a  class="refresh_1C" href="/speedometer/nomenclature_view/nomenclature/">Обновить данные <img alt="" src="/tpl/img/new/icon/1c.png"></a>
    <br clear="all">

    <table class="price">
      <thead class="thead">
          <tr>
            <th style="width:23%;" class="center"><? echo $SPEED->getOrder('Наименование', 'name', 'nomenclature_orders'); ?></th>
            <th style="width:7%;" class="center"><? echo $SPEED->getOrder('Кол-во', 'count*1', 'nomenclature_orders'); ?></th>
						<th style="width:5%;" class="center"><? echo $SPEED->getOrder('Время', 'cdate', 'nomenclature_orders'); ?></th>
            <th style="width:10%;" class="center"><? echo $SPEED->getOrder('Контрагент', 'buyer', 'nomenclature_orders'); ?></th>
						<th style="width:10%;" class="center">Кто продал</th>
						<th style="width:10%;" class="center">Склад</th>
            <th style="width:8%;" class="center sum_zakup_head"><? echo $SPEED->getOrder('Сумма', '(price*1)*(count*1)', 'nomenclature_orders'); ?></th>
						<th style="width:9%;" class="center sum_purchase_head_uu"><? echo $SPEED->getOrder('ВП. УУ', '(price*count)-(purchase*count)', 'nomenclature_orders'); ?></th>
						<th style="width:9%;" class="center sum_purchase_head_bu"><? echo $SPEED->getOrder('ВП. БУ', '((price*count)-(purchase*count)', 'nomenclature_orders'); ?></th>
          </tr>
      </thead>
    <?
				$cnt = 1;
        foreach($l as $res)
        {
						$sebes = ($res->price*1)*($res->count*1);
						$purchase = ($res->purchase*1)*($res->count*1);

/*
 if($res->returns == 1)
 {
	 	print_r($sebes.' '.$purchase); echo '<br><br>';

 }
*/
            ?>
         <tbody>
          <tr <? if($res->returns == 1) { echo ' class="bg-warning" ';}?>>
            <td style="width:23%;" data-id="<?=$res->id_product;?>" class="id_product">
							<?=$cnt;?>. <?=$res->name;?>
							<? if($res->IM_P == 1){ ?><span style="background: #F84241; font-family: 'segoeui'; padding-bottom:3px;padding-top:0px; font-weight:100" class="badge">им</span> <? }?>
							<? if($res->nds*1 > 0 || $res->nds*1 < 0){ ?><span style="background: #F84241; font-family: 'segoeui'; padding-bottom:3px;padding-top:0px; font-weight:100" class="badge">бу</span> <? }?>
						</td>
            <td style="width:7%;" class="right"><? $count[] = $res->count; echo intval($res->count*100)/100; ?></td>
            <td style="width:5%; text-align:center"><? echo dateSQL2TEXT($res->cdate, "DD.MM.YY");?><br><? echo dateSQL2TEXT($res->cdate, "hh:mm");?> </td>
						<td style="width:10%;"><?=$SPEED->buyer($res->buyer);?></td>
						<td style="width:10%;"><?=SelectDataName('users', 'name', $res->user_id);?></td>
            <td style="width:10%;"><?=SelectDataName('data_1c_warehouse', 'name', $res->warehouse_id);?></td>
            <td style="width:8%;" class="right"><?=number_sum($sebes); $sum[] = $sebes;?></td>
						<td style="width:9%;" class="right"><? if($res->nds*1 == 0){ ?><? $dochod_uu [] = $sebes-$purchase;	echo number_sum($sebes-$purchase);?> <? }?></td>

						<td style="width:9%;" class="right"><? if($res->nds*1 > 0) { echo number_sum($sebes-$purchase);  $dochod_bu [] = $sebes-$purchase; } ?></td>
          </tr>
          </tbody>
            <? $cnt ++;
        }
    ?>
		<tbody>
		 <tr>
			 <td>Итого</td>
			 <td class="right"><?=number_sum(array_sum($count));?></td>
			 <td class="right"></td>
			 <td class="right"></td>
			 <td class="right"></td>
			 <td class="right"></td>
			 <td class="right sum_zakup"><?=number_sum(array_sum($sum));?></td>
			 <td class="right sum_purchase_uu"><?=number_sum(array_sum($dochod_uu));?></td>
			 <td class="right sum_purchase_bu"><?=number_sum(array_sum($dochod_bu));?></td>
		 </tr>
		 </tbody>

    </table>
</div>


<style>

.thead.fixed { position: fixed; top: 0}
</style>
<script>


$(document).on('click','.id_product',function(e)
{
  var id = $(this).attr('data-id');
  var text = $(this).text();
	$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
  $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><center><h4 class="modal-title">Информация о товаре</h4></center></div>');
  $(".modal-body").append('<p><strong>Идентификатор товара:</strong> '+id+'<p>');

  $.ajax
  		({
  			url: "/static/data_1C_id_2/",
  			type: "POST",
  			data: {"id": id},
        async:false,
  			cache: true,
  			success: function(response)
  			{
           $(".modal-body").append('<p>'+response+'<p>');
           isotope();
  			}

  		});



      $('.period').datepicker({
       dateFormat: 'dd.mm.yy',
          onSelect: function (date)
          {
            oneC_ostatok(id, date);
          }
      })


      var datas = $(".period").val();
      oneC_ostatok(id, datas);

});

function oneC_ostatok(id, datas)
{

  console.log(id);
  console.log(datas);
    $.ajax
    ({
      url: "/static/data_1C_ostatok/",
      type: "POST",
      async:false,
      data: {"id": id,"datas": datas},
      cache: true,
      success: function(response)
      {
          $(".period_num").html(response);

          var count_real =   $(".count_real").html();

          var x = (response-count_real)/response;

          if(x == '-Infinity')
          {
            $(".coefficient").html('<span style="color:#F00">В этот период не было товара</span>');
          }
          else
          {
            $(".coefficient").html(x.toFixed(3));
          }

          isotope();
      }
    });
}



$(document).ready(function(e)
{
	$(".sum_zakup_head").append('<span class="min">'+$( ".sum_zakup" ).html()+'</span>');
	$(".sum_purchase_head_uu").append('<span class="min">'+$( ".sum_purchase_uu" ).html()+'</span>');
	$(".sum_purchase_head_bu").append('<span class="min">'+$( ".sum_purchase_bu" ).html()+'</span>');

	$(document).on('scroll', function(e)
	{
		if( $(document).scrollTop() > 380 )
		{
					$( ".thead" ).addClass( "fixed" );
					var wdt = $('table.price').width();
					$( ".thead" ).width(wdt+2);
		}
		else
		{
				$( ".thead" ).removeClass( "fixed" );
		}
	});
});


</script>
