<?

require_once("modules/marketing_plan/class/marketing_plan.php"); 


$MARKET = new Market();

if($CFG->_GET_PARAMS[0] == 'data')
{
	
	if($CFG->_GET_PARAMS[1] > 0)
	{
		include('tpl/step-2.tpl');		
	}
	else
	{	
		$MARKET->getDount($CFG->_POST_PARAMS);
	}
}
else
{
	include('tpl/body.tpl');	
}