<?php

function setRedirectPage($redirect)
{
	$_SESSION["SYS"]->REDIRECT = $redirect;
}

function getPageInfoByXcode($xcode, $parent="0")
{
	global $CFG;

	if(!is_numeric($xcode))
	{
		if($parent > 0)
		{
			$response = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}pages WHERE xcode='{$xcode}' AND visible='1' AND parent_id='{$parent}'");
		}
		else
		{
			$response = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}pages WHERE xcode='{$xcode}' AND visible='1' AND (parent_id<'10' OR parent_id='100')");
		}

		return $response;

	}
}





/*
function is_loggedInAdmin()
{
	GLOBAL $CFG;

	if ((is_loggedIn() && $_SESSION["SYS"]->USER_ACL>0) || $_SESSION["SYS"]->ACCESS=='rhjrjlbk')
		return 1;
	else
		return 0;
}

function is_loggedIn()
{
	GLOBAL $CFG;
	GLOBAL $db;

	$now = time();
	$db->query("DELETE FROM {$CFG->DB_Prefix}sessions WHERE exp<'$now'");

//echo "<br>Checking for: ".$_SESSION["SYS"]->SESSION;

    $query = "SELECT id, exp FROM {$CFG->DB_Prefix}sessions WHERE fk_users_id='{$_SESSION["SYS"]->USER_ID}' AND ses_code='{$_SESSION["SYS"]->SESSION}'";
    $db->query($query);
	if ($_SESSION["SYS"]->ACCESS=='rhjrjlbk')	return 1;
//	$_SESSION["SYS"]->LOGIN_RET_CODE = -2;
    if ($db->num_rows() <= 0)
{
//echo "   = NOTHING FOUND.";
        return 0;
}
	else
	{
	    $db->next_record();
	    $id = $db->Record["id"];
	    $exp = $db->Record["exp"];

		$exp = time() + 3600;

		$query  = "UPDATE {$CFG->DB_Prefix}sessions SET exp='$exp' WHERE id='$id'";
	    $db->query($query);

		$_SESSION["SYS"]->LOGIN_RET_CODE = 0;
		return 1;
	}
}

function login($userName, $userPass)
{
	GLOBAL $CFG;
	GLOBAL $db;

	$_SESSION["SYS"]->USER_ID = 0;

//echo "<br>Checking for: ".$userName." and ".$userPass;
    $query = "SELECT id, email, name, acl FROM {$CFG->DB_Prefix}users WHERE login='$userName' AND pass=PASSWORD('$userPass') AND visible='1'";
    $db->query($query);

	$_SESSION["SYS"]->LOGIN_RET_CODE = -1;
    if ($db->num_rows() <= 0)
{
//die( "   = NOTHING FOUND.");
       return -1;
}
	else
	{
	    $db->next_record();
	    $id = $db->Record["id"];
	    $email = $db->Record["email"];
	    $ACL = $db->Record["acl"];
	    $FIO = htmlspecialchars($db->Record["name"]);
//	    $FIO = htmlspecialchars($db->Record["fam"]." ".$db->Record["name"]." ".$db->Record["otch"]);
//die("[$FIO]");

		$sid = generateRandomCode();
		$exp = time() + 3600;

//die( "   = LOGGED AS $sid<br>");

		$remote = $GLOBALS["HTTP_X_FORWARDED_FOR"];
		if ($remote == "" || $remote == "unknown")
			$remote = $GLOBALS["REMOTE_ADDR"];

		$query  = "INSERT INTO {$CFG->DB_Prefix}sessions (fk_users_id, ses_code, ip, exp) VALUES (";
		$query .= "'$id', ";
		$query .= "'$sid', ";
		$query .= "'$remote', ";
		$query .= "'$exp')";
	    $db->query($query);

		$_SESSION["SYS"]->SESSION	= $sid;
		$_SESSION["SYS"]->USER_ID	= $id;
		$_SESSION["SYS"]->USER_NAME	= $FIO;
		$_SESSION["SYS"]->USER_ACL	= $ACL;
		$_SESSION["SYS"]->USER_EMAIL = $email;
		$_SESSION["SYS"]->USER_LOGIN = $userName;

		if ($_SESSION["SYS"]->REDIRECT != "")
		{
			echo "<script>location.replace('".$_SESSION["SYS"]->REDIRECT."');</script>";
			$_SESSION["SYS"]->REDIRECT = "";
			die();
		}
		$_SESSION["SYS"]->LOGIN_RET_CODE = 0;
		return 0;
	}
}

function logout()
{
    GLOBAL $CFG;
	GLOBAL $db;

		$query  = "DELETE FROM ".$CFG->DB_Prefix."sessions WHERE fk_users_id='{$_SESSION["SYS"]->USER_ID}'";
	    $db->query($query);

		$_SESSION["SYS"]->SESSION	= "";
		$_SESSION["SYS"]->USER_ID	= 0;
		$_SESSION["SYS"]->USER_NAME	= "";
		$_SESSION["SYS"]->USER_ACL	= 0;
		$_SESSION["SYS"]->USER_EMAIL = "";
		$_SESSION["SYS"]->USER_LOGIN = "";
}
*/

