<?php

	for ($i=0; $i<sizeof($CFG->aBlocks); $i++)
	{
		showGenericBlock($CFG->aBlocks[$i], $CFG->oPage->aOptions);
	}

?>

<!-- BEGIN CONTENT -->

	<div class="content">
		<div class="wrapper">
			<div class="mainCont">
				<div class="boxTitle"><?=showHeader($CFG->oPageInfo->name);?></div>				
				<div class="boxBlog">
					<div class="mainInfBlog">
						<div class="boxSiteMap">
                        	<ul>

<?

//	$CM_tree_data = getTree("{$CFG->DB_Prefix}menu_items", "id", "name, page_id", "parent_id", "parent_id, pos, name", "visible='1'");
//	$uid = 0;
//	if ($CFG->USER->is_loggedIn())
//		$uid = $_SESSION["SYS"]->USER_ID;
//	$CM_tree_data = getTree("{$CFG->DB_Prefix}menu_items I LEFT OUTER JOIN {$CFG->DB_Prefix}pages P ON P.id=I.page_id ", "I.id", "I.name AS name, page_id", "I.parent_id", "I.parent_id, I.pos, I.name", "I.visible='1' AND (P.access='0' OR P.access_list LIKE '%,{$uid},%')");

//	$CM_tree_data = getTree("{$CFG->DB_Prefix}pages", "id", "name, id", "parent_id", "parent_id, pos, name", "visible='1' AND sys_language='{$CFG->SYS_LANG}' AND parent_id!='9' AND menu_flag='1' AND (access='0' OR access_list LIKE '%,{$uid},%')");

	$where = "( (visible='1' AND parent_id='0' AND id!='9') OR (sys_language='{$CFG->SYS_LANG}' AND parent_id > '0' AND parent_id!='9'))";
	$CM_tree_data = getTree("{$CFG->DB_Prefix}pages", "id", "name", "parent_id", "parent_id, pos, menu_name", $where);
//	$CM_tree_data = getTree("{$CFG->DB_Prefix}pages", "id", "name, id", "parent_id", "parent_id, pos, name", "visible='1' AND sys_language='{$CFG->SYS_LANG}' AND parent_id!='9'");
// AND (access='0' OR access_list LIKE '%,{$uid},%')");

	$CM_tree_cnt = 1 * sizeof($CM_tree_data);

	$tmp = array();
	for ($i=0; $i<$CM_tree_cnt; $i++)
	{
		if ($CM_tree_data[$i]->parent_id < 10)
			$CM_tree_data[$i]->parent_id = 0;
		if ($CM_tree_data[$i]->id >= 100)
			$tmp[] = $CM_tree_data[$i];
	}
	$CM_tree_data = $tmp;
	$CM_tree_cnt = 1 * sizeof($CM_tree_data);
	
	for ($i=0; $i<$CM_tree_cnt; $i++)
	{
		if ($CM_tree_data[$i]->parent_id != 0)
			continue;
			
		$r .= CM_tree_drawLevel($CM_tree_data, $i);
	}
	echo $r;



	function CM_tree_drawLevel(&$data, $pos, $lines="")
	{
		
		$CM_tree_data = $data;
		
		$iMyLev = CM_tree_checkLevel($CM_tree_data, $CM_tree_data[$pos]->parent_id, $pos+1);

		$res2 = "";
		
		if ($CM_tree_data[$pos+1]->parent_id != $CM_tree_data[$pos]->id)
		{
			if ($iMyLev)
				$CM_tree_data[$pos]->offs = $lines."t";
			else
				$CM_tree_data[$pos]->offs = $lines."\\";
		}
		else
		{
			if ($iMyLev)
				$CM_tree_data[$pos]->offs = $lines."+";
			else
				$CM_tree_data[$pos]->offs = $lines."-";

			for($k=$pos+1; $k<sizeof($CM_tree_data); $k++)
				if ($CM_tree_data[$k]->parent_id == $CM_tree_data[$pos]->id)
					$res2 .= CM_tree_drawLevel($data, $k, $lines.($iMyLev ? "|" : " "));
		}

		$res  = CM_tree_drawLine($CM_tree_data[$pos]);
		$res .= $res2;

		return $res;
	}


	function CM_tree_drawLine(&$data)
	{
		$offs = $data->offs;
		$res = "";
		for($k=0; $k<strlen($offs); $k++)
		{
			switch($offs[$k])
			{
				case "+" : $img = "plus_i.gif"; $sfx='_i'; break;
				case "|" : $img = "line.gif"; break;
				case " " : $img = "end"; break;
				case "t" : $img = "kross_i.gif"; break;
				case "\\" : $img = ""; break;
			}
			$ots .= $img;
		}

		$res .= "<li class=\"{$ots}\"><a href=\"".getFullXCodeByPageId($data->id)."\" class=\"map_menu\">".hs($data->name)."</a></li>\n";

		return $res;
	}


	function CM_tree_checkLevel(&$data, $par, $p)
	{
		for($i=$p; $i<sizeof($CM_tree_data); $i++)
		{
			if ($data[$i]->parent_id == $par)
				return true;
		}
		return false;
	}

?>
							</ul>
						</div>
					</div>
				</div>			
			</div>	
		</div>		
	</div>	
			
<!-- CONTENT EOF   -->