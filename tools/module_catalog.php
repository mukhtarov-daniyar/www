<?

function getCategoriesTree($parent=0, $includeHead=0)
{
	GLOBAL $CFG;
	$tree = getTree("{$CFG->DB_Prefix}categories", "id", "name", "parent_id", "article, name", $w="");
	if ($parent>0)
	{
		$tree1 = array();
		for($i=0; $i<sizeof($tree) && $tree[$i]->id!=$parent; $i++);
		$lev = $tree[$i]->level;
		if ($includeHead)
			$tree1[] = $tree[$i];
		for($i++; $i<sizeof($tree) && $tree[$i]->level!=$lev; $i++)
			$tree1[] = $tree[$i];
		$tree = $tree1;
	}
	return $tree;
}


function getProps($page_id, $cat_id, $prod_id)
{
	GLOBAL $CFG;

	$props = getSQLField("SELECT props FROM {$CFG->DB_Prefix}prods_cats WHERE id='{$cat_id}' AND sys_language='{$CFG->SYS_LANG}'");
	$propsA = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}prods_p2p WHERE prod_id='{$prod_id}' AND sys_language='{$CFG->SYS_LANG}'");
	$p2 = array();
	for ($i=0; $i<sizeof($propsA); $i++)
		$p2[$propsA[$i]->prop_id] = $propsA[$i]->val;

	$l1 = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}prods_props WHERE id IN (0{$props}0) AND sys_language='{$CFG->SYS_LANG}'");
	$l2 = array();
	$cnt = 0;
	for ($i=0; $i<sizeof($l1); $i++)
	{
		$l2[$cnt]->name = $l1[$i]->name;
		$l2[$cnt]->id = $l1[$i]->id;
		$l2[$cnt]->type = $l1[$i]->ptype;
		$l2[$cnt]->vallist = $l1[$i]->vallist;
		$l2[$cnt]->val = $p2[ $l1[$i]->id ];
		$cnt++;
	}

/*
	$oPageInfo = getPageInfo($page_id);
	$l1 = explode("\n", $oPageInfo->aOptions["catalog_props"]);
	$l2 = array();
	$cnt = 0;
	for ($i=0; $i<sizeof($l1); $i++)
		if ($l1[$i] != "")
		{
			$l = explode(":", $l1[$i]);
			if (strstr($props, ",{$l[0]},"))
			{
				$l2[$cnt]->name = $l[1];
				$l2[$cnt]->id = $l[0];
				$l2[$cnt]->val = $p2[$l[0]];
				$cnt++;
			}
		}
*/

	return $l2;
}


class Catalog
{
	function Catalog()
	{
		GLOBAL $CFG, $DB;

		$CFG->SEARCH[] = $this;
	}

	function search($words)
	{
		GLOBAL $CFG, $DB;

		if (is_array($words))
			$arr = $words;
		else
			$arr = explode(" ", ereg_replace("[,]", " ", $words));

		$q1 = "";
		$q2 = "";
		for ($i=0; $i<sizeof($arr); $i++)
		{
			$word = addslashes($arr[$i]);
			$q1 .= " AND (F.name LIKE '%{$word}%' OR F.body LIKE '%{$word}%')";
			$q2 .= " AND (F.name LIKE '%{$word}%' OR F.description LIKE '%{$word}%')";
		}

		$sql = "SELECT DISTINCT F.name, F.id, F.cat_id, F.page_id, P.name AS pname FROM {$CFG->DB_Prefix}prods F LEFT OUTER JOIN {$CFG->DB_Prefix}pages P ON P.id=F.page_id WHERE P.sys_language='{$CFG->SYS_LANG}' AND F.sys_language='{$CFG->SYS_LANG}' AND P.visible='1' AND F.visible='1' {$q1}";
		$l = getSQLArrayO($sql);		

		$res = array();
		for ($i=0; $i<sizeof($l); $i++)
		{
//			$res[$i]->name	= $l[$i]->name;
//			$res[$i]->name = $l[$i]->pname." / ".stripTags(getFullCatalogPath($l[$i]->cat_id))." / ".$l[$i]->name;
			$res[$i]->name = striptags(getfullpagepath($l[$i]->page_id))." / ".stripTags(getFullCatalogPath($l[$i]->cat_id))." / ".$l[$i]->name;
			$res[$i]->url	= "index.php?page={$l[$i]->page_id}&id={$l[$i]->id}";
		}

		$sql = "SELECT DISTINCT F.name, F.id, F.page_id, P.name AS pname FROM {$CFG->DB_Prefix}prods_cats F LEFT OUTER JOIN {$CFG->DB_Prefix}pages P ON P.id=F.page_id WHERE P.sys_language='{$CFG->SYS_LANG}' AND F.sys_language='{$CFG->SYS_LANG}' AND P.visible='1' AND F.visible='1' {$q2}";
		$l = getSQLArrayO($sql);		

		$cnt = $i;
		for ($i=0; $i<sizeof($l); $i++)
		{
//			$res[$cnt]->name = $l[$i]->pname." / ".stripTags(getFullCatalogPath($l[$i]->id));
			$res[$cnt]->name = striptags(getfullpagepath($l[$i]->page_id))." / ".stripTags(getFullCatalogPath($l[$i]->id));
			$res[$cnt]->url  = "index.php?page={$l[$i]->page_id}&cid={$l[$i]->id}";
			$cnt++;
		}

		return $res;
	}



