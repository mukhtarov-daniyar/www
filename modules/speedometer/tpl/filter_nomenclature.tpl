<?

$data = $CFG->FORM->getFullForm();
$CFG->oPageInfo->html_title = 'Продажи за день';
?>


<h2><img alt="" src="/tpl/img/new/icon/10_red.png">Реализации за <? echo date('d-m-Y'); ?> с 00:01 по <? echo date('H:i'); ?></h2>
<br>

<div class="row tabs">
    <div class="obj">
        <h1><a href="<?=sklad_act();?>&tabs=1&counts=2">Наличный склад</a></h1>
    </div>
    <div class="obj">
        <h1 class="active"><a href="/speedometer/nomenclature_view/?cdate=0">Все реализации</a></h1>
    </div>

    <? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 926 || $CFG->USER->USER_ID == 565) {?>
    <div class="obj">
        <h1><a href="/speedometer/cashbox/">Кассы</a></h1>
    </div>
		<? } ?>

    <div class="obj">
        <h1><a href="/speedometer/analysis/<?=date('Y');?>/<?=date('m');?>/wp/ASC">Анализ по  номенклатуре</a></h1>
    </div>


    <!--div class="obj">
        <h1><a href="/speedometer/gkgd/">GKGD</a></h1>
    </div!-->


</div>

    <div class="col-md-12 filter_hide block"><br>
		<form method="GET" enctype="multipart/form-data" class="response" action="/speedometer/nomenclature_view/">

            <div class="col-md-3">
              <p>Что найти?</p>
              <input type="text" name="search" value="<?=$data['search']?>" id="full_search_input" <?=$e['search']?>/>
            </div>

            <div class="col-md-3">
                <p>Кто продал</p>
                <select name="user[]" class="selectpicker show-tick" multiple="multiple"  data-live-search="true">

                <?
                $group = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1  ORDER BY name ASC");

                for($i=0;$i<sizeof($group); $i++)
                {
                  foreach ($data['user'] as $arr) {  if($arr == $group[$i]->id) { $sel = " selected"; }
                }
                ?>
                    <option value="<?=$group[$i]->id?>"<?=$sel;?>><?=$group[$i]->name;?></option>
                <? $sel = ""; } ?>
                </select>
            </div>


            <div class="col-md-3">
                <p>Склад</p>
                <select name="warehouse_nomenclature[]" class="selectpicker warehouse_nomenclature"   multiple="multiple"  data-live-search="true">
                <?

                $warehouse = DataArray('data_1c_warehouse');
                for($i=0;$i<sizeof($warehouse);$i++)
                { if($warehouse[$i]->name == '') continue;

                    foreach ($data['warehouse_nomenclature'] as $arr)
                    {
                      if($arr == $warehouse[$i]->id) { $sel = " selected";
                    }
}
                    ?>
                    <option value="<?=$warehouse[$i]->id?>"<?=$sel?>><?=$warehouse[$i]->name;?></option>
                <? $sel = "";  } ?>
                </select>

            </div>

            <div class="col-md-3">
                <p>Тип реализации</p>
                <select name="IM_P" class="selectpicker show-tick">
                  <option value="0" <? if($_GET['IM_P'] == 0){ echo 'selected';} ?>>Все реализации</option>
                  <option value="1" <? if($_GET['IM_P'] == 1){ echo 'selected';} ?>>Заказы с ИМ</option>
                </select>
            </div>


            <br clear="all">

            <div class="col-md-3">
                <p>Тип продажи</p>
                <select name="type" class="selectpicker show-tick">
                  <option value="0" <? if($_GET['type'] == 0){ echo 'selected';} ?>>Все</option>
                  <option value="1" <? if($_GET['type'] == 1){ echo 'selected';} ?>>Товар</option>
                  <option value="2" <? if($_GET['type'] == 2){ echo 'selected';} ?>>Услуга</option>
                </select>
            </div>



            <div class="col-md-3">
              <p>Контрагент</p>
              <input type="text" name="buyer" class="buyer" placeholder="Укажите контрагента" value="<?=$data['buyer']?>" <?=$e['buyer']?>/>
              <div class="rec_buyer"></div>
            </div>

            <div class="col-md-3">
              <p>Поставщик</p>
              <input type="hidden" name="provider" class="providerV" placeholder="" value="<?=$data['provider']?>" <?=$e['provider']?> />
              <input type="text" class="providerS" placeholder="Укажите поставщика" value="<?=SelectData('data_1c_provider', $data['provider'])?>" <?=$e['provider']?>/>
              <div class="rec_provider"></div>
            </div>

            <!--div class="col-md-3">
                <p>Период</p>
                <select name="cdate" class="selectpicker show-tick">
                  <option value="0" <? if($_GET['cdate'] == 0){ echo 'selected';} ?>>За сегодня</option>
                  <option value="1" <? if($_GET['cdate'] == 1){ echo 'selected';} ?>>За вчера</option>
                  <option value="2" <? if($_GET['cdate'] == 2){ echo 'selected';} ?>>С понедельника</option>
                  <option value="3" <? if($_GET['cdate'] == 3){ echo 'selected';} ?>>С 1 числа месяца</option>
                  <option value="4" <? if($_GET['cdate'] == 4){ echo 'selected';} ?>>С 1 января</option>
                  <option value="5" <? if($_GET['cdate'] == 5){ echo 'selected';} ?>>За 2020 год</option>
                  <option value="6" <? if($_GET['cdate'] == 6){ echo 'selected';} ?>>За все время</option>
                </select>
            </div!-->

            <div class="col-md-3">
            	 <p>Период начало:</p>
            	 <input name="monthstart" type="text" value="<? if($_GET['monthstart'] !=''){ echo $_GET['monthstart']; } else { echo date('Y-m-d 00:00:01'); } ?>" id="cdatestart">
            </div>

            <div class="col-md-3">
            	<p>Период конец:</p>
            	<input name="monthend" type="text" value="<? if($_GET['monthend'] !=''){ echo $_GET['monthend']; } else { echo date('Y-m-d 23:59:59');  } ?>" id="cdateoff">
            </div>

            <div class="col-md-12 up_cdata">
            	 <a href="#" onclick="Up_Cdate(0)">За сегодня</a>
               <a href="#" onclick="Up_Cdate(1)">За вчера</a>
               <a href="#" onclick="Up_Cdate(2)">С понедельника</a>
               <a href="#" onclick="Up_Cdate(3)">С 1 числа месяца</a>
               <a href="#" onclick="Up_Cdate(4)">Прошедший месяц</a>
               <div class="col-md-3">
                	<button type="submit" style=" position:relative; left:-15px;">Поиск</button>
               </div>
            </div>

        </form>

	</div>


