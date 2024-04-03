<?php

	$CFG = new stdClass();
	$CFG->CATALOG = new stdClass();

	define("ERRORPAGE", "sys_404.php");

	define("ERRORREQUEST", "tpl/error/errorRequest.tpl");
	define("EMPTYPOST", "tpl/error/emptyPost.tpl");
	define("SHOWCOMMENTTPL", "tpl/comments/formComment.tpl");
	define("SHOWFORMCOMMENT", "tpl/comments/addComment.tpl");
	define("ANSWERTPL", "tpl/comments/conferences/formComment.tpl");
	define("STARFORMSHOW", "tpl/rating/formShow.tpl");
	define("STARRESULT", "tpl/rating/rating.tpl");
	define("YOUTUBE", "tpl/youtube/main.tpl");
	define("YOUTUBEERROR", "tpl/youtube/empty.tpl");

	$CFG->avatar_h = 100;
	$CFG->avatar_w = 100;

	$CFG->MAINPAGE = 'https://'.$_SERVER['SERVER_NAME'].'/';
	$CFG->AUTHPAGE = 'https://'.$_SERVER['SERVER_NAME'].'/auth';
	$CFG->USERPAGE = 'https://'.$_SERVER['SERVER_NAME'].'/profile';

	require(__DIR__. "/_cfg.php");


	require(__DIR__."/tools/db_mysqli.php");
	require(__DIR__."/tools/tools.php");
	require(__DIR__."/tools/common.php");

	define('DB_NAME', $CFG->DB_Name);
	define('DB_USER', $CFG->DB_User);
	define('DB_PASS', $CFG->DB_Password);
	define('DB_HOST', $CFG->DB_Host);

	$CFG->DB = new DB();
	$CFG->aSystemOptions = getSysInfo();


	require(__DIR__."/tools/class_pager.php");
	require(__DIR__."/init.php");

	function __autoload($name)
	{
		$name = strtolower($name);
		$file_path = realpath(dirname(__FILE__))."/modules/{$name}/class/{$name}.php";

		if (is_file($file_path))
			require_once($file_path);
		else
			if (is_file($CFG->xROOT_PATH.$file_path))
				require_once($CFG->xROOT_PATH.$file_path);
	}


	$CFG->STATUS = new Status();
	$CFG->FORM = new Form();
	$CFG->USER = new User();
	//$CFG->GAL = new gallery();


require_once(realpath(dirname(__FILE__))."/modules/mailsender/PHPMailerAutoload.php");
require_once(realpath(dirname(__FILE__))."/modules/PHPExcel/PHPExcel.php");
//require_once(realpath(dirname(__FILE__))."/modules/recaptchalib/class/recaptchalib.php");
require_once(realpath(dirname(__FILE__))."/modules/SMSc/SMSc.php");
require_once(realpath(dirname(__FILE__))."/modules/accounting/class/accounting.php");
require_once(realpath(dirname(__FILE__))."/modules/dompdf/dompdf_config.inc.php");
require_once(realpath(dirname(__FILE__))."/modules/speedometer/class/speedometer.php");
require_once(realpath(dirname(__FILE__))."/modules/gallery/class/AcImage.php");

function showHeader2($str, $align='right')
{
	echo "<h2 class=\"hdr\">{$str}&nbsp;</h1>\n";
}


function showHeader($str, $align='right')
{
	global $CFG;


	if ($CFG->mobile != "")
		showHeader2($str, $align='right');

//	echo "<h1 class=\"hdr_{$align}\">".hs($str)."</h1>\n";

	echo "<h1 class=\"hdr\">".hs($str)."</h1>\n";

	if ($_GET["sys"] == "profile")
		include("sys_profile_menu.php");
}


function DeleteDublicate($base)
{
	global $CFG;

	$tes = getSQLArrayO("SELECT id  FROM {$base} GROUP BY name order by id DESC");
	foreach($tes as $val)
	{
		$arr .= $val->id.',';
	}
	$test = trim($arr, ',');

	$query  = "DELETE FROM {$base} WHERE id NOT IN ( {$test} )	";
	$CFG->DB->query($query);

}




