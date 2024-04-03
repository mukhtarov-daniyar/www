<?

class Init {

	var $SINGLEPAGE;

	var $header = "_header.php";
	var $footer = "_footer.php";

	private $disableTemplate = "_disable.php";

	private $multiLang;

	private $mobilePath;
	private $mobilePhone;
	private $modulePath = 'modules/';

	private $title;
	private $description;
	private $keywords;
	private $content = "";

	private $lang;
	private $oPageInfo;

	private $file;

	private $request;
	private $requestlevel = 1;

	/* system options */
	private $syspages = 9;

	protected $_token;

	function __construct()
	{
		$this->setRequestUri();
		$this->initRequestChecker();
	}

	public function require_auth()
	{
		global $CFG;

		//$fp = fopen("file.txt", "a+");
		//fwrite($fp, serialize($_SERVER).' - '. date('Y-m-d H:i:s').PHP_EOL.PHP_EOL);
		//fclose($fp);

		header('Cache-Control: no-cache, must-revalidate, max-age=0');
		$has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
		$is_not_authenticated = (
			!$has_supplied_credentials ||
			$_SERVER['PHP_AUTH_USER'] != $CFG->CRM_LOGIN ||
			$_SERVER['PHP_AUTH_PW']   != $CFG->CRM_PASS	);

		if ($is_not_authenticated)
		{
			header('HTTP/1.1 401 Authorization Required');
			header('WWW-Authenticate: Basic realm="Enter CRM"');
			exit('Not Authorization');
		}
	}


	public function initialize()
	{
		global $CFG;

		$this->generateFullRequest();
		$this->generatePostRequest();
		$this->initPage();

	}

	public function data1C()
	{

		$json_daily_file = 'documents/buch/data_1C.json';
		if (!is_file($json_daily_file) || filemtime($json_daily_file) < time() - 14400)
		{
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
			file_put_contents($json_daily_file, $result);
		}

	}


