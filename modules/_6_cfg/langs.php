<?php

$sTITLE = $CFG->Locale["system_Languages"];

	$rec = new DBRecord("{$CFG->DB_Prefix}langs", $id, 0);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("title", $FIELD_DB->VARCHAR, 0, $CFG->Locale["system_name"], $FIELD_HTML->TEXT, 60, 1);
	$rec->AddField("charset", $FIELD_DB->VARCHAR, 0, "CharSet", $FIELD_HTML->TEXT, 20, 1);
	$rec->AddField("shortname", $FIELD_DB->VARCHAR, 5, $CFG->Locale["system_code"], $FIELD_HTML->TEXT, 5, 1);
	$rec->AddField("shortname_old", $FIELD_DB->NONE, 5, "Short Name", $FIELD_HTML->HIDDEN, 5, 0);
//	$rec->AddField("built", $FIELD_DB->INT, 0, "Data exists", $FIELD_HTML->CHECKBOX, 0, 1);
	$rec->setValue("charset", "utf-8");
	$rec->setStatus("built", 2);
	$rec->setValueFrom("shortname_old", "shortname");

function page_process()
{
	GLOBAL $rec, $_sys_act, $ROOT_PATH, $id, $CFG, $CM_lastActiveID;

	if ($_POST["do_copy"] != "")
	{
		copyLang($_POST["lang_from"], $_POST["lang_to"]);
	}


	CM_process($rec, 1);

	if ($_POST["deleteBtn"] != '')
	{
		delLang();
//		die("<script>parent.location.reload()</scipt>");
	}

	if ($_POST["act"] == 'save')
	{
		$shortname_old = $_POST["shortname_old"];
		$shortname = $_POST["shortname"];

		$t = getdate();
		if ($shortname == "")
			$shortname = $t["seconds"];
		$SN = $shortname;

		if ($shortname_old != "")
		{
			// do somethimg on rename language
		}
		else
		{
			// do somethimg on create language
			$last_id = $CM_lastActiveID;

			$sql = "SELECT id FROM {$CFG->DB_Prefix}pages WHERE id='100' AND sys_language='{$last_id}'";
			$id  = 1*getSQLField($sql);
			if ($id <= 0)
			{
				$sql  = "INSERT INTO {$CFG->DB_Prefix}pages (id, parent_id, name, menu_name, menu_flag, tmpl_u, visible, pos, sys_language) VALUES (";
				$sql .= "'100','1','HomePage_{$shortname}','Homepage_{$shortname}','0','homepage','1','10','{$last_id}')";
				$CFG->DB->query($sql);
			}
		}

//		die("<script>parent.location.href=\"index.php\";</script>");
		redirect("/admin/main.php?mod=_6_cfg&part=langs");
	}

}

$copy_msg = "";
function page_show()
{
	GLOBAL $_sys_act, $rec, $sTITLE, $id, $copy_msg, $CFG;

	if ($_GET["act"] == 'new')
	{
		CM_showForm($rec);
	}
	else
	{
		CM_showTable($rec, "title");


?>


<hr size='1' width='98%' />
<center>
<font color='green'><?php echo $_SESSION["copy_msg"]; ?></font>
<form name='f1' method='post' onsubmit='return testit(this)' action='main.php'>
<input type='hidden' name='mod'       id='mod'         value='_6_cfg' />
<input type='hidden' name='part'      id='part'        value='langs' />
<input type='hidden' name='act'       id='act'         value='copy' />
<input type='hidden' name='pid'       id='pid'         value='0' />
<input type='hidden' name='id'        id='id'          value='0' />
<?=$CFG->Locale["system_copyData"]." ".$CFG->Locale["from"]?> <select name='lang_from'>
<?php	showLookupSelect("my_langs", "id", "title", "title", "", "", "");	?>
</select>
<?=$CFG->Locale["to"]?> <select name='lang_to'>
<?php	showLookupSelect("my_langs", "id", "title", "title", "", "", "");	?>
</select>
<input type='submit' name='do_copy' value='<?=$CFG->Locale["system_copy"]?>' class='inp' />
</form>
</center>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
parent.menuFrame.document.location.reload();
function testit(f)
{
	var l1 = f.lang_from.selectedIndex;
	var l2 = f.lang_to.selectedIndex;
	if (l1 == l2)
	{
		alert("<?=$CFG->Locale["system_cantCopyItself"]?>");
		return false;
	}
	l1 = f.lang_from.options[l1].text;
	l2 = f.lang_to.options[l2].text;
	if (confirm("<?=$CFG->Locale["system_reallyCopy"]." ".$CFG->Locale["from"]?> "+l1+"<?=$CFG->Locale["to"]?> "+l2+" ?"))
	{
		return true;
	}
	return false;
}
//--><!]]>
</script>

<?php


	}
	$_SESSION["copy_msg"] = "";
}