function prodazha_za_god($id)
{
	global $CFG;

	foreach ($id as  $value)
	{
		$in_id .= "'".$value."',";
	}
	$in_id = trim($in_id, ',');

	$start = date('Y-01-01 00:00:00');
	$off = date('Y-m-d H:i:s');

	//SELECT my_data_1c.id, SUM(my_data_1c_nomenclature.count) as count
	//FROM my_data_1c LEFT JOIN my_data_1c_nomenclature ON my_data_1c_nomenclature.id_product = my_data_1c.id_product
	//WHERE my_data_1c.visible='1' AND my_data_1c_nomenclature.id_product IN ('e3fece5f-3e42-11e9-80f6-1c1b0d3319f5', '1ad14c13-31cf-11ea-812e-1c1b0d3319f5')
	//AND (my_data_1c_nomenclature.cdate > '2021-01-01 00:00:00' AND my_data_1c_nomenclature.cdate < '2021-03-09 09:20:19')
	//GROUP BY my_data_1c_nomenclature.id_product


		$sql = getSQLArrayO( "SELECT my_data_1c.id, my_data_1c.id, SUM(my_data_1c_nomenclature.count) AS count FROM my_data_1c LEFT JOIN my_data_1c_nomenclature ON my_data_1c_nomenclature.id_product=my_data_1c.id_product
										WHERE my_data_1c.visible='1' AND my_data_1c.id_product IN ({$in_id}) AND (my_data_1c_nomenclature.cdate > '{$start}' AND my_data_1c_nomenclature.cdate < '{$off}') GROUP BY my_data_1c.id" );
	 foreach ($sql as $variable)
	 {
		 $up .= "('".$variable->id."', '".$variable->count."' ),".PHP_EOL;
	 }

	 if($up != '')
	 {
		 $up = substr($up, 0, -2);
		 $CFG->DB->query("INSERT INTO my_data_1c (id, sale_year) VALUES {$up} ON DUPLICATE KEY UPDATE id = VALUES(id), sale_year = VALUES(sale_year); ");
	 }
}


function coefficient_varchar($id)
{
	/*
	global $CFG;

	$date = new DateTime();
	$post = ['Дата' => $date->format('01-m-Y'), 'Id' => $id ];

	$url = "http://192.168.1.110:8081/fc_utp/hs/api/v1/ostatok";
	$login = 'webservice';
	$password = 'AsdfRewq!';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
	$result = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($result, true);


	$sum = array_reduce($data[0]['Остатки'], function ($a, $b)
	{
	    isset($a[$b['ID']]) ? $a[$b['ID']]['КоличествоОстаток'] += $b['КоличествоОстаток'] : $a[$b['ID']] = $b;
	    return $a;
	});

	$array = array_values($sum);


	foreach($array as $res)
	{
		//print_r($res);
		$id = $res['ID'];
		$response = getSQLRowO("SELECT SUM(count), price, total  FROM my_data_1c WHERE id_product = '{$id}' ");
		$est = $response->{'SUM(count)'};
		$bylo = $res['КоличествоОстаток'];
		$sum =   round(($bylo-$est)/$bylo, 3);

		//print_r($res);
		//print_r($response);

		// Среднемесячные остатки
		// Цена закуп (01) + Цена закуп (31) /2

		$p_z_01 = $res['ЗакупочнаяЦена']*$res['КоличествоОстаток'];
		$p_z_31 = $response->total*$response->{'SUM(count)'};

		$os = ($p_z_01+$p_z_31)/2;

		$n_m = $date->format('Y-m-01 00:00:00');
		//echo "SELECT SUM(price*count), SUM(price*count-purchase*count)  FROM my_data_1c_nomenclature WHERE id_product = '{$id}' AND cdate > '{$n_m}' ";
		$sp = getSQLRowO("SELECT SUM(price*count), SUM(price*count-purchase*count)  FROM my_data_1c_nomenclature WHERE id_product = '{$id}' AND cdate > '{$n_m}' ");
		$v_p = $sp->{'SUM(price*count-purchase*count)'};

		$sum = ($v_p/$os)*100;

		//echo '$id '.$id.PHP_EOL;
		//echo '$v_p '.$v_p.PHP_EOL;
		//echo '$os '.$os.PHP_EOL;
		//echo '$sum '.$sum.PHP_EOL.PHP_EOL.PHP_EOL;

		$CFG->DB->query("UPDATE my_data_1c SET coefficient = '{$sum}' WHERE id_product='{$id}' ");
	}
	*/
}





function showGenericBlock($block, $options="")
{
	if ($block->title != "")
	{
?>
<br /><span class='blockTitle'><?=hs($block->title)?></span>
<?
	}
?>
<p class='blockBody'><?=$block->body?></p>
<?
}


function block($obj)
{
	echo $obj->body;
	echo "<br />";
}


function frOpen($add="")
{
	if ($add == "")
		$add = "border='0'";
?>
<table cellpadding='0' cellspacing='0' border='0'<?=$add?>>
<tr>
	<td width='10' height='10'><img src='img/fr_03.gif' width='10' height='10' border='0' alt='' /></td>
	<td><img src='img/fr_04.gif' width='100%' height='10' border='0' alt='' /></td>
	<td width='10'><img src='img/fr_06.gif' width='10' height='10' border='0' alt='' /></td>
</tr>
<tr>
	<td background='img/fr_08.gif'><img src='img/fr_08.gif' width='10' height='100%' border='0' alt='' /></td>
	<td bgcolor='#FFFFFF'><?
}

function frClose()
{
?></td>
	<td background='img/fr_10.gif'><img src='img/fr_10.gif' width='10' height='100%' border='0' alt='' /></td>
</tr>
<tr>
	<td height='10'><img src='img/fr_13.gif' width='10' height='10' border='0' alt='' /></td>
	<td><img src='img/fr_14.gif' width='100%' height='10' border='0' alt='' /></td>
	<td><img src='img/fr_15.gif' width='10' height='10' border='0' alt='' /></td>
</tr>
</table>
<?
}

function frame($content, $add="")
{
	frOpen($add);
	echo $content;
	frClose();
}


function showHdr5($str)
{
?>
<table cellspacing='0' cellpadding='3' border='0' width='100%'>
<tr class='td_hdr4'>
	<td>&nbsp;</td>
	<td><?=$str?></a></td>
	<td>&nbsp;</td>
</tr>
</table>
<?
}



function ppOpen($add="")
{
return;
	$size = 10;
?>
<table border="0" cellspacing="0" cellpadding="0"<?=$add?>>
<tr>
	<td width="<?=$size?>" height="<?=$size?>"><img src="/images/c1.gif" width="<?=$size?>" height="<?=$size?>" border="0" /></td>
	<td bgcolor="#dddddd"></td>
	<td width="<?=$size?>" height="<?=$size?>"><img src="/images/c2.gif" width="<?=$size?>" height="<?=$size?>" border="0" /></td>
</tr>
<tr>
	<td width="<?=$size?>" bgcolor="#dddddd"></td>
	<td align="left" valign="top" bgcolor="#dddddd"><?
}

function ppClose()
{
return;
	$size = 10;
?></td>
	<td width="<?=$size?>" bgcolor="#dddddd"></td>
</tr>
<tr>
	<td width="<?=$size?>" height="<?=$size?>"><img src="/images/c4.gif" width="<?=$size?>" height="<?=$size?>" border="0" /></td>
	<td bgcolor="#dddddd"></td>
	<td width="<?=$size?>" height="<?=$size?>"><img src="/images/c3.gif" width="<?=$size?>" height="<?=$size?>" border="0" /></td>
</tr>
</table>
<?
}






function getFromCache($key, $user=0)
{
	return null;

	if (isset($_SESSION["cache"]["{$key}_{$user}"]))
	{
		return 	$_SESSION["cache"]["{$key}_{$user}"];
	}
	else
	{
		return null;
	}
}

function delFromCache($key, $user=0)
{
	unset($_SESSION["cache"]["{$key}_{$user}"]);
}

function putToCache($key, $data, $user=0)
{
	$_SESSION["cache"]["{$key}_{$user}"] = $data;
}

function clearCache()
{
	unset($_SESSION["cache"]);
}



function makePreviewName($url, $w, $h, $mode)
{
	if($url == '')
	{
		echo '/tpl/img/new/avatra.png';
	}
	else
	{

		$file_path = realpath(dirname(__FILE__)).$url;

		if (file_exists($file_path))
		{
			$w *= 1;
			$h *= 1;
			$mode *= 1;
			$l1 = explode(".", $url);
			$img = $l1[0];
			for ($i1=1; $i1<sizeof($l1)-1; $i1++)
			$img .= ".".$l1[$i1];
			$img .= "_thb_{$w}x{$h}x{$mode}.".$l1[ sizeof($l1)-1 ];
			$l = explode("/", $img);
			$img = $l[0];
			for ($i=1; $i<sizeof($l)-1; $i++)
			$img .= "/".$l[$i];
			$img .= "/thb/".$l[$i];
			$img = preg_replace("/.JPG/", ".jpg", $img);
			$img = preg_replace("/.jpeg/", ".jpg", $img);

			if (file_exists($img))
			{
				return $img;
			}
			else
			{
				return $url;
			}
		}
		else
		{
			return $url;
		}

	}
}



function makePreview($url, $w, $h, $mode)
{

		$file_path = realpath(dirname(__FILE__)).$url;


			$w *= 1;
			$h *= 1;
			$mode *= 1;
			$l1 = explode(".", $url);
			$img = $l1[0];
			for ($i1=1; $i1<sizeof($l1)-1; $i1++)
			$img .= ".".$l1[$i1];
			$img .= "_thb_{$w}x{$h}x{$mode}.".$l1[ sizeof($l1)-1 ];
			$l = explode("/", $img);
			$img = $l[0];
			for ($i=1; $i<sizeof($l)-1; $i++)
			$img .= "/".$l[$i];
			$img .= "/thb/".$l[$i];
			$img = preg_replace("/.JPG/", ".jpg", $img);
			$img = preg_replace("/.jpeg/", ".jpg", $img);


				return $img;


}




	function MoneyOperacions($price)
	{
	   switch($price)
	   {
			case $CFG->USER->USER_NOTE :
				echo 'Создана заметка к записи';
			break;

			case $CFG->USER->USER_RECORD :
				echo 'Создана запись';
			break;

			default:
				echo 'Самоначисление в записи';
			break;
	   }

	}




		function SelectData($name, $id, $lang = 1)
		{
			global $CFG;

			$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}{$name} WHERE  id='{$id}'");

			return $o->name;
		}


		function SelectDataName($name, $pole, $id)
		{
			global $CFG;

			$o = getSQLRowO("SELECT {$pole} FROM {$CFG->DB_Prefix}{$name} WHERE id='{$id}'");

			return $o->$pole;
		}

		function SelectDataNameArray($name, $pole, $id)
		{
			global $CFG;

			$o = getSQLRowO("SELECT {$pole} FROM {$CFG->DB_Prefix}{$name} WHERE id='{$id}'");

			return $o;
		}


	function SelectData_live($name, $id)
	{
		global $CFG;

		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}{$name} WHERE id='{$id}'");

		return $o->name;
	}

	function SelectDataParent($name, $parent, $id, $order = 'name ASC',  $visible = 1)
	{
		global $CFG;

		if($visible == 1)
			$visib = "and visible=1";

		$o = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}{$name} WHERE {$parent}='{$id}' {$visib}  order by {$order}");

		return $o;
	}


	function SelectDataCount($name, $fild, $id)
	{
		global $CFG;

		$o = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}{$name} WHERE visible='1' and {$fild}='{$id}'");

		return count($o);
	}


	function SelectDataRowOArray($name, $id, $lang = 1, $visible = 1)
	{
		global $CFG;

		if($visible == 1)
			$visible = "visible=1";

		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}{$name} WHERE id='{$id}' and sys_language='{$lang}' '{$visible}'");

		return $o;
	}


	function SelectDataArray($name, $lang = 1, $order = 'id ASC')
	{
		global $CFG;

		$o = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}{$name} WHERE visible='1' and sys_language='{$lang}' order by {$order}");

		return $o;
	}

	function DataArray($name)
	{
		global $CFG;

		$o = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}{$name}");

		return $o;
	}

	function AndDataArray($name, $and, $value, $lang = 1, $order = 'id ASC')
	{
		global $CFG;

		$o = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}{$name} WHERE {$and}='{$value}' order by {$order}");

		return $o;
	}



	function AndDataArrayBody($name, $and, $value,  $order = 'id ASC')
	{
		global $CFG;


		$sql = "SELECT * FROM {$CFG->DB_Prefix}{$name} WHERE {$and}='{$value}' order by {$order}";

		$o = getSQLArrayO($sql);

		return $o;
	}


	function SelectDataRowOMonth($year, $month, $id)
	{
		global $CFG;

		$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$real_month =  date("m",mktime(0,0,0,$month,1, $year));

		$response = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}money_list WHERE visible = 1 AND user_id > 0 AND manager_id = '{$id}' AND (cdate >= '{$year}-{$real_month}-01 00:00:00') AND (cdate <= '{$year}-{$real_month}-{$number} 23:59:59') ");

		$query = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}money_minus_list WHERE visible = 1 AND user_id > 0  AND manager_id = '{$id}' AND (cdate >= '{$year}-{$real_month}-01 00:00:00') AND (cdate <= '{$year}-{$real_month}-{$number} 23:59:59') ");

		if($response->{'SUM(count)'})
			$res .= number_sum($response->{'SUM(count)'});
		else  $res .= 0;

		if($query->{'SUM(count)'})
			$res .= '<span>-'.number_sum($query->{'SUM(count)'}).'</span>';
		else  $res .= '<span>-0</span>';

		return $res;
	}




	function SelectDataRowOMonthHead($year, $month, $id)
	{
		global $CFG;

		$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		$response = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}money_list WHERE visible = 1 AND user_id = '{$id}' AND (cdate >= '{$year}-{$month}-01 00:00:00') AND (cdate <= '{$year}-{$month}-{$number} 23:59:59') ");

		$query = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}money_minus_list WHERE visible = 1 AND manager_id = '{$id}' AND (cdate >= '{$year}-{$month}-01 00:00:00') AND (cdate <= '{$year}-{$month}-{$number} 23:59:59') ");

		if($response->{'SUM(count)'})
			$res .= $response->{'SUM(count)'};

		if($query->{'SUM(count)'})
			$res .= '<span>-'.$query->{'SUM(count)'}.'</span>';

		return $res;
	}




	function SelectDataRowOMonthSumPlus($year, $month, $id)
	{
		global $CFG;

		$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$real_month =  date("m",mktime(0,0,0,$month,1, $year));

		$response = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}money_list WHERE visible = 1 AND user_id > 0 AND manager_id = '{$id}' AND (cdate >= '{$year}-{$real_month}-01 00:00:00') AND (cdate <= '{$year}-{$real_month}-{$number} 23:59:59') ");

		if($response->{'SUM(count)'})
			$res .= $response->{'SUM(count)'};
		else  $res .= 0;

		return $res;
	}


	function SelectDataRowOMonthSumMinus($year, $month, $id)
	{
		global $CFG;

		$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$real_month =  date("m",mktime(0,0,0,$month,1, $year));

		$query = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}money_minus_list WHERE visible = 1 AND user_id > 0 AND  manager_id = '{$id}' AND (cdate >= '{$year}-{$real_month}-01 00:00:00') AND (cdate <= '{$year}-{$real_month}-{$number} 23:59:59') ");

		if($query->{'SUM(count)'})
			$res .= $query->{'SUM(count)'};

		return $res;
	}




	function coder($value)
	{
		header ("Content-type: text/html; charset=utf-8");

		return $value;
	}


	function mailer($email, $subject, $body  )
	{
		/*
		$headers = "From: Информационная «База клиентов» <support@led.ru> \r\n" .
			"Reply-To: support@led.ru \r\n" .
			'X-Mailer: PHP/' . phpversion();
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

		if (mail($email, $subject, $body, $headers))
		{
			return true;
		}
		else
		{
			return false;
		}

		*/

		$mail = new PHPMailer;
		$mail->addAddress($email);
		$mail->Subject = $subject;
		$mail->Body    = $body;

		if($mail->send())
			return true;
		else
			return false;
	}

	function money($id, $count = 0, $page_id = 0, $type = 0, $coment_id = 0, $userpremium = 0)
	{
		global $CFG;

		$year = date('Y')*1;
		$month = date('m')*1;
		$cdate = sqlDateNow();

		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_{$year} WHERE user_id='{$id}'");

		$money = $o->{'money_'.$month};

		$in = $money + $count;

		if(count($o))
		{
			$res  = "UPDATE {$CFG->DB_Prefix}money_{$year} SET money_{$month}='{$in}' WHERE user_id='{$id}'";
			$CFG->DB->query($res);
		}
		else
		{
			$sql = "INSERT INTO {$CFG->DB_Prefix}money_{$year} (user_id, money_{$month}, visible) VALUES ('{$id}', '{$in}', 1)";
			$CFG->DB->query($sql);
		}

		$sql = "INSERT INTO {$CFG->DB_Prefix}money_list (user_id, manager_id, count, cdate, page_id, type, coment_id, visible) VALUES ('{$userpremium}', '{$id}', '{$count}', '{$cdate}', '{$page_id}', '{$type}', '{$coment_id}', 1)";
		$CFG->DB->query($sql);

		return $o;
	}




	function money_count($id)
	{
		global $CFG;

		$year = date('Y')*1;
		$month = date('m');
		$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		$o = getSQLRowO("SELECT SUM(count) FROM my_money_list WHERE visible = 1 AND manager_id = '{$id}' AND (cdate >= '{$year}-{$month}-01 00:00:00') AND (cdate <= '{$year}-{$month}-{$number} 23:59:59') ");

		if($o->{'SUM(count)'})
			return $o->{'SUM(count)'};
		else
			return 0;

	}

	function money_count_minus($id)
	{
		global $CFG;


		$year = date('Y')*1;
		$month = date('m');
		$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		$o = getSQLRowO("SELECT SUM(count) FROM {$CFG->DB_Prefix}money_minus_list WHERE visible = 1 AND manager_id = '{$id}' AND (cdate >= '{$year}-{$month}-01 00:00:00') AND (cdate <= '{$year}-{$month}-{$number} 23:59:59') ");
		if($o->{'SUM(count)'})
			return $o->{'SUM(count)'};
		else
			return 0;
	}


	function money_delete($id, $count)
	{
		global $CFG;

		$year = date('Y')*1;
		$month = date('m')*1;
		$cdate = sqlDateNow();

		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_{$year} WHERE user_id='{$id}'");

		$money = $o->{'money_'.$month};

		$in = $money - $count;

		$res  = "UPDATE {$CFG->DB_Prefix}money_{$year} SET money_{$month}='{$in}' WHERE user_id='{$id}'";
		$CFG->DB->query($res);

		return true;

	}





	function Premium_Plus($array_id, $coment_id)
	{
		global $CFG;

		$premium_id = explode(",", $array_id);

		for ($z=0; $z<sizeof($premium_id); $z++)
		{
			if(is_numeric($premium_id[$z]))
			{
				$res  = "UPDATE {$CFG->DB_Prefix}money_list SET visible='1', coment_id = '{$coment_id}' WHERE id='{$premium_id[$z]}'";

				if($CFG->DB->query($res))
				{
					$manager_id = SelectDataRowOArray('money_list', $premium_id[$z]);
					$sendUser = SelectDataRowOArray('users', $manager_id->manager_id, 0);

					$itg = $manager_id->count-$CFG->USER->USER_NOTE;
					$subject = "Вам начислили плюс в системе SIGNIMPRESS!";
					$body    = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile/view/'.$sendUser->id.'/" target="_blank"><strong>'.$sendUser->name.'</strong></a>, Вам начислили плюс : <strong>'.$itg.' '.$CFG->USER->USER_CURRENCY.'.</strong><br>=====================================================<br> Запись <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$manager_id->page_id.'/#comment-post_'.$manager_id->coment_id.'" target="_blank"> *'.$manager_id->page_id.'</a>.';
					mailer($sendUser->email, $subject, $body );
				}
			}
			else
				 continue;
		}

		return true;

	}


	function Premium_Minus($array_id, $coment_id)
	{
		global $CFG;

		$premium_id = explode(",", $array_id);

		for ($z=0; $z<sizeof($premium_id); $z++)
		{
			if(is_numeric($premium_id[$z]))
			{
				$res  = "UPDATE {$CFG->DB_Prefix}money_minus_list SET visible='1', coment_id = '{$coment_id}' WHERE id='{$premium_id[$z]}'";

				if($CFG->DB->query($res))
				{
					$manager_id = SelectDataRowOArray('money_minus_list', $premium_id[$z]);
					$sendUser = SelectDataRowOArray('users', $manager_id->manager_id, 0);

					$subject = "Вам выписали минус в системе SIGNIMPRESS!";
					$body    = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile/view/'.$sendUser->id.'/" target="_blank"><strong>'.$sendUser->name.'</strong></a>, Вам выписали минус : <strong>'.$manager_id->count.' '.$CFG->USER->USER_CURRENCY.'.</strong><br>=====================================================<br> Запись <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$manager_id->page_id.'/#comment-post_'.$manager_id->coment_id.'" target="_blank"> *'.$manager_id->page_id.'</a>.';
					mailer($sendUser->email, $subject, $body );
				}
			}
			else
				 continue;
		}

		return true;

	}


	function Send_Inform($array_id, $id)
	{
		global $CFG;

		$array = explode(",", $array_id);

		if(count($array) > 0)
		{
			for ($z=0; $z<sizeof($array); $z++)
			{
				$sendUser = SelectDataRowOArray('users', $array[$z], 0);

				$subject = 'Извещение о новой заметки к записи *'.$id.'';
				$body    = 'Вам пришло уведомление о новой заметки к записи <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$id.'" target="_blank"> *'.$id.'</a>.';

				mailer($sendUser->email, $subject, $body );
			}

		}

		return true;
	}



	function logo_company($url)
	{
		if($url)
		{
                if (!file_exists($url))
                {
                    echo '<div class="file-galery"><a href="'.$url.'" target="_blank"><img src="'.$url.'"></a></div>';
                }
                else
                {
                    	echo '<img src="/tpl/img/new/avatra.png">';
                }

		}
		else
		{
			echo '<img src="/tpl/img/new/avatra.png">';
		}
	}

	function logo_face($url)
	{
		if($url)
		{
      if (!file_exists($url))
      {
          echo '<img src="'.$url.'">';
      }
      else
      {
          	echo '<img src="/tpl/img/new/avatra.png">';
      }
		}
		else
		{
			echo '<img src="/tpl/img/new/avatra.png">';
		}
	}



            $img = explode(",", $o->attachments_image);

            if(count($img) > 1)  echo '<div class="hrs"></div>';

            for ($y=0; $y<sizeof($img); $y++)
            {
                if($img[$y] == "" || $img[$y] == "0") continue;

                $img_url = str_replace('big', 'med', $img[$y]);

                $filename = '.'.$img_url;

                if (file_exists($filename))
                {
                    echo '<div class="file-galery"><a href="'.$img[$y].'" target="_blank"><img src="'.$img_url.'"></a></div>';
                }
                else
                {
                    echo '<div class="file-galery"><a href="'.$img[$y].'" target="_blank"><img src="'.$img[$y].'"></a></div>';
                }

            }



	function heshtag($text)
	{
		$result = preg_replace("/#([\S]+)/", "<a href='/record/?search=\\1' target='_blank' class='heshtag'>#\\1</a>", $text);

		return $result;
	}



	function heshtag_list($text)
	{
		$hashtags= FALSE;

		preg_match_all("/(##\w+)/u", $text, $matches);

		if ($matches[0])
		{

			$hashtags = $matches[0];


			foreach ( $hashtags as $value )
			{

				$arr->item = $value;

				$test.= mb_strtolower($arr->item);
			}
		}

	  	return $test;
	}

	function heshtag_list_type($text, $type)
	{
		$hashtags= FALSE;

		preg_match_all($type, $text, $matches);

		if ($matches) {
			$hashtagsArray = array_count_values($matches[0]);
			$hashtags = array_keys($hashtagsArray);
		}

		foreach ( $hashtags as $value )
		{

			$arr->item = $value;

			$test.= mb_strtolower($arr->item);
		}

		return $test;
	}

	function number_sum($sum)
	{
		$result = number_format($sum, 0, ' ', ' ');
		return $result;
	}

	function deteleComent($id, $date, $autorId, $type_company_id = '', $director_id = 0)
	{
		global $CFG;

		if(($CFG->USER->USER_ID == 85) or ($CFG->USER->USER_ID == 1)  or ($director_id == $CFG->USER->USER_ID) )
		{
			$url = "<a href='#' data-id='{$id}' class='detelecoment'>DELETE</a>";
		}
		elseif($type_company_id == 555 && $autorId == $CFG->USER->USER_ID)
		{
			$url = "<a href='#' data-id='{$id}' class='detelecoment'>DELETE</a>";
		}
		else
		{
			if($autorId == $CFG->USER->USER_ID)
			{
				$date = strtotime($date);

				if($date + 3600 > time())
				{
					$url = "<a href='#' data-id='{$id}' class='detelecoment'>DELETE</a>";
				}
			}
		}

		return $url;
	}


	/*  Получаем id задач принадлежащих userУ */
	function ExplodeUsersComment($users)
	{
		global $CFG;

		$sql = getSQLArrayO("SELECT id,doer FROM {$CFG->DB_Prefix}comments WHERE visible='1' AND task='1' ");

		for ($y=0; $y<sizeof($sql); $y++)
		{
			if($sql[$y]->doer == "0") continue;

			$cat_id = explode(",", $sql[$y]->doer);

			for ($x=0; $x<sizeof($cat_id); $x++)
			{
				$res = $cat_id[$x];

				if($res == $users)
				{
					$id .= $sql[$y]->id.',';
				}
			}
		}

		$idS = trim($id, ",");
		if($idS == !"")
			$newsIdAnd .= " AND id in({$idS}) ";

		return $newsIdAnd;
	}





	function rec_warehouse($warehouse_text, $table)
	{
		global $CFG;
		//if($warehouse_text == '') continue;
		$sql = "REPLACE INTO {$table} (name, visible)	VALUES ('{$warehouse_text}', 1)";
		$CFG->DB->query($sql);
	}

		function rec_warehouse_id($warehouse_text, $table)
		{
			global $CFG;
			$html_warehouse_text = htmlspecialchars($warehouse_text);
			$res = getSQLRowO("SELECT id FROM {$table} WHERE visible='1' AND name LIKE '%{$html_warehouse_text}%' ");
			return $res->id;
		}

		function rec_warehouse_bin($warehouse_text, $table)
		{
			global $CFG;
			$res = getSQLRowO("SELECT id FROM {$table} WHERE visible='1' AND bin = '{$warehouse_text}' ");
			return $res->id;
		}



	/*  Получаем id задач принадлежащих userУ */
	function ExplodeArray($array)
	{
		global $CFG;

		$array_id = explode(",", $array);

		for ($y=0; $y<sizeof($array_id); $y++)
		{
			if($array_id == "0") continue;

			$res = SelectDataRowOArray('users', $array_id[$y], 0);

			if($res)
			{
				for ($i=0; $i<sizeof($res); $i++)
				{
					$url = '<a href="/profile/view/'.$res->id.'/#view-notes" target="_blank" class="observer">'.$res->name.'</a> ';
					echo $url;
				}
			}
		}

	}

	function AccessAlertt($type)
	{
		global $CFG;

		$sql = getSQLRowO("SELECT COUNT(my_news.id) as conts, my_news.page_id AS page_ids, {$type}.page_id, {$type}.user_id, {$type}.id, {$type}.parent_id
										FROM {$type} LEFT JOIN my_news ON my_news.id={$type}.page_id WHERE {$type}.visible='1' AND {$type}.view= 0 AND {$type}.user_id='{$CFG->USER->USER_ID}' ORDER BY {$type}.cdate DESC");

		if($sql->page_ids == 976)
		{
			$url = 'office';
		}

		if($sql->page_ids == 868)
		{
			$url = 'record';
		}

		if($sql->page_ids == 1000)
		{
			$url = 'deal';
		}

		$file_path = realpath(dirname(__FILE__))."/tpl/templates/system/{$type}.tpl";

		if(count($sql) > 0)
		{
			include($file_path);

			return true;
		}
		else
			return false;
	}





	function AccessAlert($type)
	{
		global $CFG;

		$sql = getSQLRowO("SELECT count(my_accessrecord.id) as counts, my_news.page_id AS page_ids, {$type}.page_id, {$type}.user_id, {$type}.id, {$type}.parent_id
										FROM {$type} LEFT JOIN my_news ON my_news.id={$type}.page_id WHERE {$type}.visible='1' AND {$type}.view= 0 AND {$type}.autor_id='{$CFG->USER->USER_ID}' ORDER BY {$type}.cdate DESC");

		if($sql->page_ids == 976)
		{
			$url = 'office';
		}

		if($sql->page_ids == 868)
		{
			$url = 'record';
		}

		if($sql->page_ids == 1000)
		{
			$url = 'deal';
		}

		$file_path = realpath(dirname(__FILE__))."/tpl/templates/system/{$type}.tpl";

		if(count($sql) > 0)
		{
			include($file_path);

			return true;
		}
		else
			return false;
	}



	function replycommentAlert()
	{
		global $CFG;

		$data =  getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}replycomment WHERE user_id='{$CFG->USER->USER_ID}' and visible= 1 and view = 0  order by cdate DESC");

		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news{$year} WHERE id='{$data[0]->page_id}'");

		if($o->page_id == 976)
		{
			$url = 'office';
		}

		if($o->page_id == 868)
		{
			$url = 'record';
		}

		if($o->page_id == 1000)
		{
			$url = 'deal';
		}

		$file_path = realpath(dirname(__FILE__))."/tpl/templates/system/replycommentAlert.tpl";

		if(count($data) > 0)
		{
			include($file_path);

			return true;
		}
		else
			return false;
	}



	function cashback_go()
	{
		global $CFG;

		if($CFG->USER->USER_ID == 547)
		{
			$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE status = 0 AND visible = 1 order by cdate DESC");

			$res = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$o->mobile}%' AND visible='1' ");

			if(count($o) > 0)
			{	?>

				<script type="text/javascript">

					function cashback_go()
					{
						$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
						$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Одобрите выдачу кешбека.</center></h4></div>');
						$(".modal-body").append('<h4 class="modal-title"><center><a href="/profile/view/<?=$o->user_id;?>"><? echo  SelectData('users', $o->user_id, 0);?></a> запрашивает выдачу кешбека в размере <?=$o->price;?> <?=$CFG->USER->USER_CURRENCY;?>, для клиента <a target="_blank" href="<?=getFullXCodeByPageIdUrl($res->page_id).$res->page_id?>"><?=$o->mobile;?></a></center></h4>');

						$(".modal-body").append('<center><button style="margin-top:10px;" type="button" data-id="1" class="btn hover">Одобрить кешбек</button>	<button style="margin-top:10px;" type="button" data-id="2" class="btn hover">НЕ одобрить кешбек</button></center>');

						$('.btn.hover').on('click', function(e)
						{
							var data_id = $(this).attr('data-id');

							$.ajax
							({
								url: "/static/cashback_go/",
								type: "GET",
								data: {"id": <?=$o->id;?>, "type": data_id},
								cache: true,
									beforeSend: function()
									{
										$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
									},
									success: function(response)
									{
											window.location.reload();
									}
							});
						});
					}
					setTimeout('cashback_go()', 1000);
				</script>
		<? }
		}
	}


	function cashback_go_tu()
	{
		global $CFG;

		if($CFG->USER->USER_ID == 85)
		{
			$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE status = 2 AND visible = 1 order by cdate DESC");

			$res = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$o->mobile}%' AND visible='1' ");

			if(count($o) > 0)
			{	?>

				<script type="text/javascript">

					function cashback_go()
					{
						$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
						$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Одобрите выдачу кешбека.</center></h4></div>');
						$(".modal-body").append('<h4 class="modal-title"><center><a href="/profile/view/<?=$o->user_id;?>"><? echo  SelectData('users', $o->user_id, 0);?></a> запрашивает выдачу кешбека в размере <?=$o->price;?> <?=$CFG->USER->USER_CURRENCY;?>, для клиента <a target="_blank" href="<?=getFullXCodeByPageIdUrl($res->page_id).$res->page_id?>"><?=$o->mobile;?></a></center></h4>');

						$(".modal-body").append('<center><button style="margin-top:10px;" type="button" data-id="1" class="btn hover">Одобрить кешбек</button>	<button style="margin-top:10px;" type="button" data-id="2" class="btn hover">НЕ одобрить кешбек</button></center>');

						$('.btn.hover').on('click', function(e)
						{
							var data_id = $(this).attr('data-id');

							$.ajax
							({
								url: "/static/cashback_go_tu/",
								type: "GET",
								data: {"id": <?=$o->id;?>, "type": data_id},
								cache: true,
									beforeSend: function()
									{
										$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
									},
									success: function(response)
									{
										window.location.reload();
									}
							});
						});
					}
					setTimeout('cashback_go()', 1000);
				</script>
		<? }
		}

	}


	function cashback_go_three()
	{
		global $CFG;

		if($CFG->USER->USER_ID == 565)
		{
			$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE status = 4 AND visible = 1 order by cdate DESC");


			$res = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$o->mobile}%' AND visible='1' ");

			if(count($o) > 0)
			{	?>

				<script type="text/javascript">

					function cashback_go_three()
					{
						$("#myModalBox").modal('show');
						$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Выдайте кешбек клиенту <a href="<?=getFullXCodeByPageIdUrl($res->page_id).$res->page_id?>"><?=$o->mobile;?></a>.<br> Сумма к получению <?=$o->price;?> <?=$CFG->USER->USER_CURRENCY;?></center></h4></div>');

						$(".modal-body").append('<p>Комментарий</p>');
						$(".modal-body").append('<p><textarea class="form-control text" rows="2" style="margin-bottom:15px;"></textarea></p>');
						$(".modal-body").append('<p><button type="submit" data-id="1" class="btn btn-primary submit">Отправить</button> <button data-id="2" type="submit" class="btn btn-primary submit">Отложить</button></p>');

						$('.btn.submit').live('click', function(e)
						{
							var data_id = $(this).attr('data-id');

							var textarea = $('.form-control.text').val();

							$.ajax
							({
								url: "/static/cashback_go_three/",
								type: "POST",
								data: {"id": <?=$o->id;?>, "text": textarea, "mobile": '<?=$o->mobile?>', "price": '<?=$o->price?>', "type": data_id},
								cache: true,
									beforeSend: function()
									{
										$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
									},
									success: function(response)
									{
										$('.content').load(location.href + '/json');
										$("#myModalBox").modal('hide');
										$(".modal-body").html('');


									}
							});
						});
					}
					setTimeout('cashback_go_three()', 1000);
				</script>
		<? }
		}

	}



	function my_alarm_deal()
	{
		global $CFG;

				$ii = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}news WHERE open = 2 AND visible = 1 AND page_id = 1000 order by cdate DESC");

				$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE open = 2 AND visible = 1 AND page_id = 1000 order by cdate DESC");
				$u = getSQLRowO("SELECT user_id FROM {$CFG->DB_Prefix}users WHERE id = {$o->manager_id}  ");

		if(count($o) > 0 && $CFG->USER->USER_ID == $u->user_id)
		{	?>

      <script type="text/javascript">

				function my_alarm_deal()
				{
						$('#myModalBox').modal({backdrop: 'static', keyboard: false});

						$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Одобрите сделку - (<?=count($ii);?>)</center></h4></div>');
						$(".modal-body").append('<h4 class="modal-title"><center>Сделка: <a href="/deal/<?=$o->id;?>"><?=$o->name_company;?> *<?=$o->id;?></a></center></h4>');
				}
				setTimeout('my_alarm_deal()', 1000);
			</script>
	<? }

	}


	function my_alarm()
	{
		global $CFG;

		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}alarm WHERE user_id='{$CFG->USER->USER_ID}' AND visible = 0 order by cdate ASC");

		if(count($o) > 0)
		{	?>

      <script type="text/javascript">

				function my_alarm()
				{
					$.ajax
					({
						url: "/static/my_alarm/",
						type: "POST",
						data: {"user_id": <?=$CFG->USER->USER_ID;?>},
						cache: true,
						success: function(response)
						{
							if(response == 0)
							{

							}
							else
							{
								response = $.parseJSON(response);

								$('#myModalBox').modal({backdrop: 'static', keyboard: false});

								$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>' + response.autor + '</center></h4></div>');
								$(".modal-body").append('<h4 class="modal-title"><center>' + response.text + '</center></h4>');
							}
						}
					});
				}
				setTimeout('my_alarm()', 1000);
			</script>
	<? }

	}




	function replycommentIMG($state, $state_name, $state_view)
	{
		global $CFG;

		if($state != NULL)
		{
			$stateEX = explode(",", $state);
			$stateEXNA = explode(",", $state_name);
			$stateEXVI = explode(",", $state_view);

			echo '<br clear="all">';
			$cnt = 0;
			foreach ($stateEX as $value)
			{

				?>
					<a href="/profile/view/<?=$value;?>" target="_blank"><?=$stateEXNA[$cnt];?></a>
					<img src="/tpl/img/respons/inform/<?=$stateEXVI[$cnt];?>.png" style="width:15px">
				<?
				$cnt++;
			}


		}





		/*
		$sql = getSQLArrayO("SELECT autor_id,view  FROM {$CFG->DB_Prefix}accessrecord WHERE page_id='{$page_id}' AND parent_id = {$id_coment} ");

		if(count($sql) >= 1)
		{
			echo '<br clear="all">';
			for ($x=0; $x<sizeof($sql); $x++)
			{

				echo "<a href='/profile/view/".$sql[$x]->autor_id."' target='_blank'>".SelectData('users', $sql[$x]->autor_id, 0)."</a> <img src='/tpl/img/respons/inform/".$sql[$x]->view.".png' title='' style='width:15px'> ";
			}
		}
		*/

	}


	function preg_mobile($mobile)
	{
		$tel = preg_replace('~[^0-9]+~','',$mobile);

		$tel = '<a href="tel:+'.$tel.'">'.$mobile.'</a>';

		return $tel;
	}



	function exceptions($user_id, $id, $type)
	{
		global $CFG;

		$sql = getSQLRowO("SELECT taks_id, user_id FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}' ");

		if($type == 2147483647)
		{
			if(($sql->taks_id == $id) || ($sql->user_id == $CFG->USER->USER_ID) || ($CFG->USER->USER_ID == 1) || ($CFG->USER->USER_ID == 86) || ($CFG->USER->USER_ID == 87) || ($CFG->USER->USER_ID == 246) || ($CFG->USER->USER_ID == 332) || ($CFG->USER->USER_ID == 339) || ($CFG->USER->USER_ID == 369) || ($CFG->USER->USER_ID == 430) || ($CFG->USER->USER_ID == 133) || ($CFG->USER->USER_ID == 468) )
			{
				$status = true;
			}
			else
			{
				$status = false;
			}
		}
		else
		{
			$status = true;
		}

		return $status;
	}


	function big_access($type)
	{
		global $CFG;

		$user_id = $CFG->USER->USER_ID;

		$sql = getSQLRowO("SELECT $type FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}' ");

		if($sql->{$type} == 1)
		{
			$status = true;
		}
		else
		{
			$status = false;
		}

		return $status;

	}

	function big_access_record($id)
	{
		global $CFG;

		$user_id = $CFG->USER->USER_ID;

		$sql = getSQLRowO("SELECT access_deal FROM {$CFG->DB_Prefix}news WHERE id='{$id}' ");

		if($sql->access_deal == NULL)
		{
			return 1;
		}
		else
		{
			$trio = explode(",", $sql->access_deal);

			$key = array_search($user_id, $trio);

			if(empty($key) || (empty($key) && $key == 0))
			{
				//print_r($key); exit;
				return 0;
			}
			else
			{
				return 1;
			}

		}
	}


	function exceptions_money($user_id)
	{
		global $CFG;

		$sql = getSQLRowO("SELECT taks_id, user_id FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}' ");

		if(($user_id == $CFG->USER->USER_ID) || ($sql->user_id == $CFG->USER->USER_ID) || ($CFG->USER->USER_ID == 1) || ($CFG->USER->USER_ID == 86) || ($CFG->USER->USER_ID == 87) || ($CFG->USER->USER_ID == 246) || ($CFG->USER->USER_ID == 332)  || ($CFG->USER->USER_ID == 369)  || ($CFG->USER->USER_ID == 339) || ($CFG->USER->USER_ID == 430) || ($CFG->USER->USER_ID == 133) || ($CFG->USER->USER_ID == 468) )
		{
			$status = true;
		}
		else
		{
			$status = false;
		}

		return $status;
	}





	function translit($str)
	{

	  if($_COOKIE['site_content'] == 'en')
	  {
		$rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
		return str_replace($rus, $lat, $str);
	  }
	  else
		return $str;
	}

