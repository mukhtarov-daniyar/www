<?
class Bar
{
	private $user = 0;
	private $cdate = 0;
	private $rec = 0;
	private $cnt = 0;

	function __construct()
	{

			$number = cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year']);
			$this->StartCdate = $_GET['year'].'-'.$_GET['month'].'-01 00:01:01';
			$this->EdntCdate = $_GET['year'].'-'.$_GET['month'].'-'.$number.' 23:59:59';
	}

	public function UpNewPost($array)
	{
		global $CFG;

		$this->rec = $array;
		unset($this->rec['act']);

		$name = $this->rec['names'];
		$count = $this->rec['count'];
		for ($y=0; $y<sizeof($name); $y++)
		{
				$arr[$y]->name =  $name[$y];
				$arr[$y]->count =  preg_replace('/[^0-9]/', '', $count[$y]);
		}

		$this->rec = $arr;
		$arrserialize = serialize($this->rec);
		$cdate = sqlDateNow();

		$sql = "INSERT INTO my_bar_statistics (array, cdate, visible) VALUES ('{$arrserialize}', '{$cdate}', 1)";
		if($CFG->DB->query($sql))
		{
			echo 1;
		}
		else {
			echo 0;
		}
	}


	public function SelectLast()
	{
		$sql = getSQLRowO("SELECT * FROM my_bar_statistics WHERE cdate > '{$this->StartCdate}' AND cdate < '{$this->EdntCdate}' order by id DESC ");

		return (unserialize ($sql->array ));
	}



