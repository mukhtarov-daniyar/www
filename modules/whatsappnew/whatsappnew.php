<?


if(is_numeric($CFG->_GET_PARAMS[0]) > 0)
{
	include('tpl/step-1.tpl');
}


if($CFG->_GET_PARAMS[0] == 'static')
{
	include('tpl/step-2.tpl');
}


if($CFG->_GET_PARAMS[0] == 'static_ajax')
{
	include('tpl/static_ajax.tpl');
	exit;
}

if($CFG->_GET_PARAMS[0] == 'edit')
{
	include('tpl/step-3.tpl');
}


if($CFG->_GET_PARAMS[0] == 'load_activ')
{
	if(isset($_POST["id"]) && $_POST["id"] > 0)
	{
		 $nr_user = ' AND namber = '.$_POST[id];
	}
	$count = $_POST["num"];

	$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp WHERE visible='1' {$nr_user} order by id DESC LIMIT {$count},10 ");

	 for ($i=0; $i<sizeof($sql); $i++)
	 {
		 include($_SERVER['DOCUMENT_ROOT'].'/modules/whatsappnew/tpl/tr_item.php');
	 }
 exit;
}


if($CFG->_GET_PARAMS[0] == 'options')
{
	include('tpl/step-4.tpl');
}


if($CFG->_GET_PARAMS[0] == 'start')
{
	include('tpl/step-5.tpl');
}

if($CFG->_GET_PARAMS[0] == 'wp_options')
{
	$id = $_POST["id"];
	$name = $_POST["name"];
	$namber = $_POST["namber"];
	$rand_start = $_POST["rand_start"];
	$rand_off = $_POST["rand_off"];

	$time_start_hour = $_POST["time_start_hour"];
	$time_start_minute = $_POST["time_start_minute"];
	$time_off_hour = $_POST["time_off_hour"];
	$time_off_minute = $_POST["time_off_minute"];

	for($r=0; $r<sizeof($id); $r++)
	{
		$que  = "UPDATE {$CFG->DB_Prefix}tmp_whatsapp_namber SET name = '{$name[$r]}', namber = '{$namber[$r]}', namber = '{$namber[$r]}',  rand_start = '{$rand_start[$r]}',  rand_off = '{$rand_off[$r]}', time_start_hour = '{$time_start_hour[$r]}', time_start_minute = '{$time_start_minute[$r]}', time_off_hour = '{$time_off_hour[$r]}', time_off_minute = '{$time_off_minute[$r]}' WHERE id='{$id[$r]}'
		";
		$CFG->DB->query($que);
	}

	exit;
}