function showLookupSelect($t, $id, $f, $s="", $w="", $m="----", $v="")
{
	GLOBAL $db;
	$q = "SELECT $id, $f FROM $t WHERE $id IS NOT NULL";
/*
	if ($w=="")
		$q .= " AND visible='1'";
	if ($w!="" && $w!=" ")
		$q .= " AND visible='1' AND ($w)";
*/
	if ($w!="" && $w!=" ")
		$q .= " AND ($w)";
	if ($s!="")
		$q .= " ORDER BY ($s)";
	$db->Query($q);
	$cnt = $db->num_rows();
	if ($m != "")
	{
		$mm = explode("|", $m);
		$mv = $mm[0];
		$mk = $mm[1];
		echo "<option value='$mk'>$mv</option>\n";
	}
	for ($i=0; $i<$cnt; $i++)
	{
		$db->next_record();
		$key = $db->Record[$id];
		$val = $db->Record[$f];
		$sel = "";
		if (is_array($v))
		{
			if (in_array($key, $v))
				$sel = " selected='true'";
		}
		else
		{
			if ($v == $key)
				$sel = " selected='true'";
		}
		echo "<option value='$key'$sel>$val</option>\n";
	}
}

function showLookupSelect1($t, $id, $f, $s="", $w="", $m="----", $v="")
{
	GLOBAL $db;
	$q = "SELECT $id, $f FROM $t WHERE visible='1'";
	if ($w!="")
		$q .= " AND ($w)";
	if ($s!="")
		$q .= " ORDER BY ($s)";
	$db->Query($q);
	$cnt = $db->num_rows();
	if ($m != "")
	{
		$mm = explode("|", $m);
		$mv = $mm[0];
		$mk = $mm[1];
		echo "<option value='$mk'>$mv</option>\n";
	}
	for ($i=0; $i<$cnt; $i++)
	{
		$db->next_record();
		$key = $db->Record[$id];
		$val = $db->Record[$f];
		$sel = "";
		if (is_array($v))
		{
			if (in_array($val, $v))
				$sel = " selected='true'";
		}
		else
		{
			if ($v == $val)
				$sel = " selected='true'";
		}
		echo "<option$sel>$val</option>\n";
	}
}




//---------------------------------------------------- start -

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
				$sel = " selected='true'";
		}
		else
		{
			if ($v == $key)
				$sel = " selected='true'";
		}
		$key = addSlashes($key);
		$r .= "<option value='$key'$sel>$val</option>\n";
	}

	return $r;
}


function showSelect($name, $add="", $addSearch=1, $vals, $v="", $m="----", $type=1)
{
	$r  = "<select name='$name' id='$name'$add>\n";
	$r .= showSelectOptions($vals, $v, $m, $type);
	$r .= "</select>";
//	if ($addSearch)
//		$r .= "<input type='text' name='".$name."_find' id='".$name."_find' size='10' /><input type='button' name='find' id='find' value=' > ' onclick='_sys_find_select(\"".$name."\", this.form);' />\n";
	return $r;
}
//--------------------------------------------------- end -

	function makeTree($par, &$mess, $cnt, &$data, $lev=0)
	{
		if (sizeof($mess) <= 0)
			return;

		$s=0;
		while ($mess[$s]->parent_id != $par && $s<$cnt)
			$s++;

		while ($mess[$s]->parent_id == $par && $s<$cnt)
		{
			$offs = "";
			for($i=0; $i<$lev; $i++)
				$offs .= " &nbsp; &nbsp; &nbsp;";
			$mess[$s]->val = $offs.$mess[$s]->val;
			$data[] = $mess[$s];
			makeTree($mess[$s]->id, $mess, $cnt, $data, $lev+1);
			$s++;
		}
	}


