<?

class Edrive
{
	function getCount($base, $where="", $search_word="")
	{
		global $CFG;
		$sql =  getSQLRowO("SELECT COUNT(id) as id FROM {$base} WHERE visible='1' {$load} {$search_word} {$where} ORDER BY id DESC ");
		return $sql->id;
	}

	function getList($base, $where="", $search_word="")
	{
		global $CFG;

		$sql = "SELECT  my_city.name AS city,
										my_edrive_car_port.name AS port,
										my_edrive_car.name AS auto,
										my_edrive_car_model.name AS model,
										my_face.name_other AS fio,
										my_face.name AS name,
										 {$base}.*
						FROM {$base} LEFT JOIN my_edrive_car_port ON my_edrive_car_port.id={$base}.port_id
													LEFT JOIN my_city ON my_city.id={$base}.city_id
													LEFT JOIN my_edrive_car ON my_edrive_car.id={$base}.car_id
													LEFT JOIN my_edrive_car_model ON my_edrive_car_model.id=my_edrive_car.model_id
													LEFT JOIN my_face ON my_face.id={$base}.client_id
						WHERE {$base}.visible='1' {$where} {$search_word} ORDER BY id DESC ";

		$res = getSQLArrayO($sql);

		return $res;
	}

	function getListID($id)
	{
		global $CFG;

		return getSQLRowO("SELECT * FROM my_edrive WHERE id='{$id}' ");;
	}


	function getListIDUP($post)
	{
		global $CFG;

		$id = $post['post_id'];
		$car = $post['car'];
		$city = $post['city'];
		$port = $post['port'];
		$client = $post['client'];

		$query  = "UPDATE my_edrive SET car_id='{$car}', city_id='{$city}', port_id='{$port}', client_id='{$client}' WHERE id='{$id}'";
		$CFG->DB->query($query);

		$CFG->STATUS->OK = true;
		$CFG->STATUS->MESSAGE = 'Данные успешно сохранены!';
		redirect($_SERVER["HTTP_REFERER"]);
	}




	function up_model($data)
	{
		global $CFG;

		$id = $data['id'];
		$text = $data['text'];

		if($id > 0)
		{
				$query  = "UPDATE my_edrive_car_model SET name='{$text}' WHERE id='{$id}'";
				$CFG->DB->query($query);
				echo $id;
		}
		else
		{
			$CFG->DB->query("INSERT INTO my_edrive_car_model (name) VALUES ('{$text}') ");
			echo $CFG->DB->lastId();
		}

	}

	function up_car($data)
	{
		global $CFG;

		$id = $data['id'];
		$text = $data['text'];

		if($id > 0)
		{
			$CFG->DB->query("INSERT INTO my_edrive_car (name, model_id) VALUES ('{$text}', '{$id}') ");
			echo $CFG->DB->lastId();
		}

		//else
		//{
		//	$CFG->DB->query("INSERT INTO my_edrive_car_model (name) VALUES ('{$text}') ");
		//	echo $CFG->DB->lastId();
		//}

	}

