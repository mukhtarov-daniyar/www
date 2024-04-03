<?php

	$sTITLE = "Города Казахстана";
	
	$rec = new DBRecord("{$CFG->DB_Prefix}city", $id, 1);

	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("page_id", $FIELD_DB->INT, 50, "Страница", $FIELD_HTML->HIDDEN, 10, 0);
	$rec->AddField("name", $FIELD_DB->VARCHAR, 0, 'Имя', $FIELD_HTML->TEXT, 80, 1);


	$rec->setWhere("page_id='{$_PAGE_ID}'");
	$rec->setValue("page_id", $_PAGE_ID);

	
	

function page_process()
{
	global $_sys_act, $rec, $currCat, $sTITLE, $id, $_POST, $CM_lastActiveID, $CFG;
	CM_process($rec, 0);}


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
?>