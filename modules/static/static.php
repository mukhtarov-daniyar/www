 <?

$NEWS = new News();

			$times[0] = '00:00';	$times[1] = '01:00';	$times[2] = '02:00';	$times[3] = '03:00';	$times[4] = '04:00';	$times[5] = '05:00';	$times[6] = '06:00';	$times[7] = '07:00';	$times[8] = '08:00';	$times[9] = '09:00';	$times[10] = '10:00';	$times[11] = '10:00';	$times[12] = '12:00';	$times[13] = '13:00';	$times[14] = '14:00';	$times[15] = '15:00';	$times[16] = '16:00';	$times[17] = '17:00';	$times[18] = '18:00';	$times[19] = '19:00';	$times[20] = '20:00';	$times[21] = '21:00';	$times[22] = '22:00';	$times[23] = '23:00';


 switch($CFG->_GET_PARAMS[0])
 {



	 case 'real_clientfgfg' :

	 $url = "http://192.168.1.122:8081/ledl/hs/api/data/get?date=01.10.2022";
	 $login = 'webservice';
	 $password = 'AsdfRewq!';
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_URL,$url);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	 curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	 curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
	 $result = curl_exec($ch);
	 curl_close($ch);

	 $entry = iconv('Windows-1251', 'UTF-8', $result);

	 if( count(json_decode($entry)) > 0)
	 {
					$result = json_decode($entry);

				$cnt = 0;
				foreach ($result as $value)
				{



						$namber = explode("*", $value->Сделка);
						$arr[(int)$namber[1]]->id = (int)$namber[1];
						$arr[(int)$namber[1]]->Сделка = $value->Сделка;
						$arr[(int)$namber[1]]->ДанныеПоПоступлению = $value->ДанныеПоПоступлению;
						$cnt ++;
				}

				print_r(	$arr);					exit;


	 }



	  exit;
	 break;









	 case 'real_client' :

	 	exit;
	 	$sql = getSQLArrayO("SELECT buyer , COUNT(*) c FROM my_data_1c_nomenclature GROUP BY buyer HAVING c > 1 ");

		foreach ($sql as $value)
		{

			$ln = explode("*", $value->buyer);

			if($ln[1] != '')
			{
				$str = str_replace(' ', '', $ln[1]);
				$strS .= $str.',';
			}
		}

		$strS = trim($strS, ',');

		$sql = getSQLArrayO("SELECT id, history FROM my_news WHERE  id IN ({$strS})");

		foreach ($sql as $value)
		{
				$up_html = $value->history.PHP_EOL.PHP_EOL.'ППУНК';

				$up_html = $CFG->DB->escape($up_html);

				$CFG->DB->query("UPDATE my_news SET history = '{$up_html}'  WHERE id='{$value->id}' ");
		}

 	 	exit;
 	break;







			 /* Убирать вожможность удалять записи у менеджеров раз в 5 дней */
		 	case 'fgsfgsfgsfdgsdfg' :

				$sql = getSQLArrayO("SELECT * FROM my_news WHERE  page_id = 1000 AND data_one = 0 order by cdate limit 100 ");

				foreach ($sql as $value)
				{
					$test = getSQLRowO(" SELECT page_id FROM my_news WHERE id = '{$value->parent_id}' ");

		print_r($test);

					if($test->page_id == '') continue;

					$CFG->DB->query("UPDATE my_news SET data_one = 1, deal_parent_id = {$test->page_id}  WHERE id='{$value->id}' ");
				}

				echo count($sql);

		 		exit;
		 	break;


		case 'edrive_add_user' :

			if( isset($_POST['login']) )
			{
					$login = $_POST['login'];
					$name = $_POST['name'];
					$surname = $_POST['surname'];

					$WP = new Whatsapp();
					$login = $WP->real_tel(preg_replace('/[^0-9]/', '', $login));
					$res = getSQLRowO(" SELECT * FROM my_face WHERE `mobile` LIKE '%".$login."%' AND `visible` = 1 ");
					if($res)
					{
						if($res->info !='') { $info = $res->info.PHP_EOL.'Зарегистрировался на сайте edrive.kz';}else { $info = 'Зарегистрировался на сайте edrive.kz '.date('Y-m-d H:i'); }
						if($res->marketing_id !='') { $marketing_id = $res->marketing_id.',106'; } else { $marketing_id = '106';}

						$CFG->DB->query("UPDATE my_face SET marketing_id = '{$marketing_id}', info = '{$info}'  WHERE id = '{$res->id}' ");
					}
					else
					{
							//Содаем новое лицо
							$CFG->DB->query( "INSERT INTO my_face (page_id, manager_id, marketing_id, city_id, mobile, whatsapp, name, name_other, info, cdate, edate, floor)	VALUES ('1012', '85', '106', '201', '{$login}', '{$login}', '{$name}', '".$name.' '.$surname."', 'Зарегистрировался на сайте edrive.kz ".date('Y-m-d H:i')."', '".time()."' , '".time()."', 0 ) 	");
					}
			}

			exit;
		break;

			/* Отправить на воцап VCF */
		 case 'send_VCF' :

		 	$id = $_POST['id'];
			$mobile = $_POST['mobile'];

			$sql = getSQLRowO("SELECT *  FROM my_face WHERE id = '{$id}' AND visible = 1 ");

			$ln = explode(" ", $sql->name_other);

			$name = $sql->name.' '.$ln[1];

			$vcard .= 'BEGIN:VCARD\n';
			$vcard .= 'VERSION:3.0\n';
			$vcard .= 'N;CHARSET=UTF-8:;'.$name.';;;\n';
			$vcard .= 'FN;CHARSET=UTF-8:'.$name.'\n';
			$vcard .= 'EMAIL;CHARSET=UTF-8;type=HOME,INTERNET:'.$sql->email.'\n';
			$vcard .= 'TEL;CELL;waid='.preg_replace('/[^0-9]/', '', $sql->mobile).':'. $sql->mobile.'\n';
			if($sql->international !='') {$vcard .= 'TEL;CELL;waid='.preg_replace('/[^0-9]/', '', $sql->international).':'. $sql->international.'\n'; }
			$vcard .= 'END:VCARD';


			$wp = getSQLRowO("SELECT * FROM my_tmp_whatsapp_namber WHERE  namber = '+77755475012' AND visible = 1   ");


			$data = ['phone' => $mobile, 'vcard'=> $vcard];

