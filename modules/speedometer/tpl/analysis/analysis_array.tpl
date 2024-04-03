<?

$data = $CFG->FORM->getFullForm();
$CFG->oPageInfo->html_title = 'Аналитика';
?>


<h2><img alt="" src="/tpl/img/new/icon/10_red.png">Анализ по  номенклатуре</h2>
<br>


<div class="row tabs">
    <div class="obj">
        <h1><a href="<?=sklad_act();?>&tabs=1&counts=2">Наличный склад</a></h1>
    </div>
    <div class="obj">
        <h1><a href="/speedometer/nomenclature_view/?cdate=0">Все реализации</a></h1>
    </div>

    <? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 926 || $CFG->USER->USER_ID == 565) {?>
    <div class="obj">
        <h1><a href="/speedometer/cashbox/">Кассы</a></h1>
    </div>
		<? } ?>

    <div class="obj">
        <h1 class="active"><a href="/speedometer/analysis/<?=date('Y');?>/<?=date('m');?>/wp/ASC">Анализ по  номенклатуре</a></h1>
    </div>


    <!--div class="obj">
        <h1><a href="/speedometer/gkgd/">GKGD</a></h1>
    </div!-->

</div>

<style type="text/css">
  table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:12px; text-align:center; margin-bottom:20px;  font-family: 'segoeui'; }
  table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
  table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}
  table.price th a {color:#fff; }
  table.price td a {color:#000; }
  table.price th span { display: block; font-size: 10px; text-decoration: underline;}
  .add_analysis { float: right; margin-bottom: 20px; margin-top: 20px;}

  table.price td.left { text-align: left;}
  table.price td.right { text-align: right;}
  .month_speedometer_analysis, .year_speedometer_analysis { position:relative; top:13px;}
</style>


<div class="white">
  <select name="year" class="selectpicker year_speedometer_analysis show-tick">
      <option value="2020" <? if($CFG->_GET_PARAMS[1] == '2020'){ echo 'selected="selected"';} ?>>2020</option>
      <option value="2021" <? if($CFG->_GET_PARAMS[1] == '2021'){ echo 'selected="selected"';} ?>>2021</option>
      <option value="2022" <? if($CFG->_GET_PARAMS[1] == '2022'){ echo 'selected="selected"';} ?>>2022</option>
      <option value="2023" <? if($CFG->_GET_PARAMS[1] == '2023'){ echo 'selected="selected"';} ?>>2023</option>
  </select>


<select name="floor" class="selectpicker month_speedometer_analysis show-tick">
    <option value="01" <? if($CFG->_GET_PARAMS[2] == '01'){ echo 'selected="selected"';} ?>>Январь</option>
    <option value="02" <? if($CFG->_GET_PARAMS[2] == '02'){ echo 'selected="selected"';} ?>>Февраль</option>
    <option value="03" <? if($CFG->_GET_PARAMS[2] == '03'){ echo 'selected="selected"';} ?>>Март</option>
    <option value="04" <? if($CFG->_GET_PARAMS[2] == '04'){ echo 'selected="selected"';} ?>>Апрель</option>
    <option value="05" <? if($CFG->_GET_PARAMS[2] == '05'){ echo 'selected="selected"';} ?>>Май</option>
    <option value="06" <? if($CFG->_GET_PARAMS[2] == '06'){ echo 'selected="selected"';} ?>>Июнь</option>
    <option value="07" <? if($CFG->_GET_PARAMS[2] == '07'){ echo 'selected="selected"';} ?>>Июль</option>
    <option value="08" <? if($CFG->_GET_PARAMS[2] == '08'){ echo 'selected="selected"';} ?>>Август</option>
    <option value="09" <? if($CFG->_GET_PARAMS[2] == '09'){ echo 'selected="selected"';} ?>>Сентябрь</option>
    <option value="10" <? if($CFG->_GET_PARAMS[2] == '10'){ echo 'selected="selected"';} ?>>Октябрь</option>
    <option value="11" <? if($CFG->_GET_PARAMS[2] == '11'){ echo 'selected="selected"';} ?>>Ноябрь</option>
    <option value="12" <? if($CFG->_GET_PARAMS[2] == '12'){ echo 'selected="selected"';} ?>>Декабрь</option>
</select>

  <button class="add_analysis">+ добавить</button>

  <?
  	if($CFG->_GET_PARAMS[4] == 'ASC' && $CFG->_GET_PARAMS[4] != '') {$link = 'DESC';} else {$link = 'ASC';}
   ?>


  <table class="price">
    <tr>
      <th>Название складское</th>
      <th><a href="/speedometer/analysis/<?=$CFG->_GET_PARAMS[1];?>/<?=$CFG->_GET_PARAMS[2];?>/count/<?=$link;?>">Кол-во номенкл.<br> на складе</a><span class="sum_count"></span></th>
      <th><a href="/speedometer/analysis/<?=$CFG->_GET_PARAMS[1];?>/<?=$CFG->_GET_PARAMS[2];?>/real/<?=$link;?>">Сумма реализ.<br>с 1 числа</a><span class="sum_real"></span></th>
      <th><a href="/speedometer/analysis/<?=$CFG->_GET_PARAMS[1];?>/<?=$CFG->_GET_PARAMS[2];?>/wp/<?=$link;?>">Вал. прибыль<br>с 1 числа</a><span class="sum_wp"></span></th>
      <!--th><a href="/speedometer/analysis/wp/<?=$link;?>">Проц. прибыль<br>с 1 числа</a><span class="sum_pc"></span></th!-->
    </tr>
    <?


    $cnt = 0;
    foreach($l as $res)
    {
        $data[$cnt]['id'] = $res->id;
        $data[$cnt]['name'] = $res->name;
        $data[$cnt]['count'] = $SPEED->getCountAnalysis($res->name);
        $data[$cnt]['real'] =  $SPEED->getRealAnalysis($res->name, $CFG->_GET_PARAMS[1], $CFG->_GET_PARAMS[2]);
        $data[$cnt]['wp'] =    $SPEED->getWPAnalysis($res->name, $CFG->_GET_PARAMS[1], $CFG->_GET_PARAMS[2]);
        $data[$cnt]['pc'] =    $SPEED->getPCAnalysis($res->name, $CFG->_GET_PARAMS[1], $CFG->_GET_PARAMS[2]);
        $cnt ++;
    }


    if($CFG->_GET_PARAMS[3] != '') {$sort = $CFG->_GET_PARAMS[3];} else {$sort = 'name';}

    $data = stable_usort($data, $sort, $link);

    $cnt2 = 1;
    foreach($data as $res)
    {
      ?>
      <tr>
        <td class="left"><a href="/speedometer/nomenclature_view/?search=<?=$res['name'];?>&warehouse=0&cdate=3" target="_blank"><?=$cnt2;?>. <?=$res['name'];?></a> <a href="#" class="del_analysis" data-id="<?=$res['id'];?>"><img src="/tpl/img/new/del.png"></a></td>
        <td><a href="/speedometer/?search=<?=$res['name'];?>&warehouse=0&group=0" target="_blank"><?=$res['count']; $sum_count[] = $res['count'];?></a></td>
        <td class="right"><?=number_sum($res['real']); $sum_real[] = $res['real'];?></td>
        <td class="right"><?=number_sum($res['wp']); $sum_wp[] = $res['wp'];?></td>
        <!--td class="right"><?=$res['pc']; $sum_pc[] = $res['pc'];?></td!-->
      </tr>
      <?
      $cnt2 ++;
    }
    ?>

    <tr>
      <td class="right"><strong>Итого в таблице: </strong></td>
      <td><strong class="sum_count"><?=number_sum(array_sum($sum_count));?></strong></td>
      <td class="right"><strong class="sum_real"><?=number_sum(array_sum($sum_real));?></strong></td>
      <td class="right"><strong class="sum_wp"><?=number_sum(array_sum($sum_wp));?></strong></td>
      <!--td class="right"><strong class="sum_pc"></strong></td!-->
    </tr>

    <?

    if($CFG->_GET_PARAMS[1] > 0 && $CFG->_GET_PARAMS[2])
    {
        $cdate = $CFG->_GET_PARAMS[1].'-'.$CFG->_GET_PARAMS[2].'-01 00:00:00';
        $day = new DateTime($CFG->_GET_PARAMS[1].'-'.$CFG->_GET_PARAMS[2].'-01');
        $cdateNext = $CFG->_GET_PARAMS[1].'-'.$CFG->_GET_PARAMS[2].'-'.$day->format('t').' 23:59:59';
    }
    else
    {
        $cdate = date('Y-m-01 00:00:00');
        $cdateNext = date('Y-m-d 23:59:59');
    }

      $respon = getSQLRowO(" SELECT SUM(price*count),SUM(price*count-purchase*count) FROM my_data_1c_nomenclature  WHERE (cdate >= '{$cdate}') AND (cdate <= '{$cdateNext}') ");
    ?>


    <tr>
      <td class="right"><strong>Прочее: </strong></td>
      <td><strong><? $ress = getSQLArrayO(" SELECT SUM(count) FROM my_data_1c GROUP BY name  "); echo count($ress)-(array_sum($sum_count)); ?></strong></td>
      <td class="right"><strong><?=number_sum($respon->{'SUM(price*count)'}-(array_sum($sum_real)));?></strong></td>
      <td class="right"><strong><?=number_sum($respon->{'SUM(price*count-purchase*count)'}-(array_sum($sum_wp)));?></strong></td>
      <!--td class="right"><strong></strong></td!-->
    </tr>


    <tr>
      <td class="right"><strong>в Системе итого: </strong></td>
      <td><strong><? echo count($ress);?></strong></td>
      <td class="right"><strong><?=number_sum($respon->{'SUM(price*count)'});?></strong></td>
      <td class="right"><strong><?=number_sum($respon->{'SUM(price*count-purchase*count)'});?></strong></td>
      <!--td class="right"><strong></strong></td!-->
    </tr>

  </table>



</div>
<div style="clear:both"></div>

<script>

	/* Фильтр. Выбор года */
	$(".year_speedometer_analysis").change(function()
	{
		if($(this).val() == 0)
		{
			location.href = '/speedometer/analysis/';
		}
		else
		{
			location.href = '/speedometer/analysis/' + $(this).val() + '/<?=$CFG->_GET_PARAMS[2];?>';
		}
	});


  /* Фильтр. Выбор месяца */
	$(".month_speedometer_analysis").change(function()
	{
		if($(this).val() == 0)
		{
			location.href = '/speedometer/analysis/';
		}
		else
		{
			location.href = '/speedometer/analysis/<?=$CFG->_GET_PARAMS[1];?>/' + $(this).val();
		}
	});


$('span.sum_wp').html($('strong.sum_wp').text());
$('span.sum_real').html($('strong.sum_real').text());
$('span.sum_count').html($('strong.sum_count').text());


$(document).on('click','.del_analysis',function(e)
{
  	var id = $(this).attr('data-id');
    $('#myModalBox').modal({backdrop: 'static', keyboard: false});
    $.ajax
    ({
      url: "/speedometer/analysis/",
      type: "POST",
      data: {"id": id,},
      cache: true,
        beforeSend: function()
        {
          $(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
        },
        success: function(response)
        {
          if(response > 0)
          {
            $(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
              setTimeout(function() {window.location.reload();}, 1000);
          }
          else
          {
            $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
          }
        }
    });
    e.preventDefault();
});

$(document).on('click','.add_analysis',function(e)
{
  $('#myModalBox').modal({backdrop: 'static', keyboard: false});
  $(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
	$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Добавить ключевое слово</h4></div>');
  $(".modal-body").append('<p><input type="text" class="form-control name" placeholder="Введите слово или фразу" autofocus></p>');
  $(".modal-body").append('<center><button style="margin-top:10px;" type="button" class="btn hover">Сохранить</button> &nbsp; &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Отменить</button></center>');

  /* Сохранить ключевое слово */
  $('.btn.hover').on('click', function(e)
  {
      var text = $('input.form-control.name').val();
      var curLength = $('input.form-control.name').val().length;
      if(curLength > 2)
      {
        $.ajax
				({
					url: "/speedometer/analysis/",
					type: "POST",
					data: {"text": text,},
					cache: true,
						beforeSend: function()
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{
							if(response > 0)
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Обновляем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
									setTimeout(function() {window.location.reload();}, 1000);
							}
							else
							{
								$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');
							}
						}
				});
      }
      else { alert('Надо ввести ключевое слово!');}

  });

});
</script>
