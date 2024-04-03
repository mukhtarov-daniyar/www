<?php
function monthName($month, $full=1, $lang=0)
{
	global $CFG;

	$m = $month*1;
	return  $CFG->Locale['month'.$m.''];
}

function monthName1($month, $full=1, $lang=0)
{
	global $CFG;
	if ($lang>0)
		$lang = $CFG->LANGS[$lang][3];
	else
		$lang = $CFG->LANGS[$CFG->SYS_LANG][3];

	$monthes = array();
	$monthes["en"] = array("January","February","March","April","May","June","July","August","September","October","November","December");
	$monthes["ru"] = array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
	$r = $monthes[$lang][$month-1];
	if (!$full)
		$r = utf8_substr($r, 0, 3);
	return $r;
}

function dayName($day, $lang=0)
{
	global $CFG;
	if ($lang>0)
		$lang = $CFG->LANGS[$lang][3];
	else
		$lang = $CFG->LANGS[$CFG->SYS_LANG][3];

	$days = array();
	$days["en"] = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$days["ru"] = array("Воскресение","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота");
	$r = $days[$lang][$day];
	return $r;
}

function sqlDate($timestamp, $zero=0)
{
//	$d = getDate($timestamp);
//	$rd = sprintf("%4d-%02d-%02d %02d:%02d:%02d", $d["year"], $d["mon"], $d["mday"], $d["hours"], $d["minutes"], $d["seconds"]);
	if ($zero)
		$rd = date("Y-m-d 00:00:00", $timestamp);
	else
		$rd = date("Y-m-d H:i:s", $timestamp);
	return $rd;
}

function sqlDateNow()
{
	return sqlDate(time());
}

function sqlDateNowOLD()
{
	return sqlDate(strtotime('-1 day')) ."\n";
}



function timeMediaSQL2TEXT($date)
{
	$date = trim("".$date);
	if (strpos($date, " "))
	{
		$dt = explode(" ", $date);
		$d = explode("-", $dt[0]);
		$date = $dt[1];
	}
	$t = explode(":", $date);
	$h = $t[0] + 6;
	if ($h>=24)
		$h = $h-24;
	$h = sprintf("%02d", $h);
	return ($h.":".$t[1]);
}

function datetimeSQL2TEXT($date)
{
	return dateSQL2TEXT($date, "H:i d.m.Y");
}

function dateSQL2TEXT($date, $format="d.m.Y", $full=1)
{
	$res = "";
	$date = trim("".$date);
	$time = $date;
	if ($date=="")
		$date = sqlDateNow();
	if (strpos($date, " "))
	{
		$dt = explode(" ", $date);
		$date = $dt[0];
		$time = $dt[1];
	}
	$d = explode("-", $date);
	$t = explode(":", $time);

	$hp = $t[0];
	$ap = "AM";
	if ($hp > 12)
	{
		$hp = sprintf("%02d", ($hp - 12));
		$ap = "PM";
	}

	$format = preg_replace("/DD/", $d[2], $format);
	$format = preg_replace("/DN/", dayName($d[2]), $format);
	$format = preg_replace("/MM/", $d[1], $format);
	$format = preg_replace("/MN/", monthName($d[1], 1), $format);
	$format = preg_replace("/MS/", monthName($d[1], 0), $format);
	$format = preg_replace("/YYYY/", $d[0], $format);
	$format = preg_replace("/YY/", utf8_substr($d[0], 2), $format);
	$format = preg_replace("/hh/", $t[0], $format);
	$format = preg_replace("/hp/", $hp, $format);
	$format = preg_replace("/mm/", $t[1], $format);
	$format = preg_replace("/ss/", $t[2], $format);
	$format = preg_replace("/AP/", $ap, $format);

	$format = preg_replace("/d/", $d[2], $format);
	$format = preg_replace("/l/", dayName($d[2]), $format);
	$format = preg_replace("/m/", $d[1], $format);
	$format = preg_replace("/F/", monthName($d[1], 1), $format);
	$format = preg_replace("/M/", monthName($d[1], 0), $format);
	$format = preg_replace("/Y/", $d[0], $format);
	$format = preg_replace("/y/", utf8_substr($d[0], 2), $format);
	$format = preg_replace("/H/", $t[0], $format);
	$format = preg_replace("/h/", $hp, $format);
	$format = preg_replace("/i/", $t[1], $format);
	$format = preg_replace("/s/", $t[2], $format);
	$format = preg_replace("/A/", $ap, $format);
	$format = preg_replace("/a/", strtolower($ap), $format);

	// U, z, w, j, S - still unsupported

	return ($format);
}


function writeRadioButton($name, $value)
{
    $res = "<input type='radio' name='".$name."' value='".$value."'";
    if ($globalS[$name] == $value)
        $res .= "checked";
    $res .= ">";
    return $res;
}

function writeCheckBox($name)
{
    $res = "<input type='checkbox' name='".$name."' ";
    if ($globalS[$name] == "on")
        $res .= "checked";
    $res .= ">";
    return $res;
}

function writeTextField($name)
{
    $res = "<input type='text' name='".$name."' value='".$globalS[$name]."'>";
    return $res;
}

function writeHidden($name)
{
    $res = "<input type='hidden' name='".$name."' value='".$globalS[$name]."'>";
    return $res;
}

function createConnection()
{
    global $CFG;

    if ($CFG->debug)
        echo "<br>Connect:[$CFG->DB_Name][$CFG->DB_Host][$CFG->DB_User][$CFG->DB_Password]";
    $DB=new DB_Sql($CFG->DB_Name, $CFG->DB_Host, $CFG->DB_User, $CFG->DB_Password);
	if (!$CFG->EMULATE_UTF8)
	{
		$DB->query("SET CHARACTER SET utf8");
		$DB->query("SET NAMES utf8");
	}

    return $DB;
}


function generateUID()
{
    $d = getdate();
    $mypid = getmypid();
    $orderid = sprintf("%02d%02d%02d%02d%02d%02d-%05ld",
        ($d["year"]%100), $d["mon"] + 1, $d["mday"],
        $d["hours"], $d["minutes"], $d["seconds"], $mypid);
//echo "ID:".$orderid;
    return $orderid;
}

function reparse($str)
{
 $res = "";
 $len = strlen($str)-1;
//**/ echo "<br>STR[".$str."][".strlen($str)."]";
 for($i=0; $i<$len; $i++) {
  if ($str[$i] != '\\')
   $res .= $str[$i];
  else
   if ($str[$i+1] == '\\')
     $res .= $str[$i++];
 }
 if ($str[$len] != '\\')
   $res .= $str[$len];
//**/ echo "<br>RES[".$res."][".strlen($res)."]";
 return $res;
}