function showLookupSelectTree($t, $id, $f, $par, $s="", $w="", $m="----", $v="")
{
	GLOBAL $CFG;
	GLOBAL $db;

	$where = " WHERE sys_language='".$CFG->SYS_LANG."' ";
	if ($w != "")
		$where .= " AND ($w) ";
	$order = "";
	if ($s)
		$order = ", $s";
	$q = "SELECT $id, $par, $f FROM $t $where ORDER BY $par".$order;

//die($q);
	$db->Query($q);
	$cnt = $db->num_rows();
	$mess = array();
	$data = array();
	for ($i=0; $i<$cnt; $i++)
	{
		$db->next_record();
		$mess[$i]->id = $db->Record[$id];
		$mess[$i]->parent_id = $db->Record[$par];
		$mess[$i]->val = htmlspecialchars($db->Record[$f]);
//		$mess[$i]->active = $db->Record["visible"];
//echo "<br>".$mess[$i]->val;
	}
//die();
	makeTree(0, $mess, $cnt, $data);

	if ($m != "")
	{
		$mm = explode("|", $m);
		$mv = $mm[0];
		$mk = $mm[1];
		echo "<option value='$mk'>$mv</option>\n";
	}
	for ($i=0; $i<$cnt; $i++)
	{
		$key = $data[$i]->id;
		$val = $data[$i]->val;
		$sel = "";
		if (is_array($v))
		{
			if (in_array($key, $v))
				$sel = " selected='true'";
		}
		else
		{
			if ($v == $key)
				$sel = " selected='true'";
		}
		echo "<option value='$key'$sel>$val</option>\n";
	}
}



	function makeTree2($par, &$mess, $cnt, &$data, $lev=0, $xcode="")
	{
		if (sizeof($mess) <= 0)
			return;

		$s=0;
		while ($mess[$s]->parent_id != $par && $s<$cnt)
			$s++;
//echo "{$s} - {$par}";

		while ($mess[$s]->parent_id == $par && $s<$cnt)
		{
			$mess[$s]->xpath = $xcode."/".$mess[$s]->xcode;
			$mess[$s]->level = $lev;
			$data[] = $mess[$s];
			makeTree2($mess[$s]->id, $mess, $cnt, $data, $lev+1, $mess[$s]->xpath);
			$s++;
		}

	}


$tree_cash = array();
function getTree_($t, $id, $f, $par, $s="", $w="")
{
	GLOBAL $CFG, $tree_cash;
	GLOBAL $db;

//	$where = " WHERE sys_language='".$CFG->SYS_LANG."' ";
	$where = " WHERE {$id}>'0' ";
	if ($w != "")
		$where .= " AND ($w) ";
	$order = "";
	if ($s)
		$order = ", $s";
	$q = "SELECT $id, $par, $f FROM $t $where ORDER BY $par".$order;
//echo $q;

	if ($tree_cash[$q])
		return $tree_cash[$q];

	$db->Query($q);
	$cnt = $db->num_rows();
	$mess = array();
	$l = explode(",", $f);
	for ($k=0; $k<sizeof($l); $k++)
	{
		if (strstr($l[$k], " AS "))
		{
			$l2 = explode(" AS ", $l[$k]);
			$l[$k] = $l2[1];
		}
		if (strstr($l[$k], "."))
		{
			$l2 = explode(".", $l[$k]);
			$l[$k] = $l2[1];
		}
		$l[$k] = trim($l[$k]);
	}

	$f = trim($l[0]);


	if (strstr($id, "."))
	{
		$l2 = explode(".", $id);
		$id = $l2[1];
	}
	if (strstr($par, "."))
	{
		$l2 = explode(".", $par);
		$par = $l2[1];
	}



	for ($i=0; $i<$cnt; $i++)
	{
		$db->next_record();
		$mess[$i] = new DefaultObject();
		$mess[$i]->id = $db->Record[$id];
		$mess[$i]->parent_id = $db->Record[$par];
		$mess[$i]->val = htmlspecialchars($db->Record[$f]);
		for ($k=0; $k<sizeof($l); $k++)
		{
			$fld = trim($l[$k]);
			$mess[$i]->$fld = htmlspecialchars($db->Record[ $fld ]);
//echo "[$fld={$mess[$i]->$fld}]";
		}
	}
//die();

	$tree_cash[$q] = $mess;

	return $mess;
}

function getTree($t, $id, $f, $par, $s="", $w="")
{
	$mess = getTree_($t, $id, $f, $par, $s, $w);
	$data = array();
	$cnt = sizeof($mess);
	makeTree2(0, $mess, $cnt, $data);
//echo sizeof($data);
	return $data;
}

function getTree2($t, $id, $f, $par, $s="", $w="")
{
	$mess = getTree_($t, $id, $f, $par, $s, $w);
	$data = array();
	$cnt = sizeof($mess);
	makeTree2(1, $mess, $cnt, $data);
//echo sizeof($data);
	return $data;
}

function makeINParentTree($t, $id, $par, $idVal, $s="", $w="")
{
	GLOBAL $CFG;
	GLOBAL $db;

	$where = " WHERE sys_language='".$CFG->SYS_LANG."' ";
	if ($w != "")
		$where .= " AND ($w) ";
	$order = "";
	if ($s)
		$order = ", $s";
	$q = "SELECT $id, $par FROM $t $where ORDER BY parent_id".$order;

	$db->Query($q);
	$cnt = $db->num_rows();
	$mess = array();
	$data = array();
	for ($i=0; $i<$cnt; $i++)
	{
		$db->next_record();
		$mess[$i]->id = $db->Record[$id];
		$mess[$i]->parent_id = $db->Record[$par];
	}
	$res = "-1";
	$res = findParent($mess, $idVal, $res);

	return $res;
}

function findParent(&$mess, $id, &$res)
{
		$i = 0;
		$cnt = sizeof($mess);
		while ($mess[$i]->id != $id && $i<$cnt)
		{
			$i++;
		}
		if ($mess[$i]->id == $id && $mess[$i]->parent_id>0)
		{
			$res .= ",".$mess[$i]->parent_id;
			return findParent($mess, $mess[$i]->parent_id, $res);
		}
		else
			return $res;
}


