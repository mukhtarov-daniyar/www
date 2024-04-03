<?

function win2utf8($s) {
$t = '';
for($i=0, $m=strlen($s); $i<$m; $i++) {
$c=ord($s[$i]);
if ($c<=127) {$t.=chr($c); continue; }
if ($c>=192 && $c<=207) {$t.=chr(208).chr($c-48); continue; }
if ($c>=208 && $c<=239) {$t.=chr(208).chr($c-48); continue; }
if ($c>=240 && $c<=255) {$t.=chr(209).chr($c-112); continue; }
if ($c==184) { $t.=chr(209).chr(145); continue; };
if ($c==168) { $t.=chr(208).chr(129); continue; };
}
return $t;
} 

function uni2win($s){
    $out="";
    $de=false;
    $beg=true;
    $oc=0;
    for($i=0; $i<strlen($s); $i++){
      $c=substr($s,$i,1);
      if($beg){
        $beg=false;
      }else{
        if($de){
          $de=false;
        }else if(ord($c)==4){
          $d=ord($oc);
          if($d==81){
            $c=chr(184);
          }else if($d>=48){
            $c=chr($d+176);
          }else if($d>=16){
            $c=chr($d+176);
          }else if($d==1){
            $c=chr(168);
          }
          $out.=$c;

          $de=true;
        }else if(ord($c)==0){
          $out.=$oc;

          $de=true;
        }else{
          $out.=$oc;
        }
      }
      $oc=$c;
    }
    if((!$de)){
      $out.=$oc;
    }
    return $out;
  }


function UTF16LEtoCP1251($str){ // (C) SiMM
  $ret = '';
  for ($i = 0; $i < strlen($str); $i += 2){
    extract(unpack("vc",substr($str,$i,2)));
    if ($c<128) $ret .= chr($c);
    elseif ($c>=0x410 && $c<=0x44F) $ret .= chr($c-0x410+0xC0);
    else $ret .= '?';
  }
  return $ret;
}

