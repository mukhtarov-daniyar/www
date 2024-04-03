<?php


$sTITLE = "{$CFG->Locale["system_optionsOfPage"]} '{$ADM->oPageInfo->name}'";

function page_process()
{
	global $_sys_act, $rec, $currDoc, $sTITLE, $id, $_SYS_ACCESS, $_PAGE_ID, $CFG, $db, $ADM;

//die("ok");
	if ($_POST["save_opt"] != "")
	{
		$sql = "DELETE FROM {$CFG->DB_Prefix}options WHERE page_id='{$_PAGE_ID}' AND sys_language='{$CFG->SYS_LANG}'";
		$db->query($sql);
		$t = $_POST;
		while(list($k, $v) = each($t))
			if ($k != "save_opt")
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}options (page_id, xcode, val, opttype, sys_language) VALUES ('{$_PAGE_ID}', '{$k}', '{$v}', '{$_POST["{$k}_opt"]}', '{$CFG->SYS_LANG}')";
				if ($_POST["{$k}_opt"] == "4")
					$sql = "INSERT INTO {$CFG->DB_Prefix}options (page_id, xcode, vallist, opttype, sys_language) VALUES ('{$_PAGE_ID}', '{$k}', '".addSlashes(html2xhtml(stripSlashes($v)))."', '{$_POST["{$k}_opt"]}', '{$CFG->SYS_LANG}')";
				$db->query($sql);
			}
		redirect($_SERVER["HTTP_REFERER"]);
	}
}


