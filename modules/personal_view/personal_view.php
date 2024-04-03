<?
$CFG->SITE->header = "/tpl/templates/personal/_header.php";
$CFG->SITE->footer = "/tpl/templates/personal/_footer.php";


$NEWS = new News();


$o = $NEWS->getObject(868, $CFG->USER->PERSONAL_PAGE);
		


include("./tpl/templates/personal/body.tpl");

echo "<br><br><br>";