	public function CalculationTable1C($cdate)
	{
		//Вытаскиваем доход от продаж склад Молоток алматы, продавец Дина
		$warehouse_id = getSQLRowO("SELECT * FROM my_data_1c_warehouse WHERE `name` LIKE 'Алматы Molotok.kz' ");
		$sql = getSQLRowO("SELECT

		SUM(count) as count,
		SUM(count*price) as price,
		SUM(count*purchase) as purchase,
		SUM(count*price-count*purchase) as profit,
		SUM(nds) as nds

		FROM my_data_1c_nomenclature WHERE visible = 1 AND user_id IN(143) AND warehouse_id IN({$warehouse_id->id}) AND (cdate >= '{$this->StartCdate}') AND (cdate <= '{$this->EdntCdate}')");

		$data = new stdClass();
		$data->dina->count = $sql->count*1;
		$data->dina->price = $sql->price*1;
		$data->dina->purchase = $sql->purchase*1;
		$data->dina->profit = $sql->profit*1 - $sql->nds*1;

		//Вытаскиваем продажи со всех складов Дины
		$sql = getSQLRowO("SELECT SUM(count) as count, SUM(count*price) as price, SUM(count*purchase) as purchase, SUM(count*price-count*purchase) as profit,
		SUM(nds) as nds
		 FROM my_data_1c_nomenclature WHERE visible = 1 AND user_id =143 AND warehouse_id != {$warehouse_id->id} AND (cdate >= '{$this->StartCdate}') AND (cdate <= '{$this->EdntCdate}')");

		$data->dina_alle->count = $sql->count*1;
		$data->dina_alle->price = $sql->price*1;
		$data->dina_alle->purchase = $sql->purchase*1;
		$data->dina_alle->profit = $sql->profit*1 - $sql->nds*1;


		//Вытаскиваем продажи всех с склада Молоток алматы, исключая Дину
		$sql = getSQLRowO("SELECT SUM(count) as count, SUM(count*price) as price, SUM(count*purchase) as purchase, SUM(count*price-count*purchase) as profit,
		SUM(nds) as nds FROM my_data_1c_nomenclature WHERE visible = 1 AND user_id !=143 AND warehouse_id IN({$warehouse_id->id}) AND (cdate >= '{$this->StartCdate}') AND (cdate <= '{$this->EdntCdate}')");

		$data->alle->count = $sql->count*1;
		$data->alle->price = $sql->price*1;
		$data->alle->purchase = $sql->purchase*1;
		$data->alle->profit = $sql->profit*1 - $sql->nds*1;

		return $data;
	}


	public function CalculationTableARiM1C($cdate)
	{
		//SELECT * FROM my_data_1c_nomenclature WHERE visible = 1 AND warehouse_id IN(2126) AND (cdate >= '2022-01-01 00:00:00 00:00:00') AND (cdate <= '2022-01-31 23:59:59 23:59:59')

		//Вытаскиваем продажи по УУ с скллада рим алматы
		$warehouse_id = getSQLRowO("SELECT * FROM my_data_1c_warehouse WHERE `name` LIKE 'Алматы РиМ' ");
		$sql = getSQLRowO("SELECT SUM(count) as count, SUM(count*price) as price, SUM(count*purchase) as purchase, SUM(count*price-count*purchase) as profit FROM my_data_1c_nomenclature WHERE visible = 1 AND buch = 0 AND warehouse_id IN({$warehouse_id->id}) AND (cdate >= '{$this->StartCdate}') AND (cdate <= '{$this->EdntCdate}')");

		$data = new stdClass();
		$data->rim_uu->count = $sql->count*1;
		$data->rim_uu->price = $sql->price*1;
		$data->rim_uu->purchase = $sql->purchase*1;
		$data->rim_uu->profit = $sql->profit*1;

		//Вытаскиваем продажи по БУ с скллада рим алматы
		$sql = getSQLRowO("SELECT SUM(count) as count, SUM(count*price) as price, SUM(count*purchase) as purchase, SUM(count*price-count*purchase) as profit FROM my_data_1c_nomenclature WHERE visible = 1 AND buch = 1 AND warehouse_id IN({$warehouse_id->id}) AND (cdate >= '{$this->StartCdate}') AND (cdate <= '{$this->EdntCdate}')");

		$data->rim_bu->count = $sql->count*1;
		$data->rim_bu->price = $sql->price*1;
		$data->rim_bu->purchase = $sql->purchase*1;
		$data->rim_bu->profit = $sql->profit*1;

		return $data;
	}



		public function CalculationTablePavlodarAsem1C($cdate)
		{
			//SELECT * FROM my_data_1c_nomenclature WHERE visible = 1 AND warehouse_id IN(2126) AND (cdate >= '2022-01-01 00:00:00 00:00:00') AND (cdate <= '2022-01-31 23:59:59 23:59:59')

			//Вытаскиваем продажи по УУ с скллада рим алматы
			$warehouse_id = getSQLRowO("SELECT * FROM my_data_1c_warehouse WHERE `name` LIKE 'Павлодар Асем' ");
			$sql = getSQLRowO("SELECT SUM(count) as count, SUM(count*price) as price, SUM(count*purchase) as purchase, SUM(count*price-count*purchase) as profit FROM my_data_1c_nomenclature WHERE visible = 1 AND buch = 0 AND warehouse_id IN({$warehouse_id->id}) AND (cdate >= '{$this->StartCdate}') AND (cdate <= '{$this->EdntCdate}')");

			$data = new stdClass();
			$data->rim_uu->count = $sql->count*1;
			$data->rim_uu->price = $sql->price*1;
			$data->rim_uu->purchase = $sql->purchase*1;
			$data->rim_uu->profit = $sql->profit*1;

			//Вытаскиваем продажи по БУ с скллада рим алматы
			$sql = getSQLRowO("SELECT SUM(count) as count, SUM(count*price) as price, SUM(count*purchase) as purchase, SUM(count*price-count*purchase) as profit FROM my_data_1c_nomenclature WHERE visible = 1 AND buch = 1 AND warehouse_id IN({$warehouse_id->id}) AND (cdate >= '{$this->StartCdate}') AND (cdate <= '{$this->EdntCdate}')");

			$data->rim_bu->count = $sql->count*1;
			$data->rim_bu->price = $sql->price*1;
			$data->rim_bu->purchase = $sql->purchase*1;
			$data->rim_bu->profit = $sql->profit*1;

			return $data;
		}








	public function CopyNewPost()
	{
		global $CFG;

		$start = date("Y-m-d 00:00:00");
		$off = date("Y-m-d 23:59:59");

		$sql = getSQLRowO("SELECT id, array FROM my_bar_statistics  WHERE cdate > '{$start}' AND cdate < '{$off}'  order by cdate DESC ");

		$CFG->DB->query(" DELETE FROM my_bar_statistics WHERE id  NOT IN (".$sql->id.") AND cdate > '{$start}' AND cdate < '{$off}' ");

		$last_day = date("Y-m-d 00:01:01", strtotime('+1 days') );
		$CFG->DB->query("INSERT INTO my_bar_statistics (array, cdate, visible) VALUES ('{$sql->array}', '{$last_day}', 1)");
	}






}
