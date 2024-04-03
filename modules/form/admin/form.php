<?php

	if ($CFG->SYS_LANG_ADMIN=='ru')
	{
		$m1 = "Форма";
		$m2 = "Страница";
		$m3 = "Название";
		$m4 = "Тип";
		$m5 = "Обязательное";
		$m6 = "Значения";
		$m7 = "По умолчанию";
		$m8 = "Макс. длина";
		$m9 = "Размер поля";
		$m10 = "Style";
		$m11 = "Class";
	}
	else
	{
		$m1 = "Form";
		$m2 = "Page";
		$m3 = "Title";
		$m4 = "Type";
		$m5 = "Reqired";
		$m6 = "Values";
		$m7 = "Default";
		$m8 = "MaxLength";
		$m9 = "Size";
		$m10 = "Style";
		$m11 = "Class";
	}


$sTITLE = "{$m1} '{$_SESSION["ses_page_name"]}'";

	$CFG->Form = new Form();

	$rec = new DBRecord("{$CFG->DB_Prefix}form_fields", $id, 1);
	$rec->setWhere("page_id='{$_PAGE_ID}'");
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("page_id", $FIELD_DB->INT, 50, $m2, $FIELD_HTML->HIDDEN, 10, 0);

	$rec->AddField("name", $FIELD_DB->VARCHAR, 0, $m3, $FIELD_HTML->TEXT, 80, 1);
	$rec->AddField("ctype", $FIELD_DB->INT, 0, $m4, $FIELD_HTML->SELECT, 0, 1);
	$rec->AddField("req", $FIELD_DB->INT, 0, $m5, $FIELD_HTML->CHECKBOX, 0, 1);
	$rec->AddField("len", $FIELD_DB->INT, 0, $m8, $FIELD_HTML->TEXT, 5, 0);
	$rec->AddField("size", $FIELD_DB->INT, 0, $m9, $FIELD_HTML->TEXT, 5, 0);
	$rec->AddField("style", $FIELD_DB->VARCHAR, 0, $m10, $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("class", $FIELD_DB->VARCHAR, 0, $m11, $FIELD_HTML->TEXT, 20, 0);
	$rec->AddField("defval", $FIELD_DB->VARCHAR, 0, $m7, $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("vals", $FIELD_DB->TEXT, 0, $m6, $FIELD_HTML->TEXTAREA, 10, 0);

	$rec->setValue("page_id", $_PAGE_ID);
	$rec->setValueList("ctype", $CFG->Form->getFieldTypes(), 0);


function page_process()
{
	CM_process($GLOBALS["rec"]);
}


function page_show()
{
	GLOBAL $_sys_act, $rec, $rec2, $rec3, $currCat, $sTITLE, $id, $CFG, $add, $_PAGE_ID, $module;

include("form_menu.php");

	if ($_GET["act"] == 'new')
	{
		CM_showForm($rec);
	}
	else
	{
		CM_showTable($rec, "");
	}
	toolsWriteScript();
}


function page_filter()
{
	GLOBAL $_sys_act, $rec, $rec2, $rec3, $currDoc, $sTITLE, $id;

	if ($_GET["_sys_txcode"] == "forum")
		CM_showFilter($rec2);
	else
		CM_showFilter($rec);
}
?>