	function showBlock($o, $newwin=1)
	{
	global $CFG;

	if (!is_object($o))
		return;

	if ($o->img2 == "")
		$o->img2 = $o->img;

	if ($o->img2 == "")
		$o->img2 = "/img/nophoto.gif";
	else
		$o->img2 = "/_preview.php?url={$o->img2}&w=100&h=100&mode=1";
?>
<!-- product -->
		<form name='f<?=$o->id?>' id='f<?=$o->id?>' method='post'>
		<input type='hidden' name='cart_act' value='addItem' />
<table valign='top' width='100%' border='0' cellpadding="2" cellspacing="0" class='pan1'>
<tr>
<td width='100' height='100' align='center' valign='top' rowspan='2'>
<?
	if ($newwin == 1)
	{
?>
<a href='#' onclick="var w=window.open('catalog_info.php?id=<?=$o->id?>','1','width=800,height=700,directories=no,location=no,menubar=no,status=no,toolbar=no,resizable=1,scrollbars=1,left=0,top=0,screenx=50,screeny=50'); w.focus(); return false">
<?
	}
	else
	{
?>
<a href='index.php?page=<?=$o->page_id?>&cid=<?=$o->cat_id?>&id=<?=$o->id?>'>
<?
	}
?>
<img src='.<?=$o->img2?>' border='0' alt='<?=ecrane($o->name)?>' width='100' height='100' class='img1' /></a>
</td>
<td valign='top'><b><?=$o->name?>&nbsp;</b><br /><?=$CFG->Locale["ct_art"]?>:<?=$o->article?></td></tr>
<tr><td valign='bottom'>
<?
	if ($CFG->USER->USER_ACL)
		$o->cost = $o->cost2;


	if (1*$o->cost <= 0 && $CFG->CATALOG->USE_COST)
	{
?>
		<span class="red"><?=$CFG->Locale["ct_unavailable"]?><br /><br /></span>
<?
	}
	else
	{
		if ($CFG->CATALOG->USE_COST)
		{
?>
		<?=$CFG->Locale["price"]?>: <span class="red"><?=hs($o->cost)?>р.</span><br />  
<?
		}

		if ($CFG->CATALOG->USE_CART)
		{
?>
		<?=$CFG->Locale["count"]?>: <input type="text" name="cnt<?=hs($o->id)?>" id="cnt<?=hs($o->id)?>" size="2" maxlength="4" value="0" style="width:50px;" /><input type="hidden" name="itemID" value="<?=hs($o->id)?>" /> 
		<img src="img/add2cart.gif" height="16" width="16" border="0" align="absmiddle" alt='' /><a href="#" onclick='addToCart("<?=hs($o->id)?>"); return false;' class='more1'><?=$CFG->Locale["ct_to_cart"]?></a>
<?
		}
	}

?>
</td></tr>
</table>
		</form>
<!-- /product -->
<?
	}

}

?>