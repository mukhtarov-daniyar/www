<?



class Speeds
{
	function getList($str="", $cnt = 100)
	{
		global $CFG;

		if($_COOKIE["type"] != ''){	$order = 'ORDER BY '.$_COOKIE["orders"].' '.$_COOKIE["type"].''; } else {$order ='';}

	  $res = getSQLArrayO("	SELECT  *, SUM(count) AS counts, 	SUM(price*count) AS prices, 	SUM(total*count) AS totals	FROM {$CFG->DB_Prefix}data_1c WHERE visible = 1 {$str} GROUP BY name 	{$order} limit {$cnt} ");
  	$old = getSQLArrayO("	SELECT SUM(count) AS counts, 	SUM(price*count) AS prices,	SUM(total*count) AS totals,	SUM(sale_year) AS sale_year	FROM {$CFG->DB_Prefix}data_1c WHERE visible = 1 {$str} GROUP BY name 	{$order} ");

		foreach ($old as $value)
		{
			$counts[] = 		$value->counts;
			$prices[] = 		$value->prices;
			$totals[] = 		$value->totals;
			$sale_year[] = 	$value->sale_year;
		}
		$data['row']['id'] = count($counts);
		$data['row']['run'] = count($old);
		$data['row']['runS'] = count($res);
		$data['row']['row'] = array_sum($counts);
		$data['row']['price'] = array_sum($prices);
		$data['row']['total'] = array_sum($totals);
		$data['row']['sale_year'] = array_sum($sale_year);
		$data['item'] = $res;

		return  $data;
	}

	function getListNomenclature($str="")
	{
		global $CFG;

		if($_COOKIE["nomenclature_type"] != ''){	$order = 'ORDER BY '.$_COOKIE["nomenclature_orders"].' '.$_COOKIE["nomenclature_type"].''; } else {$order ='';}

		$sql = "	SELECT  * FROM {$CFG->DB_Prefix}data_1c_nomenclature WHERE visible = 1  {$str}	{$order} ";
		$res = getSQLArrayO($sql);
		//$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}data_1c WHERE visible='1' {$str} ");
		return  $res;
	}


	function getListBuyer($data)
	{
			global $CFG;

			$res = getSQLArrayO("	SELECT  * FROM my_data_1c_nomenclature WHERE visible = 1 AND buyer LIKE '%{$data}%' GROUP BY buyer limit 20 ");

			if(count($res) > 0)
			{
				foreach ($res as $value)
				{
					$respon[] = $value->buyer;
				}

				return $respon;
			}
	}


	function getOrder($name, $table, $if='orders')
	{
		global $CFG;

		if($_COOKIE["type"] == 'ASC' || $_COOKIE["nomenclature_type"] == 'ASC' )
		{
			$type = 'DESC';
			if($_COOKIE[$if] == $table )	{ $icon = '<span class="glyphicon glyphicon-sort-by-attributes"></span>';	}
			echo '<a href="/speedometer/'.$if.'/'.$table.'/'.$type.'">'.$name.' '.$icon.'</a>';
		}
		elseif($_COOKIE["type"] == 'DESC' || $_COOKIE["nomenclature_type"] == 'DESC' )
		{
			$type = 'ASC';
			if($_COOKIE[$if] == $table)	{ $icon = '<span class="glyphicon glyphicon-sort-by-attributes-alt"></span>';	}
			echo '<a href="/speedometer/'.$if.'/'.$table.'/'.$type.'">'.$name.' '.$icon.'</a>';
		}
		else
		{
			$type = 'ASC';
			echo '<a href="/speedometer/'.$if.'/'.$table.'/'.$type.'">'.$name.' </a>';
		}
		//nomenclature_orders
	}