//print_r(	$data ); exit;

			$url = 'https://api.chat-api.com/instance'.$wp->wp_id.'/sendVCard?token='.$wp->token;
			$json = json_encode($data);
			$options = stream_context_create(['http' => [
			'method'  => 'POST',
			'header'  => 'Content-type: application/json',
			'content' => $json]]);
			$result = file_get_contents($url, false, $options);
			$result = json_decode($result);


			if($result->sent == 1)
			{
				echo 1;
			}
			else {
				echo 0;
			}

			 exit;
		 break;


		 /* Поиск в шапке */
		case 'inputQ' :
			$buyer = $_POST['buyer'];
			$buyer = str_replace("'", '', $buyer);
			$namber = explode("*", $buyer);

			// Если это поиск по номеру записи с *
			if(is_numeric($namber[1]) > 0 )
			{	$nambers = (int)$namber[1];
				$sql = getSQLRowO("SELECT * FROM my_news WHERE id={$nambers} AND visible = 1");
				if($sql->id > 0)
				{
						echo '<ul>';
						echo '<li><a href="'.getFullXCodeByCommentsId($sql->page_id).$sql->id.'">'.$sql->name_company.' <span class="badge">'.getFullXCodeByCommentsName($sql->page_id).'</span></a></li>';
						echo '</ul>';
				}
				else
				{
					echo '<div class="mask_none">Такой записи не существует или она удалена!</div>';
				}
			}
			else
			{
				$res = getSQLArrayO("	SELECT id, page_id, name, mobile FROM my_face WHERE visible = 1
																																				AND (mobile LIKE '%{$buyer}%'
																																				OR whatsapp LIKE '%{$buyer}%' AND visible = 1
																																				OR international LIKE '%{$buyer}%' AND visible = 1
																																				OR email LIKE '%{$buyer}%' AND visible = 1
																																				OR name LIKE '%{$buyer}%' AND visible = 1
																																				OR name_other LIKE '%{$buyer}%' AND visible = 1
																																				OR info LIKE '%{$buyer}%' AND visible = 1 )
																													order by edate DESC	limit 5 ");

				 $page = getSQLArrayO("	SELECT id, page_id, name_company AS name FROM my_news WHERE visible = 1
																																					AND (name_company LIKE '%{$buyer}%'
																																					OR info LIKE '%{$buyer}%' AND visible = 1
																																					OR history LIKE '%{$buyer}%' AND visible = 1
																																					OR contact LIKE '%{$buyer}%' AND visible = 1
																																					OR insta LIKE '%{$buyer}%' AND visible = 1 )
																														order by edate DESC	limit 5 ");
				$result = array_merge($res, $page);
				if(count($result) > 0)
				{
					echo '<ul>';
					foreach ($result as $value)
					{
						echo '<li><a href="'.getFullXCodeByCommentsId($value->page_id).$value->id.'">'.$value->name.' <span class="badge">'.getFullXCodeByCommentsName($value->page_id).'</span></a></li>';
					}
					echo '</ul>';
				}
				else {
					echo 0;
				}
			}
			exit;
		break;


		/* Перенос заметок с одного места на другое */
	 case 'inputGlobalT' :

	 	$note = $_POST['note'];
		$sql = getSQLRowO("SELECT *  FROM my_comments WHERE id = '{$note}' ");
		if($sql->parent_comment_id > 0)
		{
			$res = getSQLArrayO("SELECT * FROM my_comments WHERE id = '{$sql->parent_comment_id}' OR  parent_comment_id  = '{$sql->parent_comment_id}' ");
		}
		elseif($sql->parent_comment_id == 0)
		{
			$res = getSQLArrayO("SELECT * FROM my_comments WHERE id = '{$sql->id}' OR  parent_comment_id  = '{$sql->id}' ");
		}

		$namber = explode("*", $_POST['record']);
		$sql = getSQLRowO("SELECT *  FROM my_news WHERE id = '{$namber[1]}' AND visible = 1 ");
		if($namber[1] > 0 && $sql->id > 0)
		{
			foreach ($res as $value)
			{
				$CFG->DB->query("UPDATE my_comments SET parent_id = {$sql->page_id}, page_id = {$namber[1]}  WHERE id = {$value->id} ");
			}
			echo 1;
		}
		else
		{
			echo 0;
		}

		 exit;
	 break;

	 /* Поиск в шапке */
	case 'inputN' :
		$buyer = $_POST['note'];
		$buyer = str_replace("'", '', $buyer);
		$namber = explode("*", $buyer);

		// Если это поиск по номеру записи с *
		if(is_numeric($namber[1]) > 0 )
		{
			$nambers = (int)$namber[1];
			$sql = getSQLRowO("SELECT * FROM my_news WHERE id={$nambers} AND visible = 1");
			if($sql->id > 0)
			{
					echo json_encode(array('id' => $sql->id, 'text' => '<div style="color:black; font-family:segoeui_b;" class="mask_none">'.$sql->name_company.' <span class="badge">'.getFullXCodeByCommentsName($sql->page_id).'</span>', 'status' => '1'));
			}
			else
			{
				echo json_encode(array('text' => '<div style="color:red; font-family:segoeui_b;" class="mask_none">Такой записи не существует или она удалена!</div>', 'status' => '0'));
			}
		}
		else
		{
			echo json_encode(array('text' => '<div style="color:red; font-family:segoeui_b;" class="mask_none">Укажите правельно номер записи! Например *5555</div>', 'status' => '0'));
		}
		exit;
	break;






			/* Удалить скидку по артикулу */
		 case 'del_face_vender' :
		 	 $id = $_POST['id'];

			 if($id != '')
			 {
						$CFG->DB->query("DELETE FROM my_face_vendor WHERE id='{$id}'	");
						echo 1;
		 	 }

			 exit;
		 break;

			/* Отдаем скидку интернет магазину по телефону*/
		 case 'importDataSignimpress' :
		 	 $telephone = $CFG->_GET_PARAMS[1];
			 if($telephone != '')
			 {
				 $sql = getSQLRowO("SELECT *  FROM my_face WHERE mobile = '{$telephone}' AND visible = 1 ");
				 if($sql->id > 0)
				 {
					 	$user = getSQLRowO("SELECT *  FROM my_users WHERE mobile = '{$telephone}' AND visible = 1 ");
						if($user->id > 0)
						{
							$data['position'] = $user->position;
						}
						else
						{
							$data['position'] = 0;
						}
					 	$data['default_signimpress'] = $sql->default_signimpress;
						$data['good_signimpress'] = $sql->good_signimpress;
						$res = getSQLArrayO("SELECT * FROM my_face_vendor WHERE customer = '{$telephone}' AND page_id = '{$sql->id}' AND site = 1 ");
						foreach ($res as $value)
						{
							$item[$value->vendor] = $value->number;
						}
						$data['item'] = $item;

						ob_clean();
						echo json_encode($data);
				 }
				 else
				 {
						$arr['default_signimpress'] = 6;
 						$arr['good_signimpress'] = 0;
						$arr['position'] = 0;
						$arr['item'] = array();

						ob_clean();
						echo json_encode($arr);
				 }
		 	 }

			 exit;
		 break;



		 		 /* Отдаем скидку интернет магазину LED.RU по телефону*/
		 		case 'importDataLed' :
		 			$telephone = $CFG->_GET_PARAMS[1];
		 			if($telephone != '')
		 			{
		 				$sql = getSQLRowO("SELECT *  FROM my_face WHERE mobile = '{$telephone}' AND visible = 1 ");
		 				if($sql->id > 0)
		 				{
		 					 $data['default_signimpress'] = $sql->default_led;
		 					 $data['good_signimpress'] = $sql->good_led;
		 					 $res = getSQLArrayO("SELECT * FROM my_face_vendor WHERE customer = '{$telephone}' AND page_id = '{$sql->id}' AND site = 2 ");
		 					 foreach ($res as $value)
		 					 {
		 						 $item[$value->vendor] = $value->number;
		 					 }
		 					 $data['item'] = $item;

		 					 ob_clean();
		 					 echo json_encode($data);
		 				}
		 				else
		 				{
		 					 $arr['default_signimpress'] = 6;
		 					 $arr['good_signimpress'] = 0;
		 					 $arr['item'] = array();

		 					 ob_clean();
		 					 echo json_encode($arr);
		 				}
		 			}

		 			exit;
		 		break;




				/* Проверка существуетли такой нмоер в системе */
			 case 'namber_valid' :


			 $mobile = $_POST['mobile'];

			 $sql = getSQLRowO("SELECT COUNT(id)  FROM my_face WHERE mobile LIKE '%{$mobile}%' AND visible = 1 ");

			 	print_r($sql);


				 exit;
			 break;



				/* Отправить СМС whatsapp тому кто сделал заказ */
			 case 'send_whatsapp_relise' :

			 $start = date('Y-m-d').' 00:00:00';
			 $off = date('Y-m-d').' 23:59:59';

			 $sql = getSQLRowO("SELECT * FROM my_data_1c_nomenclature_sms WHERE  visible = 0 AND (cdate >= '{$start}') AND (cdate <= '{$off}')  ");

			 if($sql->mobile !='')
			 {
				 $wp = getSQLRowO("SELECT * FROM my_tmp_whatsapp_namber WHERE  namber = '+77010320320' AND visible = 1   ");
				 $data = ['phone' => $sql->mobile, 'body'=> $sql->text];
				 $url = 'https://api.chat-api.com/instance'.$wp->wp_id.'/message?token='.$wp->token;
				 whatsapp_send($data, $url);
			 }

			 	$CFG->DB->query("UPDATE my_data_1c_nomenclature_sms SET visible = 1 WHERE id= {$sql->id} ");
				 exit;
			 break;





			 /* Запись данных о новом заказе и ИМ форсайн	*/
		 	case 'updata_order_im' :

			$order_info = unserialize($_POST['order_info']);
			$order_product = unserialize($_POST['order_product']);
			$skidos = $_POST['skidos']*1;
			$cdate = sqlDateNow();
			$times = time();

			$text .= 'Заказа с ИМ: '.$order_info['order_id'].PHP_EOL;
			$text .= $order_info['firstname'].PHP_EOL;
			$text .= $order_info['email'].PHP_EOL;
			$text .= $order_info['telephone'].PHP_EOL;
			$text .= '==========='.PHP_EOL;

			//Список товаров
			foreach ($order_product->rows as  $value)
			{
				$text .= $value['name'].' '.$value['model'].', '.$value['quantity'].' шт * '.number_sum(substr($value['price'], 0, -5)).' тг. '.number_sum(substr($value['total'], 0, -5)).' тг '.PHP_EOL;
				$sum[] = substr($value['total'], 0, -5);
			}
			$text .= '==========='.PHP_EOL;
			if($order_info['shipping_method'] !='Самовывоз') {$text .= 'Доставка по адресу:'.$order_info['payment_city'].', '.$order_info['payment_address_1'].PHP_EOL; }
			$text .= 'Метод:'.$order_info['shipping_method'].PHP_EOL;
			$text .= '==========='.PHP_EOL;

			if($skidos > 0)
			{
				$text .= 'Скидка: '.$skidos.' %'.PHP_EOL;
				$text .= '==========='.PHP_EOL;
				$text .= 'Заказ на сумму: '.number_sum(array_sum($sum)).' тг '.PHP_EOL;
				$text .= 'Заказ со скидкой: '.number_sum(substr($order_info['total'], 0, -5)).' тг '.PHP_EOL;
			}
			else
			{
				$text .= 'Заказ на сумму: '.number_sum(substr($order_info['total'], 0, -5)).' тг '.PHP_EOL;
			}

			$telephone = $order_info['telephone'];

					//Проверяем если в базе человек с таким номером телефона
					$res = getSQLRowO(" SELECT id FROM my_face WHERE mobile = '{$telephone}' AND visible = 1");
					if($res->id > 0)
					{
						//Для того что бы оставить заметку о новом заказе с ИМ, находим к какой компании он првязан
						$sql = getSQLRowO("select * from my_news where find_in_set('{$res->id}',client_id) <> 0 AND page_id = 868 AND visible = 1 ");
						if($sql->id > 0)
						{
								//Записываем заметку
								$CFG->DB->query( "INSERT INTO my_comments (page_id, parent_id, user_id, text, cdate, im) VALUES ('{$sql->id}', '868', '561', '{$text}', '{$cdate}', 1 ) 	");
								$z_id = $CFG->DB->lastId();

								//Записываем извещения Геннадий
								$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '1', '{$sql->id}', '{$z_id}', '{$cdate}') 	");
								$a_id = $CFG->DB->lastId();
								$CFG->DB->query("UPDATE my_comments SET accessrecord = ',{$a_id}' WHERE id= {$z_id} ");

								//Записываем извещения Азамат
								$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '85', '{$sql->id}', '{$z_id}', '{$cdate}') 	");
								$a_id = $CFG->DB->lastId();
								$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
								$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");

								//Записываем извещения Настя
								$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '133', '{$sql->id}', '{$z_id}', '{$cdate}') 	");
								$a_id = $CFG->DB->lastId();
								$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
								$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");

								//Записываем извещения Аягоз
								$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '572', '{$sql->id}', '{$z_id}', '{$cdate}') 	");
								$a_id = $CFG->DB->lastId();
								$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
								$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");

								//Изменяем дату редактирования компании
								$CFG->DB->query("UPDATE my_news SET edate = '{$cdate}' WHERE id= {$sql->id} ");
						}
						else
						{//Если не находим то сами создаем компанию
							$CFG->DB->query( "INSERT INTO my_news (page_id, cdate, edate, manager_id, client_id, name_company, city_id) VALUES ('868', '{$cdate}', '{$cdate}', '561', '{$res->id}'	, 'Новый заказ с интернет магазина molotok.kz'	, '201' ) 	");
							$n_id = $CFG->DB->lastId();
							//Записываем заметку в эту компанию
							$CFG->DB->query( "INSERT INTO my_comments (page_id, parent_id, user_id, text, cdate, im) VALUES ('{$n_id}', '868', '561', '{$text}', '{$cdate}', 1 ) 	");
							$z_id = $CFG->DB->lastId();
							//Записываем извещения Геннадий
							$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '1', '{$n_id}', '{$z_id}', '{$cdate}') 	");
							$a_id = $CFG->DB->lastId();
							$CFG->DB->query("UPDATE my_comments SET accessrecord = ',{$a_id}' WHERE id= {$z_id} ");
							//Записываем извещения Азамат
							$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '85', '{$n_id}', '{$z_id}', '{$cdate}') 	");
							$a_id = $CFG->DB->lastId();
							$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
							$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");
							//Записываем извещения Настя
							$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '133', '{$n_id}', '{$z_id}', '{$cdate}') 	");
							$a_id = $CFG->DB->lastId();
							$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
							$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");
							//Записываем извещения Настя
							$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '572', '{$n_id}', '{$z_id}', '{$cdate}') 	");
							$a_id = $CFG->DB->lastId();
							$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
							$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");
						}
					}
					else
					{//Создаем нового человека с таким номером телефона
						$firstname = $order_info['firstname'];
						$email = $order_info['email'];
						$order_info['telephone'];
						$name = explode(" ", $firstname);

						//Записываем новое лицо
						$CFG->DB->query( "INSERT INTO my_face (page_id, manager_id, marketing_id, city_id, mobile, whatsapp, email, name, name_other, cdate, edate, bcdate) VALUES ('1012', '561', '132', '201', '{$telephone}', '{$telephone}', '{$email}', '{$name[0]}', '{$firstname}', '{$times}', '{$times}', 0		) 	");
						$а_id = $CFG->DB->lastId();

						//Создаем компани связанную с этм человеком
						$CFG->DB->query( "INSERT INTO my_news (page_id, cdate, edate, manager_id, client_id, name_company, city_id) VALUES ('868', '{$cdate}', '{$cdate}', '561', '{$а_id}'	, 'Новый заказ с интернет магазина molotok.kz'	, '201' ) 	");
						$n_id = $CFG->DB->lastId();

						//Записываем заметку в эту компанию
						$CFG->DB->query( "INSERT INTO my_comments (page_id, parent_id, user_id, text, cdate, im) VALUES ('{$n_id}', '868', '561', '{$text}', '{$cdate}', 1 ) 	");
						$z_id = $CFG->DB->lastId();

						//Записываем извещения Геннадий
						$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '1', '{$n_id}', '{$z_id}', '{$cdate}') 	");
						$a_id = $CFG->DB->lastId();
						$CFG->DB->query("UPDATE my_comments SET accessrecord = ',{$a_id}' WHERE id= {$z_id} ");

						//Записываем извещения Азамат
						$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '85', '{$n_id}', '{$z_id}', '{$cdate}') 	");
						$a_id = $CFG->DB->lastId();
						$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
						$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");

						//Записываем извещения Настя
						$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '133', '{$n_id}', '{$z_id}', '{$cdate}') 	");
						$a_id = $CFG->DB->lastId();
						$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
						$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");

						//Записываем извещения Настя
						$CFG->DB->query( "INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate) VALUES ('561', '572', '{$n_id}', '{$z_id}', '{$cdate}') 	");
						$a_id = $CFG->DB->lastId();
						$res = getSQLRowO(" SELECT * FROM my_comments WHERE id = '{$z_id}' ");
						$CFG->DB->query("UPDATE my_comments SET accessrecord = '{$res->accessrecord},{$a_id}' WHERE id= {$z_id} ");
					}
		 		exit;
		 	break;
		/* Запись данных о новом заказе и ИМ форсайн	*/




		 /* Запись звука с микрофона	*/
	 	case 'updata_record' :
			if($CFG->USER->USER_ID > 0)
			{
						$id = $CFG->_GET_PARAMS[1];

						// Каталог, в который мы будем принимать файл:
						$uploaddir = 'documents/wav/';
						$name = $_FILES['file']['name'];
						$onurl = $uploaddir.md5(rand(1,1000).time().$name).'.wav';
						$uploadfile = $uploaddir.basename($onurl);

						// Копируем файл из каталога для временного хранения файлов:
						if (copy($_FILES['file']['tmp_name'], $uploadfile))
						{
							$cdates = sqlDateNow();
							$sql = "INSERT INTO my_attachments (page_id, original, user_id, type, text, cdate) VALUES ('{$id}', 'Голосовое сообщение', '{$CFG->USER->USER_ID}', 'wav', '{$onurl}', '{$cdates}' )";
							$CFG->DB->query($sql);

							echo $response = json_encode(array('id' =>	$CFG->DB->lastId(), 'type' => 'audio'	));
						}
						else { echo 0; }

			}
	 		exit;
	 	break;
		/* Запись звука с микрофона	*/

		 /* Создание кассы */
	 	case 'add_accounting' :
			if($_POST['text'] != '')
			{
				$sql = "INSERT INTO my_money_accounting_data (director_id, name) VALUES ({$CFG->USER->USER_DIRECTOR_ID}, '{$_POST[text]}' )";
				$CFG->DB->query($sql);
				echo 	$CFG->DB->lastId();
			}
			else
				echo 0;
	 		exit;
	 	break;
		/* Создание кассы END */

		 /* Удаление кассы */
	 	case 'del_accounting' :
			if($_POST['id'] > 0)
			{
					//Скрываем кассу
					$CFG->DB->query("UPDATE my_money_accounting_data SET visible = 0 WHERE id= {$_POST[id]} ");

					//Скрываем операции
					$CFG->DB->query("UPDATE my_money_accounting SET visible = 0 WHERE data_id = {$_POST[id]} ");
			}
			exit;
	 	break;
		/* Удаление кассы END */



		/* Получение настройки кассы */
	 case 'data_update_accounting' :
		 if($_POST['id'] > 0)
		 {
			 $res = getSQLRowO(" SELECT * FROM my_money_accounting_data WHERE id = '{$_POST[id]}' ");
			 // Имя кассира
			 $respon = getSQLRowO(" SELECT name FROM my_users WHERE id = '{$res->user_id}' ");
			 if($respon->name != '') { $names = $respon->name;} else {$names = '';}

			 $response = json_encode(array('id' => $_POST['id'], 'user_id' => $res->user_id, 'name' => $res->name, 'name_accounting' =>  $names 	));
			 echo $response;
		 }
		 exit;
	 break;
	 /* Получение настройки кассы END */


	 /* Обновить настройки кассы */
	case 'update_accounting' :
		if($_POST['id'] > 0)
		{
				print_r();

				//Удаляем доступы и записываем заново
				$CFG->DB->query($queryS  = "DELETE FROM my_money_accounting_data_access WHERE data_id='{$_POST[id]}'");
				//И записываем новые
				foreach($_POST['access'] as $access)
				{
					$sql = "INSERT INTO my_money_accounting_data_access (data_id, user_id) VALUES (	{$_POST[id]}, {$access} )";
					$CFG->DB->query($sql);
				}

				if($_POST['user_id'] != 0)
				{
					//Обновляем настройки кассы с кассиром
					if($CFG->DB->query("UPDATE my_money_accounting_data SET user_id = {$_POST[user_id]}, name = '{$_POST[name]}' WHERE id= {$_POST[id]} "))
					{ echo 1;} else { echo 0; }
				}
				else
				{
					//Обновляем настройки кассы БЕЗ кассиром
					if($CFG->DB->query("UPDATE my_money_accounting_data SET  name = '{$_POST[name]}' WHERE id= {$_POST[id]} "))
					{ echo 1;} else { echo 0; }
				}
		}
		exit;
	break;
	/* Обновить настройки кассы END */


	/* Получить юзеров кто имеет доступ к кассе */
 case 'data_access_accounting' :
	 if($_POST['id'] > 0)
	 {
			 $sql = getSQLArrayO("SELECT id, name FROM my_users WHERE visible=1 AND visible=1  ORDER BY name ASC");
			 $sqlS = getSQLArrayO("SELECT * FROM my_money_accounting_data_access WHERE visible=1 AND data_id = {$_POST[id]} ORDER BY id DESC");

			 echo '<strong>Кто может просматривать: </span></strong><br><select class="selectpicker show-tick manager list-view-manager pase access" data-live-search="true"  multiple="multiple" name="access[]" title="Доступ"><option value="0" section>Выберите менеджера</option>';
			 foreach($sql as $res)
			 {
				 ?> <option value="<?=$res->id;?>" <? for($z=0;$z<sizeof($sqlS);$z++){	if ($sqlS[$z]->user_id == $res->id) { echo 'selected';}		} ?>  ><?=$res->name;?></option><?
			 }
			 echo '</select>';
	 }
	 exit;
 break;
 /* Получить юзеров кто имеет доступ к кассе */



	/* Убирать вожможность удалять записи у менеджеров раз в 5 дней */
	case 'view_delete' :

	$array = array(1 => '01', 2 => '05', 3 => '10', 4 => '15', 5 => '20', 6 => '25', 7 => '30');

	$key = array_search(date('d'), $array);

	if($key > 0)
	{
		$sql = getSQLArrayO("SELECT id, name FROM {$CFG->DB_Prefix}users WHERE visible=1 AND view_delete = 1 AND boss = 0 ORDER BY name ASC");
		foreach($sql as $res)
		{
			$CFG->DB->query("UPDATE my_users SET view_delete = 0 WHERE id='{$res->id}' ");
		}
		print_r($sql);
	}

		exit;
	break;


	/* Остановить увидомления о закрытия сделок на 20 минут */
	case 'deal_stop_20' :
		SetCookie("deal_stop_20", 1, time()+1200, '/');
		exit;
	break;



	/* Экспорт данных с xlsx */
	case 'floor' :

		$search_word = rawurldecode($CFG->_GET_PARAMS[1]);

		echo '<strong>Имя</strong>:'.$search_word.'<br>';

		$sql = getSQLArrayO("SELECT id FROM my_face WHERE name LIKE '%{$search_word}%' ");

		if( count ($sql ) > 0)
		{
			foreach($sql as $res)
			{
				$query  = "UPDATE my_face SET floor = 2 WHERE id='{$res->id}' ";
				$CFG->DB->query($query);
			}

		}
		echo '<strong>Обработано количество записей</strong>: '.count ($sql );

		exit;
	break;


			/* Экспорт контактов */
			case 'exrort_file_db' :

				set_time_limit(600);

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("forSign Kazakhstan");
			$objPHPExcel->getProperties()->setLastModifiedBy("forSign Kazakhstan");
			$objPHPExcel->getProperties()->setTitle("XLSX Document");
			$objPHPExcel->getProperties()->setSubject("Export XLSX Document");
			$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F98383');
			$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->setWrapText(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);

			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Название сделки');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Дата создания');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Кем создана сделка');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Тег сделки');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Примечание к сделке');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Примечание к сделке');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Полное имя (контакт)');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Мобильный телефон (контакт)');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Личный email (компания)');


			$manager_id = getSQLRowO(" SELECT GROUP_CONCAT(id) FROM {$CFG->DB_Prefix}users WHERE user_id = 92 ");
			$mip = $manager_id->{'GROUP_CONCAT(id)'};
			$sql = getSQLArrayO("SELECT * FROM my_news  WHERE visible = 1 AND manager_id IN($mip) ");

			$cnt = 2;
			foreach($sql as $res)
			{
					$objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", coder($res->name_company));

					$objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", dateSQL2TEXT($res->cdate, "DD.MM.YY hh:mm"));

					$manager_id = getSQLRowO(" SELECT name FROM {$CFG->DB_Prefix}users WHERE id = '{$res->manager_id}' ");
					$objPHPExcel->getActiveSheet()->SetCellValue("C$cnt", coder($manager_id->name));

					$objPHPExcel->getActiveSheet()->SetCellValue("D$cnt", coder('Экспорт с CRM'));
					$objPHPExcel->getActiveSheet()->SetCellValue("E$cnt", coder($res->info));


					$red = getSQLRowO("SELECT  GROUP_CONCAT(id) FROM my_comments WHERE visible = 1 AND page_id = {$res->id} AND parent_id = {$res->page_id} ");
					$mid = $red->{'GROUP_CONCAT(id)'};
					if($mid == '') continue;
					$sl = getSQLArrayO("SELECT text FROM my_comments  WHERE visible = 1 AND id IN($mid) ");
					foreach($sl as $text)	{	$comment .= $text->text.PHP_EOL.PHP_EOL;	}
					$objPHPExcel->getActiveSheet()->SetCellValue("F$cnt", coder($comment));	$comment = '';

					// Если есть лицо
					if($res->client_id != '')
					{
						$sql = getSQLArrayO("SELECT * FROM my_face  WHERE visible = 1 AND id IN ({$res->client_id}) ");
						if(count($sql) > 0)
						{
								foreach($sql as $face)
								{
										$name .= $face->name.PHP_EOL;
										$mobile .= $face->mobile.PHP_EOL;
										$email .= $face->email.PHP_EOL;
								}
								$objPHPExcel->getActiveSheet()->SetCellValue("G$cnt", coder($name));
								$objPHPExcel->getActiveSheet()->SetCellValue("H$cnt", coder($mobile));
								$objPHPExcel->getActiveSheet()->SetCellValue("J$cnt", coder($email));
								$name = '';
								$mobile = '';
								$email = '';
						}
					}


					$cnt ++;
			}

			$objPHPExcel->getActiveSheet()->setTitle('Simple');
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$name = './documents/buch/'.date("m_y").".xlsx";
			$objWriter->save($name);
			$filename = './'.$name;
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);


			exit;
			break;


		/* Экспорт данных с xlsx */
		case 'exrort_file_img' :

			$manager_id = getSQLRowO(" SELECT GROUP_CONCAT(id) FROM {$CFG->DB_Prefix}users WHERE user_id = 92 ");


			$sql = getSQLArrayO("SELECT attachments_image,page_id  FROM my_comments  WHERE visible = 1 AND user_id IN($mip) AND attachments_image !='' ");

			for ($i=0; $i<sizeof($sql); $i++)
			{
					//print_r($sql[$i]);
					$img_list = explode(",", $sql[$i]->attachments_image);

					foreach($img_list as $res)
					{
							$str = substr($res, 1);

							$list = explode("/", $str);

							$filename = 'bayturin/01_jpg/' . $list[2].'/';

							if (!file_exists($filename))
							{
								$oldumask = umask(0);
								mkdir($filename, 0777);
								chmod($filename, 0777);
								umask($oldumask);

								$file = $str;

								$newfile = 'bayturin/01_jpg/'.$list[2].'/'.$list[2].'.'.$list[3];

								if (!copy($file, $newfile))
								{
										echo "не удалось скопировать $file \n";
								}
							}

					}

			}



			exit;
		break;

	/* Экспорт данных с xlsx */
	case 'exrort_file' :

		$manager_id = getSQLRowO(" SELECT GROUP_CONCAT(id) FROM {$CFG->DB_Prefix}users WHERE user_id = 92 ");

		$mip = $manager_id->{'GROUP_CONCAT(id)'};

		exit;

		$sql = getSQLArrayO("SELECT attachments_file  FROM my_comments  WHERE visible = 1 AND user_id IN($mip) AND attachments_file !='' ");

		for ($i=0; $i<sizeof($sql); $i++)
		{
			$trim .= $sql[$i]->attachments_file;
		}

		$str = substr($trim, 1);

		$res = getSQLArrayO("SELECT *  FROM my_attachments  WHERE visible = 1 AND id IN({$str})  ");

		for ($y=0; $y<sizeof($res); $y++)
		{
			$filename = 'bayturin/' . $res[$y]->page_id.'/';

			if (!file_exists($filename))
			{
				$oldumask = umask(0);
				mkdir($filename, 0777);
				chmod($filename, 0777);
				umask($oldumask);
			}

			$document = explode("/", $res[$y]->text);
			$doc = explode(".", $document[3]);

			$file = $res[$y]->text;
			$convertedText = iconv('UTF-8','CP1251',$res[$y]->original);

			$newfile = 'bayturin/'.$res[$y]->page_id.'/'.$convertedText.'.'.$doc[1];

			if (!copy($file, $newfile))
			{
					echo "не удалось скопировать $file \n";
			}

		}

		exit;
	break;



	/* Экспорт данных с xlsx */
	case 'floor_city' :

		$sql = getSQLArrayO("SELECT id, client_id, city_id FROM my_news WHERE data_one = 0  limit 1000 ");


		for ($i=0; $i<sizeof($sql); $i++)
		{
				$client_id = explode(",", $sql[$i]->client_id);


				for ($y=0; $y<sizeof($client_id); $y++)
				{
					if($client_id[$y] == '') continue;

					echo $query  = "UPDATE my_face SET city_id = '{$sql[$i]->city_id}' WHERE id='{$client_id[$y]}' ";
			$CFG->DB->query($query);

				}
				echo $query  = "UPDATE my_news SET data_one = 1 WHERE id='{$sql[$i]->id}' ";
			$CFG->DB->query($query);
		}

		exit;
	break;


	/* Экспорт данных с xlsx */
	case 'import_xlsx' :

	/*
	$document = PHPExcel_IOFactory::load('502374669.xlsx');
	$activeSheetData = $document->getActiveSheet()->toArray(null, true, true, true);

			foreach($activeSheetData as $res)
			{
				if($res['A'] == 'Компания') continue;

				print_r($res);

				$cdate = time();

				$sql = "INSERT INTO my_face (page_id, manager_id, mobile, whatsapp, name, name_other, info,  visible, cdate, edate) VALUES (1012, '589', '{$res[D]}', '{$res[E]}', '{$res[B]}', '{$res[C]}',  '{$res[G]}', 1, '{$cdate}', '{$cdate}')";
				$CFG->DB->query($sql);
				$client_id = $CFG->DB->lastId();


				$cdates = sqlDateNow();
				$qwery = "INSERT INTO my_news (page_id, manager_id, client_id, type_company_id, name_company, info,  visible, cdate, edate) VALUES (868, '589', '{$client_id}', '10011941', '{$res[A]}', '{$res[G]}', 1, '{$cdates}', '{$cdates}')";

				$CFG->DB->query($qwery);
			}

		*/
		exit;
	break;
	/* Экспорт данных с xlsx */

	/*  Извещения */
	case 'calculator' :


		$o = getSQLRowO("SELECT * FROM wp_calculator WHERE id='{$_POST[select_module]}'");

		echo 'Цена за модуль: '.number_sum($o->price).' тг. <br>';
		$vse_moduli = $_POST['select_length']*$_POST['select_width'];
		$price_module =  $vse_moduli*$o->price.'<br>';
		echo '<strong>Общее количество модулей</strong>: '.$_POST['select_length']*$_POST['select_width'].'<br>';
		echo '<strong>Сумма за модули</strong>: '.number_sum($price_module).' тг. <br><br>';


		$price_power = 10000;
		$price_power_cell = ceil($vse_moduli/$o->power);
		echo 'Цена блока питания: '.number_sum($price_power).' тг. <br>';
		echo '<strong>Общее количество блоков питания</strong>: '.$price_power_cell.'<br>';
		echo '<strong>Сумма за блоки питания</strong>: '.number_sum($price_power*$price_power_cell).' тг. <br><br>';

		$price_resive = 5000;
		$price_resive_cell = ceil($vse_moduli/$o->resive);
		echo 'Цена ресивинг карты: '.number_sum($price_resive).' тг. <br>';
		echo '<strong>Общее количество ресивинг карт</strong>: '.$price_resive_cell.'<br>';
		echo '<strong>Сумма за ресивинг карты</strong>: '.number_sum($price_resive_cell*$price_resive).' тг. <br><br>';



		$price_motherboard = 50000;
		$price_motherboard_cell = 1;
		echo 'Цена материнской платы: '.number_sum($price_motherboard).' тг. <br>';
		echo '<strong>Общее количество материнских карт</strong>: '.$price_motherboard_cell.'<br>';
		echo '<strong>Сумма за материнскую плату</strong>: '.number_sum($price_motherboard*$price_motherboard_cell).' тг. <br><br>';

		$itogo = $price_module	+	($price_power*$price_power_cell)	+	($price_resive_cell*$price_resive) 	+	($price_motherboard*$price_motherboard_cell);


		$price_job = 30;
		echo 'Процент за работу: '.$price_job.'% <br>';
		echo 'Цена за работу: '.number_sum($itogo/100*30).' тг. <br><br>';

		echo 'Итого комплектующие: '.number_sum($itogo).' тг. <br><br>';

		echo 'Итого : '.number_sum($itogo+($itogo/100*30)).' тг. <br>';




		exit;

	break;
	/* Извещения */


	/*  Отправка сообщение о продаже изолона */
	case 'send_isolon' :

		$o = getSQLRowO("SELECT * FROM my_news_history WHERE page_id = 0 AND type = 'alert' AND visible = 0 order by times ASC ");

		if($o->id > 0)
		{
			$data =  unserialize($o->data);
			$html = $data->html;
			foreach ($data->tel as $value)
			{
					$wp = getSQLRowO("SELECT * FROM my_tmp_whatsapp_namber WHERE  namber = '+77010320320' AND visible = 1   ");

					$data = ['phone' => $value, 'body'=> $html];	print_r($data);
					$url = 'https://api.chat-api.com/instance'.$wp->wp_id.'/message?token='.$wp->token;
					whatsapp_send($data, $url);
			}
			$query  = "UPDATE my_news_history SET visible = 1 WHERE id='{$o->id}' ";
			$CFG->DB->query($query);
		}
		exit;
	break;
		/*  Отправка сообщение о продаже изолона */




	/*  Извещения */
	case 'client_id' :

		$CFG->DB->query("TRUNCATE TABLE my_face");

		$cdate = time();

		$o = getSQLArrayO("SELECT manager_id, id, data_name, data_mobile, data_whatsapp, data_email, data_other, type_company_portrait_id FROM my_news WHERE page_id = 868 AND visible = 1");

		for ($i=0; $i<sizeof($o); $i++)
		{

			$name_client = unserialize($o[$i]->data_name);
			$data_mobile = unserialize($o[$i]->data_mobile);
			$data_whatsapp = unserialize($o[$i]->data_whatsapp);
			$data_email = unserialize($o[$i]->data_email);
			$data_other = unserialize($o[$i]->data_other);


			for ($y=0; $y<sizeof($data_mobile); $y++)
			{
				$sql = "INSERT INTO my_face (page_id, manager_id, marketing_id, mobile, whatsapp, email, name, name_other, visible, cdate, edate) VALUES (1012, '{$o[$i]->manager_id}', '{$o[$i]->type_company_portrait_id}', '{$data_mobile[$y]}', '{$data_whatsapp[$y]}', '{$data_email[$y]}', '{$name_client[$y]}', '{$data_other[$y]}', 1, '{$cdate}', '{$cdate}')";
				$CFG->DB->query($sql);
				$client_id .= $CFG->DB->lastId().',';
			}

			$trim = trim($client_id, ',');

			$query  = "UPDATE my_news SET client_id = '{$trim}' WHERE id='{$o[$i]->id}' ";
			$CFG->DB->query($query);

			$trim = '';
			$client_id = '';
		}





		exit;

	break;
	/* Извещения */





	case 'falsecontact' :

	set_time_limit(600);

	$sql = getSQLArrayO("SELECT

		id,
		name_company,

		email,
		other_email,
		tel,
		tel_2,
		tel_3,

		name_client,
		name_client_mobile,
		name_client_email,

		name_director,
		name_director_mobile,
		name_director_email

		FROM {$CFG->DB_Prefix}news WHERE page_id = 868 AND data_one = 0 order by id ASC limit 100");

		for ($i=0; $i<sizeof($sql); $i++)
		{


			$name_client = unserialize($sql[$i]->name_client);
			$name_client_mobile = unserialize($sql[$i]->name_client_mobile);
			$name_client_email = unserialize($sql[$i]->name_client_email);

			$name_director = unserialize($sql[$i]->name_director);
			$name_director_mobile = unserialize($sql[$i]->name_director_mobile);
			$name_director_email = unserialize($sql[$i]->name_director_email);

			$array_name 			= array_merge(	(array)$name_client,	(array)$name_director	);
			$array_director_mobile	 = array_merge(	(array)$name_client_mobile,	(array)$name_director_mobile	);
			$array_director_email	 = array_merge(	(array)$name_client_email,	(array)$name_director_email	);

			if($sql[$i]->email != '')
			{
				$tel[] .= '';
				$mobile[] .= '';
				$email[] .= $sql[$i]->email;

				$array_name 			= array_merge(	(array)$tel,	(array)$array_name	);
				$array_director_mobile	 = array_merge(	(array)$mobile,	(array)$array_director_mobile	);
				$array_director_email	 = array_merge(	(array)$email,	(array)$array_director_email	);

				$tel = '';
				$mobile = '';
				$email = '';
			}

			if($sql[$i]->tel != '')
			{
				$tel[] .= '';
				$mobile[] .= $sql[$i]->tel;
				$email[] .= '';

				$array_name 			= array_merge(	(array)$tel,	(array)$array_name	);
				$array_director_mobile	 = array_merge(	(array)$mobile,	(array)$array_director_mobile	);
				$array_director_email	 = array_merge(	(array)$email,	(array)$array_director_email	);

				$tel = '';
				$mobile = '';
				$email = '';
			}

			if($sql[$i]->tel_2 != '')
			{
				$tel[] .= '';
				$mobile[] .= $sql[$i]->tel_2;
				$email[] .= '';

				$array_name 			= array_merge(	(array)$tel,	(array)$array_name	);
				$array_director_mobile	 = array_merge(	(array)$mobile,	(array)$array_director_mobile	);
				$array_director_email	 = array_merge(	(array)$email,	(array)$array_director_email	);

				$tel = '';
				$mobile = '';
				$email = '';
			}

			if($sql[$i]->tel_3 != '')
			{
				$tel[] .= '';
				$mobile[] .= $sql[$i]->tel_3;
				$email[] .= '';

				$array_name 			= array_merge(	(array)$tel,	(array)$array_name	);
				$array_director_mobile	 = array_merge(	(array)$mobile,	(array)$array_director_mobile	);
				$array_director_email	 = array_merge(	(array)$email,	(array)$array_director_email	);

				$tel = '';
				$mobile = '';
				$email = '';
			}


			$data_name = serialize(array_reverse($array_name));
			$data_mobile = serialize(array_reverse($array_director_mobile));
			$data_email = serialize(array_reverse($array_director_email));



		 	echo $query  = "UPDATE {$CFG->DB_Prefix}news SET data_one = 1, data_name = '{$data_name}', data_mobile = '{$data_mobile}', data_email = '{$data_email}' WHERE id='{$sql[$i]->id}'

			";
			$CFG->DB->query($query);
		}

		?>
        	<script>setInterval(function()  { location.reload(); }, 2000);</script>
        <?

	exit;
	break;

	/*  Получаем данные 1С с текущей базы по id  */
	case 'data_1C_id' :

		$id = $_POST['id'];
		$sql = getSQLArrayO("SELECT DISTINCT *  FROM my_data_1c WHERE id_product LIKE '%{$id}%' ");
echo '<table class="price">
	<tr>
		<th>Название</th>
		<th>Склад</th>
		<th>Остаток</th>
	</tr>';

	foreach($sql as $res)
	{
		?>
		<tr>
			<td><?=$res->name;?></td>
			<td><?= SelectData_live('data_1c_warehouse', $res->warehouse_id);?></td>
			<td><? $sum_count[] = $res->count; echo $res->count;?></td>
		</tr>
		<?
	}
	$date = new DateTime('-1 month');
echo '
<tr>
	<td>Итого по складам, онлайн остаток</td>
	<td colspan="2" class="count_real">'.array_sum($sum_count).'</td>
</tr>
	<tr>
		<td>Аналитика периода</td>
		<td  style="text-align:center"><input class="period" type="text" value="'.$date->format('d.m.Y').'" /></td>
		<td style="text-align:center">'.date('d.m.Y').'</td>
	</tr>
	<tr>
		<td>Остаток на период</td>
		<td style="text-align:center" class="period_num">0</td>
		<td style="text-align:center">'.array_sum($sum_count).'</td>
	</tr>
	<tr>
		<td>Коэффициент оборачиваемости товара</td>
		<td colspan="2" style="text-align:center" class="coefficient">0</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align:center">Данные расчеты не учитывают поступление товара в анализируемый период!!!</td>
	</tr>';


	if($CFG->USER->USER_VIEW_RUK > 0 )
	{
		echo '<tr>
			<td colspan="3"><br></td>
		</tr>

		<tr>
			<td colspan="3" style="text-align:center">Товарный босс</td>
		</tr>

		<tr>
		<td style="text-align:center" >';

		$ruk = getSQLRowO("SELECT * FROM my_data_1c_nomenclature_ruk WHERE id_product = '{$res->id_product}' order by id ASC	");

		if(count($ruk) == 0) {$bu = 1; $uu = 2;} else {$bu = $ruk->bu; $uu = $ruk->uu;}
		echo '
		<select name="warehouse" class="selectpicker manager_ruk" data-live-search="true">
			<option value="0" selected>Назначить руководителя</option>';
			$users_dir = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY name ASC");
			for ($i=0; $i<sizeof($users_dir); $i++)
			{
				($users_dir[$i]->id == $ruk->user_id) ? $sel = "selected" : $sel = "";
				echo '<option value="'.$users_dir[$i]->id.'" '.$sel.'>'.$users_dir[$i]->name.'</option>';
			}
		echo '
		</select>
		<div class="select_ruk">
			<a href="#" data-id="85">Азамат Алимжанов</a>
			<a href="#" data-id="133">Анастасия Замятина</a>
			<a href="#" data-id="86">Олжас Алимжанов</a>
			<a href="#" data-id="1">Геннадий Гнездилов</a>
		</div>
		</td>
		<td style="text-align:center; padding-top:12px;">БУ <input class="bu" value="'.$bu.'" style="width:30px; text-align:center"> %</td>
		<td style="text-align:center; padding-top:12px;">УУ <input class="uu" value="'.$uu.'" style="width:30px; text-align:center"> %</td>
		</tr>';

		echo '

		<tr>
			<td colspan="3" style="text-align:center">Товарный босс № 2</td>
		</tr>

		<tr>
		<td style="text-align:center" >';

		echo '
		<select name="warehouse" class="selectpicker manager_ruk_2" data-live-search="true">
			<option value="0" selected>Назначить руководителя</option>';

			$ruk_2 = getSQLRowO("SELECT * FROM my_data_1c_nomenclature_ruk WHERE id_product = '{$res->id_product}' order by id DESC	");

		if($ruk->id == $ruk_2->id) {$ruk_2->user_id = 0;}
		if(count($ruk_2->id) == 0) {$bu = 1; $uu = 2;} else {$bu = $ruk_2->bu; $uu = $ruk_2->uu;}


			$users_dir = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY name ASC");

			for ($i=0; $i<sizeof($users_dir); $i++)
			{
				($users_dir[$i]->id == $ruk_2->user_id) ? $sel = "selected" : $sel = "";

				echo '<option value="'.$users_dir[$i]->id.'" '.$sel.'>'.$users_dir[$i]->name.'</option>';
			}
		echo '
		</select>
		<div class="select_ruk_2">
			<a href="#" data-id="85">Азамат Алимжанов</a>
			<a href="#" data-id="133">Анастасия Замятина</a>
			<a href="#" data-id="86">Олжас Алимжанов</a>
			<a href="#" data-id="1">Геннадий Гнездилов</a>
		</div>
		</td>
		<td style="text-align:center; padding-top:12px;">БУ <input class="bu_2" value="'.$bu.'" style="width:30px; text-align:center"> %</td>
		<td style="text-align:center; padding-top:12px;">УУ <input class="uu_2" value="'.$uu.'" style="width:30px; text-align:center"> %</td>
		</tr>';



			if($CFG->USER->USER_ID == 85  || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 536)
			{
				echo '<tr>
					<td colspan="3" style="text-align:right"><bottom data-id="'.$res->id_product.'" class="save_ruk">Сохранить</bottom></td>
				</tr>';
			}
	}


echo '</table>';

		exit;
	break;



	/*  Получаем данные 1С с текущей базы по id  */
	case 'data_1C_id_2' :

		$id = $_POST['id'];
		$sql = getSQLArrayO("SELECT DISTINCT *  FROM my_data_1c WHERE id_product LIKE '%{$id}%' ");
echo '<table class="price">
	<tr>
		<th>Название</th>
		<th>Склад</th>
		<th>Остаток</th>
	</tr>';

	foreach($sql as $res)
	{
		?>
		<tr>
			<td><?=$res->name;?></td>
			<td><?= SelectData_live('data_1c_warehouse', $res->warehouse_id);?></td>
			<td><? $sum_count[] = $res->count; echo $res->count;?></td>
		</tr>
		<?
	}
	$date = new DateTime('-1 month');
echo '
<tr>
	<td>Итого по складам, онлайн остаток</td>
	<td colspan="2" class="count_real">'.array_sum($sum_count).'</td>
</tr>
	<tr>
		<td>Аналитика периода</td>
		<td  style="text-align:center"><input class="period" type="text" value="'.$date->format('d.m.Y').'" /></td>
		<td style="text-align:center">'.date('d.m.Y').'</td>
	</tr>
	<tr>
		<td>Остаток на период</td>
		<td style="text-align:center" class="period_num">0</td>
		<td style="text-align:center">'.array_sum($sum_count).'</td>
	</tr>
	<tr>
		<td>Коэффициент оборачиваемости товара</td>
		<td colspan="2" style="text-align:center" class="coefficient">0</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align:center">Данные расчеты не учитывают поступление товара в анализируемый период!!!</td>
	</tr>

	</table>';

		exit;
	break;




		/*  Получаем данные с 1С */
		case 'data_1C_ostatok' :
			set_time_limit(600);

			$id = $_POST['id'];
			$datas = $_POST['datas'];

			$post = ['Дата' => $datas, 'Id' => [$id] ];
			$url = "http://192.168.1.122:8081/fc_utp/hs/api/v1/ostatok";
			$login = 'webservice';
			$password = 'AsdfRewq!';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
			$result = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($result, true);

			foreach($data[0]['Остатки'] as $res)
			{
				$arr[] = $res["КоличествоОстаток"];
			}

			echo array_sum($arr);
			exit;
		break;
		/*  Получаем данные с 1С */



		/*  Получаем данные с 1С */
		case 'data_1C_ostatok_get' :
			set_time_limit(600);

			//$id = $CFG->_GET_PARAMS[2];
			$datas = $CFG->_GET_PARAMS[1];

			$post = ['Дата' => $datas, 'Id' => ['f66329bd-e23f-11e8-80ea-1c1b0d3319f5'] ];

			$url = "http://192.168.1.122:8081/fc_utp/hs/api/v1/ostatok";
			$login = 'webservice';
			$password = 'AsdfRewq!';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
			$result = curl_exec($ch);
			curl_close($ch);


			$data = json_decode($result, true);


			print_r($data	);
			exit;

			foreach($data as $res)
			{
				$arr[] = $res["КоличествоОстаток"];
			}
			echo array_sum($arr);
			exit;
		break;
		/*  Получаем данные с 1С */

	case 'data_1C_bin' :
			$url = "http://93.185.77.51:8081/fc_utp/hs/api7/kontragent/get?bin=141040005754";
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
			print_r($data);
			exit;
	break;


	/* Загрузка номенклатур за 2 недели */
	case 'nomenclature' :
	exit;
	break;
	/* Загрузка номенклатур за год */


	/* начисления Мелсу за реализации в выходные */
	case 'melsnomenclature' :
		$json_daily_file = 'documents/buch/nomenclature.json';
		$result = json_decode(file_get_contents($json_daily_file));
		$data = resizeArray($result->ПродажиОбороты, 'Склад');

		foreach ($data as $value)
		{
			if(date("Y-m-d", strtotime($value->Период)) == date('Y-m-d') && $value->РеализацияВВыходной == 1)
			{
					$id_prodazha[] = $value->Id_Продажи;
					$res[] = $value;
			}
		}
		$result = array_unique($id_prodazha);

		print_r($result); exit;

		if(count($result) > 0)
		{
			$user = getSQLRowO("SELECT * FROM my_users WHERE id = 250 "); //Это Мелс)
			$cdate = sqlDateNow();

			foreach ($result as $key => $value)
			{
				$o = getSQLRowO("SELECT SUM(price*count) FROM my_data_1c_nomenclature WHERE id_prodazha LIKE '{$value}' ");
				$sum = (int)$o->{'SUM(price*count)'};
				if($sum > 0)
				{
					if($sum <= 50000){$premiya = 200;	}
					elseif($sum >= 50000 && $sum <= 100000)		{		$premiya = 300;	}
					elseif($sum >= 100000 && $sum <= 200000)	{		$premiya = 400;	}
					elseif($sum >= 200000 && $sum <= 400000)	{		$premiya = 500;	}
					elseif($sum >= 400000 && $sum <= 700000)	{		$premiya = 600;	}
					elseif($sum >= 700000 && $sum <= 1000000)	{		$premiya = 700;	}
					elseif($sum >= 1000000 && $sum <= 1400000){		$premiya = 800;	}
					elseif($sum >= 1400000 && $sum <= 2000000){		$premiya = 900;	}
					elseif($sum >= 2000000)										{		$premiya = 1000;}
					$html = 'Сумма самоначисления за продажу *'.$value.'* составила '.number_sum($premiya).' тенге'.PHP_EOL;

					$CFG->DB->query( "INSERT INTO my_comments (page_id, parent_id, user_id, text, cdate) VALUES ('{$user->taks_id}', '976', '561', '{$html}', '{$cdate}') 	");
					$page_id = $CFG->DB->lastId();

					$CFG->DB->query(	"INSERT INTO my_money_list (user_id, manager_id, count, page_id, coment_id, cdate, type, visible) VALUES ('{$user->id}', '{$user->id}', '{$premiya}',  '{$user->taks_id}', '{$page_id}', '{$cdate}', 1, 1)"	);
					$money_id = $CFG->DB->lastId();

					$CFG->DB->query("UPDATE my_comments SET premiumplus = ',{$money_id}' WHERE id= {$page_id} ");
				}
			}
		}

	exit;
	break;
	/* начисления Мелсу за реализации в выходные */



	/*  Получаем данные с 1С */
	case 'data_1C' :

	 	/*  В корене сайта есть файл init.php там функция data1C - котрая раз в пол часа записывает данные с 1С в файл  data_1C.json */
		set_time_limit(600);
		$json_daily_file = 'documents/buch/data_1C.json';
		$result = json_decode(file_get_contents($json_daily_file));
		$data = resizeArray($result, 'Склад');

		if(count($data) > 1)
		{
			$sklad = '';	//Склад
			$provider = ''; //Поставщик
			foreach($data as $res)
			{
					$sklad .= "('".htmlspecialchars($res->Склад, ENT_QUOTES)."'),".PHP_EOL;
					$provider .= "('".htmlspecialchars($res->Поставщик, ENT_QUOTES)."', '".htmlspecialchars($res->БИНПоставщика, ENT_QUOTES)."'),".PHP_EOL;
					$group .= "('".htmlspecialchars($res->Группа, ENT_QUOTES)."'),".PHP_EOL;
			}

			StrikData($sklad, 'my_data_1c_warehouse', array('name' => 'name' ));
			StrikData($group, 'my_data_1c_group', array('name' => 'name' ));
			StrikData($provider, 'my_data_1c_provider', array('name' => 'name' , 'bin' => 'bin' ));

			$CFG->DB->query("TRUNCATE TABLE my_data_1c");

			/*	записываем основной массив товаров	*/
			foreach($data as $res)
			{
				if($res->ДополнительноеСвойство != '') {$ds = ' '.$res->ДополнительноеСвойство;}else{$ds='';}
				if($res->ХарактеристикаНоменклатуры != '') {$hn = ' '.$res->ХарактеристикаНоменклатуры;}else{$hn='';}
				if($res->ПолноеНаименование != '') {$pn = $res->ПолноеНаименование;}else{$pn='';}

				$name = $CFG->DB->escape(preg_replace("/\s+/u", " ", $pn.$hn.$ds));
				$price = $res->РозничнаяЦена;
				$count = $res->КоличествоОстаток;
				$type = $res->РозничнаяЕд;
				$total = $res->ЗакупочнаяЦена;
				$groups = $CFG->DB->escape($res->Группа);
				$warehouse_text = $CFG->DB->escape($res->Склад);
				$provider = $CFG->DB->escape($res->Поставщик);
				$id_product = $res->ID;
				$vendor = $res->Артикул;
				$nameIM = $CFG->DB->escape(preg_replace('/\s+/u', ' ',$res->ПолноеНаименование));
				$optionIM = $CFG->DB->escape(preg_replace('/\s+/u', ' ',$res->ХарактеристикаНоменклатуры));

				// Поиск к какому складу относится товар
				$warehouse_id = rec_warehouse_id($warehouse_text,'my_data_1c_warehouse');
				// Поиск к какой группе относится товар
				$groups_id = rec_warehouse_id($groups,'my_data_1c_group');
				// Поиск к какому поставщитку относится товар
				$provider_id = rec_warehouse_id($provider,'my_data_1c_provider');

				//if($warehouse_id == 0 || $warehouse_id == '' ||  $groups_id == 0 || $groups_id == '' || $total == 0) continue;
				$html_sql .=  "('".$vendor."', '".$id_product."', '".htmlspecialchars($name, ENT_QUOTES)."', '".htmlspecialchars($nameIM, ENT_QUOTES)."', '".htmlspecialchars($optionIM, ENT_QUOTES)."', '".$price."', '".$count."', '".$total."', '".$type."', '".$groups_id."', '".$warehouse_id."', '".$provider_id."' ),".PHP_EOL;

				$real[] = $id_product;
			}
			//$med = trim($real, ',');

			if( $html_sql != '')
			{
				$up = substr($html_sql, 0, -2);

				$CFG->DB->query("INSERT INTO my_data_1c (vendor, id_product, name, nameIM, optionIM, price, count, total, type, group_id, warehouse_id, provider_id) VALUES {$up} ON DUPLICATE KEY UPDATE vendor = VALUES(vendor), name = VALUES(name), nameIM = VALUES(nameIM), optionIM = VALUES(optionIM), price = VALUES(price), count = VALUES(count), total = VALUES(total), type = VALUES(type), group_id = VALUES(group_id), warehouse_id = VALUES(warehouse_id), provider_id = VALUES(provider_id); ");

				//coefficient_varchar($real);
				prodazha_za_god($real);
			}

			echo count($data);
		}
		else
		{
			echo 0;
		}
		exit;

	break;
	/*  Получаем данные с 1С */



	/*  Получаем полученые данные с 1С */
	case 'data_1c_json' :

	$text = $_POST["text"];

	$sql = getSQLArrayO("SELECT DISTINCT name, price,type FROM my_data_1c WHERE name LIKE '%{$text}%' ");

	if( count ($sql ) > 0)
	{
		foreach($sql as $res)
		{
			$o = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}data_1c WHERE name LIKE '{$res->name}' ");
			for ($i=0; $i<sizeof($o); $i++)
			{
				$sum[] = $o[$i]->count;
			}

			$all = array_sum($sum);

			$response = '<li data-type="'.htmlspecialchars($res->type).'" data-name="'.htmlspecialchars($res->name).'" data-price="'.$res->price.'" data-count="'.$all.'" >'.$res->name.'/'.number_sum($res->price).' '.$CFG->USER->USER_CURRENCY.'/'.$all.' '.$res->type.'</li>'; //(array('name' => $res->name, 'count' => $all, 'price' => $res->price	));

			echo $response;

			$sum = '';
		}

	}
	else
	{
		echo 0;
	}
	exit;
	break;
	/*  Получаем полученые данные с 1С */



	/*  Получаем данные с 1С */
	case 'data_1C_table' :
		set_time_limit(600);
		$url = "http://192.168.1.122:8081/fc_utp/hs/api/v1/data";
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

		if(count($data) > 1)
		{
			echo '<table border="1" cellspacing="0" cellpadding="0">

			  <tr>
    <td width="156" valign="top"><p>ПолноеНаименование</p></td>
    <td width="156" valign="top"><p>РозничнаяЦена</p></td>
    <td width="156" valign="top"><p>КоличествоОстаток</p></td>
    <td width="156" valign="top"><p>РозничнаяЕд</p></td>
  </tr>

			';

			foreach($data as $res)
			{
				echo '<tr>';
				echo  '<td width="156" valign="top"><p>'.$res["ПолноеНаименование"].' '.$res["ХарактеристикаНоменклатуры"].' '.$res["ДополнительноеСвойство"].'</td>';
				echo  '<td width="156" valign="top"><p>'.$res["РозничнаяЦена"].' тг</td>';
				echo  '<td width="156" valign="top"><p>'.$res["КоличествоОстаток"].'</td>';
				echo  '<td width="156" valign="top"><p>'.$res["РозничнаяЕд"].'</td>';
				echo '</tr>';
			}


	echo '</table>';

		}
		else
		{
			echo 0;
		}
		exit;

	break;
	/*  Получаем данные с 1С */




	/*  Сохраняем расчет 1С */
	case 'data_1c_print_to' :
		$names = $_POST["names"];
		$name = explode("#", $names);

		$counts = $_POST["count"];
		$count = explode("#", $counts);

		$prices = $_POST["price"];
		$price = explode("#", $prices);

		$types = $_POST["type"];
		$type = explode("#", $types);

		$my_name = $_POST["my_name"];

		for ($i=0; $i<sizeof($name); $i++)
		{
			if($name[$i] == '') continue;
			$name_data[] = $name[$i];
			$count_data[] = $count[$i];
			$price_data[] = $price[$i];
			$type_data[] = $type[$i];
		}

		$names_d = serialize($name_data);
		$count_d = serialize($count_data);
		$price_d = serialize($price_data);
		$type_d = serialize($type_data);

		$page_id = $_POST["page_id"];
		$user_id = $CFG->USER->USER_ID;
		$cdate = sqlDateNow();

		$sql = "INSERT INTO {$CFG->DB_Prefix}data_1c_res (user_id, page_id, names_d, count_d, price_d, type_d, company_id, my_name, visible, cdate) VALUES ('{$user_id}', '{$page_id}', '{$names_d}', '{$count_d}', '{$price_d}', '{$type_d}', '{$_POST[company_id]}', '{$my_name}', 0, '{$cdate}')";
		$CFG->DB->query($sql);

		$o = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}data_1c_res WHERE user_id='{$user_id}' AND visible = 0 order by id DESC");

		if($o->id > 0)
		{
			echo $o->id;
		}
		else
		{
			echo 0;
		}

		exit;
	break;
	/*  Сохраняем расчет 1С */




	/*  Сохраняем расчет 1С и отправляем на whatsapp */
	case 'data_1c_print_to_whatsapp' :

		$names = $_POST["names"];
		$name = explode("#", $names);

		$counts = $_POST["count"];
		$count = explode("#", $counts);

		$prices = $_POST["price"];
		$price = explode("#", $prices);

		$types = $_POST["type"];
		$type = explode("#", $types);


		for ($i=0; $i<sizeof($name); $i++)
		{
			if($name[$i] == '') continue;
			$name_data[] = $name[$i];
			$count_data[] = $count[$i];
			$price_data[] = $price[$i];
			$type_data[] = $type[$i];
		}

		$names_d = serialize($name_data);
		$count_d = serialize($count_data);
		$price_d = serialize($price_data);
		$type_d = serialize($type_data);

		$page_id = $_POST["page_id"];
		$user_id = $CFG->USER->USER_ID;
		$cdate = sqlDateNow();

		$sql = "INSERT INTO {$CFG->DB_Prefix}data_1c_res (user_id, page_id, names_d, count_d, price_d, type_d, company_id, visible, cdate) VALUES ('{$user_id}', '{$page_id}', '{$names_d}', '{$count_d}', '{$price_d}', '{$type_d}', '{$_POST[company_id]}', 0, '{$cdate}')";
		$CFG->DB->query($sql);

		$o = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}data_1c_res WHERE user_id='{$user_id}' AND visible = 0 order by id DESC");

		require_once "/modules/dompdf/dompdf_config.inc.php";

		$dompdf = new DOMPDF();

		def("DOMPDF_ENABLE_REMOTE", false);

		$url =  "https://".$_SERVER["HTTP_HOST"]."/static/commercial/".$o->id;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$html = curl_exec($ch);
		curl_close($ch);

		$dompdf->load_html($html);
		$dompdf->set_paper('A4', 'portrait');
		$dompdf->render();
		$output = $dompdf->output();

		file_put_contents('documents/whatsapp/85/cp/'.$o->id.'.pdf', $output);
		$path_site = 'documents/whatsapp/85/cp/'.$o->id.'.pdf';

		$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, cdate, original, text, type, user_id, visible) VALUES ('{$_POST[page_id]}', '{$cdate}', 'Счет на оплату № {$o->id}', '{$path_site}', 'pdf', {$CFG->USER->USER_ID}, 1)";
		$CFG->DB->query($sql);

		$a = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}attachments WHERE user_id='{$user_id}' order by id DESC");

		$response = json_encode(array('id_cp' => $o->id, 'id_attachments' => $a->id	));

		echo $response;

		exit;
	break;
	/*  Сохраняем расчет 1С и отправляем на whatsapp */


	/*  Просмотр расчет 1С */
	case 'commercial' :
		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}data_1c_res WHERE id='{$CFG->_GET_PARAMS[1]}' ");
		$z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}data_1c_company WHERE id='{$o->company_id}' ");
		include('.'.$z->tpl);
		exit;
	break;
	/*  Сохраняем расчет 1С */


	/*  Скачать расчет 1С */
	case 'down_commercial' :
		require_once "/modules/dompdf/dompdf_config.inc.php";
		$dompdf = new DOMPDF();
		$url =  "https://".$_SERVER["HTTP_HOST"]."/static/commercial/".$CFG->_GET_PARAMS[1];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$html = curl_exec($ch);
		curl_close($ch);
		def("DOMPDF_ENABLE_REMOTE", false);
		$dompdf->load_html($html);
		$dompdf->set_paper('A4', 'portrait');
		$dompdf->render();
		//$dompdf->stream('Коммерческое предложение №'.$CFG->_GET_PARAMS[1].'.pdf');
		$dompdf->stream('Счет на оплату №'.$CFG->_GET_PARAMS[1].'.pdf', array('Attachment' => false));
		exit;
	break;
	/*  Скачать расчет 1С */

	/*  Получения faq */
	case 'links' :
		$id = $_POST["id"];
		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}faq WHERE id='{$id}'");

		if(count($o) > 0)
		{
			$response = json_encode(array('img' => $o->img, 'text' => $o->text,	'status' => 1 ));
			echo $response;
		}
		else
			0;
		exit;
	break;
	/*  Получения faq */




	/*  Извещения */
	case 'my_alarm' :
		$id = $_POST['user_id'];
		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}alarm WHERE user_id='{$id}' AND visible = 0 order by cdate DESC");
		if(count($o) > 0)
		{
			$user = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}users WHERE id='{$o->autor_id}' ");
			$name_company = getSQLRowO("SELECT name_company FROM {$CFG->DB_Prefix}news WHERE id='{$o->page_id}' ");

			$response = json_encode(array('autor' => $user->name, 'text' => $o->text.'<br><a href="/static/off_alarm/'.$o->id.'">'.$name_company->name_company.'</a>'	));

			echo $response; exit;
		}
		else
			echo 0;
	break;
	/* Извещения */




	/*  Извещения */
	case 'my_tttttt' :

		$namber = '87018951527,87015551707,87075555905,87775785478,87079043945,87010000695,87474872874,87029868455,87022714822,87785425525,87017963030,87471133859,87083035599,87015342211,87025533221,87025553644,87761668833,87017828282,87021113431,87018558124,87021331221,87021141439,87757965939,87777000770,87779999323,87078500670,87026553208,87015122535,87753912527,87014508102,87082055286,87022901695,87089309160,87712545445,87012224939,87089309160,87015051145,87052968722,87011201531,87013562189,87017443495,87710722914,87758989089,87071336472,87022934098,87782024005,87021299161,87759144410,87023788286,87710330277,87019400741,87778834198,87477824604,87079081901,87027670343,87771463301,87011241234,87070424133,87088099992,87010650312,87784717070,87759400092,87029557251,87718338139,87754145383,87778600602,87010393333,87014614515,87771514755,87750264484,87052971822,87054447774,87056310524,87023337887,87078463154,87029552016,87011008077,87013713089,87711030454,87012227379,87015556953,87016435789,87021766070,87015127525,87774730164,87710225594,87712821919,87015016767,87761271273,87783136653,87778882290,87757714150,87029226761,87761698241,87782709755,87777707507,87758213120,87024288334,87016495989,87785231555,87084254623,87018833368,87014737989,87071403730,87015588893,87712281697,87757809031,87757092861,87785682924,87752990910,87711822425,87772100925,87017256878,87057606218,87784251212,87073148673,87029371379,8701 750 99 98,87019996993,87075920605,87719997702,8701 725 93 33,87 021 304 897,87016036527,87772228880,87015506622,87015888182,87015710861,87017641417,87080515696,87770011095,87022220577,87017770164,87753139444,87089595505,87753056905,87057385768,87023258611,87478337993,87016065588,8701 088 89 98,87022311710,87023338824,87016669665,87776120000,87002391988,87014907173,87777770370,87752505060,87010442424,87473953474,87761617303,87024070203,87078883314,87014522582,87022700001,87777487773,87057999070,87471696002,87025101810,87715265277,87017156230,87787503974,87021663604,87017572533,87012515956,87018221121,87710720061,87017999444,87026252583,87015559077,87776589191,87052445454,87077958940,87026766661,87012232729,87711361010,87073411333,87780007019,87017268460,87009666088,87477826168,87782633646,87016507591,87760079595,87751382326,87778916391,87782360191,87015347144,87017688567,87476198939,87022360191,87758887920,87000600069,87012229427,87788595594,87756263096,87016012342,87021093308,87086161210,87023160461,87011640185,87027108877,87759157355,87014449955,87172706059,87759991323,87754218817,87022029630,87023972044,87710010101,87012166327,87751174197,87755828873,87029919957,87473324833,87719888881,87017351254,87024070008,87010000407,87014002042,87777999695,87017695551,87029999797,87752990910,87015550557,87055558883,87015554738,87071444074,87019991022,87052314578,87075658974,87029433002,87776206699,87024410888,87000896999,87055058205,87772772050,87027716600,87761110965,87011110556,87015141915,87172719300,87027031818,87075825512,87718880288,87075141915,87750001889,87027649698,87079965357,87082839167,87757927364,87021698998,87784717292,87784130770,87014508102,87059631333,87172600251,87784615540,87057779109,87756544989,87012119410,87775670000,87025999919,87016322867,87012771458,87021926600,87715055055,87071963668,87018010913,87769962160,87028651111,87052922228,87082051265,87716660703,87026166668,87022688516,87085727185,87751030708,87016400916,87019938226,87751525075,87718434040,87015618097,87752395509,87015455284,87777273737,87012225320,87015030800,87019994111,87012110565,87029991070,87023725000,87019998371,87789993849,87006478658,87070009070,87750347066,87779966687,87055514334,87018387772,87012110565,87015938616,87770651419,87019565577,87476956657,87029996926,87089893149,87025764644,87017551101,87010913731,87768882247,87013505092,87013274282,87071344654,87755351000,87074628770,87775894949,87028466965,87057090186,87006840284,87024444629,87017309103,87017560431,87015165575,87473292917,87014121915,87775138409,87019993918,87011506776,87013338852,87757841025,87051658965,87013763705,87771000911,87015359362,87017848052,87015329021,87029119191,87025157077,87718880171,87057572737,87013557194,87786880298,87011795464,87019103010,87022634068,87021113322,87025217708,87019996980,87024058815,87015123459,87051117373,87017001377,87172799790,87780979042,87024995211,87780157633,87015694094,87775639964,87784462009,87784328571,87017744405,87025577722,87013801775,87753633166,87026174407,87015255781,87014210005,87758277107,87076565389,87477773319,87719064931,87780147788,87015447272,87011355104,87712409333,87015270327,87761223165,87020002831,87719029101,87710302021,87789285302,87073740374,87073213990,87015162950,87085960096,87017877154,87022113640,87777770806,87789161582,87011775775,87777700111,87471857910,87765212752,87477376150,87010222887,87027492707,87073313329,87012210255,87719886699,87780001595,87754500750,87472275837,87022158456,87778683835,87016074221,87019995454,87022103309,87014225042,87015200125,87472253036,87057646844,87029997748,87024397176,87052909999,87015552597,87787006061,87470940059,87788575718,87083312804,87011708888,87019993019,87017734110,87010909898,87013555559,87079118181,87023054061,87019407292,87017775331,87088434169,87751893951,87055345555,87013675200,87779555585,87775555059,87089990030,87015134075,87011026969,87083273354,87473623455,87024144114,87023980993,87710509049,87015993277,87057126262,87014440104,87777772229,87013333325,87773172413,87019200000,87017674427,87029996338,87781124251,87012393377,87013403481,87789801016,87767107053,87788536956,87071402092,87172610964,87011283553,87023794480,87775622256,87021327989,87784252075,87710509127,87017376732,87773370019,87028051555,87022237301,87775199961,87026579999,87005969690,87477221030,87024494910,87019995894,87015144130,87719755935,87074031340,87027982055,87079999955,87021794441,87711668833,87017892882,87770006600,87024951994,87013398293,87013984500,87006540000,87057050808,87027277687,87783080622,87758639997,87711011397,87079959529,87051900340,87071900340,87719091111,87026601118,87089990854,87478558203,87780979176,87478558203,87471755324,87021112113,87019479232,87007752584,87019970236,87782787373,87015360447,87070314718,87077155374,87777722004,87013016390,87015557330,87757670753,87029558596,87017380055,87017493631,87781919999,87016113230,87073834751,87752078803,87765632200,87058114711,87017617695,87711301463,87019519455,87011112474,87784616890,87474749963,87019994566,87015251092,87015297781,87019930442,87471662869,87011110561,87012119855,87079238729,87017334190,87017598669,87022144991,87784712025,87019900717,87015441086,87022861430,87710339992,87022861430,87773230060,87026398940,87013851695,87056583247,87784532320,87056332255,87084400701,87787048270,87013104105,87083412485,87755451686,87017203311,87078581555,87710000130,87777747404,87754848882,87751198499,87054001110,87017733631,87026912260,87004050600,87474488871,87759444936,87071335575,87782363356,87071615020,87770666579,87017730432,87015832999,87012088777,87785863790,87012151628,87079513875,87017492797,87028885444,87769999910,87752328198,87027857757,87782842162,87012885628,87015515511,87776771647,87003513728,87015030983,87016228522,87015440439,87772747326,87774736414,87019006936,87759070837,87018764830,87082414803,87479801238,87015389312,87022869383,87016293135,87014808449,87019181517,87021399907,87077304548,87015239440,87750607974,87079114040,87051910172,87775555730,87755403053,87474878505,87763328756,87470185075,87777720166,87785040034,87015208115,87028530330,87055838600,87015998325,87051281890,87057544740,87014531819,87015206723,87074445124,87711642333,87012222983,87752236275,87759552125,87759552125,8707900500,87767425422,87001231996,87075996865,87001620949,87010883777,87013907515,87019992203,87021754769,87773093967,87789336488,87015182732,87010450020,87015277161,87775331515,87757026069,87022883979,87018824384,87015279444,87712005876,87023212121,87023566749,87052989723,87029995865,87473554794,87772379738,87010799903,87057419316,87015268463,87015225413,87057436003,87479050997,87019299776,87755658665,87055555551,87788199297,87016211701,87074939431,87773167077,87015237879,87074447761,87016831196,87016291286,87780017437,87718888866,87075496893,87010353333,87756434444,87076069199,87015262142,87015443479,87710721250,87051416465,87025708551,87710225538,87085368002,87475997924,87015165084,87014501412,87787886032,87754559096,87473287595,87029218917,87770716314,87077220492,87016495563,87771103085,87753694605,87719922222,87784180163,87013697192,87016051438,87027428444,87089025464,87023867426,87761112070,87024995555,87074401143,87029156144,87772770570,87078883001,87754418430,87012312609,87779748888,87021101041,87755035050,87787789988,87026577622,87777519595,87023769132,87756296331,87017610882,87012221539,87012444707,87007000027,8777011117,87075695800,87015240264,87016343456,87073451636,87058720963,87013956711,87021332223,87078116640,87750005882,87017550209,87750691387,87025224470,87018132809,87752095242,87787207878,87751111119,87752095242,87710551444,87071810315,87760081007,87014548565,87027458545,87017480177,87084250480,87015553009,87023846461,87756033043,87014943719,87767021000,87015343311,87015343311,87015230076,87758803081,87023028683,87013659785,87015588944,87003753752,87750937731,87782658655,87015644300,87029991536,87758958177,87772494045,87078894433,87758270648,87010981547,87758268057,87766699949,87015581255,87017310032,87022147020,87775772255,87054444044,87017511002,87751914648,87719092588,87012971021,87026035499,87019210188,87027237373,8701511464,87761797799,87017622995,87088881111,87006877984,87079195999,87073939993,87775376981,87786018742,87087188556,87010443300,87022577025,87017775607,87000999566,87779509154,87781344433,87761666888,87006877984,87013806244,87015224241,87477731077,87782758577,87777558585,87017551877,87029081424,87055551516,87770015577,87025862925,87012223268,87010777575,87756233405,87015557179,87753999395,87014157311,87018139243,87014671267,87786631144,87015668961,87719996052,87024097318,87022092980,87021203238,87016074207,87761077733,87015218064,87759027104,87012188550,87028492363,87020002607,87011776757,87026318682,87012220005,87714163155,87710204211,87083929812,87755227701,87081019667,87017984907,87015366310,87055409747,87719210151,87081019667,87015444784,87761857270,87758888395,87075559103,87078612355,87017000888,87016664762,87777627999,87088208008,87789057979,87756442810,87757070666,87012833365,87058636969,87015200053,87013653683,87757831720,87017111154,87786999176,87013920368,87075362656,87012185465,87752107592,87055445298,87074900800';

