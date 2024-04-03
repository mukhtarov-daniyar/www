<?

$data = $CFG->FORM->getFullForm();
$CFG->oPageInfo->html_title = 'РЕЙТИНГ GKGD';
?>


<h2><img alt="" src="/tpl/img/new/icon/10_red.png">РЕЙТИНГ GKGD</h2>
<br>

<div class="row tabs">
    <div class="obj">
        <h1><a href="<?=sklad_act();?>&tabs=1&counts=2">Наличный склад</a></h1>
    </div>
    <div class="obj">
        <h1><a href="/speedometer/nomenclature_view/?cdate=0">Все реализации</a></h1>
    </div>

    <? if($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85) {?>
    <div class="obj">
        <h1><a href="/speedometer/cashbox/">Кассы</a></h1>
    </div>
		<? } ?>

    <div class="obj">
        <h1><a href="/speedometer/analysis/<?=date('Y');?>/<?=date('m');?>/wp/ASC">Анализ по  номенклатуре</a></h1>
    </div>


    <div class="obj">
        <h1  class="active"><a href="/speedometer/gkgd/">GKGD</a></h1>
    </div>
</div>


<style type="text/css">
table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:12px; text-align:center; margin-bottom:20px; margin-top:10px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000; width: 49%;}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:13px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}
</style>

<div class="white">
  <?
    $homepage = CurlInit('https://sepcom.ru/prices/ledru/%D0%9F%D1%80%D0%BE%D0%B4%D0%B0%D0%B6%D0%B8%20GKGD.xlsx');
    file_put_contents('documents/gkgd/gkgd.xlsx', $homepage);
    $document = PHPExcel_IOFactory::load('documents/gkgd/gkgd.xlsx');
    $activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);
    $activeSheetData = stable_usort($activeSheetData, 'D', 'DESC');

  ?>

  <h3 style="font-size:16px;">Накопительная ведомость по оборотам дилеров по модулям GKGD с 1.01.2020, рублей</h3>
  <table class="price">
    <tr>
      <th>Название</th>
      <th>Сумма, рублей</th>
    </tr>
    <?
    for ($i=0; $i<sizeof($activeSheetData); $i++)
    {
      if(
        $activeSheetData[$i][A] =='' ||
        $activeSheetData[$i][D] =='' ||
        $activeSheetData[$i][A] =='Итого' ||
        $activeSheetData[$i][A] =='Организация' ||
        $activeSheetData[$i][A] =='Параметры:' ||
        $activeSheetData[$i][A] =='СЭПКОМ ООО' ||
        $activeSheetData[$i][A] =='Кочкин Алексей Николаевич ИП' ||
        $activeSheetData[$i][A] =='Валовая прибыль по клиентам по организациям %ВалютаОтчета%'
        ) continue;

      ?>
        <tr>
          <td style="text-align:right"><?=$NEWS->buyer($activeSheetData[$i][A]);?></td>
          <td style="text-align:right; padding-right:350px;"><?=number_sum($activeSheetData[$i][D]);  $array[] = $activeSheetData[$i][D]*1;  ?></td>
        </tr>
      <?
    }
    ?>
    <tr>
      <td style="text-align:right; font-size:14px;"><strong>Итого,рублей</strong></td>
      <td style="text-align:right;padding-right:320px; font-size:14px;"><strong><?=number_sum(array_sum($array));  ?></strong></td>
    </tr>
  <table>
</div>


<div style="clear:both"></div>