<div style="clear:both"></div>


<script>

function Up_Cdate(type)
{
  var monthstart = '';
  var monthend = '';

  //Сейчас
  var today = new Date();

  switch(type)
  {
    case 0 :
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      monthstart = yyyy + '-' + mm + '-' + dd +' 00:00:00';
      monthend = yyyy + '-' + mm + '-' + dd +' 23:59:59';
    break;

    case 1 :
      today.setDate(today.getDate() - 1);
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      monthstart = yyyy + '-' + mm + '-' + dd +' 00:00:00';
      monthend = yyyy + '-' + mm + '-' + dd +' 23:59:59';
    break;


    case 2 :
      var dd_end = String(today.getDate()).padStart(2, '0');

      var day = today.getDay(), diff = today.getDate() - day + (day == 0 ? -6:1);
      todayS = new Date(today.setDate(diff));

      var dd = String(todayS.getDate()).padStart(2, '0');
      var mm = String(todayS.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = todayS.getFullYear();

      monthstart =  '<?=date('Y-m-d 00:00:00', strtotime("last Monday"));?>';
      monthend = '<?=date('Y-m-d 23:59:59', strtotime("next Sunday"));?>';
    break;

    case 3 :
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      monthstart = yyyy + '-' + mm + '-01 00:00:00';
      monthend = yyyy + '-' + mm + '-' + dd +' 23:59:59';
    break;

    case 4 :
      today.setMonth(today.getMonth() - 1);
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();
      monthstart = yyyy + '-' + mm + '-01 00:00:00';

      //Последний день текущего месяца
      var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth()+1, 0);

      var dd_end = String(lastDayOfMonth.getDate()).padStart(2, '0');
      var mm_end = String(lastDayOfMonth.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyy_end = lastDayOfMonth.getFullYear();
      monthend = yyy_end + '-' + mm_end + '-' + dd_end +' 23:59:59';
    break;
  }

  if(!monthstart =='' && !monthend =='' )
  {
    document.getElementById('cdatestart').value = monthstart;
    document.getElementById('cdateoff').value = monthend;
  }
  event.preventDefault ();
}


$('input.buyer').keyup(function()
{
  var buyer  = $(this).val();
    $.ajax
		({
			url: "/speedometer/buyer/",
			type: "POST",
			data: {"buyer": buyer},
			cache: true,
			success: function(response)
			{
           $('.rec_buyer').html(response);
			}
		});
});

$(document).on('click','ul.modal_rec li',function(e)
{
  var html = $(this).html();

  $('input.buyer').val(html);
  $('.rec_buyer').html('');
  $('.alert').html("Нажмите на кнопку «Поиск», для отображения информации.");
  $('.alert').animate({'opacity':'show'}, 1000);
  $('.alert').animate({'opacity':'hide'}, 6000);
});
</script>
