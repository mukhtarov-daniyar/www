<?

require_once('AcImage.php');

class gallery
{

	function Gallery()
	{
		GLOBAL $CFG, $DB;

		$CFG->SEARCH[] = $this;
	}

	function findByTheme($theme_id, $page_id=0)
	{
	}

	function getTree($page_id=0)
	{
	}

	function getCount($pageId, $where="")
	{
		global $CFG;
		$pageId *= 1;
		if ($where != "")
			$where = "{$where}";
		$sql = "SELECT COUNT(id) FROM {$CFG->DB_Prefix}gallery WHERE page_id='{$pageId}' {$where} AND visible='1' ORDER BY pos DESC";
		return 1 * getSQLField($sql);
	}

	function getList($pageId, $count=0, $start=0, $where="")
	{
		global $CFG;
		$count *= 1;
		$start *= 1;
		$pageId *= 1;
		$w1 = ($pageId > 0 ? "page_id={$pageId} AND " : "");
		if ($count > 0)
			$w = "LIMIT {$start}, {$count}";
		if ($where != "")
			$where = "$where";
		$sql = "SELECT * FROM {$CFG->DB_Prefix}gallery WHERE {$w1} visible='1' {$where} ORDER BY pos DESC $w";
		$l = getSQLArrayO($sql);
		return $l;
	}

	function getObject($pageId, $id)
	{
		global $CFG;
		$pageId *= 1;
		$id *= 1;
		if ($id > 0)
			$w = "page_id={$pageId} AND id='{$id}'";
		else
			$w = "page_id={$pageId} ORDER BY pos LIMIT 0,1";
	$sql = "SELECT * FROM {$CFG->DB_Prefix}gallery WHERE {$_CFG->lang_where_and} {$w}";
		$o = getSQLRowO($sql);
		
		return $o;
	}
	
	function getObjectAtPosition($pageId, $pos=0, $where="")
	{
		GLOBAL $CFG;
		$pageId *= 1;
		$pos *= 1;
		$w = "page_id={$pageId} ORDER BY pos LIMIT {$pos},1";
		if ($where != "")
			$w = "({$where}) AND ".$w;
		$sql = "SELECT * FROM {$CFG->DB_Prefix}gallery WHERE {$CFG->lang_where_and} {$w}";
		$o = getSQLRowO($sql);
		return $o;
	}
	
