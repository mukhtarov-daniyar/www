<?
	class Salary
	{
		var $url = 'http://192.168.1.110:8081';
		var $login = 'webservice';
		var $password = 'AsdfRewq!';

		function getList($dir_id=0, $data="")
		{
	        if($data[0] !="" && $data[1] != "")
			{
				$type = $data[1];	$pole = $data[0];
			}
			else
			{
				$type = 'ASC';	$pole = 'id';
			}

			$o = getSQLArrayO("SELECT * FROM my_users WHERE visible = 1 AND user_id = {$dir_id} order by {$pole} {$type}");

			return $o;
		}


		function getList1C($month, $year)
		{
			if($month == '' && $year == '' || $month == 0 && $year == 0)
			{
				$cdate = date("01.m.Y").'-'.date('t.m.Y');
			}
			else
			{
				$cdate = date('01.m.Y', strtotime('01.'.$month.'.'.$year)).'-'.date('t.m.Y', strtotime('01.'.$month.'.'.$year));
			}


			$data = $this->curl_1c($this->url.'/fc_utp/hs/api4/zp/GetInfo?date='.$cdate, $this->login, $this->password);

			if($data != NULL)
			{
					return $data['ЗаработнаяПлата'];
			}
		}

		function getLast($name)
		{
			if($name != '' || $name != 0)
			{
				$last = explode(" ", $name);
				return $last[1].' '.$last[0];
			}
		}

		public static function getLastZP($name, $type)
		{
			global $CFG;
			$user = getSQLRowO(" SELECT {$type} FROM my_users WHERE name = '{$name}' ");

			return $user->{$type};
		}

		function getLastZP_MINUS_UU($user_nane, $count, $month, $year)
		{
			global $CFG;

			$user = $this->getLastZP($user_nane, 'id');

			if($user > 0)
			{
				$startcdate = date('Y-m-01 00:00:00', strtotime('01.'.$month.'.'.$year));
				$endcdate = date('Y-m-t 23:59:59', strtotime('01.'.$month.'.'.$year));

				$minus = getSQLRowO(" SELECT SUM(count) FROM my_money_minus_list WHERE manager_id  = {$user}  AND cdate > '{$startcdate}' AND cdate < '{$endcdate}' ");
				$plus = getSQLRowO(" SELECT SUM(count) FROM my_money_list WHERE manager_id  = {$user}  AND cdate > '{$startcdate}' AND cdate < '{$endcdate}' ");

				if(!$count == 0)
				{

					if($minus->{'SUM(count)'} - $plus->{'SUM(count)'} > 0)
					{
						return $count-($minus->{'SUM(count)'}-$plus->{'SUM(count)'});
					}
					elseif($minus->{'SUM(count)'} - $plus->{'SUM(count)'} < 0)
					{
						return $count;
					}
					else
					{
						return $count;
					}

				}
				else
				{
						return 0;
				}
			}
		}

		public function curl_1c($url, $login, $password)
		{
			set_time_limit(600);

			//echo $url;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
			$result = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($result, true);

			return $data;
		}


		function getListCashbox()
		{
			//		$url = "http://192.168.1.110:8081/fc_utp/hs/api/v1/data";


			return $data;
		}


	}
