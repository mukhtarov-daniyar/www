<?

class News
{

	function getListCount($pageId, $count=0, $start=0, $where="", $limit, $search_word="", $and="")
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

		$value = apost($_GET["search"]);	$count = mb_strlen(trim($value), 'UTF-8');
		//$userId = $this->groupUser('users', $CFG->USER->USER_ID);
		//$vika = $this->vikaAbsen($CFG->USER->USER_ID);
		if(big_access('view_loader'))
		{
			$load = " AND manager_id = {$CFG->USER->USER_ID}";
		}

		if($count > 1)
		{
			$sql = "SELECT id FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word}  {$w1} {$where} {$userId} {$access} {$cookie} ";
			$res = getSQLArrayO($sql);
			$list = $this->comment_search($res, $value);

		 	$sql = getSQLRowO("SELECT count(id) FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word} {$w1} {$where} {$and} {$userId} {$access} {$list} {$cookie}  $w");

			return $sql->{'count(id)'};
		}
		else
		{
			 $sql = getSQLRowO("SELECT count(id) FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word}  {$w1}  {$where} {$and} {$cookie} $w");
			 return $sql->{'count(id)'};
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

		$value = apost($_GET["search"]);	$count = mb_strlen(trim($value), 'UTF-8');
		//$userId = $this->groupUser('users', $CFG->USER->USER_ID);
		//$vika = $this->vikaAbsen($CFG->USER->USER_ID);
		if(big_access('view_loader'))
		{
			$load = " AND manager_id = {$CFG->USER->USER_ID}";
		}

		if($count > 1)
		{
			$sql = "SELECT id FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word}  {$w1} {$where} {$userId} {$access} {$cookie} ";
			$res = getSQLArrayO($sql);
			$list = $this->comment_search($res, $value);

			$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word} {$w1} {$where} {$and} {$userId} {$access} {$list} {$cookie}  $w";
			$l = getSQLArrayO($sql);
		}
		else
		{
			 $sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE visible='1' {$load} {$search_word}  {$w1}  {$where} {$and} {$cookie} $w";
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

		$res_comments = getSQLArrayO("SELECT id, page_id, user_id FROM my_comments WHERE visible='1' AND parent_id = '868' {$final_news} AND MATCH(text) AGAINST('+" . $value . "' IN BOOLEAN MODE); ");
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




	function deteleComent($id, $date, $autorId, $type_company_id = '')
	{
		global $CFG;

		$data = SelectDataRowOArray('comments', $id);
		$user = SelectDataRowOArray('users', $data->user_id, 0);

		if(($CFG->USER->USER_ID == 85) or ($CFG->USER->USER_ID == 351) or ($CFG->USER->USER_ID == 1)  or ($user->user_id == $CFG->USER->USER_ID) )
		{
			$url = "<a href='#' id='{$id}' class='DeteleComent'>".$CFG->Locale["del"]."</a>";
		}
		elseif($type_company_id == 555 && $autorId == $CFG->USER->USER_ID)
		{
			$url = "<a href='#' id='{$id}' class='DeteleComent'>".$CFG->Locale["del"]."</a>";
		}
		else
		{
			if($autorId == $CFG->USER->USER_ID)
			{
				$date = strtotime($date);

				if($date + 86400 > time())
				{
					$url = "<a href='#' id='{$id}' class='DeteleComent'>".$CFG->Locale["del"]."</a>";
				}
			}
		}

		return $url;
	}


		function getUp($id, $type)
		{
				global $CFG;
				$res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news_history WHERE visible='1' AND page_id = '{$id}' order by id DESC limit 5");
				foreach ($res as $value)
				{
						if($CFG->_GET_PARAMS[2] == $value->id) {$add = ' plus';} else {$add = '';}

						?> <a class="history_vs<?=$add;?>" data-id="<?=$value->id;?>" data-page_id="<?=$value->page_id;?>" href="/record/<?=$value->page_id;?>/history/<?=$value->id;?>"><? echo date('d/m/Y H:i', $value->times); ?> - <? echo unserialize($value->data)->name_company; ?></a> 	<?
				}
		}


		function printData($type, $types, $name)
		{
				switch ($name)
				{
				    case 'img':
							if($type != $types) {$respons = '<div class="data"><img src="'.$type.'"></div>';}
				    break;

						case 'manager':
							$res = getSQLRowO("SELECT user_id, name FROM my_users WHERE id={$type}");
							if($type != $types) {$respons = '<div class="data"><strong>Автор</strong>: <a href="/profile/view/'.$res->id.'" target="_blank">'.$res->name.'</a></div>';}
				    break;

						case 'bcdate':
							if($type == 0) break;
							if($type != $types) {$respons = '<div class="data"><strong>День рождения</strong>: '.date('d/m/Y', $type).'</div>';}
				    break;

						case 'marketing':

							if($type == '') break;

							$res = getSQLArrayO("SELECT * FROM my_type_company_portrait WHERE id IN ({$type})");

							foreach ($res as $value)
							{
									$val .= '<span class="name">'.$value->name.'</span>';
							}

							if($type != $types) {$respons = '<div class="data"><strong>Маркетинг план</strong>: '.$val.' </div>';}
				    break;

				    default:
							if($type != $types) {$respons = '<div class="data"><strong>'.$name.'</strong>: '.$type.'</div>';}
				    break;
				}

				return $respons;
		}

		function faceUp($id, $data, $type)
		{
				global $CFG;

				$res = serialize($data);
				$time = time();

				$sql = "INSERT INTO {$CFG->DB_Prefix}news_history (page_id, type, data, times, user_id, visible) VALUES ({$id}, '{$type}', '{$res}', {$time}, {$CFG->USER->USER_ID}, 1)";
				if($CFG->DB->query($sql))
					return true;
		}

	public function vikaAbsen($user)
	{
		global $CFG;

		if($user == 191)
		{
			$name = 'защитаабсен';
			$name1 = 'эксплуатацияа';

			$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE name_company LIKE '%{$name}%' OR name_company LIKE '%{$name1}%' ";
			$l = getSQLArrayO($sql);

			for ($x=0; $x<sizeof($l); $x++)
			{
				$news_id .= $l[$x]->id.',';
			}

			$news_and_id = trim($news_id, ",");

			if($news_and_id !== "")
			{
				$final_news = " AND id IN ({$news_and_id}) ";
			}
			else
			{
				$final_news = "";
			}
		}
		if($user == 220)
		{
			$name = 'эксплуатацияа';

			$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE name_company LIKE '%{$name}%'";
			$l = getSQLArrayO($sql);

			for ($x=0; $x<sizeof($l); $x++)
			{
				$news_id .= $l[$x]->id.',';
			}

			$news_and_id = trim($news_id, ",");

			if($news_and_id !== "")
			{
				$final_news = " AND id IN ({$news_and_id}) ";
			}
			else
			{
				$final_news = "";
			}
		}

		return $final_news;
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
				$final .= " AND manager_id IN ({$andid}{$AccessCompany})";

				if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 351 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 488)
				{
					$final = " ";
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



	function getObject($pageId, $id=0)
	{
		global $CFG;
		$pageId *= 1;
		$id *= 1;
		$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$id}' AND  page_id={$pageId}";
		$o = getSQLRowO($sql);


		if(count($o) > 0)
		{
			return $o;
		}
		else
		{
			redirect("/error/");
		}

	}


	function insertResponse($id, $page_id, $job_user_id, $user, $response, $url)
	{
		global $CFG;

		$text = ($response);


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
			$sql = "INSERT INTO {$CFG->DB_Prefix}response (vacancy_id, page_id, job_user_id, user_id, body, cdate, visible, sys_language) VALUES ({$id}, {$page_id}, {$job_user_id}, {$user}, '{$text}', '{$cdata}', 0, '{$CFG->SYS_LANG}')";
			$CFG->DB->query($sql);


			$CFG->STATUS->ERROR = true;
			$CFG->STATUS->MESSAGE = 'Спасибо! Ваш отклик будет рассмотрен в ближайшее время.';

			redirect($redirect);
		}



	}


