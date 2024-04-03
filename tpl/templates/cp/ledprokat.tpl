<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <title>Комерческое предложение</title>
    <base href="https://domainscrm.ru/">
</head>
<body>
<style>
@page {margin: 0cm 0cm;}
body {font-family:times; margin-top: 2cm;margin-left: 2cm;margin-right: 2cm;margin-bottom: 2cm;max-width:900px;margin:0 auto !important;}


.object { position:relative;  display:block; margin:0 auto;width:100%; height:885px; padding:15px 20px; font-size:14px; background: no-repeat right 170px   url('<?=("tpl/templates/cp/img/ledprokat/copy.png");?>'); background-size:22%}

.object img.logo{ display:block; width:100%;}
.object .artikul{ padding:0; margin:0; display:block; font-size:14px; margin-top:20px;}
.object h1{ padding:0; margin:0; display:block; font-size:18px; margin-top:40px; text-align:center}
.object .welcom{ padding:0; margin:0; display:block; font-size:14px; margin-top:10px; margin-bottom:10px;}

table {  width:100%; border-collapse: collapse; font-size:16px; text-align:center; margin-bottom:20px; text-align:left;  }
table td {border: 1px solid #000;  padding:0; margin:0; padding:5px 5px; vertical-align:middle; font-size:13px}
table th {color:#000; border: 1px solid #000;background: #D4E2FF; font-size:14px; padding:0; margin:0;  padding:5px 5px;  vertical-align:middle}

.copy { display:block; border-top:2px solid #2E4F93; width:100%; position:absolute; bottom:10px;}
.copy .contats{ display:inline-block; width:30%; vertical-align:top; margin-top:10px; }
.copy .contats span.title{ text-align:right; display:block; font-size:12px;}
.copy .contats img.big{ width:100%; padding-top:5px;}
.copy .contats img.icon { width:16px; height:16px;}
.copy .contats span.icons{ display:block; font-size:12px; white-space:nowrap}

</style>

<div class="object">
	<img class="logo" src="<?=("tpl/templates/cp/img/ledprokat/logo.png");?>">
	<span class="artikul">Исх. № <?=$CFG->_GET_PARAMS[1];?> от <?= dateSQL2TEXT($o->cdate, "DD.MM.YYYY");?> г. <?= dateSQL2TEXT($o->cdate, "hh:mm");?></span>
    <h1>Коммерческое предложение.</h1>
    <span class="welcom">Предлагаем Вашему вниманию следующую продукцию:</span>
    
   
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
			<td colspan="4"><strong>Итого</strong>: '.number_sum(array_sum ($arr_price_vsr)).' тенге</td>
		  </tr>
		</table>';

		$user = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$o->user_id}' ");
		$name_company = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}data_1c_company WHERE id='{$o->company_id}' ");
			
echo $html;

?>

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
    	<span class="icons"><img src="<?=("tpl/templates/cp/img/ledprokat/world.png");?>" class="icon"> www.ledprokat.kz</span>
    	<span class="icons"><img src="<?=("tpl/templates/cp/img/ledprokat/insta.png");?>" class="icon"> ledprokat.kz</span>
    	<span class="icons"><img src="<?=("tpl/templates/cp/img/ledprokat/tel.png");?>" class="icon"> +7-701-0-320-320</span>
    	<span class="icons" style="font-size:11px;"><img src="<?=("tpl/templates/cp/img/ledprokat/map.png");?>" class="icon" style="width:12px;"> г. Астана, ул. Субханбердина, д. 20</span>        
    </div>
</div>


</div>
</body>
</html>
