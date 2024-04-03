<?

class Deal
{
	function getCount($pageId, $where="", $search_word="", $and)
	{
		global $CFG;
		$pageId *= 1;

		if($_COOKIE["order"])
			$cookie = $_COOKIE["order"];
		else
			$cookie = "ORDER BY cdate DESC";

		$w1 = ($pageId > 0 ? "AND page_id={$pageId}  " : "");

		$value = apost($_GET["search"]);
		$count = mb_strlen(trim($value), 'UTF-8');

		if($count > 1)
		{

			$final = $this->odid('comments', $pageId, $value);
		}
		else
		{
			$final = "";
		}

		$userId = $this->groupUser('users', $CFG->USER->USER_ID);


		if(big_access('view_loader'))
		{
			$load = " AND manager_id = {$CFG->USER->USER_ID}";
		}

		if($count > 1)
		{

			$sql = "SELECT id FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word}  {$w1} {$where} {$userId} {$access} {$cookie} ";
			$res = getSQLArrayO($sql);

			$list = $this->comment_search($res, $value);

			$sql =  getSQLRowO("SELECT COUNT(id) as id FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word} {$w1}  {$where}  {$userId} {$access} {$and}{$list} {$cookie}  $w"); return $sql->id;

		}
		else
		{
			$sql = getSQLRowO("SELECT COUNT(id) as id FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word}  {$w1} {$where} {$and} {$userId}  {$access} {$cookie} $w"); return $sql->id;
		}

	}



	function getList($pageId, $count=0, $start=0, $where="", $limit, $search_word="", $and="")
	{
		global $CFG;

		$count *= 1;
		$start *= 1;
		$pageId *= 1;

		$w1 = ($pageId > 0 ? "AND page_id={$pageId}  " : "");

		if ($count > 0)
			$w = "LIMIT {$start}, {$count}";

		if ($where != "")
			$where = "{$where}";

		if($_COOKIE["order"])
			$cookie = $_COOKIE["order"];
		else
			$cookie = "ORDER BY cdate DESC";

		$value = apost($_GET["search"]);
		$count = mb_strlen(trim($value), 'UTF-8');

		$userId = $this->groupUser('users', $CFG->USER->USER_ID);

		if(big_access('view_loader'))
		{
			$load = " AND manager_id = {$CFG->USER->USER_ID}";
		}

		if($count > 1)
		{
			$sql = "SELECT id FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word}  {$w1} {$where} {$userId} {$access} {$cookie} ";
			$res = getSQLArrayO($sql);

			$list = $this->comment_search($res, $value);


		 	$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word} {$w1} {$where}

			{$userId}
			{$access}

			{$and}
			{$list}

			{$cookie}  $w";


			$l = getSQLArrayO($sql);
		}
		else
		{
			 $sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE page_id = {$pageId} {$load} {$search_word}  {$w1} AND visible='1' {$where} {$and} {$userId}  {$access} {$cookie} $w";
			 $l = getSQLArrayO($sql);
		}