	function getUP($data)
	{
		global $CFG;

		if($data['user_act'] == 'add_up')
		{
			$car = (int)$data['car'];
			$city = (int)$data['city'];
			$port = (int)$data['port'];
			$client = (int)$data['client'];

			if($car != 0 && $city != 0 && $port != 0 && $client != 0 )
			{
				$CFG->DB->query("INSERT INTO my_edrive (car_id, port_id, city_id, client_id, visible) VALUES ('{$car}', '{$port}', '{$city}', '{$client}', 1) ");
				echo $CFG->DB->lastId();
			}
			else
				echo 0;
		}

		exit;
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
						$str .= " AND my_edrive.city_id IN ({$test})";
					}
					else
					{
						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = 'Отсутствует список городов';
					}
				}
			}
			if($key == 'country_training') continue;



			if($key == 'car')
			{
				if($value == 0)
				{
						// Лябая страна $str .= " AND city_id=0";
				}
				elseif(is_numeric($value) > 0 )
				{
					$response = getSQLArrayO("SELECT id FROM my_edrive_car WHERE model_id = {$value} order by id DESC");

					if(count($response) > 0)
					{
						for($y=0;$y<sizeof($response);$y++)
						{
							$idS .= $response[$y]->id.',';
						}

						$test = trim($idS, ",");
						$str .= " AND my_edrive.car_id IN ({$test})";
					}
				}
			}
			if($key == 'car') continue;



			if($key == 'city')
			{
				if($value)
				{
					if(count($value) > 0)
					{
						$str .= " AND my_edrive.city_id IN ({$value})";
					}
					else
					{
						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = 'Отсутствует список городов';
					}
				}

			}
			if($key == 'city') continue;
			if($key == 'exel') continue;



			if($key == 'port')
			{

				if($value && $value != 0)
				{
					if(count($value) > 0)
					{
						for($y=0;$y<sizeof($value);$y++)
						{
							if($value[$y] == 0) continue;
							$idSP .= $value[$y].',';
						}
						$testp = trim($idSP, ",");

						if($testp != '')
						{
						 	$str .= " AND my_edrive.port_id IN ({$testp})";
						}
					}
					else
					{
						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = 'Отсутствует список портов';
					}
				}

			}
			if($key == 'port') continue;



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

		function getDEL($id)
		{
				global $CFG;
				if($id > 0)
				{
					$CFG->DB->query("DELETE FROM my_edrive WHERE id = {$id} ");
					redirect($_SERVER['HTTP_REFERER']);
				}
		}


		function getDELCAR($id)
		{
				global $CFG;
				if($id > 0)
				{
					$CFG->DB->query("DELETE FROM my_edrive_car WHERE id = {$id} ");
					redirect($_SERVER['HTTP_REFERER']);
				}
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

				for($y=0;$y<sizeof($types);$y++)
				{
					$respon = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}face WHERE id = {$types[$y]} AND visible='1' ");

					$arraym = $respon->whatsapp;
					$arrayn = $respon->mobile;

					$tel[] = array('tel' => $respon->whatsapp, 'name' => $respon->name);
				}

				for($x=0;$x<sizeof($tel);$x++)
				{
						$up .= "('".$CFG->oPageInfo->id."', '".$res->id."',  '".$time."',  '".$tel[$x]['name']."',  '".$tel[$x]['tel']."', '0', '0'),".PHP_EOL;
				}

				if($up != '')
				{
					$up = substr($up, 0, -2);
			    $CFG->DB->query("INSERT INTO my_tmp_whatsapp_rss (page_id, whatsapp_id, times, name, mobile, status,  visible) VALUES {$up} ON DUPLICATE KEY UPDATE page_id = VALUES(page_id), whatsapp_id = VALUES(whatsapp_id), times = VALUES(times), name = VALUES(name), mobile = VALUES(mobile), status = VALUES(status), visible = VALUES(visible); ");
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

	function ExportExel($data)
	{
		global $CFG;


		set_time_limit(600);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("eDrive.kz");
		$objPHPExcel->getProperties()->setLastModifiedBy("eDrive.kz");
		$objPHPExcel->getProperties()->setTitle("XLSX Document");
		$objPHPExcel->getProperties()->setSubject("Export XLSX Document");
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F98383');
		$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);

		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1', '#');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Марка');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Модель');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Город');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Скоростной порт');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Владелец');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Мобильный');

		$cnt = 2;
		foreach ($data as $value)
		{
			$sql = getSQLRowO("SELECT	* FROM my_face WHERE id='{$value->client_id}'  ");

			$objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", $cnt-1);
			$objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", $value->model);
			$objPHPExcel->getActiveSheet()->SetCellValue("C$cnt", $value->auto);
			$objPHPExcel->getActiveSheet()->SetCellValue("D$cnt", $value->city);
			$objPHPExcel->getActiveSheet()->SetCellValue("E$cnt", $value->port);
			$objPHPExcel->getActiveSheet()->SetCellValue("F$cnt", $value->fio);
			$objPHPExcel->getActiveSheet()->SetCellValue("G$cnt", $sql->mobile);
			$cnt ++;
		}


		$mul = $cnt+2;
		$objPHPExcel->getActiveSheet()->SetCellValue("A$mul", 'Итого');
		$objPHPExcel->getActiveSheet()->SetCellValue("B$mul", $cnt-2 );

		$objPHPExcel->getActiveSheet()->setTitle('eDrive.kz');
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$name = "Электроавто.xlsx";

		ob_clean();

	 # Output headers.
	 header("Set-Cookie: fileDownload=true; path=/");
	 header("Cache-Control: private");
	 header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	 header("Content-Disposition: attachment; filename=".$name."");
	 // If you're serving to IE 9, then the following may be needed
	 header('Cache-Control: max-age=1');
	 // If you're serving to IE over SSL, then the following may be needed
	 header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	 header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	 header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	 header ('Pragma: public'); // HTTP/1.0

	 $objWriter->save('php://output');
	 $objPHPExcel->disconnectWorksheets();

	 die();

	}

}