	function putCount($id, $count=0) {

		global $CFG;

		$count++;
		$query  = "UPDATE {$CFG->DB_Prefix}news SET view='{$count}' WHERE id='{$id}'";
		$CFG->DB->query($query);

	}



	function newsUpdata($id, $post=0) {

		global $CFG;

		if($post["on"])
		{
			$count = 1;

			$CFG->STATUS->ERROR = true;
			$CFG->STATUS->MESSAGE = 'Вакансия опубликована.';
			$redirect = '/'.$CFG->SYS_LANG_NAME.'/vacancy/';
		}
		elseif($post["off"])
		{
			$count = 2;

			$CFG->STATUS->ERROR = true;
			$CFG->STATUS->MESSAGE = 'Вакансия отклонена.';
			$redirect = '/'.$CFG->SYS_LANG_NAME.'/vacancy/';

		}

		$query  = "UPDATE {$CFG->DB_Prefix}news SET visible='{$count}' WHERE id='{$id}'";
		$CFG->DB->query($query);

		redirect($redirect);

	}



	function getData($response)
	{
		global $CFG;

		foreach($response as $key => $value)
		{

			$value = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $value);

			if($key == 'search') continue;
			if($key == 'add') continue;
			if($key == 'p') continue;
			if($key == 'ajax_pids') continue;

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
					if(count($value) > 0)
					{

						$str .= " AND city_id IN ({$value})";
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


			if($key == 'type_company')
			{
				if (isset($value) && $value != 0)
				{
					$str .= $this->ExplodeCat($value);
				}
				else
				{
					continue;
				}
			}
			if($key == 'type_company')continue;


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

		foreach ($catId as $arr)
		{
			if($arr == 0) continue;

			$sql = getSQLArrayO("select id from my_news where find_in_set('{$arr}',type_company_id) <> 0 AND page_id = 868 AND visible='1' ");
			for ($x=0; $x<sizeof($sql); $x++)
			{
				$id .= $sql[$x]->id.',';
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

	function updateArrayId($data, $type, $message = "")
	{

		global $CFG;

		if($CFG->USER->USER_ID >= 1)
		{
			$director_mail = SelectDataRowOArray('users', $CFG->USER->USER_DIRECTOR_ID, 0);
			if($type == 1)
			{
				for ($z=0; $z<sizeof($data); $z++)
				{
					$respons = SelectDataRowOArray('news', $data[$z]);

					if($respons->type_company_id)
					{
						$keys = explode(',', $respons->type_company_id);

						if (in_array(6, $keys)) {

						}
						else
						{
							$email[] .= $respons->data_email;
						}
					}
				}

				if(count($email) > 0)
				{
					for ($p=0; $p<sizeof($email); $p++)
				{
					if($email[$p] == !"")
					{
						$record_mail = unserialize($email[$p]);

						for ($u=0; $u<sizeof($record_mail); $u++)
						{
							if($record_mail[$u] == '') continue;
							{
								$listemail .=$record_mail[$u].', ';
							}
						}


					}
				}

				$ars = explode(", ", $listemail);
				$result = array_unique($ars);

				for($y=0; $y<sizeof($result); $y++)
				{
					if($result[$y] == '') continue;
					{
						$listemails .= $result[$y].', ';
					}
				}

				echo trim($listemails, ", ");

				exit;



				$filename = 'Email-user_id='.$CFG->USER->USER_ID.'-date='.date("d.m_H").".txt";

				$fp = fopen ($filename, "w");
				fwrite($fp, $listemail);
				fclose($fp);

				if($CFG->USER->USER_ID == 1)
					{
						$senademail = $CFG->USER->USER_EMAIL;
					}
				elseif($CFG->USER->USER_ID == 309)
					{
						$senademail = $CFG->USER->USER_EMAIL;
					}
				else
					{
						$senademail = $director_mail->email;
					}

				$name = $filename; // в этой переменной надо сформировать имя файла (без всякого пути)
				$EOL = "\r\n"; // ограничитель строк, некоторые почтовые сервера требуют \n - подобрать опытным путём
				$boundary     = "--".md5(uniqid(time()));  // любая строка, которой не будет ниже в потоке данных.

				$headers    = "MIME-Version: 1.0;$EOL";
				$headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
				$headers   .= "From: Информационная «База клиентов» <support@led.ru>";

				$multipart  = "--$boundary$EOL";
				$multipart .= "Content-Type: text/html; charset=windows-1251$EOL";
				$multipart .= "Content-Transfer-Encoding: base64$EOL";
				$multipart .= $EOL; // раздел между заголовками и телом html-части
				$multipart .= chunk_split(base64_encode($html));

				$multipart .=  "$EOL--$boundary$EOL";
				$multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";
				$multipart .= "Content-Transfer-Encoding: base64$EOL";
				$multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";
				$multipart .= $EOL; // раздел между заголовками и телом прикрепленного файла
				$multipart .= chunk_split(base64_encode($listemail));

				$multipart .= "$EOL--$boundary--$EOL";

				if(mail($senademail, "Сформированые данные. Список E-mail", $multipart, $headers))
				{
					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'Список сформированных E-mail отправлен на почту руководителю компании.';
				}
				else
				{
					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'В процессе произошла ошибка';
				}
				unlink($filename);
				}
				else
				{
					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'Список E-mail пуст.';
				}
			}



			if($type == 2)
			{
				for ($z=0; $z<sizeof($data); $z++)
				{
					$respons = SelectDataRowOArray('news', $data[$z]);
					if($respons->client_id == '') continue;
					$sql = getSQLArrayO("SELECT email FROM my_face WHERE visible=1 AND id IN({$respons->client_id}) ");

					 for ($t=0; $t<sizeof($sql); $t++)
					 {
					 	if($sql[$t]->email == '') continue;
					 	$email .= $sql[$t]->email.',';
					 }
				}
				$email = trim($email, ',');
				$name = date('d_m_Y_H_i')."-email.txt";
				$file = fopen($name,"wb");
				fwrite($file);
				fclose($file);
				header('Content-Type: charset=utf-8');
				header("Content-disposition: attachment; filename=$name");
				print $email;

				unlink($name);
				exit;
			}

			if($type == 3)
			{
				set_time_limit(3600);

				for ($x=0; $x<sizeof($data); $x++)
				{
					$date = sqlDateNow();
					$id = $data[$x];
					$text = $CFG->DB->filter(strip_tags(addslashes($message)));
					/* Обновляем дату редактирования в записи*/
					$query  = "UPDATE {$CFG->DB_Prefix}news SET edate='{$date}' WHERE id='{$id}'";
					if($CFG->DB->query($query))
					{
						/* Создаем в записи заметку*/
						$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, wholesale, cdate) VALUES ('{$id}', '868', '{$CFG->USER->USER_ID}', '{$text}', 1, '{$date}')";
						$CFG->DB->query($sql);
						$status = "";
					}
					else
					{
						$status = json_encode(array('status' => 1, 'text' => "Произошла ошибка. Слижком большое количество записей."));
						exit;
					}
				}

				$status = json_encode(array('status' => 0, 'text' => "Заметка для ".count($data)." записей создана."));
				echo $status; exit;
			}


			if($type == 4)
			{

				for ($z=0; $z<sizeof($data); $z++)
				{
					$respons = SelectDataRowOArray('news', $data[$z]);


					if($respons->name_director_mobile[$p])
					{

						if($respons->name_director_mobile)
							{
								$director = preg_replace('~[^0-9]+~','',$respons->name_director_mobile);
								$name_company = preg_replace('/[^\p{L}0-9 ]/iu','',$respons->name_company);
							}
							else
							{
								$director = '';
								$name_company = '';
							}

						$record_mail .= $name_company.': +'.$director.PHP_EOL;
					}


					if($respons->name_client_mobile[$p])
					{

						if($respons->name_client_mobile)
							{
								$client = preg_replace('~[^0-9]+~','',$respons->name_client_mobile);
								$name = preg_replace('/[^\p{L}0-9 ]/iu','',$respons->name_company);
							}
							else
							{
								$client = '';
								$name = '';
							}

						$record_mail .= $name.': +'.$client.PHP_EOL;
					}
				}


				$filename = 'Tel-user_id='.$CFG->USER->USER_ID.'-date='.date("d.m.Y_H.i").".txt";


					$fp = fopen ($filename, "w");
					fwrite($fp, $record_mail);
					fclose($fp);

					if($CFG->USER->USER_ID == 1)
						{
							$senademail = $CFG->USER->USER_EMAIL;
						}
					elseif($CFG->USER->USER_ID == 309)
						{
							$senademail = $CFG->USER->USER_EMAIL;
						}
					else
						{
							$senademail = $director_mail->email;
						}

					$name = $filename; // в этой переменной надо сформировать имя файла (без всякого пути)
					$EOL = "\r\n"; // ограничитель строк, некоторые почтовые сервера требуют \n - подобрать опытным путём
					$boundary     = "--".md5(uniqid(time()));  // любая строка, которой не будет ниже в потоке данных.
					$headers    = "MIME-Version: 1.0;$EOL";
					$headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
					$headers   .= "From: Информационная «База клиентов» <support@led.ru>";

					$multipart  = "--$boundary$EOL";
					$multipart .= "Content-Type: text/html; charset=windows-1251$EOL";
					$multipart .= "Content-Transfer-Encoding: base64$EOL";
					$multipart .= $EOL; // раздел между заголовками и телом html-части
					$multipart .= chunk_split(base64_encode($html));

					$multipart .=  "$EOL--$boundary$EOL";
					$multipart .= "Content-Transfer-Encoding: base64$EOL";
					$multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";
					$multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";
					$multipart .= $EOL; // раздел между заголовками и телом прикрепленного файла
					$multipart .= chunk_split(base64_encode($record_mail));

					$multipart .= "$EOL--$boundary--$EOL";


					if(mail($senademail, "Сформированые данные. Список телефонов", $multipart, $headers))
					{
						$CFG->STATUS->ERROR = true;
						$CFG->STATUS->MESSAGE = 'Список сформированных телефонов отправлен на почту руководителю компании.';
					}
					else
					{
						$CFG->STATUS->ERROR = true;
						$CFG->STATUS->MESSAGE = 'В процессе произошла ошибка';
					}

					unlink($filename);

			}



			if($type == 5)
			{
				echo '<table class="price" border="1" style="font-size:12px;">
				  <tr>
					<th><p>Номер записи</p></th>
					<th><p>Название компании</p></th>
					<th><p>Город</p></th>
					<th><p>ФИО</p></th>
					<th><p>Телефон</p></th>
					<th><p>Whatsapp</p></th>
					<th><p>Email</p></th>
				  </tr>';

					for ($z=0; $z<sizeof($data); $z++)
					{
						$respons = SelectDataRowOArray('news', $data[$z]);	if($respons->client_id == '') continue;	?>
	            <tr>
	              <td><p>*<?=$respons->id;?></p></td>
	              <td><p><? echo $respons->name_company;?></p></td>
	              <td><p><? echo SelectData("city", $respons->city_id);?></p></td>
	              <?	$sql = getSQLArrayO("SELECT name,mobile,whatsapp,email FROM my_face WHERE visible=1 AND id IN({$respons->client_id}) "); ?>
	              <td><p><? for ($y=0; $y<sizeof($sql); $y++){ echo $sql[$y]->name.'<br>'; }?></p></td>
	              <td><p><? for ($x=0; $x<sizeof($sql); $x++){ echo $sql[$x]->mobile.'<br>'; }?></p></td>
	              <td><p><? for ($n=0; $n<sizeof($sql); $n++){ echo $sql[$n]->whatsapp.'<br>'; }?></p></td>
	              <td><p><? for ($t=0; $t<sizeof($sql); $t++){ echo $sql[$t]->email.'<br>'; }?></p></td>
	            </tr>
					<?
				}
				echo '</table>';

				?>
                <!--script>window.print() ;</script!-->
        <?

				exit;
			}

			if($type == 6)
			{

				for ($z=0; $z<sizeof($data); $z++)
				{
					$record_id.= $data[$z].',';
				}

				$record_id = trim($record_id, ',');

				$sql = getSQLArrayO("SELECT id, client_id FROM my_news WHERE visible=1 AND page_id = 868 AND id IN({$record_id}) ");

				for ($x=0; $x<sizeof($sql); $x++)
				{
					if($sql[$x]->client_id == '') continue;

					$face_id = explode(",", $sql[$x]->client_id);

					for ($n=0; $n<sizeof($face_id); $n++)
					{
						$fill_id.= $face_id[$n].',';
					}

					$fill_id = trim($fill_id, ',');
					$res = getSQLArrayO("SELECT name, mobile, whatsapp FROM my_face WHERE visible=1 AND id IN({$fill_id}) ");

					for ($p=0; $p<sizeof($res); $p++)
					{
						$res[$p]->name = $res[$p]->name.' *'.$sql[$x]->id;
						if($res[$p]->mobile == '') continue;
$tel .= 'BEGIN:VCARD
';
$tel .= 'VERSION:3.0
';
$tel .= 'N:;'.$res[$p]->name.';;;
';
$tel .= 'FN:'.$res[$p]->name.'
';
$tel .= 'TEL;TYPE=CELL_0:+'.preg_replace('~[^0-9]+~','',$res[$p]->mobile).'
';
if($res[$p]->whatsapp != '') {$tel .= 'TEL;TYPE=WP_1:+'.preg_replace('~[^0-9]+~','',$res[$p]->whatsapp).'
';}
$tel .= 'END:VCARD

';
					}

					$fill_id = '';
				}

				$name = date('d_m_Y_H_i').".VCF";
				$file = fopen($name,"wb");
				fwrite($file);
				fclose($file);
				header('Content-Type: charset=utf-8');
				header("Content-disposition: attachment; filename=$name");
				print $tel;

				unlink($name);
				exit;

			}


			if($type == 7)
			{

				for ($z=0; $z<sizeof($data); $z++)
				{
					$record_id.= $data[$z].',';
				}

				$record_id = trim($record_id, ',');

				$sql = getSQLArrayO("SELECT id, client_id FROM my_news WHERE visible=1 AND page_id = 868 AND id IN({$record_id}) ");

				for ($x=0; $x<sizeof($sql); $x++)
				{
					if($sql[$x]->client_id == '') continue;

					$face_id = explode(",", $sql[$x]->client_id);

					for ($n=0; $n<sizeof($face_id); $n++)
					{
						$fill_id.= $face_id[$n].',';
					}

					$fill_id = trim($fill_id, ',');
					$res = getSQLArrayO("SELECT name, mobile, whatsapp FROM my_face WHERE visible=1 AND id IN({$fill_id}) ");

					for ($p=0; $p<sizeof($res); $p++)
					{
						$res[$p]->name = $res[$p]->name.' *'.$sql[$x]->id;
						if($res[$p]->mobile == '') continue;
						$tel .= '+'.preg_replace('~[^0-9]+~','', $res[$p]->mobile).',';
					}

					$fill_id = '';
				}

				$name = date('d_m_Y_H_i').".txt";
				$file = fopen($name,"wb");
				fwrite($file);
				fclose($file);
				header('Content-Type: charset=utf-8');
				header("Content-disposition: attachment; filename=$name");
				print $tel;

				unlink($name);
				exit;

			}


			if($type == 8)
			{
				$get_id = $_GET['ajax_pids'];
				$res = getSQLRowO("SELECT id, type FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE id='{$get_id}' ");
				$pid = unserialize($res->type);

				for ($z=0; $z<sizeof($data); $z++)
				{
					$record_id.= $data[$z].',';
				}
				$record_id = trim($record_id, ',');

				$sql = getSQLArrayO("SELECT id, client_id FROM my_news WHERE visible=1 AND page_id = 868 AND id IN({$record_id}) ");
				for ($x=0; $x<sizeof($sql); $x++)
				{
					if($sql[$x]->client_id == '') continue;

					$face_id = explode(",", $sql[$x]->client_id);

					for ($n=0; $n<sizeof($face_id); $n++)
					{
						$fill_id []= $face_id[$n];
						$fill_idS.= $face_id[$n].',';
					}
				}
				$fill_idS = trim($fill_idS, ',');
				$res = getSQLArrayO("SELECT whatsapp,id FROM my_face WHERE visible=1 AND id IN({$fill_idS}) ");

				for ($p=0; $p<sizeof($res); $p++)
				{
					if($res[$p]->whatsapp == '') continue;
						$nextWeek = time();
						$up .= "('1012', '".$get_id."',  '".$nextWeek."',  '".$res[$p]->id."'),".PHP_EOL;
				}

				if($up != '')
				{
					$up = substr($up, 0, -2);
					$CFG->DB->query("INSERT INTO my_tmp_whatsapp_rss (page_id, whatsapp_id, times, face_id) VALUES {$up} ON DUPLICATE KEY UPDATE page_id = VALUES(page_id), whatsapp_id = VALUES(whatsapp_id), times = VALUES(times), face_id = VALUES(face_id); ");
				}

				$result = array_merge($pid, $fill_id);
				$type = serialize($result);
				$query  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp SET type='{$type}' WHERE id='{$get_id}'";
				$CFG->DB->query($query);

				echo 1;
				exit;
			}


			if($type == 10)
			{
				$count = count($data);
				$type = serialize($data);
				$date = sqlDateNow();

				$sql = "INSERT INTO my_tmp_whatsapp (page_id, user_id, count, type, cdate, status, visible) VALUES ('{$CFG->oPageInfo->id}','{$CFG->USER->USER_ID}', '{$count}', '{$type}', '{$date}', 0, 0)";
				$CFG->DB->query($sql);
				$i_id = $CFG->DB->lastId();

				$res = getSQLRowO("SELECT id, namber, type FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE id={$i_id} ");

				$types = unserialize($res->type);

				for($y=0;$y<sizeof($types);$y++)
				{
					$list.=$types[$y].',';
				}

				$big = trim($list, ',');

				$client_id = getSQLArrayO("SELECT client_id FROM my_news WHERE visible=1 AND id IN({$big}) ");

				for ($u=0; $u<sizeof($client_id); $u++)
				{
					if($client_id[$u]->client_id == '') continue;
					$client_id_win.= $client_id[$u]->client_id.',';
				}
				$bis = trim($client_id_win, ',');

				$types = getSQLArrayO("SELECT id,whatsapp FROM my_face WHERE visible=1 AND id IN({$bis}) ");
				for($y=0;$y<sizeof($types);$y++)
				{
					if($types[$y]->whatsapp == '') continue;
					$tel[] = array( 'id' => $types[$y]->id	);
				}

				$time = time();

				for($x=0;$x<sizeof($tel);$x++)
				{
  				$up .= "('".$CFG->oPageInfo->id."', '".$res->id."',  '".$time."',  '".$tel[$x]['id']."'),".PHP_EOL;
				}

				if($up != '')
				{
					$up = substr($up, 0, -2);
			    $CFG->DB->query("INSERT INTO my_tmp_whatsapp_rss (page_id, whatsapp_id, times, face_id) VALUES {$up} ON DUPLICATE KEY UPDATE page_id = VALUES(page_id), whatsapp_id = VALUES(whatsapp_id), times = VALUES(times), face_id = VALUES(face_id); ");
				}

				redirect('/whatsapp_new/'. $res->id);
				exit;
			}


		}
	}

	function uP($post)
	{

		global $CFG;

		$date = sqlDateNow();

		$name = $post['name'];

		$password_hash = password_hash($post['passwd'], PASSWORD_DEFAULT);

		$sql = "INSERT INTO {$CFG->DB_Prefix}users (pass, name, mobile, cdate, user_id, access) VALUES ('{$password_hash}','{$name}', '{$post[mobile]}', '{$date}', {$CFG->USER->USER_DIRECTOR_ID},  {$CFG->USER->USER_DIRECTOR_ID})";

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


	function getPrice($pageId)
	{
		global $CFG;

		$sql = getSQLArrayO("SELECT text FROM {$CFG->DB_Prefix}comments WHERE page_id='{$pageId}' and visible= 1 ");

		for($y=0;$y<sizeof($sql);$y++)
		{
			$test.= heshtag_list($sql[$y]->text);
		}

		$idS = explode("##", $test);

		$inventory = array_values( $idS );

		for ($i=0; $i<sizeof($inventory); $i++)
		{
			if($inventory[$i] == '') continue;
			$price[] = $inventory[$i].'<br>';
		}

		return array_sum($price);
	}

	function getPrice_1($pageId, $type=1)
	{
		global $CFG;

		$sql = getSQLArrayO("SELECT text FROM {$CFG->DB_Prefix}comments WHERE page_id='{$pageId}' and visible= 1 ");

		for($y=0;$y<sizeof($sql);$y++)
		{
			 $test.= $sql[$y]->text.' ';
		}

		preg_match_all("/(#{$type}#\w+)/u", $test, $matches);


		if ($matches[0])
		{

			$hashtags = $matches[0];

			foreach ( $hashtags as $value )
			{

				$arr->item = $value;

				$ass.= mb_strtolower($arr->item).' ';
			}

			$idS = explode("#{$type}#", $ass);



			$inventory = array_values( $idS );

			for ($i=0; $i<sizeof($inventory); $i++)
			{
				if($inventory[$i] == '') continue;
				$price[] = $inventory[$i].'<br>';
			}

			return array_sum($price);
		}




                // print_r(123);
	}
}
