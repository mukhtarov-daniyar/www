<?

class Acon
{
	function getList($where="", $search_word="", $count=0, $start=0)
	{
		global $CFG;
		
		$count *= 1;
		$start *= 1;
		$pageId *= 1;
		
		if ($count > 0)
			$w = "LIMIT {$start}, {$count}";
			
		if ($where != "")
			$where = "{$where}";
			
			
		$year = date('Y')*1;
		$day = date('t')*1;
		$month_clear = date('m');
			
		if(!$_GET["cdate"] > 0 or !$_GET["year"] > 0)
		{
			if($_GET["year"] == 0)
			{
				$year = $_GET["year"];
				$cdate = "";
			}
			elseif($_GET["year"] > 0 && $_GET["cdate"] == 0)
			{
				$year = $_GET["year"];
				$cdate = "AND (cdate >= '{$year}-01-01 00:00:00') AND (cdate <= '{$year}-12-31 23:59:59')"; 
			}
			else
			{	
				$cdate = "AND (cdate >= '{$year}-{$month_clear}-01 00:00:00') AND (cdate <= '{$year}-{$month_clear}-{$day} 23:59:59')"; 
			}
		}
		if($_COOKIE["company"] > 0)
		   {
				$director = $_COOKIE["company"];
				$search_where .= " AND director_id = '{$director}' ";	
		   }
		elseif($CFG->USER->USER_ID == 92)
		{
				$director = 521;
				$search_where .= " AND director_id = 521 ";				
		}
		else
			{
				$director = $CFG->USER->USER_DIRECTOR_ID;
				$search_where .= " AND director_id = '{$director}' ";	
			}

		$userId = $this->groupAccess($CFG->USER->USER_DIRECTOR_ID);
		
		$sql = "SELECT * FROM {$CFG->DB_Prefix}money_accounting WHERE visible='1' AND director_id = '{$director}'  {$search_word}  {$where}  {$cdate}  {$userId} ORDER BY cdate DESC $w";

		$l = getSQLArrayO($sql);

		return $l;
	}
	
	
	
	function getListExel($where="", $type=0)
	{
		global $CFG;
		
	 	$sql = "SELECT * FROM {$CFG->DB_Prefix}money_accounting WHERE visible='1' {$where}  AND type_id = {$type} ORDER BY cdate DESC $w";

		$l = getSQLArrayO($sql);
		
		return $l;
	}


	public function groupAccess($user_id)
	{
		global $CFG;
		
		if($CFG->USER->USER_ID > 0)
		{					
			$big_user = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}users WHERE visible='1' AND accounting = 1 AND user_id = {$user_id} ");
			
			if(count($big_user) > 0)
			{
				foreach($big_user as $key => $value)
				{
					$id .= $value->id.',';
				}
														
				$user_id = explode(",", trim($id, ","));
						
				if(in_array($CFG->USER->USER_ID, $user_id))
				{

				}
				else
				{					
					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'У Вас к сожалению нет доступа к этой странице!';					
					redirect('/');
				}
					
			}
			
		}

		return $final;
	}

	
	function sum($user)
	{
		global $CFG;

		if($_COOKIE["company"] > 0)
				$director = $_COOKIE["company"];
		elseif($CFG->USER->USER_ID == 92)
		{
				$director = 521;
				$search_where .= " AND director_id = 521 ";				
		}
		else
				$director = $user;
                    
		$z = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$director}' AND type_id = 1 AND visible='1' ");
		
		$p = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$director}' AND type_id = 2 AND visible='1' ");
		
		$plus = $z[0]->{'SUM(price)'};
		$minus = $p[0]->{'SUM(price)'};
		
		return number_sum($plus-$minus);
	}
	
	
	function sum_now($user, $where = "")
	{
		global $CFG;
                    
		$z = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$user}' {$where} AND type_id = 1 AND visible='1' ");
		
		$p = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$user}' {$where} AND type_id = 2 AND visible='1' ");
		
		$plus = $z[0]->{'SUM(price)'};
		$minus = $p[0]->{'SUM(price)'};
		
		return number_sum($plus-$minus);
	}
	
	
	function earnings($m, $user_id, $type=0)
	{
		global $CFG;

		$year = date('Y')*1;
		$day = cal_days_in_month(CAL_GREGORIAN, $m, $year);

			$cdate = "AND (cdate >= '{$year}-{$m}-01 00:00:00') AND (cdate <= '{$year}-{$m}-{$day} 23:59:59')"; 
		
		$z = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$user_id}' {$cdate} AND type_id = {$type} AND visible='1' ");
        
		$plus = $z[0]->{'SUM(price)'};  

		return number_sum($plus); 
	}
	
	function number_sum($m, $user_id, $type=0)
	{
		global $CFG;

		$year = date('Y')*1;
		$day = cal_days_in_month(CAL_GREGORIAN, $m, $year);

			$cdate = "AND (cdate >= '{$year}-{$m}-01 00:00:00') AND (cdate <= '{$year}-{$m}-{$day} 23:59:59')"; 
		
		return 1 * getSQLField("SELECT COUNT(id) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$user_id}' {$cdate} AND type_id = {$type} AND visible='1' ");
        
	}
	
	
	
	


	function getData($response)
	{
		global $CFG;

		foreach($response as $key => $value)
		{	
			if($key == 'search')continue;
			if($key == 'add')continue;
			if($key == 'p')continue;

			if($value == 0) continue;


			if($key == 'cdate')
			{
				
				
				if($_GET['year'] > 0)
				{
					$year = $_GET['year'];
				}
				else
					$year = date('Y')*1;
				
				if($value > 0)
				{
					$month = $value;	
				}
				else
				{
					$month = date('m')*1;
				}
				
                $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);

				$str .= " AND (cdate >= '{$year}-{$month}-01 00:00:00') AND (cdate <= '{$year}-{$month}-{$number} 23:59:59')";

			}
			if($key == 'cdate') continue;
		
			if($key == 'year') continue;
			
		
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



	public function ExplodeCat($catId)
	{
		global $CFG;
		
		$sql = getSQLArrayO("SELECT type_company_id, id FROM {$CFG->DB_Prefix}news WHERE visible='1' ORDER BY cdate DESC");
			
		for ($y=0; $y<sizeof($sql); $y++)
		{	
			if($sql[$y]->type_company_id == "0") continue;
			
			$cat_id = explode(",", $sql[$y]->type_company_id);
			
			for ($x=0; $x<sizeof($cat_id); $x++)
			{
				$res = $cat_id[$x];	
				
				if($res == $catId)
				{
					$id .= $sql[$y]->id.',';
				}
			}

		}

		$idS = trim($id, ",");
		
		if($idS == !"")
			$newsIdAnd .= " AND id in({$idS}) ";


		return $newsIdAnd;
	}


		
}


?>