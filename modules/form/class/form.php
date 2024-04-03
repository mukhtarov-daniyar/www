<?

	class Form
	{
		var $FORM;
		var $INPUTS;

		function __construct()
		{
		   $this->FORM = unserialize($_SESSION["FORM"][0]);
		   $this->INPUTS = unserialize($_SESSION["FORM"][1]);
		}

		function __destruct()
		{
			 $_SESSION["FORM"][0] = serialize($this->FORM);
			 $_SESSION["FORM"][1] = serialize($this->INPUTS);
		}

		function getFullForm()
		{
			return $this->FORM;
		}

		function getFailInputs()
		{
			return $this->INPUTS;
		}

		function setExeptions(array $arr)
		{
			$this->exeptions = $arr;
		}

		function setForm($data)
		{
			global $CFG;

			$err = 0;
			$cssError = 'id="input_error"';

			$this->saveForm($data);


			foreach($data as $key => $value)
			{
				if( in_array($key, $this->exeptions) )
				{
					continue;
				}

				switch($key)
				{


					case 'email' :
					/*
					   if(!$value || strlen($value) < 3)
					   {
							$input[$key] = $cssError; $CFG->STATUS->MESSAGE = $CFG->Locale["v1"];
							$err++;
					   }
					   else
					   {
							if(!$this->checkRegExEmail($value))
							{
								$input[$key] = $cssError; $CFG->STATUS->MESSAGE = $CFG->Locale["v2"];
								$err++;
							}
						}
					*/
					break;




					case 'passwd' :

					   if(!$value || strlen($value) < 5)
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v3"];
							$err++;
						}
					break;


					case 'passwd2' :
						if($data['passwd'] != $data['passwd2'])
						{
							$input['passwd2'] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v4"];
							$err++;
						}
					break;

					case 'captcha' :
						if(!is_numeric($value) || $value !== $_SESSION['xnum'])
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v6"];
							$err++;
						}

					break;

					case 'lastname' :
						if(!preg_match('/([a-zA-Zа-яА-Я])/', $value))
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v7"];
							$err++;
						}
					break;

/*
					case 'info' :

						$count = mb_strlen(trim($value), 'UTF-8');

						if($count < 80)
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = "Введите больше текста в заметке о клиенте.";
							$err++;
						}
					break;

*/
					case 'text' :
					//case 'name' :
					case 'name_company' :
						if($value == '')
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v8"];
							$err++;
						}
					break;

					/*
					case 'city_id' :
					case 'city' :
						if($value <! 0)
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = 'Укажите город';
							$err++;
						}
					break;


					case 'type_company_id' :
					case 'type_company' :
						if($value > 0)
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = 'Укажите тип компании';
							$err++;
						}
					break;
					*/


					case 'phone' :

						if(!preg_match('/[+][7] [0-9]{3} [0-9]{3}[-][0-9]{2}[-][0-9]{2}$/i', $value))
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v9"];
							$err++;
						}

					break;


					case 'date' :
						if(!preg_match('/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/', $value))
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v7"];
							$err++;
						}
					break;

					case 'bdate' :
						if(!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $value))
						{
							$input[$key] = $cssError;
							$CFG->STATUS->MESSAGE = $CFG->Locale["v7"];
							$err++;
						}
					break;


					default :


					break;
				}
			}

			$this->INPUTS = $input;


			if($err > 0)
			{

				$this->ERROR = true;

				return false;

			}
			else
			{
				$this->OK = true;

				return true;
			}
		}





		private function saveForm($data)
		{
			foreach($data as $key => $value)
			{
				 //$value = mb_ereg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $value);
			   $form[$key] = $value;
			}
			$this->FORM = $form;
		}

		static function checkRegExEmail($email)
		{
			if(preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $email))
			{
				return true;
			}
			else
			{
			   return false;
			}
		}

		function CLEARSTATUS()
		{
			unset($_SESSION["FORM"]);

			unset($this->FORM);
			unset($this->INPUTS);
		}
	}
?>
