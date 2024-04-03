<?
/* SINGLETON CLASS USER */

if (!isset($_User_class_definition))
{

$_User_class_definition = "defined";

class User
{
	var $EXP_TIME = 31536000;

	function __construct()
	{
		global $CFG;


		$this->LOGIN_RET_CODE = 1 * $_SESSION["SYS"]->login_code;

		if ($this->is_loggedIn())
		{


		  $query = "SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$this->USER_ID}'";
			$db->Record = $CFG->DB->getRow($query);
		  if($db->Record['id'] > 0)
			{
			  $this->USER_EMAIL = $db->Record["email"];
				$this->USER_MOBILE = $db->Record["mobile"];
			  $this->USER_ACL = $db->Record["acl"];
			  $this->USER_STATUS = $db->Record["status"];
			  $this->USER_BOSS = $db->Record["boss"];
				$this->USER_AVATAR = $db->Record["avatar"];
				$this->USER_ID = $db->Record["id"];
				$this->USER_DIRECTOR_ID = $db->Record["user_id"];
				$this->USER_BUCH = $db->Record["accounting"];
				$this->USER_TRANSFER_ACCESS = $db->Record["transfer_access"];

				$this->USER_ACCES_BUCH = $db->Record["accounting_access"];
				$this->ACCESS_COMPANY = $db->Record["access"];
				$this->PERSONAL_PAGE = $db->Record["personal"];
				$this->MONEY = $db->Record["money"];
				$this->USER_COUNT = $db->Record["count"];
			  $this->USER_NAME = htmlspecialchars($db->Record["name"]);
				$this->USER_LOGIN = $db->Record["login"];
				$this->USER_HIDDEN = $db->Record["hidden_record"];
				$this->USER_URL = $db->Record["home_url"];
				$this->USER_WHATSAPP = $db->Record["view_whatsapp"];
				$this->USER_DELETE = $db->Record["view_delete"];
				$this->USER_NOTE_SELECT = $db->Record["note_select_access"];
				$this->USER_WAREHOUSE = $db->Record["view_warehouse"];
				$this->USER_EXPENSES_LIL = $db->Record["view_expenses_lil"];
				$this->USER_VIEW_LOADER = $db->Record["view_loader"];
				$this->USER_VIEW_RUK = $db->Record["view_ruk"];
				$this->USER_CURATOR = $db->Record["curator"];

				$this->USER_VIEW_GOSZAKUP = $db->Record["view_goszakup"];
				$this->USER_VIEW_SALARY = $db->Record["view_salary"];

				// вытаскиваем сумы заметок и назмачаем переменые
				$res = "SELECT currency, note, record,accounting, accountant_chief,city,fraza FROM {$CFG->DB_Prefix}company WHERE id='{$db->Record[user_id]}'";
				$res = getSQLRowO($res);

				$this->USER_CURRENCY = $res->currency; // название валюты
				$this->USER_NOTE = 0; // стоимость заметки
				$this->USER_RECORD = 0; // стоимость запсии

				$this->USER_NOTEBOOK = $db->Record["notebook"]; // Номер записи блокнота1
				$this->USER_TAKS_ID = $db->Record["taks_id"]; // Номер записи примирования
				$this->USER_ACCOUNTING = $res->accounting; // id кассира компании
				$this->USER_ACCOUNTING_CHIEF = $res->accountant_chief; // id кассира компании
				$this->USER_FRAZA = $res->fraza;
				$this->USER_PAULS = $db->Record["pauls"];
				$this->USER_CITY = $res->city; // город компании
			}
			else
			{
				$this->logout();
			}
		}


	}

	function is_loggedIn()
	{
		global $CFG;

		$now = time();
		$CFG->DB->query("DELETE FROM my_sessions WHERE exp<'{$now}'");

		$this->USER_ID = 0;
		$this->USER_DIRECTOR_ID = 0;
		$session = session_id();
		$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
		$ip = $_SERVER['REMOTE_ADDR'];

		$query = "SELECT id, fk_users_id, exp FROM my_sessions WHERE ses_code='{$session}' AND ip = '{$ip}' AND user_agent = '{$HTTP_USER_AGENT}' ";
		$rec  = $CFG->DB->getRow($query, true);

	  if ($rec->id <= 0)	// NOTHING FOUND.
			return 0;
		else
		{
		  $id = 1 * $rec->id;
		  $exp = $rec->exp;
			$this->USER_ID = 1 * $rec->fk_users_id;


			$exp = time() + $this->EXP_TIME;

			$query  = "UPDATE {$CFG->DB_Prefix}sessions SET exp='{$exp}' WHERE id='{$id}'";
		  $CFG->DB->query($query);

			$this->LOGIN_RET_CODE = 0;
			$_SESSION["SYS"]->login_code = $this->LOGIN_RET_CODE;
			return 1;
		}
	}

	function getStatus($userName, $userPass)
	{
		global $CFG;

		$query = "SELECT id, visible FROM {$CFG->DB_Prefix}users WHERE mobile='{$userName}' AND visible = 1";
	  $response = getSQLRowO($query);
		return $response;
	}

	function login($userName, $userPass, $arr= array())
	{
		global $CFG;


		$this->logout();
		$this->USER_ID = 0;

		if($arr->id > 0)
		{
			$query =  getSQLRowO("SELECT pass FROM my_users WHERE mobile='{$userName}' AND visible = 1");

			if (password_verify($userPass, $query->pass))
			{
				return $this->_login($arr->id);
			}
			else {
				return $this->_login(0);
			}
		}
	}


	function _login($user_id)
	{
		global $CFG, $SYS;

		$this->logout();
		$this->USER_ID = 0;

		$user_id *= 1;

		$this->LOGIN_RET_CODE = -1;
		$_SESSION["SYS"]->login_code = $this->LOGIN_RET_CODE;

		$query = "SELECT id, email, name, status,user_id, avatar, login FROM {$CFG->DB_Prefix}users WHERE id='".$user_id."' AND visible='1'";

		if ($CFG->DB->numRows($query) <= 0)	// NOTHING FOUND
			return false;
		else
		{

				$db->Record = $CFG->DB->getRow($query);

		    $this->USER_ID = $db->Record ["id"];
		    $this->USER_LOGIN = $db->Record["login"];
		    $this->USER_EMAIL = $db->Record["email"];
				$this->USER_MOBILE = $db->Record["mobile"];
		    $this->USER_ACL = $db->Record["acl"];
		    $this->USER_STATUS = $db->Record["status"];
			$this->USER_AVATAR = $db->Record["avatar"];
			$this->USER_PHOTO = $db->Record["photo"];
			$this->USER_NAME = htmlspecialchars($db->Record["name"]);
			$this->USER_DIRECTOR_ID = $db->Record["user_id"];

		    $this->USER_PROVIDER = $db->Record["provider"];
		    $this->USER_UID = $db->Record["uid"];
		    $this->IDENTITY = $db->Record["identity"];

			$_SESSION["SYS"]->uid = $this->USER_ID;

			$sid = session_id(); $exp = time() + $this->EXP_TIME;

			$remote = $_SERVER["HTTP_X_FORWARDED_FOR"];
			if ($remote == "" || $remote == "unknown")
				$remote = $_SERVER["REMOTE_ADDR"];

			$USERA = $_SERVER["HTTP_USER_AGENT"];

			$sql  = "DELETE FROM {$CFG->DB_Prefix}sessions WHERE fk_users_id='{$this->USER_ID}'";
	   		$CFG->DB->query($sql );

			$query  = "INSERT INTO {$CFG->DB_Prefix}sessions (fk_users_id, ses_code, ip, user_agent, exp) VALUES (";
			$query .= "'{$this->USER_ID}', ";
			$query .= "'{$sid}', ";
			$query .= "'{$remote}', ";
			$query .= "'{$USERA}', ";
			$query .= "'{$exp}')";
		    $CFG->DB->query($query);

			$this->LOGIN_RET_CODE = 0;
			$_SESSION["SYS"]->login_code = $this->LOGIN_RET_CODE;

			return true;

		}
	}

	function loginFromSocial($data)
	{
		global $CFG;

		$this->logout();
		$this->USER_ID = 0;

		$sql = "SELECT id FROM {$CFG->DB_Prefix}users WHERE provider='".$data["provider"]."' AND uid='".$data["uid"]."' AND visible='1'";
		$response = getSQLField($sql);

		if(isset($response))
		{
		   return $this->_login($response);
		}
		else
		   return false;

	}


	function logout()
	{
	  global $CFG;

		$query  = "DELETE FROM {$CFG->DB_Prefix}sessions WHERE fk_users_id='{$this->USER_ID}'";
		$session = session_id();
		$query  = "DELETE FROM {$CFG->DB_Prefix}sessions WHERE ses_code='{$session}'";
	    $CFG->DB->query($query);

		$_SESSION["SYS"]->uid = 0;

		$this->USER_ID	= 0;
		$this->USER_NAME	= "";
		$this->USER_ACL	= 0;
		$this->USER_STATUS	= 0;
		$this->USER_EMAIL = "";
		$this->USER_LOGIN = "";
		$this->PROVIDER = "";
		$this->UID = 0;
		$this->IDENTITY = '';

	}

	function checkACL($acl)
	{
		if ($this->USER_ACL & $acl )
			return 1;
		else
			return 0;
	}


	function createUser($data)
	{
		global $CFG;

		$user_id = 0;
		$sql  = "SELECT id FROM {$CFG->DB_Prefix}users WHERE login='".addSlashes($data["login"])."'";

		if (getSQLField($sql))
			return -1; // Пользователь существ

		else
		{
			$status = 0;
			$visible=1;
			$date = sqlDateNow();

			   $sql  = "INSERT INTO {$CFG->DB_Prefix}users (cdate,login,pass,keyscode,info,status,visible) VALUES (";
			   $sql .= "'{$date}',";
			   $sql .= "'".addSlashes($data["login"])."',";

			   $sql .= "PASSWORD('{$data["passwd"]}'),";

			   $keyscode = $data["passwd"].md5($data["passwd"]);
			   $sql .= "'{$keyscode}',";

			   $sql .= "'{$data["info"]}',";


			   $sql .= "'".$status."', '".$visible."')";

			$CFG->DB->query($sql);
			$user_id = $CFG->DB->lastId();
			$this->saveUser($user_id, $data);
		}

		return $user_id;
	}


	function createUserJob($data)
	{


		global $CFG;


			$status = 3;
			$visible=1;
			$date = sqlDateNow();

			   $sql  = "INSERT INTO {$CFG->DB_Prefix}users (cdate,login,pass,keyscode,info,status,visible) VALUES (";
			   $sql .= "'{$date}',";
			   $sql .= "'".addSlashes($data["login"])."',";

			   $sql .= "PASSWORD('{$data["passwd"]}'),";

			   $keyscode = $data["passwd"].md5($data["passwd"]);
			   $sql .= "'{$keyscode}',";

			   $sql .= "'{$data["info"]}',";


			   $sql .= "'".$status."', '".$visible."')";

			$CFG->DB->query($sql);
			$user_id = $CFG->DB->lastId();
			$this->saveUser($user_id, $data);


		return $user_id;
	}






	function updateArray($db, $array)
	{
		global $CFG;

		foreach($array as $key => $value)
		{
			$str .= $key.', ';
			$data .= '"'.$value.'", ';
		}

		$str = rtrim($str, ', ');
		$data = rtrim($data, ', ');

		$sql = "INSERT INTO {$CFG->DB_Prefix}{$db} ({$str}) VALUES ({$data})";
		$CFG->DB->query($sql);

		return true;
	}



	function createUserFromSocial($data) {

		global $CFG;

		$user_id = 0;
		$sql  = "SELECT id FROM {$CFG->DB_Prefix}users WHERE uid='".$data["uid"]."' AND provider='".$data["provider"]."'";

		if (getSQLField($sql))
			return -1; // Пользователь существ

		else
		{
			$status = 0;
			$visible=1;
			$date = sqlDateNow();

			   $sql  = "INSERT INTO {$CFG->DB_Prefix}users (uid,identity,provider,photo,status,visible) VALUES (";
			   $sql .= "uid='".$data["uid"]."',";
			   $sql .= "identity='".$data["identity"]."',";
			   $sql .= "provider='".$data['provider']."',";
			   $sql .= "photo='{$data["photo"]}',";
			   $sql .= "'".$status."', '".$visible."')";

			$CFG->DB->query($sql);
			$user_id = $CFG->DB->lastId();

			$this->loginFromSocial($data);

		}

		return $user_id;
	}

	function saveUser($user_id, $data)
	{
		global $CFG;

		while(list($k, $v) = each($data))
			$data[$k] = addSlashes($v);

		$user_id *= 1;

		if ($user_id > 0)
		{
			$sql  = "UPDATE {$CFG->DB_Prefix}users SET ";

			$sql .= "name='{$data["name"]}',";
		    $sql .= "email='{$data["email"]}',";

		    $sql .= "avatar='/documents/avatar/noavatar.jpg'";
			$sql .= " WHERE id='{$user_id}'";

			$CFG->DB->query($sql);

		}
	}

	function delUser($user_id)
	{
		global $CFG;

		$user_id *= 1;
		$sql  = "DELETE FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}'";
		$CFG->DB->query($sql);

		return true;
	}

	function loadUser($user_id)
	{
		global $CFG;

		$user_id *= 1;
		$sql  = "SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}'";

		return getSQLRowO($sql);
	}

	function setNewPassword($pass, $id)
	{
		global $CFG;

		$sql  = "UPDATE {$CFG->DB_Prefix}users SET pass=PASSWORD('{$pass}') WHERE id='{$id}'";
		$CFG->DB->query($sql);
	}

	function getUserInfo($user_id, $field="*")
	{
		global $CFG;

		$user_id *= 1;
		$sql  = "SELECT {$field} FROM {$CFG->DB_Prefix}users WHERE id='{$user_id}' ORDER by name";

		return getSQLRowA($sql);
	}

	function getUserInfoBy($field, $value, $fields="*")
	{
		global $CFG;

		$user_id *= 1;

	 	$sql  = "SELECT {$fields} FROM {$CFG->DB_Prefix}users WHERE {$field}='{$value}' AND visible = 1";

		$id = $CFG->DB->getRow($sql);


		return $id['id'];
	}

    function checkIssetData($field, $value)
	{

        global $CFG;

        $query = "SELECT {$field} FROM {$CFG->DB_Prefix}users WHERE {$field}='{$value}'";
		$response = getSQLField($query);

        if(isset($response))
		{
           return $response;
		}
		else
		{
           return false;
		}
    }





    function checkIsset($name, $field, $value)
	{
    	global $CFG;
		$res = $value;

			for ($h=0; $h<sizeof($res); $h++)
			{
				$query = "SELECT {$res[$h]} FROM {$CFG->DB_Prefix}{$name} WHERE visible=1  AND {$res[$h]} LIKE '%{$field}%'";
				$response = getSQLField($query);

				if(isset($response))
				{
				   return $response;
				}
				else
				{
				   return false;
				}

			}
	}



	function updateUserField($field, $value, $user=0)
	{
		global $CFG;

		if($user == 0) $user = $this->USER_ID;

		$sql  = "UPDATE {$CFG->DB_Prefix}users SET {$field}='{$value}' WHERE id='{$user}'";
		$CFG->DB->query($sql);
	}





	function updateUserRate($user, $rate)
	{
		global $CFG;

		$oldrate = $this->getUserInfo($user, 'rate');
		$newrate = $oldrate + $rate;

		$this->updateUserField('rate', $newrate, $user);

		return $newrate;
	}



	function getUserInfoPASS($field, $value, $fields="*", $and="")
	{
		global $CFG;

		$user_id *= 1;

		$sql  = "SELECT {$fields} FROM {$CFG->DB_Prefix}users WHERE {$field}=PASSWORD('{$value}') {$and}";

		$count = getSQLField($sql);

		return $count;
	}


    function updateUserData($data)
	{

		global $CFG;

		$user_id *= 1;

		$username = $data['name'];
		$info = $data['info'];

		$day = $data['day'];
		$month = $data['month'];
		$year = $data['year'];

		$paul = $data['paul'];
		$city = $data['city'];

		$sql = "UPDATE {$CFG->DB_Prefix}users SET name='{$username}', info = '{$info}', day = '{$day}', month = '{$month}', year = '{$year}', paul = '{$paul}', city = '{$city}' WHERE id='{$this->USER_ID}'";
		$CFG->DB->query($sql);


		$pass = $data['passwd'];

			if($data['passwd'] && $data['newpass'] && $data['povtorpass'])
			{
				if($CFG->USER->getUserInfoPASS('pass', $pass, 'COUNT(id)', "and id='{$this->USER_ID}'") == 0)
				{
					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'Старый пароль введен не верно!';
					redirect($_SERVER["HTTP_REFERER"]);
				}
				else
				{

					if (($data['newpass'] && $data['povtorpass']) =='')
					{
						$CFG->STATUS->ERROR = true;
						$CFG->STATUS->MESSAGE = 'Введите новый пароль!';
						redirect($_SERVER["HTTP_REFERER"]);
					}
					else
					{
						if($data['newpass'] == $data['povtorpass'])
						{
							$sql = "UPDATE {$CFG->DB_Prefix}users SET pass=PASSWORD('{$data['newpass']}') WHERE id='{$this->USER_ID}'";
							$CFG->DB->query($sql);

							$CFG->STATUS->ERROR = true;
							$CFG->STATUS->MESSAGE = 'Пароль обновлен!';
							redirect($_SERVER["HTTP_REFERER"]);
						}

					}

					$CFG->STATUS->ERROR = true;
					$CFG->STATUS->MESSAGE = 'Пароли не совподают!';
					redirect($_SERVER["HTTP_REFERER"]);

				}
			}

		$this->USER_NAME = $userName;

		return true;
	}

	function updateUserUchast($field, $value)
	{
		global $CFG;

		$name = $field['name'];

		$sql  = "UPDATE {$CFG->DB_Prefix}party SET name='{$name}'";
		$CFG->DB->query($sql);
	}

	function updateCompanyDataArray($data)
	{
		global $CFG;

		$sql = "SELECT * FROM {$CFG->DB_Prefix}company WHERE id='{$CFG->USER->USER_ID}'";
		$response = getSQLRowO($sql);
		$count = count($response);

		$sql = "UPDATE my_users SET accounting = 0, accounting_access = 0 WHERE id={$response->accounting}";
		$CFG->DB->query($sql);

		if($count > 0 )
		{
			if($data['note'] == NULL) {	$note = 0;	} else {	$note = $data['note'];	}
			if($data['record'] == NULL) {	$record = 0;	} else {	$record = $data['record'];	}


			$sql = "UPDATE {$CFG->DB_Prefix}company SET name='{$data['name']}', text='{$data['text']}',  fraza='{$data['fraza']}', city='{$data['city']}', accountant_chief='{$data['accountant_chief']}', currency='{$data['currency']}', note='{$note}', record='{$record}' WHERE id={$CFG->USER->USER_ID}";
			var_dump($CFG->DB->query($sql));

			$sql = "UPDATE my_users SET accounting = 1, accounting_access = 1 WHERE id={$data['accounting']}";
			$CFG->DB->query($sql);

			redirect('/profile/company/'.$CFG->USER->USER_ID);
		}
		else
		{
			$sql = "INSERT INTO {$CFG->DB_Prefix}company (id, user_id, city, name, text, visible, sys_language) VALUES ({$CFG->USER->USER_ID}, {$CFG->USER->USER_ID}, '{$data['city']}', '{$data['name']}', '{$data['text']}', 1, '{$CFG->SYS_LANG}')";
			$CFG->DB->query($sql);

			$CFG->STATUS->ERROR = true;
			$CFG->STATUS->MESSAGE = 'Вы создали свою компанию.';
		}


		return true;
	}


	function updateUserDataArray($data)
	{
		global $CFG;

		if($data['avatar'] == '/tpl/img/noavatar.jpg')
		{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = 'Сначала необходимо загрузить ваше фото!';

					redirect($_SERVER["HTTP_REFERER"]);
		}


		foreach($data as $key => $value)
		{
			if($key == 'user_act')continue;
			if($key == 'passwd2')continue;
			if($key == 'id')continue;


			if($key == 'dob')
			{
				if($value == "")
				{
						continue;
				}
				else
				{
					$str .= ' '.$key.'="'.$value.'",';
				}
			}
			if($key == 'dob')continue;


			if($key == 'salary_uu')
			{
				if($value == "")
				{
						continue;
				}
				else
				{
					$str .= ' '.$key.'="'.$value.'",';
				}
			}
			if($key == 'salary_uu')continue;





			if($key == 'passwd')
			{
				if($value == !"")
				{
					$key = 'pass';

					$password_hash = password_hash($value, PASSWORD_DEFAULT);

					$value = "'{$password_hash}'";
					$str .= ' '.$key.'='.$value.',';
				}
				else
					continue;
			}
			if($key == 'pass')continue;

			if($key == 'home_url')
			{
				if($value == !"")
				{
					$key = 'home_url';

					$urls = htmlspecialchars($value);
					$urls = str_replace("&amp;", "&", $urls);

					$str .= ' '.$key.'="'.$urls.'",';
				}
				else
					continue;
			}
			if($key == 'home_url')continue;



			if (is_numeric($value))
			{
				$str .= ' '.$key.'='.$value.',';

			}
			else
			{
				$str .= ' '.$key.'="'.$value.'",';
			}
		}


		$str = trim($str,',');
		$sql = "UPDATE {$CFG->DB_Prefix}users SET {$str} WHERE id={$data[id]}";
		$CFG->DB->query($sql);


		return true;

	}



	function insertUserDataArray($data)
	{
		global $CFG;


		foreach($data as $key => $value)
		{
			if($key == 'user_act')continue;

			if($key == 'passwd2')
			{
				$key = 'keyscode';
				$ks = $value.md5($value);
				$value = $ks;

			}

			if($key == 'passwd')
			{
				$key = 'pass';
				$keyscode = "PASSWORD('{$value}')";
				$value = $keyscode;

			}


			if($key == 'login')
			{
				$addSlashes = addSlashes($value);
				$value = $addSlashes;
			}

			if($key == 'pass')
			{
				$valueS .= $value.',';
			}
			else
			{
				$valueS .= '"'.$value.'",';
			}

			$str .= $key.',';
		}

		$str = trim($str,',');
		$valueS = trim($valueS,',');

		$sql = "INSERT INTO {$CFG->DB_Prefix}users ({$str}) VALUES ({$valueS});";
		$CFG->DB->query($sql);

		return true;

	}



	function insertUserJobDataArray($data)
	{
		global $CFG;


		foreach($data as $key => $value)
		{
			if($key == 'user_act')continue;

			if($key == 'passwd2')
			{
				$key = 'keyscode';
				$ks = $value.md5($value);
				$value = $ks;

			}

			if($key == 'passwd')
			{
				$key = 'pass';
				$keyscode = "PASSWORD('{$value}')";
				$value = $keyscode;

			}


			if($key == 'login')
			{
				$addSlashes = addSlashes($value);
				$value = $addSlashes;
			}

			if($key == 'pass')
			{
				$valueS .= $value.',';
			}
			else
			{
				$valueS .= '"'.$value.'",';
			}

			$str .= $key.',';
		}

		$str = trim($str,',');
		$valueS = trim($valueS,',');

		$sql = "INSERT INTO {$CFG->DB_Prefix}users ({$str}) VALUES ({$valueS});";
		$CFG->DB->query($sql);

		$sql = "SELECT * FROM {$CFG->DB_Prefix}users WHERE visible=1 order by id DESC";
		$response = getSQLRowO($sql);
		$count = count($response);

		$sql = "INSERT INTO {$CFG->DB_Prefix}company (id, user_id, name_company, body, visible, sys_language) VALUES ({$response->id}, {$response->id}, '{$data['name_company']}', '{$data['text_company']}', 1, '{$CFG->SYS_LANG}')";
		$CFG->DB->query($sql);

		return true;

	}


	 		/* $sql  = "INSERT INTO {$CFG->DB_Prefix}users (cdate,login,pass,keyscode,info,status,visible) VALUES (";
			   $sql .= "'{$date}',";
			   $sql .= "'".addSlashes($data["login"])."',";

			   $sql .= "PASSWORD('{$data["passwd"]}'),";

	*/





	function updateLogoField($img)
	{

		global $CFG;

		$sql = "SELECT * FROM {$CFG->DB_Prefix}company WHERE id='{$CFG->USER->USER_ID}'";
		$response = getSQLRowO($sql);
		$count = count($response);

		if($count > 0 )
		{

			$sql = "UPDATE {$CFG->DB_Prefix}company SET logo_company='{$img}' WHERE id={$CFG->USER->USER_ID}";
			$CFG->DB->query($sql);
		}
		else
		{
			$sql = "INSERT INTO {$CFG->DB_Prefix}company (id, user_id, logo_company, visible, sys_language) VALUES ({$CFG->USER->USER_ID}, {$CFG->USER->USER_ID}, '{$img}', 1, '{$CFG->SYS_LANG}')";
			$CFG->DB->query($sql);
		}
	}




	function indateofficeDataArray($id, $array)
	{

		global $CFG;

		$str = '';

		foreach($array as $key => $value)
		{
			if($key == 'user_act') continue;
			if($key == 'sys_language')continue;

			if($key == 'type_company')
			{
				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$type_id .= $value[$x].',';
					}
				}

				$idS = trim($type_id, ",");

				if($idS == !"")
					$CatIdAnd = $idS;

				$str .= $key.'_id="'.$CatIdAnd.'", ';
			}
			if($key == 'type_company') continue;



			if (is_numeric($value))
			{
				$field .= $key.'_id,';
				$str .= $key.'_id='.$value.', ';
			}
			else
			{	$field .= $key.', ';
				$str .= $key.'="'.$CFG->DB->escape($value).'", ';
			}
		}


		$str = trim($str, ', ');

		$sql  = "UPDATE {$CFG->DB_Prefix}news SET {$str} WHERE id='{$id}'";
		$CFG->DB->query($sql);

		return true;
	}



	function indateNewsDataArray($id, $array)
	{
		global $CFG;

		$str = '';

			//Сохраняем предыдущию версию face
			$NEWS = new News();
			$o = $NEWS->getObject(868, $id);
			$NEWS->faceUp($id, $o, 'news');

		foreach($array as $key => $value)
		{
			if($key == 'user_act') continue;
			if($key == 'sys_language')continue;


			if($key == 'type_company')
			{
				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$type_id .= $value[$x].',';
					}
				}

				$idS = trim($type_id, ",");

				if($idS == !"")
					$CatIdAnd = $idS;

				$str .= $key.'_id="'.$CatIdAnd.'", ';
			}
			if($key == 'type_company') continue;


			if (is_numeric($value))
			{
				$field .= $key.'_id,';
				$str .= $key.'_id='.$value.', ';
			}
			else
			{	$field .= $key.', ';
				$str .= $key.'="'.$CFG->DB->escape($value).'", ';
			}
		}


		$str = trim($str, ', ');

		$sql  = "UPDATE {$CFG->DB_Prefix}news SET {$str} WHERE id='{$id}'";
		$CFG->DB->query($sql);

		return true;
	}


	function indateFaceDataArray($id, $array)
	{
		global $CFG;

		//Сохраняем предыдущию версию face
		$FACE = new Face();
		$o = $FACE->getObject(1012, $id);
		$FACE->faceUp($id, $o, 'face');

		$str = '';
		// Вмешиваемся в функцию и добавляем очередной костыль, если более 1 контактных данных то тогда чистим ячейки с директором и mobile_cashback по любому отчищяем
			$sql  = "UPDATE {$CFG->DB_Prefix}face SET cashback=0  WHERE id='{$id}'";
			$CFG->DB->query($sql);

		foreach($array as $key => $value)
		{
			if($key == 'user_act') continue;
			if($key == 'sys_language')continue;

			if($key == 'data_name')
			{
				$res = serialize($value);
				$field .= 'data_name, ';
				$str .= 'data_name="'.$CFG->DB->escape($res).'", ';
			}
			if($key == 'data_name')continue;


			if($key == 'data_mobile')
			{
				$res = serialize($value);
				$field .= 'data_mobile, ';
				$str .= 'data_mobile="'.$CFG->DB->escape($res).'", ';
			}
			if($key == 'data_mobile')continue;

			if($key == 'data_whatsapp')
			{
				$res = serialize($value);
				$field .= 'data_whatsapp, ';
				$str .= 'data_whatsapp="'.$CFG->DB->escape($res).'", ';
			}
			if($key == 'data_whatsapp')continue;


			if($key == 'data_email')
			{
				$res = serialize($value);
				$field .= 'data_email, ';
				$str .= 'data_email="'.$CFG->DB->escape($res).'", ';
			}
			if($key == 'data_email')continue;

			if($key == 'data_other')
			{
				$res = serialize($value);
				$field .= 'data_other, ';
				$str .= 'data_other="'.$CFG->DB->escape($res).'", ';
			}
			if($key == 'data_other')continue;

			if($key == 'tel')
			{
				$str .= 'tel="'.$value.'", ';
			}
			if($key == 'tel')continue;


			if($key == 'skidka_led')
			{
				$str .= 'skidka_led="'.$value.'", ';
			}
			if($key == 'skidka_led')continue;



			if($key == 'cashback')
			{
				if($value > 0)
				{
					$str .= 'cashback="'.$value.'", ';
				}else {					$str .= 'cashback="0", ';	}
			}
			if($key == 'cashback')continue;


			if($key == 'floor')
			{
				$str .= 'floor="'.$value.'", ';
			}
			if($key == 'floor')continue;

			if($key == 'bcdate')
			{
					if($value == '')
					{
						$date = 0;
						$str .= 'bcdate="'.$date.'", ';
					}
					else
					{
						$date = strtotime($value.' 14:00:00');
						$str .= 'bcdate="'.$date.'", ';
					}

			}
			if($key == 'bcdate')continue;

			/*
			if($key == 'mobile_cashback')
			{
				if($value == 'on')
				{
					$str .= 'mobile_cashback="'.$array["data_mobile"][0].'", ';
				}
				else
				{
					$str .= 'mobile_cashback="",';
				}
			}
			if($key == 'mobile_cashback')continue;
			*/

			if($key == 'type_company')
			{
				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$type_id .= $value[$x].',';
					}
				}

				$idS = trim($type_id, ",");

				if($idS == !"")
					$CatIdAnd = $idS;

				$str .= $key.'_id="'.$CatIdAnd.'", ';
			}
			if($key == 'type_company') continue;

			if($key == 'marketing_id')
			{

				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$type_portrait_id .= $value[$x].',';
					}
				}

				$idT = trim($type_portrait_id, ",");

				if($idT == !"")
					$CatT = $idT;

				$str .= 'marketing_id="'.$CatT.'", ';

			}
			if($key == 'marketing_id') continue;


			if (is_numeric($value))
			{
				$field .= $key.'_id,';
				$str .= $key.'_id='.$value.', ';
			}
			else
			{	$field .= $key.', ';
				$str .= $key.'="'.$CFG->DB->escape($value).'", ';
			}
		}

		$str = trim($str, ', ');

		$sql  = "UPDATE {$CFG->DB_Prefix}face SET {$str} WHERE id='{$id}'";
		$CFG->DB->query($sql);

		return true;
	}


	function updateNewsDataArray($array)
	{

		global $CFG;

		$str = '';

		foreach($array as $key => $value)
		{
			if($key == 'user_act') continue;
			if($key == 'sys_language')continue;
			if($key == 'visible')continue;


			if($key == 'name_company')
			{
				$value = apost_replace($value);
			}

			if($key == 'info')
			{
				$value = apost_replace($value);
			}


			if($key == 'type_company')
			{
				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$id .= $value[$x].',';
					}
				}

				$idS = trim($id, ",");

				if($idS == !"")
					$CatIdAnd = $idS;

				$field .= $key.'_id,';
				$str .= "'".$CatIdAnd."',";
			}
			if($key == 'type_company')continue;


			if (is_numeric($value))
			{
				$field .= $key.'_id, ';
				$str .= ''.$value.',';
			}
			else
			{	$field .= $key.', ';
				$str .= "'".$value."',";
			}

		}

		$field = trim($field, ', ');
		$str = trim($str, ',');


		$sql = "INSERT INTO {$CFG->DB_Prefix}news ({$field}) VALUES ({$str})";

		$CFG->DB->query($sql);

		return true;
	}


	function updateFaceDataArray($array)
	{
		global $CFG;

		$str = '';

		foreach($array as $key => $value)
		{

			if($key == 'user_act') continue;
			if($key == 'sys_language')continue;
			if($key == 'visible')continue;


			if($key == 'data_name')
			{
				$res = serialize($value);
				$field .= 'data_name, ';
				$str .= "'".$res."',";
			}
			if($key == 'data_name')continue;


			if($key == 'data_mobile')
			{
				$res = serialize($value);
				$field .= 'data_mobile, ';
				$str .= "'".$res."',";
			}
			if($key == 'data_mobile')continue;


			if($key == 'data_email')
			{
				$res = serialize($value);
				$field .= 'data_email, ';
				$str .= "'".$res."',";
			}
			if($key == 'data_email')continue;


			if($key == 'data_other')
			{
				$res = serialize($value);
				$field .= 'data_other, ';
				$str .= "'".$res."',";
			}
			if($key == 'data_other')continue;

			if($key == 'data_whatsapp')
			{
				$res = serialize($value);
				$field .= 'data_whatsapp, ';
				$str .= "'".$res."',";
			}
			if($key == 'data_whatsapp')continue;

			if($key == 'bcdate')
			{
					if($value == '') continue;

					$field .= 'bcdate, ';
					$str .= "'".strtotime($value.' 14:00:00')."',";
			}
			if($key == 'bcdate')continue;


			if($key == 'type_company')
			{
				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$id .= $value[$x].',';
					}
				}

				$idS = trim($id, ",");

				if($idS == !"")
					$CatIdAnd = $idS;

				$field .= $key.'_id,';
				$str .= "'".$CatIdAnd."',";
			}
			if($key == 'type_company')continue;


			if($key == 'marketing_id')
			{
				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$id .= $value[$x].',';
					}
				}

				$idS = trim($id, ",");

				if($idS == !"")
					$CatIdAnd = $idS;

				$field .= $key.',';
				$str .= "'".$CatIdAnd."',";
			}
			if($key == 'marketing_id')continue;


			if($key == 'mobile_cashback')
			{
				if($value == 'on')
				{
					$field .= $key.',';
					$str .= "'".$array["name_client_mobile"][0]."',";
				}
				else
				{
					$field .= '';
					$str .= '';
				}
			}
			if($key == 'mobile_cashback')continue;


			if($key == 'tel')
			{
				$field .= $key.',';
				$str .= "'".$value."',";
			}
			if($key == 'tel')continue;


			if($key == 'skidka_led')
			{
				$field .= $key.',';
				$str .= "'".$value."',";
			}
			if($key == 'skidka_led')continue;

			if($key == 'cashback')
			{
				$field .= $key.',';
				$str .= "'".$value."',";
			}
			if($key == 'cashback')continue;


			if($key == 'floor')
			{
				$field .= $key.',';
				$str .= "'".$value."',";
			}
			if($key == 'floor')continue;


			if (is_numeric($value))
			{
				$field .= $key.'_id, ';
				$str .= ''.$value.',';
			}
			else
			{	$field .= $key.', ';
				$str .= "'".$value."',";
			}

		}
		$field .= 'cdate, edate';
		$str .= "'".time()."',"."'".time()."',";

		$field = trim($field, ', ');
		$str = trim($str, ',');

		$sql = "INSERT INTO {$CFG->DB_Prefix}face ({$field}) VALUES ({$str})";
		$CFG->DB->query($sql);

		return true;
	}



	function updateOfficeDataArray($array)
	{

		global $CFG;

		$str = '';

		foreach($array as $key => $value)
		{
			if($key == 'user_act') continue;
			if($key == 'sys_language')continue;
			if($key == 'visible')continue;


			if($key == 'tel')
			{
				$field .= $key.',';
				$str .= "'".$value."',";
			}
			if($key == 'tel')continue;

			if($key == 'name_company')
			{
				$value = apost_replace($value);
			}

			if($key == 'info')
			{
				$value = apost_replace($value);
			}


			if($key == 'type_company')
			{
				for ($x=0; $x<sizeof($value); $x++)
				{
					if($value[$x] > 0)
					{
						$id .= $value[$x].',';
					}
				}

				$idS = trim($id, ",");

				if($idS == !"")
					$CatIdAnd = $idS;

				$field .= $key.'_id,';
				$str .= "'".$CatIdAnd."',";
			}
			if($key == 'type_company')continue;



			if($key == 'tel')
			{

				$field .= $key.',';
				$str .= "'".$value."',";
			}
			if($key == 'tel')continue;


			if (is_numeric($value))
			{
				$field .= $key.'_id, ';
				$str .= ''.$value.',';
			}
			else
			{	$field .= $key.', ';
				$str .= "'".$value."',";
			}

		}

		$field = trim($field, ', ');
		$str = trim($str, ',');

		$sql = "INSERT INTO {$CFG->DB_Prefix}news ({$field}) VALUES ({$str})";
		$CFG->DB->query($sql);
		return true;
	}


	function cropUserAvatar($data, $type, $forder = 'news', $pid_id = '')
	{
		global $CFG;

		if($pid_id == '')
		{
			$forder2 = '';
		}
		else
		{
			$forder2 = $pid_id.'/';

			$oldumask = umask(0);
				mkdir('./documents/'.$forder.'/'.$forder2.'', 0777);
				chmod('./documents/'.$forder.'/'.$forder2.'', 0777);
			umask($oldumask);

		}

		$path = 'documents/'.$forder.'/' . $forder2 . md5($file['name'] . sqlDateNow()) . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

		switch($type)
		{
			case 'crop' :

				$user = $this->getUserInfo($this->USER_ID);
				$image = $user['photo'];
				$savepath = $path.'_crop.jpg';

				if($data['x1'] != '' && $data['y1'] !='' && $data['x2'] !='' && $data['y2'] != '' && $data['w'] != '' && $data['h'] != '')
				{
					$pos = array('x1' => $data['x1'], 'y1' => $data['y1'], 'x2' => $data['x2'], 'y2' => $data['y2'], 'w' => $data['w'], 'h' => $data['h']);
					$pos = serialize($pos);

					try
					{
						$img = AcImage::createImage($image);
							   AcImage::setRewrite(true);
							   AcImage::setQuality(90);

						$img->crop((int)$data['x1'], (int)$data['y1'], (int)$data['w'], (int)$data['w'])
							->resize($CFG->avatar_w, $CFG->avatar_h)
							->save($savepath);

						$this->updateUserField("posAvatar", $pos);
					}
					catch (IllegalArgumentException $ex)
					{
					   $CFG->STATUS->ERROR = true;
					   $CFG->STATUS->MESSAGE = 'В процессе произошла ошибка';
					}
					catch (FileNotFoundException $ex)
					{
					   echo 0;
					}
					return $savepath;
				}

			break;


			case 'defaultAvatar' :

				$image = $data['tmp_name'];
				$savepath = $path.'_med.jpg';

				try
				{
					$img = AcImage::createImage($image);
						   AcImage::setRewrite(true);
						   AcImage::setQuality(80);

					$img->cropCenter('5pr', '5pr')
						->resize(150, 150)
						->save($savepath);
				}
				catch (FileNotFoundException $ex)
				{
				}

				return $savepath;
			break;

			default :


				$image = $data['tmp_name'];
				$savepath = $path.'_big.jpg';

				try
				{
					$img = AcImage::createImage($image);
						   AcImage::setRewrite(true);
						   AcImage::setQuality(80);

					$img->resize(1000, 1000)
						->save($savepath);
				}
				catch (FileNotFoundException $ex)
				{
				}

				return $savepath;
			break;

		}
	}


	function checkFILEdata($file)
	{
		global $CFG;

		$err = 0; $cssError = 'id=input_error';

		foreach($file as $key => $value)
		{
		   switch($key)
		   {
                /* ONLY DOC PDF */
				case 'resume' :

				   if(!$this->checkExtFile($file['resume'])) { $input[$key] = $cssError; $CFG->STATUS->MESSAGE = "Неверный тип файла. Доступные форматы : DOC, PPT, PDF"; $err++; }

				break;

				case 'tezis' :

				   if(!$this->checkExtFile($file['tezis'])) { $input[$key] = $cssError; $CFG->STATUS->MESSAGE = "Неверный тип файла. Доступные форматы : DOC, PPT, PDF"; $err++; }

				break;

				case 'presentation' :

				   if(!$this->checkExtFile($file['presentation'])) { $input[$key] = $cssError; $CFG->STATUS->MESSAGE = "Неверный тип файла. Доступные форматы : DOC, PPT, PDF"; $err++; }

				break;

				/* CHECK IMAGE */
				case 'submit_photo' :

				   if(!$this->checkExtFile($file['submit_photo'])) { $input[$key] = $cssError; $CFG->STATUS->MESSAGE = "Неверный тип файла. Доступные форматы : JPG, JPEG, PNG, GIF"; $err++; }

				break;

				case 'photo' :

				   if(!$this->checkExtFile($file['photo'])) { $input[$key] = $cssError; $CFG->STATUS->MESSAGE = "Неверный тип файла. Доступные форматы : JPG, JPEG, PNG, GIF"; $err++; }

				break;

				default:

					if(!$this->checkExtFile($file[$key])) { $input[$key] = $cssError; $CFG->STATUS->MESSAGE = "Неверный тип файла"; $err++; }

				break;

		   }

		}
		if($err > 0)
		{
			$CFG->STATUS->ERROR = true;
			return false;

		}else
			return true;

	}

	function checkExtFile($data, $type = NULL)
	{
		/* allowed image exp */
		$image_exp = array (
			"jpeg" => "image/jpeg",
			"jpg" => "image/jpeg",
			"JPG" => "image/jpeg",
			"jpe" => "image/jpeg",
			"jfif" => "image/jpeg",
			"gif" => "image/gif",
			"png" => "image/png"
		);

		/* allowed audio exp */
		$audio_exp = array (
			"mp3" => "audio/mpeg",
			"MP3" => "audio/mpeg",
			"aac" => "audio/mpeg",
			"AAC" => "audio/mpeg",
			"ogg" => "audio/ogg"
		);

		/* allowed doc files exp */
		$doc_exp = array (
			"doc" => "application/msword",
			"xls" => "application/vnd.ms-excel",
			"pdf" => "application/pdf",
			"xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
			"xltx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
			"potx" => "application/vnd.openxmlformats-officedocument.presentationml.template",
			"ppsx" => "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
			"pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
			"sldx" => "application/vnd.openxmlformats-officedocument.presentationml.slide",
			"docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
			"dotx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
			"xlam" => "application/vnd.ms-excel.addin.macroEnabled.12",
			"xlsb" => "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
			"rar" => "application/x-rar-compressed",
			"rar" => "application/octet-stream",
			"zip" => "application/zip, application/octet-stream",
			"txt" => "application/txt",
		);



		/* get info and exp from current file */
		$expansion = strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));

		/* set allow exp for current file */
		if(isset($image_exp[$expansion]))
		{
			$allow_expansion = $image_exp;
			$type = 'image';
		}

		if(isset($audio_exp[$expansion]))
		{
			$allow_expansion = $audio_exp;
			$type = 'audio';
		}

		if(isset($doc_exp[$expansion]))
		{
			$allow_expansion = $doc_exp;
			$type = 'other';
		}

		/* search allowed exp from file */
		foreach ($allow_expansion as $key => $value)
		{
			  if ($key == $expansion)
			  {
			  	  return $type;
			  }
		}

		return false;
	}


	function checkRegExEmail($email)
	{
		if(preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $email))
		   return true;

		else
		   return false;
	}


	function saveForm($data)
	{
		foreach($data as $key => $value)
		   $form[$key] = $value;

	    $_SESSION['form'] = serialize($form); //PACK ENTERED USER FORM
	}

	function enterSocial($token)
	{

	   global $CFG;

	   $sig = md5($token.'e5ad4614b3c4c09afc037270ede81aa6');
	   $wid = '36509';

	   $authData = file_get_contents('http://loginza.ru/api/authinfo?token='.$token);
	   $user = json_decode($authData);

	   $data['name']     = $user->name->first_name.' '.$user->name->last_name;
	   $data['uid']      = $user->uid;
	   $data['provider'] = $user->provider;
	   $data['photo']    = $user->photo;
	   $data['identity'] = $user->identity;

       if($data['uid'] && $data['provider'])

	      if(!$this->loginFromSocial($data))
              $this->createUserFromSocial($data);

	}


}// end of class User

} // end of the definition checking

	/*         2013 YEAR  */
	/*  EDUARD ZAUKARNAEV */
	/* WEBICOM PRODUCTION */

	class Operation
	{
		private $user;
		private $type = 0; /* for more operation ex: email {change_email, save_email, check_email} */
		private $expired = 1; /* 24 HOUR */
		private $operation;
		private $token;
		private $value = '';

		function __construct()
		{
		}

		function setType($int)
		{
			$this->type = $int;
		}

		function setOperation($str)
		{
			$this->operation = $str; /* ex: change_password */
		}

		function setUser($id)
		{
			$this->user = $id;
		}

		function setValue($str)
		{
			$this->value = $str; /* ex: 123456789 (operation:change_password) */
		}

		function setToken($int)
		{
			$this->token = $int; /* for check isset token */
		}

		function setExpireTime($int)
		{
			$this->expire = $int; /* in day ex:30 (after 30 days, token disabled) || default:1 day or 24 hour*/
		}

		function run()
		{
			if($key = $this->getActiveUserOperation())
			{
				$this->destroy($key);
			}

			$this->putDB();
		}

		function check()
		{
			$response = $this->issetToken();

			if(sizeof($response) > 0)
			{
				if($this->isExpiredTime($response->expire))
				{
					return $response;
				}
				else
				{
					return -1; /* key is expired */
				}
			}
			else
			{
				return 0; /* key not found */
			}
		}

		function issetToken()
		{
			global $CFG;

			if($this->user != '')
			{
				$adv = "AND user='".$this->user."'";
			}

			$sql = "SELECT * FROM {$CFG->DB_Prefix}user_operation WHERE token='{$this->token}' {$adv} AND type='{$this->type}' AND operation='{$this->operation}' AND visible=1";
			$response = getSQLRowO($sql);

			return $response;
		}

		function destroy($key='')
		{
			global $CFG;

			if($key == '')
			{
				$key = $this->token;
			}

			$sql = "UPDATE {$CFG->DB_Prefix}user_operation SET visible=0 WHERE user='{$this->user}' AND type='{$this->type}' AND token='{$key}'";
			$CFG->DB->query($sql);
		}

		function getUrl()
		{
			/*
			$url .= $CFG->MAINPAGE;
			$url .= 'sys_confirm.php?operation='.$this->operation;
			$url .= '&user='.$this->user;
			$url .= '&type='.$this->type;
			$url .= '&token='.$this->token;
			*/

			return $this->token;
		}

		private function putDB()
		{
			global $CFG;

			$token = $this->getToken(); $nowtime = time(); $expire = $this->getExpiredTime();

			$sql = "INSERT INTO {$CFG->DB_Prefix}user_operation (user, type, value, operation, token, time, expire, visible) VALUES (
			'{$this->user}', '{$this->type}', '{$this->value}', '{$this->operation}', '{$token}', '{$nowtime}', '{$expire}', '1')";
			$CFG->DB->query($sql);

			return $CFG->DB->lastId();
		}

		private function getActiveUserOperation()
		{
			global $CFG;

			$sql = "SELECT token FROM {$CFG->DB_Prefix}user_operation WHERE user='{$this->user}' AND operation='{$this->operation}' AND type='{$this->type}'";
			$response = getSQLField($sql);

			return $response;
		}

		private function getToken()
		{
			$this->setToken(GetRandomString1(32));

			return $this->token;
		}

		private function isExpiredTime($expired)
		{
			if(time() > $expired)
				return false; /* token expired */

			else
				return true; /* token enabled */
		}

		private function getExpiredTime()
		{
			return time() + ($this->expired * 24 * 60 * 60);
		}

	}

	class UserGallery
	{
		private $user;

		private $category;
		private $postid = 0;
		private $image;
		private $file;

		private $path = 'documents/gallery/';

		private $quality = 100;
		private $resolution = 600;

		function setUser($user)
		{
			$this->user = $user;
		}

		function setDirecory($path)
		{
			$this->path = $path;
		}

		function setResolution($int)
		{
			$this->maxresolution = $int;
		}

		function setQuality($int)
		{
			$this->quality = $int;
		}

		function setPostId($int)
		{
			$this->postid = $int;
		}

		function setCategory($int)
		{
			if(sizeof($this->category = UserGallery::getCategory($int)) < 0)
			{
				$this->category->id = $int;
			}
		}

		function setCategoryName($str)
		{
			$this->category->name = $str;
		}

		function setCategoryDescription($str)
		{
			$this->category->description = $str;
		}

		function setImage($mixed) /* $_FILES or INT */
		{
			if(is_array($mixed))
			{
				foreach($mixed as $key => $value)
				{
					$this->image->$key = $value;
				}
			}
			if(is_numeric($mixed) > 0)
			{
				$this->image = UserGallery::getImage($mixed);
			}
		}

		function setImageName($str)
		{
			$this->image->name = $str;
		}

		function setImageDescription($str)
		{
			$this->image->description = $str;
		}

		function getURL()
		{
			try
			{
				$this->image->url = $this->path . md5($this->image->name . $this->user . sqlDateNow()) . '.' . $this->getExtFile();

				$img = AcImage::createImage($this->image->tmp_name);
					   AcImage::setQuality($this->quality);
					   AcImage::setRewrite(true);

				$img->resize($this->resolution, $this->resolution)
					->save($this->image->url);

				return $this->image->url = '/' . $this->image->url;
			}
			catch (IllegalArgumentException $ex)
			{
				return false;
			}
			catch (FileNotFoundException $ex)
			{
				return false;
			}
			catch (InvalidFileException $ex)
			{
				return false;
			}
		}

		function getExtFile()
		{
			return strtolower(pathinfo($this->image->name, PATHINFO_EXTENSION));
		}

		function getImages()
		{
			global $CFG;

			if(sizeof($this->category) > 0)
			{
				$category = "AND theme_id='{$this->category->id}'";
			}

			$sql = "SELECT * FROM {$CFG->DB_Prefix}gallery WHERE user='{$this->user}' {$category} ORDER BY id DESC";

			return getSQLArrayO($sql);
		}

		function getImage($int)
		{
			global $CFG;

			$sql = "SELECT * FROM {$CFG->DB_Prefix}gallery WHERE user='{$this->user}' AND id='{$int}'";

			return getSQLRowO($sql);
		}

		function insertImage()
		{
			global $CFG;

			$date = sqlDateNow();

			$sql = "INSERT INTO {$CFG->DB_Prefix}gallery (page_id, user, cdate, url) VALUES ('{$this->postid}', '{$this->user}', '{$date}', '{$this->image->url}')";

			$CFG->DB->query($sql);

			return $CFG->DB->lastId();
		}

		function updateImage()
		{
			global $CFG;

			if(sizeof($this->image) > 0)
			{
				$CFG->DB->query("UPDATE {$CFG->DB_Prefix}gallery SET name='{$this->image->name}', body='{$this->image->description}' WHERE user='{$this->user}' AND id='{$this->image->id}'");
			}
		}

		function deleteImage()
		{
			global $CFG;

			if(sizeof($this->image) > 0)
			{
				$CFG->DB->query("DELETE FROM {$CFG->DB_Prefix}gallery WHERE user='{$this->user}' AND id='{$this->image->id}' ");
			}
		}

		function createCategoty()
		{
			global $CFG;

			$sql = "INSERT INTO {$CFG->DB_Prefix}gallery_cats (user, name, img, description)
			 VALUES ('{$this->user}', '{$this->category->name}', '{$this->category->img}', '{$this->category->description}')";

			$CFG->DB->query($sql);
		}

		function getCategory($int)
		{
			global $CFG;

			$sql = "SELECT * FROM {$CFG->DB_Prefix}gallery_cats WHERE id='{$int}' AND user='{$this->user}'";

			return getSQLRowO($sql);
		}

		function updateCategory()
		{
			global $CFG;

			if(sizeof($this->category) > 0)
			{
				$sql = "UPDATE {$CFG->DB_Prefix}gallery_cats SET name='{$this->category->name}', img='{$this->category->img}', description='{$this->category->description}' WHERE id='{$this->category->id}' AND user='{$this->user}'";

				$CFG->DB->query($sql);
			}
		}





}


?>