function isSerialized($s)
{
	if(
		stristr($s, '{' ) != false &&
		stristr($s, '}' ) != false &&
		stristr($s, ';' ) != false &&
		stristr($s, ':' ) != false
		){
		return true;
	}
	else
	{
		return false;
	}
}


function getFullXCodeByPageIdUrl($id)
{
	global $CFG;

	$o = getSQLRowO("SELECT page_id FROM {$CFG->DB_Prefix}news WHERE id='{$id}'");

	$page = getSQLRowO("SELECT xcode FROM {$CFG->DB_Prefix}pages WHERE id='{$o->page_id}'");

	$url = '/'.$page->xcode.'/';

	if($o->page_id > 0)
	{
		return $url;
	}
	else
		return false;
}



function ifelsrialize($data)
{

	if(isSerialized($data))
	{
		$white = unserialize($data);
		return $white;
	}
	else
	{
		$res[] = $data;
		return $res;
	}

}


function data_my_cashback($res)
{
	global $CFG;

	if($res != '')
	{
		echo $res;

		$sql = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cashback_history WHERE body LIKE '%{$res}%' order by cdate DESC");

		echo ', '.dateSQL2TEXT($sql->cdate, " DD MN YYYY, hh:mm");

		 $sql = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE name_client_mobile LIKE '%{$res}%' AND visible='1' AND page_id = 868  order by cdate DESC");

		if($sql->id > 0)
		{
			$name = unserialize($sql->name_client);
			echo ' <a href="/record/'.$sql->id.'" target="_blank">'.$name[0].'</a>';
		}

		echo '<br>';


	}
}