$name = 'АБЫЛАЙ,АДИЛЬБЕК,АДИЛЬХАН,АЖАР,АЗАМАТ,АЗАМАТ,АЗАМАТ,АЗАМАТ,АЗАМАТ,АЗАМАТ,АЗАМАТ,АЗАМАТ,АЗАТ,АЗАТ,АЗАТ,АЗАТ,АЗИЗА ,АЙБЕК,АЙБОЛ,АЙГАНЫМ,АЙГЕРИМ,АЙГЕРИМ,АЙГЕРИМ,АЙГЕРИМ,АЙГЕРИМ,АЙГУЛЬ,АЙГУЛЬ,АЙГУЛЬ,АЙГУЛЬ,АЙГУЛЬ,АИДА,АЙДАНА,АЙДАНА,АЙДАНА,АЙДАНА,АЙДАНА,АЙДАР,АЙДЫН,АЙЖАН,АЙЖАН,АЙЗАДА,АЙМАН,АЙМАН,АЙНА,АЙНАГУЛЬ,АЙНАГУЛЬ,АЙНАГУЛЬ,АЙНЕЛЬ,АЙНУР,АЙНУР,АЙНУР,АЙНУРА,АЙНУРА,АЙНУРА,АЙНУРА,АЙНУРА,АЙНУРА,АЙНУРА,АЙСАУЛЕ,АЙСУЛУ,АЙСУЛУ,АКБАЙ,АКЕРКЕ,АКЖАРКЫН,АКЖАРКЫН,АКМАРАЛ,АКСЕЛЕУ,АКЫЛБЕК,АЛАУСА,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕКСАНДР,АЛЕНА,АЛИБЕК,АЛИБЕК,АЛИМЖАН,АЛИНА,АЛИШЕР,АЛИЯ,АЛИЯ,АЛИЯ,АЛИЯ,АЛИЯ,АЛМАГУЛЬ,АЛМАГУЛЬ,АЛМАГУЛЬ,АЛМАЗ,АЛМАТ,АЛМАШ,АЛТАЙ,АЛТЫН,АЛТЫН,АЛТЫНАЙ,АЛТЫНБЕК,АЛТЫНБЕК,АЛТЫНБЕК,АЛТЫНГУЛЬ,АЛУА,АЛЬКЕН,АЛЬФИЯ,АМАН,АМИНА,АМИНА,АМИР,АНАРА,АНАРА,АНАРА,АНАРА,АНАРА,АНАРА,АНАРГУЛЬ,АНАРГУЛЬ,Анас,Анастасия,Анастасия корп,Жалелов Ерлан ,Анастасия Тимбилд,Анатолий,Анвар,Андрей ЖБ,Андрей ,Андрей 31.7.,Андрей ,Андро,Анеля,Анеля мариот,Аниматор,Аниматоры,Анис,Анна,Анна,Анна,Анна,Антон,Ануар,Ануар,Ануар,Ануар,Ануар,Ануар Сауна,Ануар Сауна,Ануар Сауна,Ануар сосед,Аня,Апок,Арай,Арай,Арай,Арай ,Ардак,Ардак,Ардак,Ардак,Ардак,Ардак,Ардак сауна,Ардашка,Арман,Арман,Арман ,Арман,Арман Рес,Арман,Арман,Арман,Арман Пирс,Арман Пшен,Армангуль,Арман,Арнат,Арнейка,Арнур,Арсен,Арта,Артем,Артем Сауна,Аружан,Аруна,Арыстан,Аселя,Асель,Асель ,Асель рест,Асель бес,Асель,Асель,Асель,Асель ,Асель беседка,Асель,Асель,Аселя,Аселя,Аселя,Аселя ,Асем,Асем,Асема,Асема,Асема,Асема,Асема,Асема,Асемгуль,Асет,Асет,Асия,Асия,Аскар,Аскар,Аскар,Аскар,Аскар,Аскар,Аскар,Аскар,Аскар,Аскар,Амир,Аскар,Аслан,Аслан,Аслан,Аслан,Асланбек,Асланбек,Аслан,Аслан,Аслан,Асланбек,АстанаДез,Астана лифты,Асхат,Асхат,Асхат,Асхат,Асхат,Асхат,Асхат,Асхат,Асхат,Асыл,асыл,Ася,Ася,Ателье,Атырбек,Алмат,Ахат,Аягоз,Аян,Аян,Багдат,Багдаулет,Багдаулет,Баглан,Балжан,Балжан,Балнур,Бану,Батырбек домик,Батырбек,Батырхан,Баур,Бауржан,Бауржан,Бауржан Вип,Бауржан,Бауржан,Бауржан,Бахарай сауна,Бахтияр,Бахыт ,Бахыт,Бахыт,Бахыт,Бахыт,Бахыт сауна,Бахытгуль,Бахытжан,Бахытжан,Бахытжан,Бахытжан,Баян,Богенбай,Бейбит,Бейжигит,Бекжан,Бекзат,Берик,Берик,Берик сауна,Берик,Бибигуль,Бибигуль,Богенбай,Болат,Болат,Бота,Бота,Бота,Бота,Ботагоз,Булат,Булат,Вадим,Вадим,Валентина,Валентина,Валерия,Валихан,Валишин,Венера,Венера,Венера,Вероника,Вероника,Вероника,Виктор сауна,Виктория,Виктория,Виктория,Виктория домик,Виктория,Венера,Виолетта,Виолетта,Виталий сауна,Владимир,Владимир сауна,Володя шатер,Вячеслав,Вячеслав,Габидали,Габиден,Галия,Галым,Гахар,Георгий,Гибрат,Гульбаршын,Гульбаршын ,Гульдана,Гульжан,Гульжан,Гульжан сауна,Гульжан,Гульжан,Гульмира,Гульмира,Гульмира,Гульмира,Гульмира,Гульмира домик,Гульназ,Гульназ,Гульнар ,Гульнара,Гульнара,Гульнара домик,Гульнара,Гульнара,Гульнара,Гульнара домик,Гульнара шатер,Гульнур,Гульнур,Гульсана,Гульсана,Гульсум,Гульфайруз,Гульфарида,Гуля,Данияр,Дана,Данияр,Дарига,Дархан,Дарья,Даулет,Даурен,Даурен,Даурен,Диана,Дидар,Диляра,Дина,Дина,Динара,Динара,Динара,Динара,Динара,Евгений,Евгений домик,Евгения,Евгения,Евгения,Едиль,Елена,Елена,Елена,Елена,Елена,Елизавета,Ербол,Ербол Вип,Ербол,Ербол,Ербол,Ербол сауна,Ербол сауна,Ергали домик,Ереке,Ержан,Ержан домик,Ержан домик,Ержан ресторан,Ержан домик,Ержан сауна,Ержан сауна,Ержан сауна,Ержан сауна,Ержан Талгат домик,Ержан Тунгус,Ержигит,Ерик,Ерик Ковалев,Ерик Мекишев,Ерик,Ерико,Ерканат,Еркебулан домик,Еркебулан,Еркен сауна,Еркеш,Еркен ,Ерлан,Ерлан,Ерлан домик,Ерлан,Ерлан,Ерлан,Ерлан Китай,Ерлан Рахымжанович,Ерлан сауна,Ермек сауна,Ермек домик,Ермек домик,Ермек,Ермек новый басс,Ермек сауна,Ермек шатер,Ернар,Ернар шатер,Ернар,Ернар беседка,Ернат,Есенбол,Жазира,Жанар,Жанара беседка,Жанара стекляшка,Жанара,Жанара,Жанат,Жанат,Жанат,Жангельды домик,Жанибек,Жанибек домик,Жанибек беседка,Жанибек,Жанибек,Жанибек,Жанибек домик,Жанна,Жанна беседка,Жанна,Жанузак Толегенович,Жарасхан,Жасик,Жаслан,Жасулан,Жасур,Жасын сауна,Женис,Женя,Женя,Женя,Женя домик,Жибек ресторан,Жибек,Жомарт домик,Жадыра,Жука,Жулдыз,Жулдыз Амбала,Жулдыз,Жулдыз,Жулдыз,Жулдызай,Жума,Жума,Жума,Жумабек,Жуматай,Жупархан,Закер сауна,Зарема,Зарема,Зарина ресторан,Зарина беседка,Зауре беседка,Зауре беседка,Зауре домик,Зауреш,Зульфия,Ильгиза беседка,Индира,Иван,Иван,Ивена,Игорь Вип,Ильгам,Ильхом,Ильяс,Ильяс,Имад,Иман,Имран,Инагари домик,Инара,Инара,Инат сауна,Индира беседка,Индира беседка,Индира домик,Индира,Инесса,Инна,Инсайд,Инна,Ирина,Ирина домик,Ирина,Ирина,Ирина,Ислам сауна,Кайнар,Кайрат Вип,Кайрат,Какимова,Казбек,Казбек,Кайрат,Кайрат,Кайрат сауна,Кайрат,Кайрат,Кайрат,Кайсар домик,Камал,Камила,Камила беседка,Камила домик,Камила,Камила,Камиля беседка,Канагат,Канагат,Канат Пирс,Канат Вип,Канат,Канат,Канат,Канат,Канат,Канат сауна,Канаш сауна,Карина,Карлыгаш,Карлыгаш домик,Карлыгаш,Карлыгаш,Катана Пирс,Катерина,Катерина,Катира,Келден,Ким,Кокем домик,Колганат беседка,Константин,Константин,Костя,Кристина,Ксения Вип,Ксюша,Куандык,Куандык шатер,Куандык домик,Куандык шатер,Куаныш,Куаныш,Куаныш,Куаныш,Куаныш,Куат,Куат,Куатбек,Кунсулу,Куралай,Кымбат домик,Кымбат домик,Лаки,Лаура беседка,Лаура,Лейла,Лена,Любовь домик,Людмила,Лальжи,Лора Мазеевна,Мадина домик,Мадина,Макпал,Макс,Максат,Максат сауна,Максат,Максат,Манат беседка,Манат домик,Маншук,Марат КНБ,Марат КНБ,Маржан,Марина,Марина,Марлен беседка,Макпал,Медет,Меркен,Меруерт,Мира,Мира,Мирас домик,Мирас домик,Михаил домик,Миша Пак,Мурат,Назерке,Назия домик,Наиля беседка,Нара беседка,Наталья,Наталья,Неля,Никита,Николай,Нурбек домик,Нкржан Арыстанбекович,Нурлан,Оксана,Оксана,Олжас домик,Олжас сауна,Олжас,Олжас сауна,Олжас домик,Олжас сауна,Олжас сауна,Олжас,Олжас Алимжанов,Олжас,Олжас Есмагамбетов,Ольга,Ольга домик,Ольга,Ольга,Ольга,Ольга,Ольга,Ольга,Ольга,Ольга,Орынбасар,Ослик,Павел Соколов,Раксана,Рамазан домик,Рауан,Раушан,Рахат,Рашид,Ренат,Роза домик,Роза домик,Роза,Рома,Рома,Роман,Руслан,Руслан,Рустам,Рустем домик,Рустем Омаров,Рустем,Сабит,Сабит,Сабыржан домик,Саид домик,Сайран,Салима домик,Салтанат,Салтанат,Самал,Самал,Самат,Самат Вип,Самат домик,Сандугаш,Сандугаш,Санжар,Санжар домик,Санжар,Санжар сауна,Сания,Сания беседка,Сания,Сания,Сантамария,Сапаргали домик,Сара,Сауле,Сауле,Сауле,Сауле,Сауле,Сауле,Сауле домик,Сауле,Сауле,Сауле,Сауле,Сауле,Сауле,Саят,Саят,Саят,Света,Светлана,Светлана домик,Светлана домик,Светлана домик,Светлана,Светлана,Светлана,Сева,Сейфула,Сергей,Сергей беседка,Сергей,Сергей,Сергей,Серега,Серик,Серик сауна,Серик,Сиезбек,Сосик,Станислав,Станислав домик,Станислав,Султан домик,Султан домик,Сульфия,Сымбат,Сымбат домик,Сырым,Талгат домик,Талгат домик,Талгат,Талгат,Талгат,Талгат Кенжегалиев,Талгат,Таскын,Таттимбет,Темирлан,Тилек,Тимур домик,Тлеухан,Тогжан,Толеген домик,Толкын шатер,Таир,Тайр,Талгат сауна,Талгат шатер,Таскын,Татьяна Власенко,Татьяна ,Татьяна,Темирхан сауна,Тимур сауна,Тимур беседка,Толганай,Торебек,Турлыбек,Турсун,Турсын сауна,Тымыс,Уали,Уалихан сауна,Улжан,Ултай,Умит,Ужымхан,Улан,Фазыл,Фариза Спанова,Фарида,Фарида,Фархад,Фарида,Хасан шатер,Хайдар домик,Хабиба,Хайдар домик,Чингиз сауна,Чингиз домик,Чингиз,Чингиз,Шолпан сауна,Шолпан ресторан,Шолпан,Шолпан,Шаяхмет,Шокан,Шынар,Шынар домик,Шынар,Шынара домик,Шокан,Эльвира домик,Эльвира,Эльвира,Эльдар,Эльдар,Эльдар Вип,Эльдар,Эльдар,Эльмара,Эльмира,Эльнара,Эридина,Юлия,Юлия,Юлия,Юлия,Юля,Юля,Юля,Юля домик,Юрий,Юрий,Юрий беседка,Юрий,Ырымтай';


		$mobile = explode(",", $namber);
		$names = explode(",", $name);