function myurlencode($str)
{
//**/ echo "<br><pre>STRen[".$str."][".strlen($str)."]</pre>";

 $str0 = stripslashes($str);
 $str1 = preg_replace("/\%/",  "%0", $str0);
 $str2 = preg_replace("/'/",  "%2", $str1);
 $str3 = preg_replace("/\"/", "%3", $str2);
 $str4 = preg_replace("/ /",  "%4", $str3);
 $str5 = preg_replace("/>/",  "%8", $str4);
 $str6 = preg_replace("/</",  "%9", $str5);
 $str7 = preg_replace("/".chr(10)."/", "%5", $str6);
 $res  = preg_replace("/".chr(13)."/", "%6", $str7);

//**/ echo "<br><pre>RESen[".$res."][".strlen($res)."]</pre>";

 return $res;
}

function myurldecode($str)
{
//**/ echo "<br><pre>STRde[".$str."][".strlen($str)."]</pre>";
 $str2 = preg_replace("/\%1/", "\\", $str);
 $str3 = preg_replace("/\%2/", "'",  $str2);
 $str4 = preg_replace("/\%3/", "\"", $str3);
 $str5 = preg_replace("/\%4/", " ",  $str4);
 $str6 = preg_replace("/\%8/", ">",  $str5);
 $str7 = preg_replace("/\%9/", "<",  $str6);
 $str8 = preg_replace("/\%5/", chr(10), $str7);
 $str9 = preg_replace("/\%6/", chr(13), $str8);
 $res  = preg_replace("/\%0/",  "%", $str9);

//**/ echo "<br><pre>RESde[".$res."][".strlen($res)."]</pre>";
 return $res;
}


function ecrane($str)
{
	$res = preg_replace("!\&!", "&amp;", $str);
	$res = preg_replace("!'!", "&#039;", $res);
	$res = preg_replace("!\"!", "&quot;", $res);
	$res = preg_replace("!<!", "&lt;", $res);
	$res = preg_replace("!>!", "&gt;", $res);

	return $str;
}

function apost($value)
{
	$value = strip_tags(htmlspecialchars($value));
	$value = str_replace(array("'",'"',''),'', $value);

	return $value;
}

function apost_replace($value)
{
	$value = strip_tags(($value));
	$value = str_replace(array("'",'"','\/',''),'"', $value);

	return $value;
}

function mysql_apost($str)
{
	$res = htmlspecialchars(mysql_real_escape_string($str));

	return $res;
}




function replace_r_n($text)
{
	$text = str_replace('\\r\\n',' ', $text);
	$text = str_replace('\r\n',' ', $text);
	$text = str_replace('\\R\\N',' ', $text);
	$text = str_replace('\R\N',' ', $text);
	$text = str_replace('/\r\\n',' ', $text);
	$text = str_replace('/r/n',' ', $text);
	$text = str_replace('/\R\\N',' ', $text);
	$text = str_replace('/R/N',' ', $text);

	return $text;
}

function replace_r_n_br($text)
{
	$text = str_replace('\\r\\n',' <br>', $text);
	$text = str_replace('\r\n',' <br>', $text);
	$text = str_replace('\\R\\N',' <br>', $text);
	$text = str_replace('\R\N',' <br>', $text);
	$text = str_replace('/\r\\n',' <br>', $text);
	$text = str_replace('/r/n',' <br>', $text);
	$text = str_replace('/\R\\N',' <br>', $text);
	$text = str_replace('/R/N',' <br>', $text);

	return nl2br($text);
}


function apos($str)
{

 $res = preg_replace("!\&!", "&amp;", $str);
 $res = preg_replace("!'!", "&#039;", $str);
 $res = preg_replace("!\"!", "&quot;", $res);
 $res = preg_replace("!\'!", "&quot;", $res);
 $res = preg_replace("!<!", "&lt;", $res);
 $res = preg_replace("!>!", "&gt;", $res);

 //**/echo "<br>RESen[".$res."][".strlen($res)."]";

 return $res;
}




function ecraneJS($str)
{

 //**/echo "<br>STRen[".$str."][".strlen($str)."]";

 $res = preg_replace("!'!", "&#039;", $str);
 $res = preg_replace("!\"!", "&#034", $res);
 $res = preg_replace("![\]!", "\\\\", $res);
 $res = preg_replace("!".chr(13)."!", "\\n", $res);
 $res = preg_replace("!".chr(10)."!", "\\r", $res);

 //**/echo "<br>RESen[".$res."][".strlen($res)."]";

 return $res;
}

function showArray($a)
{
   while(list($key, $val) = each($a))
      echo "<br />$key = $val\n";
   echo "<br />\n";
}

function showArray1()
{
   while(list($key, $val) = each($globalS))
   {
      echo "<br />$key = $val\n";
      $n = $globalS[$key];
//      if (is_array($n))
//       showArray($n);
//        for ($i=0; $i<sizeof($n); $i++)
//      echo "<br />--------".$key."[$i] = ".$n[$i];
   }
   echo "<br />\n";
}

function redirect($url)
{
	//$host  = $_SERVER['HTTP_HOST'];
	//$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	//header("Location: http://$host$uri/");

die(header("Location: {$url}"));
}


function isNN()
{
    if (!strstr($_SEREVR["HTTP_USER_AGENT"], "MSIE"))
        return true;
    else
        return false;
}

function isIE()
{
    if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
        return true;
    else
        return false;
}

function slashes($str)
{
    $res = preg_replace("/'/", "\\'", $str);
    $res = preg_replace("/\"/", "\\\"", $res);
    return $res;
}

function mailto($to, $subj, $body, $header="")
{
//echo "<br>To: $to<br>Subj: $subj<br>Header: $header<br>Body: ".nl2br($body)."<br>";
	$res = @mail($to, $subj, $body, $header);
	return $res;
}


function myDate($tmstmp)
{
 $t  = $tmstmp[0];
 $t .= $tmstmp[1];
 $t .= $tmstmp[2];
 $t .= $tmstmp[3];
 $t .= "-";
 $t .= $tmstmp[4];
 $t .= $tmstmp[5];
 $t .= "-";
 $t .= $tmstmp[6];
 $t .= $tmstmp[7];
 $t .= " ";
 $t .= $tmstmp[8];
 $t .= $tmstmp[9];
 $t .= ":";
 $t .= $tmstmp[10];
 $t .= $tmstmp[11];
 $t .= ":";
 $t .= $tmstmp[12];
 $t .= $tmstmp[13];
// $t = $tmstmp;
 return $t;
}

