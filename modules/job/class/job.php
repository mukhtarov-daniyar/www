<?

class Job
{

	function News()
	{
		global $CFG, $DB;

	}

	function findByTheme($theme_id, $page_id=0)
	{
	}
	
	function getCount($pageId, $where="")
	{
		global $CFG;
		$pageId *= 1;

		return 1 * getSQLField("SELECT COUNT(id) FROM {$CFG->DB_Prefix}news WHERE {$CFG->lang_where_and} page_id={$pageId} AND visible='1' {$where} ORDER BY cdate DESC");
	}

	function getCountSearch($pageId, $where="", $search_word="")
	{
		global $CFG;
		$pageId *= 1;

		return 1 * getSQLField("SELECT COUNT(id) FROM {$CFG->DB_Prefix}users WHERE {$search_word} visible='1' AND status='3' {$where}  {$and} ORDER BY cdate DESC");
	}
	
	function getList($count=0, $start=0, $where="", $limit, $search_word="")
	{
		global $CFG;
		
		$count *= 1;
		$start *= 1;
		$pageId *= 1;
		
		if ($count > 0)
			$w = "LIMIT {$start}, {$count}";
			
		if ($where != "")
			$where = "{$where}";
			
		$sql = "SELECT * FROM {$CFG->DB_Prefix}users WHERE {$search_word} visible='1' AND status='3' {$where} ORDER BY cdate DESC $w";
		$l = getSQLArrayO($sql);
		return $l;
	}
	
	

	function delInsert($user_id, $value=0)
	{
		
		global $CFG;
		
		$CFG->USER->updateUserField('status', $value, $user_id);
			
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function newInsert($user_id, $value=0)
	{
		
		global $CFG;
		
		$CFG->USER->updateUserField('status', $value, $user_id);
			
		redirect($_SERVER['HTTP_REFERER']);
	}



	

		
}


?>