for ($i=0; $i<sizeof($mobile); $i++)
{
	$string = str_replace(' ','',$mobile[$i]);
	$str = substr_replace($string, '+7-', 0, 1);
	$one =  substr($str,  0, 6);
	$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

	$mobiles[0] = $rel;
	$namest[0] = $names[$i];


	$r_m = serialize($mobiles);
	$r_n = serialize($namest);

	$cdate = sqlDateNow();

//page_id, cdate, edate, name_company, data_name, data_mobile, data_email, type_company_id, type_company_portrait_id, visible

$sql = "INSERT INTO {$CFG->DB_Prefix}news (page_id, cdate, edate, name_company, data_name, data_mobile, type_company_id, type_company_portrait_id, visible, manager_id, city_id, info)
										VALUES (868, '{$cdate}', '{$cdate}', '{$names[$i]}', '{$r_n}', '{$r_m}', 10011925, 53, 1, 85, 4, 'Старый клиент ЧП')";
//$CFG->DB->query($sql);

	$mobiles[0] = '';
	$namest[0] = '';


}

exit;


/* foreach($tel as $v)
{
	$str = substr_replace($v, '+7-', 0, 1);

	$one =  substr($str,  0, 6);

	$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

	  $com = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE data_mobile LIKE '%{$rel}%' AND visible='1' AND page_id = 868 ");

	print_r($com);
}

*/


		exit;

	break;
	/* Извещения */





	case 'reload' :


	print_r(123);

	//echo $_POST["url"].'json';


	//$file = file_get_contents($_POST["url"].'json');

	//print_r($file);

	exit;
	break;





	case 'json_get_messages' :
	//https://domainscrm.ru/static/json_get_messages/40901/9zd6ch0nlqqbd208/+77010320320
	//https://domainscrm.ru/static/json_get_messages/40900/r0wtptu1au814f54/+77755475012
exit;

		$url = "https://eu36.chat-api.com/instance".$CFG->_GET_PARAMS[1]."/messages?token=".$CFG->_GET_PARAMS[2]."&last";

		$file = file_get_contents($url);
		$json_a = json_decode($file, true);
		$array  = $json_a["messages"];

		foreach ($array as &$value)
		{

			if($value['fromMe'] != '' ) continue;

			$o = getSQLRowO("SELECT count FROM {$CFG->DB_Prefix}tmp_whatsapp_robot WHERE visible= 1 AND namber = {$CFG->_GET_PARAMS[4]} order by id desc");

			if($value['messageNumber'] > $o->count )
			{
				$string = str_replace(' ','',$value['chatId']);
				$str = substr_replace($string, '+7-', 0, 1);
				$one =  substr($str,  0, 6);
				$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

				$res = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}news WHERE visible='1' AND page_id='868' AND data_whatsapp LIKE '%{$rel}%'  ORDER BY cdate ASC");
				if(count($res) > 0)
				{
					$body = $CFG->DB->escape($value["body"]);
					$cdate = sqlDateNow();

					$comm = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE visible='1' AND page_id='{$res->id}' AND whatsapp = 1 ORDER BY cdate DESC");

					$sql = "INSERT INTO {$CFG->DB_Prefix}alarm_whatsapp (page_id, user_id, autor_id, status, text, cdate, visible) VALUES ('{$res->id}', '{$comm->user_id}', 561, 0, '{$body}', '{$cdate}', 1)";
					$CFG->DB->query($sql);


					$nextWeek = $value[time] + (0 * 24 * 60 * 60);
					$cdate = date('Y-m-d H:i:s', $nextWeek);

					switch ($value['type'])
					{
						case 'chat':
							$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, cdate, whatsapp, visible, whatsapp_from, whatsapp_namber) VALUES ('{$res->id}', 868, 561, '{$body}', '{$cdate}', 2, 1, '{$CFG->_GET_PARAMS[3]}', '{$rel}')";
							$CFG->DB->query($sql);
						break;


						case 'image':
							$name_file = md5(time());

							$url = $value['body'];
							$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/85/'.$name_file.'.jpg';
							$path_site = '/documents/whatsapp/85/'.$name_file.'.jpg';
							file_put_contents($path, file_get_contents($url));

							if($value['caption'] != '')
							{
								$caption = $value['caption'];
							}
							else {$caption = 'Абонент отправил вам картинку';}

							$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attachments_image, cdate, whatsapp, visible, text, whatsapp_from, whatsapp_namber) VALUES ('{$res->id}', 868, 561, '{$path_site}', '{$cdate}', 2, 1, '{$caption}', '{$CFG->_GET_PARAMS[3]}', '{$rel}')";
							$CFG->DB->query($sql);
						break;


						case 'ptt':
							$name_file = md5(time());

							$url = $value['body'];
							$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/85/'.$name_file.'.mp3';
							$path_site = 'documents/whatsapp/85/'.$name_file.'.mp3';
							file_put_contents($path, file_get_contents($url));

							$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, cdate, original, text, type, user_id, visible) VALUES ('{$res->id}', '{$cdate}', 'Голосовое сообщение', '{$path_site}', 'mp3', 561, 1)";
							$CFG->DB->query($sql);

							$ptt = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}attachments WHERE visible= 1 order by id desc");
							$file = ','.$ptt->id;

							if($value['caption'] != '')
							{
								$caption = $value['caption'];
							}
							else {$caption = 'Абонент отправил вам голосовик';}

							$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attach_files_music, cdate, whatsapp, visible, text, whatsapp_from, whatsapp_namber) VALUES ('{$res->id}', 868, 561, '{$file}', '{$cdate}', 2, 1, '{$caption}', '{$CFG->_GET_PARAMS[3]}', '{$rel}')";
							$CFG->DB->query($sql);
						break;

						default:
							if($value['body'] != '')
							{
								$ext = pathinfo($value['body'], PATHINFO_EXTENSION);
								$new_string = preg_replace("/\?.+/", "", $ext);


								$name_file = md5(time());
								$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/85/'.$name_file.'.'.$new_string;
								$path_site = 'documents/whatsapp/85/'.$name_file.'.'.$new_string;
								file_put_contents($path, file_get_contents($value['body']));

								$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, cdate, original, text, type, user_id, visible) VALUES ('{$res->id}', '{$cdate}', 'Файл', '{$path_site}', '{$new_string}', 561, 1)";
								$CFG->DB->query($sql);

								$ptt = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}attachments WHERE visible= 1 order by id desc");
								$file = ','.$ptt->id;

								if($value['caption'] != '')
								{
									$caption = $value['caption'];
								}
								else {$caption = 'Абонент отправил вам файл';}

								$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attachments_file, cdate, whatsapp, visible, text, whatsapp_from, whatsapp_namber) VALUES ('{$res->id}', 868, 561, '{$file}', '{$cdate}', 2, 1, '{$caption}', '{$CFG->_GET_PARAMS[3]}', '{$rel}')";
								$CFG->DB->query($sql);
							}
						break;
					}

					$sql = "INSERT INTO {$CFG->DB_Prefix}tmp_whatsapp_robot (count, visible, namber) VALUES ('{$value[messageNumber]}', 1, {$CFG->_GET_PARAMS[4]})";
					$CFG->DB->query($sql);

				}
				else
				{
					$string = str_replace(' ','',$value['chatId']);
					$str = substr_replace($string, '+7-', 0, 1);
					$one =  substr($str,  0, 6);
					$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

					$cdate = sqlDateNow();

					$name[0] = '';	$names = serialize($name);
					$mobile[0] = $rel;	$mobiles = serialize($mobile);
					$whatsapp[0] = $rel;	$whatsapps = serialize($whatsapp);

					$sql = "INSERT INTO {$CFG->DB_Prefix}news (page_id, cdate, edate, visible, data_name, data_mobile, data_whatsapp, name_company, info, type_company_id, manager_id) VALUES ('868', '{$cdate}', '{$cdate}', 1, '{$names}', '{$mobiles}', '{$whatsapps}', 'Новый клиент Whatsapp {$rel}', 'Новый клиент написал в Whatsapp с номера {$rel}', 10011925, 561)";
					$CFG->DB->query($sql);
				}
			}
		}




		exit;

	break;




	case 'json_get_messages_2' :

		$url = "https://eu36.chat-api.com/instance40900/messages?token=r0wtptu1au814f54&last";
		$file = file_get_contents($url);
		$json_a = json_decode($file, true);
		$array  = $json_a["messages"];


