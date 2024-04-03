<?php

if ($CFG->SYS_LANG_ADMIN=='ru')
{
	$sTITLE = "Пользователи";

	$l = array();
	$l["cdate"] 	= "Дата регистрации";
	$l["login"] 	= "Логин";
	$l["pass"] 	= "Пароль";
	$l["status"] 	= "Статус";
	$l["name"] 	= "Имя";     
	$l["email"]	= "E-mail";    
	$l["country"] 	= "Страна";
	$l["zip"] 	= "Индекс";
	$l["city"] 	= "Город"; 
	$l["addr"] 	= "Адрес";
	$l["bdate"] 	= "Дата рождения";
	$l["passp"] 	= "Паспорт (серия номер)";
	$l["phone"] 	= "Телефон";
	$l["company"] 	= "Компания";
	$l["buss"] 	= "Сфера деят.";
	$l["fax"] 	= "Fax";
	$l["acl"] 	= "Права";
	                                                   
	$l["photo"] 	= "Фотография";
	$l["video"] 	= "Видео";
	$l["audio"] 	= "Аудио";
	$l["avatar"] 	= "Аватар";
	                                                   
	$l["active"] 	= "Открыт для контатов";
	$l["deleted"] 	= "На удаление";
	                                                   
	$l["vdate"] 	= "Последнее посещение";

	$l["alert"]	= "Новый пароль подтвержден неверно!";
}
else
{
	$sTITLE = "Users";

	$l = array();
	$l["cdate"] 	= "Registration date";
	$l["login"] 	= "Login";
	$l["pass"] 	= "Password";
	$l["status"] 	= "Status";
	$l["name"] 	= "Name";     
	$l["email"]	= "E-mail";    
	$l["country"] 	= "Country";
	$l["zip"] 	= "Post Index";
	$l["city"] 	= "City"; 
	$l["addr"] 	= "Address";
	$l["bdate"] 	= "Birthdate";
	$l["passp"] 	= "Passnort #";
	$l["phone"] 	= "Phone";
	$l["company"] 	= "Company";
	$l["buss"] 	= "Bussines";
	$l["fax"] 	= "Fax";
	$l["acl"] 	= "ACL";
	                                                   
	$l["photo"] 	= "Photo";
	$l["video"] 	= "Video";
	$l["audio"] 	= "Audio";
	$l["avatar"] 	= "Avatar";
	                                                   
	$l["active"] 	= "Allowed for contacts";
	$l["deleted"] 	= "To be deleted";
	                                                   
	$l["vdate"] 	= "Last visit";

	$l["alert"]	= "Wrong confirmation of the new password!";
}


	if (isset($_GET["in_del"]))
		$_SESSION["in_del"] = 1 * $_GET["in_del"];

	$rec = new DBRecord("{$CFG->DB_Prefix}users", $id, 0);

	$del = 1 * $_SESSION["in_del"];
	$rec->setWhere("deleted='{$del}'");

	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("cdate", $FIELD_DB->VARCHAR,	255, $l["cdate"], $FIELD_HTML->TEXT, 20, 0);
	$rec->AddField("login", $FIELD_DB->VARCHAR, 	255, $l["login"], $FIELD_HTML->TEXT, 20, 1);
	$rec->AddField("pass", $FIELD_DB->VARCHAR,	255, $l["pass"], $FIELD_HTML->PASSWORD, 20, 0);
	$rec->AddField("status", $FIELD_DB->VARCHAR,	255, $l["status"], $FIELD_HTML->SELECT, 50, 1);
	$rec->AddField("name", $FIELD_DB->VARCHAR,	255, $l["name"], $FIELD_HTML->TEXT, 40, 1);
	//$rec->AddField("email", $FIELD_DB->VARCHAR,	255, $l["email"], $FIELD_HTML->TEXT, 30, 1);