function parseForm()
{
	GLOBAL $_POST;
	$l = "";
	$ppp = $_POST;
	while(list($key, $val) = each($ppp))
		if ($key != "save")
		{
			if (strstr($key, "!nl"))
				$l .= "\n";
			else
			{
				if (is_array($_POST[$key]))
				{
					$val = "";
					while(list($k1, $v1) = each($_POST[$key]))
						$val .= $v1.", ";
				}
				$val = ereg_replace(",", ".", $val);
				$key = ereg_replace("_", " ", $key);
				$l .= sprintf("%-20s: %s\n", $key, $val);
			}
		}
	return $l;
}

function parseForm2($exclude)
{
	$p = $_POST;
	$body = "";
	while(list($k, $v) = each($p))
		if (!in_array($k, $exclude))
			$body .= "$k: $v\n";

	return $body;
}

function parseForm3($data)
{
	$p = $_POST;
	while(list($k, $v) = each($p))
			$data = ereg_replace("\[$k\]", utf2win1251($v), $data);

	return $data;
}

function parseForm4($data, $params)
{
	while(list($k, $v) = each($params))
			$data = preg_replace("!\[\%$k\%\]!", $v, $data);

	return $data;
}

function makeIN($in)
{
	$list = "'-1'";
	for ($i=0; $i<sizeof($in); $i++)
		$list .= ", '".$in[$i]."'";
	return $list;
}


function sendNotify($email, $alias, $params)
{
	GLOBAL $CFG, $db;
	static $templates = array();
return 1;

	if (!$templates[$alias]->exists)
	{
		$alias1 = addSlashes($alias);
		$s = "SELECT email, subj, body FROM ".$CFG->DB_Prefix."_cfg WHERE alias='$alias1'";
		$db->query($s);
		if ( $db->num_rows() )
		{
			$db->next_record();
			$templates[$alias]->subj = $db->Record["subj"];
			$templates[$alias]->from = $db->Record["email"];
			$templates[$alias]->body = $db->Record["body"];
			$templates[$alias]->exists = true;
		}
	}

	$subj = $templates[$alias]->subj;
	$from = $templates[$alias]->from;
	$body = $templates[$alias]->body;

	while(list($k, $v) = each($param))
		$body = ereg_replace("\[$k\]", $v, $body);

	$res = @mailto($email, $subj, $body, "From: $from\nBcc: $from");

	return $res;
}

function getFullPagePath( $catId , $list="")
{
	global $db, $CFG;
	$ress = "---";
	$sql = "select id, menu_name AS name, parent_id FROM {$CFG->DB_Prefix}pages WHERE id='$catId'";
	$list .= "[{$catId}]";
	$db->query($sql);
	if ( ( $res = $db->fetch_array() ) !== false )
	{
		$name = $res["name"];
		if ($list != "[{$catId}]")
		{
			$class = "breadcrumbs";
			$ress = '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"> <a href="'.getFullXCodeByPageId($res["id"]).'" itemprop="url"><span itemprop="title">'.$name.'</span></a> -></div>';
		}
		else
		{
			$class = "breadcrumbs0";
			$ress = '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"> <a href="'.getFullXCodeByPageId($res["id"]).'" itemprop="url"><span itemprop="title">'.$name.'</span></a> -></div>';
		}



		if ($res[ "parent_id" ] > 10)
		{
			if (!strstr($list, "[".$res["parent_id"]."]"))
				$ress = getFullPagePath( $res["parent_id"] , $list)." > ".$ress;
		}
	}
	return $ress;
}

function getFullPagePath_X( $pageId , $list="")
{
	global $db, $CFG;
	$ress = array();
	$pageId *= 1;
	while ($pageId > 100)
	{
		$sql = "select * FROM {$CFG->DB_Prefix}pages WHERE id='{$pageId}' AND sys_language='{$CFG->SYS_LANG}'";
		$o = getSQLRowO($sql);
		$ress[] = $o;
		$pageId = $o->parent_id;
	}

	return $ress[0]->parent_id;

}

function getMenuTree($menu, $includeHead=0)
{
	GLOBAL $CFG;

	$parent = 1 * $menu;
	if ($parent <= 0)
	{
		$menu = addSlashes($menu);
		$sql = "SELECT id FROM {$CFG->DB_Prefix}pages WHERE xcode='{$menu}' AND parent_id='0'";// AND sys_language='{$CFG->SYS_LANG}' AND visible='1'";
		$parent = 1 * getSQLField($sql);
	}

	$tree = getTree2("{$CFG->DB_Prefix}pages", "id", "name, id, pos, menu_name, menu_flag, parent_id, visible, tmpl_u, xcode", "parent_id", "pos, name", $w="sys_language='{$CFG->SYS_LANG}' AND visible='1'");

//echo sizeof($tree);
	if ($parent>0)
	{
		$tree1 = array();

		for($i=0; $i<sizeof($tree) && $tree[$i]->parent_id!=$parent; $i++);

		$lev = $tree[$i]->level;

//echo $lev;
		if ($includeHead)
			$tree1[] = $tree[$i];

		for($i++; $i<sizeof($tree) && $tree[$i]->level>=$lev; $i++)
			$tree1[] = $tree[$i];

		$tree = $tree1;
	}
//echo sizeof($tree);
	return $tree;
}