function big_search_real_face($page_id, $search)
{
	global $CFG;

	$search_where = "AND (name LIKE '%{$search}%' OR mobile LIKE '%{$search}%' OR email LIKE '%{$search}%' OR whatsapp LIKE '%{$search}%' OR name_other LIKE '%{$search}%' OR info LIKE '%{$search}%' )";
	$sql = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}face WHERE visible='1' AND page_id = 1012 {$search_where} order by id ");
	for ($i=0; $i<sizeof($sql); $i++)
	{
		$pid[] .= $sql[$i]->id;
	}


	$com = getSQLArrayO("SELECT page_id FROM {$CFG->DB_Prefix}comments WHERE text LIKE '%{$search}%' AND visible='1' AND parent_id = 1012 AND user_id order by id ");
	for ($i=0; $i<sizeof($com); $i++)
	{
		$pid[] .= $com[$i]->page_id;
	}

	$res = array_unique($pid);


	$res = array_values($res);

	return $res;
}


function big_search_tho($page_id, $search)
{
	global $CFG;

	$search_where = "AND (name_company LIKE '%{$search}%' OR history LIKE '%{$search}%' OR contact LIKE '%{$search}%' OR insta LIKE '%{$search}%' OR info LIKE '%{$search}%')";

	$sql = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}news WHERE visible='1' {$search_where} AND page_id = '{$page_id}' order by cdate DESC");
	for ($i=0; $i<sizeof($sql); $i++)
	{
		$pid[] .= $sql[$i]->id;
	}

	$com = getSQLArrayO("SELECT page_id FROM {$CFG->DB_Prefix}comments WHERE text LIKE '%{$search}%' AND visible='1' AND parent_id = '{$page_id}'  order by cdate DESC");
	for ($i=0; $i<sizeof($com); $i++)
	{
		$pid[] .= $com [$i]->page_id;
	}

	$res = array_unique($pid);
	$res = array_values($res);

	return $res;
}


