<?php



$sTITLE = $CFG->Locale["system_pages"]."";
	$rec = $records["sys_pages"];

	$rec->setValueList("access", array(0=>$CFG->Locale["system_accessAll"], 1=>$CFG->Locale["system_accessReg"], 2=>$CFG->Locale["system_accessSpec"]), 0);
//	$rec->setStatus("access_list", $FIELD_STATUS->INVISIBLE);
	$rec->setFunction("access", " onchange='var o = document.getElementById(\"tr_access_list\").style; if(this.value==2) o.display=\"".($CFG->Browser=="FF" ? "table-row":"block")."\"; else o.display=\"none\"'");
	$rec->setLookup("access_list", "{$CFG->DB_Prefix}users", "id", "login", "login", "");//"sys_language>=0");
	$rec->setLookup("moderator", "{$CFG->DB_Prefix}users", "id", "login", "login", "status='2'");//"sys_language>=0");

	$rec->setValue("menu_flag", 1);
	$rec->setValueList("menu_win", array(0=>$CFG->Locale["system_openInSame"], 1=>$CFG->Locale["system_openInNew"]), 0);

	$show_row = "block";
	if ($CFG->Browser=="FF" || $CFG->Browser=="OP")
		$show_row = "table-row";

	$rec->setFunction("menu_flag", " onclick='var o1=document.getElementById(\"tr_menu_name\").style; var o2=document.getElementById(\"tr_menu_win\").style;  if(this.checked) {o1.display=\"{$show_row}\"; o2.display=\"{$show_row}\"; } else {o1.display=\"none\"; o2.display=\"none\";}'");

	$rec->setFunction("name", "onchange='if (this.form.menu_name.value==\"\") this.form.menu_name.value=this.value; if (this.form.html_title.value==\"\") this.form.html_title.value=this.value; '");
	$rec->setFunction("menu_name", "onchange='if (this.value==\"\") this.value=this.form.name.value;'");
	$rec->setFunction("html_title", "onchange='if (this.value==\"\") this.value=this.form.name.value;'");
	$rec->setFunction("tmpl_u", "onchange='if (this.value==\"link\") document.getElementById(\"xcode_ubtn\").style.visibility=\"visible\"; else document.getElementById(\"xcode_ubtn\").style.visibility=\"hidden\"; '");

//*	$rec->setValue("menu_id", $_SESSION["currMenu"]);
	

	$types = array();
/*
	$d = dir("modules");
	while($entry=$d->read())
	{
		if ($entry != '.' && $entry!=".." && $entry[0]!="_")
			if (is_dir("modules/".$entry))
			{
				$types[$entry] = $entry;
			}
	}
*/
	if (is_file("{$CFG->ROOT_PATH}{$CFG->SITES[$CFG->CURRENT_SITE][1]}admin/modules/_templates.php"))
		include("{$CFG->ROOT_PATH}{$CFG->SITES[$CFG->CURRENT_SITE][1]}admin/modules/_templates.php");
	else
		include("{$CFG->ROOT_PATH}admin/modules/_templates.php");
	while(list($k, $v) = each($CM_templates))
		$types[$k] = $v;
	
	$rec->setValueList("tmpl_u", $types, 'info');

//	$rec->setValue("parent_id", 0);

//	$rec->setLookupTree("parent_id", $CFG->DB_Prefix."pages", "id", "menu_name", "parent_id", "menu_name", "-(sys_language='{$CFG->SYS_LANG}' OR parent_id='0')", 0);

//	$rec->setLookupTree("parent_id", $CFG->DB_Prefix."pages", "id", "menu_name", "parent_id", "pos, menu_name", "(sys_language='{$CFG->SYS_LANG}') AND id<>'{$id}'", 0);
	$rec->setLookupTree("parent_id", $CFG->DB_Prefix."pages", "id", "menu_name", "parent_id", "pos, menu_name", "(sys_language='{$CFG->SYS_LANG}' AND id<>'{$id}') OR (id<'10')", 0);

	if ($_GET["setCat"] > 0)
		$rec->setValue("parent_id", $_GET["setCat"]);


$edt = 0;

