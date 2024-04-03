<?

class Whatsapp
{
	public function SendContact($data)
	{
		$id = $data['id'];
		$mobile = $data['mobile'];
		$autor = $data['wp'];
		$sql = getSQLRowO("SELECT *  FROM my_tmp_whatsapp_namber WHERE id = '{$autor}' AND visible = 1 ");
		if($sql)
		{
			$res = getSQLRowO("SELECT *  FROM my_face WHERE id = '{$id}' AND visible = 1 ");


			$name = explode(" ", $res->name);
			$name_other = explode(" ", $res->name_other);

			if(isset($name[0])) {$names = $name[0]; } else {$names = '';}
			if(isset($name_other[1])) {$name_others = $name_other[1]; } else {$name_others = '';}

			$nameREAL = $names.' '.$name_others;

			$contacts[0] = array('name' => array('first_name' => $nameREAL, 'formatted_name' => $nameREAL), 'phones' => array( 0 => array('phone' => '+'.preg_replace('/[^0-9]/', '',$res->mobile), 'type' => 'WORK', 'wa_id' => preg_replace('/[^0-9]/', '', $res->whatsapp))) , 'emails' => array( 0 => array('email' => $res->email, 'type' => 'WORK')));
			$data = ['phone' =>'+'.preg_replace('/[^0-9]/', '',$mobile), 'contacts'=> $contacts ];
			$url = 'https://api.1msg.io/'.$sql->wp_id.'/sendContact?token='.$sql->token;
			$jsons = $this->whatsapp_send($data, $url);
			$json_a = json_decode($jsons, true);

			if($json_a['description'] == 'Message has been sent to the provider')
			{
				echo 1;
			}
			else {
				echo 0;
			}
		}
		else
		{
			echo 0;
		}

	}

	public function whatsapp_send($data, $url, $decod = false)
	{
		if($decod === true)
		{
			$json = $data;
		}
		else {
			$json = json_encode($data);
		}

		$options = stream_context_create(['http' => [
		'method'  => 'POST',
		'header'  => 'Content-type: application/json; charset=utf-8',
		'content' => $json]]);

		$result = file_get_contents($url, false, $options);
		return $result;
	}


	public function ping($mobile, $autor)
	{
		$sql = getSQLRowO("SELECT *  FROM my_tmp_whatsapp_namber WHERE namber = '{$autor}' AND visible = 1 ");
		if($sql)
		{
			$mobile = preg_replace('/[^0-9]/', '', $mobile);
			if(strlen($mobile) == 11)
			{
				$phone = '+'.$mobile;
				$data = ['contacts' => $phone];
			 	$url = 'https://api.1msg.io/'.$sql->wp_id.'/contacts?token='.$sql->token;
				$json = $this->whatsapp_send($data, $url);
				$json_a = json_decode($json, true);

				if($json_a['contacts'][0]['status'] == 'processing')
				{
					sleep(1);  //Если запрос в процессе обработки, ждем 1 секунду и снова делаем запрос
					$json = $this->whatsapp_send($data, $url);
					$json_a = json_decode($json, true);
					return $this->OperationPing($json_a);
				}
				else
				{
					return $this->OperationPing($json_a);
				}
			}

		}
	}


	function get_send_crm($post)
	{
		global $CFG;

		$string = preg_replace('~[^0-9]+~','',$post["manager"]);
		$mobile = "+".$string;

		//Если есть картинка
		if($post["img"] != '')
		{
			return $this->SendTemplates($mobile, 'images', $post);
		}//Если есть файл
		elseif($post["file_wp"] != '')
		{
			return $this->SendTemplates($mobile, 'file', $post);
		}//если только текст
		else
		{
			return $this->SendTemplates($mobile, 'text', $post);
		}
	}