function big_search($page_id, $search)
{
	global $CFG;

			$zapros="SELECT id FROM my_news WHERE MATCH (name_company, data_name, data_mobile, data_email, data_other, history, contact, insta, info) AGAINST ('{$search}' IN BOOLEAN MODE) AND page_id = {$page_id} order by cdate DESC limit 5";
           	$sql = getSQLArrayO($zapros);
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pid[] .= $sql[$i]->id;
            }

			$com = getSQLArrayO("SELECT page_id FROM my_comments WHERE MATCH (text) AGAINST ('{$search}' IN BOOLEAN MODE) AND visible='1' AND parent_id = {$page_id}  order by cdate DESC limit 5");

            for ($i=0; $i<sizeof($com); $i++)
            {
                $pid[] .= $com   [$i]->page_id;
            }

            $res = array_unique($pid);


			$res = array_values($res);

	return $res;
}




function big_search_count($page_id, $search)
{
	global $CFG;

	$zapros="SELECT id FROM my_news WHERE MATCH (name_company, data_name, data_mobile, data_email, data_other, history, contact, insta, info) AGAINST ('{$search}' IN BOOLEAN MODE) AND page_id = {$page_id} order by cdate DESC";
	$sql = getSQLArrayO($zapros);
	for ($i=0; $i<sizeof($sql); $i++)
	{
		$pid[] .= $sql[$i]->id;
	}

	$com = getSQLArrayO("SELECT page_id FROM my_comments WHERE MATCH (text) AGAINST ('{$search}' IN BOOLEAN MODE) AND visible='1' AND parent_id = {$page_id}  order by cdate DESC");

	for ($i=0; $i<sizeof($com); $i++)
	{
		$pid[] .= $com   [$i]->page_id;
	}

	$res = array_unique($pid);
	$res = array_values($res);

	for ($i=0; $i<sizeof($res); $i++)
	{
	   $data = SelectDataRowOArray("news", $res[$i]);
	   if($data->id == !'' && $data->page_id == $page_id)
		{
			$array[] .= $data->id;
		}
	}

	return count($array);
}