function randomint($max = 100) {
    static $startseed = 0;
    if (!$startseed) {
        $startseed = (double)microtime()*getrandmax();
        srand($startseed);
    }
    return (rand()%$max);
}

function generateRandomCode($length=25)
{
	$s = "";
	for($i=0; $i<$length; $i++)
	{
		$s .= randomint(9);
	}
	return $s;
}


    function getTimeForm($name)
    {
        $h = $globalS[$name."_H"];
        $i = $globalS[$name."_I"];
        $s = $globalS[$name."_S"];
        $r = sprintf("%02d:%02d:%02d", $h, $i, $s);
//        $globalS[$name] = $r;
        return $r;
    }

    function __getDateForm($name)
    {
        $d = $globalS[$name."_D"];
        $m = $globalS[$name."_M"];
        $y = $globalS[$name."_Y"];
        $r = sprintf("%04d-%02d-%02d", $y, $m, $d);
//        $globalS[$name] = $r;
        return $r;
    }

    function __getDateTimeForm($name)
    {
        $h = $globalS[$name."_H"];
        $i = $globalS[$name."_I"];
        $s = $globalS[$name."_S"];
        $d = $globalS[$name."_D"];
        $m = $globalS[$name."_M"];
        $y = $globalS[$name."_Y"];
        $r = sprintf("%04d-%02d-%02d %02d:%02d:%02d", $y, $m, $d, $h, $i, $s);
//        $globalS[$name] = $r;
        return $r;
    }




    function getDateForm($name)
    {
        $r = $_POST[$name];
        return $r;
    }

    function getDateTimeForm($name)
    {
        $h = $_POST[$name."_H"];
        $i = $_POST[$name."_I"];
        $s = $_POST[$name."_S"];
        $r = sprintf("%s %02d:%02d:%02d", $_POST[$name], $h, $i, $s);
//echo("<br />".$name."=".$r);
        return $r;
    }

$d = getDate();
$START_YEAR = 1*$d["year"];
$END_YEAR = $START_YEAR+1;


function showDateForm($name, $value="", $ro=0, $sy=0, $ey=0, $showZero=0, $fnc="")
{
/*
	$r  = '<input type="text" class="inp" name="'.$name.'" id="'.$name.'" size="10" value="'.ecrane($value).'" />';
	$r .= '<input type="button" class="inp" value="&nbsp;...&nbsp;" ';
	$r .= 'onMouseDown="showCalendar(this, \''.$name.'_3\', \'a_'.$name.'_3\', \''.$name.'\');" ';
	$r .= 'style="width:20px;" /><a name="a_'.$name.'_3" style="position:absolute; display:block;"></a>';
	$r .= '<span id="'.$name.'_3" style="position: absolute; z-index: 2;"></span>';
*/
/*
	$r  = '<input type="text" class="inp" name="'.$name.'" id="'.$name.'" size="10" value="'.ecrane($value).'" />';
	$r .= '<a href="#" onclick="showCalendar(this, \''.$name.'_3\', \'a_'.$name.'_3\', \''.$name.'\');">';
	$r .= '<img src="/images/calendar/dayselect.gif" width="30" height="14" alt="" border="0" />';
	$r .= '</a><a name="a_'.$name.'_3" style="position:absolute; display:block;"></a>';
	$r .= '<span id="'.$name.'_3" style="position: absolute; z-index: 2;"></span>';

*/
	$l = explode(" ", $value);
	$value = $l[0];
	$r  = '<input type="text" class="inp" name="'.$name.'" id="'.$name.'" size="10" value="'.ecrane($value).'" x_readonly="readonly" ';
	$r .= 'onclick="showCalendar(this, \''.$name.'_3\', \'a_'.$name.'_3\', \''.$name.'\');" />';
	$r .= '<a name="a_'.$name.'_3" style="position:absolute; display:block;"></a>';
	$r .= '<span id="'.$name.'_3" style="position: absolute; z-index: 2;"></span>';


	return $r;
}

function __showDateForm($name, $value="", $ro=0, $sy=0, $ey=0, $showZero=0, $fnc="")
{
	global $START_YEAR, $END_YEAR;

		$ey = ($ey ? $ey : $END_YEAR);
		$sy = ($sy ? $sy : $START_YEAR);
        $ro = ($ro ? " disabled='true'" : "");

		if ($value=="")
			$value = sqlDateNow();

		$l = explode(" ", $value);
		$value = $l[0];

		$l = explode("-", $value);
		$y = $l[0];
		$m = $l[1];
		$d = $l[2];

		$r  = "";
	    $r .= "<select $fnc id='".$name."_D' name='".$name."_D'$ro>\n";
		if ($showZero)
            $r .= " <option value='00'>--</option>\n";
		for ($i=1; $i<=31; $i++)
		{
			$sel = "";
			if ($d == $i)
				$sel = " selected='true'";
			$s = sprintf("%02d", $i);
			$r .= "	<option value='$s'$sel>$i</option>\n";
		}
	    $r .= "</select>\n";
		$fnc1 = "onchange='_dateChanged(\"$name\");'";
	    $r .= "<select $fnc1 id='".$name."_M' name='".$name."_M' $ro>\n";
		if ($showZero)
            $r .= " <option value='00'>--</option>\n";
		for ($i=1; $i<=12; $i++)
		{
			$sel = "";
			if ($m == $i)
				$sel = " selected='true'";
			$s = sprintf("%02d", $i);
			$r .= "	<option value='$s'$sel>".monthName($i)."</option>\n";
		}
	    $r .= "</select>\n";
	    $r .= "<select $fnc1 id='".$name."_Y' name='".$name."_Y'$ro>\n";
		if ($showZero)
            $r .= " <option value='0000'>--</option>\n";
		if ($sy < $ey)
			for ($i=$sy; $i<=$ey; $i++)
			{
				$sel = "";
				if ($y == $i)
					$sel = " selected='true'";
				$r .= "	<option value='$i'$sel>$i</option>\n";
			}
		else
			for ($i=$sy; $i>=$ey; $i--)
			{
				$sel = "";
				if ($y == $i)
					$sel = " selected='true'";
				$r .= "	<option value='$i'$sel>$i</option>\n";
			}
	    $r .= "</select>\n";
		if ($ro != "")
		{
            $r .= "<input type='hidden' name='".$name."_D' value='".ecrane($d)."' />\n";
            $r .= "<input type='hidden' name='".$name."_M' value='".ecrane($m)."' />\n";
            $r .= "<input type='hidden' name='".$name."_Y' value='".ecrane($y)."' />\n";
		}
		return $r;
}


