<?


	$testing_satart = microtime(true);

	ini_set('error_reporting', E_ERROR   ); //	E_ALL		E_ERROR
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	header('Content-type: text/html; charset=utf-8');
	setlocale (LC_ALL, "ru_RU.UTF-8");
	set_time_limit(600);

	require_once("_properties.php");



	//$CFG->DB->query(" SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')); ");


	$CFG->fromIndex = true;
	$CFG->SITE = new Init();
	//$CFG->SITE->setDisableTemplate("_disable.php");
	//$CFG->SITE->profileuser();

	//echo ' | Время выполнения скрипта: '.round(microtime(true) - $testing_satart, 4).' сек.';
	//exit;

	$CFG->SITE->initialize();

 	$CFG->STATUS->__destruct;
 	$CFG->FORM->__destruct;