function copyLang($lang_from, $lang_to)
{
	GLOBAL $ADMIN_PATH, $ROOT_PATH, $LANGS, $copy_msg, $CFG;

	$db = createConnection();
	$db1 = createConnection();


/*
	mysql_connect($CFG->DB_Host, $CFG->DB_User, $CFG->DB_Password);
	$result = mysql_listtables ($CFG->DB_Name);
	$num = 0;
	while ($num < mysql_num_rows ($result))
	{
	    $tables[$num] = mysql_tablename ($result, $num);
	    $num++;
	}
*/

	$tables = getTablesList();

	$num = sizeof($tables);
	for ($j=0; $j<$num; $j++)
	{
		$res = mysql_list_fields($CFG->DB_Name, $tables[$j]);
		$fnum = mysql_num_fields($res);
		echo "<br><b>{$tables[$j]} - ".$fnum."</b>";
		echo "\$tables[] = '{$tables[$j]}';<br>";
		$FIELDS = "";
		$r = array();
		$ok = 0;
		for ($i=0; $i<$fnum; $i++)
		{
			$fname = mysql_field_name($res, $i);
			if ($fname == "sys_language")
			{
				$ok = 1;
				continue;
			}
			$FIELDS .= ", ".$fname;
			$r[] = $fname;
		}

		if ($ok == 0)
			continue;

		$sql = "DELETE FROM {$tables[$j]} WHERE sys_language='{$lang_to}'";
echo "<br />{$sql}";
		$db->query($sql);
		$sql = "SELECT sys_language{$FIELDS} FROM {$tables[$j]} WHERE sys_language='{$lang_from}'";
echo "<br />{$sql}";
		$l = getSQLArrayA($sql);
		for ($k=0; $k<sizeof($l); $k++)
		{
			$VALS = "'{$lang_to}'";
			for ($m=0; $m<sizeof($r); $m++)
			{
				$VALS .= ", '". addSlashes( $l[$k][ $r[$m] ] )."'";
			}
			$sql = "INSERT INTO {$tables[$j]} (sys_language{$FIELDS}) VALUES ({$VALS})";
echo "<br />{$sql}";
			$db1->query($sql);
		}
	}
	$_SESSION["copy_msg"] = "All done.";
die("---");
}

function delLang()
{
	GLOBAL $ROOT_PATH, $_sys_total, $LANGS;

	$db = createConnection();

	$recs = getTablesList();

	for($i=0; $i<$_sys_total; $i++)
		if ($GLOBALS["del_$i"]>0)
		{
			$id = $GLOBALS["del_$i"];

			for ($j=0; $j<sizeof($recs); $j++)
			{
				$rec = $recs[$j];
				$sql = "DELETE FROM $rec->table WHERE sys_language='$id'";
				$db->query($sql);
			}
		}
}

function getTablesList()
{
	global $CFG;

	$tb_names = array();
	$len = strlen($CFG->DB_Prefix);
	$result = mysql_listtables ($CFG->DB_Name);
	$i = 0;
	while ($i < mysql_num_rows ($result))
	{
		$tname = mysql_tablename ($result, $i);
		if (substr($tname, 0, $len) == $CFG->DB_Prefix)
		{

			$sql = "SHOW FIELDS FROM {$tname}";
			$l = getSQLArrayO($sql);
			for ($j=0; $j<sizeof($l); $j++)
				if ($l[$j]->Field == "sys_language")
{
					$tb_names[] = $tname;
//echo "<br />{$tname}";
}
		}
    		$i++;
	}

	return $tb_names;
}

?>