/*
	$rec->AddField("country", $FIELD_DB->VARCHAR,	255, $l["country"], $FIELD_HTML->TEXT, 40, 0);
	$rec->AddField("zip", $FIELD_DB->VARCHAR, 	255, $l["zip"], $FIELD_HTML->TEXT, 10, 0);
	$rec->AddField("city", $FIELD_DB->VARCHAR, 	255, $l["city"], $FIELD_HTML->TEXT, 40, 1);
	$rec->AddField("addr", $FIELD_DB->VARCHAR, 	255, $l["addr"], $FIELD_HTML->TEXT, 150, 0);
	$rec->AddField("bdate", $FIELD_DB->DATE, 	255, $l["bdate"], $FIELD_HTML->TEXT, 20, 0);
	$rec->AddField("passp", $FIELD_DB->VARCHAR, 	255, $l["passp"], $FIELD_HTML->TEXT, 20, 0);
	$rec->AddField("phone", $FIELD_DB->VARCHAR, 	255, $l["phone"], $FIELD_HTML->TEXT, 40, 1);
	$rec->AddField("company", $FIELD_DB->VARCHAR, 	255, $l["company"], $FIELD_HTML->TEXT, 40, 1);
	$rec->AddField("buss", $FIELD_DB->VARCHAR, 	255, $l["buss"], $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("fax", $FIELD_DB->VARCHAR, 	255, $l["fax"], $FIELD_HTML->TEXT, 40, 0);
	$rec->AddField("acl", $FIELD_DB->INT,   	0,   $l["acl"], $FIELD_HTML->BITSET, 0, 1);

//	$rec->AddField("photo", $FIELD_DB->VARCHAR, 	255, $l["photo"], $FIELD_HTML->FILE, 40, 0);
//	$rec->AddField("video", $FIELD_DB->VARCHAR, 	255, $l["video"], $FIELD_HTML->FILE, 40, 0);
//	$rec->AddField("audio", $FIELD_DB->VARCHAR, 	255, $l["audio"], $FIELD_HTML->FILE, 40, 0);
//	$rec->AddField("avatar", $FIELD_DB->VARCHAR, 	255, $l["avatar"], $FIELD_HTML->FILE, 40, 0);
//	$rec->AddField("active", $FIELD_DB->INT, 	0,   $l["active"], $FIELD_HTML->CHECKBOX, 0, 0);

//	$rec->AddField("deleted", $FIELD_DB->INT, 	0,   $l["deleted"], $FIELD_HTML->CHECKBOX, 0, 0);
*/
	$rec->AddField("vdate", $FIELD_DB->VARCHAR, 	255, $l["vdate"], $FIELD_HTML->TEXT, 20, 0);

	$rec->setValue("active", 1);
//	$rec->setValue("status", 0);

	$rec->setValueList("status", array(0=>"User", 1=>"Admin", 2=>"Moderator"), 0);
//	$rec->setValueList("status", array(0=>"User", 1=>"Admin", 2=>"Moderator", 3=>"Seller", 4=>"Affiliate"), 0);
//	$rec->setBitSet("acl", $ACL->NAMES, $ACL->ACCESS);


	$rec->AddField("user_id", $FIELD_DB->INT, 0, $m6, $FIELD_HTML->SELECTM, 0, 1);

	$rec->setLookup("user_id", "{$CFG->DB_Prefix}users", "id", "login", "");



	$rec->setOnSubmit("checkPassword(this)");

function page_process()
{
	GLOBAL $rec, $CFG, $_sys_act, $_sys_total;

	CM_process($GLOBALS["rec"]);
}


function page_show()
{
	GLOBAL $_sys_act, $rec, $currDoc, $sTITLE, $id;

	if ($_GET["act"] == 'new')
	{
?>
<script>
<!--
function checkPassword(f)
{
	if (f.pass.value != "" && f.pass.value != f.pass2.value)
	{
		alert(<?=$l["alert"]?>);
		f.pass.focus();
		return false;
	}
	return true;
}
//-->
</script>
<?
		$hdr = "New record";
		if ($id>0)
			$hdr = "Edit record";
		CM_showForm($rec);
	}
	else
	{


		$del = 1 * $_SESSION["in_del"];
		if ($_SESSION["where_m"] != "")
			$rec->setWhere("deleted='{$del}' AND ".$_SESSION["where_m"]);
		CM_showTable($rec);
	}
	toolsWriteScript();

}// end function show()


function page_filter()
{
	GLOBAL $_sys_act, $rec, $currDoc, $sTITLE, $id;

		CM_showFilter($rec);

}// end function filter()
?>