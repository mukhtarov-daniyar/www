<?
	$rec = new DBRecord("{$CFG->DB_Prefix}pages", $ADM->_PAGE_ID, 1);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	if ($id>0 && $id<100)
	{
		$rec->AddField("parent_id", $FIELD_DB->INT, 0, "Parent", $FIELD_HTML->HIDDEN, 5, 0);
		$rec->AddField("xcode", $FIELD_DB->VARCHAR, 255, "Код", $FIELD_HTML->TEXT, 80, 0);
		$rec->AddField("tmpl_u", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_pageType"], $FIELD_HTML->HIDDEN, 40, 0);
		$rec->AddField("name", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_name"], $FIELD_HTML->TEXT, 80, 1);
		$rec->AddField("menu_name", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_nameInMenu"], $FIELD_HTML->TEXT, 20, 1);
		$rec->AddField("html_title", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_htmlT"], $FIELD_HTML->TEXT, 80, 0);
	}
	else
	{
	$rec->AddField("parent_id", $FIELD_DB->INT, 0, "Parent", $FIELD_HTML->SELECT, 5, 1);
	$rec->AddField("tmpl_u", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_pageType"], $FIELD_HTML->SELECT, 40, 0);
	$rec->AddField("xcode", $FIELD_DB->VARCHAR, 255, "Код", $FIELD_HTML->URL, 80, 0);
	$rec->AddField("name", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_name"], $FIELD_HTML->TEXT, 80, 1);
	$rec->AddField("menu_flag", $FIELD_DB->INT, 0, $CFG->Locale["system_inMenu"], $FIELD_HTML->CHECKBOX, 0, 1);
	$rec->AddField("menu_name", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_nameInMenu"], $FIELD_HTML->TEXT, 20, 1);
	
	
	$rec->AddField("tmpl_view", $FIELD_DB->VARCHAR, 255, "Шаблон ListView", $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("tmpl_body", $FIELD_DB->VARCHAR, 255, "Шаблон BodyView", $FIELD_HTML->TEXT, 80, 0);
	
	$rec->AddField("tmpl_a", $FIELD_DB->VARCHAR, 255, "Картинка", $FIELD_HTML->FILE, 40, 1);
	$rec->AddField("access", $FIELD_DB->INT, 5, $CFG->Locale["system_access"], $FIELD_HTML->SELECT, 40, 0);
	
	$rec->AddField("html_title", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_htmlT"], $FIELD_HTML->TEXT, 80, 0);
	$rec->AddField("html_keywords", $FIELD_DB->TEXT, 2000, $CFG->Locale["system_htmlK"], $FIELD_HTML->TEXTAREA, 5, 0);
	$rec->AddField("html_description", $FIELD_DB->TEXT, 2000, $CFG->Locale["system_htmlD"], $FIELD_HTML->TEXTAREA, 5, 0);
	$rec->AddField("moderator", $FIELD_DB->VARCHAR, 255, $CFG->Locale["system_moderatorUList"], $FIELD_HTML->SELECTM, 5, 0);

	}

	$records["sys_pages"] = $rec;
?>