function utf2win1251 ($s)
{

return utf2win($s);
//		return (iconv("UTF-8", "WINDOWS-1251", $s));

 $out = "";

 for ($i=0; $i<strlen($s); $i++)
 {
  $c1 = substr ($s, $i, 1);
  $byte1 = ord ($c1);
  if ($byte1>>5 == 6) // 110x xxxx, 110 prefix for 2 bytes unicode
  {
   $i++;
   $c2 = substr ($s, $i, 1);
   $byte2 = ord ($c2);
   $byte1 &= 31; // remove the 3 bit two bytes prefix
   $byte2 &= 63; // remove the 2 bit trailing byte prefix
   $byte2 |= (($byte1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
   $byte1 >>= 2; // c1 shifts 2 to the right

   $word = ($byte1<<8) + $byte2;
   if ($word==1025) $out .= chr(168);                    // ?
   elseif ($word==1105) $out .= chr(184);                // ?
   elseif ($word>=0x0410 && $word<=0x044F) $out .= chr($word-848); // ?-? ?-?
   else
   { 
     $a = dechex($byte1);
     $a = str_pad($a, 2, "0", STR_PAD_LEFT);
     $b = dechex($byte2);
     $b = str_pad($b, 2, "0", STR_PAD_LEFT);
     $out .= "&#x".$a.$b.";";
   }
  }
  else
  {
   $out .= $c1;
  }
 }

 return $out;
}


function utf8win1251($s){
$out="";$c1="";$byte2=false;
for ($c=0;$c<strlen($s);$c++){
$i=ord($s[$c]);
if ($i<=127) $out.=$s[$c];
if ($byte2){
$new_c2=($c1&3)*64+($i&63);
$new_c1=($c1>>2)&5;
$new_i=$new_c1*256+$new_c2;
if ($new_i==1025) $out_i=168; else
if ($new_i==1105) $out_i=184; else $out_i=$new_i-848;
$out.=chr($out_i);
$byte2=false;}
if (($i>>5)==6) {$c1=$i;$byte2=true;}
}
return $out;}

function str2upper($s)
{
	$scl = " Ğ°Ğ±Ğ²Ğ³Ğ´ĞµÑ‘Ğ¶Ğ·Ğ¸Ğ¹ĞºĞ»Ğ¼Ğ½Ğ¾Ğ¿Ñ€ÑÑ‚ÑƒÑ„Ñ…Ñ†Ñ‡ÑˆÑ‰ÑŠÑ‹ÑŒÑÑÑ";
	$scu = " ĞĞ‘Ğ’Ğ“Ğ”Ğ•ĞĞ–Ğ—Ğ˜Ğ™ĞšĞ›ĞœĞĞĞŸĞ Ğ¡Ğ¢Ğ£Ğ¤Ğ¥Ğ¦Ğ§Ğ¨Ğ©ĞªĞ«Ğ¬Ğ­Ğ®Ğ¯";
	$sll = "abcdefghijklmnopqrstuvwxyz àáâãäå¸æçèéêëìíîïğñòóôõö÷øùúûüışÿ";
	$slu = "ABCDEFGHIJKLMNOPQRSTUVWXYZ ÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖ×ØÙÚÛÜİŞß";


	$res = "";
	$c = "";
	for ($i=0; $i<strlen($s); $i++)
	{
		$c = $s[$i];
		if ($c == "Ğ" || $c == "Ñ")
		{
			$i++;
			$c .= $s[$i];
			$pos = strpos($scl, $c);
			if ($pos > 0)
				$c = substr($scu, $pos, 2);
		}
		else
		{
			$pos = strpos($sll, $c);
			if ($pos > 0)
				$c = substr($slu, $pos, 1);
		}
		$res .= $c;
	}

	return $res;
}


function utf2win($s)
{
	$s = str_replace('â„–', '¹', $s);

	$utf = "  Ğ°Ğ±Ğ²Ğ³Ğ´ĞµÑ‘Ğ¶Ğ·Ğ¸Ğ¹ĞºĞ»Ğ¼Ğ½Ğ¾Ğ¿Ñ€ÑÑ‚ÑƒÑ„Ñ…Ñ†Ñ‡ÑˆÑ‰ÑŠÑ‹ÑŒÑÑÑĞĞ‘Ğ’Ğ“Ğ”Ğ•ĞĞ–Ğ—Ğ˜Ğ™ĞšĞ›ĞœĞĞĞŸĞ Ğ¡Ğ¢Ğ£Ğ¤Ğ¥Ğ¦Ğ§Ğ¨Ğ©ĞªĞ«Ğ¬Ğ­Ğ®Ğ¯";
	$win = " àáâãäå¸æçèéêëìíîïğñòóôõö÷øùúûüışÿÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖ×ØÙÚÛÜİŞß";

	$res = "";
	$c = "";
	for ($i=0; $i<strlen($s); $i++)
	{
		$c = $s[$i];
		if ($c == "Ğ" || $c == "Ñ")
		{
			$i++;
			$c .= $s[$i];
			$pos = strpos($utf, $c);
			if ($pos > 0)
				$c = substr($win, $pos/2, 1);
		}
		else
		{
		}
		$res .= $c;
	}

	return $res;
}


function win2trnslt($s)
{
	$r = "_àáâãäå¸æçèéêëìíîïğñòóôõö÷øùúûüışÿÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖ×ØÙÚÛÜİŞß¹";
	$t = "_abvgdeezzijklmnoprstufhc____y'e__ABVGDEEZZIJKLMNOPRSTUFHC____Y_E__#";

	$res = "";
	$c = "";
	for ($i=0; $i<strlen($s); $i++)
	{
		$c = $s[$i];
		$c1 = $c;
		$pos = strpos($r, $c);
		if ($pos > 0)
			$c = substr($t, $pos, 1);
		if ($c1 == "×")
			$c = "CH";
		if ($c1 == "Ø")
			$c = "SH";
		if ($c1 == "Ù")
			$c = "SCH";
		if ($c1 == "Ş")
			$c = "JU";
		if ($c1 == "ß")
			$c = "JA";
		if ($c1 == "÷")
			$c = "ch";
		if ($c1 == "ø")
			$c = "sh";
		if ($c1 == "ù")
			$c = "sch";
		if ($c1 == "ş")
			$c = "ju";
		if ($c1 == "ÿ")
			$c = "ja";
		$res .= $c;
	}
	return $res;
}


function utf2trnslt($s)
{
	return win2trnslt( utf2win($s) );
}


?>