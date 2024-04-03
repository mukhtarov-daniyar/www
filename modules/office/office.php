<?
$OFFICE = new Office();
$NEWS = new News();
$GAL = new Gallery();

$id = $CFG->_GET_PARAMS[0];

if ($id <= 0)
{
		$search_where = $OFFICE->groupUserOffice('users', $CFG->USER->USER_ID);

		if($CFG->FORM->setForm($_GET))
			{
				$data = $CFG->FORM->getFullForm();
				$str = $OFFICE->getData($data);

				if(!$data["search"] == '')
					{
						$namber = explode("*", $data["search"]);

						if(is_numeric($namber[1]) > 0 )
						{
							$z = $OFFICE->getObject($CFG->oPageInfo->id, $namber[1]);
							if(count($z) > 0 )
							{
								redirect('/office/'.$z->id);

							}
							else
							{
								$CFG->STATUS->ERROR = true;
								$CFG->STATUS->MESSAGE = 'Запись с таким номером не найдена.';
								redirect($_SERVER["HTTP_REFERER"]);
							}
						}
						else
						{
							$apost = apost($data["search"]);
							$search_where .= " AND (name_company LIKE '%{$apost}%' OR info LIKE '%{$apost}%')";
						}
					}
			}

		$cnt = $OFFICE->getCount(976,"", $str, $search_where, 0);

		echo ' <h2><img alt="" src="/tpl/img/new/icon/2_red.png">Служебных записей ('.$cnt.')</h2>';

		include("./modules/office/tpl/filter.tpl");

		echo '<div class="white">';

		$pager = new Pager($cnt, 40);

		$l = $OFFICE->getList(976, $pager->pp, $pager->start, '', '', $str, $search_where, 0);

		$count = mb_strlen(trim($_GET["search"]), 'UTF-8');
		if($count > 0)
		{
			include("./modules/office/tpl/search.tpl");
		}

		include("./modules/office/tpl/listViewTable.tpl");

		include($_SERVER['DOCUMENT_ROOT'] . "/_pager.php");

		echo '<a href="/profile/add_office/" class="mailogo">Добавить запись</a>';

		echo '</div>';

}
else
{
	$o = $OFFICE->getObject($CFG->oPageInfo->id, $id);
	$OFFICE->putCount($o->id, $o->view);
	$CFG->oPageInfo->html_title = $o->name_company;

		$ac = big_access('view_prize'); //Видит примирование
		$zp = big_access('view_factories'); //Видит заводы
		$vr = big_access('view_record');	//Видет скрытые записи access_id

		if($CFG->USER->USER_ID)
		{
			if(
				($o->type_company_id == 2147483647 && $ac == false) ||
				($o->type_company_id == 10011935 && $zp == false) ||
				($o->access_id == 1 && $vr == false) ||
				($o->manager_id != $CFG->USER->USER_ID && $CFG->USER->USER_VIEW_LOADER == 1)
				)
			{
				if($o->manager_id !=  $CFG->USER->USER_ID )
				{
					$CFG->STATUS->OK = true;
					$CFG->STATUS->MESSAGE = "У Вас недостаточно прав для чтения этой записи.";
					redirect($_SERVER['HTTP_REFERER']);
				}
				else
				{

					include("./modules/office/tpl/body.tpl");
				}

			}
			else
			{
				include("./modules/office/tpl/body.tpl");
			}

		}
		else
		{
			$CFG->STATUS->OK = true;
			$CFG->STATUS->MESSAGE = "Для чтения информации на сайте, необходимо быть зарегистрированным.";
			redirect('/');
		}












}
