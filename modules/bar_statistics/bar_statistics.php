<?
require (__DIR__.'/class/'.$CFG->oPageInfo->xcode.'.php');

$res = new Bar();


$StartCdate = $_GET['year'].'-'.$_GET['month'];

if ($CFG->_GET_PARAMS[0] == 'aluminum_rim')
{
	$asi = $res->CalculationTableARiM1C($StartCdate);
	include ('tpl/aluminum_rim.tpl');
}
elseif ($CFG->_GET_PARAMS[0] == 'pavlodar_asem')
{
	$asi = $res->CalculationTablePavlodarAsem1C($StartCdate);
	include ('tpl/pavlodar_asem.tpl');
}
elseif ($CFG->_GET_PARAMS[0] == 'last')
{
	$res->CopyNewPost();
	exit;
}
elseif(isset($_GET['year']) == NULL)
{
	redirect( '/'.$CFG->oPageInfo->xcode.'/?year='.date('Y').'&month='.date('m') );
}
elseif(isset($_POST['act']) != NULL && $_POST['act'] == 'on')
{
	$res->UpNewPost($_POST);
	exit;
}
else
{
	$arr = $res->SelectLast();
	$asi = $res->CalculationTable1C($StartCdate);
	include ('tpl/table.tpl');
}
