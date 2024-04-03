<?
$NEWS = new News();
$GAL = new Gallery();

$id = $CFG->_GET_PARAMS[0];


if ($id <= 0)
{

		if($CFG->FORM->setForm($_GET))
			{
				$data = $CFG->FORM->getFullForm();
				$data['company'] = preg_replace('~[^0-9]+~','',$data['company']);

				$str = $NEWS->getData($data);

				if(!$data["search"] == '')
					{
						$namber = explode("*", $data["search"]);
						if(is_numeric($namber[1]) > 0 )
						{
							$z = $NEWS->getObject($CFG->oPageInfo->id, $namber[1]);
							if(count($z) > 0 )	{	redirect('/record/'.$z->id);}
						}
						else
						{
							$apost = apost($data["search"]);
							$search_where .= "	AND (name_company LIKE '%{$apost}%' OR history LIKE '%{$apost}%' OR contact LIKE '%{$apost}%' OR insta LIKE '%{$apost}%' OR info LIKE '%{$apost}%') ";
						}
					}

				if(!$data["cdatestart"] == '' && !$data["cdateoff"] == '' )
				{
					$search_where .= "AND (cdate >= '{$data["cdatestart"]} 00:00:00') AND (cdate <= '{$data["cdateoff"]} 00:00:00')";
				}
			}

		$s = $NEWS->getListCount(868, '', '', '', '', $str, $search_where);

		if($s > 0 || isset($_GET['ajax']))
		{
			if($_GET['ajax'] == 'yes')
			{
					include("./modules/news/tpl/filter_face.tpl");
					exit;
			}

			if($_GET["add"])
			{
				$s = $NEWS->getList(868, '', '', '', '', $str, $search_where);
				for ($y=0; $y<sizeof($s); $y++)
        {
            $respons[$y] .= $s[$y]->id;
				}
				$NEWS->updateArrayId($respons, $_GET["add"], $_GET["text"]);
			}
		}

		echo ' <h2><img alt="" src="/tpl/img/new/icon/2_red.png">Компаний (<span class="record_count">'.$s.'</span>)</h2>';
		include("./modules/news/tpl/filter.tpl");
		echo '<div class="white">';
			$pager = new Pager($s, 40);
			$l = $NEWS->getList(868, $pager->pp, $pager->start, '', '', $str, $search_where);
			include("./modules/news/tpl/listViewTable.tpl");
			include("_pager.php");
			include("./modules/news/tpl/options.tpl");
		echo '</div>';


}
elseif($CFG->_GET_PARAMS[1] == 'history')
{
		include("./modules/news/tpl/history.tpl");
}
else
	{
		$o = $NEWS->getObject($CFG->oPageInfo->id, $id);
		$NEWS->putCount($o->id, $o->view);
		$data = SelectDataRowOArray('users', $o->manager_id, 0, 0);
		$CFG->oPageInfo->html_title = $o->name_company;
		$vr = big_access('view_record');	//Видет скрытые записи access_id
		$data = SelectDataRowOArray('users', $o->manager_id, 0, 0);
		//$bg = $NEWS->BgAccessCompany($data->user_id);
		 if($CFG->USER->USER_ID)
		 {
			 	if($CFG->_GET_PARAMS[1] == "delete" && ($CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85))
				{
					$query  = "DELETE FROM {$CFG->DB_Prefix}news WHERE id='{$CFG->_GET_PARAMS[0]}'";
					$CFG->DB->query($query);
					$CFG->STATUS->MESSAGE = "Запись № ".$CFG->_GET_PARAMS[0]." удалена";
					redirect("/record/");
				}

				if($CFG->USER->USER_VIEW_LOADER == 1)  // Если грузчик
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = "У Вас недостаточно прав для чтения этой записи.";
					redirect($_SERVER['HTTP_REFERER']);
				}

			 	include("./modules/news/tpl/body.tpl");
		}
		else
		{
			$CFG->STATUS->OK = true;
			$CFG->STATUS->MESSAGE = "Для чтения информации на сайте, необходимо быть зарегистрированным.";
			redirect('/auth');
		}
}