function whatsapp_send($data, $url)
{
	$json = json_encode($data);

	$options = stream_context_create(['http' => [
	'method'  => 'POST',
	'header'  => 'Content-type: application/json',
	'content' => $json]]);

	$result = file_get_contents($url, false, $options);
	return $result;
}


function whatsapp_send_test($data, $url)
{
	$json = json_encode($data);

	$options = stream_context_create(['http' => [
	'method'  => 'POST',
	'header'  => 'Content-type: application/json',
	'content' => $json]]);

	$result = file_get_contents($url, false, $options);
	return $result;
}



function count_alarm_whatsapp($user_id)
{
	global $CFG;

	$com = getSQLRowO("SELECT COUNT(id) FROM my_alarm_whatsapp WHERE visible='1' AND status = 0  AND user_id = {$user_id} ");

	return $com->{'COUNT(id)'};
}





function CurlInit($url)
{
		global $CFG;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
}


function personArray($client_id)
{
	global $CFG;

	$array = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}face WHERE visible=1 AND id IN({$client_id}) ");

	echo '<div class="col-md-12 user">';
	for ($i=0; $i<sizeof($array); $i++)
	{
		?>
        <div class="card">
          <h4><?=$array[$i]->name;?></h4>
            <div class="avatar">
                <a class="quickbox" data-fancybox="images" href="<?=$array[$i]->img;?>"><img src="<?=makePreviewName($array[$i]->img, 50, 50, 2);?>" style="border-radius:0;"></a>
            </div>
            <div class="intro">
                <? if($array[$i]->name_other != '') {?><p><span class="name">Подробнее:</span> <?=$array[$i]->name_other;?></p><? }?>
                <? if($array[$i]->mobile != '') {?><p><span>Телефон:</span> <a href="tel:<?=$array[$i]->mobile;?>"><?=$array[$i]->mobile;?></a></p><? }?>
                <? if($array[$i]->whatsapp != '') {?><p><span>Whatsapp:</span> <a target="_blank" href="https://web.whatsapp.com/send?phone=<?=$array[$i]->whatsapp;?>"><?=$array[$i]->whatsapp;?></a></p><? }?>
                <? if($array[$i]->email != '') {?><p><span class="name">E-mail:</span> <?=$array[$i]->email;?></p><? }?>

            	<div class="edit">
                <a href="/person/<?=$array[$i]->id;?>">Посмотреть</a> |
            	<a href="/profile/edit_person/<?=$array[$i]->id;?>" class="face_edit">Править</a>
							<? if($array[$i]->cashback == 1) { ?> | <a href="/cashback/<?=$array[$i]->mobile;?>" target="_blank">Кешбек</a> <? } ?>

            	</div>
            </div>
          </div>

        <?
	}

	echo '</div>';

	//return $src;
}