	public function SendTemplates($phone, $type, $data)
	{
			global $CFG;
			$cdate = sqlDateNow();
			$string = preg_replace('~[^0-9]+~','',$data["manager"]);
			$mobile = "+".$string;
			$file_img = $data['img'];
			$file_wp = $data['file_wp'];


			$text = str_replace(PHP_EOL, ' ', $data['text']);
			$autor = getSQLRowO("SELECT * FROM my_tmp_whatsapp_namber WHERE id= ".$data['from']." " );


				$templates = getSQLRowO("SELECT * FROM my_whatsapp_system_templates WHERE type = '".$type."' AND phone = '".$autor->namber."' " );
				$phone = preg_replace('~[^0-9]+~','',$phone);
				$url = 'https://api.1msg.io/'.$autor->wp_id.'/sendTemplate?token='.$autor->token;

				switch ($type)
				{
					case 'text':
						$array ='{"template":"'.$templates->template.'","language":{"policy":"deterministic","code":"'.$templates->language.'"},"namespace":"'.$templates->namespace.'","params":[{"type":"body","parameters":[{"type":"text","text": "'.$text.'"}]}],"phone":"'.$phone.'"}';
						$jsons = $this->whatsapp_send($array, $url, true);

						$json_a = json_decode($jsons, true);
					break;

					case 'images':
						$url_img = 'https://'.$_SERVER['SERVER_NAME'].$data['img'];
						$img_base64 = ['body'=> $url_img];
						$jsonimg = $this->whatsapp_send($img_base64, 'https://api.1msg.io/'.$autor->wp_id.'/uploadMedia?token='.$autor->token);
						$json_img_res = json_decode($jsonimg, true);

						$array = '{"template":"'.$templates->template.'","language":{"policy":"deterministic","code":"'.$templates->language.'"},"namespace":"'.$templates->namespace.'","params":[{"type":"header","parameters":[{"type":"image","image": {"id":"'.$json_img_res['mediaId'].'"}}]},{"type":"body","parameters":[{"type":"text","text":"'.$text.'"}]}],"phone":"'.$phone.'"}';
						$jsons = $this->whatsapp_send($array, $url, true);

						$json_a = json_decode($jsons, true);
					break;

					case 'file':
						$file_wp_url = explode(",", $file_wp);
						$res = getSQLRowO(" SELECT * FROM my_attachments WHERE id = '".$file_wp_url[1]."' ");
						$url_file = 'https://'.$_SERVER['SERVER_NAME'].'/'.$res->text;
						$file_base64 = ['body'=> $url_file];
						$jsonfile = $this->whatsapp_send($file_base64, 'https://api.1msg.io/'.$autor->wp_id.'/uploadMedia?token='.$autor->token);
						$json_file_res = json_decode($jsonfile, true);
						$array = '{"template":"'.$templates->template.'","language":{"policy":"deterministic","code":"'.$templates->language.'"},"namespace":"'.$templates->namespace.'","params":[{"type":"header","parameters":[{"type":"document","document": {"id":"'.$json_file_res['mediaId'].'", "filename":"'.$res->original.'.'.$res->type.'"}}]},{"type":"body","parameters":[{"type":"text","text":"'.$text.'"}]}],"phone":"'.$phone.'"}';
						$jsons = $this->whatsapp_send($array, $url, true);

						$json_a = json_decode($jsons, true);
					break;
				}

				if($json_a['description'] == 'Message has been sent to the provider')
				{
					$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from, attachments_image, attachments_file) VALUES ('".$data['Ondate']."', 868, '{$CFG->USER->USER_ID}', '{$text}', 1, 1, '{$cdate}', '{$mobile}', 1, '{$autor->namber}', '".$file_img."', '".$file_wp."')";

					if($CFG->DB->query($sql))
					{
						echo 1;
					}
				}
				else {
					echo 0;
				}

	}


