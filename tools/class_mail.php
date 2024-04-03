<?php

$ASMailCounter = 0;
$ASMailConvert = array();
$ASMailConvert["utf-8:windows-1251"] = "utf2win1251";
$ASMailConvert["windows-1251:utf-8"] = "win2utf8";

class ASMailContent
{
	var $id = "";
	var $strContentType = "";
	var $strCharset = "";
	var $txtHeader;
	var $txtBody;
	var $baseCharset;

	function ASMailContent($ch="")
	{
		$ASMailCounter++;
		$this->id = $ASMailCounter;
		$this->baseCharset = $ch;
	}

	function setCharset($charset)
	{
		$this->strCharset = $charset;
	}

	function setContentType($ct)
	{
		$this->strContentType = $ct;
	}

	function setBody($txt)
	{
		$this->txtBody = $txt;
	}
	
	function getBody()
	{
		return $this->txtBody;	
	}
	function setHeader($txt)
	{
		$this->txtHeader = $txt;
	}

	function write($nl)
	{
		global $ASMailConvert;

		$res  = $this->txtHeader;
		$res .= "{$nl}";
		$body = $this->txtBody;
		if ($this->strCharset != "" && $this->strCharset != $this->baseCharset)
		{
			$fun = $ASMailConvert["{$this->baseCharset}:{$this->strCharset}"];
			if ($fun != "")
				$body = $fun($body);
		}
		$res .= $body;
		return $res;
	}
}

class ASMail
{
	var $strFrom = "";
	var $strReplyTo = "";
	var $strSubj = "";
	var $strTo = array();
	var $strCC = array();
	var $strBCC = array();
	var $intPrior = 3;	//	1-Urgent, 2-High, 3-Normal, 4-Low
	var $intConfirmDeliv = 0;
	var $intConfirmRead = 0;
	var $arrContent = null;
	var $strSeparator = "";
	var $NL = "\n";	//"\r\n";
	var $arrCTypes = array("jpg"=>"image/jpeg", "jpeg"=>"image/jpeg", "gif"=>"image/gif", "mp3"=>"audio/mp3", "wav"=>"audio/wav", "mid"=>"audio/mid");
	var $ASMailCounter = 0;
	var $baseCharset = "utf-8";
	var $outCharset = "utf-8";
	var $objectContent;
	var $htmltpl;
	
	private $pattern;
	
	function ASMail()
	{
		$this->strSeparator = "-----------QWERTYUIOP";
		$this->arrContent = array();
	}

	function setBaseCharset($ch)
	{
		$this->baseCharset = $ch;
	}

	function setContentCharset($ch)
	{
		$this->outCharset = $ch;
	}

	function setClientCharset($ch)
	{
		$this->outCharset = $ch;
	}

	function setConfirmDeliv($f)
	{
		$this->intConfirmDeliv = $f;
	}

	function setConfirmRead($f)
	{
		$this->intConfirmRead = $f;
	}

	function setPriority($v)
	{
		$this->intPrior = $v;
	}

	function setFrom($to)
	{
//return;
		$this->strFrom = $to;
		if ($this->strReplyTo == "")
			$this->strReplyTo = $to;
	}

	function resetTo()
	{
		$this->strTo = array();
		$this->strCC = array();
		$this->strBCC = array();
	}

	function setReplyTo($to)
	{
//return;
		$this->strReplyTo = $to;
	}

	function setTo($to)
	{
		$to = preg_replace("/,/", " ", $to);
		$to = preg_replace("/;/", " ", $to);

		$l = explode(" ", $to);
		for($i=0; $i<sizeof($l); $i++)
			$this->strTo[] = "<".trim($l[$i]).">";
	}

	function setCC($to)
	{
		$to = ereg_replace(",", " ", $to);
		$to = ereg_replace(";", " ", $to);
		$to = ereg_replace(" {2,}", " ", $to);
		$l = explode(" ", $to);
		for($i=0; $i<sizeof($l); $i++)
			$this->strCC[] = "<".trim($l[$i]).">";
	}

	function setBCC($to)
	{
		$to = ereg_replace(",", " ", $to);
		$to = ereg_replace(";", " ", $to);
		$to = ereg_replace(" {2,}", " ", $to);
		$l = explode(" ", $to);
		for($i=0; $i<sizeof($l); $i++)
			$this->strBCC[] = "<".trim($l[$i]).">";
	}