function personArrayAdd($client_id)
{
	global $CFG;

	$array = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}face WHERE visible=1 AND id IN({$client_id}) ");

	echo '<div class="col-md-12 user">';
	for ($i=0; $i<sizeof($array); $i++)
	{
		if($array[$i]->img ==''){$avatar ='/tpl/img/new/avatra.png';}else{$avatar = $array[$i]->img;}
		?>
         	<div class="contact">
            	<img src="<?=$avatar;?>">
                <span class="name"><?=$array[$i]->name;?></span>
                <span class="mobiles"><?=$array[$i]->mobile;?></span>
                <div class="mask none_client" data-id="<?=$array[$i]->id;?>">
                	<img src="/tpl/img/not_grey.jpg">
                    <span class="name">Открепить?</span>
                	<span class="mobiles"><?=$array[$i]->mobile;?></span>
                </div>
            </div>
        <?
	}
	echo '</div>';
}


function FaceArrayAdd($client_id)
{
	global $CFG;

	$array = getSQLArrayO("SELECT parent_id FROM my_face_to_face WHERE visible=1 AND page_id = {$client_id} ");
	foreach($array as $arrays)
	{
			$res  = getSQLRowO("SELECT * FROM my_face WHERE id='{$arrays->parent_id}'");
			$data[] = $res;
	}

	echo '<div class="col-md-12 user">';
	foreach($data as $array)
	{
				if($array->img ==''){$avatar ='/tpl/img/new/avatra.png';}else{$avatar = $array->img;}
		?>
         	<div class="contact">
            	<img src="<?=$avatar;?>">
                <span class="name"><?=$array->name;?></span>
                <span class="mobiles"><?=$array->mobile;?></span>
                <div class="mask none_client" data-id="<?=$array->id;?>">
                	<img src="/tpl/img/not_grey.jpg">
                    <span class="name">Открепить?</span>
                	<span class="mobiles"><?=$array->mobile;?></span>
                </div>
            </div>
        <?
	}
	echo '</div>';
}


function wp_client_id($client_id)
{
	global $CFG;

	if($client_id != '')
	{
		$array = getSQLArrayO("SELECT mobile,whatsapp,name FROM {$CFG->DB_Prefix}face WHERE visible=1 AND id IN({$client_id}) ");
		for ($i=0; $i<sizeof($array); $i++)
		{
			if($array[$i]->mobile){
			?><option value="<?=$array[$i]->mobile;?>"><?=$array[$i]->name;?> - <?=$array[$i]->mobile;?></option><?
			}
			if($array[$i]->whatsapp){
			?><option value="<?=$array[$i]->whatsapp;?>"><?=$array[$i]->name;?> - <?=$array[$i]->whatsapp;?></option><?
			}
		}
	}
}



function EditRecord($xcode, $id)
{
	if($xcode == 'record')
	{
		$edit = 'edit_vacancy';
	}
	elseif($xcode == 'office')
	{
		$edit = 'edit_office';
	}
	elseif($xcode == 'person')
	{
		$edit = 'edit_person';
	}

	echo '<a href="/profile/'.$edit.'/'.$id.'/">Редактировать</a>';
}


//Номер записи 			Кому пренадлежит запись 	Текущий пользователь
function DeleteRecord($id, $autor_id, $user_id, $options ='')
{
	global $CFG;


	if(
		$user_id == 85 || //Ахуевший доступ
		$user_id == 1 ||  //Ахуевший доступ
		$user_id == $CFG->USER->USER_DIRECTOR_ID ||  // Это директор
		$CFG->USER->USER_DELETE == 1 //Юзер может удалять
		)
	{
		echo '<a href="#" class="delete'.$options.'" data-id="'.$id.'">Удалить</a>';
	}

}



// Генератор названия файла
function GenerFile($url)
{
	$type = pathinfo(rawurldecode($url));	// Получаем расширение файла

	// Генерируем случайное название
	$length = rand(5, 25);
	$randomBytes = openssl_random_pseudo_bytes($length);
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$result = '';
	for ($i = 0; $i < $length; $i++)
	{
		$result .= $characters[ord($randomBytes[$i]) % $charactersLength];
	}

	$filename = $result.'.'.$type["extension"];

	return $filename;
}


// xcode для определеной страницы в список сотрудников
function getFullXCodeByCommentsId($pid)
{
	   switch($pid)
	   {
			case 868 : // Компании
				return '/record/';
			break;
			case 976 : // Служебные
				return '/office/';
			break;
			case 1000 : // Сделки
				return '/deal/';
			break;
			case 1012 : // Лица
				return '/person/';
			break;

	   }
}

// xcode для определеной страницы в список сотрудников
function getFullXCodeByCommentsName($pid)
{
	   switch($pid)
	   {
			case 868 : // Компании
				return 'Компания';
			break;
			case 976 : // Служебные
				return 'Служебная';
			break;
			case 1000 : // Сделки
				return 'Сделка';
			break;
			case 1012 : // Лица
				return 'Лицо';
			break;

	   }
}



function request_url()
{
  $result = ''; // Пока результат пуст
  $default_port = 80; // Порт по-умолчанию

  // А не в защищенном-ли мы соединении?
  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
    // В защищенном! Добавим протокол...
    $result .= 'https://';
    // ...и переназначим значение порта по-умолчанию
    $default_port = 443;
  } else {
    // Обычное соединение, обычный протокол
    $result .= 'http://';
  }
  // Имя сервера, напр. site.com или www.site.com
  $result .= $_SERVER['SERVER_NAME'];

  // А порт у нас по-умолчанию?
  if ($_SERVER['SERVER_PORT'] != $default_port) {
    // Если нет, то добавим порт в URL
    $result .= ':'.$_SERVER['SERVER_PORT'];
  }
  // Последняя часть запроса (путь и GET-параметры).
  $result .= $_SERVER['REQUEST_URI'];
  // Уфф, вроде получилось!
  return $result;
}