/*
	function get_send_crm($post)
	{
		global $CFG;

		$string = preg_replace('~[^0-9]+~','',$post["manager"]);
		$mobile = "+".$string;
		$id = $post["Ondate"];
		$img = $post["img"];
		$file_wp = $post["file_wp"];
		$cdate = sqlDateNow();

		$namber = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE id= '{$post[from]}' " );

		print_r($namber); exit;


		//Если есть картинка
		if( $img != '')
		{
			$type = pathinfo(rawurldecode($img));	// Получаем расширение файла
			// Генерируем случайное название
			$length = rand(5, 25);
			$randomBytes = openssl_random_pseudo_bytes($length);
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			$charactersLength = strlen($characters);
			$result = '';
			for ($i = 0; $i < $length; $i++)
			{
				$result .= $characters[ord($randomBytes[$i]) % $charactersLength];
			}

			$filename = $result.'.'.$type["extension"];
			// Шифруем в base64

			$images = getImageDataFromUrlImg(rawurldecode($img));

			$data = ['phone' => $mobile, 'body'=> $images, 'filename'=> $filename, 'caption'=> $post["text"]];
			$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/sendFile?token='.$namber->token;

			$this->sendr($data, $url);
		}//Если это файл
		elseif($post["file_wp"] != '')
		{
			$str = substr($post["file_wp"], 1);

			$file_url = getSQLRowO("SELECT text FROM {$CFG->DB_Prefix}attachments WHERE id= '{$str}' " );

			$type = pathinfo(rawurldecode($file_url->text));	// Получаем расширение файла

			// Генерируем случайное название
			$length = rand(5, 25);
			$randomBytes = openssl_random_pseudo_bytes($length);
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			$charactersLength = strlen($characters);
			$result = '';
			for ($i = 0; $i < $length; $i++)
			{
				$result .= $characters[ord($randomBytes[$i]) % $charactersLength];
			}

			$filename = $result.'.'.$type["extension"];
			// Шифруем в base64
			$images = getImageDataFromUrl(rawurldecode($file_url->text));

			$data = ['phone' => $mobile, 'body'=> $images, 'filename'=> $filename, 'caption'=> $post["text"]];
			$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/sendFile?token='.$namber->token;
			$this->sendr($data, $url);
		}//если только текст
		else
		{
			$data = ['phone' => $mobile, 'body'=> $post["text"]];
			$url = 'https://api.chat-api.com/instance'.$namber->wp_id.'/message?token='.$namber->token;;
			$this->sendr($data, $url);
		}

		if($img != '')
		{
			error_reporting(E_ALL);
			$reversed = array_reverse(explode(".", $img));
			$new_file = 'documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/send/'.$id.'/'.time().'.'.$reversed[0];
			$new_dir  = 'documents/whatsapp/comments/'.$CFG->USER->USER_DIRECTOR_ID.'/rss/send/'.$id;

			if (!file_exists($new_file))
			{
				$str = substr($img, 1);

				if (file_exists($new_dir)) {	copy($str, $new_file);	}
				else	{	mkdir($new_dir, 0755, true);	copy($str, $new_file);	}
			}

		}

		$text = $post['text'];
		if($img != ''){ $img_url = '/'.$new_file; }

		$sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from, attachments_image, attachments_file) VALUES ('{$id}', 868, '{$CFG->USER->USER_ID}', '{$text}', 1, 1, '{$cdate}', '{$mobile}', 1, '{$namber->namber}', '{$img_url}', '{$file_wp}')";

		if($CFG->DB->query($sql))
		{
			echo 1;
		}


	}
*/


	public  function sendr($data, $url)
	{
		$json = json_encode($data);

		$options = stream_context_create(['http' => [
		'method'  => 'POST',
		'header'  => 'Content-type: application/json',
		'content' => $json]]);
		$result = file_get_contents($url, false, $options);
		//echo $result; exit;
	}



	function real_tel($tel)
	{
		$string = str_replace(' ','',$tel);
		$str = substr_replace($string, '+7-', 0, 1);
		$one =  substr($str,  0, 6);
		$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

		return $rel;
	}

	function real_tel_2($tel)
	{
		$string = str_replace(' ','',$tel);
		$str = substr_replace($string, '+7-', 0, 2);
		$one =  substr($str,  0, 6);
		$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

		return $rel;
	}

	function real_tel_3($tel)
	{
		global $CFG;

		$tel = preg_replace ("/[^a-zA-ZА-Яа-я0-9\s]/","",$tel);

		$string = str_replace(' ','',$tel);
		$str = substr_replace($string, '+7-', 0, 1);
		$one =  substr($str,  0, 6);
		$rel = $one.'-'.substr($str,  6, 3).'-'.substr($str,  9, 2).'-'.substr($str,  11, 2);

		$namber = getSQLRowO("SELECT whatsapp, name, id FROM {$CFG->DB_Prefix}face WHERE visible='1' AND whatsapp LIKE '%{$rel}%'  " );

		echo '<a href="/person/'.$namber->id.'" target="_blank"  data-toggle="tooltip" data-placement="bottom" title="'.$namber->name.'" data-original-title="'.$namber->name.'" class="mobile_copy">'.$namber->whatsapp.'</a> ';

	}
}