	function addItem($pageId, $url, $name, $img, $body="", $pos=0, $vis=1)
	{
		GLOBAL $CFG;

		$pageId *= 1;
		$pos *= 1;

		if ($pos>=0)
		{
			$pos = 10 + 1*getSQLField("SELECT max(pos) FROM {$CFG->DB_Prefix}gallery WHERE page_id='{$pageId}'");
		}

		$sql  = "INSERT INTO {$CFG->DB_Prefix}gallery (page_id, url, name, img, body, pos, visible, sys_language) VALUES (";
		$sql .= "'{$pageId}', ";
		$sql .= "'".addSlashes($url)."', ";
		$sql .= "'".addSlashes($name)."', ";
		$sql .= "'".addSlashes($img)."', ";
		$sql .= "'".addSlashes($body)."', ";
		$sql .= "'{$pos}', ";
		$sql .= "'{$vis}', ";
		$sql .= "'{$CFG->SYS_LANG}')";

		$DB = createConnection();
		$DB->query($sql);
		return( $DB->last_id() );
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
		$q3 = "";
		for ($i=0; $i<sizeof($arr); $i++)
		{
			$word = addslashes($arr[$i]);
			$q1 .= " AND (F.intro LIKE '%{$word}%' OR F.name LIKE '%{$word}%')";
			$q2 .= " AND (F.name LIKE '%{$word}%' OR F.description LIKE '%{$word}%')";
			$q3 .= " AND (F.intro LIKE '%{$word}%' OR F.body LIKE '%{$word}%')";
		}

		$sql = "SELECT DISTINCT F.cat_id, F.pos, F.id, F.page_id, P.name FROM {$CFG->DB_Prefix}gallery F LEFT OUTER JOIN {$CFG->DB_Prefix}pages P ON P.id=F.page_id WHERE P.sys_language='{$CFG->SYS_LANG}' AND F.sys_language='{$CFG->SYS_LANG}' AND P.visible='1' AND F.visible='1' {$q1}";
//echo "<br/>$sql<br/>";
		$l = getSQLArrayO($sql);		

		$opt = array();
		$res = array();
		for ($i=0; $i<sizeof($l); $i++)
		{
			$p_id = $l[$i]->page_id;
			if ($opt[$p_id] == 0)
			{
				$o = getPageInfo($p_id);
				$opt[$p_id] = $o->aOptions["perline"] * $o->aOptions["perpage"];
			}
			$pos = 1 + 1 * getSQLField("select count(id) from {$CFG->DB_Prefix}gallery where sys_language='{$CFG->SYS_LANG}' AND  page_id='{$p_id}' and cat_id='{$l[$i]->cat_id}' and pos<'{$l[$i]->pos}' order by pos, name");
			$p = ceil($pos / $opt[$p_id])-1;
			$p = ($p>=0 ? $p : 0);
//			$res[$i]->name	= $l[$i]->name." [p{$p}]";
			$res[$i]->name	= striptags(getfullpagepath($l[$i]->page_id)).($l[$i]->cat_id>0 ? " / ".striptags(getfullgallerypath($l[$i]->cat_id)) : "").($p>0 ? " <i>({$CFG->Locale["page"]} ".($p+1).")</i>" : "");
			$res[$i]->url	= "index.php?page={$p_id}&cid={$l[$i]->cat_id}".($p>0 ? "&p={$p}" : "");
		}


		$sql = "SELECT DISTINCT F.name, F.pos, F.id, F.page_id, P.name AS pname FROM {$CFG->DB_Prefix}gallery F LEFT OUTER JOIN {$CFG->DB_Prefix}pages P ON P.id=F.page_id WHERE P.sys_language='{$CFG->SYS_LANG}' AND F.sys_language='{$CFG->SYS_LANG}' AND P.visible='1' AND F.visible='1' {$q3}";
//echo "<br/>$sql<br/>";
		$l = getSQLArrayO($sql);		

		$cnt = $i;
		for ($i=0; $i<sizeof($l); $i++)
		{
			$l[$i]->name = ($l[$i]->name ? $l[$i]->name : "?????");
//			$res[$cnt]->name = $l[$i]->pname." &gt; ".$l[$i]->name;
			$res[$cnt]->name = striptags(getfullpagepath($l[$i]->page_id)).($l[$i]->cat_id>0 ? " / ".striptags(getfullgallerypath($l[$i]->cat_id)) : "")." / ".$l[$i]->name;
			$res[$cnt]->url  = "index.php?page={$l[$i]->page_id}&id={$l[$i]->id}";
			$cnt++;
		}

		return $res;
	}


	function makePreviewName($url, $w, $h, $mode)
	{
			$w *= 1;
			$h *= 1;
			$mode *= 1;
			$l1 = explode(".", $url);
			$img = $l1[0];
			for ($i1=1; $i1<sizeof($l1)-1; $i1++)
			$img .= ".".$l1[$i1];
			$img .= "_thb_{$w}x{$h}x{$mode}.".$l1[ sizeof($l1)-1 ];
			$l = explode("/", $img);
			$img = $l[0];
			for ($i=1; $i<sizeof($l)-1; $i++)
			$img .= "/".$l[$i];
			$img .= "/thb/".$l[$i];
            //$img = preg_replace("/.JPG/", ".jpg", $img);
			//$img = preg_replace("/.jpeg/", ".jpg", $img);
			//print_r($img);	
			return $img;
	}

}


?>