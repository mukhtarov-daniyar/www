<?php

$sTITLE = ($CFG->SYS_LANG_ADMIN=='ru' ? "Вступительный блок к Галереи" : "Intro for Gallery ")." '{$_SESSION["ses_page_name"]}'";

	$rec2 = new DBRecord("{$CFG->DB_Prefix}docs", $id, 1);
	$rec2->setXCode("top");
	$rec2->setWhere("page_id='{$_PAGE_ID}' AND btype='intro'");
	$rec2->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec2->AddField("page_id", $FIELD_DB->INT, 50, "Страница", $FIELD_HTML->HIDDEN, 10, 0);
	$rec2->AddField("body", $FIELD_DB->TEXT, 0, "Текст", $FIELD_HTML->EDITOR, 10, 1);
	$rec2->AddField("btype", $FIELD_DB->VARCHAR, 0, "ptype", $FIELD_HTML->HIDDEN, 50, 0);

	$rec2->setValue("btype", "intro");
	$rec2->setValue("page_id", $_PAGE_ID);

function page_process()
{
	CM_process($GLOBALS["rec2"]);
}


function page_show()
{
	GLOBAL $_sys_act, $rec2, $currCat, $sTITLE, $id, $CFG, $add, $_PAGE_ID, $module, $oPageInfo;

include("form_menu.php");

	if ($_GET["act"] == 'new')
	{
			CM_showForm($rec2);
	}
	else
	{
		CM_showTable($rec2);

	}
	toolsWriteScript();
}


function page_filter()
{
	GLOBAL $_sys_act, $rec2, $currDoc, $sTITLE, $id;

		CM_showFilter($rec2);
}
?>