function page_process()
{
	global $CFG, $rec, $currCat, $sTITLE, $id, $CM_lastActiveID, $ADM;


	$id = getGP("pid");

//	$_sys_act = $CFG->aAdminParams["_sys_act"];

//	if ($_sys_act == ""  &&  $_GET["act"] != "")

	$_sys_act = getGP("act");

//showArray($_POST);

//die("==={$_sys_act}");

	$id *= 1;
	if ($_sys_act == 'mhide' || $_sys_act == 'mshow')
	{
		$h = ($_sys_act == 'mhide' ? 0 : 1);
		$sql = "UPDATE {$CFG->DB_Prefix}pages SET menu_flag='{$h}' WHERE id='{$id}' AND sys_language='{$CFG->SYS_LANG}'";
		$CFG->DB->query($sql);
	}

	if ($_sys_act == 'hide' || $_sys_act == 'show')
	{
		$h = ($_sys_act == 'hide' ? 0 : 1);
		$sql = "UPDATE {$CFG->DB_Prefix}pages SET visible='{$h}' WHERE id='{$id}' AND sys_language='{$CFG->SYS_LANG}'";
		$CFG->DB->query($sql);
	}

	if ($_sys_act == 'del')
	{
		geleteTree($id);
	}

	if (($_sys_act != 'edit' && $_sys_act != 'new' && $_sys_act != '') || $_GET["act"] != "")
	{
//die("OK - {$_GET["act"]}");
		clearCache();
	}

	$_GET["id"] = $id;
	$_POST["id"] = $id;

 	CM_tree_process($rec, 1);

//	if ($_sys_act == "save")
//		$ADM->_PAGE_ID = CM_saveRecord($rec);

	if ($_sys_act != "" && $_sys_act != "edit")
	{
		if ($id == 0 && $_sys_act == "save")
			$REDIRECT = "main.php?mod={$_POST["tmpl_u"]}&part={$_POST["tmpl_u"]}&pid={$CM_lastActiveID}";	//{$ADM->_PAGE_ID}";
		else
			$REDIRECT = $_SERVER["HTTP_REFERER"];

		redirect($REDIRECT);

//die("----");
	}
//die("-[{$_sys_act}]-");
}

function page_show()
{
	global $CFG, $_sys_act, $rec, $currDoc, $sTITLE, $id, $list_anker, $db, $REDIRECT_URL, $edt;

	$id = getGP("pid");

	if ($_GET["act"] == 'edit')
	{
		CM_showForm($rec);
?>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
	if (document.getElementById("tmpl_u").value!="link")
		document.getElementById("xcode_ubtn").style.visibility="hidden";
/*
	var o = document.getElementById("tr_access_list").style;
	if(document.getElementById("access").value == 2)
		o.display = "block";
	else
		o.display = "none";
*/
//--><!]]>
</script>
<?
	}
	else
	{
		
	echo "<br /><br /><table align='center'><tr><td>";
	if ($edt & SYS_FLAG_ADD)
	{
	    echo "<form name='frm1' method='post'>\n";
		echo "<input type='button' class='inp' value='{$CFG->Locale["button_new"]}' onclick='_sys_edt(0);' />";
		echo "</form>";
	}


	$where = $rec->where;
//	if ($where != "")
//		$where = "(($where) OR parent_id='0')";
//	else
//		$where = "(sys_language='{$CFG->SYS_LANG}' OR parent_id='0')";

//	$where = "(sys_language='{$CFG->SYS_LANG}' AND ((visible='1' AND parent_id='0') OR parent_id>'0'))";
	$where = "( (visible='1' AND parent_id='0') OR (sys_language='{$CFG->SYS_LANG}' AND parent_id>'0'))";
	echo CM_tree_show("pages", "<b><u>{$CFG->Locale["system_pages"]}</u></b>", $rec->table, "id", "menu_flag, menu_name AS name, tmpl_u, access, moderator", "parent_id", "pos, name", $where);


	if ($edt & SYS_FLAG_ADD)
	{
	    echo "<form name='frm2' method='post'>\n";
		echo "<input type='button' class='inp' value='{$CFG->Locale["button_new"]}' onclick='_sys_edt(0);' />";
		echo "</form>";
	}

	echo "</td></tr></table>";

//	if ($list_anker != "")
//		echo "<script>location.href = location.href+'#ank_$list_anker';</script>";


	}
	toolsWriteScript();

}// end function show()


