<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <title>Комерческое предложение</title>
</head>


<base href="https://<?=$_SERVER["HTTP_HOST"];?>/">
<body>
<style>

@page {margin: 0cm 0cm;}
body {font-family:times; margin-top: 2cm;margin-left: 2cm;margin-right: 2cm;margin-bottom: 2cm;max-width:900px;margin:0 auto !important;}

.object { position:relative;  display:block; margin:0 auto;width:100%; height:885px; padding:15px 20px; font-size:14px; background: no-repeat right 170px   url('<?=("tpl/templates/cp/img/forsign/copy.png");?>'); background-size:22%}
.object img.logo{ display:block; width:100%;}
.object .artikul{ padding:0; margin:0; display:block; font-size:14px; margin-top:20px;}
.object h1{ padding:0; margin:0; display:block; font-size:18px; margin-top:5px; margin-bottom: 5px;text-align:center}
.object .welcom{ padding:0; margin:0; display:block; font-size:14px; margin-top:10px; margin-bottom:10px;}

table {  width:100%; border-collapse: collapse; font-size:16px; text-align:center; margin-bottom:20px; text-align:left;  }
table td {border: 1px solid #000;  padding:0; margin:0; padding:3px; vertical-align:middle; font-size:12px}
table th {color:#000; border: 1px solid #000;background: #FFE0CC; font-size:13px; padding:0; margin:0;  padding:3px;  vertical-align:middle}

.copy { display:block; border-top:2px solid #F5811E; width:100%; position:absolute; bottom:10px;}
.copy .contats{ display:inline-block; width:30%; vertical-align:top; margin-top:10px; }
.copy .contats span.title{ text-align:right; display:block; font-size:12px;}
.copy .contats img.big{ width:80%;  padding-top:5px;}
.copy .contats img.icon { width:16px; height:16px;}
.copy .contats span.icons{ display:block; font-size:12px; white-space:nowrap}

</style>


<div class="object">
	<img class="logo" src="<?=("tpl/templates/cp/img/forsign/logo.png");?>">

  <strong>образец платежного поручения</strong>
  <table cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="6">Бенефициар:</td>
      <td colspan="5">ИИК</td>
      <td colspan="3">Кбе</td>
    </tr>
    <tr>
      <td colspan="6" >Товарищество с Ограниченной Ответственностью &quot;forSign Center&quot;</td>
      <td colspan="5" rowspan="2">KZ689650000072084139</td>
      <td colspan="3" rowspan="2">17</td>
    </tr>
    <tr>
      <td colspan="6">БИН: 060840010531</td>
    </tr>
    <tr>
      <td colspan="6">Банк бенефициара:</td>
      <td colspan="5">БИК</td>
      <td colspan="3">Код назначения платежа</td>
    </tr>
    <tr>
      <td colspan="6" width="376">АО ForteBank в  г. Астана</td>
      <td colspan="5">IRTYKZKA</td>
      <td colspan="3">&nbsp;</td>
    </tr>
  </table>


    <h1>Счет на оплату  № <?=$CFG->_GET_PARAMS[1];?> от <?= dateSQL2TEXT($o->cdate, "DD.MM.YYYY");?> г. <?= dateSQL2TEXT($o->cdate, "hh:mm");?></h1>

    <table cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="4">Поставщик:</td>
        <td colspan="10" >Товарищество с    Ограниченной Ответственностью &quot;forSign Center&quot;, БИН: 060840010531,    Адрес: Республика Казахстан, г.Астана, район Сарыарка, Проспект Бөгенбай    Батыр, дом 59, н.п. 7, почтовый индекс 010000</td>
      </tr>
      <tr>
        <td colspan="4">Покупатель:</td>
        <td colspan="10" ><?=$o->my_name;?></td>
      </tr>

      <tr>
        <td colspan="4">Основание:</td>
        <td colspan="10">без договора</td>
      </tr>
    </table>



<?

		$html .= '
		<table border="1" cellspacing="0" cellpadding="0">
		  <tr>
			<th>Позиция</th>
			<th>Кол-во</th>
			<th>Цена, тг.</th>
			<th>Сумма, тг.</th>
		  </tr>';

		  $names = unserialize($o->names_d);
		  $count = unserialize($o->count_d);
		  $price = unserialize($o->price_d);

		  for ($i=0; $i<sizeof($names); $i++)
		  {
			  $arr_count [] = $count[$i];
			  $arr_price [] = $price[$i];
			  $arr_price_vsr [] = $price[$i]*$count[$i];


                $html .= '
				<tr>
                    <td>'.$names[$i].'</td>
                    <td>'.number_sum($count[$i]).' шт.</td>
                    <td>'.number_sum($price[$i]).' тенге</td>
                    <td>'.number_sum($price[$i]*$count[$i]).' тенге</td>
                  </tr>
              ';
		  }

		  $html .= '


		  <tr>
			<td colspan="4"><strong>Итого</strong>: '.number_sum(array_sum ($arr_price_vsr)).' тенге, в т.ч. НДС '.number_sum(round(array_sum ($arr_price_vsr)-array_sum ($arr_price_vsr)/1.12, 2)).' тенге </td>
		  </tr>
		</table>

<h2>Счет и цена на товар действительны в течение трех банковских дней.<br>
  Товар отпускается после зачисления денег на счет при наличии доверенности.</h2>

    ';

		$user = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$o->user_id}' ");
		$name_company = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}data_1c_company WHERE id='{$o->company_id}' ");

echo $html;

?>




<img style="position:absolute; bottom:100px; left:300px;" src="<?=("tpl/templates/cp/img/stamp/5.jpg");?>">

<div style="position:absolute; bottom:150px; left:20px">
	<span style="">с/у, директор Алимжанов А.К.<br><?=$name_company->name;?></span>
</div>

<div style="position:absolute; bottom:150px; left:560px;">
	<span style="font-style:italic; display:block; font-size:12px; ">Исполнитель <?=$user->name;?></span>
	<span style="font-style:italic; display:block; font-size:12px; ">Моб.: <?=$user->mobile;?></span>
	<span style="font-style:italic; display:block; font-size:12px; "> Whatsapp: +7-701-0-320-320</span>
</div>

<div class="copy">
	<div class="contats">
    	<span class="title">Информационный Instagram портал о светодиодных технологиях</span>
        <img class="big" src="<?=("tpl/templates/cp/img/forsign/signimpress.png");?>">
    </div>
	<div class="contats" style="margin-left:3%;">
    	<span class="title">Единый справочно-информационный центр WhatsApp</span>
        <img class="big" src="<?=("tpl/templates/cp/img/forsign/call.png");?>">
    </div>
	<div class="contats" style="margin-left:3%;">
    	<span class="icons"><img src="<?=("tpl/templates/cp/img/forsign/world.png");?>" class="icon"> forsign.kz</span>
    	<span class="icons"><img src="<?=("tpl/templates/cp/img/forsign/insta.png");?>" class="icon"> forsign.kz</span>
    	<span class="icons"><img src="<?=("tpl/templates/cp/img/forsign/tel.png");?>" class="icon"> +7-701-0-320-320</span>
    	<span class="icons" style="font-size:11px;"><img src="<?=("tpl/templates/cp/img/forsign/map.png");?>" class="icon" style="width:12px;"> г. Астана, ул. Субханбердина, д. 20</span>
    </div>
</div>


</div>
</body>
</html>