function dayOfWeek($value)
{
		if ($value=="")
			$value = sqlDateNow();

		$l = explode(" ", $value);
		$value = $l[0];

		$l = explode("-", $value);
		$y = $l[0];
		$m = $l[1];
		$d = $l[2];

		$r = getDate(mktime(0, 0, 0, $m, $d, $y));
		return $r["wday"];
}

function dayOfWeekName($value)
{
		return dayName(dayOfWeek($value));
}


function toolsWriteScript()
{
?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
function _dateChanged(obj)
{
	var mObj = document.getElementById(obj+"_M");
	var dObj = document.getElementById(obj+"_D");
	var yObj = document.getElementById(obj+"_Y");
	var mVal = mObj.value;
	var dVal = dObj.value;
	var yVal = yObj.value;
	var maxD = 28;

	if (Math.floor(yVal/4)*4 == yVal)
		maxD = 29;

	if (mVal == 2)
	{
		dObj.options.length = maxD;
		dObj.options[maxD-1].value = maxD;
		dObj.options[maxD-1].text = maxD;
	}
	else if (mVal == 1 || mVal == 3 || mVal == 5 || mVal == 7 || mVal == 8 || mVal == 10 || mVal == 12)
	{
		dObj.options.length=31;
		dObj.options[28].value = "29";
		dObj.options[28].text = "29";
		dObj.options[29].value = "30";
		dObj.options[29].text = "30";
		dObj.options[30].value = "31";
		dObj.options[30].text = "31";
		maxD = 31;
	}
	else
	{
		dObj.options.length=30;
		dObj.options[28].value = "29";
		dObj.options[28].text = "29";
		dObj.options[29].value = "30";
		dObj.options[29].text = "30";
		maxD = 30;
	}

	if (dVal > maxD)
		dObj.options.selectedIndex = maxD-1;
}
//--><!]]>
</script>

<?php
}

    function showTimeForm($name, $value="", $ro=0, $showSec=0, $fnc="", $media=0)
    {
        $ro = ($ro ? " disabled=\"disabled\"" : "");

		if (strstr($value, " "))
		{
	        $l = explode(" ", $value);
       		$val = $l[1];
		}
		else
			$val = $value;

        $l = explode(":", $val);
        $h = $l[0];
        $i = $l[1];
        $s = $l[2];

		$r = "";
		$r .= "<input type=\"text\" name=\"{$name}_H\" id=\"{$name}_H\" value=\"{$h}\" class=\"td_time\"{$ro} onblur=\"if(!_sys_check_int(this.value)) this.value='00'; if(1*this.value>23 || 1*this.value<0) this.value='00'\" />";
		$r .= ":<input type=\"text\" name=\"{$name}_I\" id=\"{$name}_I\" value=\"{$i}\" class=\"td_time\"{$ro} onblur=\"if(!_sys_check_int(this.value)) this.value='00'; if(1*this.value>59 || 1*this.value<0) this.value='00'\" />";
        if ($showSec)
			$r .= " :<input type=\"text\" name=\"{$name}_S\" id=\"{$name}_S\" value=\"{$s}\" class=\"td_time\"{$ro} onchange=\"if(!_sys_check_int(this.value)) this.value='00'; else if(1*this.value>59 || 1*this.value<0) this.value='00'\" />";

		return $r;
	}

    function ___showTimeForm($name, $value="", $ro=0, $showSec=0, $fnc="", $media=0)
    {
        $ro = ($ro ? " disabled=\"disabled\"" : "");

		if ($value=="")
			$value = sqlDateNow();

		if (strstr($value, " "))
		{
	        $l = explode(" ", $value);
       		$val = $l[1];
		}
		else
			$val = $value;

        $l = explode(":", $val);
        $h = $l[0];
        $m = $l[1];
        $s = $l[2];

        $r  = "<select $fnc id='{$name}_H' name='{$name}_H'$ro>\n";
        for ($i=0; $i<24; $i++)
        {
            $sel = "";
            if ($h == $i)
                $sel = " selected='true'";
            $ii = sprintf("%02d", $i);
            $ii2 = sprintf("%02d", $i);
			if ($media)
	            $ii2 = sprintf("%02d", (($i+6)<24 ? ($i+6) : ($i+6)-24));
            $r .= " <option value='$ii'$sel>$ii2</option>\n";
        }
        $r .= "</select>\n";
        $r .= ":<select $fnc id='{$name}_I' name='{$name}_I'$ro>\n";
        for ($i=0; $i<60; $i++)
        {
            $sel = "";
            if ($m == $i)
                $sel = " selected='true'";
            $ii = sprintf("%02d", $i);
            $r .= " <option value='$ii'$sel>$ii</option>\n";
        }
        $r .= "</select>\n";
        if ($showSec)
        {
            $r .= ":<select $fnc name='{$name}_S' id='{$name}_S'$ro>\n";
            for ($i=0; $i<60; $i++)
            {
                $sel = "";
                if ($s == $i)
                    $sel = " selected='true'";
                $ii = sprintf("%02d", $i);
                $r .= " <option value='$ii'$sel>$ii</option>\n";
            }
            $r .= "</select>\n";
        }
        $r .= "\n";
        if ($ro != "")
        {
            $r .= "<input type='hidden' name='{$name}_H' id='{$name}_H' value='".ecrane($h)."' />\n";
            $r .= "<input type='hidden' name='{$name}_I' id='{$name}_I' value='".ecrane($m)."' />\n";
            $r .= "<input type='hidden' name='{$name}_S' id='{$name}_S' value='".ecrane($s)."' />\n";
        }
        return $r;
    }






function parseWWW($string)
{

/*
$res = preg_replace("/([a-zA-Z0-9_]+[\.a-zA-Z0-9_-]*)@([a-zA-Z0-9]+[\.a-zA-Z0-9\-]*\.[a-z]{2,4})/",
"<a href=\"mailto:\\1@\\2\">\\1@\\2</a>", $string);

$res = preg_replace("/(http|ftp|https{1}):\/\/([a-zA-Z0-9]+[\.a-zA-Z0-9\-]*\.[a-z]{2,4})(\/.*)?/",
"<a href=\"\\1://\\2\\3\">\\1://\\2\\3</a>", $res);
*/

	$pattern = "([a-zA-Z0-9_]+[\.a-zA-Z0-9_-]*@[a-zA-Z0-9]+[\.a-zA-Z0-9\-]*\.[a-z]{2,4})|((http://|ftp://|https://)?[a-zA-Z0-9]+[\.a-zA-Z0-9\-]*\.[a-z]{2,4}(/.*)?)";
	$loop = true;
	$res = "";

	while ($loop)
	{
		if( $loop = ereg( $pattern, $string, $regs ) )
		{
		    $str = $regs[0];
//return $regs[0];
			$l = explode($str, $string);
			$string = $l[1];
			$res .= $l[0];
			if (strtolower(substr($str, 0, 7)) == "http://")
				$url = $str;
			else if (strtolower(substr($str, 0, 6)) == "ftp://")
				$url = $str;
			else if (strtolower(substr($str, 0, 8)) == "https://")
				$url = $str;
			else if (strstr($str, '@'))
				$url = "mailto:$str";
			else
				$url = "http://$str";
			$res .= "<a href='$url'>$str</a>";
		}
		else
			$res .= $string;
	}


	return $res;
}