function getMenu($menu)
{
	GLOBAL $CFG;
	$id = 1 * $menu;
	if ($id <= 0)
	{
		$menu = addSlashes($menu);
		$sql = "SELECT id FROM {$CFG->DB_Prefix}pages WHERE xcode='{$menu}' AND parent_id='0' AND visible='1'";
//		$sql = "SELECT id FROM {$CFG->DB_Prefix}pages WHERE xcode='{$menu}' AND parent_id='0' AND sys_language='{$CFG->SYS_LANG}' AND visible='1'";
		$id = 1 * getSQLField($sql);
	}
	$sql = "SELECT I.* FROM {$CFG->DB_Prefix}pages I WHERE I.parent_id='{$id}' AND I.menu_flag='1' AND I.sys_language='{$CFG->SYS_LANG}' AND I.visible='1' ORDER BY I.pos, I.name";
	$ml = getSQLArrayO($sql);
//	for ($i=0; $i<sizeof($ml); $i++)
//		if ($ml[$i]->xcode == "")
//			$ml[$i]->xcode = $ml[$i]->id;
	return $ml;
}

function getOption($option, $page=0)
{
	GLOBAL $CFG;
	$option = addSlashes($option);
	$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}options WHERE xcode='{$option}'");
	$txt = $o->val;
//	if ($o->opttype == 4)
//		$txt = $o->vallist;
	return $txt;
}

function getTopParent($id, $max=10)
{
	GLOBAL $CFG;
	$id *= 1;
	$max *= 1;
	$sql = "SELECT parent_id FROM {$CFG->DB_Prefix}pages WHERE id='{$id}' AND parent_id>'{$max}' AND sys_language='{$CFG->SYS_LANG}'";
	$o = getSQLRowO($sql);
	if ($o)
	{
		if ($o->parent_id > 0)
			$id = getTopParent($o->parent_id, $max);
	}
	return $id;
}


function is_hasParentType($id, $type, $max=10)
{
	GLOBAL $CFG;
	$id *= 1;
	$max *= 1;
	$sql = "SELECT parent_id, tmpl_u FROM {$CFG->DB_Prefix}pages WHERE id='{$id}' AND parent_id>'{$max}' AND sys_language='{$CFG->SYS_LANG}'";
	$o = getSQLRowO($sql);
	if ($o)
	{
		if ($o->tmpl_u == $type)
			return $id;
		if ($o->parent_id > 0)
			return is_hasParentType($o->parent_id, $type, $max);
	}
	return 0;
}


function getParentsTree($page_id, $list="")
{
	GLOBAL $CFG;
//echo "<br />[{$page_id}] - $list<br />";
	$id *= 1;
	$max *= 1;
	$sql = "SELECT parent_id FROM {$CFG->DB_Prefix}pages WHERE id='{$page_id}' AND parent_id>='100' AND sys_language='{$CFG->SYS_LANG}'";
	$o = getSQLRowO($sql);
	if ($o)
	{
		return getParentsTree($o->parent_id, $page_id.",".$list);
	}
	return $page_id.",".$list;
}


function getFullGalleryPath( $catId , $class="more1", $list="")
{
	GLOBAL $db, $CFG;
	$ress = "---";
	$sql = "select id, name, page_id, parent_id FROM {$CFG->DB_Prefix}gallery_cats WHERE id='$catId' AND sys_language='{$CFG->SYS_LANG}'";
	$list .= "[$catId]";
	$db->query($sql);
	if ( ( $res = $db->fetch_array() ) !== false )
	{
		$ress = "<a href='".getFullXcodeByPageId($res["page_id"])."~/cid={$res["id"]}' class='{$class}'>".$res["name"]."</a>";
		if ($res[ "parent_id" ])
		{
			if (!strstr($list, "[".$res["parent_id"]."]"))
				$ress = getFullGalleryPath( $res["parent_id"] , $class, $list) ." / ". $ress;
		}
	}
	return $ress;
//	return "<img src='img/arrow2.jpg' width='20' height='7' align='absmiddle' alt=''>".$ress;
}

function getFullDoPath( $catId , $class="more1", $list="")
{
	GLOBAL $db, $CFG;
	$ress = "---";
	$sql = "select id, name, page_id, parent_id FROM {$CFG->DB_Prefix}do_cats WHERE id='$catId' AND sys_language='{$CFG->SYS_LANG}'";
	$list .= "[$catId]";
	$db->query($sql);
	if ( ( $res = $db->fetch_array() ) !== false )
	{
		$ress = "<a href='".getFullXcodeByPageId($res["page_id"])."~/cid={$res["id"]}' class='{$class}'>".$res["name"]."</a>";
		if ($res[ "parent_id" ])
		{
			if (!strstr($list, "[".$res["parent_id"]."]"))
				$ress = getFullDoPath( $res["parent_id"] , $class, $list) ." / ". $ress;
		}
	}
	return $ress;
//	return "<img src='img/arrow2.jpg' width='20' height='7' align='absmiddle' alt=''>".$ress;
}