function showRow($obj, $lev)
{
	global $CFG, $b, $REDIRECT_URL, $CM_templates;

		if ($b == $CFG->colorBG4)
			$b = $CFG->colorBG3;
		else
			$b = $CFG->colorBG4;
		$id = $obj->id;

		$tmpl_u = $CM_templates[$obj->tmpl_u];

		$url = "<span class=\"gray\"><b>".hs($obj->name)."</b></span> ({$tmpl_u})";
		$ok = 0;

		if ($CFG->USER->is_masterLoggedIn())
			$ok = 1;

//		if ("".strpos($obj->moderator, ",{$CFG->USER->USER_ID},") != "" && $CFG->USER->USER_STATUS==2)
		if (is_moderator($obj->id, $CFG->USER->USER_ID) && $CFG->USER->USER_STATUS==2)
			$ok = 1;

		if ($CFG->USER->USER_STATUS==1)
			$ok = 1;

		$b_edt   = "<a href=\"main.php?mod=_1_pages&part=pages&act=edit&pid={$id}\"><img src='{$CFG->ADMIN_URL}tools/edt.gif' alt='{$CFG->Locale["system_edit"]}' title='{$CFG->Locale["system_edit"]}' width='12' height='12' border='0' align='absmiddle' /></a>";
		$b_add   = "<a href=\"main.php?mod=_1_pages&part=pages&act=edit&setCat={$id}\"><img src='{$CFG->ADMIN_URL}tools/add.gif' alt='{$CFG->Locale["system_add"]}'  title='{$CFG->Locale["system_add"]}' width='16' height='16' border='0' align='middle' /></a>";
		$b_del   = "<a href=\"main.php?mod=_1_pages&part=pages&act=del&pid={$id}\" onclick=\"return confirm('".ecrane($CFG->Locale["confirm_del_tree"])."');\"><img src='{$CFG->ADMIN_URL}tools/del2.gif' alt='{$CFG->Locale["system_del"]}'  title='{$CFG->Locale["system_del"]}' width='16' height='16' border='0' align='middle' /></a>";
		$b_hide  = "<a href=\"main.php?mod=_1_pages&part=pages&act=hide&pid={$id}\"><img src='{$CFG->ADMIN_URL}tools/hide.gif' border='0' alt='{$CFG->Locale["system_hide"]}' title='{$CFG->Locale["system_hide"]}' align='middle' /></a>";
		$b_show  = "<a href=\"main.php?mod=_1_pages&part=pages&act=show&pid={$id}\"><img src='{$CFG->ADMIN_URL}tools/show.gif' border='0' alt='{$CFG->Locale["system_show"]}' title='{$CFG->Locale["system_show"]}' align='middle' /></a>";
		$b_mhide = "<a href=\"main.php?mod=_1_pages&part=pages&act=mhide&pid={$id}\"><img src='{$CFG->ADMIN_URL}tools/mhide.gif' border='0' alt='{$CFG->Locale["system_mhide"]}' title='{$CFG->Locale["system_mhide"]}' align='middle' /></a>";
		$b_mshow = "<a href=\"main.php?mod=_1_pages&part=pages&act=mshow&pid={$id}\"><img src='{$CFG->ADMIN_URL}tools/mshow.gif' border='0' alt='{$CFG->Locale["system_mshow"]}' title='{$CFG->Locale["system_mshow"]}' align='middle' /></a>";


		if ($ok == 1)
			$url = "<a class='menu' href='main.php?mod={$obj->tmpl_u}&part={$obj->tmpl_u}&pid={$obj->id}'><b>".hs($obj->name)."</b></a> ({$tmpl_u} {$b_edt})";

		if ($obj->access == "1")
			$url .= " <img src='{$CFG->ADMIN_URL}tools/lock1.gif' alt='lock1' title='{$CFG->Locale["system_accessReg"]}' width='16' height='16' border='0' align='middle' />";
		if ($obj->access == "2")
			$url .= " <img src='{$CFG->ADMIN_URL}tools/lock2.gif' alt='lock1' title='{$CFG->Locale["system_accessSpec"]}' width='16' height='16' border='0' align='middle' />";
		$acl = 0;

		if ($obj->visible == 1)
			$add1  = $b_hide;
		else
			$add1  = $b_show;

		if ($obj->menu_flag == 1)
			$add2  = $b_mhide;
		else
			$add2  = $b_mshow;


/*
		$add1 = " <a href='pageopt.php?pid={$obj->id}'><img src='{$CFG->ADMIN_URL}tools/opt.gif' border='0' alt='Опции' /></a>";
		$sql = "SELECT count(id) FROM {$CFG->DB_Prefix}options WHERE module_id='$obj->tmpl_u' AND visible='1'";
		$cntopt = 1*getSQLField($sql);
		if (!$cntopt)
*/
//			$add1 = "";

//		$std = "all";
//		$add = $add2.$add1;

		$std = "";
		$menuID = getTopParent($obj->id, 0);
		$add = "{$b_add} {$add1} {$add2}";
		if ($obj->id > 100 && $menuID < 9)
			$add .= " {$b_del}";

//		$add .= " <a href='main.php?page=_6_cfg/baners3.php&pid={$obj->id}'>[ADS]</a>";

		if ($lev == 0)
		{
//			$std = "add";
			$add = "";
			$url = "<span class='menu_title'>".hs($obj->name)."</span>";
			$std = "";
			$add = $b_add;
		}
		if ($menuID == 9 && !$CFG->USER->is_masterLoggedIn())
		{
//			$std = "edit";
//			$add = $add1;
			$std = "";
			$add = "";
		}
		if ($obj->id == 100 && !$CFG->USER->is_masterLoggedIn())
		{
//			$std = "edit,visible,add";
//			$add = $add2.$add1;
			$std = "";
		}
		if ($obj->id == 9 && !$CFG->USER->is_masterLoggedIn())
		{
			$std = "";
			$add = "";
		}

		if (!$CFG->USER->is_masterLoggedIn() && $CFG->USER->USER_STATUS != 1)
		{
			$std = "";
			$add = "";
		}


		return (array($url, $acl, $add, $std));
}

function geleteTree($id)
{
	global $CFG, $rec;

	$id *= 1;

	$q = "SELECT id FROM {$rec->table} {$rec->where} AND parent_id='{$id}'";
	$l = getSQLArrayO($q);

	for($i=0; $i<sizeof($l); $i++)
		geleteTree($l[$i]->id);

	$sql = "DELETE FROM {$rec->table} WHERE {$rec->lang_support_where_and} id='{$id}'";
	$CFG->DB->query($sql);

	$sql = "DELETE FROM {$CFG->DB_Prefix}docs WHERE {$rec->lang_support_where_and} page_id='{$id}'";
	$CFG->DB->query($sql);
}

	include("_tree_processing.php");

?>