function getDocument($host_port, $url, $params, $method="GET", $cookie="")
{
	global $_SERVER;

	$sendDataStr = '';
	if (is_array($params))
	{
		while(list($k, $v) = each($params))
			$sendDataStr .= "$k=".urlencode($v)."&";
//		$sendDataStr = substr( $sendDataStr, 0, -1 );
	}
	else
		$sendDataStr = $params;

	$sCookie = '';
	if (is_array($cookie))
	{
		while(list($k, $v) = each($cookie))
			$sCookie .= "$k=".urlencode($v)."&";
	}
	else
		$sCookie = $cookie;

	$len = "".strlen( $sendDataStr );

	$method = strtoupper($method);
	$method = ($method == "GET" ? "GET" : "POST");

	if ($method == "GET" && $sendDataStr != "")
		$url .= "?".$sendDataStr;

	$hp = explode(":", $host_port);
	$host = $hp[0];
	$port = $hp[1];
	if ($port == 0)
		$port = 80;

	$zapros = "";
	$zapros .= "{$method} {$url} HTTP/1.0\r\n";
	$zapros .= "Host: {$host}\r\n";
	$zapros .= "Accept: */*\r\n";
	$zapros .= "Accept-Language: ru\r\n";
	$zapros .= "Accept-Encoding: deflate\r\n";
	$zapros .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 4.0)\r\n";
	$zapros .= "Connection: Close\r\n";
//	$zapros .= "Connection: Keep-Alive\r\n";
	if ($sCookie != "")
		$zapros .= "Cookie: {$sCookie}\r\n";
	else
		$zapros .= "Cookie: {$_SERVER["HTTP_COOKIE"]}\r\n";
	if ($method == "POST")
	{
		$zapros .= "Content-type: application/x-www-form-urlencoded\r\n";
		$zapros .= "Content-length: {$len}\r\n";
		$zapros .= "\r\n";
		$zapros .= "{$sendDataStr}\r\n";
	}
	$zapros .= "\r\n";

	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	if(!$fp) { print "$errstr ($errno)<br/>\n"; return ""; }

	fputs($fp,$zapros);

	$headers = '';
	while( $str = trim( fgets( $fp, 4096 ) ) )
		$headers .= "$str\n";

	$body = '';
	while( !feof( $fp ) )
		$body .= fgets( $fp, 4096 );

	fclose($fp);
	return array($headers, $body);
}

function koi2win($str)
{
	return convertString($str, "koi", "win");
}

function convertString($str, $chFrom, $chTo)
{
	$chSets = array();
	$chSets["win"] = "�������������������������������������Ũ��������������������������";
	$chSets["dos"] = "������񦧨��������������������������������������������������";
	$chSets["koi"] = "�����ţ����������������������������������������������������������";

	$res = "";
	for($j=0; $j<strlen($str); $j++)
	{
		$c = $str[$j];
		for($i=0; $i<strlen($chSets[$chFrom]); $i++)
			if ($chSets[$chFrom][$i] == $c)
			{
				$c = $chSets[$chTo][$i];
				break;
			}
		$res .= $c;
	}
	return $res;
}


function striptags($s, $tags="")
{
	$tags = trim($tags);

	if ($tags == "")
	{
		$r = preg_replace("/<.*?>/", "", $s);
	}
	else
	{
		$r = $s;
		$l = explode(" ", $tags);
		for($i=0; $i<sizeof($l); $i++)
		{
			$r = preg_replace("!\<".$l[$i].".*?\>!", "", $r);
			$r = preg_replace("!\</".$l[$i]."\>!", "", $r);
		}
	}

	return $r;
}

function striptagsarray($a)
{
	$b = array();
	while(list($k, $v) = each($a))
		$b[ $k ] = striptags( $v );
	return $b;
}

function explodeBitSet($name, $val, $NAMES, $ro=0)
{
	$d = ($ro ? " disabled='true'" : "");
	$bit = 1;
	$res = array();
	for ($i=0; $i<sizeof($NAMES); $i++)
	{
		$sel = "";
		if ($val & $bit)
			$sel = " checked='true'";
		$res[$i] = "<input type='checkbox' name='$name"."_"."$i' id='$name"."_"."$i' value='1'$sel$d />";
		$bit *= 2;
	}
	return $res;
}

function getBitSet($name, $NAMES)
{
	$bit = 1;
	$res = 0;
	for ($i=0; $i<sizeof($NAMES); $i++)
	{
		$val = $_POST[$name."_".$i];
		if ($val)
			$res |= $bit;
		$bit *= 2;
	}
	return $res;
}


function copydir($from, $to, $subdirs=1)
{
	if (!is_dir($from))
		return;

	$d = dir($from);
	while($entry = $d->read())
	{
		if ($entry == "." || $entry == "..")
			continue;

		if (is_file("$from/$entry"))
			copy("$from/$entry", "$to/$entry");

		if (is_dir("$from/$entry") && $subdirs)
		{
			mkdir2("$to/$entry");
			copydir("$from/$entry", "$to/$entry");
		}
	}
	$d->close();
}

function deldir($from, $subdirs=1)
{
	if (!is_dir($from))
		return;

	$d = dir($from);
	while($entry = $d->read())
	{
		if ($entry == "." || $entry == "..")
			continue;

		if (is_file("$from/$entry"))
			unlink("$from/$entry");

		if (is_dir("$from/$entry") && $subdirs)
		{
			deldir("$from/$entry", 1);
		}

	}
	$d->close();
	rmdir($from);

	return 1;
}

function stripslashesarray($arr)
{
	global $CFG;

	$newarr = array();
	while(list($k, $v) = each($arr))
		$newarr[$k] = stripslashes($v);

		foreach($newarr as $key => $value)
		{
			$newarr[$key] = htmlspecialchars( $CFG->DB->escape($value ) );
		}

	return $newarr;
}