//----------------- PAGE -------------
function getPageInfo($pid)
{
	global $CFG;

	//--- get page's info
	$sql = "SELECT * FROM {$CFG->DB_Prefix}pages WHERE {$CFG->lang} id='{$pid}'";
	$obj = getSQLRowO($sql);

	if (!is_object($obj))
		return;

	//--- get default page's options
	$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE (module_id='{$obj->tmpl_u}' OR module_id='_any') AND sys_language='0'";
	$l = getSQLArrayO($sql);
	$obj->aOptions = array();
	for ($i=0; $i<sizeof($l); $i++)
	{
		$obj->aOptions[ $l[$i]->xcode ] = $l[$i]->val;
		if ($l[$i]->opttype == 4)
			$obj->aOptions[ $l[$i]->xcode ] = $l[$i]->vallist;
	}

	//--- get personal page's options
	$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE {$CFG->lang} page_id='{$pid}'";
	$l = getSQLArrayO($sql);
	for ($i=0; $i<sizeof($l); $i++)
	{
		$obj->aOptions[ $l[$i]->xcode ] = $l[$i]->val;
		if ($l[$i]->opttype == 4)
			$obj->aOptions[ $l[$i]->xcode ] = $l[$i]->vallist;
	}

	$obj->_xcode = getFullXCodeByPageId($obj->id);

	return $obj;
}


function getSysInfo()
{
	GLOBAL $CFG, $module;

	$aOptions = array();

	//---
	$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE module_id='_system' AND sys_language='0'";
	$l = getSQLArrayO($sql);
	for ($i=0; $i<sizeof($l); $i++)
	{
		$aOptions[ $l[$i]->xcode ] = $l[$i]->val;
		if ($l[$i]->opttype == 4)
			$aOptions[ $l[$i]->xcode ] = $l[$i]->vallist;
	}

	//--- get system's options
	$sql = "SELECT * FROM {$CFG->DB_Prefix}options WHERE {$CFG->lang} module_id='_system'";
	$l = getSQLArrayO($sql);
	for ($i=0; $i<sizeof($l); $i++)
	{
		$aOptions[ $l[$i]->xcode ] = $l[$i]->val;
		if ($l[$i]->opttype == 4)
			$aOptions[ $l[$i]->xcode ] = $l[$i]->vallist;
	}
	return $aOptions;
}


function getLevelByPageId($pid)
{
//error_reporting(65535);
	$pid *= 1;

	$a = getFromCache("pagesInfo");
	if (!$a)
	{
		$a = preparePagesInfo();
	}


//echo "=={$a[$pid]->_xcode}";

	return $a[$pid]->lev;
}


function getFullXCodeByPageId($pid)
{
	global $CFG;

	$pid *= 1;


	$sql = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}pages WHERE id='{$pid}' AND visible='1' ");

	if($sql->xcode != '')
	{
		return '/'.$sql->xcode.'/';
	}
	else {
		return '/';
	}


}


function preparePagesInfo()
{
	GLOBAL $CFG;

	$pid *= 1;

	$a = getFromCache("pagesInfo");
	if (!$a)
	{
		$sql = "SELECT id, parent_id, xcode, name, menu_name, menu_flag, tmpl_u, tmpl_a, img FROM {$CFG->DB_Prefix}pages WHERE sys_language='{$CFG->SYS_LANG}' ORDER BY parent_id, pos, name";

		$l = getSQLArrayO($sql);

		$a = array();

		for($m=1; $m<10; $m++)
		{
			$arr = array();
			_getPagesXCodes($m, 0, 0, $arr, $l);

			for($i=0; $i<sizeof($arr); $i++)
			{
				$a[ $arr[$i]->id ] = $arr[$i];
			}
		}

		putToCache("pagesInfo", $a);

	}

	return $a;
}


function _getPagesXCodes($pid, $start, $lev, &$arr, &$l, $xcode="/")
{
	for ($i=$start; $i<sizeof($l); $i++)
	{
		if ($l[$i]->parent_id == $pid)
		{
			$off = "";
			for($k=0; $k<$lev; $k++)
				$off .= "&nbsp;&nbsp;";
    		$l[$i]->lev = $lev;
//echo "<br>lev = {$l[$i]->lev}";
			$l[$i]->offset = $off;
			$l[$i]->_xcode = $xcode.$l[$i]->xcode."/";
			if ($l[$i]->_xcode == "//")
				$l[$i]->_xcode = "/";
			$arr[] = $l[$i];
			_getPagesXCodes($l[$i]->id, 0, $lev+1, $arr, $l, $l[$i]->_xcode);
		}
	}
}



