<?

if (!isset($_MList_class_definition))
{

$_MList_class_definition = "defined";

class MList
{

	function MList()
	{
		GLOBAL $CFG, $DB;

		$this->autoProcess();
	}


	function autoProcess()
	{
		GLOBAL $CFG;

		if ($_POST["mlist_act"] == "ml_subscr")
		{
			$message_done = 1;
			$mid = 1 * $_GET["listID"];
			$db->query("INSERT INTO {$CFG->DB_Prefix}ml_list (email, ml_id, visible) VALUES ('{$_POST["email"]}', '{$mid}', '0')");
			redirect($CFG->REQUEST_URI);
		}

		if ($_GET["mlist_act"] == "ml_remove")
		{
			$message_done = 2;
			$mid = 1 * $_GET["listID"];
			$db->query("DELETE FROM {$CFG->DB_Prefix}ml_list WHERE email='{$_GET["email"]}' AND ml_id='{$mid}'");
			$_GET["act"] = "subscr";
			redirect($CFG->REQUEST_URI);
		}

		if ($_GET["mlist_act"] == "ml_confirm")
		{
			$message_done = 2;
			$mid = 1 * $_GET["listID"];
			$db->query("UPDATE {$CFG->DB_Prefix}ml_list SET visible='1' WHERE email='{$_GET["email"]}' AND ml_id='{$mid}'");
			$_GET["act"] = "subscr";
			redirect($CFG->REQUEST_URI);
		}
	}

}// end of class MList

} // end of the definition checking

	
?>