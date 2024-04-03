<?

class Response
{

	function Response()
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

	function getCountSearch($pageId, $where="", $search_word="", $and)
	{
		global $CFG;
		$pageId *= 1;
		
		if($CFG->USER->USER_STATUS === '0' )
		{
			$user = "and user_id='{$CFG->USER->USER_ID}'";
		}
		elseif($CFG->USER->USER_STATUS ==3 )
		{
			$user = "and job_user_id='{$CFG->USER->USER_ID}'";
		}

		return 1 * getSQLField("SELECT COUNT(id) FROM {$CFG->DB_Prefix}response WHERE {$search_word} sys_language='{$CFG->SYS_LANG}' and page_id={$pageId} {$where}  {$and} {$user} ORDER BY cdate DESC");
	}
	
	function getList($pageId, $count=0, $start=0, $where="", $limit, $search_word="", $and="")
	{
		global $CFG;
		
		if($CFG->USER->USER_STATUS === '0' )
		{
			$user = "and user_id='{$CFG->USER->USER_ID}'";
		}
		elseif($CFG->USER->USER_STATUS ==3 )
		{
			$user = "and job_user_id='{$CFG->USER->USER_ID}'";
			$jober ="AND visible <> 0";
		}
		
		$count *= 1;
		$start *= 1;
		$pageId *= 1;
		
		$w1 = ($pageId > 0 ? "page_id={$pageId}  " : "");
		
		if ($count > 0)
			$w = "LIMIT {$start}, {$count}";
			
		if ($where != "")
			$where = "{$where}";
			
		$sql = "SELECT * FROM {$CFG->DB_Prefix}response WHERE {$search_word} {$CFG->lang_where_and} {$w1} {$where} {$and} {$user} {$jober} ORDER BY cdate DESC $w";
		$l = getSQLArrayO($sql);
		return $l;
	}
	
	
	function getListAjax($pageId, $num)
	{
		global $CFG;
		$count *= 1;
		$start *= 1;
		$pageId *= 1;
		
		$w1 = ($pageId > 0 ? "page_id={$pageId} AND " : "");
		
		if ($count > 0)
			$w = "LIMIT {$start}, {$count}";
			
		if ($where != "")
			$where = "{$where}";
			
		$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE {$CFG->lang_where_and} {$w1} visible='1' ORDER BY cdate DESC, pos  limit {$num}, 12"; 
		$l = getSQLArrayO($sql);
		return $l;
	}

	function getObject($pageId, $id=0)
	{
		global $CFG;
		$pageId *= 1;
		$id *= 1;
		if ($id > 0)
			$w = "id='{$id}'";
		else
			$w = "page_id={$pageId} ORDER BY cdate DESC LIMIT 0,1";
		$sql = "SELECT * FROM {$CFG->DB_Prefix}response WHERE {$w}";
		$o = getSQLRowO($sql);
		return $o;
	}
	
	
	function insertResponse($id, $page_id, $user, $response, $url)
	{
		global $CFG;
		
		$cdata = sqlDateNow();
		
		$sql = "SELECT * FROM {$CFG->DB_Prefix}response WHERE vacancy_id='{$id}' and user_id='{$user}'";
		$z = getSQLRowO($sql);
		
		$url = explode('/', $_SERVER['REQUEST_URI']);
		$redirect = '/'.$url[1].'/'.$url[2].'/'.$url[3].'/';
		
		if($z->id)
		{
			$CFG->STATUS->ERROR = true;
			$CFG->STATUS->MESSAGE = 'Вы уже откликались на эту вакансию';
			
			redirect($redirect);
		}
		else 
		{
			$sql = "INSERT INTO {$CFG->DB_Prefix}response (vacancy_id, page_id, user_id, body, cdate, visible, sys_language) VALUES ({$id}, {$page_id}, {$user}, '{$response}', '{$cdata}', 1, '{$CFG->SYS_LANG}')";
			$CFG->DB->query($sql);
			
			$CFG->STATUS->ERROR = true;
			$CFG->STATUS->MESSAGE = 'Спасибо! Ваш отклик будет рассмотрен в ближайшее время.';
			
			redirect($redirect);
		}
		
		

	}

	
	function putCount($id, $count=0,$modern=0) {
		
		global $CFG;
		
		$query  = "UPDATE {$CFG->DB_Prefix}response SET visible='{$count}', edit = {$modern}, modern = 1  WHERE id='{$id}'";
		$CFG->DB->query($query);
		
	}

	function getData($response)
	{
	
		foreach($response as $key => $value)
		{	
			if($key == 'search')continue;
			if($key == 'p')continue;
			
			if($value == 0) continue;
						
				if (is_numeric($value))
				{
					$str .= 'AND '.$key.'_id='.$value.' ';	
					
				}
				else
				{
					continue;
				}
		}
		
			return $str;
		}

		
}


?>