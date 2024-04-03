<?php

	if ($CFG->SYS_LANG_ADMIN=='ru')
	{
		$m1 = "Ответы";
		$m2 = "Страница";
		$m3 = "Дата";
		$m4 = "IP";
		$m5 = "Дополнительно";
		$m6 = "Пользователь";
	}
	else
	{
		$m1 = "Answers";
		$m2 = "Page";
		$m3 = "Date";
		$m4 = "IP";
		$m5 = "More info";
		$m6 = "User";
	}


$sTITLE = "{$m1} '{$_SESSION["ses_page_name"]}'";


	$rec = new DBRecord("{$CFG->DB_Prefix}form_answer", $id, 0);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("page_id", $FIELD_DB->INT, 50, $m2, $FIELD_HTML->HIDDEN, 10, 0);

	$rec->AddField("cdate", $FIELD_DB->VARCHAR, 0, $m3, $FIELD_HTML->TEXT, 80, 1);
	$rec->AddField("remote", $FIELD_DB->VARCHAR, 0, $m4, $FIELD_HTML->TEXT, 80, 1);
	$rec->AddField("info", $FIELD_DB->INT, 0, $m5, $FIELD_HTML->TEXTAREA, 0, 1);
	$rec->AddField("user_id", $FIELD_DB->INT, 0, $m6, $FIELD_HTML->SELECT, 0, 1);

	$rec->setValue("page_id", $_PAGE_ID);
	$rec->setLookup("user_id", "{$CFG->DB_Prefix}users", "id", "login", "login", "");


	$CFG->FORM = new Form();

function page_process()
{
	global $CFG;
	if ($_GET["act"] == "del")
	{
		$id = 1 * $_GET["act_id"];
		$sql = "DELETE FROM {$CFG->DB_Prefix}form_answer_fields WHERE answer_id='{$id}'";
		$CFG->DB->query($sql);
		$sql = "DELETE FROM {$CFG->DB_Prefix}form_answer WHERE id='{$id}'";
		$CFG->DB->query($sql);
	}
	CM_process($GLOBALS["rec"]);
}


function page_show()
{
	GLOBAL $_sys_act, $rec, $rec2, $rec3, $currCat, $sTITLE, $id, $CFG, $add, $_PAGE_ID, $module, $CM_page;

include("form_menu.php");

	if ($_sys_act == 'new')
	{
//		CM_showForm($rec);
	}
	else
	{
//		CM_showTable($rec, "");
	}
//	toolsWriteScript();



	$cnt = $CFG->FORM->loadForm($_PAGE_ID);

?>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
function del(id)
{
	if (confirm("<?=$CFG->Locale["confirm_del_tree"]?>"))
		location.replace("<?=$CFG->REDIRECT_URL?>?page=<?=$CM_page?>&act_id="+id+"&act=del"+"&<?=$REDIRECT_QUERY_STRING?>");
}
//--><!]]>
</script>

	<table width="100%" style="background-color:<?=$CFG->colorBG2?>;" cellpadding='2' cellspacing="1" border="0">
	<thead class="td_hdr4">
	<tr>
		<th>Инфо</th>
<?
		for ($i=0; $i<$cnt; $i++)
		{
?>
		<th align="center" nowrap="nowrap"><?

$label = $CFG->FORM->getLabel($i, 1);

if (utf8_strlen($label) > 10)
{
	echo "<span title=\"".ecrane($label)."\">".utf8_substr($label, 0, 10)."...</span>";
}
else
{
	echo $label;
}
?></th>
<?
		}
?>
	</tr>
	</thead>

	<tbody style="background-color:#FFFFFF;">
<?

	$cntData = $CFG->FORM->loadData("DESC", 1);

	for ($d=0; $d<$cntData; $d++)
	{
		$row = $CFG->FORM->getRow($d);
		echo "\t<tr>\n";
		echo "\t<td nowrap=\"nowrap\">".$row["cdate"]."<br />IP: ".$row["remote"]."<br />";
		if ($row["visible"])
			echo " <a href='{$CFG->REDIRECT_URL}?page={$CM_page}&amp;id={$row["id"]}&amp;_sys_act=hide'><img src='tools/hide.gif' width='16' height='16' border='0' alt='hide' title='{$CFG->Locale["tree_hide"]}' /></a>";
		else
			echo " <a href='{$CFG->REDIRECT_URL}?page={$CM_page}&amp;id={$row["id"]}&amp;_sys_act=show'><img src='tools/show.gif' width='16' height='16' border='0' alt='show' title='{$CFG->Locale["tree_show"]}' /></a>";
		echo " <a href='javascript: del({$row["id"]});'><img src='tools/del2.gif' width='16' height='16' border='0' alt='del' title='{$CFG->Locale["tree_delete"]}' /></a>";
		echo "</td>\n";
		for ($i=0; $i<$cnt; $i++)
		{
/*
?>
		<td><?=parseWWW(ecrane($CFG->FORM->getData($d, $i)))?></td>
<?
*/

			$data = $CFG->FORM->getData($d, $i);
			if ($CFG->FORM->getType($i) == 12)
				$data = "<a target=\"_blank\" href=\"{$CFG->DOC_PATH}formfiles/{$_PAGE_ID}/{$data}\">{$data}</a>";
			else
				$data = ecrane($data);
?>
		<td><?=$data?></td>
<?
		}
		echo "\t</tr>\n";
	}


?>
	</tbody>
	</table>
<?



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