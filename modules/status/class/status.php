<?
class Status {
	
	private $MESSAGE;
	
	public $OK;
	public $ERROR;

	function __construct() 
	{
		($_SESSION["STATUS"][0]) ? $this->OK = true : $this->OK = false;
		($_SESSION["STATUS"][1]) ? $this->ERROR = true : $this->ERROR = false;
								   $this->MESSAGE = unserialize($_SESSION["STATUS"][2]);
	}

	function __destruct() 
	{
		   ($this->OK) ? $_SESSION["STATUS"][0] = 1 : $_SESSION["STATUS"][0] = 0;
		($this->ERROR) ? $_SESSION["STATUS"][1] = 1 : $_SESSION["STATUS"][1] = 0;
						 $_SESSION["STATUS"][2] = serialize($this->MESSAGE);	
	}	
	
	function __set($key, $value) 
	{
		if($key == "MESSAGE")
		{
		   $this->setMessage($value);
		}
	}
	
	private function setMessage($str)
	{
		for($i=0;$i<sizeof($this->MESSAGE);$i++)
		{
			if($str == $this->MESSAGE[$i])
			{
				return;
			}
		}
		
		$this->MESSAGE[] = $str;
	}

	function GETSTATUSJSON()
	{
		global $CFG;
	
		$JSON['error'] = $this->ERROR;
		$JSON['success'] = $this->OK;
		$JSON['message'] = $this->MESSAGE;
		
		/* FOR FORMS */
		if(sizeof($CFG->FORM->getFullForm()) > 0)
		{
			$JSON['inputs'] = $this->INPUTS;
			$JSON['form'] = $this->FORM;
		}
		
		return json_encode($JSON);
	}
	
	function SHOWSTATUS() 
	{
		require_once("_status.php");	
	}
	
	function CLEARSTATUS() 
	{
		unset($_SESSION["STATUS"]);
	
		unset($this->OK);
		unset($this->ERROR);
		unset($this->MESSAGE);
	}
}

?>