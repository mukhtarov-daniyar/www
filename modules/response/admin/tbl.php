 ﻿<?

	$rec = new DBRecord("{$CFG->DB_Prefix}news", $id, 1);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);

	$rec->AddField("page_id", $FIELD_DB->INT, 50, "Страница", $FIELD_HTML->HIDDEN, 10, 0);
	
	$rec->AddField("cdate", $FIELD_DB->VARCHAR, 0, "Дата", $FIELD_HTML->TEXT, 20, 1);

	$rec->AddField("name", $FIELD_DB->VARCHAR, 0, "Заголовок", $FIELD_HTML->TEXT, 80, 1);
	
	$rec->AddField("user_id", $FIELD_DB->INT, 0, "User", $FIELD_HTML->SELECT, 10, 0);
	$rec->setValue("user_id", 0);
	$rec->setLookupTree("user_id", $CFG->DB_Prefix."users", "id", "name");
	
	$rec->AddField("company_id", $FIELD_DB->INT, 0, "Компания", $FIELD_HTML->SELECT, 10, 0);
	$rec->setValue("company_id", 0);
	$rec->setLookupTree("company_id", $CFG->DB_Prefix."company", "id", "name");
	
	$rec->AddField("sphere_id", $FIELD_DB->INT, 0, "Профобласть", $FIELD_HTML->SELECT, 10, 1);
	$rec->setValue("sphere_id", 0);
	$rec->setLookupTree("sphere_id", $CFG->DB_Prefix."sphere", "id", "name");
	
	$rec->AddField("city_id", $FIELD_DB->INT, 0, "Город", $FIELD_HTML->SELECT, 10, 1);
	$rec->setValue("city_id", 0);
	$rec->setLookupTree("city_id", $CFG->DB_Prefix."city", "id", "name");
		
	$rec->AddField("salary_id", $FIELD_DB->INT, 0, "Зарплата", $FIELD_HTML->SELECT, 0, 1);
	$rec->setValueList("salary_id", array(0=>$CFG->Locale["ps37"], 1=>$CFG->Locale["ps38"], 2=>$CFG->Locale["ps39"], 3=>$CFG->Locale["ps40"], 4=>$CFG->Locale["ps41"], 5=>$CFG->Locale["ps42"], 6=>$CFG->Locale["ps43"]), 0);
	$rec->setValue("salary_id", 0);
	
	$rec->AddField("experience_id", $FIELD_DB->INT, 0, "Опыт работы", $FIELD_HTML->SELECT, 0, 1);
	$rec->setValueList("experience_id", array(0=>$CFG->Locale["ps51"], 1=>$CFG->Locale["ps52"], 2=>$CFG->Locale["ps53"], 3=>$CFG->Locale["ps54"]), 	0);
	$rec->setValue("experience_id", 0);
	
	$rec->AddField("employment_id", $FIELD_DB->INT, 0, "Занятость", $FIELD_HTML->SELECT, 0, 1);
	$rec->setValueList("employment_id", array(0=>$CFG->Locale["ps45"], 1=>$CFG->Locale["ps46"], 2=>$CFG->Locale["ps47"], 3=>$CFG->Locale["ps48"], 4=>$CFG->Locale["ps49"]), 	0);
	$rec->setValue("employment_id", 0);




	$rec->AddField("body", $FIELD_DB->TEXT, 0, "Описание", $FIELD_HTML->EDITOR, 10, 0);
	
	$records["news"] = $rec;
	
	
?>