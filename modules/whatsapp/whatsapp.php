<?
$WP = new Whatsapp();

switch ($CFG->_GET_PARAMS[0])
{
	case 'send_contact':
		$WP->SendContact($_POST);
	break;


	case 'test':

		/* Получить список шаблонов
		echo '<pre>'; print_r(file_get_contents('https://api.1msg.io/45975/templates?token=mbmovqb92f11b9xj')); echo '</pre>';
		*/

		/* Отправка шаблона с картинкой
			$array = '{"template":"start_template_image_all","language":{"policy":"deterministic","code":"en"},"namespace":"f89d2c32_4c8b_4379_b53c_197020e2abf2","params":[{"type":"header","parameters":[{"type":"image","image": {"link":"https://edrive.kz/upload/iblock/546/uzmjp1n75e99iw2pe0fosc6c96yvfts5.jpg"}}]},{"type":"body","parameters":[{"type":"text","text":"test"}]}],"phone":"77072224282"}';

		$url = 'https://api.1msg.io/45975/sendTemplate?token=mbmovqb92f11b9xj';
		$jsons = whatsapp_send(json_decode($array), $url);
		$json_a = json_decode($jsons, true);
		*/

		/*
		Отправка контакта
		$contacts[0] = array('name' => array('first_name' => 'Gennadiy', 'formatted_name' => 'Gennadiy Gnezdilov'), 'phones' => array( 0 => array('phone' => '+77072224282', 'type' => 'WORK', 'wa_id' => '77072224282')) );
		$data = ['phone' => '+77072224282', 'contacts'=> $contacts ];
		$url = 'https://api.1msg.io/45975/sendContact?token=mbmovqb92f11b9xj';
		$jsons = whatsapp_send($data, $url);
		$json_a = json_decode($jsons, true);
		*/

		/*
		Проверка на существования номера
		$data = ['contacts' => '+77072224282'];
		$url = 'https://api.1msg.io/45975/contacts?token=mbmovqb92f11b9xj';
		$json = whatsapp_send($data, $url);
		$json_a = json_decode($json, true);
		if($json_a['contacts'][0]['status'] == 'valid')
		{
			echo 'Такой номер существует';
		}
		else
		{
			echo 'Такой номер НЕсуществует';
		}
		*/

		/*
			Отправка текста
			$data = ['phone' => '+77072224282', 'body'=>'Hello Gena 3' ];
			$url = 'https://api.1msg.io/45975/sendMessage?token=mbmovqb92f11b9xj';
			whatsapp_send($data, $url);
		*/

		exit;
	break;

	case 'user_info':
		$id = $CFG->_GET_PARAMS[1]; // id пользователя
		$day_7 = date('Y-m-d 00:00:01', strtotime("Monday this week")); // Начало недели
		$current = 	date("Y-m-d 23:59:59"); // Конец недели

		$user = getSQLRowO("SELECT * FROM my_users  WHERE id = {$id} AND visible = 1 ");
		if($user->id > 0)
		{
			// Сумма от продаж как менеджер
			$respon = getSQLRowO("SELECT SUM(count) FROM my_money_list  WHERE (cdate >= '{$day_7}') AND (cdate <= '{$current}') AND type = 3 AND manager_id = {$id} ");
			// Сумма от продаж как Товарный Босс
			$respon_boss = getSQLRowO("SELECT SUM(count) FROM my_money_list  WHERE (cdate >= '{$day_7}') AND (cdate <= '{$current}') AND type = 4 AND manager_id = {$id} ");

			$sum = $respon->{'SUM(count)'};
			$sum_boss = $respon_boss->{'SUM(count)'};

			$html .= 'Здравствуйте '.$user->name.'!'.PHP_EOL;
			$html .= 'С Вами общается витруальный финансовый аналитик forSign.kz'.PHP_EOL;
			$html .= 'Спешу известить Вас, что за период с '.date('d.m.Y', strtotime("Monday this week")).' по '.date("d.m.Y").', за рабочую неделю, менеджерское начисленное Вам составило '.number_sum($sum).' тенге!'.PHP_EOL;
			$html .= 'А руководящее АН составило '.number_sum($sum_boss).' тенге.'.PHP_EOL;
			$html .= 'Продолжайте думать как увеличить продажи продукции.'.PHP_EOL;

			$wp = getSQLRowO("SELECT * FROM my_tmp_whatsapp_namber WHERE  namber = '+77010320320' AND visible = 1   ");
			$data = ['phone' => '$user->mobile', 'body'=> $html];
			$url = 'https://api.chat-api.com/instance'.$wp->wp_id.'/message?token='.$wp->token;
			whatsapp_send($data, $url);
		}
		else
		{
			echo 'Такой пользователь не существует';
		}
		exit;
	break;


	case 'send':
		echo $WP->get_send_crm($_POST);
	break;

	case 'you':


	?>



<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
<title>Fullscreen HTML5 Page Background Video – Indigo</title>
<link rel="stylesheet" href="https://idg.net.ua/blog/wp-content/uploads/reset.css">
<link rel="stylesheet" href="https://idg.net.ua/blog/wp-content/uploads/style-html5-video-bg.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="favicon.png"/>

</head>
<body>
<div class="content">
<h1>HTML5 Page Background Video</h1>
<p><a href="/blog/video-fon-dlya-sajta-html5">Вернуться к уроку</a></p>
</div>
<div id="video-bg">
	<video width="100%" height="auto" autoplay="autoplay" loop="loop" preload="auto" poster="/blog/wp-content/uploads/daisy-stock-poster.jpg">
		<source src="https://idg.net.ua/blog/wp-content/uploads/daisy-stock-h264-video.mp4" type="video/mp4"></source>
		<source src="https://idg.net.ua/blog/wp-content/uploads/daisy-stock-webm-video.webm" type="video/webm"></source>
	</video>
</div>
</body>
</html>


    <?

	echo time();
			exit;
	break;

	case 'qr':

		if(isset($CFG->_GET_PARAMS[1]))
		{
			$res = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber ='{$CFG->_GET_PARAMS[1]}' " );
			$url = "https://eu36.chat-api.com/instance".$res->wp_id."/status?token=".$res->token."";
			$file = file_get_contents($url);
			$json_a = json_decode($file, true);
			if($json_a["accountStatus"] == 'got qr code')
			{
				echo ' <img src="'.$json_a['qrCode'].'" alt="got qr code" /><script>setTimeout(function() {window.location.reload();}, 10000);</script>';
			}
			else
			{
				echo 'Вы авторизованы!';
			}
		}
		exit;
	break;


	case 'insert-file':

		$file_url = $_POST["file"];

		$type = pathinfo($file_url);

		$rest = substr($file_url, 1);


		$new_file = 'documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/send/'.$_POST[page_id].'/'.time().'.'.$type['extension'];
		$new_dir  = 'documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/send/'.$_POST[page_id];


		if (!file_exists($new_file))
		{
			$str = substr($img, 1);
			if (file_exists($new_dir)) {	copy($rest, $new_file);	}
			else	{	mkdir($new_dir, 0755, true);	copy($rest, $new_file);	}
		}

		$cdate = sqlDateNow();

		$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, original, user_id, type, text, cdate, visible) VALUES ('{$_POST[page_id]}', '{$type[filename]}', '{$CFG->USER->USER_ID}', '{$type[extension]}', '{$new_file}', '{$cdate}', 1)";
		if($CFG->DB->query($sql))
		{
			echo $CFG->DB->lastId();
		}
	break;

	//Удалить маркетинг план
	case 'dsdfasdfasdf':
		$sql = getSQLArrayO("select id, marketing_id from my_face where find_in_set(126,marketing_id) <> 0 AND page_id = 1012 ");
		for ($i=0; $i<sizeof($sql); $i++)
		{
			$mi = explode(",", $sql[$i]->marketing_id);
			$toremove = 126;
			unset($mi[array_search($toremove, $mi)]);

			foreach($mi as $yes)
			{
				$list.= $yes.',';
			}

			$list = trim($list, ',');

			$edate  = "UPDATE {$CFG->DB_Prefix}face SET marketing_id='{$list}' WHERE id='{$sql[$i]->id}'";
			$CFG->DB->query($edate);

			$list = '';
		}
	exit;
	break;

	//Удалить маркетинг план
	case 'dsdfasrtertredfasdf':
		$sql = getSQLArrayO("SELECT * FROM my_news WHERE  manager_id IN (1,476,250,83,84,85,86,87,594,94,371,434,182,546,522,131,132,133,432,143,369,258,175,185,212,189,190,191,209,246,233,235,238,244,310,257,259,267,271,279,274,275,545,284,283,285,287,289,290,291,295,299,300,305,301,304,306,307,309,312,311,314,315,316,317,320,322,324,325,327,328,331,329,330,332,346,333,341,338,339,340,347,348,351,353,354,374,375,376,364,365,366,372,373,377,378,388,389,390,391,393,394,396,401,411,412,418,419,422,424,426,427,428,430,435,468,441,442,443,445,450,451,452,453,463,465,469,470,471,472,473,474,475,477,478,479,481,483,484,486,487,492,488,489,490,491,494,547,496,497,499,500,502,504,582,506,581,514,510,511,512,513,515,516,575,530,574,533,572,536,537,538,539,541,549,552,560,561,564,562,563,565,566,567,568,583,584,590,586,589,591) AND city_id IN (4) AND page_id=868 AND visible='1' AND find_in_set(3,type_company_id) <> 0 ORDER BY edate DESC ");

		for ($i=0; $i<sizeof($sql); $i++)
		{
			$mi = explode(",", $sql[$i]->type_company_id);

			//$toremove = '1';
			//unset($mi[array_search($toremove, $mi)]);

			foreach($mi as $yes)
			{
				if($yes==1) break;

				$list.= $yes.',';
			}

			$list = trim($list, ',');

			echo  $edate  = "UPDATE {$CFG->DB_Prefix}news SET type_company_id='{$list}' WHERE id='{$sql[$i]->id}'

			";
			//$CFG->DB->query($edate);

			$list = '';
		}
	exit;
	break;


	case 'testdfgd':

		 $sql = getSQLArrayO("SELECT * FROM my_comments WHERE attachments_image LIKE '%.jpeg%' AND parent_id = 1012 AND whatsapp= 1 AND visible = 1 AND test = 0 ORDER BY id DESC limit 100");

		foreach ($sql as $value)
		{

			$url = iconv('utf-8', 'windows-1251', $value->attachments_image);
			$image = substr($url, 1);
			$type = pathinfo(rawurldecode($image));

			$new_file = 'documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/send/'.$value->parent_id.'_'.$value->page_id.'/'.md5(time()).'.'.$type["extension"];
			$new_dir  = 'documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/send/'.$value->parent_id.'_'.$value->page_id;
			if (!file_exists($new_file))
			{
				if (!file_exists($image))
				{
					echo 'Нет файла
					';

					echo $que  = "UPDATE my_comments SET attachments_image='', test = 1 WHERE id='{$value->id}'
					";
					$CFG->DB->query($que);

					continue;
				}

				mkdir($new_dir, 0755, true);
				$img = AcImage::createImage($image);
				AcImage::setRewrite(true);
				AcImage::setQuality(70);
				$img->resize(300, 300)->save($new_file);
			}


			$new_file = '/documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/send/'.$value->parent_id.'_'.$value->page_id.'/'.md5(time()).'.'.$type["extension"];

				if (!file_exists($new_file))
				{
					echo $que  = "UPDATE my_comments SET attachments_image='{$new_file}', test = 1 WHERE id='{$value->id}'
					";
					$CFG->DB->query($que);

				}
		}

		exit;
	break;



	//Удалить маркетинг план
	case 'start':

		echo '<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>';
		?>
            <div class="white">
                <div id="status_0"></div><br clear="all">
                <div id="status_1"></div><br clear="all">
                <div id="status_2"></div><br clear="all">
                <div id="status_3"></div><br clear="all">
                <div id="status_4"></div><br clear="all">
                <div id="status_5"></div><br clear="all">
                <div id="status_6"></div><br clear="all">
                <div id="status_7"></div><br clear="all">
            </div>

            <script>
                <?
                $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE visible='1' AND wp_id > 0 order by id ASC ");
                $vse = count($sql)-1;
                for ($i=0; $i<sizeof($sql); $i++)
                {
                    ?>
                        function d_<?=$i;?>()
                        {
                            $.ajax({ url: "/whatsapp/json_get_messages/<?=$sql[$i]->namber;?>", type: "GET", cache: true, async:false, success: function(response) { $("#status_<?=$i;?>").html("<?=$sql[$i]->namber;?> " + response);		}	});
                            cb();
                            <? if($vse == $i) {?> setInterval(function() { location.reload();
                            }, 20000); <?}?>
                        }

                    <? $n.= 'd_'.$i.', ';
                }
                ?>

                var fns = [ <?=trim($n, ", ");?>];

                function cb()
                {
                    var fn = fns.shift();
                    if( typeof fn == 'function' ) fn.call();
                }

                setInterval(function()
                {
                    cb();
                }, 10000);
            </script>
        <?
	exit;
	break;




	case 'json_get_messages':

		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);

		set_time_limit(10);
		ini_set('default_socket_timeout', 2);

		$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber = '{$CFG->_GET_PARAMS[1]}' " );

		$url = "https://eu36.chat-api.com/instance".$namber->wp_id."/messages?token=".$namber->token."&last";

		$ch=curl_init();
		$timeout=10;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$result=curl_exec($ch);
		curl_close($ch);

		$json_a = json_decode($result, true);
		$array  = $json_a["messages"];

		for ($i=0; $i<sizeof($array); $i++)
		{
			$cdate = sqlDateNow();
			$times = time();
			if($array[$i]["fromMe"] != '' ) continue;

			if($array[$i]["time"] > $namber->times)
			{
				$rel = $WP->real_tel($array[$i]["author"]);
				$res = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}face WHERE visible='1' AND page_id='1012' AND whatsapp LIKE '%{$rel}%'  ORDER BY cdate ASC");
				if(count($res) > 0)
				{
					$timesR = $array[$i]['time'];
					$query  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp_namber SET times='{$timesR}' WHERE id='{$namber->id}'   ";
					$CFG->DB->query($query);
					echo 'Обновили время'.PHP_EOL;
				}
				else
				{
					if(count($res) == 0)
					{
						$rel = $WP->real_tel($array[$i]["author"]);

						$names = 'Новый клиент Whatsapp '.$rel;	;
						$mobiles = $rel;
						$whatsapps = $rel;
						$resp = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}face WHERE visible='1' AND page_id='1012' AND mobile LIKE '%{$rel}%'  ORDER BY cdate ASC");

						if(count($resp) > 0)
						{
							$sql = "UPDATE {$CFG->DB_Prefix}face SET whatsapp = '{$whatsapps}' WHERE id='{$resp->id}' ";
							$CFG->DB->query($sql);
						}
						else
						{
							$sql = "INSERT INTO {$CFG->DB_Prefix}face (page_id, cdate, edate, visible, info, name, mobile, whatsapp, manager_id) VALUES ('1012', '{$times}', '{$times}', 1, '{$names}', '{$names}', '{$mobiles}', '{$whatsapps}',  {$namber->user_pid_id})";
							$CFG->DB->query($sql);
							$rea_id =  mysql_insert_id();

							$sql = "INSERT INTO {$CFG->DB_Prefix}news (type_company_id, client_id, page_id, cdate, edate, visible, name_company, info, manager_id) VALUES ('10011925', '{$rea_id}', '868', '{$cdate}', '{$cdate}', 1, '{$names}', '{$names}', {$namber->user_pid_id})";
							$CFG->DB->query($sql);
						}
						echo 'Создали контакт'.PHP_EOL;
						break;

					}
				}

			}
			//	$array[$i]["time"] > $o->times
		}
		//	for ($i=0; $i<sizeof($array); $i++)

		exit;


	break;











	case 'json_get_messages___':

		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);

		set_time_limit(10);
		ini_set('default_socket_timeout', 2);

		$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE namber = '{$CFG->_GET_PARAMS[1]}' " );

		$url = "https://eu36.chat-api.com/instance".$namber->wp_id."/messages?token=".$namber->token."&last";

		$ch=curl_init();
		$timeout=10;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		$result=curl_exec($ch);
		curl_close($ch);

		$json_a = json_decode($result, true);
		$array  = $json_a["messages"];

		print_r($array); exit;



		for ($i=0; $i<sizeof($array); $i++)
		{
			$cdate = sqlDateNow();
			$times = time();

			if($array[$i]["fromMe"] != '' ) continue;

			if($array[$i]["time"] > $namber->times)
			{
				$rel = $WP->real_tel($array[$i]["author"]);
				$res = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}face WHERE visible='1' AND page_id='1012' AND whatsapp LIKE '%{$rel}%'  ORDER BY cdate ASC");

				if(count($res) > 0)
				{
					$pids = getSQLRowO("select id from my_news where find_in_set('{$res->id}',client_id) <> 0 AND page_id = 868 ");

					$edate  = "UPDATE {$CFG->DB_Prefix}face SET edate='{$times}' WHERE id='{$res->id}'";
					$CFG->DB->query($edate);
					$edate  = "UPDATE {$CFG->DB_Prefix}news SET edate='{$cdate}' WHERE id='{$pids->id}'";
					$CFG->DB->query($edate);

					$body = mysql_real_escape_string($array[$i]["body"]);

					if($array[$i]["type"] == 'chat'){$bodys = $body;} else {$bodys = 'Абонент отправил вам файл';}

					$comm = getSQLRowO("SELECT user_id FROM {$CFG->DB_Prefix}comments WHERE visible='1' AND page_id='{$pids->id}' AND whatsapp = 1 ORDER BY cdate DESC");

					$sql = "INSERT INTO {$CFG->DB_Prefix}alarm_whatsapp (page_id, user_id,autor_id,status, text, cdate, visible) VALUES ('{$pids->id}', '{$comm->user_id}', {$namber->user_pid_id}, 0, '{$bodys}', '{$cdate}', 1)";
					$CFG->DB->query($sql);

					switch ($array[$i]['type'])
					{
						case 'chat':
							echo $sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, cdate, whatsapp, visible, whatsapp_from, whatsapp_namber) VALUES ('{$pids->id}', 868, {$namber->user_pid_id}, '{$body}', '{$cdate}', 2, 1, '{$namber->namber}', '{$rel}')	";
							$CFG->DB->query($sql);

						break;

						case 'ptt':
						case 'audio':
						echo 'Аудио';
							$name_file = md5($array[$i]['time']);
							$url = $array[$i]['body'];
							$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/TEXT/'.$name_file.'.mp3';
							$path_site = 'documents/whatsapp/TEXT/'.$name_file.'.mp3';
							file_put_contents($path, file_get_contents($array[$i]['body']));
							$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, cdate, original, text, type, user_id, visible) VALUES ('{$pids->id}', '{$cdate}', 'Голосовое сообщение', '{$path_site}', 'mp3', {$namber->user_pid_id}, 1); ";
							$CFG->DB->query($sql);
							//usleep(200000);
							$ptt = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}attachments WHERE visible= 1 order by id desc");
							$test = ','.$ptt->id;

							if(isset($array[$i]['caption']) != '')
							{
								$caption = $array[$i]['caption'];
							}
							else {$caption = 'Абонент отправил вам голосовик';}

							$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attach_files_music, cdate, whatsapp, visible, text, whatsapp_from, whatsapp_namber) VALUES ('{$pids->id}', 868, {$namber->user_pid_id}, '{$test}', '{$cdate}', 2, 1, '{$caption}', '{$namber->namber}', '{$rel}')";
							$CFG->DB->query($sql);

							$timesS = $array[$i]['time'];

							echo $query  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp_namber SET times='{$timesS}' WHERE id='{$namber->id}'   ";
							$CFG->DB->query($query);
							exit;

						break;

						case 'image':
							$name_file = md5(time());

							$url = $array[$i]['body'];
							$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/85/'.$name_file.'.jpg';
							$path_site = '/documents/whatsapp/85/'.$name_file.'.jpg';
							file_put_contents($path, file_get_contents($url));

							if($array[$i]['caption'] != '')
							{
								$caption = $array[$i]['caption'];
							}
							else {$caption = 'Абонент отправил вам картинку';}

							$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attachments_image, cdate, whatsapp, visible, text, whatsapp_from, whatsapp_namber) VALUES ('{$pids->id}', 868, {$namber->user_pid_id}, '{$path_site}', '{$cdate}', 2, 1, '{$caption}', '{$namber->namber}', '{$rel}')";
							$CFG->DB->query($sql);
						break;


						case 'video':

						break;

						default:
	//print_r($array[$i]);

							if($array[$i]['body'] != '')
							{
								$ext = pathinfo($array[$i]['body'], PATHINFO_EXTENSION);
								$new_string = preg_replace("/\?.+/", "", $ext);

								$name_file = md5(time());
								$path = $_SERVER['DOCUMENT_ROOT'] . '/documents/whatsapp/85/'.$name_file.'.'.$new_string;
								$path_site = 'documents/whatsapp/85/'.$name_file.'.'.$new_string;

								file_put_contents($path, file_get_contents($array[$i]['body']));


								$sql = "INSERT INTO {$CFG->DB_Prefix}attachments (page_id, cdate, original, text, type, user_id, visible) VALUES ('{$pids->id}', '{$cdate}', 'Файл', '{$path_site}', '{$new_string}', {$namber->user_pid_id}, 1)";
								$CFG->DB->query($sql);

								$ptt = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}attachments WHERE visible= 1 order by id desc");
								$file = ','.$ptt->id;

								if($array[$i]['caption'] != '')
								{
									$caption = $array[$i]['caption'];
								}
								else {$caption = 'Абонент отправил вам файл';}

								echo $sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, attachments_file, cdate, whatsapp, visible, text, whatsapp_from, whatsapp_namber) VALUES ('{$pids->id}', 868, {$namber->user_pid_id}, '{$file}', '{$cdate}', 2, 1, '{$caption}', '{$namber->namber}', '{$rel}')";
								$CFG->DB->query($sql);



							}
						break;
					}
					// switch ($array[$i]['type'])

					$timesR = $array[$i]['time'];
					$query  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp_namber SET times='{$timesR}' WHERE id='{$namber->id}'   ";
					$CFG->DB->query($query);

				//if(count($res) > 0)
				}
				else
				{
					if(count($res) == 0)
					{
						$rel = $WP->real_tel($array[$i]["author"]);

						$names = 'Новый клиент Whatsapp '.$rel;	;
						$mobiles = $rel;
						$whatsapps = $rel;
						$resp = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}face WHERE visible='1' AND page_id='1012' AND mobile LIKE '%{$rel}%'  ORDER BY cdate ASC");

						if(count($resp) > 0)
						{
							$sql = "UPDATE {$CFG->DB_Prefix}face SET whatsapp = '{$whatsapps}' WHERE id='{$resp->id}' ";
							$CFG->DB->query($sql);
						}
						else
						{
							$sql = "INSERT INTO {$CFG->DB_Prefix}face (page_id, cdate, edate, visible, info, name, mobile, whatsapp, manager_id) VALUES ('1012', '{$times}', '{$times}', 1, '{$names}', '{$names}', '{$mobiles}', '{$whatsapps}',  {$namber->user_pid_id})";
							$CFG->DB->query($sql);
							$rea_id =  mysql_insert_id();

							$sql = "INSERT INTO {$CFG->DB_Prefix}news (type_company_id, client_id, page_id, cdate, edate, visible, name_company, info, manager_id) VALUES ('10011925', '{$rea_id}', '868', '{$cdate}', '{$cdate}', 1, '{$names}', '{$names}', {$namber->user_pid_id})";
							$CFG->DB->query($sql);
						}
						break;

					}
				}

			}
			//	$array[$i]["time"] > $o->times
		}
		//	for ($i=0; $i<sizeof($array); $i++)

		exit;


	break;

}

exit;
