<?
	$Locale = array();
if ($CFG->SYS_LANG_ADMIN=='ru')
{
	$Locale["date"] = "Дата публикации";
	$Locale["page"] = "Страница";
	$Locale["descr"] = "Описание";
	$Locale[""] = "";
}
else
{
	$Locale["date"] = "Date";
	$Locale["page"] = "Page";
	$Locale["descr"] = "Description";
}



	$rec = new DBRecord("{$CFG->DB_Prefix}docs", $id, 1);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("page_id", $FIELD_DB->INT, 50, $Locale["page"], $FIELD_HTML->HIDDEN, 10, 0);
//	$rec->AddField("intro", $FIELD_DB->INT, 0, "Анонс", $FIELD_HTML->TEXTAREA, 5, 1);
//	$rec->AddField("url", $FIELD_DB->VARCHAR, 100, $CFG->Locale["system_image"], $FIELD_HTML->FILE, 50, 0);
	$rec->AddField("title", $FIELD_DB->VARCHAR, 100, $CFG->Locale["system_name"], $FIELD_HTML->TEXT, 100, 1);
	$rec->AddField("body", $FIELD_DB->TEXT, 65500, $Locale["descr"], $FIELD_HTML->EDITOR, 10, 0);
	$rec->AddField("btype", $FIELD_DB->VARCHAR, 0, "ptype", $FIELD_HTML->HIDDEN, 50, 0);

	$records["homepage_events"] = $rec;

//------------------

	$rec = new DBRecord("{$CFG->DB_Prefix}docs", $id, 1);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("page_id", $FIELD_DB->INT, 50, $Locale["page"], $FIELD_HTML->HIDDEN, 10, 0);
	$rec->AddField("title", $FIELD_DB->VARCHAR, 100, $CFG->Locale["system_name"], $FIELD_HTML->TEXT, 100, 1);
	$rec->AddField("url", $FIELD_DB->VARCHAR, 100, $CFG->Locale["Ссылка"], $FIELD_HTML->TEXT, 50, 0);
	$rec->AddField("intro", $FIELD_DB->INT, 0, "Описание", $FIELD_HTML->TEXTAREA, 5, 1);
	$rec->AddField("btype", $FIELD_DB->VARCHAR, 0, "ptype", $FIELD_HTML->HIDDEN, 50, 0);

	$records["homepage_links"] = $rec;

//------------------


//------------------

	$rec = new DBRecord("{$CFG->DB_Prefix}docs", $id, 1);
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("page_id", $FIELD_DB->INT, 50, $Locale["page"], $FIELD_HTML->HIDDEN, 10, 0);
	$rec->AddField("cdate", $FIELD_DB->DATE, 100, "Дата проведения", $FIELD_HTML->TEXT, 50, 1);
	$rec->AddField("title", $FIELD_DB->VARCHAR, 100, $CFG->Locale["system_name"], $FIELD_HTML->TEXT, 100, 1);
	$rec->AddField("url", $FIELD_DB->VARCHAR, 100, "На главную", $FIELD_HTML->CHECKBOX, 50, 1);
	$rec->AddField("intro", $FIELD_DB->INT, 0, "Анонс", $FIELD_HTML->TEXTAREA, 5, 1);
	$rec->AddField("body", $FIELD_DB->TEXT, 65500, $Locale["descr"], $FIELD_HTML->EDITOR, 10, 0);
	$rec->AddField("btype", $FIELD_DB->VARCHAR, 0, "ptype", $FIELD_HTML->HIDDEN, 50, 0);

	$records["homepage_anons"] = $rec;

//------------------


?>