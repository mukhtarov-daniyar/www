<?
	$spages = array();
if ($CFG->SYS_LANG_ADMIN=='ru')
{
	$spages[intro] = "Втупительный блок";
	$spages[form] = "Поля формы";
	$spages[answers] = "Ответы";
}
else
{
	$spages[intro] = "Intro text";
	$spages[form] = "Form's fields";
	$spages[answers] = "Answers";
}

/*
?>
<table width='100%' cellpadding='2' cellspacing='0' border='0'><tr>
<td class='menu_tab_sep'>&nbsp;</td>
<?
	while(list($k, $v) = each($spages))
	{
		$class = "menu_tab_reg";
		if (strpos(" ".$_SESSION["ses_page"], "{$module}{$k}.php") != "")
			$class = "menu_tab_act";
echo "<td class='{$class}'>&nbsp;&nbsp;&nbsp;<a class='menu_tab' href='main.php?page={$module}{$k}.php'>{$v}</a></td>\n";
echo "<td class='menu_tab_sep' width='5'>&nbsp;</td>\n";
	}
?>
<td class='menu_tab_sep'>&nbsp;</td>
</tr></table>
<br />
<?
*/
?>