foreach ($array as &$value)
{
	if($value['fromMe'] != '' ) continue;

	$o = getSQLRowO("SELECT count FROM {$CFG->DB_Prefix}tmp_whatsapp_robot WHERE visible= 1 AND namber = 2 order by id desc");

	if($value['messageNumber'] > $o->count )
	{
		$string = str_replace(' ','',$value['chatId']);
		$str = substr_replace($string, '+7-', 0, 1);
		$one =  substr($str,  0, 6);
		$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

		$res = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}news WHERE visible='1' AND page_id='868' AND data_whatsapp LIKE '%{$rel}%'  ORDER BY cdate ASC");
		if(count($res) > 0)
		{
			$nextWeek = $value[time] + (0 * 24 * 60 * 60);
			$cdate = date('Y-m-d H:i:s', $nextWeek);

			if($value['type'] == 'chat')
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, cdate, whatsapp, visible, whatsapp_from, whatsapp_namber) VALUES ('{$res->id}', 868, 561, '{$value[body]}', '{$cdate}', 2, 1, '+77755475012', '{$rel}')";
				$CFG->DB->query($sql);
			}

			if($value['type'] == 'image')
			{
				$name_file = md5(time());

				$url = $value['body'];
				$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/85/'.$name_file.'.jpg';
				$path_site = '/documents/whatsapp/85/'.$name_file.'.jpg';
				file_put_contents($path, file_get_contents($url));

				if($value['caption'] != '')
				{
					$caption = $value['caption'];
				}

				$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attachments_image, cdate, whatsapp, visible, text) VALUES ('{$res->id}', 868, 561, '{$path_site}', '{$cdate}', 1, 1, '{$caption}')";
				$CFG->DB->query($sql);
			}

			if($value['type'] == 'ptt')
			{
				$name_file = md5(time());

				$url = $value['body'];
				$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/85/'.$name_file.'.mp3';
				$path_site = 'documents/whatsapp/85/'.$name_file.'.mp3';
				file_put_contents($path, file_get_contents($url));

				$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, cdate, original, text, type, user_id, visible) VALUES ('{$res->id}', '{$cdate}', 'Голосовое сообщение', '{$path_site}', 'mp3', 85, 1)";
				$CFG->DB->query($sql);

				$ptt = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}attachments WHERE visible= 1 order by id desc");
				$file = ','.$ptt->id;

				$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attach_files_music, cdate, whatsapp, visible) VALUES ('{$res->id}', 868, 561, '{$file}', '{$cdate}', 1, 1)";
				$CFG->DB->query($sql);
			}


			$sql = "INSERT INTO {$CFG->DB_Prefix}tmp_whatsapp_robot (count, visible, namber) VALUES ('{$value[messageNumber]}', 1, 2)";
			$CFG->DB->query($sql);
		}


	}
}



		exit;

	break;


	case 'push_whatsapp' :

		$user_id = $_POST["user_id"];

		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}alarm_whatsapp WHERE user_id='{$user_id}' and status = 0 order by id DESC");

		if(count($o) > 0)
		{
			$response = json_encode(array('name' =>'*'.$o->page_id, 'body' => ''.$o->text.'', 'url' => '/count_alarm_whatsapp/record/'.$o->page_id.'/'.$o->id.'', 'status' => 1 ));
		}

		echo $response; exit;

	break;






	/*  Прочитали извещения */
	case 'off_alarm' :
		$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}alarm WHERE id='{$CFG->_GET_PARAMS[1]}'");
		$query  = "UPDATE {$CFG->DB_Prefix}alarm SET visible = 1  WHERE id='{$CFG->_GET_PARAMS[1]}'";
		$CFG->DB->query($query);
		redirect('/deal/'.$o->page_id);
	break;
	/*  Прочитали извещения */


	/*  Не одобрили сделку  */
	case 'off_alarm_black' :
		$id = $_POST["id"];
		$text = $_POST['text'];
		$cdate = sqlDateNow();
		$user_id = $CFG->USER->USER_ID;

		$query  = "UPDATE {$CFG->DB_Prefix}news SET open = 0  WHERE id='{$id}'";
		$CFG->DB->query($query);


		$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, cdate, visible) VALUES ('{$id}', 1000, '{$user_id}', '{$text}', '{$cdate}', 1)";
		if($CFG->DB->query($sql))
			echo 1;
		else
			echo 0;
		exit;
	break;
	/*  Прочитали извещения */

	/*  Одобрили сделку  */
	case 'on_alarm_black' :

		$query  = "UPDATE {$CFG->DB_Prefix}news SET open = 2  WHERE id='{$CFG->_GET_PARAMS[1]}'";
		$CFG->DB->query($query);

		$CFG->STATUS->OK = true;
		$CFG->STATUS->MESSAGE = 'Сделка отправлена на модерацию к директору!';

		redirect('/deal/'.$CFG->_GET_PARAMS[1]);
		exit;
	break;
	/*  Одобрили сделку  */

	/* Босс одобрил закрытие сделки  */
	case 'on_alarm_boss' :
		$query  = "UPDATE {$CFG->DB_Prefix}news SET open = 3  WHERE id='{$CFG->_GET_PARAMS[1]}'";
		$CFG->DB->query($query);
		$CFG->STATUS->OK = true;
		$CFG->STATUS->MESSAGE = 'Сделка закрыта!';
		redirect('/deal/'.$CFG->_GET_PARAMS[1]);
		exit;
	break;
	/* Босс одобрил закрытие сделки  */


	/* Закрыть сделку */
	case 'open_deal' :
		$id = $_POST['id'];

		if($CFG->USER->USER_PAULS == 2)
		{
			$text = 'Сделка ЗАКРЫТА! Я все проверила, все документы на нужном месте, работы выполнены, оплаты прошли, логика и правила соблюдены.';
		}
		else if($CFG->USER->USER_PAULS == 1)
		{
			$text = 'Сделка ЗАКРЫТА! Я все проверил, все документы на нужном месте, работы выполнены, оплаты прошли, логика и правила соблюдены.';
		}
		else
		{
			$text = 'Сделка ЗАКРЫТА! Я все проверил(а), все документы на нужном месте, работы выполнены, оплаты прошли, логика и правила соблюдены.';
		}

		$row = getSQLRowO("SELECT * FROM my_news  WHERE id='{$id}' ");

		if($row->id > 0)
		{
			$cdate = sqlDateNow();

			$CFG->DB->query( "INSERT INTO my_comments (page_id, parent_id, user_id, text, cdate) VALUES ('{$row->id}', '{$row->page_id}', '{$CFG->USER->USER_ID}', '{$text}', '{$cdate}') 	");
			$z_id = $CFG->DB->lastId();


			$rows = getSQLRowO("SELECT * FROM my_users WHERE id='{$CFG->USER->USER_CURATOR}' AND visible = 1 ");
			if($rows)
			{
				$CFG->DB->query("INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES ({$CFG->USER->USER_ID}, '{$CFG->USER->USER_DIRECTOR_ID}', {$row->id}, {$z_id}, '{$cdate}', 1 )");
				$f_id = $CFG->DB->lastId();
			}
			else {
				$CFG->DB->query("INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES ({$CFG->USER->USER_ID}, '{$CFG->USER->USER_DIRECTOR_ID}', {$row->id}, {$z_id}, '{$cdate}', 1 )");
				$f_id = $CFG->DB->lastId();
			}


			$CFG->DB->query("UPDATE my_comments SET accessrecord = ',".$f_id."' WHERE id='{$z_id}'	");
			echo 1;

			$query  = "UPDATE my_news SET open = 3  WHERE id='{$row->id}'";
			$CFG->DB->query($query);
		}
		else
		{
			echo 0;
		}
		exit;
	break;
	/* Закрыть сделку */



	case 'off_crm' :
		$query  = "UPDATE {$CFG->DB_Prefix}options SET val = 0  WHERE xcode='site_on'";
		$CFG->DB->query($query);
		exit;
	break;

	case 'add_orders' :
			$text = $_POST['text'];
			$id = $_POST['id'];
			$cdate = sqlDateNow();

			$sql = "INSERT INTO {$CFG->DB_Prefix}orders (page_id, text, user_id, cdate) VALUES ({$id}, '{$text}', '{$CFG->USER->USER_ID}', '{$cdate}')";
			$CFG->DB->query($sql);

			$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}orders order by id DESC");
			echo $o->id;
		exit;
	break;



 	/* Поиск с стороних сайтов  */
	case 'search_signimpress' :

		header('Access-Control-Allow-Origin: https://signimpress.ru');

		if($_SERVER['HTTP_ORIGIN'] == 'https://signimpress.ru')
		{

			$apost = apost($_POST["kode"]);

		$search_word = trim($apost);
 		$search_where .= "AND (name_company LIKE '%{$search_word}%'
										OR name_director LIKE '%{$search_word}%'
										OR email LIKE '%{$search_word}%'
										OR other_email LIKE '%{$search_word}%'
										OR name_director_mobile LIKE '%{$search_word}%'
										OR name_director_email LIKE '%{$search_word}%'
										OR name_client LIKE '%{$search_word}%'
										OR name_client_mobile LIKE '%{$search_word}%'
										OR name_client_email LIKE '%{$search_word}%'
										OR info LIKE '%{$search_word}%'
										OR history LIKE '%{$search_word}%'
										OR contact LIKE '%{$search_word}%')
										";

        if (utf8_strlen($search_word) > 2)
        {
            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible='1' {$search_where} AND page_id = 868 order by edate DESC limit 10");
            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pid[] .= $sql[$i]->id;
            }

            $com = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE text LIKE '%{$search_word}%' AND visible='1' AND parent_id = 868  order by cdate DESC limit 10");
            for ($i=0; $i<sizeof($com); $i++)
            {
                $pid[] .= $com   [$i]->page_id;
            }

            $res = array_unique($pid);
        }

		if(count($res) > 0)
		{

			echo '<table class="respons">
					<tr>
						<th>Номер</th>
						<th>Клиент</th>
						<th>Город</th>
						<th>Дата созд.</th>
						<th>Дата редак.</th>
						<th>Менеджер</th>
						<th>Заметок</th>
					</tr>';

		   for ($i=0; $i<sizeof($res); $i++)
		   {
				$data = SelectDataRowOArray("news", $res[$i]);

				if($data->id == !'' && $data->page_id == 868)
				{
					?>
					<tr>
						<td><span>*<?=$data->id;?></span></td>
						<td><?=$data->name_company;?></td>
						<td><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}city WHERE id='{$data->city_id}'");		echo $o->name;?></td>
						<td><? echo dateSQL2TEXT($data->cdate, "DD.MM.YY").' '.dateSQL2TEXT($data->cdate, "hh:mm").'';?></td>
						<td><? echo dateSQL2TEXT($data->edate, "DD.MM.YY").' '.dateSQL2TEXT($data->edate, "hh:mm").'';?></td>
						<td><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$data->manager_id}'");		echo $o->name;?></td>
						<td><?=SelectDataCount('comments', 'page_id', $data->id);?></td>
					</tr>

					 <?
				}
			}
			echo '</table>';

		}
		else
		{
			echo '<div style="margin-top:40px;">Не найдено!</div>';
		}



		}
		exit;
	break;
	/* Поиск с стороних сайтов  */



 	/* Пополнение Кешбека */
	case 'cashback_record' :
		header('Access-Control-Allow-Origin: https://signimpress.kz');
		if($_SERVER['HTTP_ORIGIN'] == 'https://signimpress.kz')
		{

			$mobile = $_POST['mobile'];
			$price = $_POST['price'];
			$info = $_POST['info'];

			$text = $price.',
			'.$mobile.',
		     '.$info;

			$cdate = sqlDateNow();

			$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, cdate, visible) VALUES (34388, 1000, 85, '{$text}', '{$cdate}', 1)";
			$CFG->DB->query($sql);

			$id = $CFG->DB->lastId();

			$sql = "INSERT INTO {$CFG->DB_Prefix}accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES (85, 85, 34388, {$id}, '{$cdate}', 1)";
			if($CFG->DB->query($sql))
				echo 1;
			else
				echo 0;
		}
		exit;
	break;
	/* Пополнение Кешбека */




 	/* Кешбек регистрация заявки с сайта */
	case 'cashback_reg' :
		header('Access-Control-Allow-Origin: https://signimpress.kz');
		if($_SERVER['HTTP_ORIGIN'] == 'https://signimpress.kz')
		{
			$name = $_POST['name'];
			$company = $_POST['company'];
			$mobile = $_POST['mobile'];

			$text = $name.',
			'.$company.',
		     '.$mobile;

			$cdate = sqlDateNow();

			$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, cdate, visible) VALUES (34363, 1000, 85, '{$text}', '{$cdate}', 1)";
			$CFG->DB->query($sql);

			$id = $CFG->DB->lastId();

			$sql = "INSERT INTO {$CFG->DB_Prefix}accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES (85, 85, 34363, {$id}, '{$cdate}', 1)";
			if($CFG->DB->query($sql))
				echo 1;
			else
				echo 0;
		}
		exit;
	break;
	/* Кешбек регистрация заявки с сайта */


 	/* Кешбек просмотр json*/
	case 'cashback_status_json' :


			//header('Access-Control-Allow-Origin: *');

			$mobile = $CFG->_GET_PARAMS[1];

			$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' order by cdate DESC");

			if(count($sql) > 0)
			{

				$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' AND status = 1 ");

				for($z=0;$z<sizeof($sql);$z++)
				{
					$sumplus[] = $sql[$z]->price;
				}

				$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' AND status = 6 ");

				for($y=0;$y<sizeof($sql);$y++)
				{
					$summinus[] = $sql[$y]->price;
				}

				$all = array_sum($sumplus) - array_sum($summinus);

				$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' order by cdate DESC");

				for($z=0;$z<sizeof($sql);$z++)
				{
					$sumplusArray[] = $sql[$z];
				}

				$response = array('mobile' => $mobile, 'sum' => $all);
 				$log = array();

                for($z=0;$z<sizeof($sumplusArray);$z++)
                {
                    if($sumplusArray[$z]->status == 1)
                    {
                         array_push($log, array('cdate' => $sumplusArray[$z]->cdate, 'sum' => $sumplusArray[$z]->price, 'status' => 'plus'));
                    }
                    if($sumplusArray[$z]->status == 6)
                    {

                         array_push($log, array('cdate' => $sumplusArray[$z]->cdate, 'sum' => $sumplusArray[$z]->price, 'status' => 'minus'));
                    }

                }

				$response['log'] = $log;
 				echo  json_encode($response);

				exit;
			}
			else
			{
				echo 'no money'; exit;
			}



	break;
	/* Кешбек просмотр */

 	/* Кешбек просмотр */
	case 'cashback_status' :

		header('Access-Control-Allow-Origin: https://signimpress.kz');

		$post = serialize($_POST);
		$cdate = sqlDateNow();

		$sql = "INSERT INTO {$CFG->DB_Prefix}cashback_history (body, cdate) VALUES ('{$post}', '{$cdate}')";
		$CFG->DB->query($sql);

		if($_SERVER['HTTP_ORIGIN'] == 'https://signimpress.kz')
		{
			//header('Access-Control-Allow-Origin: *');

			$mobile = strip_tags($_POST['mobile']);

			$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' order by cdate DESC");

			if(count($sql) > 0)
			{

				$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' AND status = 1 ");

				for($z=0;$z<sizeof($sql);$z++)
				{
					$sumplus[] = $sql[$z]->price;
				}

				$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' AND status = 6 ");

				for($y=0;$y<sizeof($sql);$y++)
				{
					$summinus[] = $sql[$y]->price;
				}

				$all = array_sum($sumplus) - array_sum($summinus);

				$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE mobile LIKE '%{$mobile}%' AND visible='1' order by cdate DESC");

				for($z=0;$z<sizeof($sql);$z++)
				{
					$sumplusArray[] = $sql[$z];
				}
				?>
                     <div class="col-md-12 textS">
                        <div class="obj">
                            <br clear="all"><br clear="all">
                            <p class="gray">Кешбек по номеру <span style="color:#F00"><?=$mobile;?></span>  составляет: <span style="color:#F00"><? echo $all;?> </span> тенге</p>
                        </div>

                        <div class="obj">
                        <br clear="all">
                            <?
                                for($z=0;$z<sizeof($sumplusArray);$z++)
                                {
                                    if($sumplusArray[$z]->status == 1)
                                    {
                                        ?> <p class="gray" style=" color:#090;"> <span>+</span> <?=dateSQL2TEXT($sumplusArray[$z]->cdate, "DD MN YYYY, hh:mm");?> зачислен кешбек в размере <?=$sumplusArray[$z]->price;?> тенге</p> <?
                                    }
                                    if($sumplusArray[$z]->status == 6)
                                    {
                                        ?> <p class="gray" style=" color:#F00;"> <span>-</span> <?=dateSQL2TEXT($sumplusArray[$z]->cdate, "DD MN YYYY, hh:mm");?> выдан кешбек в размере <?=$sumplusArray[$z]->price;?> тенге</p> <?
                                    }

                                }
                            ?>
                        </div>
                    </div>

				<?
				exit;
			}
			else
			{
				echo 0; exit;
			}


		}

	exit;
	break;
	/* Кешбек просмотр */


 	/* Выдача кешбека */
	case 'cashback_from' :
		$cdate = sqlDateNow();
		$time = time();
		$director = $CFG->USER->USER_DIRECTOR_ID;
		//$text = 'Выдан кешбек в размере '.$_POST[price].' '.$CFG->USER->USER_CURRENCY.', клиенту '.$_POST[mobile].', запись *'.$_POST[page_id].'';
		//$text_sms = 'Вам выдан кешбек '.$_POST[price].' '.$CFG->USER->USER_CURRENCY.'. www.forsign.kz';
		$o = getSQLRowO("SELECT accounting FROM {$CFG->DB_Prefix}company WHERE id='{$director}'");

		if($CFG->USER->USER_ID == 153)
		{
			$text_sms = 'Вам выдан кешбек '.$_POST[price].' '.$CFG->USER->USER_CURRENCY.'. www.sepcom.ru';
			send_sms($_POST[mobile], $text_sms);
			$status = 6;
		}	else {$status = 0;}

		$sql = "INSERT INTO {$CFG->DB_Prefix}cashback (text, price, page_id, user_id, mobile, status, visible, cdate) VALUES ('{$_POST[text]}', '{$_POST[price]}', '{$_POST[page_id]}', '{$CFG->USER->USER_ID}', '{$_POST[mobile]}', {$status}, 1, '{$cdate}')";
		$CFG->DB->query($sql);
		//send_sms($_POST[mobile], $text_sms);
		exit;

	break;
	/* Выдача кешбека */



 	/* Одобрение кешбека */
	case 'cashback_go' :
		if($_GET[type] == 1)
		{
			$query  = "UPDATE {$CFG->DB_Prefix}cashback SET  status = 2  WHERE id='{$_GET[id]}'";
			if($CFG->DB->query($query))
				echo 1;
			else
				echo 0;
		}
		if($_GET[type] == 2)
		{
			$query  = "UPDATE {$CFG->DB_Prefix}cashback SET  status = 3  WHERE id='{$_GET[id]}'";
			if($CFG->DB->query($query))
				echo 1;
			else
				echo 0;
		}
		exit;
	break;
	/* Одобрение кешбека */


 	/* Одобрение кешбека Азаматом */
	case 'cashback_go_tu' :
		if($_GET[type] == 1)
		{
			$query  = "UPDATE {$CFG->DB_Prefix}cashback SET  status = 4  WHERE id='{$_GET[id]}'";
			if($CFG->DB->query($query))
				echo 1;
			else
				echo 0;
		}
		if($_GET[type] == 2)
		{
			$query  = "UPDATE {$CFG->DB_Prefix}cashback SET  status = 3  WHERE id='{$_GET[id]}'";
			if($CFG->DB->query($query))
				echo 1;
			else
				echo 0;
		}
		exit;
	break;
	/* Одобрение кешбека Азаматом */

 	/* Выдача кешбека кассиром */
	case 'cashback_go_three' :

		if($_POST[type] == 1)
		{
			$query  = "UPDATE {$CFG->DB_Prefix}cashback SET  status = 6, textarea='$_POST[text]'  WHERE id='{$_POST[id]}'";
			if($CFG->DB->query($query))
				echo 1;
			else
				echo 0;
			$text_sms = 'Вам выдан кешбек '.$_POST[price].' '.$CFG->USER->USER_CURRENCY.'. www.signimpress.kz';
			send_sms($_POST[mobile], $text_sms);
		}
		else
		{
			$query  = "UPDATE {$CFG->DB_Prefix}cashback SET  status = 5, textarea='$_POST[text]'  WHERE id='{$_POST[id]}'";
			if($CFG->DB->query($query))
				echo 1;
			else
				echo 0;
		}
		exit;
	break;
	/* Выдача кешбека кассиром */




 	/* Начисление кешбека */
	case 'cashback' :

		$page_id = $_POST["page_id"];
		$my_cashback = $_POST["manager"];
		$price = $_POST["price"];
		$page_xcode = $_POST["page_xcode"];
		$cdate = sqlDateNow();

		$sql = "INSERT INTO {$CFG->DB_Prefix}cashback (price, page_id, user_id, mobile, status, visible, cdate) VALUES ('{$price}', '{$page_id}', '{$CFG->USER->USER_ID}', '{$my_cashback}', 1, 0, '{$cdate}')";
		$CFG->DB->query($sql);
        //print_r($sql);
		echo $CFG->DB->lastId();
		exit;

		break;
	/* Начисление кешбека */



		case 'send_price' :

		if($_POST['mobile'] != '')
		{
			$mobile = $_POST['mobile'];
			$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}face WHERE  page_id = 1012 AND visible = 1  AND `mobile` LIKE '%{$mobile}%'   ");
			$response = json_encode(array('skidka' => $o->skidka_led, 'array' => $o));
			echo $response; exit;
		}
			exit;
		break;



		case 'led_ru_send_pin' :

			if($_POST['mobile'] != '')
			{
				$mobile = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $_POST['mobile']);
				$password = $_POST['password'];
				$ap_mobile = $_POST['mobile'];

				$res = getSQLRowO("SELECT * FROM my_face WHERE  mobile LIKE '%{$ap_mobile}%' AND visible = 1   ");

				if($res->skidka_led*1 > 0)
				{
					echo json_encode(array('type' => 1	));
				}
				else
				{
					 echo json_encode(array('type' => 0	));
				}
			}
			exit;
		break;


		case 'led_ru_send_zayavka' :
			if($_POST['mobile'] != '')
			{
				if(mailer('moscow@gkgd.ru', 'Новый заказ с сайта gkgd.ru', nl2br($_POST["text"])  ) == true )
				{
					$o = getSQLRowO("SELECT * FROM my_tmp_whatsapp_namber WHERE  namber = '+79620701030' AND visible = 1   ");
					$mobile = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $_POST['mobile']);
					$text = $_POST['text'];
					$data = ['phone' => $mobile, 'body'=> $text];
					$url = 'https://api.chat-api.com/instance'.$o->wp_id.'/message?token='.$o->token;
					whatsapp_send($data, $url);

				 	return true;
				}
				else
				{
				  return false;
				}

			}
			exit;
		break;



	case 'page_id_cat' :

		$page_id = $_POST["page_id"];

		$sql = getSQLArrayO("SELECT  * FROM {$CFG->DB_Prefix}type_company WHERE page_id = {$page_id} order by pos ASC");

		if(count($sql) > 0)
		{
			echo '<select name="type_company[]" class="selectpicker pager_page_id" multiple="multiple" title="Выберите категорию">';
			for($z=0;$z<sizeof($sql);$z++)
			{	?>

			  <option value="<?=$sql[$z]->id?>"><?=translit($sql[$z]->name);?></option>

			<? }
			echo '</select>';

		}
		else
		{
			echo 0;
		}

		exit;

	break;

	/* Редактирование название сделки*/
	case 'edit_deal' :
		$id = $_POST["id"];
		$text = $_POST["text"];
		$edit_price = $_POST["edit_price"];
		$user_id = $CFG->USER->USER_ID;

		for ($i=0; $i<sizeof($_POST['manager']); $i++)
		{
			$manager.= $_POST['manager'][$i].',';
		}

			if($_POST['manager'])
			{
				$full = '0,'.$user_id.','.$manager;	$big = trim($full, ',');
			}

		$query  = "UPDATE {$CFG->DB_Prefix}news SET name_company = '{$text}', access_deal = '{$big}', price = '{$edit_price}'  WHERE id='{$id}'";
		if($CFG->DB->query($query))
			echo 1;
		else
			echo 0;
		exit;

	break;
	/* Редактирование название сделки*/


	case 'notebook' :

		if($CFG->USER->USER_NOTEBOOK == 0)
		{
			$cdate = sqlDateNow();


			$data = SelectDataRowOArray('users', $CFG->USER->USER_ID, 0);

			$sql = "INSERT INTO {$CFG->DB_Prefix}news (logo_company, logo_company_mini, page_id, cdate, edate, name_company, manager_id, type_company_id,  city_id) VALUES ('{$data->avatar}', '{$data->avatar}', 976, '{$cdate}', '{$cdate}', '{$CFG->USER->USER_NAME}, Блокнот', '{$CFG->USER->USER_ID}', '555', '{$data->city}' )";
			$CFG->DB->query($sql);

			$o = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}news WHERE manager_id='{$CFG->USER->USER_ID}' and page_id = 976 and type_company_id = 555 order by id DESC");

			$query  = "UPDATE {$CFG->DB_Prefix}users SET notebook = '{$o->id}' WHERE id='{$CFG->USER->USER_ID}'";
		    $CFG->DB->query($query);

			redirect('/office/'.$o->id);
		}
		else
		{

			$o = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}news WHERE manager_id='{$CFG->USER->USER_ID}' and page_id = 976 and type_company_id = 555 order by id DESC");

			redirect('/office/'.$o->id);
		}


		exit;

	break;


  case 'city' :

		$dataS = SelectDataParent('city','page_id',$CFG->_POST_PARAMS["country"]);

		if($dataS)
		{
			echo ' <select name="city" class="selectpicker show-tick" data-live-search="true">';
			echo ' <option value="0" selected>Любой</option>';

			for($z=0;$z<sizeof($dataS);$z++)
			{	?>

			  <option value="<?=$dataS[$z]->id?>"><?=translit($dataS[$z]->name);?></option>

			<? }
			echo '</select>';
			exit;
		}
		elseif($CFG->_POST_PARAMS["country"] == 0)
		{
			echo 0; exit;
		}
		else
		{
			echo '<select name="city" class="selectpicker show-tick selajax" data-live-search="true" title="Города отсутствуют" disabled="disabled" data-style="btn-inverse">';
			echo '</select>';

			exit;
		}
		exit;

	break;


	  case 'company' :

		if($CFG->_POST_PARAMS["company"] == 0)
		{
				echo 0; exit;
		}
		else
		{

			$dataS = SelectDataParent('users', 'user_id', $CFG->_POST_PARAMS["company"]);

			if($dataS)
			{
				echo ' <select name="users" class="selectpicker show-tick" data-live-search="true">';
				echo '<option value="0">'.$CFG->Locale["fi2"].'</option>';
				for($z=0;$z<sizeof($dataS);$z++)
				{	?>

				  <option value="<?=$dataS[$z]->id?>"><?=translit($dataS[$z]->name);?></option>

				<? }
				echo '</select>';
				exit;
			}
			else
			{

				exit;
			}

		}

			 exit;

		break;


		//Создаем сделку
		case 'app' :

		// attachments_file , attach_files_music
		$test = getSQLRowO(" SELECT * FROM my_comments WHERE visible = 0 order by attachments_image DESC ");

		//Удаляем фото
		if($test->attachments_image !='')
		{
			$filename  = $_SERVER['DOCUMENT_ROOT'].$test->attachments_image;

			if (file_exists($filename))
			{
					if(unlink($filename))
					{
							echo 'Файл '. $filename.' удален'.' - '. 'id комента '.$test->id.' - '.date('Y-m-d H:i:s').PHP_EOL.PHP_EOL;

						 $fp = fopen("file.txt", "a+");
						 fwrite($fp, 'Файл '. $filename.' удален'.' - '. 'id комента '.$test->id.' - '.date('Y-m-d H:i:s').PHP_EOL.PHP_EOL);
						 fclose($fp);
					}
			}
		}

		//Удаляем файл
		if($test->attachments_file !='')
		{
			$id = explode(",", $test->attachments_file);
			foreach ($id as $value)
			{
				if($value == '') continue;

				if(is_numeric($value) > 0)
				{
					$testFIL = getSQLRowO(" SELECT * FROM my_attachments WHERE id = '{$value}' ");

					$filename  = $_SERVER['DOCUMENT_ROOT'].'/'.$testFIL->text;
					if (file_exists($filename))
					{
						if(unlink($filename))
						{
							 echo 'ФАЙЛ '. $filename.' удален'.' - '. 'id комента '.$test->id.' - '.date('Y-m-d H:i:s').PHP_EOL.PHP_EOL;
							 $fp = fopen("file.txt", "a+");
							 fwrite($fp, 'ФАЙЛ '. $filename.' удален'.' - '. 'id комента '.$test->id.' - '.date('Y-m-d H:i:s').PHP_EOL.PHP_EOL);
							 fclose($fp);

							 $CFG->DB->query("DELETE FROM my_attachments WHERE id = '{$value}' ");
						}
					}
				}
			}

		}

		//Удаляем голосовик
		if($test->attach_files_music !='')
		{
			$id = explode(",", $test->attach_files_music);
			foreach ($id as $value)
			{
				if($value == '') continue;

				if(is_numeric($value) > 0)
				{
					$testMUS = getSQLRowO(" SELECT * FROM my_attachments WHERE id = '{$value}' ");

					$filename  = $_SERVER['DOCUMENT_ROOT'].'/'.$testMUS->text;
					if (file_exists($filename))
					{

						if(unlink($filename))
						{
							 echo 'Голосовик '. $filename.' удален'.' - '. 'id комента '.$test->id.' - '.date('Y-m-d H:i:s').PHP_EOL.PHP_EOL;
							 $fp = fopen("file.txt", "a+");
							 fwrite($fp, 'Голосовик '. $filename.' удален'.' - '. 'id комента '.$test->id.' - '.date('Y-m-d H:i:s').PHP_EOL.PHP_EOL);
							 fclose($fp);

							 $CFG->DB->query("DELETE FROM my_attachments WHERE id = '{$value}' ");
						}
					}
				}
			}

		}

		echo "DELETE FROM my_comments WHERE id='{$test->id}'";

		$CFG->DB->query("DELETE FROM my_comments WHERE id='{$test->id}'");

		exit;
		break;


	//Создаем сделку
	case 'add_deal' :

			$name = $_POST['text'];
			$price = $_POST['price'];
			$name_company = $_POST['name_company'];
			$name_real = $name;
			$id = $_POST['id'];
			$cdate = sqlDateNow();
			$user_id = $CFG->USER->USER_ID;

			for ($i=0; $i<sizeof($_POST['manager']); $i++)
			{
				$manager.= $_POST['manager'][$i].',';
			}

			$test = getSQLRowO(" SELECT page_id FROM my_news WHERE id = '{$id}' ");

			if($_POST['manager'])
			{
				$full = '0,'.$user_id.','.$manager;	$big = trim($full, ',');
			}

			if($price == ''){	$price = 0;	}


			$sql = "INSERT INTO {$CFG->DB_Prefix}news (deal_parent_id, price, access_deal, page_id, parent_id, name_company, manager_id, edate, cdate, visible) VALUES ('{$test->page_id}', '{$price}', '{$big}', 1000, '{$id}', '{$name_real}', '{$user_id}', '{$cdate}', '{$cdate}', 1)";

			if($CFG->DB->query($sql))
			{
				$data = getSQLRowO("SELECT id,manager_id FROM {$CFG->DB_Prefix}news ORDER BY cdate DESC ");
				print_r('/deal/'.$data->id);
			}
			else
			{
				echo 0;
			}

		exit;
	break;
		//Создаем сделку





	case 'report' :

			$day = date('d');
			$year = date('Y');
			$mech = date('m');

			$data = $year.'-'.$mech.'-'.$day.' 00:00:00';
			$data_end = $year.'-'.$mech.'-'.$day.' 23:59:59';

			$data_email = $year.'-'.$mech.'-'.$day;

			//488
			$query = getSQLArrayO("SELECT  * FROM {$CFG->DB_Prefix}comments WHERE visible = 1 AND user_id = 488 AND cdate > '{$data}' AND cdate < '{$data_end}' order by cdate DESC");

			$server = $_SERVER["HTTP_HOST"];

			if(date('N') <= 5 && count($query) > 0)
			{


				$html ='<style>
				table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:16px; text-align:center; margin-bottom:20px;  }
				table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align:middle; font-size:14px}
				table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:15px; padding:0; margin:0;  padding:10px 10px;  vertical-align:middle}
				</style>

				<table class="price">
				  <tr>
					<th>Time and data record</th>
					<th>Client ID</th>
					<th>Client company name</th>
					<th>City</th>
					<th>Comment</th>
				  </tr>';


			for ($i=0; $i<sizeof($query); $i++)
			{
				$o = $query[$i];
				$name = SelectDataRowOArray('news', $o->page_id);
				$city = SelectDataRowOArray('city', $name->city_id);
				$html .= '<tr>';
					$html .= '<td>'.$o->cdate.'</td>';
					$html .= '<td><a href="https://'.$server.getFullXCodeByPageId($o->parent_id).$o->page_id.'#'.$o->id.'">*'.$o->id.'</a></td>';
					$html .= '<td>'.$name->name_company.'</td>';
					$html .= '<td>'.$city->name.'</td>';
					$html .= '<td>'.$o->text.'</td>';
				$html .= '</tr>';


			}
				$html .='</table>';

				echo $html;

				mailer('gnezdilov.gena@mail.ru, 0077010323333@mail.ru, bayturin@mail.ru, onbon@onbonbx.com', "Report - ".$data_email, $html);

			}



		exit;
	break;


	case 'receipt' :

		if($CFG->USER->USER_ID)
		{
			$user_id = $CFG->USER->USER_ID;
			$name = serialize($_POST['name']);
			$count = serialize($_POST['count']);
			$price = serialize($_POST['price']);
			$cdate = sqlDateNow();

			$sql = "INSERT INTO {$CFG->DB_Prefix}receipt (user_id, name, counts, price, cdate) VALUES ('{$user_id}', '{$name}', '{$count}', '{$price}', '{$cdate}')";

			if($CFG->DB->query($sql))
			{
				$data = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}receipt ORDER BY cdate DESC ");


				print_r('/receipt/'.$data->id);
			}
			else
			{
				echo 0;
			}

		}
		exit;
	break;


	case 'edit_comment' :

print_r($_POST);

exit;


		if($CFG->USER->USER_ID)
		{
			$id = $_POST["id"];

			$data = getSQLRowO("SELECT *FROM {$CFG->DB_Prefix}comments WHERE id = {$id} ORDER BY cdate DESC ");

			$o =  getSQLArrayO("SELECT  * FROM {$CFG->DB_Prefix}news WHERE parent_id = {$data->page_id} order by  id DESC");

			if(count($o) == 0)
			{
				echo 0; exit;
			}
			else
			{
				for ($y=0; $y<sizeof($o); $y++)
				{
					?> <option value="<? echo $o[$y]->id; ?>"><? echo $o[$y]->name_company; ?></option> <?
				}
			}

		}
		exit;
	break;

	case 'edit_comment_2' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST["id"];
			$deal = $_POST["deal"];


			$data = getSQLRowO("SELECT *FROM {$CFG->DB_Prefix}comments WHERE id = {$id} ");
			$res = getSQLRowO("SELECT *FROM {$CFG->DB_Prefix}news WHERE id = {$deal} ");

			$c_data = strtotime($res->cdate);
			$n_data = strtotime($data->cdate);

			if($n_data < $c_data)
			{
				$query  = "UPDATE {$CFG->DB_Prefix}news SET cdate = '{$data->cdate}' WHERE id='{$deal}'";
				$CFG->DB->query($query);
			}

			$query  = "UPDATE {$CFG->DB_Prefix}comments SET page_id = '{$deal}', parent_id = 1000, parent_comment_id = 0 WHERE id='{$id}'";

			if($CFG->DB->query($query))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}


		}
		exit;
	break;






	case 'send_data_user' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST['user_id'];
			$view_deal_access = $_POST['view_deal_access'];
			$view_money = $_POST['view_money'];
			$view_prize = $_POST['view_prize'];
			$view_record = $_POST['view_record'];
			$view_note = $_POST['view_note'];
			$view_loader = $_POST['view_loader'];
			$view_factories = $_POST['view_factories'];
			$view_delete = $_POST['view_delete'];
			$view_whatsapp = $_POST['view_whatsapp'];
			$note_select_access = $_POST['note_select_access'];
			$view_warehouse = $_POST['view_warehouse'];
			$view_expenses_lil = $_POST['view_expenses_lil'];

			$view_ruk = $_POST['view_ruk'];
			$view_salary = $_POST['view_salary'];
			$view_goszakup = $_POST['view_goszakup'];
			$transfer_access = $_POST['transfer_access'];



			$query  = "UPDATE {$CFG->DB_Prefix}users SET view_money = '{$view_money}', view_deal_access = {$view_deal_access}, view_prize = {$view_prize}, view_record = {$view_record}, view_note = {$view_note}, view_loader = {$view_loader}, view_factories = {$view_factories}, view_delete = {$view_delete}, view_whatsapp = {$view_whatsapp}, note_select_access = {$note_select_access}, view_warehouse = {$view_warehouse}, view_ruk = {$view_ruk}	, view_salary = {$view_salary}, view_goszakup = {$view_goszakup}	, view_expenses_lil = {$view_expenses_lil}	, transfer_access = {$transfer_access} WHERE id='{$id}'";
			if($CFG->DB->query($query))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}

		}
		exit;
	break;


  case 'cdate' :

		if($CFG->_POST_PARAMS["position"] == 1)
		{
			$date = date("Y-m-d");

			echo '


		<div id="cdatehide">
			<div class="col-md-12">
                <p>От:</p>
                <input id="cdatestart" type="text" name="cdatestart" value="" placeholder="'.$date.'"  style="width:100%; padding:6px; padding-left:10px;" >
            </div>

            <div class="col-md-12">
                <p>До:</p>
                <input id="cdateoff" type="text" name="cdateoff" value="" placeholder="'.$date.'" style="width:100%; padding:6px; padding-left:10px;" >
            </div>
		</div>

			';
			exit;
		}
		else
		{

			exit;
		}


		 exit;

	break;

	case 'name' :

		$name = htmlspecialchars(trim(apost($_POST["name"])));

		$search_where = "AND (name_company LIKE '%{$name}%' )";

		$s = $NEWS->getList(868, 10, 0, '', '', '', $search_where);

		if(count($s) > 0)
		{
			for ($i=0; $i<sizeof($s); $i++)
			{
						$o = $s[$i];

				echo '<li class="'.$o->id.'">'.$o->name_company.'</li>';

			}
		}
		else
		{
			echo 0;
		}
		exit;

	break;

	case 'file_upload' :

		$max_size = (ini_get('post_max_size')*1024)*1024;

 		if($max_size >= $_FILES["file"]["size"] && is_numeric($_FILES["file"]["size"]) )
		{
				if( $CFG->USER->checkExtFile($_FILES['file']) == 'audio' )
				{
					if(is_numeric($_POST["pid_id"]))
						{
							$pid_id = $_POST["pid_id"];
							$filename = 'documents/record/' . $pid_id.'/';

							if (!file_exists($filename))
							{
								$oldumask = umask(0);
								mkdir($filename, 0777);
								chmod($filename, 0777);
								umask($oldumask);
							}

							$tmp_name  = $_FILES['file']['tmp_name'];
							$name = $_FILES['file']['name'];

							setlocale(LC_ALL, 'ru_RU.utf8');
							$path = pathinfo($name);

							$onurl = $filename.md5(rand(1,1000).time().$name).'.'.$path['extension'];

							move_uploaded_file($tmp_name, $onurl);


							$cdate = sqlDateNow();
							$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, user_id, original, type, text, cdate) VALUES ('{$pid_id}', '{$CFG->USER->USER_ID}', '{$path[filename]}', '{$path[extension]}', '{$onurl}', '{$cdate}')";
							$CFG->DB->query($sql);

						}

					$res = SelectDataParent('attachments', 'page_id', $_POST["pid_id"],  'cdate DESC limit 1');
					$id = $res[0]->id;

					$big = trim($big, '/'); $big = '/' . $big;
					$med = trim($med, '/'); $med = '/' . $med;

					$response = json_encode(array('big' => $id,'med' => $med, 'type' => 'audio', 'name' => $res[0]->original.'.'.$res[0]->type, 'url' => $res[0]->text));

					echo $response; exit;

				}

				if( $CFG->USER->checkExtFile($_FILES['file']) == 'image' )
				{

					if(is_numeric($_POST["pid_id"]))
						{
							$pid_id = $_POST["pid_id"];
						}

					$big = $CFG->USER->cropUserAvatar($_FILES['file'], 'default', 'record', $pid_id);
					$med = $CFG->USER->cropUserAvatar($_FILES['file'], 'defaultAvatar', 'record', $pid_id);

					$big = trim($big, '/'); $big = '/' . $big;
					$med = trim($med, '/'); $med = '/' . $med;

					$response = json_encode(array('big' => $big,'med' => $med, 'type' => 'image', 'url' => $big));
					echo $response; exit;
				}

				if( $CFG->USER->checkExtFile($_FILES['file']) == 'other' )
				{
					if(is_numeric($_POST["pid_id"]))
						{
							$pid_id = $_POST["pid_id"];

							$filename = 'documents/record/' . $pid_id.'/';

							if (!file_exists($filename))
							{
								$oldumask = umask(0);
								mkdir($filename, 0777);
								chmod($filename, 0777);
								umask($oldumask);
							}

							$tmp_name  = $_FILES['file']['tmp_name'];
							$name = $_FILES['file']['name'];
							setlocale(LC_ALL, 'ru_RU.utf8');
							$path = pathinfo($name);
							$onurl = $filename.md5(rand(1,1000).time().$name).'.'.$path['extension'];

							move_uploaded_file($tmp_name, $onurl);

							$cdate = sqlDateNow();
							$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, user_id, original, type, text, cdate) VALUES ('{$pid_id}', '{$CFG->USER->USER_ID}', '{$path[filename]}', '{$path[extension]}', '{$onurl}', '{$cdate}')";
							$CFG->DB->query($sql);
						}

					$res = SelectDataParent('attachments', 'page_id', $_POST["pid_id"], 'cdate DESC limit 1');
					$id = $res[0]->id;

					$big = trim($big, '/'); $big = '/' . $big;
					$med = trim($med, '/'); $med = '/' . $med;


					$response = json_encode(array('big' => $id,'med' => $med, 'type' => 'other', 'name' => $res[0]->original.'.'.$res[0]->type, 'url' => $res[0]->text));

					echo $response; exit;
				}
				else
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
					redirect($_SERVER["HTTP_REFERER"]);
				}
		}
		else
		{
			echo 'error';
			$CFG->STATUS->OK = true;
			$CFG->STATUS->MESSAGE = 'Произошла ошибка загрузки файла, либо размер файла привышает допустимый лимит в '.ini_get('post_max_size');
			redirect($_SERVER["HTTP_REFERER"]);
		}


		exit;

	break;

	case 'record' :

				$id = $CFG->_POST_PARAMS["record"];

				if($CFG->_POST_PARAMS["type"] == 'parent')
				{
					$id = $CFG->_POST_PARAMS["id"];
					$query  = "DELETE FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE id='{$id}'";
				}
				else
				{
					//$query  = "DELETE FROM {$CFG->DB_Prefix}news WHERE id='{$id}'";
					$query  = "UPDATE {$CFG->DB_Prefix}news SET visible=0 WHERE id='{$id}'";
				}

				//Опрелеояем чья запись и передаем значения id юзера что бы у него отнять бабок
				$data = getSQLRowO("SELECT manager_id FROM {$CFG->DB_Prefix}news WHERE id='{$id}' ");

				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}

		exit;
	break;

	case 'dell_face' :

			$id = $_POST["record"];

			$query  = "UPDATE {$CFG->DB_Prefix}face SET visible=0 WHERE id='{$id}'";
			if($CFG->DB->query($query))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}

		exit;
	break;





	/* Удалить сделку */
	case 'record_deal' :

		$id = $_POST['record'];
		$data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE page_id='{$id }' AND parent_id = 1000 AND visible=1 ");

		$counts = count($data);
		if($counts == 0)
		{
			//$query  = "DELETE FROM {$CFG->DB_Prefix}news WHERE id='{$id}'";
			$query  = "UPDATE {$CFG->DB_Prefix}news SET visible=0 WHERE id='{$id}'";
			if($CFG->DB->query($query))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		else
		{
			echo 0;
		}
		exit;
	break;
	/* Удалить сделку.end */





	case 'premium_minus' :

		if($CFG->USER->USER_ID)
		{
			$cdate = sqlDateNow();
			$text = htmlspecialchars(trim($_POST['text']));
			$manager = ($_POST["manager"]);
			$page_id = ($_POST["page_id"]);
			$user_id = $CFG->USER->USER_ID;

			if(is_numeric($text))
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}money_minus_list (user_id, manager_id, count, page_id, cdate, visible) VALUES ('{$user_id}', '{$manager}', '{$text}',  '{$page_id}', '{$cdate}', 0)";
				$CFG->DB->query($sql);

				$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_minus_list WHERE visible='0' and page_id='{$page_id}' ORDER BY cdate DESC limit 1 ");

				$name_manager = SelectData("users", $o->manager_id, 0);

				$CFG->DB->query("INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES ({$CFG->USER->USER_ID}, '{$manager}', {$page_id}, 0, '{$cdate}', 0 )");

				$CFG->DB->query("INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES ({$CFG->USER->USER_ID}, '{$CFG->USER->USER_DIRECTOR_ID}', {$page_id}, 0, '{$cdate}', 0 )");

				$CFG->DB->query("DELETE t1 FROM my_accessrecord t1 JOIN my_accessrecord t2  ON t1.autor_id = t2.autor_id 	WHERE t1.id > t2.id AND t1.page_id = '{$page_id}'  AND t1.visible = 0 AND t2.page_id = '{$page_id}' AND t2.visible = 0;");

				$op = getSQLArrayO("SELECT * FROM my_accessrecord WHERE page_id='{$page_id}' AND visible = 0  ");

				$ids = '';
				foreach ($op as $val)
				{
					$ids .= ','.$val->id;
				}

				$response = json_encode(array('id' => $o->id, 'manager_id' => $name_manager, 'count' => $o->count, 'accessrecord' => $ids));
				echo $response; exit;
			}
			else
			{
				echo 0; exit;
			}
		}
		exit;
	break;


	case 'premium_plus' :

		if($CFG->USER->USER_ID)
		{
		 //print_r($_POST); exit;

			$cdate = sqlDateNow();
			$text = htmlspecialchars(trim($_POST['text']));

			$premium_plus = $text;

			$manager = ($_POST["manager"]);
			$page_id = ($_POST["page_id"]);
			$user_id = $CFG->USER->USER_ID;


			if(is_numeric($text))
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}money_list (user_id, manager_id, count, page_id, cdate, type, visible) VALUES ('{$user_id}', '{$manager}', '{$premium_plus}',  '{$page_id}', '{$cdate}', 1, 0)";
				$CFG->DB->query($sql);

				$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_list WHERE visible='0' and page_id='{$page_id}' ORDER BY cdate DESC limit 1 ");

				$name_manager = SelectData("users", $o->manager_id, 0);

				$CFG->DB->query("INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES ({$CFG->USER->USER_ID}, '{$manager}', {$page_id}, 0, '{$cdate}', 0 )");

				$CFG->DB->query("INSERT INTO my_accessrecord (user_id, autor_id, page_id, parent_id, cdate, visible) VALUES ({$CFG->USER->USER_ID}, '{$CFG->USER->USER_DIRECTOR_ID}', {$page_id}, 0, '{$cdate}', 0 )");

				$CFG->DB->query("DELETE t1 FROM my_accessrecord t1 JOIN my_accessrecord t2  ON t1.autor_id = t2.autor_id 	WHERE t1.id > t2.id AND t1.page_id = '{$page_id}'  AND t1.visible = 0 AND t2.page_id = '{$page_id}' AND t2.visible = 0;");

				$op = getSQLArrayO("SELECT * FROM my_accessrecord WHERE page_id='{$page_id}' AND visible = 0  ");

				$ids = '';
				foreach ($op as $val)
				{
					$ids .= ','.$val->id;
				}

				$response = json_encode(array('id' => $o->id, 'manager_id' => $name_manager, 'count' => $o->count, 'accessrecord' => $ids));
				echo $response; exit;
			}
			else
			{
				echo 0; exit;
			}
		}
		exit;
	break;