		return $l;
	}


	public function comment_search($res, $value)
	{
		global $CFG;

		for ($x=0; $x<sizeof($res); $x++)
		{
			$news_id .= $res[$x]->id.',';
		}

		$news_and_id = trim($news_id, ",");

		if($news_and_id !== "")
		{
			$final_news = " AND page_id IN ({$news_and_id}) ";
		}
		else
		{
			$final_news = "";
		}

		$res_comments = getSQLArrayO("SELECT id, page_id, user_id FROM my_comments WHERE visible='1' AND parent_id = '1000' {$final_news} AND MATCH(text) AGAINST('+" . $value . "' IN BOOLEAN MODE); ");
	//	$res_comments = getSQLArrayO("SELECT id, page_id, user_id FROM {$CFG->DB_Prefix}comments WHERE visible='1' AND parent_id = '1000' {$final_news} AND text LIKE '%{$value}%' ");



		for ($y=0; $y<sizeof($res_comments); $y++)
		{
			$comments_page_id .= $res_comments[$y]->page_id.',';
		}

		$or_id = trim($comments_page_id, ",");

		if($or_id !== "")
		{
			$dop_page = " OR id IN ({$or_id})";
		}
		else
		{
			$dop_page = "";
		}

		return $dop_page;
	}





	public function AccessCompany($company_id)
	{
		global $CFG;

		if($CFG->USER->USER_ID > 0)
		{
			$company_id = explode(",", $company_id);
			if((count($company_id)) >= 1)
			{
				for ($y=0; $y<sizeof($company_id); $y++)
                {
					$user = AndDataArray('users', 'user_id', $company_id[$y], 0);

					for ($z=0; $z<sizeof($user); $z++)
                	{
						$id .= $user[$z]->id.',';
					}
				}
					$and_id = trim($id, ",");

					if($and_id == !"")
					{
						$manager_id .= ", {$and_id}";
					}

			}
			else
			{
				$manager_id ="";
			}
		}

		return $manager_id;
	}


	function BgAccessCompany($boss_id)
	{
		global $CFG;

		if($CFG->USER->USER_ID > 0 )
		{
			$company_id = explode(",", $CFG->USER->ACCESS_COMPANY);

			if(count($company_id) >= 1 )
			{
				if(in_array($boss_id, $company_id))
				{
					return true;
				}
				else
				{
					return false;
				}
			}

		}

	}


	public function groupUser($name, $user_id)
	{
		global $CFG;

		if($CFG->USER->USER_ID > 0)
		{

			$sql = "SELECT user_id FROM {$CFG->DB_Prefix}{$name} WHERE id={$user_id}";
			$res = getSQLRowO($sql);

			$big_user = AndDataArray('users', 'user_id', $res->user_id, 0);

			if(count($big_user) > 0)
			{
				for($y=0;$y<sizeof($big_user);$y++)
				{

					$in .= $big_user[$y]->id.",";
				}

				$AccessCompany = $this->AccessCompany($CFG->USER->ACCESS_COMPANY);
				$andid = trim($in, ",");

				$someArray1 = explode(",", $andid);
				$someArray2 = explode(",", $AccessCompany);

				$array1 = isset($someArray1) ? $someArray1 : [];
				$array2 = isset($someArray2) ? $someArray2 : [];

				$result = array_merge($array1, $array2);

				$uniqueResult = array_unique($result);

				$filteredResult = array_filter($uniqueResult);

				$stringResult = implode(", ", $filteredResult);


				$final .= " AND manager_id IN ({$stringResult})";


				if(($CFG->USER->USER_STATUS == 1) || ($CFG->USER->USER_STATUS == 2) || $_GET["type_company"] == 6)
				{
					$final ="";
				}

			}
			else
			{
				$final ="";
			}
		}


		return $final;
	}




	public function groupUserOffice($name, $user_id)
	{
		global $CFG;

		if($CFG->USER->USER_ID > 0)
		{

			$sql = "SELECT user_id FROM {$CFG->DB_Prefix}{$name} WHERE id={$user_id}";
			$res = getSQLRowO($sql);

			$big_user = AndDataArray('users', 'user_id', $res->user_id, 0);


			if(count($big_user) > 0)
			{
				for($y=0;$y<sizeof($big_user);$y++)
				{

					$in .= $big_user[$y]->id.",";
				}

				$andid = trim($in, ",");
				$final .= " AND manager_id IN ({$andid})";

			}
			else
			{
				$final ="";
			}
		}


		return $final;
	}


	public function access($status)
	{
		if ($status)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function odid($name, $pageId, $value)
	{
		global $CFG;


			$sql = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}users WHERE user_id='{$CFG->USER->USER_DIRECTOR_ID}'");

			for ($y=0; $y<sizeof($sql); $y++)
			{
				$id .= $sql[$y]->id.',';
			}

			$idS = trim($id, ",");

			if($idS == !"")
				$newsIdAnd .= " AND id in({$idS}) ";


		$sql = "SELECT page_id, id, user_id FROM {$CFG->DB_Prefix}{$name} WHERE visible='1' AND parent_id = '{$pageId}' AND text LIKE '%{$value}%' {$newsIdAnd} ";
		$res = getSQLArrayO($sql);

		if(count($res) > 0)
		{
			for($y=0;$y<sizeof($res);$y++)
			{
				$id_pid = $res[$y]->page_id;

				$same = getSQLRowO("SELECT id, type_company_id, manager_id, city_id, access_id  FROM {$CFG->DB_Prefix}news WHERE id='{$id_pid}' and page_id = '{$pageId}'  and visible=1");


				/* Когда выбрана только компания а пользователь нет*/
				if(($_GET["type_company"] > 0) && ($_GET["users"] == 0))
				{
					$respon = SelectDataParent('users', 'id', $same->manager_id);

					$id_director = $respon[0]->user_id;

					if($id_director == $_GET["type_company"])
					{
						if(is_numeric($same->id))
						{
							$in .= $same->id.',';
						}
					}
				}

				/* Когда выбрана компания и пользователь */
				if(($_GET["type_company"] > 0) && ($_GET["users"] > 0))
				{

					if($same->manager_id == $_GET["users"] && $same->type_company_id == $_GET["type_company"] )
					{
						$in .= $same->id.',';
					}
				}
			}


			$andid = trim($in, ",");

			if($andid == "")
			{
				$final = "";
			}
			else
			{
				$final .= " OR id IN ({$andid})";
			}

		}
		else
		{
			$final ="";
		}

		return $final;
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
		$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE {$w}";
		$o = getSQLRowO($sql);
		return $o;
	}

	function getObjectJsonData($pageId)
	{
		global $CFG;

		$json_daily_file = 'documents/buch/data_1C_LIL.json';
		$result = json_decode(file_get_contents($json_daily_file));

		foreach ($result->{$pageId}->ДанныеПоПоступлению as $values)
		{
			$ars[] = $values->Сумма;
		}
		return number_sum(array_sum($ars));
	}


	function putCount($id, $count=0) {

		global $CFG;

		$count++;
		$query  = "UPDATE {$CFG->DB_Prefix}news SET view='{$count}' WHERE id='{$id}'";
		$CFG->DB->query($query);

	}





	function getData($response)
	{
		global $CFG;

		foreach($response as $key => $value)
		{
			if($key == 'search') continue;
			if($key == 'add') continue;
			if($key == 'p') continue;



			if($key == 'country_training')
			{
				if($value == 0)
				{
						// Лябая страна $str .= " AND city_id=0";
				}
				elseif(is_numeric($value) > 0 )
				{
					$response = SelectDataParent('city', 'page_id', $value);

					if(count($response) > 0)
					{
						for($y=0;$y<sizeof($response);$y++)
						{
							$idS .= $response[$y]->id.',';
						}

						$test = trim($idS, ",");
						$str .= " AND city_id IN ({$test})";


					}
					else
					{

						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = 'Отсутствует список городов';

					}
				}

			}

			if($key == 'country_training') continue;




			if($key == 'city')
			{
				if($value)
				{
					$response = $value;

					if(count($response) > 0)
					{
						for($y=0;$y<sizeof($response);$y++)
						{

							$id .= $response[$y].',';
						}

						$testS = trim($id, ",");
						$str .= " AND city_id IN ({$testS})";
					}
					else
					{

						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = 'Отсутствует список городов';

					}
				}

			}

			if($key == 'city') continue;



			if($key == 'company')
			{
				if($value ==! 0)
				{

					$response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE user_id='{$value}' ");


					for($y=0;$y<sizeof($response);$y++)
					{
						$id .= $response[$y]->id.',';
					}

						$test = trim($id, ",");

						if($test == !"")
						{
							$str .= " AND manager_id IN ({$test}) ";
						}
				}
			}



			if($key == 'company') continue;

			if($key == 'users')
			{
				$key = 'manager';
			}


			if($key == 'bought_id_filt')
			{
				$mass = $value;

				for($y=0;$y<sizeof($mass);$y++)
				{

					if($mass[$y] == 0)
					{
					}
					else
					{
						$str .= " AND {$mass[$y]} in(bought_id_0, bought_id_1, bought_id_2, bought_id_3, bought_id_4, bought_id_5, bought_id_7, bought_id_8, bought_id_9, bought_id_10) ";
					}
				}

			}




			if($value == 0) continue;


			if($key == 'intensive')
			{
				$current = 	date("Y-m-d");
				$day_30 =  	date("Y-m-d", strtotime('-30 days') );
				$day_60 =  	date("Y-m-d", strtotime('-60 days') );
				$day_90 =  	date("Y-m-d", strtotime('-90 days') );
				$day_120 =  date("Y-m-d", strtotime('-120 days') );


				switch ($value)
				{

					case 1:
					 $str .= "  AND (edate >= '{$day_30}') AND (edate <= '{$current}')  ";
					break;

					case 2:
					 $str .= "  AND (edate >= '{$day_60}') AND (edate <= '{$day_30}')  ";
					break;

					case 3:
					 $str .= "  AND (edate >= '{$day_90}') AND (edate <= '{$day_60}')  ";
					break;

					case 4:
					 $str .= "  AND (edate >= '{$day_120}') AND (edate <= '{$day_90}')  ";
					break;

					case 5:
					 $str .= "   AND (edate <= '{$day_120}')  ";
					break;

				}

			}
			if($key == 'intensive')continue;



			if($key == 'price')
			{
				if (is_numeric($value) > 0)
				{
					switch ($value)
					{
						case 1:
						 $str .= "  AND (price >= 0) AND (price <= 200000)  ";
						break;

						case 2:
						 $str .= "  AND (price >= 200000) AND (price <= 1000000)  ";
						break;

						case 3:
						 $str .= "  AND (price >= 1000000)  ";
						break;
					}
				}
				else
				{
					continue;
				}
			}
			if($key == 'price')continue;



			if($key == 'type_company')
			{
				if (is_numeric($value) > 0)
				{
					$str .= $this->ExplodeCat($value);
				}
				else
				{
					continue;
				}
			}
			if($key == 'type_company')continue;




			if($key == 'visible')
			{
				if ($value == 1)
				{
					$str .= ' AND visible=1 AND open = 0 ';
				}
				elseif ($value == 2)
				{
					$str .= ' AND visible=1 AND open = 3  ';
				}
				elseif($value == 3)
				{
					$str .= ' AND visible=1 AND open = 1 ';

				}
				elseif($value == 4)
				{
					$str .= ' AND visible=1 AND open = 2 ';

				}
				else
				{
					continue;
				}
			}
			if($key == 'visible')continue;





			if($key == 'cdate')continue;


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


	public function getCountExplode($where="")
	{
		global $CFG;
		$pageId *= 1;

		if($where == !"")
		{
			$Object_id = $this->ExplodeCat($where);

			if($Object_id == !"")
			{
				return 1 * getSQLField("SELECT COUNT(id) FROM {$CFG->DB_Prefix}news WHERE {$CFG->lang_where_and} page_id={$pageId} {$Object_id} AND visible='1' ORDER BY cdate DESC");
			}
		}
	}

	function uP($post)
	{

		global $CFG;

		$date = sqlDateNow();

		$name = $post['name'];

		$sql = "INSERT INTO {$CFG->DB_Prefix}users (login, pass, name, email, cdate, status, user_id) VALUES ('{$post['email']}', PASSWORD('{$post[passwd]}'),'{$name}', '{$post[email]}', '{$date}',  '{$post[status]}',  {$CFG->USER->USER_ID})";

		$CFG->DB->query($sql);

	}

	function newsColor($ecdata="", $cdata="")
	{
		//echo floor((strtotime("now")-strtotime("2015-07-20"))/86400);

		global $CFG;

		if(isset($ecdata))
		{
			$datetime1 = date_create(date("Y-m-d"));
			$datetime2 = date_create(dateSQL2TEXT($ecdata, "YYYY-MM-DD"));
			$interval = date_diff($datetime1, $datetime2);

			if($interval->days >= 0)
			{
				$str = " class='green' title='' ";
			}

			if($interval->days > 30)
			{
				$str = " class='yellow' title='' ";
			}

			if($interval->days >60)
			{
				$str = " class='blue' title='' ";
			}

			if($interval->days > 90)
			{
				$str = " class='red' title='' ";
			}

			if($interval->days > 120)
			{
				$str = " class='ice' title='' ";
			}
		}
		else
		{
			$datetime1 = date_create(date("Y-m-d"));
			$datetime2 = date_create(dateSQL2TEXT($cdata, "YYYY-MM-DD"));
			$interval = date_diff($datetime1, $datetime2);

			if($interval->days >= 0)
			{
				$str = " class='green' title='' ";
			}

			if($interval->days > 30)
			{
				$str = " class='yellow' title='' ";
			}

			if($interval->days >60)
			{
				$str = " class='blue' title='' ";
			}

			if($interval->days > 90)
			{
				$str = " class='red' title='' ";
			}

			if($interval->days > 120)
			{
				$str = " class='ice' title='' ";
			}

		}

		return $str;
	}


}


?>