	function setSubject($subj)
	{
		$this->strSubj = $subj;
	}

	function _addFile($filename, $nik="")
	{
		$fd = fopen( $filename, "r" );
		$contents = fread( $fd, filesize( $filename ) );
		fclose( $fd );
		$img1 = base64_encode($contents);
		$img  = "";
		while(strlen($img1)>70)	{
			$img .= substr($img1, 0, 70);
			$img .= chr(13).chr(10);
			$img1 = substr($img1, 70);
		}
		$img .= $img1;

		$arrCTypes = $this->types;

		$l = explode(".", strtolower($filename));
		$ext = $l[sizeof($l)-1];
		$ct = $arrCTypes[$ext];

		if ($ct == "")
			$ct = "application/$ext";

		if ($nik == "")
		{
			$l = explode("/", ereg_replace("\\\\", "/", $filename));
			$nik = $l[sizeof($l)-1];
		}

		$obj = new ASMailContent();
		$obj->setContentType($ct);
		$obj->setBody($img);
		$hdr  = "Content-Type: {$ct}; name=\"{$nik}\"{$this->NL}";
		$hdr .= "Content-Transfer-Encoding: base64{$this->NL}";
		$hdr .= "Content-Disposition: attachment; filename=\"{$nik}\"{$this->NL}";
		$hdr .= "Content-ID: <{$nik}>{$this->NL}";
		$obj->setHeader($hdr);
		$this->arrContent[] = $obj;
	}

	function addFile($filename, $nik="")
	{
		$fd = fopen( $filename, "r" );
		$contents = fread( $fd, filesize( $filename ) );
		fclose( $fd );
		if ($nik == "")
		{
			$l = explode("/", ereg_replace("\\\\", "/", $filename));
			$nik = $l[sizeof($l)-1];
		}

		$this->addData($contents, $nik);
	}

	function addData($contents, $nik)
	{
		$img1 = base64_encode($contents);
		$img  = "";
/*
		while(strlen($img1)>70)	{
			$img .= substr($img1, 0, 70);
			$img .= chr(13).chr(10);
			$img1 = substr($img1, 70);
		}
		$img .= $img1;
*/
/*
		$ts = time();
		$l = strlen($img1);
		$cnt = 0;
		for($i=0; $i<$l; $i++)
		{
			$img .= $img1[$i];
			if ($cnt == 70)
			{
				$cnt = 0;
				$img .= chr(13).chr(10);
			}
			$cnt++;
		}
		$te = time();
		echo "<br>=".($te-$ts);
*/
		$img  = "";
		$ts = time();
		$l = strlen($img1);
		for($i=0; $i<$l; $i+=70)
		{
			$img .= substr($img1, $i, 70).chr(13).chr(10);
		}
		$te = time();
//		echo "<br>=".($te-$ts);
//die("<br>OK");

		$arrCTypes = $this->types;

		if ($nik == "")
			$nik = "no_name";

		$l = explode(".", strtolower($nik));
		$ext = $l[sizeof($l)-1];
		$ct = $arrCTypes[$ext];

		if ($ct == "")
			$ct = "application/$ext";

		$obj = new ASMailContent();
		$obj->setContentType($ct);
		$obj->setBody($img);
		$hdr  = "Content-Type: {$ct}; name=\"{$nik}\"{$this->NL}";
		$hdr .= "Content-Transfer-Encoding: base64{$this->NL}";
		$hdr .= "Content-Disposition: attachment; filename=\"{$nik}\"{$this->NL}";
		$hdr .= "Content-ID: <{$nik}>{$this->NL}";
		$obj->setHeader($hdr);
		$this->arrContent[] = $obj;
	}

	function addText($txt, $charset = "")
	{
		$charset = ($charset != "" ? $charset : $this->outCharset);
		$obj = new ASMailContent($this->baseCharset);
		$this->objectContent = $obj;
		$obj->setContentType("text/plain");
		$obj->setCharset($charset);
		$obj->setBody($txt);
		$hdr  = "Content-Type: text/plain; charset=\"{$charset}\"{$this->NL}";
		$obj->setHeader($hdr);
		$this->arrContent[] = $obj;
		return sizeof($this->arrContent);
	}

