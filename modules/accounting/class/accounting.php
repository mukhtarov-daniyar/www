<?

class Acon
{
	function getList($where="", $search_word="", $count=0, $start=0, $data_id)
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

		//$userId = $this->groupAccess($CFG->USER->USER_DIRECTOR_ID);

		$sql = "SELECT * FROM {$CFG->DB_Prefix}money_accounting WHERE visible='1' AND data_id = '{$data_id}'  {$search_word}  {$where}  {$cdate}  {$userId} ORDER BY cdate DESC $w";

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


	public function sum($user)
	{
		global $CFG;

		$director = $user;

		$z = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE data_id='{$director}' AND type_id = 1 AND visible='1' ");

		$p = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE data_id='{$director}' AND type_id = 2 AND visible='1' ");

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


			if($key == 'pdf')continue;


			if($key == 'monthstart')
			{
				$str .= "AND (cdate >= '{$value} 00:00:00') ";
			}
			if($key == 'monthstart') continue;

			if($key == 'monthend')
			{
				$str .= " AND (cdate <= '{$value} 23:59:59')";
			}
			if($key == 'monthend') continue;


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


		public function getListExport($data)
		{
			global $CFG;

			$itogo = 'Итого в кассе: '.$this->sum($CFG->_GET_PARAMS[1]).' '.$CFG->USER->USER_CURRENCY;

			foreach ($data as $value)
			{

				//Определяем юзера
				$test = getSQLRowO(" SELECT name FROM my_users WHERE id = '{$value->user_id}' ");
				$value->user_id = $test->name;

				//Определяем тип операции
				if($value->type_id == 2) { $type = 'расход';}else{ $type = 'приход';}
				$value->type_id  = $type;

				//Определяем категорию
				$test = getSQLRowO(" SELECT name FROM my_money_accounting_type_id WHERE id = '{$value->cat_id}' ");
				$value->cat_id = $test->name;


				$respon[]= $value;

			}

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("forSign Kazakhstan");
		$objPHPExcel->getProperties()->setLastModifiedBy("forSign Kazakhstan");
		$objPHPExcel->getProperties()->setTitle("XLSX Document");
		$objPHPExcel->getProperties()->setSubject("Export XLSX Document");
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F98383');
		$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Тип операции');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Категория');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Менеджер');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Сумма в '.$CFG->USER->USER_CURRENCY);
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Дата');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Примечание');


		$cnt = 2;
		foreach($respon as $res)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", $res->type_id);
			$objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", $res->cat_id);
			$objPHPExcel->getActiveSheet()->SetCellValue("C$cnt", $res->user_id);
			$objPHPExcel->getActiveSheet()->SetCellValue("D$cnt", $res->price);
			$objPHPExcel->getActiveSheet()->SetCellValue("E$cnt", $res->cdate);
			$objPHPExcel->getActiveSheet()->SetCellValue("F$cnt", $res->text);

			//Формируем и того, пиход расход
			if($res->type_id == 'расход') {$rashod[] = $res->price;} else {$prihod[] = $res->price;}

			$cnt ++;
		}

		$rashod = 'Сумма по расходам: '.array_sum($rashod).' '.$CFG->USER->USER_CURRENCY;
		$prihod = 'Сумма по приходам: '.array_sum($prihod).' '.$CFG->USER->USER_CURRENCY;

		$cnt = $cnt+1;
		$objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", $prihod);
		$objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", $rashod);
		$objPHPExcel->getActiveSheet()->SetCellValue("C$cnt", $itogo);


		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$name = date("m_y").".xlsx";
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="' . $name . '"');
		$objWriter->save('php://output');
		$objPHPExcel->disconnectWorksheets();

		}



}
