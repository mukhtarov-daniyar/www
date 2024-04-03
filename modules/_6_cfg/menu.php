<?php

$sTITLE = "Список Меню";

	$rec = new DBRecord("{$CFG->DB_Prefix}pages", $id, 0);
	$rec->setWhere("parent_id='0'");
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("xcode", $FIELD_DB->VARCHAR, 0, "Код", $FIELD_HTML->TEXT, 10, 1);
	$rec->AddField("menu_name", $FIELD_DB->VARCHAR, 0, "Название", $FIELD_HTML->TEXT, 60, 1);

function page_process()
{
	GLOBAL $_sys_act, $rec, $sTITLE, $id;

	CM_process($rec);
}

function page_show()
{
	GLOBAL $_sys_act, $rec, $sTITLE, $id;

	if ($_sys_act == 'new')
	{
		$hdr = "Новая запись";
		if ($id>0)
			$hdr = "Редактирование";
		CM_showForm($rec);
	}
	else
	{
?>


<br />

<?php
		CM_showTable($rec);
?>

<br />

<?php
	}

}
?>