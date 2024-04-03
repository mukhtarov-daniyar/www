<?php


$sTITLE = $CFG->Locale["system_optionsSite"];

function page_process()
{
	GLOBAL $_sys_act, $rec, $currDoc, $sTITLE, $id, $_SYS_ACCESS, $_PAGE_ID, $CFG, $db;

	if ($_POST["save"] != "")
	{
		$sql = "DELETE FROM {$CFG->DB_Prefix}options WHERE sys_language='{$CFG->SYS_LANG}' AND module_id='_system' AND visible='1'";
		$db->query($sql);
		$t = $_POST;
		while(list($k, $v) = each($t))
			if (strpos($k, "_opt") != "")
			{
				$k1 = str_replace("_opt", "", $k);
				$sql = "INSERT INTO {$CFG->DB_Prefix}options (module_id, xcode, val, opttype, sys_language, visible) VALUES ('_system', '{$k1}', '{$_POST[$k1]}', '{$v}', '{$CFG->SYS_LANG}', '1')";
				if ($v == "4")
					$sql = "INSERT INTO {$CFG->DB_Prefix}options (module_id, xcode, vallist, opttype, sys_language, visible) VALUES ('_system', '{$k1}', '".addSlashes(html2xhtml(stripSlashes($_POST[$k1])))."', '{$v}', '{$CFG->SYS_LANG}', '1')";
				$db->query($sql);
			}
//		redirect($_SERVER["HTTP_REFERER"]);
	}
}


function page_show()
{
	GLOBAL $_sys_act, $rec, $currDoc, $sTITLE, $id, $_SYS_ACCESS, $_PAGE_ID, $CFG;

/*
	$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE module_id='_system' AND sys_language='{$CFG->SYS_LANG}' AND visible='1' ORDER BY pos";
	$l = getSQLArrayO($sql);
	if (sizeof($l)<=0)
	{
		$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE module_id='_system' AND sys_language='0' AND visible='1' ORDER BY pos";
		$l = getSQLArrayO($sql);
	}

	$options = array();
	for ($i=0; $i<sizeof($l); $i++)
	{
		$o = $l[$i];
		$options[$o->xcode] = $o;
		$options[$o->xcode]->value = $o->val;
	}
*/

	$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE (module_id='_system' AND (sys_language='0' OR sys_language='{$CFG->SYS_LANG}')) AND visible='1' ORDER BY sys_language, pos";
	$l = getSQLArrayO($sql);

	$options = array();
	for ($i=0; $i<sizeof($l); $i++)
	{
		$o = $l[$i];
		if ($o->sys_language == 0)
		{
			$options[$o->xcode] = $o;
			$options[$o->xcode]->value = $o->val;
		}
		else
		{
			$options[$o->xcode]->value = $o->val;
			if ($o->opttype == 4)
				$options[$o->xcode]->vallist = $o->vallist;
		}
	}

?>
<form name='_sys_form_list_my_users' id='_sys_form_list_my_users' method='post' action='main.php'>

<input type='hidden' name='mod'       id='mod'         value='_6_cfg' />
<input type='hidden' name='part'      id='part'        value='options3' />
<input type='hidden' name='act'       id='act'         value='save' />
<input type='hidden' name='pid'       id='pid'         value='0' />
<input type='hidden' name='id'        id='id'          value='0' />
<?
	echo "<br /><br />\n<table cellspacing='1' cellpadding='1' border='0' align='center'>\n";
	echo "<tr><td class='tblHead'>&nbsp;<b>{$CFG->Locale["system_optionsParameter"]}</b>&nbsp;</td><td class='tblHead'>&nbsp;<b>{$CFG->Locale["system_optionsValue"]}</b>&nbsp;</td></tr>\n";
	while(list($k, $o) = each($options))
	{
		$o->name = hs($o->name);
		$o->vald_name = hs($o->val);
		$o->valr_name = ecrane($o->value);
		$o->valr_list = ecrane($o->vallist);
		$o->valr_select = "<input type='text' name='{$o->xcode}' value='{$o->valr_name}' size='60' />";
		switch ($o->opttype)
		{
			case 1 : {
				if ($o->vallist != "")
				{
					$o->vallist = trim(preg_replace("/\r/i", "", $o->vallist));
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
					$o->valr_select = "<input type='text' name='{$o->xcode}' value='{$o->valr_name}' size='5' />";
				break;	}
			case 3 : {
					$o->valr_select = "<input type='text' name='{$o->xcode}' value='{$o->valr_name}' size='40' />";
				break;	}
			case 4 : {
					$o->valr_select = CM_showETextField($o->xcode, $o->vallist, 5, 40);
				break;	}
			case 5 : {
//					$o->valr_select = "<input type='checkbox' name='{$o->xcode}' value='1' ".($o->valr_name ? "checked='true'" : "")." />";
					$o->valr_select  = "<input type='radio' name='{$o->xcode}' value='1' ".($o->valr_name ? "checked='true'" : "")." /> {$CFG->Locale["system_yes"]} &nbsp; ";
					$o->valr_select .= "<input type='radio' name='{$o->xcode}' value='0' ".($o->valr_name ? "" : "checked='true'")." /> {$CFG->Locale["system_no"]}";
				break;	}
			case 6 : {
			                $o->valr_select = "<input type='text' $fnc name='".$o->xcode."' value='".$o->valr_name."' id='".$o->xcode."' size='40' />";
					$o->valr_select .= "<input type='button' id=\"{$o->xcode}_fbtn\" value='{$CFG->Locale["set_file"]}' onclick='sf(\"{$o->xcode}\" )' style='width:100px' />";
				break;	}
		}
		$o->valr_select .= "<input type='hidden' name='{$o->xcode}_opt' value='{$o->opttype}' />";
/*
		if ($o->vallist != "")
		{
			$o->vald_name = $o->list[$o->val];
			$o->valr_name = $o->list[$o->value];
			$o->valr_select = "<select name='{$o->xcode}'>{$opt}</select>";
		}
*/
		echo "<tr bgcolor='{$CFG->colorBG3}'><td class='tblData'>&nbsp;<b>{$o->name}</b>&nbsp;</td><td>&nbsp;{$o->valr_select}&nbsp;</td></tr>\n";
	}
	echo "<tr><th colspan='2' align='right'><br /><input type='submit' name='save' value='{$CFG->Locale["button_save"]}' /></td></th>\n";
	echo "</table>\n";
	echo "</form>";
	_CM_writeJScript();
//	toolsWriteScript();
}

?>