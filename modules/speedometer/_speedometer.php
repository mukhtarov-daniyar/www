<?

switch ($CFG->_GET_PARAMS[0]) 
{
    case 'tovar':
        include('tpl/tovar.tpl');
     break;
	
    default:
       include('tpl/default.tpl');
	break;
}