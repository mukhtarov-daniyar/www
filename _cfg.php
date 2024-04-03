<?
session_write_close();

		//$CFG = new stdClass();
		//$CFG->CATALOG = new stdClass();

		//-------------- Database parameters ---------------------------------------
		$CFG->DB_type	= "mysqli";
		$CFG->DB	= null;			//- !Do not modify

		$CFG->DB_Host		= "localhost";			//- DataBase Host
		$CFG->DB_Name	= "test";			//- DataBase name
		$CFG->DB_User		= "root";				//- DataBase user
		$CFG->DB_Password	= "";				//- DataBase user's pasword
		$CFG->DB_Prefix	= "my_";

		$CFG->debug	= false;            //- Debug options
		//-------------- /Database parameters ---------------------------------------
		$CFG->MULTILANG = 0;
		$CFG->MULTILANG_TWEEN = 0;
		$CFG->EMULATE_UTF8 = 0;
		$CFG->CATALOG->USE_CART = 1;

		$CFG->ReCaptcha_private	= "6LfBmtEZAAAAAGpLNyQFNV8wO4zC8gekezQas4h9";
		$CFG->ReCaptcha_site	= "6LfBmtEZAAAAAHvAz01_8xxyzFhvSvtKA0M6YBv3";


		//Логин и пароль первой авторизации
		$CFG->CRM_LOGIN	= "signcrm";
		$CFG->CRM_PASS	= "*signcrm320#";




	$_cfg_defined = 1;

@session_start();
