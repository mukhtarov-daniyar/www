<?

$SALARY = new Salary();

$id = $CFG->_GET_PARAMS[0];

switch ($id)
{
	//Кассы компаний
	case 'cashbox':
		$l = $SALARY->getListCashbox();
		include("tpl/cashbox.tpl");
	break;

	//Все юзеры 1С
	case 'all':
		$l = $SALARY->getList($CFG->USER->USER_DIRECTOR_ID, $CFG->_GET_PARAMS);
		include("tpl/tovar_array.tpl");
	break;


	default:
		$l = $SALARY->getList1C($_GET['month'], $_GET['year']);
		include("tpl/zpSI.tpl");
	break;
}