function hs($str)
{

	$str = preg_replace("/</", "&lt;", $str);
	$str = preg_replace("/>/", "&gt;", $str);
	return $str;
}


function fgets1($f, $len)
{
	$s = @fgets($f, $len);
	$s = preg_replace("/".chr(13)."/", "", $s);
	$s = preg_replace("/".chr(10)."/", "", $s);
	return $s;
}


function scaleImage_old($fileName, $newX, $newY, $JPG_QUALITY=80)
{
		$imgname = strtoupper($fileName);
		if (strstr($imgname, ".BMP"))
			$im = @imagecreatefromwbmp($fileName);
		if (strstr($imgname, ".XBM"))
			$im = @imagecreatefromxbm($fileName);
		if (strstr($imgname, ".XPM"))
			$im = @imagecreatefromxpm($fileName);
		if (strstr($imgname, ".GIF"))
			$im = @imagecreatefromgif($fileName);
		if (strstr($imgname, ".PNG"))
			$im = @imagecreatefrompng($fileName);
		if (strstr($imgname, ".GD"))
			$im = @imagecreatefromgd($fileName);
		if (strstr($imgname, ".GD2"))
			$im = @imagecreatefromgd2($fileName);
		if (strstr($imgname, ".JPG") || strstr($imgname, ".JPEG") || strstr($imgname, ".JPE"))
			$im = imagecreatefromjpeg($fileName);
		$sx = imagesx($im);
		$sy = imagesy($im);

		if ($newX > 0  && $newY <= 0)
			$ratio = $newX/$sx;

		if ($newY > 0  && $newX <= 0)
			$ratio = $newY/$sy;

		if ($newX > 0  && $newY > 0)
			$ratio = min($newX/$sx, $newY/$sy);

//die("[$fileName, $newX, $newY][$sx<$newX][$ratio]");

		if ($ratio >= 1)
			return;

		$x = round($sx * $ratio);
		$y = round($sy * $ratio);
		$im2 = imagecreatetruecolor($x, $y);
		imageinterlace($im2, 1);

		imagecopyresized ($im2, $im, 0, 0, 0, 0, $x+1, $y+1, $sx+1, $sy+1);

		if (strstr($imgname, ".JPG") || strstr($imgname, ".JPEG") || strstr($imgname, ".JPE"))
			imagejpeg($im2, $fileName, $JPG_QUALITY);
		if (strstr($imgname, ".GIF"))
			imagegif($im2, $fileName);
}

//==============================================
//
// mode	-	0:specified resize only (������ ����� ��������� � ������������� ��������� ��������
//			  ��� ������������ �� ��������� ����� �������)
//			1:��������� � ������� �������������, ���������� � ��� ��������, ��������� �������� ����������
//			2:��������� �� ������� �������, ���������� ������������� � ��������, ������ ��������
//
//==============================================
function scaleImage($filename, $args)
{
//showArray($args);
		$newX = 1 * $args["newX"];
		$newY = 1 * $args["newY"];
		$JPG_QUALITY = 1 * $args["jpg"];
		$mode = 1 * $args["mode"];
		$ext = $args["ext"];
		$newfilename = $args["newfilename"];

		$JPG_QUALITY = ($JPG_QUALITY > 0 ? $JPG_QUALITY : 80);

		$l = explode(".", $filename);
		$ext_src = strtolower( $l[sizeof($l)-1] );
		if ($args["src"] != "")
			$ext_src = strtolower($args["src"]);

		if ($ext_src == "bmp")
			$im = @imagecreatefromwbmp($filename);
		if ($ext_src == "xbm")
			$im = @imagecreatefromxbm($filename);
		if ($ext_src == "xpm")
			$im = @imagecreatefromxpm($filename);
		if ($ext_src == "gif")
			$im = @imagecreatefromgif($filename);
		if ($ext_src == "png")
			$im = @imagecreatefrompng($filename);
		if ($ext_src == "gd")
			$im = @imagecreatefromgd($filename);
		if ($ext_src == "gd2")
			$im = @imagecreatefromgd2($filename);
		if ($ext_src == "jpg" || $ext_src == "jpeg" || $ext_src == "jpe")
		{
			$im = @imagecreatefromjpeg($filename);
			$ext_src = "jpg";
		}

		$l = explode(".", $newfilename);
			$ext = strtolower( $l[sizeof($l)-1] );
		if ($ext == "")
			$ext = "gif";

		if (!$im)
		{
			putEmptyImage($newX, $newY, $newfilename);
			return;
		}

		$sx = @imagesx($im);
		$sy = @imagesy($im);

		if ($newX > 0  && $newY <= 0)
			$ratio = $newX/$sx;

		if ($newY > 0  && $newX <= 0)
			$ratio = $newY/$sy;

		if ($newX<=0 || $newY<=0)
			$mode = 0;

		if ($newX > 0  && $newY > 0)
		{
			if ($mode < 2)
				$ratio = min($newX/$sx, $newY/$sy);
			if ($mode == 2)
				$ratio = max($newX/$sx, $newY/$sy);
		}

//die("[$filename, $newX, $newY, $mode, $ext][$sx<$newX][$ratio]");

		if ($ratio >= 1)
		{
			$ratio = 1;
		}

		$x = round($sx * $ratio);
		$y = round($sy * $ratio);
		$x = ($x>0 ? $x : 1);
		$y = ($y>0 ? $y : 1);
//		$im2 = imagecreatetruecolor($x, $y);

		if ($mode==0)
		{
			$newX = $x;
			$newY = $y;
		}

		$im2 = imagecreatetruecolor($newX, $newY);
		$white = imagecolorallocate($im2, 255, 255, 255);
		@imagealphablending($im2, false);
		@imagelayereffect($im2, 1);
		@imageinterlace($im2, 1);
		@imagefill($im2, 0, 0, $white);

		if ($mode < 2)
		{
			$nx = ($newX>$x ? ($newX-$x)/2 : 0);
			$ny = ($newY>$y ? ($newY-$y)/2 : 0);
			@imagecopyresized ($im2, $im, $nx, $ny, 0, 0, $x, $y, $sx, $sy);
		}
		if ($mode == 2)
		{
			$nx = ($newX<$x ? ($x-$newX)/2 : 0);
			$ny = ($newY<$y ? ($y-$newY)/2 : 0);
			@imagecopyresized ($im2, $im, 0, 0, $nx, $ny, $x, $y, $sx, $sy);
		}


		@imagecolortransparent($im2, $white);

//die("Save:".$newfilename);

		if ($im2)
		{
			if ($ext == "jpg")
			{
				if ($newfilename != "")
				{
//die("Save");
					imagejpeg($im2, $newfilename, $JPG_QUALITY);
				}
				else
				{
					header("Content-type: image/jpeg");
					imagejpeg($im2, null, $JPG_QUALITY);
				}
			}
			if ($ext == "gif")
			{
				if ($newfilename != "")
					imagegif($im2, $fileName);
				else
				{
					header("Content-type: image/gif");
					imagegif($im2);
				}
			}
		}
		else
			putEmptyImage($newX, $newY, $newfilename);

}