	function addPattern($pattern, $text)
	{	
		/*
		$obj = $this->objectContent;
		$txt = $obj->getBody();
		$out = str_replace($pattern, $text, $txt);
		
		$obj->setBody($out);
		*/
		$cnt = sizeof($this->pattern);
		$this->pattern[$cnt]['pattern'] = $pattern;
		$this->pattern[$cnt]['text'] = $text;
	}

	function addHtml($txt, $charset = "")
	{
		$charset = ($charset != "" ? $charset : $this->outCharset);
		$obj = new ASMailContent($this->baseCharset);
		$this->objectContent = $obj;
		$obj->setContentType("text/html");
		$obj->setCharset($charset);
		$obj->setBody($txt);
		$hdr  = "Content-Type: text/html; charset=\"{$charset}\"{$this->NL}";
		$obj->setHeader($hdr);
		$this->arrContent[] = $obj;
		return sizeof($this->arrContent);
	}
	
	
	function setText($id, $txt, $charset = "")
	{
		$charset = ($charset != "" ? $charset : $this->outCharset);
		$obj = new ASMailContent($this->baseCharset);
		$this->objectContent = $obj;
		$obj->setContentType("text/plain");
		$obj->setCharset($charset);
		$obj->setBody($txt);
		$hdr  = "Content-Type: text/plain; charset=\"{$charset}\"{$this->NL}";
		$obj->setHeader($hdr);
		$this->arrContent[$id] = $obj;
	}

	function setHtml($id, $txt, $charset = "")
	{
		$charset = ($charset != "" ? $charset : $this->outCharset);
		$obj = new ASMailContent($this->baseCharset);
		$this->objectContent = $obj;
		$obj->setContentType("text/html");
		$obj->setCharset($charset);
		$obj->setBody($txt);
		$hdr  = "Content-Type: text/html; charset=\"{$charset}\"{$this->NL}";
		$obj->setHeader($hdr);
		$this->arrContent[$id] = $obj;
	}

	function send($force=0)
	{
		global $ASMailConvert, $CFG;

		$to = implode(",", $this->strTo);
   		$headers  = "From: <{$this->strFrom}>{$this->NL}";
//		$headers .= "To: ".$to."{$this->NL}";
		if ($this->strCC)
	   		$headers .= "CC: ".implode(",", $this->strCC)."{$this->NL}";
		if ($this->strBCC)
	   		$headers .= "BCC: ".implode(",", $this->strBCC)."{$this->NL}";
   		$headers .= "X-Sender: <{$this->strFrom}>{$this->NL}";
   		$headers .= "X-Mailer: Aster mail module{$this->NL}";
		$headers .= "X-Priority: {$this->intPrior}{$this->NL}";
   		$headers .= "Return-Path: <{$this->strReplyTo}>{$this->NL}";
   		$headers .= "Reply-To: <{$this->strReplyTo}>{$this->NL}";

		if ($this->intConfirmDeliv)
	   		$headers .= "Return-Receipt-To: {$this->strReplyTo}{$this->NL}";

		if ($this->intConfirmRead)
		{
	   		$headers .= "X-Confirm-Reading-To: {$this->strReplyTo}{$this->NL}";
   			$headers .= "Disposition-Notification-To: {$this->strReplyTo}{$this->NL}";
		}

		$subj = $this->strSubj;

		$fun = $ASMailConvert["{$this->baseCharset}:{$this->outCharset}"];
		if ($fun != "")
			$subj = $fun($subj);

   		$subj = "=?{$this->outCharset}?B?".base64_encode($subj)."?=";
//   		$headers .= "Subject: =?{$this->outCharset}?B?".base64_encode($subj)."?={$this->NL}";
   		$headers .= "MIME-Version: 1.0{$this->NL}";
   		$headers .= "Content-Type: multipart/mixed; boundary=\"{$this->strSeparator}\"{$this->NL}";
	
		$body  = "";
		for($i=0; $i<sizeof($this->arrContent); $i++)
		{
//echo  "[{$i}]";
			if ($this->arrContent[$i])
			{
				$body .= "--{$this->strSeparator}{$this->NL}";
				$body .= $this->arrContent[$i]->write($this->NL)."{$this->NL}";
			}
//echo "+";
		}
//die("{$body}");
		$body .= "--{$this->strSeparator}--{$this->NL}";
//echo "===".sizeof($this->arrContent);

//die  ("<br /><br />\n\nto: {$to}\n\n".$headers."{$this->NL}".$body);

//$f = fopen("f.msg", "w");
//fputs($f, $headers."{$this->NL}".$body);
//fclose($f);

//die("----");


		for($n=0;$n<sizeof($this->pattern);$n++)
		{
			$patterntext = $this->pattern[$n]["pattern"];
			$textreplace = $this->pattern[$n]["text"];
			
			$body = str_replace($patterntext, $textreplace, $body);
		}

		$r = @mail($to, $subj, $body, $headers);
//		sleep(1);
//die(($r ? "OK":"NO")." - $to, $subj, $body, $headers");
		return $r;


//		return $headers."{$this->NL}".$body;
	}



