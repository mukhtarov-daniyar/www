<?php


$sTITLE = ($CFG->SYS_LANG_ADMIN=='ru' ? "Главная страница" : "Homepage")." '{$_SESSION["ses_page_name"]}'";

	$rec2 = $records["homepage_events"];
	$rec2->setWhere("page_id='{$_PAGE_ID}' AND btype='info'");
	$rec2->setXCode("top");

	$rec2->setValue("btype", "info");
	$rec2->setValue("page_id", $_PAGE_ID);

function page_process()
{
	CM_process($GLOBALS["rec2"]);
}


function page_show()
{
	GLOBAL $rec2, $ADM;

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