<?
//	include("_properties.php");

	$sql = "SELECT id FROM {$CFG->DB_Prefix}pages WHERE {$CFG->lang} parent_id='{$CFG->pid}' AND visible='1' ORDER BY pos LIMIT 0, 1";

	$id = 1 * getSQLField($sql);

	redirect( getFullXCodeByPageId($id) );


	die("<script>location.href=\"".getFullXCodeByPageId($id)."\";</script>");

?>