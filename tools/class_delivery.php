<?php


class Delivery
{
	var $NDS = 18;
	var $method = 0;
	var $cost_self = 0;
	var $cost_curier = 5;
	var $NAMES = array("Самовывоз", "СПСР", "Курьер", "Почта", "Автодоставка", "ЖелДорЭкспедиция");

	function Delivery($dtype)
	{
		$this->method = $dtype;
	}

	function getCost($weight, $rajon=0, $city=0)
	{
		GLOBAL $CFG;

		$weight = round($weight + .5);

		if ($this->method == 0)	
			return $this->cost_self;

		if ($this->method == 2)	
			return $this->cost_curier;

		if ($this->method == 1)
		{
			$sql = "SELECT (S.cost*C.coeff) AS cost, S.zone_id FROM {$CFG->DB_Prefix}spsr_costs S LEFT OUTER JOIN hc_spsr_rajons R ON S.zone_id=R.zone_id LEFT OUTER JOIN hc_spsr_city C ON C.rajon_id=R.id WHERE C.id='$city' AND R.id='$rajon' AND S.weight>'$weight' ORDER BY S.weight LIMIT 0,1";
			$items = getSQLArrayO($sql);
			$cost = $items[0]->cost;
			$zone = $items[0]->zone_id;
			$cost = 1 * trim(sprintf("%10.2f", $cost+($cost/100)*$this->NDS));
			return $cost;
		}

		if ($this->method == 3)
		{
			$sql = "SELECT zone_id FROM {$CFG->DB_Prefix}post_rajons WHERE id='{$rajon}'";
			$zone = getSQLField($sql);
			if ($zone == "")
				return 0;
			$sql = "SELECT zone_{$zone} AS cost FROM {$CFG->DB_Prefix}post_costs WHERE weight>'$weight' ORDER BY weight LIMIT 0,1";
			$items = getSQLArrayO($sql);
			$cost = $items[0]->cost;
			$cost = 1 * trim(sprintf("%10.2f", $cost));
			return $cost;
		}

		if ($this->method == 4)	
		{
			$city = urlencode($city);
			$res = postDocument("truckmarket.ru", 80, "tc.php", "City1=Новосибирск&City5={$city}&City2=&Submit1=Определить");
			$l1 = explode("<br><br><font style=\"font-size: 16px\"><b>", $res[1]);
			$l2 = explode("<", $l1[1]);
			$dist = 1 * trim($l2[0]);
			$cost = $dist * 8;
			return $cost;
		}

		if ($this->method == 5)
		{
			$f = 6;
			if ($weight <= 1000)	$f = 5;
			if ($weight <= 5000)	$f = 4;
			if ($weight <= 3000)	$f = 3;
			if ($weight <= 1500)	$f = 2;
			if ($weight <=  500)	$f = 1;
			$city = addSlashes($city);
			$sql = "SELECT w{$f} AS cost, min_cost FROM {$CFG->DB_Prefix}zeldor_costs WHERE city='{$city}'";
			$items = getSQLArrayO($sql);
			if (sizeof($items)<=0)
				$cost = 0;
			else
			{
				$cost = 1 * $items[0]->cost * $weight;
				if ($cost < $items[0]->min_cost)
					$cost = $items[0]->min_cost;
			}
			$cost = 1 * trim(sprintf("%10.2f", $cost));
			return $cost;
		}

	}

	function getName($type=-1)
	{
		if ($type==-1)
			$type = $this->method;
		$res = $this->NAMES[$type];
		if ($res == "")
			$res = "Неопределена";
		return $res;
	}

}


function postDocument($host, $port, $url, $senddata)
{
  $len = "".strlen( $senddata );

	$errno=0;
	$errstr = "";
  $sock = fsockopen( $host, $port, $errno, $errstr, 30 );
  if( !$sock ) die( $errstr.' ('.$errno.')' );

//die($senddata);
  fputs( $sock, 'POST /'.$url.' HTTP/1.0'."\r\n" );
  fputs( $sock, 'Host: '.$host."\r\n" );
  fputs( $sock, 'Content-type: application/x-www-form-urlencoded'."\r\n" );
  fputs( $sock, 'Content-length: '.$len."\r\n" );
//  fputs( $sock, 'Cookie: '.$_SERVER["HTTP_COOKIE"]."\r\n" );
  fputs( $sock, 'Accept: */*'."\r\n" );
  fputs( $sock, "\r\n" );
  fputs( $sock, $senddata."\r\n" );
  fputs( $sock, "\r\n" );

  $headers = '';
  while( $str = trim( fgets( $sock, 4096 ) ) )
    $headers .= "$str\n";

  $res = '';
  while( !feof( $sock ) )
    $res .= fgets( $sock, 4096 );

  fclose( $sock );

	return array($headers, $res);
}


?>