	public function data1CLIL()
	{

		$json_daily_file = 'documents/buch/data_1C_LIL.json';
		if (!is_file($json_daily_file) || filemtime($json_daily_file) < time() - 21600)
		{
			$url = "http://192.168.1.122:8081/ledl/hs/api/data/get?date=01.01.2022";
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
							$arr[(int)$namber[1]]->БИН = $value->ДанныеПоПоступлению[0]->БИН;
							$arr[(int)$namber[1]]->ДанныеПоПоступлению = $value->ДанныеПоПоступлению;
							$cnt ++;
						}

						file_put_contents($json_daily_file, json_encode($arr));
				}

		}


	}


	public function profileuser()
	{

		/*
		global $CFG;

		$sql = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$CFG->USER->USER_ID}'");

			if($CFG->USER->USER_ID > 0)
			{
				if($sql->mobile == "" || $sql->name == "" || $sql->email == "" ||  $sql->avatar == '/documents/avatar/noavatar.jpg' )
				{
					if($_SERVER["REQUEST_URI"] !== '/profile/edit/'.$CFG->USER->USER_ID)
					{
							//print_r($sql);


						//$CFG->STATUS->ERROR = true;
						//$CFG->STATUS->MESSAGE = "Для продолжения работы в системе, заполните все поля помеченные красной *";

						//redirect('/profile/edit/'.$CFG->USER->USER_ID);
					}
				}
			}

			*/
	}


	/* add js css dynamicly to header --> not supported via ajax */
	function addToHeaderHTML($code)
	{
		$this->headerblock[] = $code;
	}

	function setTitle($str)
	{
		global $CFG;

		$CFG->oPageInfo->html_title = $str;

		$this->title = $str;
	}

	function setDescription($str)
	{
		global $CFG;

		$CFG->oPageInfo->html_description = $str;

		$this->description = $str;
	}

	function setKeywords($str)
	{
		global $CFG;

		$CFG->oPageInfo->html_keywords = $str;

		$this->keywords = $str;
	}

	function setContent($str)
	{
		$this->content = $str;
	}

	function setRequestUri($request = "")
	{
		if(!$request)
		{
			$request = $_SERVER["REQUEST_URI"];
		}

		$E = stripslashesarray(explode('/', $request));

		for($i=0; $i<sizeof($E); $i++)
		{
			if($E[$i] !== '')
			{
				++$L;

				$this->request[$L] = $E[$i];
			}
		}

		$this->request[] = '';


		//Костыль с авторизацнией, это урлы куда можно заходить без авторизации
		switch ($this->request[1])
		{
			case 'static':
			break;
			case 'documents':
			break;
			case 'whatsapp':
			break;
			case 'whatsapp_new':
			break;
			case 'speedometer':
			break;
			case 'bar_statistics':
			break;
			case 'filebox':
			break;

			default:

			break;
		}
		//End. Костыль с авторизацнией, это урлы куда можно заходить без авторизации
	}

	function setModulePath($path)
	{
		$this->modulePath = $path;
	}

	function setMobilePath($path)
	{
		$this->mobilePath = $path;
	}

	function setMobilePhone($phone)
	{
		$this->mobilePhone = $phone;
	}

	function setDisableTemplate($tpl)
	{
		$this->disableTemplate = $tpl;
	}

	function setSysPages($int)
	{
		$this->syspages = $int;
	}



	private function isWork()
	{
		global $CFG;

	   	if($CFG->aSystemOptions["site_on"] == 0)
		{
			echo 'error'; exit;
		}
	}

	private function isAjax()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function initRequestChecker()
	{
		global $CFG;
		switch($this->request[1])
		{
			case 'comments' :
				$COM = new Comments($_POST['pid']);
				$USER = new User();

				$CFG->USER = $USER;

				$COM->setPostId($_POST['id']);
				$COM->getListAjax(20, $_POST['num']);
				exit;
			break;

			case 'ajax' :

			break;

			case 'sys_comment.php' :

			break;


			case 'cookie' :


				if($_GET["id"] == 1)
				{
					setCookie('order', 'ORDER BY cdate DESC', time() + 3600*360, "/");
					setCookie('ordername', 'Дата создания', time() + 3600*360, "/");
				}
				elseif($_GET["id"] == 2)
				{
					setCookie('order', 'ORDER BY edate DESC', time() + 3600*360, "/");
					setCookie('ordername', 'Дата изменения', time() + 3600*360, "/");
				}

				redirect($_SERVER['HTTP_REFERER']);

			break;

			case 'cookie_face' :

				if($_GET["id"] == 1)
				{
					setCookie('order_my_face', 'ORDER BY  my_face.cdate DESC', time() + 3600*360, "/");
					setCookie('ordername', 'Дата создания', time() + 3600*360, "/");
				}
				elseif($_GET["id"] == 2)
				{
					setCookie('order_my_face', 'ORDER BY my_face.edate DESC', time() + 3600*360, "/");
					setCookie('ordername', 'Дата изменения', time() + 3600*360, "/");
				}

				redirect($_SERVER['HTTP_REFERER']);

			break;


			default :

			break;
		}
	}


	private function initPage()
	{
		global $CFG;

		$this->initModule();
		$this->isWork();


		if (is_object($this->oPageInfo))
		{
			if (isPageAccessable($this->oPageInfo))
			{
				$this->setContent($this->generateFromModuleContent($this->file));

				if(($this->oPageInfo->tmpl_u !== 'sys_login') && ($CFG->USER->USER_ID == 0))
				{
					redirect($CFG->AUTHPAGE);
				}
				else
				{
					$this->printPage();
				}
			}
			else
			{
				$CFG->STATUS->ERROR = true;
				$CFG->STATUS->MESSAGE = "Доступ к этой старнице запрещен";
				redirect($CFG->AUTHPAGE);
			}
		}
		else
		{
		  redirect('/error/');
		}
	}


	 function error($code)
	 {
		  $this->setTitle($code . ' - Ошибка');
		  $this->setContent($this->generateFromModuleContent($code.'.php'));
		  $this->printPage();
	 }

	private function initModule()
	{
		global $CFG;

		do
		{
			$response = getPageInfoByXcode($this->request[$this->requestlevel], $this->oPageInfo->id);

			if(sizeof($response) > 0)
			{
				$this->determineModule($response);
			}
		}
		while($response->id && $this->requestlevel < sizeof($this->request));

		$this->generateRequestLevel();
	}

	private function determineModule($response)
	{
		global $CFG;

		$CFG->pid =  $response->id;
		$CFG->oPageInfo = $response;

		$this->oPageInfo = $response;
		$this->requestlevel++;

		if($response->parent_id == $this->syspages)
		{
			$this->file = $response->tmpl_u.".php";
		}
		else
		{
			$this->file = "modules/{$response->tmpl_u}/{$response->tmpl_u}.php";
		}
	}

	/*
		Выводит на экран сгенерированную страницу
	*/

	public function printPage()
	{
		global $CFG;

		if ($this->isAjax())
		{
			header("Content-Type:application/json; charset=utf-8;");
			switch($this->request[sizeof($this->request)-1])
			{
				/* SPECIAL FOR OPERATIONS */
				case 'json' :
					echo $this->content;
				break;

				/* STATUS TEXT LAST OPERATION */
				case 'status' :
					echo $CFG->STATUS->GETSTATUSJSON(); $CFG->STATUS->CLEARSTATUS();
				break;

				default:
					$site['title'] = ($this->title) ? $this->title : $this->oPageInfo->html_title;
					$site['content'] = trim($this->content, "\r\n\r\n");
					echo json_encode($site);
				break;
			}
		}
		else
		{
			if($this->SINGLEPAGE == true) /* RSS and more more more */
			{
				echo $this->content;
			}
			else /* SHOW FULLPAGE with HEADER and FOOTER */
			{
				echo $this->generateFromModuleContent($this->header).
				$this->content.
				$this->generateFromModuleContent($this->footer);
				$this->UpSesHis();
				$this->UpDealAlert();
			}
		}
	}

	protected function generateRequestLevel()
	{
		global $CFG;

		$CFG->_GET_PARAMS = array();
		$outModuleLevel = 0;

		for($i=($this->requestlevel); $i<sizeof($this->request); $i++)
		{
			$CFG->_GET_PARAMS[$outModuleLevel] = $this->request[$i];

			$outModuleLevel++;
		}
	}

	// Ксли мы автаризованый, обновляем сесию и дату последнего посейщения + записываем в историю где был последний раз были
	protected function UpSesHis()
	{
		global $CFG;

		if($CFG->USER->USER_ID > 0)
		{
			$exp = time() + 31536000;
			$date = sqlDateNow();

			$query = "UPDATE {$CFG->DB_Prefix}sessions SET exp='{$exp}' WHERE fk_users_id='{$CFG->USER->USER_ID}'";
			$CFG->DB->query($query);

			$query = "UPDATE {$CFG->DB_Prefix}users SET vdate='{$date}' WHERE id='{$CFG->USER->USER_ID}'";
			$CFG->DB->query($query);

			//$this->history($_SERVER['REQUEST_URI']);
		}
	}


	// Пользователи тукущей компании
	protected function CompanyUsers()
	{
		global $CFG;

		if($CFG->USER->USER_ID > 0)
		{
			$manager_id_pid = getSQLRowO(" SELECT GROUP_CONCAT(id) FROM {$CFG->DB_Prefix}users WHERE user_id = {$CFG->USER->USER_DIRECTOR_ID}  AND visible = 1");
			return $manager_id_pid->{'GROUP_CONCAT(id)'};
		}
	}


	// Выводим уведомлние о закрыкых сделках
	protected function UpDealAlert()
	{
		global $CFG;

		//Вывод извещения ГлавБуху на одобрение закрытия сделки и отправка на модерацию далее босу
		if($CFG->USER->USER_ID == $CFG->USER->USER_ACCOUNTING_CHIEF && $CFG->USER->USER_ID > 0)
		{
			$users_id = $this->CompanyUsers();

			$data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE manager_id IN({$users_id}) AND visible= 1 AND open = 1 AND page_id = 1000 order by edate DESC ");
			foreach($data as $res)
			{
				$o = getSQLRowO("SELECT page_id FROM my_news WHERE id='{$res->parent_id}'");
				if($o->page_id == 976) continue;
				$resp[] = $res;
			}
			$data =	$resp[0];

			if(count($data) > 0)
			{
				include(realpath(dirname(__FILE__))."/tpl/templates/system/DealAlert.tpl");
				return true;
			}
			else
				return false;
		}

		//Вывод извещения Босу на одобрение закрытия сделки
		if($CFG->USER->USER_ID == $CFG->USER->USER_DIRECTOR_ID && $CFG->USER->USER_ACCOUNTING_CHIEF > 0)
		{
			$users_id = $this->CompanyUsers();

			$data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}news WHERE manager_id IN({$users_id}) AND visible= 1 AND open = 2 AND page_id = 1000 order by edate DESC ");

			foreach($data as $res)
			{
				$o = getSQLRowO("SELECT page_id FROM my_news WHERE id='{$res->parent_id}'");
				if($o->page_id == 976) continue;
				$resp[] = $res;
			}
			$data =	$resp[0];
			$real =	$resp;

			if(count($data) > 0)
			{
				if($_COOKIE['deal_stop_20'] != 1)
				{
					include(realpath(dirname(__FILE__))."/tpl/templates/system/DealAlertBoss.tpl");
				}
				return true;
			}
			else
				return false;
		}


	}



	// Записываем в историю где был последний раз были
	protected function history($url)
	{
		global $CFG;

		$user_id = $CFG->USER->USER_ID;
		$cdate = sqlDateNow();

		if($user_id == 1)
		{

		}
		else
		{
			if($url != '/error/')
			{
				$sql = "INSERT INTO {$CFG->DB_Prefix}histery (user_id, url, cdate) VALUES ('{$user_id}', '{$url}', '{$cdate}')";
				$CFG->DB->query($sql);
			}
		}
	}



	protected function generatePostRequest()
	{
		global $CFG;

		$CFG->_POST_PARAMS = array();

		foreach($_POST as $key => $value)
		{
			$CFG->_POST_PARAMS[$key] = $value;
		}
	}

	protected function generateFullRequest()
	{
		global $CFG;

		$CFG->_GET_FULL_PARAMS = array();

		for($i=0; $i<sizeof($this->request); $i++)
		{
			$CFG->_GET_FULL_PARAMS[$i] = $this->request[$i];
		}
	}

	private function generateFromModuleContent($path)
	{
		global $CFG;

		$path = $_SERVER['DOCUMENT_ROOT'].'/'.$path;

		if (is_file($path))
		{
			ob_start();
			include($path);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		else
			echo 'Нет такого модуля!'; exit;

	}



}
