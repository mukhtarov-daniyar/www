<?

$EDRIVE = new Edrive();
$NEWS = new News();

$id = $CFG->_GET_PARAMS[0];

if($id == 'add')
{
	if($CFG->_GET_PARAMS[1] == 'up')
	{
		$EDRIVE->getUP($_POST);
	}

	if($CFG->_GET_PARAMS[1] == 'dell')
	{
		$EDRIVE->getDEL($CFG->_GET_PARAMS[2]);
		exit;
	}

	if($CFG->_GET_PARAMS[1] == 'dell_car')
	{
		$EDRIVE->getDELCAR($CFG->_GET_PARAMS[2]);
		exit;
	}

	if($CFG->_GET_PARAMS[1] == 'up_model')
	{
		$EDRIVE->up_model($_POST);
		exit;
	}

	if($CFG->_GET_PARAMS[1] == 'up_car')
	{
		$EDRIVE->up_car($_POST);
		exit;
	}
	include("./modules/edrive/tpl/add.tpl");
}
elseif($id == 'edit')
{
	if($CFG->_GET_PARAMS[1] > 0)
	{
		$sp = $EDRIVE->getListID($CFG->_GET_PARAMS[1]);
		include("./modules/edrive/tpl/edit.tpl");
	}
	else
	{
		$EDRIVE->getListIDUP($_POST);
		exit;
	}
}
elseif($id == 'model')
{
	include("./modules/edrive/tpl/model.tpl");
}
elseif($id == 'marka')
{
	include("./modules/edrive/tpl/marka.tpl");
}
else
{
	if ($id <= 0)
	{
		if($CFG->FORM->setForm($_GET))
		{
			$data = $CFG->FORM->getFullForm();
			$str = $EDRIVE->getData($data);
		}

		$cnt = $EDRIVE->getCount("my_edrive", $str, $search_where);
		$l = $EDRIVE->getList("my_edrive", $str, $search_where);

		if($_GET['exel'] == 1)
		{
			$EDRIVE->ExportExel($l);
			exit;
		}

		include("./modules/edrive/tpl/listViewTable.tpl");
	}

}
