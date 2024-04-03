<?

if (!isset($_FAQ_class_definition))
{

$_FAQ_class_definition = "defined";

class FAQ
{

	function FAQ()
	{
		GLOBAL $CFG, $DB;

		$CFG->SEARCH[] = $this;

		$this->autoProcess();
	}


	function autoProcess()
	{
		GLOBAL $CFG;

		if ($_POST["faq_act"] == "save_faq")
		{
			$d = sqlDateNow();
			$sql = "INSERT INTO {$CFG->DB_Prefix}faq (page_id, cdate, name, email, city, phone, cat_id, quest, visible) VALUES ('{$_POST["page_id"]}', '{$d}', '{$_POST["name"]}', '{$_POST["email"]}', '{$_POST["city"]}', '{$_POST["phone"]}', '{$_POST["cat_id"]}', '{$_POST["quest"]}', '0')";
			$db->query($sql);
			sendForm("faq_new");
			redirect($CFG->REQUEST_URI);
		}
	}

	function search($words)
	{
		GLOBAL $CFG, $DB;

		if (is_array($words))
			$arr = $words;
		else
			$arr = explode(" ", ereg_replace("[,]", " ", $words));

		$q = "";
		for ($i=0; $i<sizeof($arr); $i++)
		{
			$word = addslashes($arr[$i]);
			$q .= " AND (F.quest LIKE '%{$word}%' OR F.answer LIKE '%{$word}%')";
		}

		$sql = "SELECT DISTINCT P.id, P.name FROM {$CFG->DB_Prefix}faq F LEFT OUTER JOIN {$CFG->DB_Prefix}pages P ON P.id=F.page_id WHERE P.sys_language='{$CFG->SYS_LANG}' AND F.sys_language='{$CFG->SYS_LANG}' AND P.visible='1' AND F.visible='1' {$q}";
		$l = getSQLArrayO($sql);		

		$res = array();
		for ($i=0; $i<sizeof($l); $i++)
		{
//			$res[$i]->name	= $l[$i]->name;
			$res[$i]->name	= striptags(getfullpagepath($l[$i]->id));
			$res[$i]->url	= "index.php?page={$l[$i]->id}";
		}

		return $res;
	}

}// end of class FAQ

} // end of the definition checking

	
?>