<?php

	$sTITLE = ($CFG->SYS_LANG_ADMIN=='ru' ? "Новости" : "News");
	$rec = $records["news"];
	$rec->setWhere("page_id='{$_PAGE_ID}'");
	$rec->setValue("hot", 0);
	$rec->setValue("cdate", sqlDateNow());
	$rec->setDateRange("cdate", 2004, date("Y"));
	$rec->setValue("page_id", $_PAGE_ID);

function page_process()
{
	GLOBAL $_sys_act, $rec, $currCat, $sTITLE, $id, $_POST, $CM_lastActiveID, $CFG;
	CM_process($rec, 0);}


function page_show()
{
	GLOBAL $rec, $ADM;

	if ($_GET["act"] == 'new')
	{
		CM_showForm($rec);
	}
	else
	{
		CM_showTable($rec, "cdate DESC, pos");
	}
	toolsWriteScript();

}

function page_filter()
{
	GLOBAL $_sys_act, $rec, $currDoc, $sTITLE, $id;

	CM_showFilter($rec);
}
?>