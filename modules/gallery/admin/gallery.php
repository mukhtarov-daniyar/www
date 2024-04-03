<?php

$sTITLE = "&nbsp;";

	$rec = new DBRecord("{$CFG->DB_Prefix}gallery", $id, 1);
	$rec->setWhere("page_id='{$_PAGE_ID}' ");
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("cdate", $FIELD_DB->VARCHAR, 0, "Дата", $FIELD_HTML->TEXT, 20, 0);


	$rec->AddField("page_id", $FIELD_DB->INT, 50, "Страница", $FIELD_HTML->HIDDEN, 10, 0);
	$rec->AddField("name", $FIELD_DB->TEXT, 0, "Название", $FIELD_HTML->TEXT, 80, 1);
	$rec->AddField("gallery", $FIELD_DB->VARCHAR, 0, "Фото", $FIELD_HTML->FILE, 80, 1);

	$rec->setValue("page_id", $_PAGE_ID);
	
	$rec->setValue("cdate", sqlDateNow());
	$rec->setDateRange("cdate", 2004, date("Y"));
	
function page_process()
{
	CM_process($GLOBALS["rec"]);
}


function page_show()
{
	global $rec, $ADM;

	if ($_GET["act"] == 'new')
	{
			CM_showForm($rec);
	}
	else
	{
		CM_showTable($rec, "id DESC, pos");
	}
	toolsWriteScript();
}


function page_filter()
{
	global $_sys_act, $rec, $currDoc, $sTITLE, $id;

		CM_showFilter($rec);
}
?>