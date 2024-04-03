<?php

$sTITLE = $CFG->Locale["system_infoPage"]." '{$ADM->oPageInfo->name}'";

	$rec = $records["contact"];
	$rec->setWhere("page_id='{$_PAGE_ID}'");	// AND btype='info'");

	$rec->setValue("btype", "info");
	$rec->setValue("page_id", $_PAGE_ID);

function page_process()
{
	CM_process($GLOBALS["rec"]);
}


function page_show()
{
	GLOBAL $rec, $ADM;

	if ($_GET["act"] == 'new')
	{
		CM_showForm($rec);
	}
	else
	{
		CM_showTable($rec);
	}
	toolsWriteScript();

}


function page_filter()
{
	GLOBAL $_sys_act, $rec, $currDoc, $sTITLE, $id;

	CM_showFilter($rec);
}
?>