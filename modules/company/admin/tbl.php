<?
	$rec = new DBRecord("{$CFG->DB_Prefix}docs", $id, 1);
	$rec->setWhere("btype='contact'");
	$rec->AddField("id", $FIELD_DB->KEY, 0, "id", $FIELD_HTML->KEY, 0, 0);
	$rec->AddField("page_id", $FIELD_DB->INT, 50, "Страница", $FIELD_HTML->HIDDEN, 10, 0);
//	$rec->AddField("page", $FIELD_DB->INT, 50, "Страница", $FIELD_HTML->TEXT, 2, 1);
//	$rec->AddField("num", $FIELD_DB->INT, 50, "Номер", $FIELD_HTML->TEXT, 2, 1);
//	$rec->AddField("cdate", $FIELD_DB->DATE, 50, "Дата", $FIELD_HTML->TEXT, 2, 1);
	$rec->AddField("title", $FIELD_DB->VARCHAR, 0, $CFG->Locale["system_name"], $FIELD_HTML->TEXT, 50, 1);
	$rec->AddField("body", $FIELD_DB->TEXT, 0, $CFG->Locale["system_text"], $FIELD_HTML->EDITOR, 15, 1);
//	$rec->AddField("img", $FIELD_DB->VARCHAR, 0, "image", $FIELD_HTML->FILE, 50, 1);
//	$rec->AddField("url", $FIELD_DB->VARCHAR, 0, "URL", $FIELD_HTML->URL, 50, 1);
	$rec->AddField("btype", $FIELD_DB->VARCHAR, 0, "ptype", $FIELD_HTML->HIDDEN, 50, 0);

	$records["contact"] = $rec;
?>