<?

class Face
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

		//$userId = $this->groupUser('users', $CFG->USER->USER_ID);

		$sql =  getSQLRowO("SELECT COUNT(id) as id FROM {$CFG->DB_Prefix}face WHERE visible='1' {$load} {$search_word} {$w1}  {$where} {$and} {$userId} {$cookie}  $w");

		return $sql->id;
	}

	function logo($url, $id)
	{
		global $CFG;

		if($url)
		{
			$path = 'documents/tmp/' . $id . '.'.strtolower(pathinfo($url, PATHINFO_EXTENSION));

			if (!file_exists($path))
			{
				try
				{
					$img = AcImage::createImage($_SERVER['DOCUMENT_ROOT'].$url);
							 AcImage::setRewrite(true);
							 AcImage::setQuality(70);
					$img->cropCenter('5pr', '5pr')
						->resize(54, 54)
						->save($path);
				}
				catch (FileNotFoundException $ex)
				{
				}

				echo '<img src="/'.$path.'">';
			}
			else
			{
				echo '<img src="/'.$path.'">';
			}
		}
		else
		{
			echo '<img src="/tpl/img/new/avatra.png">';
		}
	}

	function getList($pageId, $count=0, $start=0, $where="", $limit, $search_word="", $and="")
	{
		global $CFG;

		$count *= 1;
		$start *= 1;
		$pageId *= 1;

		$w1 = ($pageId > 0 ? "AND my_face.page_id={$pageId}  " : "");

		if ($count > 0)
			$w = "LIMIT {$start}, {$count}";

		if ($where != "")
			$where = "{$where}";

		if($_COOKIE["order_my_face"])
			$cookie = $_COOKIE["order_my_face"];
		else
			$cookie = "ORDER BY my_face.cdate DESC";

		//$userId = $this->groupUser('users', $CFG->USER->USER_ID);

		//$sql = "SELECT * FROM {$CFG->DB_Prefix}face WHERE visible='1' {$and}  {$w1} {$where} {$search_word} {$cookie} $w";
		$sql = "SELECT my_users.name AS name_user, my_face.* FROM my_face LEFT JOIN my_users ON my_users.id=my_face.manager_id WHERE my_face.visible='1' {$and}  {$w1} {$where} {$search_word} {$cookie} $w";

		$res = getSQLArrayO($sql);

		return $res;
	}

	function getAccess($manager_id)
	{
		global $CFG;

		$sql = getSQLRowO("SELECT user_id FROM {$CFG->DB_Prefix}users WHERE id='{$manager_id}' ");

		if($sql->user_id == $CFG->USER->USER_DIRECTOR_ID )
			return true;
		else
			return false;
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

	function getUp($id, $type)
	{
			global $CFG;
			$res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news_history WHERE visible='1' AND page_id = '{$id}' order by id DESC limit 5");
			foreach ($res as $value)
			{
					if($CFG->_GET_PARAMS[2] == $value->id) {$add = ' plus';} else {$add = '';}

					?> <a class="history_vs<?=$add;?>" data-id="<?=$value->id;?>" data-page_id="<?=$value->page_id;?>" href="/person/<?=$value->page_id;?>/history/<?=$value->id;?>"><? echo date('d/m/Y H:i', $value->times); ?> - <? echo unserialize($value->data)->name; ?></a> 	<?
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


		$res_comments = getSQLArrayO("SELECT id, page_id, user_id FROM {$CFG->DB_Prefix}comments WHERE visible='1' {$final_news} AND parent_id = '976' AND text LIKE '%{$value}%' ");

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
		$sql = "SELECT * FROM {$CFG->DB_Prefix}face WHERE id='{$id}' AND  page_id={$pageId}";
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


	function putCount($id, $count=0)
	{
		global $CFG;
		$count++;
		$query  = "UPDATE {$CFG->DB_Prefix}face SET view='{$count}' WHERE id='{$id}'";
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
							$str .= " AND my_face.manager_id IN ({$test}) ";
						}
				}
			}
			if($key == 'company') continue;



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
						$str .= " AND my_face.city_id IN ({$test})";
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

						$str .= " AND my_face.city_id IN ({$value})";
					}
					else
					{
						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = 'Отсутствует список городов';
					}
				}

			}
			if($key == 'city') continue;


			if($key == 'users')
			{
				$key = 'my_face.manager';
			}


			if($value == 0) continue;



			if($key == 'marketing')
			{
				if (isset($value) && $value != 0)
				{
					$str .= $this->ExplodeCatPortrait($value);
				}
				else
				{
					continue;
				}
			}
			if($key == 'marketing')continue;


			if($key == 'skidka_led')
			{
				if ($value > 0)
				{
					$str .=  ' AND my_face.'.$key.' = '.$value.' ';
				}
				else
				{
					continue;
				}
			}
			if($key == 'skidka_led')continue;


			if($key == 'floor')
			{
				if ($value > 0)
				{
					$str .=  ' AND my_face.'.$key.' = '.$value.' ';
				}
				else
				{
					continue;
				}
			}
			if($key == 'floor')continue;

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

	public function ExplodeCatPortrait($catId)
	{
		global $CFG;

		foreach ($catId as $arr)
		{
			if($arr == 0) continue;

			$sql = getSQLArrayO("select id from my_face where find_in_set('{$arr}',marketing_id) <> 0 AND page_id = 1012 AND visible='1' ");
			for ($x=0; $x<sizeof($sql); $x++)
			{
				$id .= $sql[$x]->id.',';
			}

		}

		$idS = trim($id, ",");

		if($idS == !"")
			$newsIdAnd .= " AND my_face.id in({$idS}) ";


		return $newsIdAnd;
	}

	public function ExplodeCat($catId)
	{
		global $CFG;

		$sql = getSQLArrayO("SELECT type_company_id, id FROM {$CFG->DB_Prefix}face WHERE visible='1' ORDER BY cdate DESC");

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
				return 1 * getSQLField("SELECT COUNT(id) FROM {$CFG->DB_Prefix}face WHERE {$CFG->lang_where_and} page_id={$pageId} {$Object_id} AND visible='1' ORDER BY cdate DESC");
			}
		}
	}

	function updateArrayId($data, $type)
	{
		global $CFG;
		set_time_limit(600);

		if($CFG->USER->USER_ID >= 1 && $type == 1)
		{
				for ($y=0; $y<sizeof($data); $y++)
				{
					$respons[$y].= $data[$y]->id;
				}

				$type = serialize($respons);
				$date = sqlDateNow();
				$time = time();

				$sql = "INSERT INTO {$CFG->DB_Prefix}tmp_whatsapp (user_id, page_id, type, cdate, status, visible) VALUES ('{$CFG->USER->USER_ID}', '{$CFG->oPageInfo->id}', '{$type}', '{$date}', 0, 0)";
				$CFG->DB->query($sql);

				$res = getSQLRowO("SELECT id, namber, type FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE visible='0' ORDER BY id DESC");

				$types = unserialize($res->type);

				for ($u=0; $u<sizeof($types); $u++)
				{
					$client_id_win.= $types[$u].',';
				}
				$bis = trim($client_id_win, ',');

				$types = getSQLArrayO("SELECT id,whatsapp FROM my_face WHERE visible=1 AND id IN({$bis}) ");
				for($y=0;$y<sizeof($types);$y++)
				{
					if($types[$y]->whatsapp == '') continue;
					$tel[] = array( 'id' => $types[$y]->id	);
				}

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




	function updateList($data, $type)
	{
		global $CFG;

		set_time_limit(100);

		if($CFG->USER->USER_ID >= 1 && $type == 2)
		{


			for ($y=0; $y<sizeof($data); $y++)
			{

				if($data[$y]->mobile == '' && $data[$y]->international == '') continue;

			if($data[$y]->name == ""){$name = $data[$y]->mobile; }else {$name = $data[$y]->name;}

	$name_other = explode(" ", $data[$y]->name_other);

if($name_other[1] != '') {$n_o = ' '.$name_other[1];} else { $n_o = '';}

$tel .= 'BEGIN:VCARD
';
$tel .= 'VERSION:3.0
';
$tel .= 'N:;'.$name.$n_o.';;;
';
$tel .= 'FN:'.$name.$n_o.'
';
if($data[$y]->mobile != '') {$client = preg_replace('~[^0-9]+~','',$data[$y]->mobile);
$tel .= 'TEL;:+'.$client.'
'; }
if($data[$y]->international != '') { $client = preg_replace('~[^0-9]+~','',$data[$y]->international);
$tel .= 'TEL;:+'.$client.'
'; }
$tel .= 'NOTE;:'.$data[$y]->name_other.'
';
$tel .= 'NOTE;:'.str_replace(array("\r\n", "\r", "\n"), ' ', $data[$y]->info).'
';

if($data[$y]->marketing_id != '')
{
	$res_comments = getSQLArrayO("SELECT name FROM my_type_company_portrait WHERE visible='1' AND id IN ({$data[$y]->marketing_id}) ");
	foreach($res_comments as $res)
	{
		$arr  .= $res->name.'\n';
	}
}

$tel .= 'NOTE;:'.$arr.'
';
$tel .= 'END:VCARD
';
$arr = '';
			}

			$content = $tel;

			$name = date('d_m_Y_H_i').".VCF";
			$file = fopen($name,"wb");
			fwrite($file);
			fclose($file);
			header('Content-Type: charset=utf-8');
			header("Content-disposition: attachment; filename=$name");
			print $content;
			unlink($name);
			exit;
		}
		if($type == 3)
		{
			for ($y=0; $y<sizeof($data); $y++)
			{
				if($data[$y]->mobile == '') continue;
				$string .= '+'.preg_replace('~[^0-9]+~','',$data[$y]->mobile).',';
			}
			echo trim($string, ",");
			exit;
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

	function FaceToFace($t_l=0, $s_l=0)
	{
			global $CFG;
			if($t_l > 0 && $s_l > 0)
			{

					$sql = "INSERT INTO my_face_to_face (page_id, parent_id) VALUES ({$t_l},  {$s_l})";
					$CFG->DB->query($sql);
					$face_to_face = $CFG->DB->lastId();

					return $face_to_face;
			}
	}

	function DellFaceToFace($t_l=0, $s_l=0)
	{
			global $CFG;
			if($t_l > 0 && $s_l > 0)
			{
				$sql = getSQLRowO("SELECT id FROM my_face_to_face WHERE page_id={$t_l} AND parent_id = {$s_l} ");
				echo $query  = "DELETE FROM my_face_to_face WHERE id = {$sql->id} ";
				$CFG->DB->query($query);
			}
	}



	function getMobileUser($mobile)
	{
			global $CFG;

			if($mobile != '')
			{
				$sql = getSQLRowO("SELECT default_signimpress, good_signimpress, default_led, good_led FROM my_face WHERE mobile='{$mobile}' and visible = 1 ");
				return $sql;
			}
	}

	function upMobileUser($data)
	{
			global $CFG;

			$user = $CFG->USER->USER_ID;
			$sql = getSQLRowO("SELECT	* FROM my_users WHERE id='{$user}' ");

			$client_id = $data['page_id'];
			$client = getSQLRowO("SELECT	* FROM my_face WHERE id='{$client_id}' ");


			if($data['id'] == 'good_signimpress')
			{
				$html = $sql->name.' установил скидку '.$data['res'].'%, клиенту '.urlencode($client->mobile).', за хорошие отношения';
			}
			elseif($data['id'] == 'default_signimpress')
			{
				$html = $sql->name.' установил базовую скидку '.$data['res'].'%, клиенту '.urlencode($client->mobile).'';
			}


			$token = "5425212111:AAGmywnZt-QTQfvhkiRpTzM81-2-lLD4J4s";
			$chat_id = "-873857229";
			$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$html}","r");
			if ($sendToTelegram) {
				return true;
			} else {
				return false;
			}


			$page_id = $data['page_id'];
			$id = $data['id'];
			$res = $data['res'];

			$query  = "UPDATE my_face SET {$id}='{$res}' WHERE id='{$page_id}'";
			$CFG->DB->query($query);
	}


	function AddProductDiscount($data)
	{
			global $CFG;
			$sql = getSQLRowO("SELECT	* FROM my_data_1c WHERE vendor='{$data[vendor]}' and visible = 1 ");
			if($sql->id > 0)
			{
				$cdate = sqlDateNow();
				$sql = "INSERT INTO my_face_vendor (cdate, page_id, vendor, user_id, number, site, customer) VALUES ('{$cdate}', {$data[page_id]}, {$data[vendor]}, {$data[user_id]}, {$data[skidos_number]}, {$data[site]},  '{$data[mobile]}' )";
				$CFG->DB->query($sql);
				echo $CFG->DB->lastId();
			}
			else
			{
					echo 0;
			}
	}

}
