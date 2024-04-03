<?


if(is_numeric($CFG->_GET_PARAMS[0]) > 0)
{
	include('tpl/step-1.tpl');
}
else
{
	if($CFG->_GET_PARAMS[0] == 'record' )
	{

		$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}alarm_whatsapp WHERE status = 0 AND page_id = '{$CFG->_GET_PARAMS[1]}'  AND user_id = {$CFG->USER->USER_ID} ");
		
		for ($i=0; $i<sizeof($sql); $i++)
		{
			$query  = "UPDATE {$CFG->DB_Prefix}alarm_whatsapp SET status = 1  WHERE id='{$sql[$i]->id}'";
			$CFG->DB->query($query);
			
		}
		redirect('/search/*'.$CFG->_GET_PARAMS[1]);
	}
}



