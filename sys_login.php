<?

	$data = stripslashesarray($CFG->_POST_PARAMS);

	 /* Logic */
    switch($CFG->_GET_PARAMS[0])
	{
		case 'logout' :

			$CFG->USER->logout();

			redirect($CFG->MAINPAGE);

		break;

		case 'party' :

			if($CFG->FORM->setForm($data))
			{
				$CFG->USER->updateArray('party',$data);
				$CFG->FORM->CLEARSTATUS();

				$CFG->STATUS->ERROR = true;
				$CFG->STATUS->MESSAGE = $CFG->Locale["party"];

			}

			redirect($_SERVER["HTTP_REFERER"]);

		break;

		default :

			if($CFG->USER->USER_ID)
			{
				redirect($CFG->USERPAGE);
			}

			if($data['token'])
			{
				$CFG->USER->enterSocial($data['token']);
			}

		break;
	}

	/*  POST ACTION  */
	switch($_POST['user_act'])
	{
		case 'forgot_user_password' :

			if($CFG->FORM->setForm($_POST))
			{

				$response = null;
				$reCaptcha = new ReCaptcha($CFG->ReCaptcha_private);
				if ($_POST["g-recaptcha-response"])
				{
					if ($_POST["g-recaptcha-response"])
					{
						$response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"],	$_POST["g-recaptcha-response"]);
					}
					if ($response != null && $response->success && $data["tel"] !='')
					{

						if($CFG->USER->checkIssetData('mobile', $data["tel"]))
						{
							$info = $CFG->USER->getUserInfoBy("mobile", $data["tel"], "id, name, mobile");
							$userinfo = $CFG->USER->getUserInfo($info);
							$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
							$max=9;
							$size=StrLen($chars)-1;
							$password=null;
							 while($max--)
							$password.=$chars[rand(0,$size)];

							$password_hash = password_hash($password, PASSWORD_DEFAULT);


							$sql  = "UPDATE {$CFG->DB_Prefix}users SET pass='{$password_hash}' WHERE id='{$userinfo['id']}' AND visible=1";
							$CFG->DB->query($sql);
							$text = 'Ваш новый пароль от CRM '.PHP_EOL.$password;
							send_sms($userinfo['mobile'], $text);

							$CFG->STATUS->OK = true;
							$CFG->STATUS->MESSAGE = "Новый пароль отправлен на мобильный телефон!";

							redirect($_SERVER["HTTP_REFERER"]);
						}
						else
						{
						   $CFG->STATUS->ERROR = true;
						   $CFG->STATUS->MESSAGE = 'Такой пользователь в системе не найден!';

						   redirect($CFG->oPageInfo->_xcode . 'forgot/step-1');
						}
					}
					else
					{
							$CFG->STATUS->ERROR = true;
							$CFG->STATUS->MESSAGE = 'Введите номер телефона!';
							redirect($_SERVER['REQUEST_URI']);
					}

				}
				else
				{
					 $CFG->STATUS->ERROR = true;
					 $CFG->STATUS->MESSAGE = 'Укажите галочку что вы не робот!';
					 redirect($_SERVER['REQUEST_URI']);
				}

			}

		break;

		case 'enter_user' :

			if($CFG->FORM->setForm($data))
			{
				/*
				$response = null;
				$reCaptcha = new ReCaptcha($CFG->ReCaptcha_private);
				if ($_POST["g-recaptcha-response"])
				{
					$response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"],	$_POST["g-recaptcha-response"]);
				}
				else
				{
					 $CFG->STATUS->ERROR = true;
					 $CFG->STATUS->MESSAGE = 'Укажите галочку что вы не робот!';
					 redirect($_SERVER['REQUEST_URI']);
				}
				*/
				//if ($response != null && $response->success && $data["mobile"] != '' && $data["passwd"] != '')


				if ($data["mobile"] != '' && $data["passwd"] != '')
				{
					$st = $CFG->USER->getStatus($data["mobile"], $data["passwd"]);

					if($st->id > 0)
					{

						if($CFG->USER->login($data["mobile"], $data["passwd"], $st))
						{
							setCookie("site_content", "ru", time() + 3600*360, "/");
							$z = getSQLRowO("SELECT home_url FROM {$CFG->DB_Prefix}users WHERE id='{$CFG->USER->USER_ID}'");

							redirect(htmlspecialchars_decode($z->home_url));
						}
						else
						{
							$CFG->STATUS->ERROR = true;
							$CFG->STATUS->MESSAGE = 'Неверно указан пароль :(';
							redirect($_SERVER['REQUEST_URI']);
						}
					}
					else
					{
						 $CFG->STATUS->ERROR = true;
						 $CFG->STATUS->MESSAGE = 'Пользователь с таким номером телефона не обнаружен :(';
						 redirect($_SERVER['REQUEST_URI']);
					}
				}
				else
				{
					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'Введите номер телефона и пароль!';
					redirect($_SERVER['REQUEST_URI']);
				}



			}



		break;

	}

	/* TEMPLATE PART */
	/*****************/
	if(!$CFG->fromIndex) redirect($CFG->MAINPAGE);

	/* Interface VIEW */
    switch($CFG->_GET_PARAMS[0])
	{
		case 'forgot' :

			switch($CFG->_GET_PARAMS[1])
			{

				default :

					$_SESSION['xnum'] = "".rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);

					include('tpl/templates/system/forgot_step-1.tpl');

					$CFG->oPageInfo->html_title = $CFG->Locale["site-local7"];

				break;
			}

		break;


		default :

			include 'tpl/templates/system/enter.tpl';

		break;

	}
?>