function request_url_home()
{
  $result = ''; // Пока результат пуст
  $default_port = 80; // Порт по-умолчанию

  // А не в защищенном-ли мы соединении?
  if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
    // В защищенном! Добавим протокол...
    $result .= 'https://';
    // ...и переназначим значение порта по-умолчанию
    $default_port = 443;
  } else {
    // Обычное соединение, обычный протокол
    $result .= 'http://';
  }
  // Имя сервера, напр. site.com или www.site.com
  $result .= $_SERVER['SERVER_NAME'];

  // А порт у нас по-умолчанию?
  if ($_SERVER['SERVER_PORT'] != $default_port) {
    // Если нет, то добавим порт в URL
    $result .= ':'.$_SERVER['SERVER_PORT'];
  }
  return $result;
}


function big_search_ajax($page_id, $search, $num)
{
	global $CFG;

			$zapros="SELECT id FROM my_news WHERE MATCH (name_company, data_name, data_mobile, data_email, data_whatsapp, history, contact, insta, info) AGAINST ('{$search}' IN BOOLEAN MODE) AND page_id = {$page_id} order by cdate DESC limit {$num},5 ";
           	$sql = getSQLArrayO($zapros);
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pid[] .= $sql[$i]->id;
            }

			$com = getSQLArrayO("SELECT page_id FROM my_comments WHERE MATCH (text) AGAINST ('{$search}' IN BOOLEAN MODE) AND visible='1' AND parent_id = {$page_id}  order by cdate DESC limit {$num},5 ");

            for ($i=0; $i<sizeof($com); $i++)
            {
                $pid[] .= $com   [$i]->page_id;
            }

            $res = array_unique($pid);
			$res = array_values($res);
			if(count($res) == 0)
			{
				echo 0;
			}
			else
			{
				for ($i=0; $i<sizeof($res); $i++)
				{
					$data = SelectDataRowOArray("news", $res[$i]);
					if($data->id == !'')
					{
						?>                 <tr>
                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span></a></td>
                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><?=$data->name_company;?></a></td>
                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}city WHERE id='{$data->city_id}'");		echo $o->name;?></td>
                    <td><? echo dateSQL2TEXT($data->cdate, "DD.MM.YY").' '.dateSQL2TEXT($data->cdate, "hh:mm").'';?></td>
                    <td><? echo dateSQL2TEXT($data->edate, "DD.MM.YY").' '.dateSQL2TEXT($data->edate, "hh:mm").'';?></td>
                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$data->manager_id}'");		echo $o->name;?></a></td>
                    <td><?=SelectDataCount('comments', 'page_id', $data->id);?></td>
                </tr>
 <?
					}
				}
			}

}

function list_boss_manager($user_id)
{
	global $CFG;

	$com = getSQLRowO("SELECT * FROM my_users WHERE id = '{$user_id}'  ");

	$id[] = 90; //Канат
	$id[] = 153; // Марат
	$id[] = 85; //Азамат

	$array = $id;
	$value_to_delete = $com->user_id ; //Элемент с этим значением нужно удалить
	$array = array_flip($array); //Меняем местами ключи и значения
	unset ($array[$value_to_delete]) ; //Удаляем элемент массива
	$array = array_flip($array); //Меняем местами ключи и значения

	foreach($array as $res)
	{
		$o = getSQLRowO("SELECT id,name FROM my_users WHERE id='{$res}'");
		$name .= ' &nbsp; &nbsp; &nbsp; <a class="click_val manager" href="#" data-id="'.$o->id.'">'.$o->name.'</a>';
	}

	return $name;
}



function stable_usort($data, $cmp, $type = '')
{
	if($type == 'DESC') {
		usort($data, function($a,$b) use ($cmp)
		{
				return ($b[$cmp]-$a[$cmp]);
		});
	}
	else {
		usort($data, function($a,$b) use ($cmp)
		{
				return ($a[$cmp]-$b[$cmp]);
		});
	}



	return $data;
}



// Наличные товары с количеством
function sklad_act()
{
	global $CFG;

	//$com = getSQLArrayO("SELECT id FROM my_data_1c_group WHERE name IN ('1. МАТЕРИАЛЫ ДЛЯ РЕКЛАМЫ', '2. СТАНКИ И ИНСТРУМЕНТЫ ДЛЯ РЕКЛАМЫ', '3. ОБОРУДОВАНИЕ ШОУСЦЕНИЧЕСКОЕ', '4. ОБОРУДОВАНИЕ СВЕТОТЕХНИЧЕСКОЕ', '5. Комплектующие')  ");

	//foreach($com as $val)
	//{		$st.='&group%5B%5D='.$val->id;	}


	//$url = '/speedometer/?'.substr($st, 1);


	$url = '/speedometer/?tabs=1&counts=2&search=&warehouse=0';
	return $url;
}


// ОС, имущество копании
function sklad_os()
{
	global $CFG;

	$com = getSQLArrayO("SELECT id FROM my_data_1c_group WHERE name IN ('Стройматериалы', 'Прочие', 'Услуги')  ");
	foreach($com as $val)
	{		$st.='&group%5B%5D='.$val->id;	}

	$url = '/speedometer/?'.substr($st, 1);

	return $url;
}


function parse_links($str)
{
	$str = preg_replace("/(https:\/\/[^\s]+)/", "<a href='$1' target='_blank'>$1</a>", $str);
	$str = preg_replace("/(http:\/\/[^\s]+)/", "<a href='$1' target='_blank'>$1</a>", $str);
	$str = rawurldecode($str);

	$str = preg_replace("!\\*(.*?)\\*!","<strong>\\1</strong>", $str);


	return $str;
}

function resizeArray($data, $str)
{
		foreach ($data as $value)
		{
				if(
						$value->$str == 'СКЛАДЫ Стройка' ||
						$value->$str == 'Демонстрация' ||
						$value->$str == 'Магазин Караганда' ||
						$value->$str == 'Магазин Павлодар' ||
						//$value->$str == 'Основной склад' ||
						$value->$str == 'Офис' ||
						$value->$str == 'Питер Пикалов' ||
						$value->$str == 'Склад  Кайрата' ||
						$value->$str == 'Склад  Стежок' ||
						$value->$str == 'Склад Антон Лопатов' ||
						$value->$str == 'Склад Ардака' ||
						$value->$str == 'Склад ГСМ' ||
						$value->$str == 'Склад Дамира' ||
						$value->$str == 'Склад Даурена' ||
						$value->$str == 'склад Еламан' ||
						$value->$str == 'Склад Казань Иван' ||
						$value->$str == 'Склад КамазЦентр' ||
						$value->$str == 'Склад Мебельщик Астана' ||
						$value->$str == 'Склад Ростов Роман' ||
						$value->$str == 'Склад Челябинск' ||
						$value->$str == ''
						) continue;
				$arr[] =$value;
		}
	return $arr;
}


function StrikData($up, $base, $pole)
{
	global $CFG;

	if( $up != '')
	{
		foreach ($pole as $value)
		{
			$name .= $value.',';
			$dupl .= $value.' = VALUES('.$value.'),';
		}

		$name = trim($name, ',');
		$dupl = trim($dupl, ',');

		$up = substr($up, 0, -2);

		$CFG->DB->query("INSERT INTO {$base} ({$name}) VALUES {$up} ON DUPLICATE KEY UPDATE {$dupl} ; "); //Записываем название подраздилений
		DeleteDublicate($base); //Удаляем дубликаты
		$id = getSQLRowO("SELECT id FROM {$base} WHERE visible = 1 order by id DESC	"); // Получаем последний id
		$AI = $id->id+1;
		$CFG->DB->query("ALTER TABLE {$base} AUTO_INCREMENT = {$AI} "); //Перезаписываем AUTO_INCREMENT у базы
	}
}

//// MysqlI DB


function getSQLArrayO($sql)
{
	global $CFG;
	return $CFG->DB->getResults($sql, true);
}

function getSQLRowO($sql)
{
	global $CFG;

	//echo '<pre>'; print_r($CFG->DB); 	echo '</pre>';


	return $CFG->DB->getRow($sql, true);
}

function getSQLRowA($sql)
{
	global $CFG;
	return $CFG->DB->getRow($sql);
}

function getSQLField($sql)
{
	global $CFG;
	return $CFG->DB->numFields($sql);
}

function MonthNames($id)
{
	$month = array(1=>'Январь', 2=>'Февраль', 3=>'Март', 4=>'Апреля', 5=>'Май', 6=>'Июнь', 7=>'Июль', 8=>'Август', 9=>'Сентябрь', 10=>'Октябрь', 11=>'Ноябрь', 12=>'Декабрь');
	return $month[$id];
}
