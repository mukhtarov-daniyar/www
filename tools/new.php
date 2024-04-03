


function showSelectOptions($vals, $v="", $m="----", $type=1)
{
	$r = "";
	if ($m != "")
	{
		$mm = explode("|", $m);
		$mv = $mm[0];
		$mk = $mm[1];
		$r .= "<option value='$mk'>$mv</option>\n";
	}

	while(list($key, $val) = each($vals))
	{
		if (!$type)
			$key = $val;
		$sel = "";
		if (is_array($v))
		{
			if (in_array($key, $v))
				$sel = " SELECTED";
		}
		else
		{
			if ($v == $key)
				$sel = " SELECTED";
		}
		$key = addSlashes($key);
		$r .= "<option value='$key'$sel>$val</option>\n";
	}

	return $r;
}


function showSelect($name, $add="", $addSearch=1, $vals, $v="", $m="----", $type=1)
{
	$r  = "<SELECT name='$name'$add>\n";
	$r .= showSelectOptions($vals, $v, $m, $type);
	$r .= "</select>";
	if ($addSearch)
		$r .= "<input type='text' name='".$name."_find' size='10'><input type='button' name='find' value=' > ' onclick='_sys_find_select(\"".$name."\", this.form);'>\n";
	return $r;
}
