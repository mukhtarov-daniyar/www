<?php
	$data = stripslashesarray($_POST);

	/* CODE */
	switch($data['user_act'])
	{

	   case 'update_profile' :

			if($data['name'] && $CFG->USER->USER_ID)
			{

				$CFG->FORM->setExeptions(array("passwd","newpass", "povtorpass"));

				if($CFG->FORM->setForm($data))
				{
					$CFG->USER->updateUserDataArray($data);

					// echo '<pre>'.print_r($CFG->DB, true).'</pre>'; exit;

					$date = sqlDateNow();
					$date_time = time();

					$fraza  = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}company WHERE id='{$CFG->USER->USER_DIRECTOR_ID}'");

					//Если не указанно лицо то создаем новое
					if($data['chl_id'] == 0)
					{
						$name = explode(" ", $data['name']);
						$name_other = $name[0].' '.$name[1];

						if($strtotime == false) { $strtotime = 0; } else { $strtotime = strtotime ($data[dob]); 	}
						if($data['city'] == NULL) { $city = 0; } else { $city = $data['city']; 	}

						$sql = "INSERT INTO my_face (page_id, manager_id, cdate, edate, bcdate, name, name_other, email, img, floor, city_id, mobile, whatsapp, info, visible) VALUES ('1012', '{$data[id]}', '{$date_time}', '{$date_time}', '{$strtotime}', '{$name[0]}',  '{$name_other}', '{$data['email']}', '{$data['avatar']}', '{$data['pauls']}', '{$city}', '{$data['mobile']}', '{$data['mobile']}', '{$fraza->fraza}', 1)";
						$CFG->DB->query($sql);

						$taks_id = $CFG->DB->lastId();;

						$res = "UPDATE {$CFG->DB_Prefix}users SET chl_id='{$taks_id}'  WHERE id={$data[id]}";
						$CFG->DB->query($res);
					}

					//Если не указанна служебная запись "Примирование" то создаем новое
					if($data["taks_id"] == 0)
					{
						if($data['city'] == NULL) { $city = 0; } else { $city = $data['city']; 	}

						$sql = "INSERT INTO {$CFG->DB_Prefix}news (logo_company, logo_company_mini, cdate, edate, page_id, type_company_id, manager_id, city_id, name_company, visible) VALUES ('{$data['avatar']}', '{$data['avatar']}', '{$date}', '{$date}', 976, 10011938, {$data[id]}, '{$city}', '{$data['name']}, Премирование', 1)";
						$CFG->DB->query($sql);

						$taks_id = $CFG->DB->lastId();

						$res = "UPDATE {$CFG->DB_Prefix}users SET taks_id='{$taks_id}'  WHERE id={$data[id]}";
						$CFG->DB->query($res);
					}

					//Если не указанна служебная запись "Основные средства" то создаем новое
					if($data["os_id"] == 0)
					{

						if($data['city'] == NULL) { $city = 0; } else { $city = $data['city']; 	}

						$sql = "INSERT INTO {$CFG->DB_Prefix}news (logo_company, logo_company_mini,cdate, edate, page_id, type_company_id, manager_id, city_id, name_company, visible) VALUES ('{$data['avatar']}', '{$data['avatar']}', '{$date}', '{$date}', 976, 10011926, {$data[id]}, '{$city}', '{$data['name']}, Основные средства',  1)";
						$CFG->DB->query($sql);

						$os_id = $CFG->DB->lastId();

						$res = "UPDATE {$CFG->DB_Prefix}users SET os_id='{$os_id}'  WHERE id={$data[id]}";
						$CFG->DB->query($res);
					}

					//Если не указанна служебная запись "Обязанности и работа" то создаем новое
					if($data["official_id"] == 0)
					{
						if($data['city'] == NULL) { $city = 0; } else { $city = $data['city']; 	}

						$sql = "INSERT INTO {$CFG->DB_Prefix}news (logo_company, logo_company_mini,cdate, edate, page_id, type_company_id, manager_id, city_id, name_company, visible) VALUES ('{$data['avatar']}', '{$data['avatar']}', '{$date}', '{$date}', 976, 10011927, {$data[id]}, '{$city}', '{$data['name']},  Обязанности и работа', 1)";
						$CFG->DB->query($sql);

						$official_id = $CFG->DB->lastId();

						$res = "UPDATE {$CFG->DB_Prefix}users SET official_id='{$official_id}'  WHERE id={$data[id]}";
						$CFG->DB->query($res);
					}

					$CFG->STATUS->OK = true;
					//$CFG->STATUS->MESSAGE = $CFG->Locale["ob_data"];
					redirect('/profile/view/'.$data[id]);

				}
			}

		   redirect($_SERVER["HTTP_REFERER"]);

	   break;

	   case 'upload_logo_company' :

				if( $CFG->USER->checkExtFile($_FILES['avatar']) == 'image' )
				{
					$big = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'default');
					$med = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'defaultAvatar');

					$big = trim($big, '/'); $big = '/' . $big;
					$med = trim($med, '/'); $med = '/' . $med;

					$CFG->USER->updateLogoField($big);

					$response = json_encode(array('big' => $big,'med' => $med,));

					echo $response; exit;
				}
				else
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
					redirect($_SERVER["HTTP_REFERER"]);
				}

	   break;

	   case 'upload_avatar' :

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

	   break;

	   case 'add_vacancy' :

			if($CFG->USER->USER_ID)
			{

				if($CFG->FORM->setForm($data))
				{
					 if($CFG->USER->updateNewsDataArray($_POST))
					 {
						$sql  = "SELECT id FROM {$CFG->DB_Prefix}news WHERE manager_id='{$CFG->USER->USER_ID}' ORDER BY id DESC";
						$res = getSQLArrayO($sql);
						redirect('/record/'.$res[0]->id);
					 }
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}


		   redirect($_SERVER["HTTP_REFERER"]);

	   break;

	   case 'edit_vacancy' :

			  if(!isset($_POST["type_company"]))
			  {
					$_POST['type_company'] = '';
			  }

			if($CFG->FORM->setForm($data))
			{
				 if($CFG->USER->indateNewsDataArray($CFG->_GET_PARAMS[1], $_POST))
				 {

					$edate =  sqlDateNow();
					$query  = "UPDATE {$CFG->DB_Prefix}news SET edate='{$edate}' WHERE id='{$CFG->_GET_PARAMS[1]}'";
					$CFG->DB->query($query);


					$CFG->FORM->CLEARSTATUS();
					$CFG->STATUS->OK = true;

					redirect('/record/'.$CFG->_GET_PARAMS[1].'/');
				 }
			}


		   redirect($_SERVER["HTTP_REFERER"]);

	   break;

	   case 'add_person' :

			if($CFG->USER->USER_ID)
			{
				if($CFG->FORM->setForm($data))
				{
					 if($CFG->USER->updateFaceDataArray($_POST))
					 {
						$sql  = "SELECT id FROM {$CFG->DB_Prefix}face WHERE manager_id='{$CFG->USER->USER_ID}' ORDER BY id DESC";
						$res = getSQLRowO($sql);

						redirect('/person/'.$res->id);
					 }
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}


		   redirect($_SERVER["HTTP_REFERER"]);

	   break;



	   case 'edit_person' :

				if(!isset($_POST["marketing_id"]))
				{
					$_POST['marketing_id'] = '';
				}

    	 if($CFG->USER->indateFaceDataArray($CFG->_GET_PARAMS[1], $_POST))
    	 {
    		$edate =  time();
    		$query  = "UPDATE {$CFG->DB_Prefix}face SET edate='{$edate}' WHERE id='{$CFG->_GET_PARAMS[1]}'";
    		$CFG->DB->query($query);

    		$CFG->FORM->CLEARSTATUS();
    		$CFG->STATUS->OK = true;

    		redirect('/person/'.$CFG->_GET_PARAMS[1].'/');
    	 }

			 redirect($_SERVER["HTTP_REFERER"]);

	   break;


	   case 'company' :

			if($CFG->FORM->setForm($data))
			{
				if($CFG->USER->updateCompanyDataArray($data) == false)
				{
					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'Что то пошло не так :(';
				}
			}

		   redirect($_SERVER["HTTP_REFERER"]);
	   break;

	   case 'add_office' :

			if($CFG->USER->USER_ID)
			{
				if($CFG->FORM->setForm($data))
				{
					 if($CFG->USER->updateOfficeDataArray($_POST))
					 {

						$sql  = "SELECT id FROM {$CFG->DB_Prefix}news WHERE manager_id='{$CFG->USER->USER_ID}' ORDER BY id DESC";
						$res = getSQLArrayO($sql);

						redirect('/office/'.$res[0]->id);
					 }
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}


		   redirect($_SERVER["HTTP_REFERER"]);

	   break;

		case 'edit_office' :

			if($CFG->FORM->setForm($data))
			{

				 if($CFG->USER->indateOfficeDataArray($CFG->_GET_PARAMS[1], $_POST))
				 {

					$edate =  sqlDateNow();
					$query  = "UPDATE {$CFG->DB_Prefix}news SET edate='{$edate}' WHERE id='{$CFG->_GET_PARAMS[1]}'";
					$CFG->DB->query($query);


					$CFG->FORM->CLEARSTATUS();
					$CFG->STATUS->OK = true;

					$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}' ");


					if($o->page_id == 868)
					{
						redirect('/record/'.$CFG->_GET_PARAMS[1].'/');
					}
					else
					{
						redirect('/office/'.$CFG->_GET_PARAMS[1].'/');
					}


				 }
			}

		   redirect($_SERVER["HTTP_REFERER"]);

		break;

	   /* Добавить запись, личный дневник */
  	   case 'add_alimzhanov' :

			if($CFG->USER->USER_ID)
			{
				if($CFG->FORM->setForm($data))
				{
					 if($CFG->USER->updateNewsDataArray($_POST))
					 {
						$sql  = "SELECT id FROM {$CFG->DB_Prefix}news WHERE manager_id='{$CFG->USER->USER_ID}' ORDER BY id DESC";
						$res = getSQLArrayO($sql);

						redirect('/alimzhanov-history/'.$res[0]->id);
					 }
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}


		   redirect($_SERVER["HTTP_REFERER"]);

	   break;

		case 'edit_alimzhanov' :

			if($CFG->FORM->setForm($data))
			{

				 if($CFG->USER->indateNewsDataArray($CFG->_GET_PARAMS[1], $_POST))
				 {

					$edate =  sqlDateNow();
					$query  = "UPDATE {$CFG->DB_Prefix}news SET edate='{$edate}' WHERE id='{$CFG->_GET_PARAMS[1]}'";
					$CFG->DB->query($query);


					$CFG->FORM->CLEARSTATUS();
					$CFG->STATUS->OK = true;

					redirect('/alimzhanov-history/'.$CFG->_GET_PARAMS[1].'/');
				 }
			}

		   redirect($_SERVER["HTTP_REFERER"]);

		break;


	}



	/* INTERFACE */
	switch($CFG->_GET_PARAMS[0])
	{
	   /* Добавить запись, личный дневник */
		case 'add_alimzhanov' :

			if($CFG->USER->USER_ID)
			{
				if(is_numeric($CFG->_GET_PARAMS[1]) > 0 )
				{
				 	$sql  = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}'";
					$res = getSQLRowO($sql);

					$CFG->FORM->setForm($res);

					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();

					include 'tpl/templates/employer/alimzhanov/add.tpl';
				}
				else
				{

					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();

					include 'tpl/templates/employer/alimzhanov/add.tpl';
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;

		case 'edit_alimzhanov' :

				$sql  = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}'";
				$res = getSQLRowO($sql);

				$resp = SelectDataRowOArray('users', $res->manager_id, 0);

				if(count($res) == 1)
				{

					if(($res->manager_id == $CFG->USER->USER_ID) || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 143  || $CFG->USER->USER_ID == 219 || ($CFG->USER->USER_DIRECTOR_ID == $resp->user_id))
					{
						$CFG->FORM->setForm($res);

						$e = $CFG->FORM->getFailInputs();
						$data = $CFG->FORM->getFullForm();

						include 'tpl/templates/employer/alimzhanov/edit.tpl';
					}
					else
					{
						redirect('/alimzhanov-history/');
					}
				}
				else
				{
					redirect('/');
				}
		break;
	   /* Добавить запись, личный дневник */



		case 'add_office' :

			if($CFG->USER->USER_ID)
			{
				if(is_numeric($CFG->_GET_PARAMS[1]) > 0 )
				{
				 	$sql  = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}'";
					$res = getSQLRowO($sql);


					$CFG->FORM->setForm($res);

					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();



					include 'tpl/templates/employer/office/add.tpl';
				}
				else
				{

					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();

					include 'tpl/templates/employer/office/add.tpl';
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;

		case 'edit_office' :

				$sql  = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}'";
				$res = getSQLRowO($sql);

				$resp = SelectDataRowOArray('users', $res->manager_id, 0);

				if(count($res) == 1)
				{

					if(($res->manager_id == $CFG->USER->USER_ID) || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 143  || $CFG->USER->USER_ID == 219 || ($CFG->USER->USER_DIRECTOR_ID == $resp->user_id))
					{
						$CFG->FORM->setForm($res);

						$e = $CFG->FORM->getFailInputs();
						$data = $CFG->FORM->getFullForm();

						include 'tpl/templates/employer/office/edit.tpl';
					}
					else
					{
						redirect('/office/');
					}
				}
				else
				{
					redirect('/');
				}
		break;


		case 'edit_person' :

			$sql  = "SELECT * FROM {$CFG->DB_Prefix}face WHERE id='{$CFG->_GET_PARAMS[1]}'";
			$res = getSQLRowO($sql);

			$CFG->FORM->setForm($res);

			$e = $CFG->FORM->getFailInputs();
			$data = $CFG->FORM->getFullForm();

			include 'tpl/templates/employer/person/edit_person.tpl';
		break;


		case 'edit_vacancy' :

				$sql  = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}'";
				$res = getSQLRowO($sql);

				$resp = SelectDataRowOArray('users', $res->manager_id, 0);

				if(count($res) == 1)
				{
					$CFG->FORM->setForm($res);

					$e = $CFG->FORM->getFailInputs();
					$data = $CFG->FORM->getFullForm();

					include 'tpl/templates/employer/edit_vacancy.tpl';
				}
				else
				{
					redirect('/');
				}
		break;


		case 'edit' :

			if($CFG->USER->USER_ID && (int)$CFG->_GET_PARAMS[1] > 0)
			{
				$data = $CFG->USER->getUserInfo($CFG->_GET_PARAMS[1]);

				$post = $_POST;

				if( !empty($data) && ($CFG->USER->USER_ID == $CFG->_GET_PARAMS[1]) || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_BOSS == 1)
				{
					include 'tpl/templates/profile/edit.tpl';
				}
				else
				{
					redirect('/');
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;

		case 'add_person' :

			if($CFG->USER->USER_ID)
			{
				if(is_numeric($CFG->_GET_PARAMS[1]) > 0 )
				{
				 	$sql  = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}'";
					$res = getSQLRowO($sql);


					$CFG->FORM->setForm($res);

					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();



					include 'tpl/templates/employer/person/add_person.tpl';
				}
				else
				{
					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();

					$CFG->FORM->CLEARSTATUS();

					include 'tpl/templates/employer/person/add_person.tpl';
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;


		case 'add_vacancy' :

			if($CFG->USER->USER_ID)
			{
				if(is_numeric($CFG->_GET_PARAMS[1]) > 0 )
				{
				 	$sql  = "SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[1]}'";
					$res = getSQLRowO($sql);


					$CFG->FORM->setForm($res);

					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();

					include 'tpl/templates/employer/add_vacancy.tpl';
				}
				else
				{
					$e = $CFG->FORM->getFailInputs();
    				$data = $CFG->FORM->getFullForm();

					include 'tpl/templates/employer/add_vacancy.tpl';
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;




		case 'company' :

			if($CFG->USER->USER_ID && ($CFG->USER->USER_BOSS == 1) || ($CFG->USER->USER_ID == 1) || ($CFG->USER->USER_ID == 536) || ($CFG->USER->USER_ID == 339)
			|| ($CFG->USER->USER_ID == 133) || ($CFG->USER->USER_ID == 912))
			{
				if($CFG->_GET_PARAMS[0] == 'company')
				{
					if($CFG->USER->USER_BOSS == 0){ $id_company = $CFG->USER->USER_DIRECTOR_ID;} else {$id_company = $CFG->_GET_PARAMS[1]; }

					$sql  = "SELECT * FROM {$CFG->DB_Prefix}company WHERE id='{$id_company}'";
					$res = getSQLRowO($sql);

					if(count($res) == 0)
					{
						include 'tpl/templates/company/add.tpl';

					}

					elseif($CFG->_GET_PARAMS[2] == 'edit')
					{
						$CFG->FORM->setForm($res);

						$e = $CFG->FORM->getFailInputs();
						$data = $CFG->FORM->getFullForm();

						include 'tpl/templates/company/add.tpl';
					}

					elseif($CFG->_GET_PARAMS[2] == 'money')
					{
						$CFG->FORM->setForm($res);

						$e = $CFG->FORM->getFailInputs();
						$data = $CFG->FORM->getFullForm();

						include 'tpl/templates/company/accounting.tpl';
					}

					elseif($CFG->_GET_PARAMS[2] == 'accounting_add_type')
					{
						$CFG->FORM->setForm($res);

						$e = $CFG->FORM->getFailInputs();
						$data = $CFG->FORM->getFullForm();

						include 'tpl/templates/company/accounting_add_type.tpl';
					}

					elseif($CFG->_GET_PARAMS[2] == 'manager')
					{

						if($CFG->_GET_PARAMS[3] == 'visible')
						{
							$sql = "UPDATE {$CFG->DB_Prefix}users SET visible = {$CFG->_GET_PARAMS[4]} WHERE id='{$CFG->_GET_PARAMS[5]}'";
								$CFG->DB->query($sql);
							redirect($_SERVER['HTTP_REFERER']);

						}

						if($CFG->_GET_PARAMS[3] == 'add')
						{
							$data = stripslashesarray($CFG->_POST_PARAMS);

								if($data)
								{
									if ($CFG->FORM->setForm($data) == 1)
									{
										if($CFG->USER->getUserInfoBy('mobile', $_POST['mobile'], 'COUNT(id)') > 0 )
										{
											$CFG->STATUS->ERROR = true;
											$CFG->STATUS->MESSAGE = "Пользователь с таким телефоном уже существует!";
										}
										else
										{
											$NEWS = new News();

											$NEWS->uP($data);

											$users = SelectDataParent("users", "mobile", $data["mobile"]);

											$CFG->FORM->CLEARSTATUS();
											$CFG->STATUS->ERROR = true;
											$CFG->STATUS->MESSAGE = "Новый менеджер добавлен!";
										}
									}
									else
									{
										$CFG->STATUS->ERROR = true;
										$CFG->STATUS->MESSAGE = 'Введите правильно все поля!';
									}

								}
							include 'tpl/templates/company/add_manager.tpl';

						}
						else
						{
							include 'tpl/templates/company/list_manager.tpl';
						}
					}
					elseif($CFG->_GET_PARAMS[2] == 'statistics')
					{
						include 'tpl/templates/company/statistics.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'cashback_list')
					{
						include 'tpl/templates/company/cashback_list.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'otrosl_list')
					{
						include 'tpl/templates/company/otrosl_list.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'marketing_list')
					{
						include 'tpl/templates/company/marketing_list.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'money_list')
					{
						include 'tpl/templates/company/money_list.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'type_company_portrait')
					{
						include 'tpl/templates/company/type_company_portrait.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'type_company')
					{
						include 'tpl/templates/company/type_company.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'town')
					{
						include 'tpl/templates/company/town.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'faq')
					{
						include 'tpl/templates/company/faq.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'cashback')
					{
						include 'tpl/templates/company/cashback.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'help')
					{
						include 'tpl/templates/company/help.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'sticker')
					{
						include 'tpl/templates/company/sticker.tpl';
					}
					elseif($CFG->_GET_PARAMS[2] == 'other')
					{
						if($CFG->_GET_PARAMS[3] == "xls")
						{
							$objPHPExcel = new PHPExcel();

							$objPHPExcel->getProperties()->setCreator("forSign Kazakhstan");
							$objPHPExcel->getProperties()->setLastModifiedBy("forSign Kazakhstan");
							$objPHPExcel->getProperties()->setTitle("XLSX Document");
							$objPHPExcel->getProperties()->setSubject("Export XLSX Document");


							$response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE user_id='{$CFG->USER->USER_ID}' ");

							for($y=0;$y<sizeof($response);$y++)
							{
								$idS .= $response[$y]->id.',';
							}
							$test = trim($idS, ",");
							$str .= " AND manager_id IN ({$test}) ";

							setCookie('order', 'ORDER BY cdate DESC', time() + 3600*360, "/");
							setCookie('ordername', 'Дата создания', time() + 3600*360, "/");

							$NEWS = new News();
							$respons = $NEWS->getList(868,'', '', $str);


							if(count($respons) > 0)
							{

								$objPHPExcel->setActiveSheetIndex(0);


								$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFill()
									->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
									->getStartColor()->setARGB('BD2149');

								$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()
									->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

								$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()
									->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

								$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()
									->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

								$objPHPExcel->getActiveSheet()->getStyle('C:O')->getAlignment()
									->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


								$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()
									->setWrapText(true);

								$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

								$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
								$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
								$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
								$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(45);
								$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
								$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
								$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
								$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
								$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
								$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
								$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

								$objPHPExcel->getActiveSheet()->SetCellValue('A1', coder("№ Записи"));
									$objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('B1', coder("Название компании"));
									$objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('C1', coder("Тип компании"));
									$objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('D1', coder("Почтовый ящик"));
									$objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('E1', coder("Дополнительный почтовый ящик"));
									$objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
									$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setWrapText(true);
								$objPHPExcel->getActiveSheet()->SetCellValue('F1', coder("Город"));
									$objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('G1', coder("Заметка"));
									$objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('H1', coder("ФИО директора"));
									$objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('I1', coder("Мобильный директора"));
									$objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('K1', coder("E-mail директора"));
									$objPHPExcel->getActiveSheet()->getStyle('K1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('L1', coder("ФИО контактирующего"));
									$objPHPExcel->getActiveSheet()->getStyle('L1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('M1', coder("Мобильный контактирующего"));
									$objPHPExcel->getActiveSheet()->getStyle('M1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('N1', coder("E-mail контактирующего"));
									$objPHPExcel->getActiveSheet()->getStyle('N1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
								$objPHPExcel->getActiveSheet()->SetCellValue('O1', coder("Дата создания записи"));

								set_time_limit(600);

									for($z=0;$z<sizeof($respons); $z++)
									{

										$array = explode(",", $respons[$z]->type_company_id);

										for ($y=0; $y<sizeof($array); $y++)
										{
											$h = $array[$y];
											$strS .= SelectData('type_company', $h).', ';
										}

										$type_company = trim($strS, ', ');

										$x = $z+2;




										$res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE page_id='{$respons[$z]->id}' ");

										for ($t=0; $t<sizeof($res); $t++)
										{

											$tt.= $res[$t]->text.'

											';
										}

										$objPHPExcel->getActiveSheet()->SetCellValue("A$x", '#'.$respons[$z]->id);
										$objPHPExcel->getActiveSheet()->SetCellValue('B'.$x, coder($respons[$z]->name_company));
											$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
											$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getFill()->getStartColor()->setARGB('EBEBEB');
											$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
										$objPHPExcel->getActiveSheet()->SetCellValue('C'.$x, $type_company);
										$objPHPExcel->getActiveSheet()->SetCellValue('D'.$x, coder($respons[$z]->email));
										$objPHPExcel->getActiveSheet()->SetCellValue('E'.$x, coder($respons[$z]->other_email));
										$objPHPExcel->getActiveSheet()->SetCellValue('F'.$x, SelectData('city', $respons[$z]->city_id));
										$objPHPExcel->getActiveSheet()->SetCellValue('G'.$x, coder(strip_tags($respons[$z]->info.''.$tt)));
											$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
											$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getFill()->getStartColor()->setARGB('EBEBEB');
											$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
										$objPHPExcel->getActiveSheet()->SetCellValue('H'.$x, coder($respons[$z]->name_director));
										$objPHPExcel->getActiveSheet()->SetCellValue('I'.$x, coder($respons[$z]->name_director_mobile));
										$objPHPExcel->getActiveSheet()->SetCellValue('K'.$x, coder($respons[$z]->name_director_email));
										$objPHPExcel->getActiveSheet()->SetCellValue('L'.$x, coder($respons[$z]->name_client));
										$objPHPExcel->getActiveSheet()->SetCellValue('M'.$x, coder($respons[$z]->name_client_mobile));
										$objPHPExcel->getActiveSheet()->SetCellValue('N'.$x, coder($respons[$z]->name_client_email));
										$objPHPExcel->getActiveSheet()->SetCellValue('O'.$x, coder(dateSQL2TEXT($respons[$z]->cdate, "DD MN YYYY, hh:mm")));





										$strS = "";

									}

								$objPHPExcel->getActiveSheet()->setTitle(coder("Записи компании"));

								header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
								header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
								header ( "Cache-Control: no-cache, must-revalidate" );
								header ( "Pragma: no-cache" );
								header('Content-disposition: attachment; filename=export.xlsx');
								header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
								readfile('export.xlsx');

								$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
								$objWriter->save('php://output');

								$objPHPExcel->disconnectWorksheets();
								unset($objPHPExcel);

								exit;
						}
					}
					else
					{
						include 'tpl/templates/company/other.tpl';
					}
					}
					elseif(count($res) == 1 && (count($res) > 0))
					{
						$CFG->FORM->setForm($res);

						$e = $CFG->FORM->getFailInputs();
						$data = $CFG->FORM->getFullForm();

						include 'tpl/templates/company/view.tpl';
					}

				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}


		break;

		case 'view' :

			if(is_numeric($CFG->_GET_PARAMS[1]) && $CFG->_GET_PARAMS[1] > 0)
				{
					$data = $CFG->USER->getUserInfo($CFG->_GET_PARAMS[1]);

					include 'tpl/templates/profile/view.tpl';
				}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;

		case 'settings' :

			if(is_numeric($CFG->_GET_PARAMS[1]) && $CFG->_GET_PARAMS[1] > 0)
				{
					$data = $CFG->USER->getUserInfo($CFG->_GET_PARAMS[1]);

					include 'tpl/templates/profile/settings.tpl';
				}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;


		case 'edit' :

			if($CFG->USER->USER_ID && (int)$CFG->_GET_PARAMS[1] > 0)
			{
				$data = $CFG->USER->getUserInfo($CFG->_GET_PARAMS[1]);

				$post = $_POST;

				if( !empty($data) && ($CFG->USER->USER_ID == $CFG->_GET_PARAMS[1]) )
				{
					include 'tpl/templates/profile/edit.tpl';
				}
				else
				{

					redirect('/');
				}
			}
			else
			{
				redirect($CFG->AUTHPAGE);
			}

		break;

		default :

			if(is_numeric($CFG->_GET_PARAMS[0]))
			{
				$user = $CFG->USER->getUserInfo($CFG->_GET_PARAMS[0]);

				if(sizeof($user) > 0)
				{
					include 'tpl/templates/profile/userprofile.tpl';
				}
				else
				{
					include ERRORREQUEST;
				}
			}
			else
			{
				if($CFG->USER->USER_ID)
				{
					$user = $CFG->USER->getUserInfo($CFG->USER->USER_ID);

					redirect('/ru/profile/edit');
				}
				else
				{
					redirect($CFG->AUTHPAGE);
				}
			}
		break;

	}