		function getData($response)
		{
			global $CFG;

			foreach($response as $key => $value)
			{

				if($key == 'standards') continue;
			  if($key == 'cnt') continue;

				if($key == 'remove_position')
				{
					if($value == '') continue;

					foreach ($value as $ids)
					{
						$trims .= "'".$ids."',";
					}

					$ral_id =  trim($trims, ',');
					$str .= " AND id NOT IN ({$ral_id})	" ;
				}
				if($key == 'remove_position') continue;


				if($key == 'buyer')
				{
					if($value == '') continue;
					$apost = apost($value);
					$str .= " AND buyer LIKE '%{$apost}%'  	" ;
				}
				if($key == 'buyer') continue;


				if($key == 'search')
				{
					if($value == '') continue;
					$apost = apost($value);
					$str .= " AND name LIKE '%{$apost}%'  	" ;
				}
				if($key == 'search') continue;



				if($key == 'group')
				{
					if($value == '' || $value == 0 ) continue;

					foreach ($value as $res)
					{
						$id .= $res.',';
					}

					$trim = trim($id, ',');

					$str .= " AND group_id IN({$trim})  	" ;
				}
				if($key == 'group') continue;


				if($key == 'counts')
				{
					if($value == 1)
					{
						$str .= " AND count >= 0  	" ;
					}
					if($value == 2)
					{
						$str .= " AND count >= 1  	" ;
					}
					if($value == 3)
					{
						$str .= " AND count = 0  	" ;
					}
				}
				if($key == 'counts') continue;


				if($key == 'prices')
				{
					if($value == 1)
					{
						$str .= " AND price >= 0  	" ;
					}
					if($value == 2)
					{
						$str .= " AND price >= 1  	" ;
					}
					if($value == 3)
					{
						$str .= " AND price = 0  	" ;
					}
				}
				if($key == 'prices') continue;




				if($key == 'tabs') continue;
				if($key == 'exel') continue;



					if($key == 'cdate')
					{
						$current = 	date("Y-m-d 00:00:00");
						$day_1 =  	date("Y-m-d 00:00:00", strtotime('-1 days') );		$day_1_1 =  	date("Y-m-d 23:59:59", strtotime('-1 days') );

						switch ($value)
						{
							//За сегодня
							case 0:
							 $str .= " AND (cdate >= '{$current}')  ";
							break;

							//За вчера
							case 1:
							 $str .= "  AND (cdate >= '{$day_1}') AND (cdate <= '{$day_1_1}')  ";
							break;

							//С понедельника
							case 2:
								$day_7 = date('Y-m-d 00:00:01', strtotime("Monday this week"));	$current = 	date("Y-m-d 23:59:59");
							 	$str .= "  AND (cdate >= '{$day_7}') AND (cdate <= '{$current}')  ";
							break;

							//С первого числа месяца
							case 3:
								$day_30 = date("Y-m-01 00:00:00");	$current = 	date("Y-m-d 23:59:59");
							 	$str .= "  AND (cdate >= '{$day_30}') AND (cdate <= '{$current}')  ";
							break;

							//С первого января
							case 4:
								$day_30 = date("Y-01-01 00:00:00");	$current = 	date("Y-m-d 23:59:59");
							 	$str .= "  AND (cdate >= '{$day_30}') AND (cdate <= '{$current}')  ";
							break;

						}
					}
					if($key == 'cdate') continue;

					if($key == 'user')
					{
						if($value == '') continue;
						foreach($value as $res)
						{
								$resp .= $res.',';
						}
						$resp = trim($resp, ',');	if($resp == '') continue;
						$str .= 'AND '.$key.'_id IN('.$resp.') ';
					}
					if($key == 'user') continue;


					if($key == 'IM_P')
					{
						if (is_numeric($value) && $value == 1)
						{
							$str .= 'AND '.$key.'='.$value.' ';
						}
					}
					if($key == 'IM_P') continue;



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

			function NomenclatureRuk($data)
			{
				global $CFG;

				$id = $data['id'];

				$val = $data['val'];
				$bu = $data['bu'];
				$uu = $data['uu'];

				$val_2 = $data['val_2'];
				$bu_2 = $data['bu_2'];
				$uu_2 = $data['uu_2'];


				$query  = "DELETE FROM my_data_1c_nomenclature_ruk WHERE id_product= '{$id}' ";
				$CFG->DB->query($query);

				$sql = "INSERT INTO my_data_1c_nomenclature_ruk (id_product,user_id,bu,uu) VALUES ('{$id}','{$val}','{$bu}','{$uu}')";
 				if($CFG->DB->query($sql))
				{
						if($val_2 > 0)
						{
							$CFG->DB->query("INSERT INTO my_data_1c_nomenclature_ruk (id_product,user_id,bu,uu) VALUES ('{$id}','{$val_2}','{$bu_2}','{$uu_2}')");
						}

					echo 1;
				}
			}

			function Nomenclature($type)
			{
				global $CFG;
				if($type == 1)
				{
					  return $this->printHello();
				}
			}

			protected function DeleteNomenclature($base)
			{
				global $CFG, $DB;

				$tes = getSQLArrayO("SELECT id  FROM {$base} GROUP BY name order by id DESC");

				foreach($tes as $val)
				{
					$arr .= $val->id.',';
				}
				$test = trim($arr, ',');

		  	$query  = "DELETE FROM {$base} WHERE id NOT IN ( {$test} );	";
				$CFG->DB->query($query);
			}

			protected function printHello($date)
	    {
				global $CFG;
				set_time_limit(600);

				$json_daily_file = 'documents/buch/nomenclature.json';
		 		$result = json_decode(file_get_contents($json_daily_file));
				$data = resizeArray($result->ПродажиОбороты, 'Склад');

				//Если даннные поучены и есть с чем работать
				if(count($data) > 0)
				{
					$nomenclature_sklad = '';	//Склад
					$nomenclature_subdivision = ''; //Подразделение
					foreach($data as $res)
					{
						$nomenclature_sklad .= "('".htmlspecialchars($res->Склад, ENT_QUOTES)."'),".PHP_EOL;
						$nomenclature_subdivision .= "('".htmlspecialchars($res->Подразделение, ENT_QUOTES)."'),".PHP_EOL;
						$nomenclature_provider .= "('".htmlspecialchars($res->Поставщик, ENT_QUOTES)."', '".htmlspecialchars($res->БИНПоставщика, ENT_QUOTES)."'),".PHP_EOL;
					}

					if( $nomenclature_subdivision != '')
					{
						$nomenclature_subdivision = substr($nomenclature_subdivision, 0, -3);
						$CFG->DB->query("INSERT INTO my_data_1c_nomenclature_sklad (name) VALUES {$nomenclature_subdivision} ON DUPLICATE KEY UPDATE name = VALUES(name); "); //Записываем название подраздилений
						$this->DeleteNomenclature('my_data_1c_nomenclature_subdivision'); //Удаляем дубликаты
						$subdivision_id = getSQLRowO("SELECT id FROM my_data_1c_nomenclature_subdivision WHERE visible = 1 order by id DESC	"); // Получаем последний id
						$AIsubdivision = $subdivision_id->id+1;
						$CFG->DB->query("ALTER TABLE my_data_1c_nomenclature_subdivision AUTO_INCREMENT = {$AIsubdivision} "); //Перезаписываем AUTO_INCREMENT у базы
					}

					if( $nomenclature_sklad != '')
					{
						$nomenclature_sklad = substr($nomenclature_sklad, 0, -3);
						$CFG->DB->query("INSERT INTO my_data_1c_nomenclature_sklad (name) VALUES {$nomenclature_sklad} ON DUPLICATE KEY UPDATE name = VALUES(name); ");
						$this->DeleteNomenclature('my_data_1c_nomenclature_sklad');  //Удаляем дубликаты
						$sklad_id = getSQLRowO("SELECT id FROM my_data_1c_nomenclature_sklad WHERE visible = 1 order by id DESC	"); // Получаем последний id
						$AIsklad = $sklad_id->id+1;
						$CFG->DB->query("ALTER TABLE my_data_1c_nomenclature_sklad AUTO_INCREMENT = {$AIsklad} "); //Перезаписываем AUTO_INCREMENT у базы
					}
					
					if( $nomenclature_provider != '')
					{
						$nomenclature_provider = substr($nomenclature_provider, 0, -3);
						$CFG->DB->query("INSERT INTO my_data_1c_provider (name, bin) VALUES {$nomenclature_provider} ON DUPLICATE KEY UPDATE name = VALUES(name), bin = VALUES(bin); ");
						$this->DeleteNomenclature('my_data_1c_provider');  //Удаляем дубликаты
						$provider_id = getSQLRowO("SELECT id FROM my_data_1c_provider WHERE visible = 1 order by id DESC	"); // Получаем последний id
						$AIprovider = $provider_id->id+1;
						$CFG->DB->query("ALTER TABLE my_data_1c_provider AUTO_INCREMENT = {$AIprovider} "); //Перезаписываем AUTO_INCREMENT у базы
					}


					exit;


					 $cdate = date('01-01-d 00:00:00');

					 $CFG->DB->query("DELETE FROM my_data_1c_nomenclature WHERE cdate >= '{$cdate}'");

					 $CFG->DB->query("TRUNCATE TABLE my_data_1c_nomenclature_sklad");
					 $CFG->DB->query("TRUNCATE TABLE my_data_1c_nomenclature_subdivision");

					 foreach($data as $res)
		 			 {
						 $sql = "INSERT INTO my_data_1c_nomenclature_sklad (name) VALUES ('{$res->Склад}')";
						 $CFG->DB->query($sql);

						 $sql = "INSERT INTO my_data_1c_nomenclature_subdivision (name) VALUES ('{$res->Подразделение}')";
						 $CFG->DB->query($sql);
					 }

					 /*	 Удаляем дубликаты групп		*/
					// $CFG->DB->query("ALTER IGNORE TABLE `my_data_1c_nomenclature_sklad` ADD UNIQUE INDEX(name, id ASC) ;");


					 foreach($data as $res)
		 			 {
						 	if(
								$res->Подразделение == 'Временно возвратная' ||
								$res->Подразделение == 'Прочие услуги' ||
								$res->Подразделение == 'аренда' ||
								$res->Подразделение == 'прокат' ) continue;

						$autor = explode(" ", $res->Автор);
						$autor = $autor[0].' '.$autor[1];
						$user = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}users WHERE name LIKE '%{$autor}%'	");

						$warehouse_id = getSQLRowO("SELECT id FROM my_data_1c_nomenclature_sklad WHERE name LIKE '%{$res->Склад}%'	");
						$subdivision_id = getSQLRowO("SELECT id FROM my_data_1c_nomenclature_subdivision WHERE name LIKE '%{$res->Подразделение}%'	");

						if($res->ХарактеристикаНоменклатуры != '') {$har = ' '.$res->ХарактеристикаНоменклатуры; } else {$har = '';}

						$name = mysql_real_escape_string($res->ПолноеНаименование.$har);
						$counts = $res->КоличествоОборот;
						$price = $res->СтоимостьОборот;
						$id_product = $res->ID;
						$buyer = mysql_real_escape_string($res->Контрагент);
						$purchase = $res->ЗакупочнаяЦена;
						$buch = $res->ОтражатьВБухгалтерскомУчете;
						$nds = $res->НДС;
						$id_prodazha = $res->Id_Продажи;
						$IM_P = $res->ИнтернетМагазин;

						$date = new DateTime($res->Период);
			 		 	$cdate = $date->format('Y-m-d H:i:s');

						$sql = "REPLACE INTO my_data_1c_nomenclature (IM_P, id_product,name, user_id, price, count, buyer, cdate, warehouse_id, purchase, subdivision_id, buch, nds, id_prodazha) 	VALUES ('{$IM_P}', '{$id_product}','{$name}', '{$user->id}', '{$price}', '{$counts}', '{$buyer}', '{$cdate}', '{$warehouse_id->id}', '{$purchase}', '{$subdivision_id->id}', '{$buch}', '{$nds}', '{$id_prodazha}')";
						$CFG->DB->query($sql);
					 }

			 }

				 /*

				 $text_arr[1] = 'Премного признательности';
				 $text_arr[2] = 'В наши трудные времена премного благодарности';
				 $text_arr[3] = 'Большое спасибо';
				 $text_arr[4] = 'Очень признательны';



				 					$start = date('Y-m-d').' 00:00:00';
				 					$off = date('Y-m-d').' 23:59:59';
				 					$respon = getSQLArrayO("SELECT * FROM my_data_1c_nomenclature  WHERE (cdate >= '{$start}') AND (cdate <= '{$off}') AND IM_P = 0 ");
									foreach ($respon  as $value)
									{
										$id = explode("*", $value->buyer);
										if($id[1] > 0)
										{
													$resp =  SelectDataNameArray('news', '*', $id[1]);
													$client_id = explode(",", $resp->client_id);
													foreach ($client_id as $valu)
													{
																if($valu > 0)
																{
																	$arr[]= $valu;
																}
													}
										}
									}

									$result = array_unique($arr);

									foreach ($result as $variable)
									{
										$resp =  SelectDataNameArray('face', '*', $variable);
										$name = $resp->name;
										$whatsapp = $resp->whatsapp;
										$cdate = sqlDateNow();

										$real = getSQLRowO("SELECT id FROM my_data_1c_nomenclature_sms WHERE mobile = '{$whatsapp}' AND (cdate >= '{$start}') AND (cdate <= '{$off}') 	");
										if($real->id > 0) continue;

										if($name !='') {$last = ' '.$name.'!';} else {$last='!';}

										$text .= 'Здравствуйте'.$last.PHP_EOL;
										$text .=  $text_arr[rand(1, 4)].' Вам за покупки в нашей компании.'.PHP_EOL.PHP_EOL;
										$text .= 'Напоминаю Вам о возможности получения скидки 6% в нашей компании при заказе через интернет магазин www.forsign.kz'.PHP_EOL;
										$text .= 'с/у, коммерческий директор Замятина Анастасия'.PHP_EOL;

										$sql = "REPLACE INTO my_data_1c_nomenclature_sms (name, mobile, cdate, visible, text) 	VALUES ('{$name}', '{$whatsapp}', '{$cdate}', 0, '{$text}')";
										$CFG->DB->query($sql);
										$text = '';
									}



									$respon = getSQLArrayO("SELECT * FROM my_data_1c_nomenclature  WHERE (cdate >= '{$start}') AND (cdate <= '{$off}') AND IM_P = 1 ");
									foreach ($respon  as $value)
									{
										$id = explode("*", $value->buyer);
										if($id[1] > 0)
										{
													$resp =  SelectDataNameArray('news', '*', $id[1]);
													$client_id = explode(",", $resp->client_id);
													foreach ($client_id as $valu)
													{
																if($valu > 0)
																{
																	$arrs[]= $valu;
																}
													}
										}
									}

									$result = array_unique($arrs);

									foreach ($result as $variable)
									{
										$resp =  SelectDataNameArray('face', '*', $variable);
										$name = $resp->name;
										$whatsapp = $resp->whatsapp;
										$cdate = sqlDateNow();

										$real = getSQLRowO("SELECT id FROM my_data_1c_nomenclature_sms WHERE mobile = '{$whatsapp}' AND (cdate >= '{$start}') AND (cdate <= '{$off}') 	");
										if($real->id > 0) continue;

										if($name !='') {$last = ' '.$name.'!';} else {$last='!';}

										$text .= 'Здравствуйте'.$last.PHP_EOL;
										$text .=  $text_arr[rand(1, 4)].' Вам за покупки в нашей компании www.forsign.kz.'.PHP_EOL.PHP_EOL;
										$text .= 'с/у, коммерческий директор Замятина Анастасия'.PHP_EOL;

										$sql = "REPLACE INTO my_data_1c_nomenclature_sms (name, mobile, cdate, visible, text) 	VALUES ('{$name}', '{$whatsapp}', '{$cdate}', 0, '{$text}')";
										$CFG->DB->query($sql);
										$text = '';
									}
									*/

	    }


			function buyer($text)
			{
				$name = explode("*", $text);

				if($name[1] != '')
					return '<a target="_blank" style=" color:#0000A3;" href="/search/*'.$name[1].'/">'.$text.'</a>';
				else
					return $text;
			}



						function Motivation($month, $user_id)
						{

								$start = '2020-'.$month.'-00 00:00:00';
								$off = '2020-'.$month.'-31 23:59:59';

								$respon = getSQLArrayO("SELECT SUM(price*count),SUM(purchase*count) FROM my_data_1c_nomenclature  WHERE user_id = '{$user_id}' 	 AND (cdate >= '{$start}') AND (cdate <= '{$off}') ");

								$price = $respon[0]->{'SUM(price*count)'};

								return number_sum($price);
						}


						function MotivationDot($month, $user_id)
						{

								$start = '2020-'.$month.'-00 00:00:00';
								$off = '2020-'.$month.'-31 23:59:59';

								$respon = getSQLArrayO("SELECT SUM(price*count),SUM(purchase*count) FROM my_data_1c_nomenclature  WHERE user_id = '{$user_id}' AND (cdate >= '{$start}') AND (cdate <= '{$off}') 	");

								$price = $respon[0]->{'SUM(price*count)'};
								$purchase = $respon[0]->{'SUM(purchase*count)'};

								$real = ($price*1)-($purchase*1);

								return number_sum($real/100);
						}


						public function MotivationNomenclaturePlus($id_user, $text, $type, $count, $primiya, $cdate, $type_record=3)
						{
								global $CFG;

								/* шаг 1. Дастоем номер записи примирования */
								$o = getSQLRowO("SELECT taks_id FROM my_users WHERE id='{$id_user}'; ");

								$p = getSQLRowO("SELECT * FROM my_news WHERE parent_id='{$o->taks_id}' AND visible = 1 AND name_company LIKE '%Автоначисление с 1С%' ; ");

								if($p->id > 0)
								{
									 $record_id = $p->id;
								}
								else
								{
									$sql = "INSERT INTO my_news (page_id, parent_id, cdate, manager_id, name_company, visible) VALUES (1000, '{$o->taks_id}', '{$cdate}', {$id_user}, 'Автоначисление с 1С', 1); ";
									$CFG->DB->query($sql);
									$record_id = mysql_insert_id();
								}

								/*	шаг 2. Записываем коммент	в запись полученную из запроса выше*/
								$sql = "INSERT INTO my_comments (page_id, parent_id, user_id, text, cdate, visible) VALUES ('{$record_id}', 1000, {$id_user}, '{$text}', '{$cdate}', 1); ";

								$CFG->DB->query($sql);
								$comment_id = mysql_insert_id();
								$comment_id.PHP_EOL;

								/*	шаг 3. Записываем начисление  суммы на человека что приходит в $id_user */
								$sql = "INSERT INTO {$type} (user_id, manager_id, count, page_id, coment_id, type, cdate, visible) VALUES ('{$id_user}', '{$id_user}', {$count}, '{$o->taks_id}', '{$comment_id}', {$type_record}, '{$cdate}', 1); ";
								$CFG->DB->query($sql);
								$money_id = mysql_insert_id();

								$query  = "UPDATE my_comments SET  {$primiya}  = ',{$money_id}'  WHERE id='{$comment_id}'";
								$CFG->DB->query($query);

								return true;
						}


						function MotivationNomenclature($cdate)
						{
								global $CFG;


								$start = $cdate.' 00:00:00';
								$off	 = $cdate.' 23:59:59';
								$respon = getSQLArrayO("SELECT * FROM my_data_1c_nomenclature  WHERE visible = 1 AND (cdate >= '{$start}') AND (cdate <= '{$off}') order by cdate ASC ");

								$z = getSQLRowO("SELECT cdate FROM my_money_list WHERE visible =1 AND type in(3,4)  AND (cdate >= '{$start}') AND (cdate <= '{$off}') order by id DESC; ");

								if($z->cdate ==''){ $time = '2020-02-01 00:00:00';} else {$time = $z->cdate;}
								$date = new DateTime($time);
								$last = $date->getTimestamp();

								foreach($respon as $value)
								{
									$dates = new DateTime($value->cdate);
									$first = $dates->getTimestamp();

									if($last >= $first) continue;

										$dir = ($value->price*1-$value->purchase*1)*$value->count;
										$itogo = ($value->price*1)*$value->count;

										// Если реализация по БХ - с НДС
										if($value->buch > 0 && $value->user_id > 0)
										{
												$procent = '1%';
												$reliz = 'БУ';

												$html .= 'Продажа по '.$reliz.', начисление '.$procent.' от ВП.'.PHP_EOL;
												$html .= $value->name.' ('.$value->count.' шт.)'.PHP_EOL;
												$html .= 'Цена за шт: '.$value->price.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
												$html .= 'Цена за шт закуп: '.$value->purchase.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
												$html .= 'Итого: '.$itogo.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
												$html .= 'Начисление: '.($dir/100*1).' '.$CFG->USER->USER_CURRENCY.PHP_EOL;

												/*	Если сумма дохода от реализации больше 0, то делаем начисление + 1% от дохода	*/
												if($dir > 0)
												{
														$this->MotivationNomenclaturePlus($value->user_id, $html, 'my_money_list', $dir/100*1, 'premiumplus', $value->cdate);

														//Начисление товарному босу			//Подключаем руководителя продаж
														$boss = getSQLArrayO("SELECT * FROM my_data_1c_nomenclature_ruk WHERE id_product = '{$value->id_product}'; ");
														foreach ($boss as $variable)
														{
																$boss_html .= 'Продажа по '.$reliz.', начисление '.$variable->bu.'% от ВП, для руководителя продаж'.PHP_EOL;
																$boss_html .= $value->name.' ('.$value->count.' шт.)'.PHP_EOL;
																$boss_html .= 'Цена за шт: '.$value->price.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
																$boss_html .= 'Цена за шт закуп: '.$value->purchase.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
																$boss_html .= 'Итого: '.$itogo.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
																$boss_html .= 'Начисление: '.($dir/100*$variable->bu).' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
																if($variable->user_id > 0 ) {$this->MotivationNomenclaturePlus($variable->user_id, $boss_html, 'my_money_list', $dir/100*$variable->bu, 'premiumplus', $value->cdate, 4); }
																$boss_html = '';
														}


												}
												elseif($dir < 0 )
												{
														$this->MotivationNomenclaturePlus($value->user_id, $html, 'my_money_minus_list', substr($dir/100*1, 1), 'premiumminus', $value->cdate);

														//Начисление товарному босу			//Подключаем руководителя продаж
														$boss = getSQLArrayO("SELECT * FROM my_data_1c_nomenclature_ruk WHERE id_product = '{$value->id_product}'; ");
														foreach ($boss as $variable)
														{
																$boss_html .= 'Продажа по '.$reliz.', начисление '.$variable->bu.'% от ВП, для руководителя продаж'.PHP_EOL;
																$boss_html .= $value->name.' ('.$value->count.' шт.)'.PHP_EOL;
																$boss_html .= 'Цена за шт: '.$value->price.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
																$boss_html .= 'Цена за шт закуп: '.$value->purchase.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
																$boss_html .= 'Итого: '.$itogo.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
																$boss_html .= 'Начисление: '.($dir/100*$variable->bu).' '.$CFG->USER->USER_CURRENCY.PHP_EOL;

																if($variable->user_id > 0 ) {$this->MotivationNomenclaturePlus($variable->user_id, $boss_html, 'my_money_minus_list', substr($dir/100*$variable->bu, 1), 'premiumplus', $value->cdate, 4); }

																$boss_html = '';
														}
												}

										} // Если реализация по УУ
										elseif($value->buch == 0 && $value->user_id > 0)
										{

											$procent = '2%';
											$reliz = 'УУ';

											$html .= 'Продажа по '.$reliz.', начисление '.$procent.' от ВП.'.PHP_EOL;
											$html .= $value->name.' ('.$value->count.' шт.)'.PHP_EOL;
											$html .= 'Цена за шт: '.$value->price.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
											$html .= 'Цена за шт закуп: '.$value->purchase.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
											$html .= 'Итого: '.$itogo.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
											$html .= 'Начисление: '.($dir/100*2).' '.$CFG->USER->USER_CURRENCY.PHP_EOL;


											if($dir > 0)
											{
												//Начисление менеджеру кто сделал продажу
												$this->MotivationNomenclaturePlus($value->user_id, $html, 'my_money_list', $dir/100*2, 'premiumplus', $value->cdate);

												//Начисление товарному босу			//Подключаем руководителя продаж
												$boss = getSQLArrayO("SELECT * FROM my_data_1c_nomenclature_ruk WHERE id_product = '{$value->id_product}'; ");
												foreach ($boss as $variable)
												{
														$boss_html .= 'Продажа по '.$reliz.', начисление '.$variable->uu.'% от ВП, для руководителя продаж'.PHP_EOL;
														$boss_html .= $value->name.' ('.$value->count.' шт.)'.PHP_EOL;
														$boss_html .= 'Цена за шт: '.$value->price.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
														$boss_html .= 'Цена за шт закуп: '.$value->purchase.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
														$boss_html .= 'Итого: '.$itogo.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
														$boss_html .= 'Начисление: '.($dir/100*$variable->uu).' '.$CFG->USER->USER_CURRENCY.PHP_EOL;

														if($variable->user_id > 0 ) {$this->MotivationNomenclaturePlus($variable->user_id, $boss_html, 'my_money_list', $dir/100*$variable->uu, 'premiumplus', $value->cdate, 4); }

														$boss_html = '';
												}
											}
											elseif($dir < 0 )
											{
												$this->MotivationNomenclaturePlus($value->user_id, $html, 'my_money_minus_list', substr($dir/100*2, 1), 'premiumminus', $value->cdate);

												//Начисление товарному босу			//Подключаем руководителя продаж
												$boss = getSQLArrayO("SELECT * FROM my_data_1c_nomenclature_ruk WHERE id_product = '{$value->id_product}'; ");
												foreach ($boss as $variable)
												{
														$boss_html .= 'Продажа по '.$reliz.', начисление '.$variable->uu.'% от ВП, для руководителя продаж'.PHP_EOL;
														$boss_html .= $value->name.' ('.$value->count.' шт.)'.PHP_EOL;
														$boss_html .= 'Цена за шт: '.$value->price.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
														$boss_html .= 'Цена за шт закуп: '.$value->purchase.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
														$boss_html .= 'Итого: '.$itogo.' '.$CFG->USER->USER_CURRENCY.PHP_EOL;
														$boss_html .= 'Начисление: '.($dir/100*$variable->uu).' '.$CFG->USER->USER_CURRENCY.PHP_EOL;

														if($variable->user_id > 0 ) {$this->MotivationNomenclaturePlus($variable->user_id, $boss_html, 'my_money_minus_list', substr($dir/100*$variable->uu, 1), 'premiumplus', $value->cdate, 4); }

														$boss_html = '';
												}
											}

										}

								$html = '';
								$boss_html = '';
								//exit;
								}
								exit;
						}

						function getImg($id)
						{
							$filename =  $_SERVER['DOCUMENT_ROOT'].'/documents/filebox/Я - Товар системный - не переименовывать не удалять !!!!/'.$id.'.jpg';
							$filename5 =  '/documents/filebox/Я - Товар системный - не переименовывать не удалять !!!!/'.$id.'.jpg';
							$filename2 = iconv('utf-8', 'windows-1251', $filename);

							if (file_exists($filename2))
							{
							    echo '<a href="'.$filename5.'"  class="quickbox" data-fancybox="images" ><img src="/tpl/img/new/img.png"></a>';

							}

						}

						function getListAnalysis()
						{
							$sql = "	SELECT  * FROM my_data_1c_analysis WHERE visible = 1 order by id DESC ";
						 	return getSQLArrayO($sql);
						}



						function getListAnalysisinsert($text)
						{
							global $CFG;
							if($text != '')
							{
								$query = "INSERT INTO my_data_1c_analysis (name, visible) VALUES ('{$text}', 1)";
								$CFG->DB->query($query);
								echo mysql_insert_id();
							}
						}



						function getListAnalysisDel($id)
						{
							global $CFG;

							if($id > 0)
							{
								$query  = "DELETE FROM my_data_1c_analysis WHERE id='{$id}'";
								if($CFG->DB->query($query))
								{
									echo 1;
								}
								else
								{
									echo 0;
								}
							}
						}


						function getCountAnalysis($text)
						{
							global $CFG;

							$res = getSQLArrayO(" SELECT id FROM my_data_1c WHERE name LIKE '%{$text}%'  and visible = 1 GROUP BY name  ");
							$res_resl = getSQLArrayO(" SELECT id FROM my_data_1c WHERE name LIKE '%{$text}%' AND count >= 1 and visible = 1 GROUP BY name  ");

							$span = '<span style="color:#007F0E">'.count($res_resl).'</span> / <span style="color:#FF0000">'.count($res).'</span> ';

							return count($res_resl);
						}

						function getPCAnalysis($text)
						{
							global $CFG;

							$res_resl = getSQLArrayO(" SELECT id, id_product FROM my_data_1c WHERE name LIKE '%{$text}%' AND count >= 1 and visible = 1 GROUP BY name  ");

							//print_r($res_resl);

							return $span;
						}

						function getRealAnalysis($text, $year = 0, $month = 0)
						{
							global $CFG;

							$res = getSQLArrayO(" SELECT id_product  FROM my_data_1c WHERE name LIKE '%{$text}%' and visible = 1 GROUP BY id_product  ");

							foreach($res as $sp)
							{
									$data .= "'".$sp->id_product."',";
							}
							$id_product = trim($data, ',');

							if($id_product !='')
							{
								if($month > 0 && $year > 0)
								{
									$cdate = $year.'-'.$month.'-01 00:00:00';
									$day = new DateTime(date('Y').'-'.$month.'-01');
									$cdateNext = $year.'-'.$month.'-'.$day->format('t').' 23:59:59';
								}
								else
								{
									$cdate = date('Y-m-01 00:00:00');
									$cdateNext = date('Y-m-d 23:59:59');
								}

								$sql = getSQLRowO("SELECT SUM(price*count) FROM my_data_1c_nomenclature  WHERE visible = 1 AND name LIKE '%{$text}%' AND (cdate >= '{$cdate}') AND (cdate <= '{$cdateNext}') ");

								return $sql->{'SUM(price*count)'};
							}
						}


						function getWPAnalysis($text,  $year = 0, $month = 0)
						{
							global $CFG;
							$res = getSQLArrayO(" SELECT id_product  FROM my_data_1c WHERE name LIKE '%{$text}%' and visible = 1 GROUP BY id_product  ");

							foreach($res as $sp)
							{
									$data .= "'".$sp->id_product."',";
							}
							$id_product = trim($data, ',');

							if($id_product !='')
							{
								if($month > 0)
								{
									$cdate =$year.'-'.$month.'-01 00:00:00';
									$day = new DateTime(date('Y').'-'.$month.'-01');
									$cdateNext = $year.'-'.$month.'-'.$day->format('t').' 23:59:59';
								}
								else
								{
									$cdate = date('Y-m-01 00:00:00');
									$cdateNext = date('Y-m-d 23:59:59');
								}
								$sql = getSQLRowO("SELECT SUM(price*count-purchase*count) FROM my_data_1c_nomenclature  WHERE visible = 1 AND name LIKE '%{$text}%' AND (cdate >= '{$cdate}') AND (cdate <= '{$cdateNext}')  ");
								return $sql->{'SUM(price*count-purchase*count)'};
							}
						}
	function Audit($data)
	{
		global $CFG;

		$id_product = $data['id'];
		$user_id = $CFG->USER->USER_ID;
		$cdate = time();

		$sql = getSQLRowO("SELECT * FROM my_data_1c_audit  WHERE product_id = '{$id_product}' ");

		if( $sql->product_id  == $id_product && $sql->user_id  == $user_id )
		{
			$response = json_encode(array('product_id' => $id_product, 'text' => 'Вы уже отметили этот товар!', 'returns' => 0	));
			echo $response;		exit;
		}
		else
		{
			$sql =  "INSERT INTO my_data_1c_audit (cdate, user_id, product_id ) VALUES ('{$cdate}', '{$user_id}', '{$id_product}') " ;
 			$CFG->DB->query($sql);

			$response = json_encode(array('product_id' => $id_product, 'text' => 'Спасибо! :)', 'returns' => 1	));
			echo $response;		exit;
		}
	}


	function Card($data)
	{
		global $CFG;

		$id_product = $data['id'];
		$vals = (int)$data['vals'];
		$user_id = $CFG->USER->USER_ID;

		$sql = getSQLRowO("SELECT * FROM my_data_1c_card  WHERE id_product = '{$id_product}' AND user_id = '{$user_id}' ");
		if($sql->id > 0)
		{
				echo 'Такой товар уже добавлен в корину заказа!';
		}
		else
		{
				$sql =  $CFG->DB->query("INSERT INTO my_data_1c_card (user_id, id_product, vals ) VALUES ( '{$user_id}', '{$id_product}', '{$vals}') ");
				echo 'Товар добавлен в корину заказа!';
		}
	}


	function CardList($user)
	{
		global $CFG;

		$sql = getSQLArrayO("SELECT my_data_1c.vendor, my_data_1c.name, my_data_1c_card.* FROM my_data_1c_card LEFT JOIN my_data_1c ON my_data_1c.id_product=my_data_1c_card.id_product WHERE my_data_1c_card.user_id = '{$user}' GROUP BY my_data_1c.vendor ");

		return $sql;
	}


	function Сashbox($cdate)
	{
		$url = "http://192.168.1.110:8081/fc_utp/hs/api6/Money/ostatok?date=".$cdate;
		$login = 'webservice';
		$password = 'AsdfRewq!';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
		$result = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($result, true);
		if(count($data['ОстаткиДенежныхСредств']) > 0)
		{
			return $data['ОстаткиДенежныхСредств'];
		}
	}

	function ExportExel($data,  $item)
	{
		global $CFG;

		set_time_limit(600);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("forSign Kazakhstan");
		$objPHPExcel->getProperties()->setLastModifiedBy("forSign Kazakhstan");
		$objPHPExcel->getProperties()->setTitle("XLSX Document");
		$objPHPExcel->getProperties()->setSubject("Export XLSX Document");
		$objPHPExcel->getActiveSheet()->getStyle('A8:F8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F98383');
		$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);

		$objPHPExcel->setActiveSheetIndex(0);
		// Объединение ячеек в строке
		$objPHPExcel->getActiveSheet()->mergeCells("B1:F1");
		$objPHPExcel->getActiveSheet()->setCellValue("B1", "B1:F1");
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', $item['cdate']['data']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', $item['cdate']['name']);
		// Объединение ячеек в строке
		$objPHPExcel->getActiveSheet()->mergeCells("B3:F3");
		$objPHPExcel->getActiveSheet()->setCellValue("B3", "B3:F3");
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', $item['search']['data']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B3', $item['search']['name']);
		// Объединение ячеек в строке
		$objPHPExcel->getActiveSheet()->mergeCells("B4:F4");
		$objPHPExcel->getActiveSheet()->setCellValue("B4", "B4:F4");
		$objPHPExcel->getActiveSheet()->SetCellValue('A4', $item['warehouse']['data']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B4', $item['warehouse']['name']);
		// Объединение ячеек в строке
		$objPHPExcel->getActiveSheet()->mergeCells("B5:F5");
		$objPHPExcel->getActiveSheet()->setCellValue("B5", "B5:F5");
		$objPHPExcel->getActiveSheet()->SetCellValue('A5', $item['sklad']['data']);
		$objPHPExcel->getActiveSheet()->SetCellValue('B5', $item['sklad']['name']);

		$objPHPExcel->getActiveSheet()->SetCellValue('A8', 'Наименование');
		$objPHPExcel->getActiveSheet()->SetCellValue('B8', 'Кол-во');
		$objPHPExcel->getActiveSheet()->SetCellValue('C8', 'Цена розница');
		$objPHPExcel->getActiveSheet()->SetCellValue('D8', 'Сумма розница');
		$objPHPExcel->getActiveSheet()->SetCellValue('E8', 'Цена закуп.');
		$objPHPExcel->getActiveSheet()->SetCellValue('F8', 'Сумма закуп.');

		$cnt = 9;
		foreach ($data as $value)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", $value->nameIM.' '.$value->optionIM);
			$objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", $value->counts); $countS[] = $value->counts;
			$objPHPExcel->getActiveSheet()->SetCellValue("C$cnt", $value->price); $priceS[] = $value->price;
			$objPHPExcel->getActiveSheet()->SetCellValue("D$cnt", $value->prices); $pricesS[] = $value->prices;
			$objPHPExcel->getActiveSheet()->SetCellValue("E$cnt", $value->total); $totalS[] = $value->total;
			$objPHPExcel->getActiveSheet()->SetCellValue("F$cnt", $value->totals); $totalsS[] = $value->totals;
			$cnt ++;
		}

		$mul = $cnt+2;
		$objPHPExcel->getActiveSheet()->SetCellValue("A$mul", 'Итого');
		$objPHPExcel->getActiveSheet()->SetCellValue("B$mul", array_sum($countS));
		$objPHPExcel->getActiveSheet()->SetCellValue("C$mul", array_sum($priceS));
		$objPHPExcel->getActiveSheet()->SetCellValue("D$mul", array_sum($pricesS));
		$objPHPExcel->getActiveSheet()->SetCellValue("E$mul", array_sum($totalS));
		$objPHPExcel->getActiveSheet()->SetCellValue("F$mul", array_sum($totalsS));

		$objPHPExcel->getActiveSheet()->setTitle('Наличный склад');
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$name = date("m_y").".xlsx";

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