// Удалить заметки, трансфер и т д
case 'checked' :

	switch ($_POST['type'])
	{
	    case 'delete':
				if(count($_POST['id']) > 0)
				{
					foreach($_POST['id'] as $id)
					{
							// Удаляем плюс
							$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_list WHERE coment_id='{$id}'");
							if($o->count > 0)
							{
								$query  = "DELETE FROM {$CFG->DB_Prefix}money_list WHERE coment_id='{$id}'";
								$CFG->DB->query($query);
							}

							// Удаляем минус
							$del = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_minus_list WHERE coment_id='{$id}'");
							if($del->id > 0)
							{
								$queryS  = "DELETE FROM {$CFG->DB_Prefix}money_minus_list WHERE id='{$del->id}'";
								$CFG->DB->query($queryS);
							}
							// Удаляем напоменания
							$del = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}accessrecord WHERE parent_id='{$id}'");
							if($del->id > 0)
							{
								$queryS  = "DELETE FROM {$CFG->DB_Prefix}accessrecord WHERE parent_id='{$id}'";
								$CFG->DB->query($queryS);
							}


							$query  = "UPDATE {$CFG->DB_Prefix}comments SET visible = 0 WHERE id='{$id}'";
							$CFG->DB->query($query);
					}
					echo 1;
				}
				else
					echo 0;
	    break;

			case 'transfer':
				$page_id = $_POST['page_id'];
				$o =  getSQLArrayO("SELECT  * FROM {$CFG->DB_Prefix}news WHERE parent_id = {$page_id} and visible = 1 order by  id DESC");
				if(count($o) == 0)
				{
					echo 0; exit;
				}
				else
				{
					for ($y=0; $y<sizeof($o); $y++)
					{
						?> <option value="<? echo $o[$y]->id; ?>"><? echo $o[$y]->name_company; ?></option> <?
					}
				}
			break;

			case 'transfer_2':
				foreach($_POST["id"] as $id)
				{
						$query  = "UPDATE {$CFG->DB_Prefix}comments SET page_id = {$_POST[deal]}, parent_id = 1000 WHERE id='{$id}'";
						$CFG->DB->query($query);
				}
				echo 1;
			break;

	}

	exit;
