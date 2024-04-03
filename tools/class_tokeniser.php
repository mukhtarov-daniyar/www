<?




class Tokeniser
{
	
	var $str = "";
	var $curr_pos = 0;
	var $len = 0;


	function Tokeniser($str)
	{
		$this->str = $str;
		$this->len = strlen($str);
		$this->curr_pos = 0;
	}

	function isTerminal($c)
	{
		$term = "<>=\"'/ ";
		for ($i=0; $i<strlen($term); $i++)
			if ($term[$i] == $c)
					return 1;
		return 0;
	}

	function getToken()
	{
		if ($this->curr_pos >= $this->len)
			return null;

		$c = $this->str[$this->curr_pos];
		if ($c!="/" && $this->isTerminal($c))
		{
			$this->curr_pos++;
			return $c;
		}
		$s = "";
		$exit = 0;
		while (!$exit && $this->curr_pos < $this->len)
		{
			$s .= $c;
			if ($c=="Ð" || $c=="Ñ")
			{
				$this->curr_pos++;
				$c = $this->str[$this->curr_pos];
				$s .= $c;
			}
			$this->curr_pos++;
			$c = $this->str[$this->curr_pos];
			if ($this->isTerminal($c))
			{
				$exit = 1;
				if ($c=='>' && $s=="/")
				{
					$s = "/>";
					$this->curr_pos++;
				}
			}
		}
		return "".$s;
	}

}	//-- end class Tokeniser

?>