<?php
error_reporting(E_ERROR | E_PARSE);
require('/var/www/www-root/data/www/signcrm.kz/_properties.php');
// /usr/bin/php /var/www/www-root/data/www/signcrm.kz/modules/whatsapp/newsletter.php +77755475012
// /usr/bin/php /var/www/www-root/data/www/signcrm.kz/modules/whatsapp/newsletter.php +79631817597
// supervisorctl restart php_wp


while (true)
{
  $today = date("l");
  if ($today != "Saturday" && $today != "Sunday")
	{
		// Получаем текущее время
		$current_time = time();

		// Устанавливаем временные границы
		$start_time = strtotime(date('Y-m-d 10:00:00'));
		$end_time = strtotime(date('Y-m-d 18:00:00'));

		// Проверяем, находится ли текущее время внутри временного отрезка
		if ($current_time >= $start_time && $current_time <= $end_time)
		{
					$res = $argv;

					if( isset($res[1]))
					{
						//Смотрим достуный номер для рассылки
						$o = getSQLRowO("SELECT * FROM my_tmp_whatsapp_namber WHERE visible='1' and namber='".$res[1]."'");
						if($o)
						{
							//Если есть рассылка по этому номеру телефона - отправляем)
							$response = getSQLRowO("SELECT * FROM my_tmp_whatsapp WHERE visible='1' AND status = 0 and namber='".$o->id."' order by id ASC ");
							if($response)
							{
								//Кому отправляем.
								$object = getSQLRowO("SELECT * FROM my_tmp_whatsapp_rss WHERE visible='0' and whatsapp_id='".$response->id."' order by id ASC ");
								if(	$object)
								{
									$user = getSQLRowO("SELECT * FROM my_face WHERE id = '".$object->face_id."' " );
									echo PHP_EOL.'Отправляем рассылку для '.$user->name.' '.$user->whatsapp.' в '.date('Y-m-d H:i:s').PHP_EOL;

									$WP = new Whatsapp();

									$str_replace_whatsapp = str_replace("+7-", '', $user->whatsapp);
									$preg_replace_user_whatsapp = preg_replace('/\D/', '', $str_replace_whatsapp);

									$url_img = 'https://signcrm.kz/'.rawurlencode($response->img);
									$img_base64 = ['body'=> $url_img];

									$jsonimg = $WP->whatsapp_send($img_base64, 'https://api.1msg.io/'.$o->wp_id.'/uploadMedia?token='.$o->token);
									$json_img_res = json_decode($jsonimg, true);

									echo 'Создали файл: '.$json_img_res['mediaId'].PHP_EOL;
									if($json_img_res['mediaId'])
									{
										$templates = getSQLRowO("SELECT * FROM my_whatsapp_system_templates WHERE type = 'rss' AND phone = '".$o->namber."' ORDER BY RAND() LIMIT 2" );
										$url = 'https://api.1msg.io/'.$o->wp_id.'/sendTemplate?token='.$o->token;

										echo 'Заходим в условия отправки шаблона: '.$templates->template.PHP_EOL;

										$text = str_replace(array("\r\n", "\r", "\n"), " ", $user->name.'. '.$response->text);

										$array = '{"template":"'.$templates->template.'","language":{"policy":"deterministic","code":"'.$templates->language.'"},"namespace":"'.$templates->namespace.'","params":[{"type":"header","parameters":[{"type":"image","image": {"id":"'.$json_img_res['mediaId'].'"}}]},{"type":"body","parameters":[{"type":"text","text":"'.$text.'"}]}],"phone":"'.$preg_replace_user_whatsapp.'"}';


										$jsons = $WP->whatsapp_send($array, $url, true);
										$json_a = json_decode($jsons, true);

										if($json_a['sent'] == 1 && $json_a['sent'] == 'Message has been sent to the provider' )
										{
											$CFG->DB->query("UPDATE my_tmp_whatsapp_rss SET visible = 1, send_time = '".time()."' WHERE id='{$object->id}' ");
											echo 'Рассылка отправлена для '.$user->name.' '.$user->whatsapp.' в '.date('Y-m-d H:i:s').PHP_EOL.PHP_EOL;

											if($object->page_id == 1012) //это лица
											{
												$page_id = $user->id;
												$parent_id = $object->page_id;
											}
											else {
												$page_id = $user->id;
												$parent_id = 1012;
											}

											$cdate = sqlDateNow();

											$sql = "INSERT INTO my_comments (page_id, parent_id, user_id, text, whatsapp, visible, cdate, whatsapp_namber, whatsapp_sender, whatsapp_from, attachments_image) VALUES ('{$page_id}', '{$parent_id}', '561', '{$text}', 1, 1, '{$cdate}', '{$user->whatsapp}', 1, '{$o->namber}', '{$response->img}')";
											$CFG->DB->query($sql);
										}
										else
										{
											$CFG->DB->query("DELETE FROM my_tmp_whatsapp_rss WHERE id='{$object->id}' ");
											$CFG->DB->query("UPDATE my_face SET whatsapp = NULL WHERE id='{$user->id}' ");
											echo 'Удаляем из рассылки: '.$user->whatsapp.PHP_EOL;
											echo 'Причина: '.$json_a['error']['message'].PHP_EOL.PHP_EOL;
										}


									}

								}
								else {
									//Если некому отправлять то необходимо закрыть зассылку
										$CFG->DB->query("UPDATE my_tmp_whatsapp SET status = 1 WHERE id='{$response->id}' ");
										echo 'Закрываем рассылку: '.$response->id.PHP_EOL.PHP_EOL;
								}
							}
							else {
								//если нет рассылки в очереди
								//echo 'Нет рассылок для отправки!'.PHP_EOL.PHP_EOL;
							}

						}

					} // if $argv;

		} //if ($current_time >= $start_time && $current_time <= $end_time)

	}  //if (date('w')


	$sleep =  rand(300, 350);
	sleep($sleep);
}
