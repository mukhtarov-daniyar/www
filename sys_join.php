<?

	/* FUNCTION */
		
	
	$data = stripslashesarray($_POST);

	
	/* LOGIC */
	switch($data["user_act"]) 
	{
		case 'role' :

			if($CFG->FORM->setForm($data)) 
			{
				if($data["status"] == 1)
				{

					redirect($CFG->oPageInfo->_xcode."step_1");

				}
				elseif ($data["status"] == 2)
				{
					
					redirect($CFG->oPageInfo->_xcode."step_2");

				}
				else
				{
					redirect($CFG->USERPAGE);
				}
			
			}
			
		break;

		
		
		
		case 'save_user' :

			if($CFG->FORM->setForm($data)) 
			{
					if($CFG->USER->checkIssetData('login', $data["login"])) 
					{  
					
						 $CFG->STATUS->ERROR = true;
						 $CFG->STATUS->MESSAGE = $CFG->Locale['ps26'];
					} 
					else 
					{
						if($data['login'] && $data['passwd'] && $data['name'])
						{
							
							$userid = $CFG->USER->insertUserDataArray($data);
							
							$CFG->FORM->CLEARSTATUS();	
		
							$CFG->STATUS->OK = true;
							$CFG->STATUS->MESSAGE = $CFG->Locale['ps27'];
							
							redirect($CFG->AUTHPAGE);
						}
					}
			}
		break;
		
		case 'save_company' :

			if($CFG->FORM->setForm($data)) 
			{
					if($CFG->USER->checkIssetData('login', $data["login"])) 
					{  
					
						 $CFG->STATUS->ERROR = true;
						 $CFG->STATUS->MESSAGE = $CFG->Locale['ps26'];
					} 
					else 
					{
						if($data['login'] && $data['passwd'] && $data['name'])
						{
							
							$userid = $CFG->USER->insertUserJobDataArray($data);
							

	

							
							$CFG->FORM->CLEARSTATUS();	
		
							$CFG->STATUS->OK = true;
							$CFG->STATUS->MESSAGE = $CFG->Locale['ps27'];
							
							redirect($CFG->AUTHPAGE);
						}
					}
			}
		break;
		
		
		
	}
	
	
	/* INTERFACE */
	switch($CFG->_GET_PARAMS[0]) 
	{

	default :

		$_SESSION['xnum'] = "".rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);

		if($CFG->STATUS->OK != true) 
		{ 
			$o = $CFG->FORM->getFullForm();
			$e = $CFG->FORM->getFailInputs();
			
			include 'tpl/templates/system/register/register.tpl';
		}

	break;
	
	
	case 'step_1' :

		$_SESSION['xnum'] = "".rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);

		if($CFG->STATUS->OK != true) 
		{ 
			$o = $CFG->FORM->getFullForm();
			$e = $CFG->FORM->getFailInputs();
			
			include 'tpl/templates/system/register/register_step-1.tpl';
		}
	break;

	case 'step_2' :

		$_SESSION['xnum'] = "".rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);

		if($CFG->STATUS->OK != true) 
		{ 
			$o = $CFG->FORM->getFullForm();
			$e = $CFG->FORM->getFailInputs();
			
			include 'tpl/templates/system/register/register_step-2.tpl';
		}

	break;

	}
?>