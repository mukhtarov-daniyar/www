<style>
table.price {  width:100%; margin:0 auto; margin-top:10px; border-collapse: collapse; font-size:12px; text-align: left; margin-bottom:20px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align: left;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}


table.price td.right { text-align: right;}
table.price th.center { text-align: center;}

table.price a.sort { color: #fff}
table.price span.min { white-space: nowrap;display: block; font-size: 10px; }

a.refresh_1C { font-size:16px; display: inline-block; float:right; margin-top: 5px; margin-bottom: 10px;     font-family: 'segoeui_sb'; text-transform: uppercase;color: #000; text-align: right;}

a.refresh_1C img{ width: 30px; position: relative; top:-2px;vertical-align: middle; display: inline-block;}
form.aps { display:inline-block; float:left;}
#group { float:left; margin-bottom:10px; display:inline-block;}
.aps .search { padding:5px; position: relative; top:2px;}
.aps .send { padding:5px; position: relative; top:2px;}
.bootstrap-select { width: 200px !important;}
</style>

<h2><img alt="" src="/tpl/img/new/icon/10_red.png"> Список товаров</h2>

<div class="white">
<form action="/speedometer/tovar/" method="get" class="aps">
  <select name="group" class="selectpicker show-tick" id="group">
      <option value="0" <? if($_GET['group'] <= 0) {echo 'selected'; }?>>Выберите группу товара</option>
      <option value="1" <? if($_GET['group'] == 1) {echo 'selected'; }?>>1. МАТЕРИАЛЫ ДЛЯ РЕКЛАМЫ</option>
      <option value="2" <? if($_GET['group'] == 2) {echo 'selected'; }?>>2. СТАНКИ И ИНСТРУМЕНТЫ ДЛЯ РЕКЛАМЫ</option>
      <option value="3" <? if($_GET['group'] == 3) {echo 'selected'; }?>>3. ОБОРУДОВАНИЕ ШОУСЦЕНИЧЕСКОЕ</option>
      <option value="4" <? if($_GET['group'] == 4) {echo 'selected'; }?>>4. ОБОРУДОВАНИЕ СВЕТОТЕХНИЧЕСКОЕ</option>
      <option value="5" <? if($_GET['group'] == 5) {echo 'selected'; }?>>5. Комплектующие</option>
      <option value="6" <? if($_GET['group'] == 6) {echo 'selected'; }?>>6. Стройматериалы</option>
  </select>

  <!--select name="warehouse_text" class="selectpicker show-tick" id="group">
      <option value="0" <? if($_GET['warehouse_text'] <= 0) {echo 'selected'; }?>>Выберите склад</option>
      <option value="1" <? if($_GET['warehouse_text'] == 1) {echo 'selected'; }?>>1. Магазин Чикаго</option>
      <option value="2" <? if($_GET['warehouse_text'] == 2) {echo 'selected'; }?>>2. Магазин Тамаша</option>
      <option value="3" <? if($_GET['warehouse_text'] == 3) {echo 'selected'; }?>>3. Офис</option>
      <option value="4" <? if($_GET['warehouse_text'] == 4) {echo 'selected'; }?>>4. Склад Азамата - образцы</option>
      <option value="5" <? if($_GET['warehouse_text'] == 5) {echo 'selected'; }?>>5. Склад брака</option>
      <option value="6" <? if($_GET['warehouse_text'] == 6) {echo 'selected'; }?>>6. Склад Динамичный</option>
      <option value="2" <? if($_GET['warehouse_text'] == 7) {echo 'selected'; }?>>7. Склад Казань *25803</option>
      <option value="3" <? if($_GET['warehouse_text'] == 8) {echo 'selected'; }?>>8. Склад Мебельщик Астана</option>
      <option value="4" <? if($_GET['warehouse_text'] == 9) {echo 'selected'; }?>>9. Склад Самосбор</option>
      <option value="5" <? if($_GET['warehouse_text'] == 10) {echo 'selected'; }?>>10. Склад Светотехники </option>
      <option value="6" <? if($_GET['warehouse_text'] == 11) {echo 'selected'; }?>>11. Собственный прокат</option>
      <option value="6" <? if($_GET['warehouse_text'] == 12) {echo 'selected'; }?>>12. Стеклянные колбы</option>
      <option value="6" <? if($_GET['warehouse_text'] == 13) {echo 'selected'; }?>>13. Шоу и сценического оборудрования</option>
  </select!-->

	<input value="<?=$_GET['search'];?>" placeholder="" class="search" name="search" />
    <button class="send">поиск</button>
</form>


	  <a  class="refresh_1C" href="#">Обновить данные <img alt="" src="/tpl/img/new/icon/1c.png"></a>
    <br clear="all">
    <?

      if(isset($_GET['search']))
      {
          $sql = " AND name LIKE '%{$_GET[search]}%' ";
      }

      if(isset($_GET['group']) && $_GET['group'] != 0)
      {
      	$group_num = " AND group_num = ".$_GET[group];
      }
      elseif($_GET['group'] == 0 || $_GET['group'] == '')
      {
      	$group_num = " AND group_num IN (1,2,3,4,5)	";
    	}

			$sql = getSQLArrayO("SELECT * FROM my_data_1c  WHERE visible = 1 {$warehouse_text} {$group_num} {$sql} ");
			for ($i=0; $i<sizeof($sql); $i++)
			{
				$respons[$i]->name .= $sql[$i]->name;
        $respons[$i]->groups .= $sql[$i]->groups;
				$respons[$i]->count .= $sql[$i]->count;
				$respons[$i]->type .= $sql[$i]->type;
				$respons[$i]->roz .= $sql[$i]->price;
				$respons[$i]->sum_roz .= $sql[$i]->price*$sql[$i]->count;
				$respons[$i]->zakup .= $sql[$i]->total_pieces;
				$respons[$i]->sum_zakup .= $sql[$i]->total_pieces*$sql[$i]->count;
			}
			switch ($_GET['sort'])
			{
				case 'up':
					usort($respons, function($a, $b) {
							return $a->sum_zakup < $b->sum_zakup;
					});
					$sql = $respons;
				break;

				case 'down':
					usort($respons, function($a, $b) {
							return $a->sum_zakup > $b->sum_zakup;
					});
					$sql = $respons;
				break;

			    default:
			       $sql = $respons;
				  break;
			}
			?>
    <table class="price">
      <thead class="thead">
          <tr>
            <th style="width:40%;" class="center">Наименование</th>
            <th style="width:10%;" class="center">Кол-во</th>
            <th style="width:10%;" class="center">Единица измерения</th>
            <th style="width:10%;" class="center">Цена розница</th>
            <th style="width:10%;" class="center sum_roz_head">Сумма розница</th>
            <th style="width:10%;" class="center">Цена закуп.</th>
            <th style="width:10%;" class="center sum_zakup_head">

            <a class="sort" href="<?=rawurldecode($_SERVER['REQUEST_URI']);?><?if(isset($_GET['group'])) {echo '&';} else {echo '?';}?>sort=<? if($_GET['sort'] == 'up') {echo 'down';} elseif($_GET['sort'] == 'down') {echo 'up';}else{{echo 'up';}} ?>">

            Сумма закуп. <? if($_GET['sort'] == 'up') {echo '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';} elseif($_GET['sort'] == 'down') {echo '<span class="glyphicon glyphicon-sort-by-attributes"></span>';} ?>
            </a></th>
          </tr>
      </thead>
    <?
		$cnt = 1;
        foreach($sql as $res)
        {
            ?>
         <tbody>
          <tr>
            <td style="width:40%;"><?=$cnt;?>. <?=$res->name;?></td>
            <td style="width:10%;" class="right"><? echo $count[] = sprintf("%.2f", $res->count);?></td>
            <td style="width:10%;" class="right"><?=$res->type;?></td>
            <td style="width:10%;" class="right"><?=number_sum($res->roz);?></td>
            <td style="width:10%;" class="right"><? $sum_roz[] = $res->sum_roz; echo number_sum($res->sum_roz);?></td>
            <td style="width:10%;" class="right"><?=number_sum($res->zakup);?></td>
            <td style="width:10%;" class="right"><? $sum_zakup[] =$res->sum_zakup; echo  number_sum($res->sum_zakup);?></td>
          </tr>
          </tbody>
            <? $cnt ++;
        }

    ?>
		<tbody>
		 <tr>
			 <td style="width:40%;">Итого</td>
			 <td style="width:10%;" class="right"><?=number_sum(array_sum($count));?></td>
			 <td style="width:10%;" class="right"></td>
			 <td style="width:10%;" class="right"></td>
			 <td style="width:10%;" class="right sum_roz"><?=number_sum(array_sum($sum_roz));?></td>
			 <td style="width:10%;" class="right"></td>
			 <td style="width:10%;" class="right sum_zakup"><?=number_sum(array_sum($sum_zakup));?></td>
		 </tr>
		 </tbody>

    </table>
</div>


<style>

.thead.fixed { position: fixed; top: 0}
</style>
<script>
$(document).ready(function(e)
{
	$(".sum_zakup_head").append('<span class="min">'+$( ".sum_zakup" ).html()+'</span>');
	$(".sum_roz_head").append('<span class="min">'+$( ".sum_roz" ).html()+'</span>');

	$(document).on('scroll', function(e)
	{
		if( $(document).scrollTop() > 250 )
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

$(document).on('click','.refresh_1C',function(e)
{
	$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
	$(".modal-body").html('<h4 class="modal-title"><center>Идет загрузка данных с 1С, дождитесь завершения....</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

	$.ajax
	({
		url: "/static/data_1C",
		type: "GET",
		cache: true,
		success: function(response)
		{
			if (typeof response == 'undefined' || response == 0)
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Произошла ошибка, дождитесь перезагрузки страници и попробуйте еще раз :(</center></h4>');
				setTimeout(function() {window.location.reload();}, 1000);
			}
			else
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Новые данные загружены. Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				setTimeout(function() {window.location.reload();}, 1000);
			}
		}
	});


});

</script>
