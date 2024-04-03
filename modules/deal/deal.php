<?
$DEAL = new Deal();
$NEWS = new News();
$GAL = new Gallery();

$id = $CFG->_GET_PARAMS[0];

if ($id <= 0)
{

		$search_where = $DEAL->groupUserOffice('users', $CFG->USER->USER_ID);

		if($CFG->FORM->setForm($_GET))
			{
				$data = $CFG->FORM->getFullForm();
				$str = $DEAL->getData($data);


				if(!$data["search"] == '')
					{
						$namber = explode("*", $data["search"]);

						if(is_numeric($namber[1]) > 0 )
						{
							$z = $DEAL->getObject($CFG->oPageInfo->id, $namber[1]);
							if(count($z) > 0 )
							{
								redirect('/deal/'.$z->id);

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

							$search_where .= "
										   AND (name_company LIKE '%{$apost}%' OR info LIKE '%{$apost}%') ";
						}
					}
			}


		$cnt = $DEAL->getCount(1000,"", $str, $search_where, 0);

		echo ' <h2><img alt="" src="/tpl/img/new/icon/2_red.png">Сделок в базе ('.$cnt.')</h2>';

		include("./modules/deal/tpl/filter.tpl");

		echo '<div class="white">';

		$s = $DEAL->getList(1000, '', '', '', '', $str, $search_where);

		$pager = new Pager($cnt, 40);

		$l = $DEAL->getList(1000, $pager->pp, $pager->start, '', '', $str, $search_where, 0);

		$count = mb_strlen(trim($_GET["search"]), 'UTF-8');
		if($count > 0)
		{
			include("./modules/deal/tpl/search.tpl");
		}

		include("./modules/deal/tpl/listViewTable.tpl");

		include($_SERVER['DOCUMENT_ROOT'] . "/_pager.php");

		echo '</div>';

}
else
{
	$o = $DEAL->getObject($CFG->oPageInfo->id, $id);
	$DEAL->putCount($o->id, $o->view);
	$CFG->oPageInfo->html_title = $o->name_company;

		$ac = big_access('view_prize'); //Видит примирование

		$ar = big_access_record($id); //Доступ к сделки

		$da = big_access('view_deal_access');

		if(($CFG->USER->USER_ID == 1) || ($CFG->USER->USER_ID == 85) || ($CFG->USER->USER_ID == 536 || $da == true))
		{
			$ar = 1;
		}

		$zp = big_access('view_factories'); //Видит заводы
		$vr = big_access('view_record');	//Видет скрытые записи access_id
		$da = big_access('view_deal_access');	//Видет скрытые записи access_id

		if($CFG->USER->USER_ID)
		{
			if(
				($o->type_company_id == 2147483647 && $ac == false) ||
				($o->type_company_id == 10011935 && $zp == false) ||
				($o->access_id == 1 && $vr == false) ||
				($o->manager_id != $CFG->USER->USER_ID && $CFG->USER->USER_VIEW_LOADER == 1) ||
				($ar != 1)
				)
			{
				$CFG->STATUS->OK = true;
				$CFG->STATUS->MESSAGE = "У Вас недостаточно прав для чтения этой записи.";
				redirect($_SERVER['HTTP_REFERER']);
			}
			else
			{
				include("./modules/deal/tpl/body.tpl");
			}
		}

}