function putEmptyImage($x=24, $y=24, $filename="", $rbg="255,255,255")
{
	$im2 = @imagecreatetruecolor($x, $y);
	$l = explode(",", $rgb);
   	$white = @imagecolorallocate($im2, $l[0], $l[1], $l[2]);
	@imagefill($im2, 0, 0, $white);
//  $white = @imagecolorallocate($im2, 255, 255, 255);
//	$black = @imagecolorallocate($im2, 0, 0, 0);
//	@imagefill($im2, 0, 0, $black);
//	@imagefilledrectangle($im2, 1, 1, $x-2, $y-2, $white);

	if ($filename != "")
		@imagejpeg($im2, $filename);
	else
	{
		header("Content-type: image/jpeg");
		@imagejpeg($im2);
	}
}


function GetRandomString1($length, $type=7)
{

	$templates = array();
	$templates[0] = "1234567890";
	$templates[1] = "abcdefghijklmnopqrstuvwxyz";
	$templates[2] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	$length *= 1;
	$type *= 1;
	if (!($type & 7))
		$type = 7;

	$template = "";
	$flag = 1;
	for ($i=0; $i<3; $i++)
	{
		if ($type & $flag)
			$template .= $templates[$i];
		$flag = $flag << 1;
	}


       settype($length, "integer");
       settype($rndstring, "string");
       settype($a, "integer");
       settype($b, "integer");

		srand((double)microtime()*1000000);
       for ($a = 0; $a < $length; $a++) {
               $b = rand(0, strlen($template) - 1);
               $rndstring .= $template[$b];
       }

       return "".$rndstring;
}


function mkdir2($url, $mask=0777)
{

	$l = explode("/", $url);

	$curr = "./";

	$res = 1;

	for ($i=0; $i<sizeof($l); $i++)
	{
		$curr .= $l[$i]."/";
		if (!is_dir($curr))
		{
			$oldumask = @umask(0) ;
			$res = @mkdir( $curr, $mask ) ;
			if ($res)
				@chmod( $curr, $mask ) ;
			@umask( $oldumask ) ;
		}
/*
		if ($l[$i] == "..")
			chdir("..");
	 	if ($l[$i] == "." || $l[$i] == ".." || $l[$i] == "")
			continue;

		$res = @mkdir( $l[$i], $mask ) ;
		if ($res)
			@chmod( $l[$i], $mask ) ;
		chdir($l[$i]);
*/
	}

	return $res;
}

function mkdir2_old($url)
{
	$oldumask = @umask(0) ;
	$res = @mkdir( $url, 0777 ) ;
	if ($res)
		@chmod( $url, 0777 ) ;
	@umask( $oldumask ) ;
	return $res;
}

function html2xhtml($text)
{
	$t1 = "";

	$T = new Tokeniser($text);

	$inTag = 0;
	$isValue = 0;
	while(($s = "".$T->getToken()) != null)
	{

		if ($inTag)
		{
			if ($inTag == 1)
			{
				$inTag = 2;
				$s = strtolower($s);
				$isSingle = isSingleTag($s);
				$isValue = 0;
			}

			if ($isSingle && $s == ">")
				$s = "/>";

			if (!$isValue)
				$s = strtolower($s);

			if ($isValue)
			{
				if ($s!='"' && $s!="'")
					$s = "\"{$s}\"";
				else
				{
					$s1 = $s;
					while(($s2=$T->getToken()) != $s1)
						$s .= $s2;
					$s .= $s2;
				}
				$isValue = 0;
			}

			if ($s == "=")
				$isValue = 1;

			if ($s=="selected" || $s=="checked" || $s=="readonly" || $s=="disabled" || $s=="compact" || $s=="declare" || $s=="defer" || $s=="ismap" || $s=="noresize" || $s=="noshade" || $s=="nowrap" || $s=="multiple")
				$s = "{$s}=\"{$s}\"";

//			if ($s=="selected" || $s=="checked" || $s=="readonly" || $s=="disabled" || $s=="compact" || $s=="declare" || $s=="defer" || $s=="ismap" || $s=="noresize" || $s=="noshade" || $s=="nowrap" || $s=="multiple")
//				$s = "{$s}=\"true\"";

		}

		if ($s == "<")
			$inTag = 1;

		if ($s == ">" || $s == "/>")
			$inTag = 0;

		if ($s == "/>")
			$s = " {$s}";

		$t1 .= $s;
	}

	$t1 = preg_replace("![ ]*/>!", " />", $t1);
	return $t1;
}


function isSingleTag($s)
{
	$tags = array("br","hr","img","input");
	for ($i=0; $i<sizeof($tags); $i++)
		if ($tags[$i] == $s)
				return 1;
	return 0;

	echo $s;
}




function utf8_strlen($s)
{
    return preg_match_all('/./u', $s, $tmp);
}

function utf8_substr($s, $offset, $len = 'all')
{
    if ($offset<0) $offset = utf8_strlen($s) + $offset;
    if ($len!='all')
    {
        if ($len<0) $len = utf8_strlen($s) - $offset + $len;
        $xlen = utf8_strlen($s) - $offset;
        $len = ($len>$xlen) ? $xlen : $len;
        preg_match('/^.{' . $offset . '}(.{0,'.$len.'})/us', $s, $tmp);
    }
    else
    {
        preg_match('/^.{' . $offset . '}(.*)/us', $s, $tmp);
    }
    return (isset($tmp[1])) ? $tmp[1] : false;
}

function utf8_strpos($haystack, $needle, $offset = 0)
{
    # get substring (if isset offset param)
    $offset = ($offset<0) ? 0 : $offset;
    if ($offset>0)
    {
        preg_match('/^.{' . $offset . '}(.*)/us', $haystack, $dummy);
        $haystack = (isset($dummy[1])) ? $dummy[1] : '';
    }

    # get relative pos
    $p = strpos($haystack, $needle);
    if ($haystack=='' or $p===false) return false;
    $r = $offset;
    $i = 0;

    # calc real pos
    while($i<$p)
    {
        if (ord($haystack[$i])<128)
        {
            # ascii symbol
            $i = $i + 1;
        }
        else
        {
            # non-ascii symbol with variable length
            # (handling first byte)
            $bvalue = decbin(ord($haystack[$i]));
            $i = $i + strlen(preg_replace('/^(1+)(.+)$/', '\1', $bvalue));
        }
        $r++;
    }
    return $r;
}

