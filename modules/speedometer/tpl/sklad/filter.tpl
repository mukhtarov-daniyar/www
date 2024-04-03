<?

$data = $CFG->FORM->getFullForm();
$CFG->oPageInfo->html_title = 'Список товаров 1С на складе';
?>


<h2><img alt="" src="/tpl/img/new/icon/10_red.png">Отображено товаров <!--span class="count_1c">100</span!--> -  <?=number_sum($l['row']['id']);?></h2>
<br>

<div class="row tabs">
    <div class="obj">
        <h1 class="active"><a href="<?=sklad_act();?>">Наличный склад</a></h1>
    </div>

    <div class="obj">
        <h1><a href="/speedometer/nomenclature_view/?cdate=0">Все реализации</a></h1>
    </div>

    <? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 926 || $CFG->USER->USER_ID == 565) {?>
    <div class="obj">
        <h1 ><a href="/speedometer/cashbox/">Кассы</a></h1>
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
		<form method="GET" enctype="multipart/form-data" class="response">
    <?
      if($_GET[tabs] > 0) { echo '<input type="hidden" value="'.$_GET[tabs].'" name="tabs">';}
      if($_GET[counts] > 0) { echo '<input type="hidden" value="'.$_GET[counts].'" name="counts">';}
      if($_GET[prices] > 0) { echo '<input type="hidden" value="'.$_GET[prices].'" name="prices">';}
    ?>

            <div class="col-md-3">
              <p>Что найти?</p>
              <input type="text" name="search" value="<?=$data['search']?>" id="full_search_input" <?=$e['search']?>/>
            </div>

            <div class="col-md-3">
                <p>Склад</p>
                <select name="warehouse" class="selectpicker show-tick" data-live-search="true">
                <option value="0" selected>Выберите склад</option>
                <?
                $warehouse = DataArray('data_1c_warehouse');
                for($i=0;$i<sizeof($warehouse);$i++)
                {
                  if($warehouse[$i]->name == '') continue;
                    ($data['warehouse'] == $warehouse[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$warehouse[$i]->id?>"<?=$sel?>><?=$warehouse[$i]->name;?></option>
                <? } ?>
                </select>
            </div>

            <div class="col-md-3">
                <p>Группа товаров</p>
                <select name="group[]" class="selectpicker show-tick" multiple="multiple" data-live-search="true">
                <option value="0">Выберите группу</option>
                <?
                $group = DataArray('data_1c_group');

                for($i=0;$i<sizeof($group);$i++)
                {
                  foreach($data['group'] as $res)
                  {
                    if($res == $group[$i]->id) { $sel = ' selected="selected"'; }
                  } ?>
                    <option value="<?=$group[$i]->id?>"<?=$sel?>><?=$group[$i]->name;?></option>
                <?  $sel = ""; } ?>
                </select>
            </div>

            <div class="col-md-3">
              <p>Поставщик</p>
              <input type="hidden" name="provider" class="providerV" placeholder="" value="<?=$data['provider']?>" <?=$e['provider']?> />
              <input type="text" class="providerS" placeholder="Укажите поставщика" value="<?=SelectData('data_1c_provider', $data['provider'])?>" <?=$e['provider']?>/>
              <div class="rec_provider"></div>
            </div>

           <div class="col-md-3">
            	<button type="submit" style="margin-top:0">Поиск</button>
            </div>

        </form>

<style>
.sklad_tab { display: block; clear: both; padding: 0; margin: 0; list-style: none; text-align:left;;}
.sklad_tab li{ display: inline-block; font-size:11px;     font-family: 'segoeui_sb'; }
.sklad_tab li a{ color:#000; display: block; padding: 5px 20px;}
.sklad_tab li a.act, .sklad_tab li a:hover{ color:#fff; background:#F84241}
</style>

<ul class="sklad_tab">
  <li><a <? if($_GET[tabs] == 1) {echo ' class="act" ';} ?> href="<?=sklad_act();?>&tabs=1&counts=2">Наличные товары с количеством</a></li>
  <li><a <? if($_GET[tabs] == 2) {echo ' class="act" ';} ?> href="/speedometer/?tabs=2&counts=1">Все товары - справочник</a></li>
  <li><a <? if($_GET[tabs] == 3) {echo ' class="act" ';} ?> href="<?=sklad_act();?>&tabs=3&prices=3">Малоценые товары (нулевая цена)</a></li>
  <li><a <? if($_GET[tabs] == 4) {echo ' class="act" ';} ?> href="<?=sklad_os();?>&tabs=4&prices=2">ОС, имущество копании</a></li>
</ul>


	</div>


<div style="clear:both"></div>

<div class="more_default">