break;
// Удалить заметки, трансфер и т д


	case 'accessrecord' :

		if($CFG->USER->USER_ID)
		{
			$cdate = sqlDateNow();
			$manager = $_POST["manager"];
			$page_id = ($_POST["page_id"]);
			$user_id = $CFG->USER->USER_ID;

			$managerS = explode(",", $manager);

			if(count($managerS) > 0)
			{
				foreach ($managerS as  $value)
				{
					$manager = (int)$value;
					if($manager > 0)
					{
						$sql = "INSERT INTO {$CFG->DB_Prefix}accessrecord (user_id, autor_id, page_id, view, cdate, visible) VALUES ('{$user_id}', '{$manager}', '{$page_id}', 0, '{$cdate}', 0)";
						$CFG->DB->query($sql);
					}
				}

				$manager_id = getSQLRowO(" SELECT GROUP_CONCAT(id) as manager_id,  GROUP_CONCAT(autor_id) as autor_id FROM {$CFG->DB_Prefix}accessrecord WHERE user_id = {$user_id} AND page_id = '{$page_id}' AND parent_id = 0 AND visible = 0; ");

				$autor_id = getSQLRowO("SELECT GROUP_CONCAT(name) as name FROM {$CFG->DB_Prefix}users WHERE id IN ({$manager_id->autor_id}) ORDER BY id DESC ");

				$response = json_encode(array('id' => $manager_id->manager_id, 'manager' => $autor_id->name));
				echo $response; exit;
			}
		}
		else
		{
			echo 0; exit;
		}

		exit;

	break;


	case 'accessalert' :

		if($CFG->USER->USER_ID)
		{
			if($_POST["type"] == 3)
			{
				$id = $_POST["id"];
				$z = getSQLRowO("SELECT * FROM my_accessrecord WHERE id='{$id}'");

				$user_id = $CFG->USER->USER_ID;

				$с = getSQLArrayO("SELECT id FROM my_accessrecord WHERE autor_id='{$z->autor_id}' AND  user_id = '{$user_id}' AND view = 0   ");

				foreach ($с as $value)
				{
					$CFG->DB->query("UPDATE my_accessrecord SET view = 1 WHERE id='{$value->id}'	");
				}
				exit;
			}
			else
			{
				$cdate = sqlDateNow();
				$type = $_POST["type"];
				$id = $_POST["id"];
				$user_id = $CFG->USER->USER_ID;

				$query  = "UPDATE {$CFG->DB_Prefix}accessrecord SET view = '{$type}' WHERE id='{$id}'";
			  $CFG->DB->query($query);

				exit;

				//$response = json_encode(array('id' => $o->id, 'manager_id' => $name_manager, 'count' => $o->count));
				echo $response; exit;
			}

		}
		else
		{
			echo 0; exit;
		}

		exit;

	break;







	case 'replycommentalert' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST["id"];
			$query  = "UPDATE {$CFG->DB_Prefix}replycomment SET view = '1' WHERE id='{$id}'";
		 	$CFG->DB->query($query);
			echo 1;
		}
		else
		{
			echo 0; exit;
		}

		exit;

	break;









	case 'add_menu_parent' :

		if($CFG->USER->USER_ID)
		{
			$text = htmlspecialchars(trim($_POST['text']));
			$parent = ($_POST["parent"]);
			$parent_item = ($_POST["parent_item"]);
			$type_id = ($_POST["type_id"]);

			if(($parent == 0 && $parent_item == 0))
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}money_accounting_type_id (name, user_id, type_id) VALUES ('{$text}', '{$CFG->USER->USER_DIRECTOR_ID}', '{$type_id}')";
				$CFG->DB->query($sql);

				$response = json_encode(array('text' => "Новая категория добавлена."));
				echo $response; exit;
			}
			elseif(($parent > 0  && $parent_item == 0))
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}money_accounting_type_id (name, parent_id, user_id, type_id) VALUES ('{$text}', '{$parent}', '{$CFG->USER->USER_DIRECTOR_ID}', '{$type_id}')";
				$CFG->DB->query($sql);

				$response = json_encode(array('text' => "Новая подкатегория добавлена."));
				echo $response; exit;
			}
			elseif(($parent > 0  && $parent_item > 0))
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}money_accounting_type_id (name, parent_id, parent_item_id, user_id, type_id) VALUES ('{$text}', '{$parent}', '{$parent_item}', '{$CFG->USER->USER_DIRECTOR_ID}', '{$type_id}')";
				$CFG->DB->query($sql);

				$response = json_encode(array('text' => "Новая подкатегория добавлена."));
				echo $response; exit;
			}
			else
			{
				echo 0; exit;
			}

		}

		exit;
	break;



	case 'reminder' :

		if($CFG->USER->USER_ID)
		{
			$date = sqlDateNow();
			$text = htmlspecialchars(trim($_POST['text']));
			$manager = serialize($_POST["manager"]);

			$sql  = "INSERT INTO my_reminder (coment_id,user_id,text,cdate,date_start,time_start,manager_id,status) VALUES ('{$_POST[coment_id]}', '{$CFG->USER->USER_ID}', '{$text}', '{$date}', '{$_POST[date]}', '{$_POST[time]}', '{$manager}', '{$_POST[status]}')";
			$CFG->DB->query($sql);

			$res = SelectDataParent('reminder', 'coment_id', $_POST["coment_id"], 'cdate DESC limit 1');

			$response = json_encode(array('coment_id' => $res[0]->id, 'date_start' => $res[0]->date_start, 'time_start' => $times[$res[0]->time_start],'name' => strip_tags($res[0]->text)));
			echo $response; exit;

		}

		exit;
	break;


		// Дни рождения клиентов
	case 'birth' :
			$query = "SELECT  name_company, name_director_email, name_director_mobile, name_director_cdata, name_director, id, manager_id  FROM {$CFG->DB_Prefix}news WHERE visible = 1 AND (DATE_FORMAT( `name_director_cdata`  , '%m %d' ) >= '".date("m d")."') order by  DATE_FORMAT( `name_director_cdata`  , ' %m %d' ) ASC";
			$o = getSQLArrayO($query);

			for ($y=0; $y<sizeof($o); $y++)
			{
				if($o[$y]->name_director_cdata == '0000-00-00 00:00:00') continue;

				$to = dateSQL2TEXT($o[$y]->name_director_cdata, "DD-MM");
				$today = date('d').'-'.date('m');

				if($today == $to)
				{
					$users = SelectDataRowOArray('users', $o[$y]->manager_id, 0);

					$mail->text .= $o[$y]->name_director.'<br>';
					$mail->text .= $o[$y]->name_director_email.'<br>';
					$mail->text .= $o[$y]->name_director_mobile.'<br>';
					$mail->text .= 'Дата рождения: '.dateSQL2TEXT($o[$y]->name_director_cdata, "DD MN YYYY").'<br>';

					$mail->Subject = "Сегодня д.рождение у Вашего клиента!";
					$mail->Body  = '<strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$o[$y]->id.'/" target="_blank">'.$o[$y]->id.'- '.$o[$y]->name_company.'</a><br>'.$mail->text.'</strong>';

					mailer($users->email, $mail->Subject, $mail->Body);

				}

			}


			$query = "SELECT name_company, name_client_email, name_client_mobile, name_client_cdata, name_client, id, manager_id FROM {$CFG->DB_Prefix}news WHERE visible = 1  AND (DATE_FORMAT( `name_client_cdata`  , '%m %d' ) >= '".date("m d")."') order by  DATE_FORMAT( `name_client_cdata`  , ' %m %d' ) ASC";
			$o = getSQLArrayO($query);

			for ($y=0; $y<sizeof($o); $y++)
			{
				if($o[$y]->name_client_cdata == '0000-00-00 00:00:00') continue;

				$to = dateSQL2TEXT($o[$y]->name_client_cdata, "DD-MM");
				$today = date('d').'-'.date('m');

				if($today == $to)
				{
					$users = SelectDataRowOArray('users', $o[$y]->manager_id, 0);

					$mail->text .= $o[$y]->name_client.'<br>';
					$mail->text .= $o[$y]->name_client_email.'<br>';
					$mail->text .= $o[$y]->name_client_mobile.'<br>';
					$mail->text .= 'Дата рождения: '.dateSQL2TEXT($o[$y]->name_client_cdata, "DD MN YYYY").'<br>';

					$mail->Subject = "Сегодня д.рождение у Вашего клиента!";
					$mail->Body  = '<strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$o[$y]->id.'/" target="_blank">'.$o[$y]->id.'- '.$o[$y]->name_company.'</a><br>'.$mail->text.'</strong>';

					mailer($users->email, $mail->Subject, $mail->Body);

				}
			}



		exit;
	break;


	// Отключать пользователя после 10 дней НЕактивности
	case 'users_off' :
	 /*
		$o = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE visible = 1 AND boss = 0 ");

		for ($y=0; $y<sizeof($o); $y++)
		{
			$nextWeek = time() - (10 * 24 * 60 * 60);

			$now = dateSQL2TEXT($o[$y]->vdate, "YYYY-MM-DD");
			$past = date('Y-m-d', $nextWeek);

			if($now <= $past)
			{
				$query  = "UPDATE {$CFG->DB_Prefix}users SET visible= 0 WHERE id='{$o[$y]->id}'";
		    	$CFG->DB->query($query);
			}

		}

		exit;
		*/
	break;




	case 'selec' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST["user_id"];

			$res = $_POST["selec"];

			for ($r=0; $r<sizeof($res); $r++)
            {
				if($res[$r] > 0)
				{
					$id_company .= $res[$r].',';
				}
			}

			$and = trim($id_company, ",");

			print_r($_POST); exit;


			if($and =='') $and = $CFG->USER->USER_DIRECTOR_ID;

			$query  = "UPDATE {$CFG->DB_Prefix}users SET access = '{$and}' WHERE id='{$id}'";
			$CFG->DB->query($query);

		}

		exit;
	break;


	case 'load_search' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST["user_id"];
			$search = $_POST["search"];
			$num = $_POST["num"];

			$search_word = $search;

			if($id == 1012)
			{
				$search_where = "AND (name LIKE '%{$search_word}%' OR mobile LIKE '%{$search_word}%' OR email LIKE '%{$search_word}%' OR whatsapp LIKE '%{$search_word}%' OR name_other LIKE '%{$search_word}%' OR info LIKE '%{$search_word}%' )";
				$base = 'face';
			}
			else
			{
			 	$search_where = "AND (name_company LIKE '%{$search_word}%' OR name_director LIKE '%{$search_word}%' OR email LIKE '%{$search_word}%' OR other_email LIKE '%{$search_word}%' OR name_director_mobile LIKE '%{$search_word}%' OR name_director_email LIKE '%{$search_word}%' OR name_client LIKE '%{$search_word}%' OR name_client_mobile LIKE '%{$search_word}%' OR name_client_email LIKE '%{$search_word}%' OR info LIKE '%{$search_word}%' OR history LIKE '%{$search_word}%' OR contact LIKE '%{$search_word}%')";
				$base = 'news';
			}


			$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}{$base} WHERE visible='1' {$search_where} AND page_id = '{$id}' order by id limit {$num},10");
			for ($i=0; $i<sizeof($sql); $i++)
			{
				$pid[] .= $sql[$i]->id;
			}

			$com = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE text LIKE '%{$search}%' AND visible='1' AND parent_id = '{$id}'  order by id limit {$num},10");
			for ($i=0; $i<sizeof($com); $i++)
			{
				$pid[] .= $com   [$i]->page_id;
			}

			$res = array_unique($pid);

			if(count($res) == 0)
			{
				echo 0;
			}
			else
			{
				for ($i=0; $i<sizeof($res); $i++)
				{
					$data = SelectDataRowOArray($base, $res[$i]);
					if($data->id == !'')
					{
						?>                 <tr>
                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><span>*<?=$data->id;?></span></a></td>
                    <td><a href="<? echo getFullXCodeByPageId($data->page_id);?><?=$data->id;?>"><?=$data->name_company;?></a></td>
                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}city WHERE id='{$data->city_id}'");		echo $o->name;?></td>
                    <td><? echo dateSQL2TEXT($data->cdate, "DD.MM.YY").' '.dateSQL2TEXT($data->cdate, "hh:mm").'';?></td>
                    <td><? echo dateSQL2TEXT($data->edate, "DD.MM.YY").' '.dateSQL2TEXT($data->edate, "hh:mm").'';?></td>
                    <td><a href="/profile/view/<?=$data->manager_id?>"><? $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$data->manager_id}'");		echo $o->name;?></a></td>
                    <td><?=SelectDataCount('comments', 'page_id', $data->id);?></td>
                </tr>
 <?
					}
				}
			}
		}

		exit;
	break;



	case 'manager' :
		if($CFG->USER->USER_ID)
		{
			$id = $_POST["user_id"];
			$count = $_POST["num"];

			$num = $count+11;

			$y = getSQLArrayO("SELECT id, parent_id, page_id, cdate, text FROM my_comments WHERE user_id = '{$id}'  AND visible = 1 AND access =0 AND text !=''  order by cdate DESC LIMIT 0,{$num}");
			if( count($y) > 10)	{	$bottom = '<div class="col-md-12 more">	<div class="load_id users_'.$id.'" data-id="'.$id.'" data-num="'.$num.'">Загрузить еще</div></div>';	} else { 	$bottom = ''; }
			for ($r=0; $r<sizeof($y); $r++)
    	{
        $html .= '<div class="jobday" style="width:100%; overflow:hidden; white-space: nowrap">';
				$html .= '	<a href="'.getFullXCodeByCommentsId($y[$r]->parent_id).$y[$r]->page_id.'#comment-post_'.$y[$r]->id.'" class="namber">*'.$y[$r]->page_id.'</a>';
				$html .= '	<a href="'.getFullXCodeByCommentsId($y[$r]->parent_id).$y[$r]->page_id.'#comment-post_'.$y[$r]->id.'" class="cdate">'.dateSQL2TEXT($y[$r]->cdate, "Y.m.d hh:mm").'</a>';
				$html .= '	<a href="'.getFullXCodeByCommentsId($y[$r]->parent_id).$y[$r]->page_id.'#comment-post_'.$y[$r]->id.'" class="text">'.utf8_substr(strip_tags($y[$r]->text), 0, 55).'</a>';
				$html .= '</div>';
			}

			$html .= $bottom;
			echo $html;
		}
		exit;
	break;



	case 'load_activ' :

		if($CFG->USER->USER_ID && $_POST["user_id"] > 0 && $_POST["num"] > 0)
		{
			$id = $_POST["user_id"];
			$count = $_POST["num"];

			$y = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE user_id = '{$id}' AND visible = 1 AND access = '0'  order by cdate DESC LIMIT {$count},10");

			for ($r=0; $r<sizeof($y); $r++)
            	{
                	$w = $y[$r];

					$z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$w->page_id}'");

					?>
                        <tr>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><strong>*<?=$w->page_id;?></strong></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><? $name = SelectDataRowOArray('news', $w->page_id);  echo $name->name_company;?></a></td>
                            <td style="white-space:nowrap"><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><? echo dateSQL2TEXT($w->cdate, "DD.MM.YY, hh:mm")?></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><? echo utf8_substr(strip_tags($w->text), 0, 120);?></a></td>
                        </tr>
                    <?
				}
		}

		exit;
	break;

	case 'load_notifications' :

		if($CFG->USER->USER_ID && $_POST["user_id"] > 0 && $_POST["num"] > 0)
		{
			$id = $_POST["user_id"];
			$count = $_POST["num"];

			echo $id;

			$y = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}accessrecord WHERE autor_id = '{$id}' AND visible = 1 order by cdate DESC LIMIT {$count},10");

			for ($r=0; $r<sizeof($y); $r++)
			{
				$w = $y[$r];
				$z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$w->page_id}'"); ?>
        <tr>
            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/#<?=$w->parent_id;?>" target="_blank"><strong>*<?=$w->page_id;?></strong></a></td>
						<td style="white-space:nowrap"><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/#<?=$w->parent_id;?>" target="_blank"><? echo dateSQL2TEXT($w->cdate, "DD.MM.YY, hh:mm")?></a></td>
            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/#<?=$w->parent_id;?>" target="_blank"><? $name = SelectDataRowOArray('news', $w->page_id);  echo $name->name_company;?></a></td>
            <td>
							<?
							?>
							<a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/#<?=$w->parent_id;?>" target="_blank"><? $text = getSQLRowO("SELECT text FROM {$CFG->DB_Prefix}comments WHERE id='{$w->parent_id}'"); echo utf8_substr(strip_tags($text->text), 0, 120) ;?>...
						</a>
					</td>
        </tr>
				<?
				}
		}

		exit;
	break;




	case 'load_activ_important' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST["user_id"];
			$count = $_POST["num"];

			$y = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE user_id = '{$id}' AND visible = 1 AND access = '0' and important = 1  order by cdate DESC LIMIT {$count},10");

			for ($r=0; $r<sizeof($y); $r++)
            	{
                	$w = $y[$r];

					$z = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$w->page_id}'");

					?>
                        <tr>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><strong>*<?=$w->page_id;?></strong></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><? $name = SelectDataRowOArray('news', $w->page_id);  echo $name->name_company;?></a></td>
                            <td style="white-space:nowrap"><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><? echo dateSQL2TEXT($w->cdate, "DD.MM.YY, hh:mm")?></a></td>
                            <td><a href="<?=getFullXCodeByPageId($z->page_id);?><?=$w->page_id;?>/"><? echo utf8_substr(strip_tags($w->text), 0, 120);?></a></td>
                        </tr>
                    <?
				}
		}

		exit;
	break;


	case 'reminder_start':

			set_time_limit(600);
			$now = date('Y-m-d 00:00:00');
			$endday = date('Y-m-d 23:59:59');

			$res = getSQLArrayO("SELECT * FROM my_reminder WHERE visible='1'  AND (date_start >= '{$now}') AND (date_start <= '{$endday}')  ");

			if(count($res) >= 1)
			{
				for ($y=0; $y<sizeof($res); $y++)
				{
					if(date('G') == $res[$y]->time_start)
					{
						$manager_id = unserialize($res[$y]->manager_id);

						for ($i=0; $i<sizeof($manager_id); $i++)
						{
							if($manager_id[$i] == 0) continue;
							$users = SelectDataRowOArray('users', $manager_id[$i], 0);

							switch ($res[$y]->status)
							{
								case 2:	/* Отправка уведомления в системе дополнительному менеджеру */
									$cdate = sqlDateNow();
									$manager = $manager_id[0];
									$page_id = $res[$y]->coment_id;
									$user_id = $res[$y]->user_id;
									$managerS = explode(",", $manager);

									if(count($managerS) > 0)
									{
										foreach ($managerS as  $value)
										{
											$manager = (int)$value;
											if($manager > 0)
											{
												$real_comment = getSQLRowO(" SELECT * FROM `my_comments` WHERE `reminder` LIKE '%".$res[$y]->id."%' ORDER BY `id` DESC ");
												if($real_comment) //Если реальный комент существует пишем к нему уведомление
												{
													$real_comment_id = $real_comment->id;
													$sql = "INSERT INTO {$CFG->DB_Prefix}accessrecord (user_id, autor_id, page_id, parent_id, view, cdate, visible) VALUES ('{$user_id}', '{$manager}', '{$page_id}', '{$real_comment_id}', 0, '{$cdate}', 1)";
													$CFG->DB->query($sql);
												}
											}
										}
									}
								break;

								case 3: /* Отправка уведомления в системе + whatsapp дополнительному менеджеру */
									$cdate = sqlDateNow();
									$manager = $manager_id[0];
									$page_id = $res[$y]->coment_id;
									$user_id = $res[$y]->user_id;
									$managerS = explode(",", $manager);

									if(count($managerS) > 0)
									{
										foreach ($managerS as  $value)
										{
											$manager = (int)$value;
											if($manager > 0)
											{
												$real_comment = getSQLRowO(" SELECT * FROM `my_comments` WHERE `reminder` LIKE '%".$res[$y]->id."%' ORDER BY `id` DESC ");
												if($real_comment) //Если реальный комент существует пишем к нему уведомление
												{
													$real_comment_id = $real_comment->id;
													$sql = "INSERT INTO {$CFG->DB_Prefix}accessrecord (user_id, autor_id, page_id, parent_id, view, cdate, visible) VALUES ('{$user_id}', '{$manager}', '{$page_id}', '{$real_comment_id}', 0, '{$cdate}', 1)";
													$CFG->DB->query($sql);
												}
											}
										}
									}

									if($users->mobile ==! "")
									{
										$WP = new Whatsapp();
										$WP->get_send_crm(["manager" => $users->mobile, "text" => "*".$res[$y]->coment_id." ".$res[$y]->text." ", "Ondate" => $page_id, "from" => 13, "img" => "", "file_wp" => ""]);
									}
								break;

								case 4: /* Отправка уведомления в системе + whatsapp + email дополнительному менеджеру */
									$cdate = sqlDateNow();
									$manager = $manager_id[0];
									$page_id = $res[$y]->coment_id;
									$user_id = $res[$y]->user_id;
									$managerS = explode(",", $manager);

									if(count($managerS) > 0)
									{
										foreach ($managerS as  $value)
										{
											$manager = (int)$value;
											if($manager > 0)
											{
												$real_comment = getSQLRowO(" SELECT * FROM `my_comments` WHERE `reminder` LIKE '%".$res[$y]->id."%' ORDER BY `id` DESC ");
												if($real_comment) //Если реальный комент существует пишем к нему уведомление
												{
													$real_comment_id = $real_comment->id;
													$sql = "INSERT INTO {$CFG->DB_Prefix}accessrecord (user_id, autor_id, page_id, parent_id, view, cdate, visible) VALUES ('{$user_id}', '{$manager}', '{$page_id}', '{$real_comment_id}', 0, '{$cdate}', 1)";
													$CFG->DB->query($sql);
												}
											}
										}
									}

									$mail->Subject = "Важное уведомление";
									$mail->Body    = 'Уважаемый, <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/profile/view/'.$users->id.'/" target="_blank">'.$users->name.'</a>.</strong><br>Вам установили напоминания на <strong>«'.dateSQL2TEXT($res[$y]->date_start, "DD MN").' '.$times[$res[$y]->time_start].'»</strong><br>Описание: <strong>'.$res[$y]->text.'</strong><br>Запись: <a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$res[$y]->coment_id.'/" target="_blank">*'.$res[$y]->coment_id.'</a>.';
									mailer($users->email, $mail->Subject, $mail->Body);

									if($users->mobile ==! "")
									{
										$WP = new Whatsapp();
										$WP->get_send_crm(["manager" => $users->mobile, "text" => "*".$res[$y]->coment_id." ".$res[$y]->text." ", "Ondate" => $page_id, "from" => 13, "img" => "", "file_wp" => ""]);
									}
								break;
							}
							exit;
						}
					}
				}

			}

		exit;
	break;


	case 'reminderhtml' :

		if($CFG->USER->USER_ID)
		{

				$id = $_POST['id'];

				$data = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}reminderhtml WHERE id='{$id}'");

				if($data->id > 0)
				{
					$query  = "UPDATE {$CFG->DB_Prefix}reminderhtml SET status=1 WHERE id='{$id}'";
					if($CFG->DB->query($query))
						echo 1;
					else
						echo 0;
				}
		}
		exit;
	break;





	/* Задача выполнена отправляется на модерацию */
	case 'upstatus' :

		if($CFG->USER->USER_ID)
		{
			$id = htmlspecialchars(trim($_POST["record"]));
			$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));

			if(is_numeric($id))
			{
				$data = SelectDataRowOArray('comments', $id);
				if(count($data) > 0)
				{

					$query  = "UPDATE {$CFG->DB_Prefix}comments SET status_taks=2 WHERE id='{$id}'";
					$CFG->DB->query($query);

					/* автор задачи */
					$user = SelectDataRowOArray('users', $data->user_id, 0);

					/* исполнитель задачи */
					$doer = SelectDataRowOArray('users', $data->doer, 0);


					$subject = ''.$doer->name.', Выполнил(а) задачу!';
					$body    = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile/view/'.$doer->id.'/#view-notes" target="_blank"><strong>'.$doer->name.'</strong></a>, выполнил поставленную задачу к записи <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$data->page_id.'/" target="_blank"> *'.$data->page_id.'</a> </strong>';
					mailer($user->email, $subject, $body );

				}
			}
			else
			{
				$response = $status;
				echo $response; exit;
			}

		}
		exit;
	break;



	/* Ваша задача выполнена. Одобряем */
	case 'upmodernstatustaks' :

		if($CFG->USER->USER_ID)
		{
			$id = htmlspecialchars(trim($_POST["record"]));
			$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));

			if(is_numeric($id))
			{
				$data = SelectDataRowOArray('comments', $id);
				if(count($data) > 0)
				{

					$query  = "UPDATE {$CFG->DB_Prefix}comments SET status_taks = 3 WHERE id='{$id}'";
					$CFG->DB->query($query);

					/* автор задачи */
					$user = SelectDataRowOArray('users', $data->user_id, 0);

					/* исполнитель задачи */
					$doer = SelectDataRowOArray('users', $data->doer, 0);

					/* Название записи */
					$record = SelectDataRowOArray('news', $data->page_id);

					$subject = ''.$user->name.', подтвердил выполнение задачи!';
					$body    = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile/view/'.$user->id.'/#view-notes" target="_blank"><strong>'.$user->name.'</strong></a>, подтвердил что Вы выполнил(и)(а) задачу <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$data->page_id.'/" target="_blank"> *'.$record->name_company.'</a> </strong>';
					mailer($doer->email, $subject, $body );

				}
			}
			else
			{
				$response = $status;
				echo $response; exit;
			}

		}
		exit;
	break;

	/* Задача выполнена. Получаем список задач */
	case 'upmodernstatustaks_send' :
		if($CFG->USER->USER_ID)
		{
			$id = htmlspecialchars(trim($_POST["user_id"]));
			$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));

			if(is_numeric($id))
			{

				$response = getSQLArrayO("SELECT *  FROM {$CFG->DB_Prefix}comments WHERE visible=1 AND user_id = {$id}  AND status_taks = 2 AND task = 1 ORDER BY offtask  DESC");

				if(count($response) > 0)
				{
					?>
					 <table class="users tasks">
						<tr>
							<th><strong>Дедлайн</strong></th>
							<th><strong>Задача</strong></th>
							<th><strong>Запись</strong></th>
							<th><strong>Исполнитель</strong></th>
							<th><strong>Статус</strong></th>
						</tr>
						   <?
							for ($i=0; $i<sizeof($response); $i++)
								{
									$o = $response[$i];

									if(sqlDateNow() > $o->offtask) { $offtask = ' class="offtask"';} else {$offtask = '';}
							?>
							<tr<?=$offtask?>>
								<td class="offtask"><?=dateSQL2TEXT($o->offtask, "DD.MM.YY")?></td>
								<td class="text"><a href="/record/<?=$o->page_id;?>#comment-post_<?=$o->id;?>"><?=utf8_substr(strip_tags($o->text), 0, 100);?></a></td>
								<td class="offtask"><a href="/record/<?=$o->page_id;?>"><? $res = SelectDataRowOArray('news', $o->page_id);?> <?=$res->name_company;?></a></td>
								<td class="autor"><? $resp = SelectDataRowOArray('users', $o->doer, 0);?> <a href="/profile/view/<?=$resp->id;?>/#view-notes"><?=$resp->name;?></a></td>
								<td>
									  <div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
										   <span class="moder">На модерации</span>
										   <? if($o->user_id == $CFG->USER->USER_ID) {?><span class="caret"></span><? }?>
										</button>

										<? if($o->user_id == $CFG->USER->USER_ID) {?>
										<ul class="dropdown-menu">
										  <li><a href="#" data-id="<?=$o->id;?>" class="upmodernstatustaks">Выполнено</a></li>
										</ul>
										<? }?>
									</div>
								</td>
							</tr>
							<? } ?>
					</table>
					<?
				}

			}
			else
			{
				$response = $status;
				echo $response; exit;
			}

		}
		exit;
	break;



	/* Задача НЕ выполнена */
	case 'notdone' :

		if($CFG->USER->USER_ID)
		{
			$id = htmlspecialchars(trim($_POST["record"]));
			$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));

			if(is_numeric($id))
			{
				$data = SelectDataRowOArray('comments', $id);
				if(count($data) > 0)
				{

					$query  = "UPDATE {$CFG->DB_Prefix}comments SET status_taks = 1 WHERE id='{$id}'";
					$CFG->DB->query($query);

					/* автор задачи */
					$user = SelectDataRowOArray('users', $data->user_id, 0);

					/* исполнитель задачи */
					$doer = SelectDataRowOArray('users', $data->doer, 0);

					/* Название записи */
					$record = SelectDataRowOArray('news', $data->page_id);

					$subject = ''.$user->name.', не подтвердил выполнение задачи!';
					$body    = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile/view/'.$user->id.'/#view-notes" target="_blank"><strong>'.$user->name.'</strong></a>, неподтвердил что Вы выполнил(и)(а) задачу <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$data->page_id.'/" target="_blank"> *'.$record->name_company.'</a> </strong>';
					mailer($doer->email, $subject, $body );

				}
			}
			else
			{
				$response = $status;
				echo $response; exit;
			}

		}
		exit;
	break;
	/* Задача НЕ выполнена. END*/





	/* Задача выполнена */
	case 'upstatusresponse' :

		if($CFG->USER->USER_ID)
		{
			$id = htmlspecialchars(trim($_POST["record"]));
			$text = htmlspecialchars(trim($_POST["text"]));
			$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));

			if(is_numeric($id))
			{
				$data = getSQLRowO("SELECT * FROM my_comments WHERE id='{$id}'  AND visible = 1");
				if(count($data) > 0)
				{
					$query  = "UPDATE my_comments SET response = '{$text}', status_taks = 3 WHERE id='{$id}'";
					$CFG->DB->query($query);

					$response = json_encode(array('status' => 1, 'text' => "Вы выполнили свою задачу!"));
					echo $response; exit;
				}
			}
			else
			{
				$response = $status;
				echo $response; exit;
			}

		}
		exit;
	break;





	case 'detelecoment' :

		if($CFG->USER->USER_ID)
		{
			$date = sqlDateNow();
			$times = time();
			$id = htmlspecialchars(trim($_POST["record"]));
			$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));

			if(is_numeric($id))
			{
				$data = getSQLRowO("SELECT *  FROM {$CFG->DB_Prefix}comments WHERE id='{$id}' ");

				if(count($data) > 0)
				{
					$datetime1 = date_create($date);
					$datetime2 = date_create($data->cdate);
					$interval = date_diff($datetime1, $datetime2);

					if(($interval->days = 1) or ($CFG->USER->USER_ID == 85) or ($CFG->USER->USER_ID == 1))
					{
						// Удаляем плюс
						$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_list WHERE coment_id='{$data->id}'");
						if($o->count > 0)
						{
							$query  = "DELETE FROM {$CFG->DB_Prefix}money_list WHERE coment_id='{$data->id}'";
							$CFG->DB->query($query);
						}

						// Удаляем минус
						$del = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}money_minus_list WHERE coment_id='{$data->id}'");
						if($del->id > 0)
						{
							$queryS  = "DELETE FROM {$CFG->DB_Prefix}money_minus_list WHERE id='{$del->id}'";
							$CFG->DB->query($queryS);
						}
						// Удаляем напоменания
						$del = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}accessrecord WHERE parent_id='{$data->id}'");
						if($del->id > 0)
						{
							$queryS  = "DELETE FROM {$CFG->DB_Prefix}accessrecord WHERE parent_id='{$data->id}'";
							$CFG->DB->query($queryS);
						}

						//$query  = "DELETE FROM {$CFG->DB_Prefix}comments WHERE id='{$data->id}'";
						//$CFG->DB->query($query);
						$query  = "UPDATE {$CFG->DB_Prefix}comments SET visible=0, del_user= '{$CFG->USER->USER_ID}', del_time= '{$times}' WHERE id='{$data->id}'";
						$CFG->DB->query($query);

						$response = json_encode(array('status' => 1, 'text' => "Ваша заметка удалена!"));
						echo $response; exit;
					}
					else
					{
						$response = $status;
						echo $response; exit;
					}
				}
				else
				{
					$response = $status;
					echo $response; exit;
				}

			}
			else
			{
				$response = $status;
				echo $response; exit;
			}

		}
		exit;
	break;



	/* Удаляем штраф админом */
	case 'delpenalty' :

		if($CFG->USER->USER_ID)
		{
			$date = sqlDateNow();
			$id = htmlspecialchars(trim($_POST["record"]));
			$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));

			if(is_numeric($id))
			{
				$data = getSQLRowO("SELECT *  FROM {$CFG->DB_Prefix}money_minus_list WHERE id='{$id}' ");

				if(count($data) > 0)
				{
					/* Удаляем заметку */
					$queryS  = "DELETE FROM {$CFG->DB_Prefix}comments WHERE id='{$data->coment_id}'";
					$CFG->DB->query($queryS);

					/* Удаляем запись в журнале учета денег */
					$res  = "DELETE FROM {$CFG->DB_Prefix}money_minus_list WHERE id='{$data->id}'";
					$CFG->DB->query($res);

					$response = json_encode(array('status' => 1, 'text' => "Ваша заметка удалена!"));
					echo $response; exit;
				}
				else
				{
					$response = $status;
					echo $response; exit;
				}

			}
			else
			{
				$response = $status;
				echo $response; exit;
			}

		}
		exit;
	break;






	/* Защита проекта. */
	case 'protection' :

		$name = "защитаабсен";
		$search_where = "AND (name_company LIKE '%{$name}%') ";

		$s = $NEWS->getList(868, '', '', '', '', $search_where);

		if(count($s) > 0)
		{
			for ($i=0; $i<sizeof($s); $i++)
			{

				$o = $s[$i];

				$datetime1 = date_create(date("Y-m-d"));
				$datetime2 = date_create(dateSQL2TEXT($o->edate, "YYYY-MM-DD"));
				$interval = date_diff($datetime1, $datetime2);

				switch ($interval->days)
				{
					case 30:
						$users = SelectDataRowOArray('users',$o->manager_id,0);
						$subject = 'Защита проекта. *'.$o->id;
						$text = 'У Вас осталось 30 дней до конца защиты проекта «<strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$o->id.'/" target="_blank">'.utf8_substr($o->name_company, 12).'</a></strong>».';

						mailer($users->email, $subject, $text);
					break;

					case 59:
						$users = SelectDataRowOArray('users',$o->manager_id,0);
						$subject = 'Защита проекта. *'.$o->id;
						$text = 'У Вас осталось 1 день до конца защиты проекта «<strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$o->id.'/" target="_blank">'.utf8_substr($o->name_company, 12).'</a></strong>».';

						mailer($users->email, $subject, $text);
					break;

					case 60:

						$users = SelectDataRowOArray('users',$o->manager_id,0);
						$subject = 'Защита проекта. *'.$o->id;
						$text = 'Уважаемый <strong>'.$users->name.'</strong>!<br>Статус защиты снят с проекта  «<strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$o->id.'/" target="_blank">'.utf8_substr($o->name_company,12).'</a></strong>»!';

						mailer($users->email, $subject, $text);

						$itg = utf8_substr($o->name_company, 12);
						$query  = "UPDATE {$CFG->DB_Prefix}news SET name_company='{$itg}' WHERE id='{$o->id}'";
						$CFG->DB->query($query);

					break;
				}
			}
		}
		exit;
	break;


	case 'avatar' :

				if( $CFG->USER->checkExtFile($_FILES['avatar']) == 'image' )
				{
					$big = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'default');
					$med = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'defaultAvatar');

					$big = trim($big, '/'); $big = '/' . $big;
					$med = trim($med, '/'); $med = '/' . $med;

					$response = json_encode(array('big' => $big,'med' => $med,));

					echo $response; exit;
				}
				else
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
					redirect($_SERVER["HTTP_REFERER"]);
				}
		exit;
	break;


	case 'user_avatar' :
				if( $CFG->USER->checkExtFile($_FILES['avatar']) == 'image' )
				{
					$big = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'default');
					$med = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'defaultAvatar');

					$big = trim($big, '/'); $big = '/' . $big;
					$med = trim($med, '/'); $med = '/' . $med;

					$response = json_encode(array('big' => $big,'med' => $med,));


					$query  = "UPDATE {$CFG->DB_Prefix}users SET avatar = '{$med}', photo = '{$big}' WHERE id='{$CFG->USER->USER_ID}'";

					if($CFG->DB->query($query))
					{
						echo $response; exit;
					}
					else
					{
						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
						redirect($_SERVER["HTTP_REFERER"]);
					}


				}
				else
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
					redirect($_SERVER["HTTP_REFERER"]);
				}
		exit;
	break;




	case 'vse_avatar' :
				if( $CFG->USER->checkExtFile($_FILES['avatar']) == 'image' )
				{
					$big = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'default');
					$med = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'defaultAvatar');

					$big = trim($big, '/'); $big = '/' . $big;
					$med = trim($med, '/'); $med = '/' . $med;

					$response = json_encode(array('big' => $big,'med' => $med,));

					echo $response; exit;


				}
				else
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
					redirect($_SERVER["HTTP_REFERER"]);
				}
		exit;
	break;








	case 'up_sms' :

	set_time_limit (6000);

				if( $CFG->USER->checkExtFile($_FILES['txt']) == 'other' )
				{
					$tmp_name = $_FILES['txt']['tmp_name'];

					$path = 'documents/txt/' . $file['name'] . sqlDateNow() . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

					move_uploaded_file($tmp_name, "/documents/txt/");

						$filename = 'documents/txt/' .$CFG->USER->USER_DIRECTOR_ID.'/';

							if (!file_exists($filename))
							{
								$oldumask = umask(0);
								mkdir($filename, 0777);
								chmod($filename, 0777);
								umask($oldumask);
							}

							$tmp_name  = $_FILES['txt']['tmp_name'];
							$name = $_FILES['txt']['name'];
							setlocale(LC_ALL, 'ru_RU.utf8');
							$path = pathinfo($name);
							$onurl = $filename.md5(rand(1,1000).time().$name).'.'.$path['extension'];
							move_uploaded_file($tmp_name, $onurl);

							$file = file_get_contents($onurl, true);
							$sms = explode(",", $file);
							$count_sms =  count($sms);

							$cdata = sqlDateNow();

							$query = "INSERT INTO {$CFG->DB_Prefix}tmp_file_tel (user_id, count_email, file_url, file_name, cdate, visible) VALUES ('{$CFG->USER->USER_ID}', '{$count_sms}', '{$onurl}', '{$name}', '{$cdata}', 0)";

							if($CFG->DB->query($query))
							{
								$s = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_file_tel WHERE visible='0' and user_id='{$CFG->USER->USER_ID}' order by id DESC");

								for($r=0; $r<sizeof($sms); $r++)
								{
									$emails = $sms[$r];
									$query = "INSERT INTO {$CFG->DB_Prefix}tmp_tel (pid_id, user_id, email, visible) VALUES ('{$s->id}', '{$CFG->USER->USER_ID}', '{$emails}', 0)";
									$CFG->DB->query($query);

								}

								echo $s->id; exit;
							}
							else
							{
								$CFG->STATUS->OK = true;
								$CFG->STATUS->MESSAGE = 'Произошла ошибка. Попробуйте еще раз.';
								redirect($_SERVER["HTTP_REFERER"]);
							}

					 exit;

				}
				else
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
					redirect($_SERVER["HTTP_REFERER"]);
				}
		exit;
	break;

	case 'up_sms_type' :

		$id = $_POST["id"];
		$text = $_POST["text"];

		$query  = "UPDATE {$CFG->DB_Prefix}tmp_file_tel SET text = '{$text}',  visible = 1  WHERE id='{$id}'";

		if($CFG->DB->query($query))
			echo 1;
		else
			echo 0;

	 	exit;

	break;


	case 'up_sms_record' :

	//send_sms("gnezdilov.gena@mail.ru", "Hello, this is <strong>my photo</strong>", 0, 0, 0, 8, "no-replay@ledprokat.kz", "subj=Гигант");


	//exit;

		$date = sqlDateNow();

		// date('Y-m-d 09-00-00')

			ini_set('max_execution_time', '6000');

			$s = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_tel WHERE visible='0' order by id ASC");

			$pid = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_file_tel WHERE id='{$s->pid_id}' and visible = 1");



			$input = $s->email;
			$toDelete = 1;
			mb_internal_encoding("UTF-8");
			$email = mb_substr( $input, $toDelete);

				if(count($pid) == 1)
				{
					if(count($res) == 0)
					{
						$res = getSQLRowO("SELECT * FROM my_news WHERE visible='1' AND (name_company LIKE '%{$email}%' OR tel LIKE '%{$email}%' OR  tel_2 LIKE '%{$email}%' OR tel_3 LIKE '%{$email}%' OR name_client_mobile LIKE '%{$email}%' OR name_director_mobile LIKE '%{$email}%') ORDER BY cdate ASC");

						if(send_sms($s->email, $pid->text))
						{
							$text = 'Отправелена СМС рассылка с текстом: *'.$pid->text.'*';
							$query = "INSERT INTO {$CFG->DB_Prefix}comments (cdate, page_id, parent_id, user_id, text, visible) VALUES ( '{$date}', '{$res->id}', '{$res->page_id}', '{$s->user_id}', '{$text}',  1)";
							$CFG->DB->query($query);
						}


						// После того как создали запись, добавлем +1 к счетчику
						$count = $pid->count_email_ok + 1;
						$que  = "UPDATE {$CFG->DB_Prefix}tmp_file_tel SET count_email_ok = '{$count}'  WHERE id='{$s->pid_id}'";
						$CFG->DB->query($que);

						//После всего удаляем временую запись м пепеходим к следующей
						$CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_tel WHERE id='{$s->id}'");
					}
					else
					{
						$CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_tel WHERE id='{$s->id}'");
					}

					$respon = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_tel WHERE pid_id='{$pid->id}' order by id ASC");

					if(count($respon) == 1)
					{
						$que  = "UPDATE {$CFG->DB_Prefix}tmp_file_tel SET status = 1  WHERE id='{$s->pid_id}'";
						$CFG->DB->query($que);

						$z = getSQLRowO("SELECT email FROM {$CFG->DB_Prefix}users WHERE id='{$pid->user_id}' ");

						$subject = 'SMS рассылка завершена.';
						$body    = 'SMS рассылка завершена в CRM системе!';
						mailer($z->email, $subject, $body );

						exit;
					}

				}
				else
				{
					$CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_tel WHERE id='{$s->id}'");
				}


	 	exit;

	break;










	case 'up_txt' :

	set_time_limit (6000);

				if( $CFG->USER->checkExtFile($_FILES['txt']) == 'other' )
				{
					$tmp_name = $_FILES['txt']['tmp_name'];

					$path = 'documents/txt/' . $file['name'] . sqlDateNow() . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

					move_uploaded_file($tmp_name, "/documents/txt/");

						$filename = 'documents/txt/' .$CFG->USER->USER_DIRECTOR_ID.'/';

							if (!file_exists($filename))
							{
								$oldumask = umask(0);
								mkdir($filename, 0777);
								chmod($filename, 0777);
								umask($oldumask);
							}

							$tmp_name  = $_FILES['txt']['tmp_name'];
							$name = $_FILES['txt']['name'];
							setlocale(LC_ALL, 'ru_RU.utf8');
							$path = pathinfo($name);
							$onurl = $filename.md5(rand(1,1000).time().$name).'.'.$path['extension'];
							move_uploaded_file($tmp_name, $onurl);

							$file = file_get_contents($onurl, true);
							$email = explode(",", $file);
							$count_email =  count($email);

							$cdata = sqlDateNow();

							$query = "INSERT INTO {$CFG->DB_Prefix}tmp_file (user_id, count_email, file_url, file_name, cdate, visible) VALUES ('{$CFG->USER->USER_ID}', '{$count_email}', '{$onurl}', '{$name}', '{$cdata}', 0)";

							if($CFG->DB->query($query))
							{
								$s = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_file WHERE visible='0' and user_id='{$CFG->USER->USER_ID}' order by id DESC");

								for($r=0; $r<sizeof($email); $r++)
								{
									$emails = $email[$r];
									$query = "INSERT INTO {$CFG->DB_Prefix}tmp_email (pid_id, user_id, email, visible) VALUES ('{$s->id}', '{$CFG->USER->USER_ID}', '{$emails}', 0)";
									$CFG->DB->query($query);

								}

								echo $s->id; exit;
							}
							else
							{
								$CFG->STATUS->OK = true;
								$CFG->STATUS->MESSAGE = 'Произошла ошибка. Попробуйте еще раз.';
								redirect($_SERVER["HTTP_REFERER"]);
							}

					 exit;

				}
				else
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
					redirect($_SERVER["HTTP_REFERER"]);
				}
		exit;
	break;



	case 'up_txt_type' :

		$id = $_POST["id"];
		$type = serialize($_POST["type"]);
		$city = $_POST["city"];

		$query  = "UPDATE {$CFG->DB_Prefix}tmp_file SET city = '{$city}', type = '{$type}',  visible = 1  WHERE id='{$id}'";

		if($CFG->DB->query($query))
			echo 1;
		else
			echo 0;

	 	exit;

	break;



	case 'up_txt_record' :

		ini_set('max_execution_time', '6000');

		$s = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_email WHERE visible='0' order by id ASC");

		$pid = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_file WHERE id='{$s->pid_id}' and visible = 1");

		$date = sqlDateNow();

		$email = trim($s->email);

		$type = unserialize($pid->type);

		for ($y=0; $y<sizeof($type); $y++)
		{
			$id .= $type[$y].',';
		}

		$and_id = trim($id, ",");

			if(count($pid) == 1)
			{
				$res = getSQLArrayO("SELECT * FROM my_news WHERE sys_language='1' AND page_id=868 AND visible='1' AND (name_company LIKE '%{$email}%' OR email LIKE '%{$email}%' OR other_email LIKE '%{$email}%' OR name_director_email LIKE '%{$email}%' OR name_client_email LIKE '%{$email}%') ORDER BY cdate DESC");

				if(count($res) == 0)
				{
					// Создаем запись
				//	$query = "INSERT INTO {$CFG->DB_Prefix}news (cdate, edate, page_id, manager_id, email, name_company, type_company_id, city_id, visible) VALUES ( '{$date}', '{$date}', '868', '{$s->user_id}', '{$email}', 'Автозаписулька {$email}', '{$and_id}', '{$pid->city}' , 1)";
					$CFG->DB->query($query);

					// После того как создали запись, добавлем +1 к счетчику
					$count = $pid->count_email_ok + 1;
					$que  = "UPDATE {$CFG->DB_Prefix}tmp_file SET count_email_ok = '{$count}'  WHERE id='{$s->pid_id}'";
					$CFG->DB->query($que);

					//После всего удаляем временую запись м пепеходим к следующей
					$CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_email WHERE id='{$s->id}'");
				}
				else
				{
					$CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_email WHERE id='{$s->id}'");
				}

				$respon = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_email WHERE pid_id='{$pid->id}' order by id ASC");
				if(count($respon) == 1)
				{
					$que  = "UPDATE {$CFG->DB_Prefix}tmp_file SET status = 1  WHERE id='{$s->pid_id}'";
					$CFG->DB->query($que);

					$z = getSQLRowO("SELECT email FROM {$CFG->DB_Prefix}users WHERE id='{$pid->user_id}' ");

					$subject = 'Автозаполнение E-mail завершено.';
					$body    = 'Автозаполнение E-mail в CRM системе завершено!';
					mailer($z->email, $subject, $body );

					exit;
				}

			}
			else
			{
				$CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}tmp_email WHERE id='{$s->id}'");
			}



	 	exit;

	break;


	//Повторить мнговеное извещение - Посмотреть позже
	case 'my_accessrecord' :
		$res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}accessrecord WHERE view='2'  AND visible='1' ORDER BY cdate DESC");
		for($r=0; $r<sizeof($res); $r++)
		{
			$que  = "UPDATE {$CFG->DB_Prefix}accessrecord SET view = 0  WHERE id='{$res[$r]->id}'";
			$CFG->DB->query($que);
		}
		exit;
	break;


	case 'my_replycomment' :

		$res = getSQLArrayO("SELECT * FROM my_replycomment WHERE view='2'  AND visible='1' ORDER BY cdate DESC");
		for($r=0; $r<sizeof($res); $r++)
		{
			$que  = "UPDATE my_replycomment SET view = 0  WHERE id='{$res[$r]->id}'";
			$CFG->DB->query($que);
		}

		exit;
	break;



	case 'user_status' :

		if($CFG->USER->USER_ID)
		{
			$user_id = $_POST["user_id"];
			$user_status = $_POST["user_status"];

			$query  = "UPDATE {$CFG->DB_Prefix}users SET status = '{$user_status}' WHERE id='{$user_id}'";
		    if($CFG->DB->query($query))
			{
				echo 0; exit;
			}
			else
				echo 1; exit;
		}

	break;


	case 'user_op' :

		if($CFG->USER->USER_ID)
		{
			$user_id = $_POST["user_id"];

			$pid = getSQLRowO("SELECT visible FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}'");

			if($pid->visible == 1)
			{
				$query  = "UPDATE {$CFG->DB_Prefix}users SET visible = 0 WHERE id='{$user_id}'";
		    	$CFG->DB->query($query);
			}

			if($pid->visible == 0)
			{
				$query  = "UPDATE {$CFG->DB_Prefix}users SET visible = 1 WHERE id='{$user_id}'";
		    	$CFG->DB->query($query);
			}

		}
		else
		{
			echo 0; exit;
		}

		exit;

	break;




	case 'user_dismissed' :

		if($CFG->USER->USER_ID)
		{

			$user_id = $_POST["user_id"];
			$cdate = sqlDateNow();



			$CFG->DB->query(" UPDATE my_users SET enddate = '{$cdate}'  WHERE id = {$user_id}  	");


			$pid = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}'");
			$face_id = getSQLRowO("SELECT * FROM my_face WHERE mobile LIKE '%{$pid->mobile}%' AND manager_id = '{$user_id}'	 AND  visible = 1");

			if($face_id->id > 0 )
			{
				$CFG->DB->query(" UPDATE my_face SET info = REPLACE (`info`, '{$CFG->USER->USER_FRAZA}', ' ')  WHERE id = {$face_id->id}  	");
			}

			$CFG->DB->query(" UPDATE my_face SET manager_id = '{$pid->user_id}' WHERE manager_id = {$user_id}  	");


			if($pid->taks_id > 0)
			{
				$query  = "UPDATE {$CFG->DB_Prefix}news SET page_id = 976, type_company_id = 10011934, name_company = '{$pid->name}', cdate = '{$cdate}', edate='{$cdate}' WHERE id='{$pid->taks_id}'";
				$CFG->DB->query($query);
			}

			$query  = "UPDATE {$CFG->DB_Prefix}users SET visible = 0, dismissed = 1 WHERE id='{$user_id}'";
			$CFG->DB->query($query);

			$manager_id_pid = getSQLRowO(" SELECT GROUP_CONCAT(id) FROM {$CFG->DB_Prefix}news WHERE manager_id = {$user_id}  AND page_id = 868");

			$mip = $manager_id_pid->{'GROUP_CONCAT(id)'};

			if($mip != '')
			{
				$query  = "UPDATE {$CFG->DB_Prefix}news SET manager_id = {$CFG->USER->USER_DIRECTOR_ID}, cdate = '{$cdate}', edate='{$cdate}' WHERE id IN  ({$mip})";
				$CFG->DB->query($query);
			}
			exit;
		}
		else
		{
			echo 0; exit;
		}

		exit;

	break;


 	// Добавить изменить категории портрета клиента
	case 'add_type_company' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST['id']; $del = $_POST['del']; $text = $_POST['text']; $pos = $_POST['pos'];

			if($id > 0 && $del == '')
			{
				$query  = "UPDATE {$CFG->DB_Prefix}type_company_portrait SET name = '{$text}', pos = '{$pos}' WHERE id='{$id}'";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
			elseif($id == 0 && $del == '')
			{
				$query = "INSERT INTO {$CFG->DB_Prefix}type_company_portrait (name, page_id, pos, visible) VALUES ('{$text}', 868, '{$pos}', 1)";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
			elseif($id > 0 && $del == 'del')
			{
				$query  = "DELETE FROM {$CFG->DB_Prefix}type_company_portrait WHERE id='{$id}'";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
		}
	break;



 	// Добавить изменить faq
	case 'add_faq' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST['id']; $del = $_POST['del']; $text = $_POST['text'];

			if($id > 0 && $del == '')
			{
				$query  = "UPDATE {$CFG->DB_Prefix}faq SET name = '{$text}', director_id = '{$CFG->USER->USER_DIRECTOR_ID}', text = '{$_POST[intro]}', img = '{$_POST[img]}', page_id = '{$_POST[page_id]}' WHERE id='{$id}'";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
			elseif($id == 0 && $del == '')
			{
				$query = "REPLACE  INTO {$CFG->DB_Prefix}faq (director_id, page_id, name, text, img, visible) VALUES ('{$CFG->USER->USER_DIRECTOR_ID}', '{$_POST[page_id]}', '{$_POST[text]}', '{$_POST[intro]}', '{$_POST[img]}', 1)";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
			elseif($id > 0 && $del == 'del')
			{
				$query  = "DELETE FROM {$CFG->DB_Prefix}faq WHERE id='{$id}'";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
		}
	break;




 	// Добавить изменить категории портрета клиента
	case 'add_type_company_ot' :

		if($CFG->USER->USER_ID)
		{
			$id = $_POST['id']; $del = $_POST['del']; $text = $_POST['text']; $pos = $_POST['pos'];

			if($id > 0 && $del == '')
			{
				$query  = "UPDATE {$CFG->DB_Prefix}type_company SET name = '{$text}', pos = '{$pos}' WHERE id='{$id}'";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
			elseif($id == 0 && $del == '')
			{
				$query = "REPLACE  INTO {$CFG->DB_Prefix}type_company (name, page_id, pos, visible) VALUES ('{$text}', 868, '{$pos}', 1)";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
			elseif($id > 0 && $del == 'del')
			{
				$query  = "DELETE FROM {$CFG->DB_Prefix}type_company WHERE id='{$id}'";
				if($CFG->DB->query($query))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
				exit;
			}
		}
	break;




	case 'client_id_search' :

		$WP = new Whatsapp();

		$text = $_POST["text"];

		$sql = getSQLArrayO("SELECT DISTINCT * FROM my_face WHERE visible='1' AND (name LIKE '%{$text}%' OR name_other LIKE '%{$text}%' OR  mobile LIKE '%{$text}%' OR whatsapp LIKE '%{$text}%' OR email LIKE '%{$text}%' OR info LIKE '%{$text}%') ORDER BY cdate ASC LIMIT 10");

		if( count ($sql ) > 0)
		{
			foreach($sql as $res)
			{
				if($res->img ==''){$avatar ='/tpl/img/new/avatra.png';}else{$avatar = $res->img;}

				$response = '<li>'.$res->mobile.' - '.$res->name.' <a data-name="'.htmlspecialchars($res->name).'"  data-mobile="'.htmlspecialchars($res->mobile).'" data-id="'.$res->id.'" data-avatar="'.$avatar.'" href="#" class="a_go">Добавить</a> |
				 <a href="/person/'.$res->id.'" target="_blank">Посмотреть</a>

				</li>';

				echo $response;
			}
		}
		else
		{
			echo 0;
		}

		exit;
	break;



	case 'updata_client_id' :
		$res = getSQLRowO("SELECT client_id FROM {$CFG->DB_Prefix}news WHERE id ='{$_POST[page_id]}'" );
		$wp = $res->client_id.','.$_POST[id];

		if( $res->client_id == "")
		{
			$wp = $_POST[id];
		}
		else
		{
			$wp = $res->client_id.','.$_POST[id];
		}

		$que  = "UPDATE {$CFG->DB_Prefix}news SET client_id='{$wp}' WHERE id='{$_POST[page_id]}'";
		$CFG->DB->query($que);

		exit;
	break;


	case 'client_id_dell' :

		$res = getSQLRowO("SELECT client_id FROM {$CFG->DB_Prefix}news WHERE id ='{$_POST[page_id]}'" );

		$client = explode(",", $res->client_id);

		foreach($client as $yes)
		{
			if($yes != $_POST[id]) {$real .= $yes.',';}
		}

		$trim = trim($real, ",");

		$que  = "UPDATE {$CFG->DB_Prefix}news SET client_id='{$trim}' WHERE id='{$_POST[page_id]}'";
		$CFG->DB->query($que);

		$time = time();
		$que  = "UPDATE {$CFG->DB_Prefix}face SET edate='{$time}' WHERE id='{$_POST[id]}'";
		$CFG->DB->query($que);


		$cdate = sqlDateNow();

		$text = 'Я открепил физ. лицо от компании *'.$_POST[page_id];

		$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, visible, cdate) VALUES ('{$_POST[id]}', 1012, '{$CFG->USER->USER_ID}', '{$text}', 1, '{$cdate}')";
		$CFG->DB->query($sql);



		exit;
	break;






	case 'client_id_record' :
		$id = $_POST["id"];
		$cdate = sqlDateNow();
		$sql = "INSERT INTO {$CFG->DB_Prefix}news (page_id, cdate, edate, manager_id, client_id, name_company, type_company_id, visible) VALUES (868, '{$cdate}', '{$cdate}', '{$CFG->USER->USER_ID}', '{$id}', 'НЕИЗВЕСТНАЯ ЗАПИСЬ', 10011925, 1)";
		$CFG->DB->query($sql);echo $CFG->DB->lastId();exit;

	break;




	case 'whatsapp_active' :

		$id = $_POST["id"];
		$img = $_POST["img"];
		$send = $_POST["send"];
		$subject = $_POST["subject"];

		//Здесь делаем удаление старых рассылок
		$dels = getSQLRowO(" SELECT GROUP_CONCAT(id) as del FROM my_tmp_whatsapp WHERE status = 0 AND visible = 0 AND id != {$id} ");

		if($dels->del != '')
		{
			$CFG->DB->query("DELETE FROM my_tmp_whatsapp WHERE id IN ({$dels->del})	");
			$CFG->DB->query("DELETE FROM my_tmp_whatsapp_rss WHERE whatsapp_id IN ({$dels->del})	");
		}


		if($img != '')
		{
			$reversed = array_reverse(explode(".", $img));
			$new_file = $_SERVER['DOCUMENT_ROOT'].'/documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/'.time().'.'.$reversed[0];
			$new_dir  = $_SERVER['DOCUMENT_ROOT'].'/documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/';
			if (!file_exists($new_file))
			{
				$img = 	$_SERVER['DOCUMENT_ROOT'].$img;
				if (file_exists($new_dir))
				{
					var_dump(copy($img, $new_file));
				}
				else
				{
					mkdir($new_dir, 0755, true);	copy($img, $new_file);
				}
			}
			$img = '/documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/'.time().'.'.$reversed[0];
		}

		$text = $_POST["text"];

		$valdelS = NULL;
		$del = getSQLArrayO(" SELECT face_id, id, COUNT(*) c FROM my_tmp_whatsapp_rss WHERE whatsapp_id = {$id} GROUP BY face_id HAVING c > 1;  ");

		foreach ($del as $valdel)
		{
			$valdelS .= $valdel->id.',';

			if($valdel->c > 1)
			{
				$CFG->DB->query("		DELETE FROM `my_tmp_whatsapp_rss` WHERE whatsapp_id = {$id} AND face_id  = '".$valdel->face_id."' AND id != '".$valdel->id."' 	");
			}
		}

		$sql = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}tmp_whatsapp_rss WHERE visible='0' AND status = 0 AND whatsapp_id = {$id} ORDER BY id DESC");
		if(count($sql) > 0)
		{
			$idT = NULL;
			for($x=0;$x<sizeof($sql);$x++)
			{
				 $idT .= $sql[$x]->id.', ';
			}

			$and_id = trim($idT, ", ");

			$query  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp_rss SET send_mobile='{$send}', visible = 1 WHERE id IN ({$and_id})  ";
			$CFG->DB->query($query);

			$que  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp SET img='{$img}', text='{$text}', subject='{$subject}', namber='{$send}', visible = 1 WHERE id='{$id}'";
			$CFG->DB->query($que);
		}

		exit;
	break;


	case 'whatsapp_update' :

		$id = $_POST["id"];
		$img = $_POST["img"];
		$subject = $_POST["subject"];
		$text = $_POST["text"];

		if($img != '')
		{
			$reversed = array_reverse(explode(".", $img));
			$new_file = $_SERVER['DOCUMENT_ROOT'].'/documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/'.time().'.'.$reversed[0];
			$new_dir  = $_SERVER['DOCUMENT_ROOT'].'/documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/';
			if (!file_exists($new_file))
			{
				$img = 	$_SERVER['DOCUMENT_ROOT'].$img;
				if (file_exists($new_dir))
				{
					copy($img, $new_file);
				}
				else
				{
					mkdir($new_dir, 0755, true);	copy($img, $new_file);
				}
			}
			$img = '/documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/'.time().'.'.$reversed[0];
		}

		$que  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp SET img='{$img}', text='{$text}', subject='{$subject}', visible = 1 WHERE id='{$id}'";
		if($CFG->DB->query($que))
			echo 1;
		else
			echo 0;


		exit;
	break;

	case 'whatsapp_send_to' :

		$sql = getSQLArrayO("SELECT id,page_id,parent_id,whatsapp_namber FROM {$CFG->DB_Prefix}comments WHERE visible='1' AND parent_id = 868 AND `text` LIKE '%Отправлена рассылка%' limit 100   ");

		foreach($sql as $res)
		{

			$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}face WHERE visible='1' AND whatsapp LIKE '%{$res->whatsapp_namber}%'  " );

			echo $que  = "UPDATE {$CFG->DB_Prefix}comments SET parent_id= 1012, page_id='{$namber->id}' WHERE id='{$res->id}'";
			$CFG->DB->query($que);
		}


		exit;
	break;




	case 'parser' :
	$text = file_get_contents( 'https://www.ps.kz/domains/whois/result?q=aza.kz' );

	if (strpos($text, 'но подождать ещё 1 день') !== false)
	{
		echo 'Еще ждем';
	}
	else
	{
		echo 'Время регистрировать';
		send_sms("+77072224282", "aza.kz свободен");
	}

	$rand = mt_rand(30000, 60000);

	?> <script type="text/javascript">	setInterval(function() {	window.location.reload(true);	}, <?=$rand;?>);	</script> <?
		exit;
	break;



	case 'whatsapp_active_go' :

		$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber = '{$CFG->_GET_PARAMS[1]}' " );
		$rand = mt_rand($namber->rand_start*60000, $namber->rand_off*60000);

		?> <script type="text/javascript">	setInterval(function() {	window.location.reload(true);	}, <?=$rand;?>);	</script><?
//$nextWeek = time() - (10 * 24 * 60 * 60);

		if(date('N') == 7) exit;
		if(date('N') == 6) exit;

		$start = mktime($namber->time_start_hour, $namber->time_start_minute, 0, date('n'), date('j'), date('Y'));
		$end = mktime($namber->time_off_hour, $namber->time_off_minute, 0, date('n'), date('j'), date('Y'));
		$times = time();

		if($end > $times && $start < $times)
		{

			$WP = new Whatsapp();

			if($CFG->_GET_PARAMS[1] == '+77755475012'  || $CFG->_GET_PARAMS[1] == '+77783035070'  || $CFG->_GET_PARAMS[1] == '+77010320320' ) {$spt = '';}	else  {$spt = " AND times < '{$times}'";}

			$res = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_rss WHERE status= 0 {$spt} AND send_mobile ='{$namber->id}' order by id asc" );
			$whatsapp_id = getSQLRowO("SELECT page_id,text,hello,img,namber FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE id= '{$res->whatsapp_id}' " );

			if($WP->get_status($res->send_mobile) == 0) {echo 'Не работает номер'; exit;}

			$hello = unserialize($whatsapp_id->hello);
			$text = unserialize($whatsapp_id->text);

			if(count($hello) > 1 )	{	$rand_welcom = mt_rand(0, count($hello)-1);		}	else	{	$rand_welcom = 0;	}
			if(count($text) > 1 )	{	$rand_text = mt_rand(0, count($text)-1);		}	else	{	$rand_text = 0;	}

			if( $res->name != '')
			{
				$text = $hello[$rand_welcom].', '.$res->name.'! '.$text[$rand_text];
			}
			else
			{
				$text = $hello[$rand_welcom].'! '.$text[$rand_text];
			}

			if( $whatsapp_id->img != '')
			{

				$filename = GenerFile($whatsapp_id->img);

				$images = getImageDataFromUrlImgTmp(rawurldecode($whatsapp_id->img));

				$data = ['phone' => $res->mobile, 'body'=> $images, 'filename'=> $filename, 'caption'=> $text];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/sendFile?token='.$namber->token;
				whatsapp_send($data, $url);
			}
			else
			{
				$data = ['phone' => $res->mobile, 'body'=> $text];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;

				whatsapp_send($data, $url);
			}


				$y = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}face WHERE page_id = 1012 AND visible= 1 AND whatsapp LIKE '%{$data[phone]}%' ");

				$tetx = 'Отправлена АВТОрассылка: '.$text;

				$cdate = sqlDateNow();
				$times = time();

				echo $sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from, attachments_image) VALUES ('{$y->id}', 1012, '{$namber->user_pid_id}', '{$tetx}', 1, 1, '{$cdate}', '{$res->mobile}', 1, '{$namber->namber}', '{$whatsapp_id->img}')";
				$CFG->DB->query($sql);

				$querys  = "UPDATE {$CFG->DB_Prefix}face SET edate= '{$times}' WHERE id='{$y->id}'";
				$CFG->DB->query($querys);

				$query  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp_rss SET status=1, send_time = '{$times}', send_text = '{$text}' WHERE id='{$res->id}'";
				$CFG->DB->query($query);

		}

		exit;

	break;



 	/* Заявка с сайта ledprokat.kz 1 */
	case 'ledprokat_1' :

		header('Access-Control-Allow-Origin: https://ledprokat.kz');
		if($_SERVER['HTTP_ORIGIN'] == 'https://ledprokat.kz')
		{
			$times = time();
			$cdate = sqlDateNow();
			$mobile = strip_tags($_POST['mobile']);
			$text = strip_tags($_POST['text']);

			$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber = '+77755475012' " );

			$y = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}face WHERE page_id = 1012 AND visible= 1 AND mobile LIKE '%{$mobile}%' ");
			if($y->id > 0)
			{
				$news_id = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}news WHERE page_id = 868 AND visible= 1 AND client_id =  '{$y->id}' ");

				$message = 'Новая заявка с сайта ledprokat.kz от '.$text.', номер записи в системе *'.$news_id->id;
				$data = ['phone' => '+77010353333', 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);

				$message = 'Ваша заявка на сайте ledprokat.kz успешно отправлена, через несколько минут с Вами свяжется наш менеджер!';
				$data = ['phone' => $mobile, 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);


				$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from) VALUES ('{$news_id->id}', 868, '{$namber->user_pid_id}', '{$message}', 1, 1, '{$cdate}', '{$mobile}', 1, '{$namber->namber}')";
				if($CFG->DB->query($sql)){	echo 1;	}else{	echo 0;	}

				$querys  = "UPDATE {$CFG->DB_Prefix}news SET edate= '{$cdate}' WHERE id='{$news_id->id}'";
				$CFG->DB->query($querys);

				$querys  = "UPDATE {$CFG->DB_Prefix}face SET edate= '{$times}' WHERE id='{$y->id}'";
				$CFG->DB->query($querys);
			}
			else
			{
				$subject = 'Клиент оставил заявку на сайте ledprokat.kz, что бы перезвонили';
				$sql = "INSERT INTO my_face (page_id, manager_id, mobile, whatsapp, name, name_other, info,  visible, cdate, edate) VALUES (1012, '{$namber->user_pid_id}', '{$mobile}', '{$mobile}', '{$text}', '',  '{$subject}', 1, '{$times}', '{$times}')";
				if($CFG->DB->query($sql)){	echo 1;	}else{	echo 0;	}

				$client_id = $CFG->DB->lastId();

				$qwery = "INSERT INTO my_news (page_id, manager_id, client_id, name_company, info,  visible, cdate, edate) VALUES (868, '{$namber->user_pid_id}', '{$client_id}', '{$text}', '{$subject}', 1, '{$cdate}', '{$cdate}')";
				$CFG->DB->query($qwery);

				$news_id = $CFG->DB->lastId();

				$message = 'Новая заявка с сайта ledprokat.kz от '.$text.', номер записи в системе *'.$news_id;
				$data = ['phone' => '+77010353333', 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);

				$message = 'Ваша заявка на сайте ledprokat.kz успешно отправлена, через несколько минут с Вами свяжется наш менеджер!';
				$data = ['phone' => $mobile, 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);


			}

		}
		exit;
	break;
	/* Заявка с сайта ledprokat.kz 1 */





 	/* Заявка с сайта homuty.kz 1 */
	case 'homuty_kz' :

		header('Access-Control-Allow-Origin: https://homuty.kz');
		if($_SERVER['HTTP_ORIGIN'] == 'https://homuty.kz')
		{
			$cdate = sqlDateNow();
			$times = time();
			$mobile = strip_tags($_POST['mobiles']);
			$html = strip_tags($_POST['html']);
			$name = strip_tags($_POST['name']);

			$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber = '+77010320320' " );

			$y = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}face WHERE page_id = 1012 AND visible= 1 AND mobile LIKE '%{$mobile}%' ");
			if($y->id > 0)
			{
				$news_id = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}news WHERE page_id = 868 AND visible= 1 AND client_id =  '{$y->id}' ");

				$message = 'Новая заявка с сайта homuty.kz от '.$name.', номер записи в системе *'.$news_id->id.'