function utf8_substr_count($h, $n)
{
    # preparing $n for using in reg. ex.
    $n = preg_quote($n, '/');

    # select all matches
    preg_match_all('/' . $n . '/u', $h, $dummy);
    return count($dummy[0]);
}

function getMediaTypeByExt($ext)
{
	$t = array();
	$t["image"] = "x.jpg.jpeg.gif.bmp.psd.png.tif.tiff.";
	$t["video"] = "x.mpg.mpeg.avi.flv.mov.rm.qt.3gp.";
	$t["audio"] = "x.mp3.wav.au.";

	while(list($k,$v) = each($t))
		if (strpos($v, ".{$ext}."))
			return $k;

	return "";
}





function abbr($str)
{
	$l = explode(" ", $str);
	$res = "";
	for($i=0; $i<sizeof($l); $i++)
		$res .= utf8_substr($l[$i], 0, 1);
	return $res;
}

function utf8_strtostr($str)
{

	$lo = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";
	$hi = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

	$len = strlen($str);
	$res = "";
	$i = 0;
	while($i < $len)
	{
		$c1 = $str[$i];
		if ($c1 != "�" && $c1 != "�")
			$res .= $c1;
		else
		{
			$c2 = $str[$i+1];
			$add = $c1.$c2;
			for ($j=0; $j<strlen($hi)-1; $j++)
				if ($i==0)
				{
					if ($lo[$j] == $c1 && $lo[$j+1] == $c2)
						$add = $hi[$j].$hi[$j+1];
				}
				else
				{
					if ($hi[$j] == $c1 && $hi[$j+1] == $c2)
						$add = $lo[$j].$lo[$j+1];
				}
			$res .= $add;
			$i++;
		}

		$i++;
	}

	return $res;
}

function utf8_strtolower($str)
{

	$lo = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";
	$hi = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

	$len = strlen($str);
	$res = "";
	$i = 0;
	while($i < $len)
	{
		$c1 = $str[$i];
		if ($c1 != "�" && $c1 != "�")
			$res .= $c1;
		else
		{
			$c2 = $str[$i+1];
			$add = $c1.$c2;
			for ($j=0; $j<strlen($hi)-1; $j++)
			{
				if ($hi[$j] == $c1 && $hi[$j+1] == $c2)
					$add = $lo[$j].$lo[$j+1];
			}
			$res .= $add;
			$i++;
		}

		$i++;
	}

	return $res;
}

function utf8_strtoupper($str)
{

	$lo = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";
	$hi = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

	$len = strlen($str);
	$res = "";
	$i = 0;
	while($i < $len)
	{
		$c1 = $str[$i];
		if ($c1 != "�" && $c1 != "�")
			$res .= $c1;
		else
		{
			$c2 = $str[$i+1];
			$add = $c1.$c2;
			for ($j=0; $j<strlen($hi)-1; $j++)
			{
				if ($lo[$j] == $c1 && $lo[$j+1] == $c2)
					$add = $hi[$j].$hi[$j+1];
			}
			$res .= $add;
			$i++;
		}

		$i++;
	}

	return $res;
}


function utf8_strtotitle($str)
{

	$lo = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";
	$hi = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";

	$len = strlen($str);
	$res = "";
	$i = 0;
	while($i < $len)
	{
		$c1 = $str[$i];
		if ($c1 != "�" && $c1 != "�")
			$res .= $c1;
		else
		{
			$c2 = $str[$i+1];
			$add = $c1.$c2;
			for ($j=0; $j<strlen($hi)-1; $j++)
			{
				if ($hi[$j] == $c1 && $hi[$j+1] == $c2)
					$add = $lo[$j].$lo[$j+1];
			}
			$res .= $add;
			$i++;
		}

		$i++;
	}

	return $res;
}


/*
 * if $del_dir = 1, specified $path will be removed,
 * if $del_dir = 0, $path will saved and only all files and folders inside $path will be removed
 */
function deltree($path, $del_dir=1)
{
	$path .= "/";
	$path = preg_replace("!//!", "/", $path);
	$d = dir($path);
	$e = array();
	while($entry = $d->read())
	{
		if ($entry == "." || $entry == "..")
			continue;

		if (is_file($path.$entry))
			unlink($path.$entry);
		if (is_dir($path.$entry))
			deltree($path.$entry);
		if ($del_dir)
			rmdir($path.$entry);
	}
}


function is_email($email)
{
  if (function_exists("filter_var"))
  {
    $s=filter_var($email, FILTER_VALIDATE_EMAIL);
    return !empty($s);
  }
  $p = '/^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*';
  $p.= '@([-a-z0-9]+\.)+([a-z]{2,3}';
  $p.= '|info|arpa|aero|coop|name|museum|mobi)$/ix';
  return preg_match($p, $email);
}

function is_email_exists($email)
{
  $server_prefix = Array("", "mail.", "smtp.");
  $ret = false;
  list($prefix, $domain) = split("@", $email);
  if(function_exists("getmxrr") && getmxrr($domain, $mxhosts))
  {
    $ret = true;
  }
  else
  {
    foreach($server_prefix as $val)
    {
      if($ret OR @fsockopen($val.$domain, 25, $errno, $errstr, 3))
      {
        $ret = true;
      }
    }
  }
  return $ret;
}

function get_os_($user_agent)
{
   $oses = array (
     'Windows 3.11' => 'Win16',
     'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
     'Windows 98' => '(Windows 98)|(Win98)',
     'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
     'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
     'Windows 2003' => '(Windows NT 5.2)',
     'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
     'Windows ME' => 'Windows ME',
     'Open BSD'=>'OpenBSD',
     'Sun OS'=>'SunOS',
     'Linux'=>'(Linux)|(X11)',
     'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
     'QNX'=>'QNX',
     'BeOS'=>'BeOS',
     'OS/2'=>'OS/2',
     'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
   );

   foreach($oses as $os=>$pattern)
   {
     if (eregi($pattern, $user_agent))
       return $os;
   }
   return 'Unknown';
}







function parseInt($string) {

   if(preg_match('/(\d+)/', $string, $array))

	  return $array[1];

   else

      return 0;
}
