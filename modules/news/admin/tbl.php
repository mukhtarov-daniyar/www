 ﻿<?

	$rec = new DBRecord("{$CFG->DB_Prefix}news", $id, 1);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);

	$rec->AddField("page_id", $FIELD_DB->INT, 50, "Страница", $FIELD_HTML->HIDDEN, 10, 0);
	
	$rec->AddField("cdate", $FIELD_DB->VARCHAR, 0, "Дата", $FIELD_HTML->TEXT, 20, 1);

	$rec->AddField("manager_id", $FIELD_DB->INT, 0, "Заполняющий менеджер", $FIELD_HTML->SELECT, 80, 0);
	$rec->setValue("manager_id", 0);
	$rec->setLookupTree("manager_id", $CFG->DB_Prefix."users", "id", "name");
	
	$rec->AddField("email", $FIELD_DB->TEXT, 0, "Почтовый ящик компании, служебный, общий", $FIELD_HTML->TEXT, 80, 0);
	
	$rec->AddField("name_company", $FIELD_DB->VARCHAR, 0, "Название компании", $FIELD_HTML->TEXT, 80, 1);

	$rec->AddField("bought_id", $FIELD_DB->INT, 0, "Что купил или чем интересуется", $FIELD_HTML->SELECT, 10, 1);
	$rec->setValue("bought_id", 0);
	$rec->setLookupTree("bought_id", $CFG->DB_Prefix."bought", "id", "name");
	
	$rec->AddField("city_id", $FIELD_DB->INT, 0, "Город", $FIELD_HTML->SELECT, 10, 1);
	$rec->setValue("city_id", 0);
	$rec->setLookupTree("city_id", $CFG->DB_Prefix."city", "id", "name");

	$rec->AddField("name_director", $FIELD_DB->VARCHAR, 0, "ФИО генерального директора/учредителя/хозяина", $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("name_director_mobile", $FIELD_DB->VARCHAR, 0, "→&nbsp;Мобильный", $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("name_director_email", $FIELD_DB->VARCHAR, 0, "→&nbsp;Почта", $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("name_director_cdata", $FIELD_DB->VARCHAR, 0, "→&nbsp;Дата рождения", $FIELD_HTML->TEXT, 80, 0);
	
	
	$rec->AddField("name_client", $FIELD_DB->VARCHAR, 0, "ФИО покупающего/контактирующегозаинтересованного лица/", $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("name_client_mobile", $FIELD_DB->VARCHAR, 0, "→&nbsp;Мобильный", $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("name_client_email", $FIELD_DB->VARCHAR, 0, "→&nbsp;Почта", $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("name_client_cdata", $FIELD_DB->VARCHAR, 0, "→&nbsp;Дата рождения", $FIELD_HTML->TEXT, 80, 0);
	

	$rec->AddField("type_id", $FIELD_DB->INT, 0, "Новизна клиента", $FIELD_HTML->SELECT, 10, 0);
	$rec->setValue("type_id", 0);
	$rec->setLookupTree("type_id", $CFG->DB_Prefix."type", "id", "name");

	$rec->AddField("info", $FIELD_DB->VARCHAR, 0, "История покупок", $FIELD_HTML->TEXTAREA, 80, 0);
	
	$records["news"] = $rec;