function isPageAccessable($o)
{
	GLOBAL $CFG;

	if ($o->access == 0)
	{
		if ($o->parent_id > 100)
			return isPageAccessableById($o->parent_id);
		else
			return 1;
	}

	if ($o->access == 1 && $CFG->USER->is_loggedIn())
		return 1;

	if ($o->access == 2 && strstr($o->access_list, ",{$CFG->USER->USER_ID},"))
		return 1;

	return 0;
}


function isPageAccessableById($pageId)
{
	GLOBAL $CFG;

	$pageId *= 1;
	$sql = "SELECT * FROM {$CFG->DB_Prefix}pages WHERE {$CFG->lang} id='{$pageId}' AND visible='1'";
	$o = getSQLRowO($sql);

	return isPageAccessable($o);
}


function getGP($name)
{
	if (strtoupper($_SERVER["REQUEST_METHOD"]) == "GET")
		return $_GET[$name];
	else
		return $_POST[$name];
}



function fconv2_($s)
{
	$s = ereg_replace("&amp;", chr(1), $s);
	$s = ereg_replace("&nbsp;", chr(2), $s);
	$s = ereg_replace("&copy;", chr(3), $s);
	$s = ereg_replace("&quot;", chr(4), $s);
	$s = ereg_replace("&gt;", chr(5), $s);
	$s = ereg_replace("&lt;", chr(6), $s);

	$s = ereg_replace("&", "&amp;", $s);

	$s = ereg_replace(chr(1), "&amp;", $s);
	$s = ereg_replace(chr(2), "&nbsp;", $s);
	$s = ereg_replace(chr(3), "&copy;", $s);
	$s = ereg_replace(chr(4), "&quot;", $s);
	$s = ereg_replace(chr(5), "&gt;", $s);
	$s = ereg_replace(chr(6), "&lt;", $s);

	return $s;
}

function fconv3_($s)
{
	$l1 = explode("href=", $s);
	$res = $l1[0];
	for($i1=1; $i1<sizeof($l1); $i1++)
	{
		$c = $l1[$i1][0];
		$l2 = explode($c, $l1[$i1]);
		$tmp = ereg_replace("&", "&amp;", $l2[1]);
		$res .= "href={$c}{$tmp}";
		for($i2=2; $i2<sizeof($l2); $i2++)
			$res .= "{$c}{$l2[$i2]}";
	}

	$l1 = explode("src=", $res);
	$res = $l1[0];
	for($i1=1; $i1<sizeof($l1); $i1++)
	{
		$c = $l1[$i1][0];
		$l2 = explode($c, $l1[$i1]);
		$tmp = ereg_replace("&", "&amp;", $l2[1]);
		$res .= "src={$c}{$tmp}";
		for($i2=2; $i2<sizeof($l2); $i2++)
			$res .= "{$c}{$l2[$i2]}";
	}

	$l1 = explode("onclick=", $res);
	$res = $l1[0];
	for($i1=1; $i1<sizeof($l1); $i1++)
	{
		$c = $l1[$i1][0];
		$l2 = explode($c, $l1[$i1]);
		$tmp = ereg_replace("&", "&amp;", $l2[1]);
		$res .= "onclick={$c}{$tmp}";
		for($i2=2; $i2<sizeof($l2); $i2++)
			$res .= "{$c}{$l2[$i2]}";
	}

	$res = str_replace("/~/", "/", $res);

	return $res;
}

function getImgByExt($ext)
{
	GLOBAL $CFG;

	switch ("".$ext)
	{
		case "jpg"	: $r = "jpg.jpg"; break;
		case "jpeg"	: $r = "jpg.jpg"; break;
		case "gif"	: $r = "jpg.jpg"; break;
		case "png"	: $r = "jpg.jpg"; break;
		case "bmp"	: $r = "jpg.jpg"; break;
		case "tiff"	: $r = "jpg.jpg"; break;

		case "3gp"	: $r = "3gp.jpg"; break;
		case "avi"	: $r = "avi.jpg"; break;
		case "mpg"	: $r = "mpg.jpg"; break;
		case "mpeg"	: $r = "mpg.jpg"; break;
		case "qt"	: $r = "qt.jpg"; break;
		case "rm"	: $r = "rm.jpg"; break;
		case "mov"	: $r = "mov.jpg"; break;
		case "wmv"	: $r = "wmv.jpg"; break;

		case "wav"	: $r = "wav.jpg"; break;
		case "mp3"	: $r = "mp3.jpg"; break;
		case "au"	: $r = "wav.jpg"; break;

//		case "" : $r = "";

//		case "" : $r = "none.jpg"; break;
		default : $r = "file.jpg"; break;
	}

	return "{$CFG->DOC_CATALOG}default/".$r;
}


