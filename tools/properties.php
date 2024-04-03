<?php


	require_once("db_mysqli.php");
	require_once("tools.php");
	require_once("common.php");

	define('DB_NAME', $CFG->DB_Name);
	define('DB_USER', $CFG->DB_User);
	define('DB_PASS', $CFG->DB_Password);
	define('DB_HOST', $CFG->DB_Host);


$CFG->DB = new DB();