	function compose()
	{
		global $ASMailConvert, $CFG;

		$to = implode(",", $this->strTo);
   		$headers  = "From: <{$this->strFrom}>{$this->NL}";
//		$headers .= "To: ".$to."{$this->NL}";
		if ($this->strCC)
	   		$headers .= "CC: ".implode(",", $this->strCC)."{$this->NL}";
		if ($this->strBCC)
	   		$headers .= "BCC: ".implode(",", $this->strBCC)."{$this->NL}";
   		$headers .= "X-Sender: <{$this->strFrom}>{$this->NL}";
   		$headers .= "X-Mailer: Aster mail module{$this->NL}";
		$headers .= "X-Priority: {$this->intPrior}{$this->NL}";
   		$headers .= "Return-Path: <{$this->strReplyTo}>{$this->NL}";
   		$headers .= "Reply-To: <{$this->strReplyTo}>{$this->NL}";

		if ($this->intConfirmDeliv)
	   		$headers .= "Return-Receipt-To: {$this->strReplyTo}{$this->NL}";

		if ($this->intConfirmRead)
		{
	   		$headers .= "X-Confirm-Reading-To: {$this->strReplyTo}{$this->NL}";
   			$headers .= "Disposition-Notification-To: {$this->strReplyTo}{$this->NL}";
		}

		$subj = $this->strSubj;

		$fun = $ASMailConvert["{$this->baseCharset}:{$this->outCharset}"];
		if ($fun != "")
			$subj = $fun($subj);

   		$subj = "=?{$this->outCharset}?B?".base64_encode($subj)."?=";
//   		$headers .= "Subject: =?{$this->outCharset}?B?".base64_encode($subj)."?={$this->NL}";
   		$headers .= "MIME-Version: 1.0{$this->NL}";
   		$headers .= "Content-Type: multipart/mixed; boundary=\"{$this->strSeparator}\"{$this->NL}";
	
		$body  = "";
		for($i=0; $i<sizeof($this->arrContent); $i++)
		{
			if ($this->arrContent[$i])
			{
				$body .= "--{$this->strSeparator}{$this->NL}";
				$body .= $this->arrContent[$i]->write($this->NL)."{$this->NL}";
			}
		}
		$body .= "--{$this->strSeparator}--{$this->NL}";

		return array($subj, $headers, $body);
	}

}

//------------------ usage example ----------------

/*

$m = new ASMail();

$m->setTo("mailTo@mail.ru");
$m->setFrom("mailFrom@mail.ru");
$m->setSubject("HelloWorld!");
$m->addText("Hello\n<b>World</b>");
$m->addHtml("Hello\n<b>World</b> - 1");
$m->addHtml("Hello\n<b>World</b> - 2");
$m->addFile("E:\bti1.jpg");
$m->addFile("E:\bti2.jpg");

$m->send();

//$f = fopen("f.msg", "w");
//fputs($f, $m->send());
//fclose($f);

echo "done...";


=?Windows-1251?B?

=?koi8-r?B?

=?ISO-8859-1?B?

?=


У меня сейчас во всех письмах кодирование поля Тема имеет следующий вид:

Subject: =?Windows-1251?Q?Re=3A_=F1=F2=E0=F2=E8=F1=F2=E8=EA=E0_=EE=F2=EA=E0=F2=EE?=
        =?Windows-1251?Q?=E2?=

но в некоторых письмах (написанных другими почтовиками) встречается такой вид:

Subject: =?Windows-1251?B?UmU6INjz7A==?=

Я понимаю, что в первом случае кодирование символов Quoted, а во втором Base64.





*/

?>