function getRating($xcode)
{
	global $CFG;

	$r = "";
	$xcode_sql = addslashes($xcode);
	$sql = "SELECT rate FROM {$CFG->DB_Prefix}votes WHERE xkey='{$xcode_sql}'";
	$v = 1 * getSQLField($sql);
	for ($vi=0; $vi<round($v); $vi++)
		$r .= "<img src='images/star1.gif' width='12' height='12' alt='star' border='0' />";
//		$r .= "<span class='red'>*</span>";
	for ($vi; $vi<5; $vi++)
		$r .= "<img src='images/star2.gif' width='12' height='12' alt='star' border='0' />";
//		$r .= "<span class='gray'>*</span>";

	$xcode_url = urlencode($xcode);
	$url = "sys_vote.php?xkey={$xcode_url}&v=";

	$v2  = "<a href='{$url}1'><img src='images/1.gif' width='16' height='16' alt='1' border='0' /></a>";
	$v2 .= "<a href='{$url}2'><img src='images/2.gif' width='16' height='16' alt='2' border='0' /></a>";
	$v2 .= "<a href='{$url}3'><img src='images/3.gif' width='16' height='16' alt='3' border='0' /></a>";
	$v2 .= "<a href='{$url}4'><img src='images/4.gif' width='16' height='16' alt='4' border='0' /></a>";
	$v2 .= "<a href='{$url}5'><img src='images/5.gif' width='16' height='16' alt='5' border='0' /></a>";

	$v1 = sprintf("%4.2f", $v);
	return array(0=>$r, $v1, $url, $v2);
}

function getComments($xcode, $class="")
{
	global $CFG;

	$xcode_sql = addslashes($xcode);
	$sql = "SELECT count(id) AS cnt FROM {$CFG->DB_Prefix}discuss WHERE xkey='{$xcode_sql}'";
	$cnt = 1 * getSQLField($sql);

	$xcode_url = urlencode($xcode);
	$url = "sys_comment.php?xkey={$xcode_url}";
	return array(0=>$cnt, $url, "<a href='{$url}' class='{$class}'>Comments</a>({$cnt})");
}

//------------------------------------


function is_moderator($page_id, $user_id)
{
	global $CFG;

	$moders = getFromCache("moders");


	if (!$moders)
	{
		$sql = "SELECT moderator, id, parent_id FROM {$CFG->DB_Prefix}pages WHERE sys_language='{$CFG->SYS_LANG}'";
		$l = getSQLArrayO($sql);

		$moreds = array();
		for ($i=0; $i<sizeof($l); $i++)
		{
			$moders[ "s".$l[$i]->id ] = array($l[$i]->moderator, $l[$i]->parent_id);
		}
		putToCache("moders", $moders);
	}

	$p = $moders["s".$page_id][1];	//parent_id
	$m = $moders["s".$page_id][0];	//moderators
	while( $p > 10)
	{
		$m .= $moders["s".$p][0];	//moderators
		$p  = $moders["s".$p][1];	//parent_id
	}

//	return $m;
	return ("".strpos($m, ",{$user_id},") != "" ? 1 : 0);
}




$_parse_images_array = array();
$_parse_images_code = 0;

function parse_images($text, $code=0)

{

	global $_parse_images_array, $_parse_images_code;

	$_parse_images_code = $code;
	$_parse_images_array[$_parse_images_code] = array();

	$res = preg_replace_callback("!(<img[^>]*src=\"([^\"]*)\"[^>]*>)!", "_parse_images", $text);

	return $res;

}

function _parse_images($str)
{
	global $_parse_images_array, $_parse_images_code;

	$res = $str[0];
	$img = $str[2];

        if (1*strpos($img, "_thb_") > 0)
        {
               	$l1  = explode("_thb_", $img);
                $l2  = explode(".", $l1[1]);
                $l3  = explode("x", $l2[0]);
               	$big = $l1[0].".".$l2[1];
       	}
	else
	{
		$l = strlen($img);
		$ext  = substr($img, $l-4);
		$name = substr($img, 0, $l-4);
		$big = $name."_big".$ext;
	}

	if (is_file(".".$big))
	{
		$res = str_replace("/>", ">", $res);
		$res = str_replace(">", " border=\"0\" />", $res);
		$res = "<a href=\"{$big}\" onclick=\"javascript: tt1('{$big}', $_parse_images_code); return false;\" target=\"_blank\" class=\"gallery\">{$res}</a>";
		$_parse_images_array[$_parse_images_code][] = $big;
	}

	return $res;
}


$trnsl = array("r"=>"Russian", "e"=>"English", "g"=>"Germany", "f"=>"France", "s"=>"Spanish", "p"=>"Portugal", "i"=>"Italian");
function translate($text, $from, $to)
{
	$res = $text;
	$f = fopen("http://m.translate.ru/translator/result/?text={$text}&dirCode={$from}{$to}", "r");
	if ($f)
	{
		$data = "";
		while(!feof($f))
			$data .= fgets($f, 4096);
		fclose($f);

		$l1 = explode("\"tres\">", $data);
		$l2 = explode("</div>", $l1[1]);
		$res = $l2[0];
	}

	return $res;
}


?>