'.$html;

				$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from) VALUES
				('{$news_id->id}', 868, '{$namber->user_pid_id}', '{$message}', 1, 1, '{$cdate}', '{$mobile}', 1, '{$namber->namber}')";
				if($CFG->DB->query($sql)){	echo 1;	}else{	echo 0;	}

				$querys  = "UPDATE {$CFG->DB_Prefix}news SET edate= '{$cdate}' WHERE id='{$news_id->id}'";
				$CFG->DB->query($querys);

				$quer  = "UPDATE {$CFG->DB_Prefix}face SET edate= '{$times}' WHERE id='{$y->id}'";
				$CFG->DB->query($quer);

				$data = ['phone' => '+77081756010', 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);

				$data = ['phone' => '+77010323333', 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);

			}
			else
			{
				// Создаем лицо
				$subject = 'Клиент оставил заявку на сайте homuty.kz';
				$sql = "INSERT INTO my_face (page_id, manager_id, mobile, whatsapp, name, name_other, info,  visible, cdate, edate) VALUES (1012, '{$namber->user_pid_id}', '{$mobile}', '{$mobile}', '{$name}', '',  '{$subject}', 1, '{$times}', '{$times}')";
				if($CFG->DB->query($sql)){	echo 1;	}else{	echo 0;	}
				$client_id = $CFG->DB->lastId();

				// Создаем компанию
				$qwery = "INSERT INTO my_news (page_id, manager_id, client_id, name_company, info,  visible, cdate, edate) VALUES (868, '{$namber->user_pid_id}', '{$client_id}', '{$name}', '{$subject}', 1, '{$cdate}', '{$cdate}')";
				$CFG->DB->query($qwery);
				$news_id = $CFG->DB->lastId();

				//Создаем комент
				$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from) VALUES
				('{$news_id}', 868, '{$namber->user_pid_id}', '{$html}', 1, 1, '{$cdate}', '{$mobile}', 1, '{$namber->namber}')";
				$CFG->DB->query($sql);


				$message = 'Новая заявка с сайта homuty.kz от '.$name.', номер записи в системе *'.$news_id.'

'.$html;
				$data = ['phone' => '+77081756010', 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);

				$data = ['phone' => '+77010323333', 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);
			}

				//sleep(20);
				$message = 'Здравствуйте, это с сайта www.homuty.kz, Настя. А куда вам подвезти хомуты? Адрес дайте, пожалуйста.';
				$data = ['phone' => $mobile, 'body'=> $message];
				$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;
				whatsapp_send($data, $url);


		}
		exit;
	break;
	/* Заявка с сайта homuty.kz 1 */





	case 'whatsapp_send_masseger' :

		$string = preg_replace('~[^0-9]+~','',$_POST["manager"]);

		$mobile = "+".$string;

		$id = $_POST["Ondate"];
		$img = $_POST["img"];

		$cdate = sqlDateNow();

		$data = ['phone' => $mobile, 'body'=> $_POST["text"]];

		$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE id= '{$_POST[from]}' " );

		if( $img != '')
		{
			$data = ['phone' => $mobile, 'body'=> $_POST["text"]];
			whatsapp_send($data, $namber->url_message);

			$images = 'https://domainscrm.ru'.$img;
			$data = ['phone' => $mobile, 'body'=> $images, 'filename'=> 'prev.jpg'];
			whatsapp_send($data, $namber->url_file);
		}
		else
		{
			$data = ['phone' => $mobile, 'body'=> $_POST["text"]];
			whatsapp_send($data, $namber->url_message);
		}


		$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from, attachments_image) VALUES ('{$id}', 868, '{$CFG->USER->USER_ID}', '{$_POST[text]}', 1, 1, '{$cdate}', '{$mobile}', 1, '{$namber->namber}', '{$img}')";

		if($CFG->DB->query($sql))
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
		exit;


	break;

		case 'tesla_test' :
		set_time_limit(600);

		$sql = getSQLArrayO("SELECT id, whatsapp_namber FROM my_comments WHERE visible='1' AND parent_id = 868 AND whatsapp = 1 AND whatsapp_from IN ('+77755475012') AND whatsapp_namber !='' AND whatsapp_namber !='+' AND tesla = 0 GROUP BY whatsapp_namber ORDER BY `my_comments`.`whatsapp_namber`  LIMIT 30  ");


		foreach($sql as $res)
		{
			$rils = preg_replace('/[^0-9]/', '', $res->whatsapp_namber);
			$string = str_replace(' ','',$rils);
			$str = substr_replace($string, '+7-', 0, 1);
			$one =  substr($str,  0, 6);
			$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

		 	$sp = getSQLArrayO("SELECT id FROM my_comments WHERE visible='1' AND whatsapp = 1 AND whatsapp_from = '+77755475012'
			 		AND  whatsapp_namber = '{$rel}' OR whatsapp_namber = '{$res->whatsapp_namber}' limit 5  ");

			if(count($sp) > 4)
			{
				$id[] = $rel;
			}

				$CFG->DB->query("UPDATE my_comments SET tesla = 1 WHERE id='{$res->id}'");

		}

		$id = array_unique($id);

		foreach($id as $respon)
		{
				$namber = getSQLRowO("SELECT id, marketing_id, mobile FROM my_face WHERE whatsapp= '{$respon}' " );
				$name = explode(",", $namber->marketing_id);

				if($name[0] > 0)
				{
						$mart = $namber->marketing_id.',150';
						$CFG->DB->query("UPDATE my_face SET marketing_id = '{$mart}' WHERE id='{$namber->id}'");
						echo 'Лицо C МП id '.$namber->id.PHP_EOL.'<br>';
				}
				else
				{
						$mart = '150';
						$CFG->DB->query("UPDATE my_face SET marketing_id = '{$mart}' WHERE id='{$namber->id}'");
						echo 'Лицо без МП id '.$namber->id.PHP_EOL.'<br>';
				}
		}

			exit;
		break;


	exit;
}


 ?>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>

<div class="content">


	<h2>Статистика по записям</h2>

<div class="white">

	<!--  Записи !-->
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
		  var data = google.visualization.arrayToDataTable([
			["Element", "Количество записей", { role: "style" } ],
			  <?
				$m = date('m')*1;
				$year = date('Y')*1;
				$day = date('t')*1;

				for ($y=0; $y<$m; $y++)
					{
						$cnt = $y+1;
						$sql = "SELECT cdate FROM {$CFG->DB_Prefix}news WHERE visible=1 and cdate >= '{$year}-{$cnt}-00 00:00:00' and cdate <= '{$year}-{$cnt}-{$day} 23:59:00' ORDER BY id DESC";
						$l = getSQLArrayO($sql); ?>
						['<?=dateSQL2TEXT('2015-'.$cnt.'-00 00:00:00', "MN")?>',  <? $res += count($l); echo $res;?>, "color: #4285F4"],
				<? }?>
		  ]);

		  var view = new google.visualization.DataView(data);
		  view.setColumns([0, 1, { calc: "stringify", sourceColumn: 1, type: "string", role: "annotation" }, 2]);
		  var options = {
			title: "Общее количество ЗАПИСЕЙ по месяцам.",
			width: 800,
			height: 400,
			legend: { position: "none" },
		  };
		  var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
		  chart.draw(view, options);
		}
    </script>
	<div id="columnchart_values" style="width:90%; height:400px; margin:0 auto;"></div>

	<hr>

	<script type="text/javascript">
      google.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Месяц', 'Количество записей'],
          <?
            $m = date('m')*1;
            $year = date('Y')*1;
            $day = date('t')*1;

            for ($y=0; $y<$m; $y++)
                {
                    $cnt = $y+1;
                    $sql = "SELECT cdate FROM {$CFG->DB_Prefix}news WHERE visible=1 and cdate >= '{$year}-{$cnt}-00 00:00:00' and cdate <= '{$year}-{$cnt}-{$day} 00:00:00' ORDER BY id DESC";
                    $l = getSQLArrayO($sql); ?>
                    ['<?=dateSQL2TEXT('2015-'.$cnt.'-00 00:00:00', "MN")?>',  <?=count($l);?>],
            <? }?>
        ]);

        var options = {
          title: 'Месячная интенсивность заполнения Записей.',
          legend: { position: 'none' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

    <div id="curve_chart" style="width:90%; height:400px; margin:0 auto;"></div>
    <p>&nbsp;</p>


	<h2>Статистика по заметкам</h2>
	<!--  Заметки !-->
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
		  var data = google.visualization.arrayToDataTable([
			["Element", "Количество заметок", { role: "style" } ],
			  <?
				$m = date('m')*1;
				$year = date('Y')*1;
				$day = date('t')*1;

				for ($y=0; $y<$m; $y++)
					{
						$cnt = $y+1;
						$sql = "SELECT cdate FROM {$CFG->DB_Prefix}comments WHERE visible=1 and cdate >= '{$year}-{$cnt}-00 00:00:00' and cdate <= '{$year}-{$cnt}-{$day} 23:59:00' ORDER BY id DESC";
						$l = getSQLArrayO($sql);
						 ?>
						['<?=dateSQL2TEXT('2015-'.$cnt.'-00 00:00:00', "MN")?>',  <? $respon += count($l); echo $respon;?>, "color: #4285F4"],
				<? }?>
		  ]);

		  var view = new google.visualization.DataView(data);
		  view.setColumns([0, 1, { calc: "stringify", sourceColumn: 1, type: "string", role: "annotation" }, 2]);
		  var options = {
			title: "Общее количество ЗАМЕТОК по месяцам.",
			width: 800,
			height: 400,
			legend: { position: "none" },
		  };
		  var chart = new google.visualization.ColumnChart(document.getElementById("notes"));
		  chart.draw(view, options);
		}
    </script>
	<div id="notes" style="width:90%; text-align:center; height:400px; margin:0 auto;"></div>

	<hr>

 	<script type="text/javascript">
      google.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Месяц', 'Количество записей'],
          <?
            $m = date('m')*1;
            $year = date('Y')*1;
            $day = date('t')*1;

            for ($y=0; $y<$m; $y++)
                {
                    $cnt = $y+1;
                    $sql = "SELECT cdate FROM {$CFG->DB_Prefix}comments WHERE visible=1 and cdate >= '{$year}-{$cnt}-00 00:00:00' and cdate <= '{$year}-{$cnt}-{$day} 23:59:00' ORDER BY id DESC";
                    $l = getSQLArrayO($sql); ?>
                    ['<?=dateSQL2TEXT('2015-'.$cnt.'-00 00:00:00', "MN")?>',  <?=count($l);?>],
            <? }?>
        ]);

        var options = {
          title: 'Месячная интенсивность заполнения ЗАМЕТОК.',
          legend: { position: 'none' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_notes'));

        chart.draw(data, options);
      }
    </script>

    <div id="curve_notes" style="width:90%; height:400px; margin:0 auto;"></div>
    <p>&nbsp;</p>

	<h2>Статистика по компаниям</h2>
    <br clear="all">
    <br clear="all">
	<?
        $company = SelectDataArray('company', 1, 'pos ASC');

        for($r=0; $r<sizeof($company); $r++)
        {
            $users = AndDataArray('users', 'user_id', $company[$r]->id, 0);


            for($y=0; $y<sizeof($users); $y++)
            {
                $id .= $users[$y]->id.', ';
            }

            $and_id = trim($id, ", ");

            if($and_id == !"")
            {
                $name .= '"'.$company[$r]->name.'", ';

                $manager_id = "AND manager_id IN({$and_id})";
                $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE visible=1 {$manager_id} ");
                $count .= count($sql).', ';
            }
            $id = "";
        }

    ?>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["bar"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', <?=trim($name, ", ");?>],
          ['', <?=trim($count, ", ");?>]

        ]);

        var options = {
          chart: {

          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, options);
      }
    </script>
    <div id="columnchart_material" style="width:70%; height:870px; margin:0 auto;"></div>

</div>
</div>