function page_show()
{
	global $_sys_act, $rec, $currDoc, $sTITLE, $id, $_SYS_ACCESS, $_PAGE_ID, $CFG, $ADM;

	$sql = "SELECT tmpl_u FROM {$CFG->DB_Prefix}pages WHERE id='{$_PAGE_ID}' AND sys_language='{$CFG->SYS_LANG}'";
	$mod = getSQLField($sql);

	$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE (((module_id='{$mod}' OR module_id='_any') AND sys_language='0') OR (page_id='{$_PAGE_ID}' AND sys_language='{$CFG->SYS_LANG}')) AND visible='1' ORDER BY page_id, pos";
	$l = getSQLArrayO($sql);

	$options = array();
	for ($i=0; $i<sizeof($l); $i++)
	{
		$o = $l[$i];
		if ($o->page_id == 0)
		{
			$options[$o->xcode] = $o;
			$options[$o->xcode]->value = $o->val;
			if ($o->opttype == 4)
				$options[$o->xcode]->val = $o->vallist;
		}
		else
		{
			$options[$o->xcode]->value = $o->val;
			if ($o->opttype == 4)
				$options[$o->xcode]->vallist = $o->vallist;
		}
	}

	echo "<form name='f1' method='post' action=\"main.php\">\r\n";
	echo "<input type='hidden' name='mod'       id='mod'         value='".ecrane($ADM->mod)."' />\n";
	echo "<input type='hidden' name='part'      id='part'        value='".ecrane($ADM->part)."' />\n";
	echo "<input type='hidden' name='act'       id='act'         value='save' />\n";
	echo "<input type='hidden' name='pid'       id='pid'         value='".ecrane($ADM->_PAGE_ID)."' />\n";
	echo "<input type='hidden' name='id'        id='id'          value='".ecrane($record->keyValue)."' />\n";
	echo "<br/><br/><table cellspacing='1' cellpadding='1' border='0' align='center'>\r\n";
	echo "<tr>\r\n";
	echo "<td class='tblHead'>&nbsp;<b>{$CFG->Locale["system_optionsParameter"]}</b>&nbsp;</td>\r\n";
	echo "<td class='tblHead' width='200'>&nbsp;<b>{$CFG->Locale["system_optionsDefault"]}</b>&nbsp;</td>\r\n";
	echo "<td class='tblHead'>&nbsp;<b>{$CFG->Locale["system_optionsValue"]}</b>&nbsp;</td>\r\n";
	echo "</tr>\n";
	while(list($k, $o) = each($options))
	{
		$o->name = hs($o->name);
/*
		$o->vallist = trim(ereg_replace("\r", "", $o->vallist));
		$l = explode("\n", $o->vallist);
		$opt = "";
		for($j=0; $j<sizeof($l); $j++)
		{
			$l2 = explode(":", $l[$j]);
			if (sizeof($l2)==1)
				$l2[1] = $l2[0];
			$o->list[$l2[0]] = hs($l2[1]);
			$sel = ($o->value==$l2[0] ? " selected='true'" : "");
			$opt .= "<option value='{$l2[0]}'{$sel}>".hs($l2[1])."</option>\n";
		}
*/
		$o->vald_name = nl2br(hs($o->val));

		$o->valr_name = ecrane($o->value);
		$o->valr_select = "<input type='text' name='{$o->xcode}' value='{$o->valr_name}' size='40'/>";
		switch ($o->opttype)
		{
			case 0 : {
					$o->valr_select = "";
				break;	}
			case 1 : {
				if ($o->vallist != "")
				{
					$o->vallist = trim(ereg_replace("\r", "", $o->vallist));
					$l =explode("\n", $o->vallist);
					$opt = "";
					for($j=0; $j<sizeof($l); $j++)
					{
						$l2 = explode(":", $l[$j]);
						if (sizeof($l2)==1)
							$l2[1] = $l2[0];
						$o->list[$l2[0]] = hs($l2[1]);
						$sel = ($o->value==$l2[0] ? " selected='true'" : "");
						$opt .= "<option value='{$l2[0]}'{$sel}>".hs($l2[1])."</option>\n";
					}
					$o->vald_name = $o->list[$o->val];
					$o->valr_name = $o->list[$o->value];
					$o->valr_select = "<select name='{$o->xcode}'>{$opt}</select>";
				}
				break;	}
			case 2 : {
					$o->valr_select = "<input type='text' name='{$o->xcode}' value='{$o->valr_name}' size='5'/>";
				break;	}
			case 3 : {
					$o->valr_select = "<input type='text' name='{$o->xcode}' value='{$o->valr_name}' size='40'/>";
				break;	}
			case 4 : {
					$o->valr_select = CM_showETextField($o->xcode, $o->vallist, 5, 40);
				break;	}
			case 5 : {
//					$o->valr_select = "<input type='checkbox' name='{$o->xcode}' value='1' ".($o->valr_name ? "checked='true'" : "")." />";
					$o->valr_select  = "<input type='radio' name='{$o->xcode}' value='1' ".($o->valr_name ? "checked='true'" : "")." /> {$CFG->Locale["system_yes"]} &nbsp; ";
					$o->valr_select .= "<input type='radio' name='{$o->xcode}' value='0' ".($o->valr_name ? "" : "checked='true'")." /> {$CFG->Locale["system_no"]}";
				break;	}
		}
		$o->valr_select .= "<input type='hidden' name='{$o->xcode}_opt' value='{$o->opttype}' />";
		if ($o->name)
			if ($o->opttype == 0)
				echo "<tr bgcolor=\"{$CFG->colorBG2}\"><td colspan=\"3\" height=\"30\">&nbsp;&nbsp;&nbsp;<b>{$o->name}</b>&nbsp;</td></tr>\r\n";
			else
				echo "<tr bgcolor='{$CFG->colorBG3}'><td>&nbsp;<b>{$o->name}</b>&nbsp;</td><td>&nbsp;{$o->vald_name}&nbsp;</td><td>&nbsp;{$o->valr_select}&nbsp;</td></tr>\n";
	}
	echo "<tr bgcolor='#FFFFFF'><td colspan='3' align='right'><input type='submit' name='save_opt' value='{$CFG->Locale["button_save"]}' /></td></tr>\n";
	echo "</table>\n";
	echo "</form>";
	toolsWriteScript();
}

?>