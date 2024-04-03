<? if($CFG->USER->USER_ID == 244 || $CFG->USER->USER_ID == 311 || $CFG->USER->USER_ID == 310) { ?>
<style>
.container_new aside { display:none}
.container_new .content { width:1100px;}
.container_new a.mailogo { display:none}
.filter_hide.block { display:none}
</style>
<? } ?>


<?


$NEWS = new News();
$GAL = new Gallery();

$id = $CFG->_GET_PARAMS[0];

if ($id <= 0)
{


		$search_where = $NEWS->groupUserOffice('users', $CFG->USER->USER_ID);

		if($CFG->FORM->setForm($_GET))
			{
				$data = $CFG->FORM->getFullForm();
				$str = $NEWS->getData($data);


				if(!$data["search"] == '')
					{
						$namber = explode("*", $data["search"]);

						if(is_numeric($namber[1]) > 0 )
						{
							$z = $NEWS->getObject($CFG->oPageInfo->id, $namber[1]);
							if(count($z) > 0 )
							{
								redirect('/record/'.$z->id);

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
										   AND (name_company LIKE '%{$apost}%'
										OR name_director LIKE '%{$apost}%'
										OR email LIKE '%{$apost}%'
										OR other_email LIKE '%{$apost}%'
										OR name_director_mobile LIKE '%{$apost}%'
										OR name_director_email LIKE '%{$apost}%'
										OR name_client LIKE '%{$apost}%'
										OR name_client_mobile LIKE '%{$apost}%'
										OR name_client_email LIKE '%{$apost}%'
										OR info LIKE '%{$apost}%')

										";
						}
					}



			}


		$cnt = $NEWS->getListCount(979,"", $str, $search_where, 0);

		echo ' <h2><img alt="" src="/tpl/img/new/icon/2_red.png">Записей в дневнике ('.$cnt.')</h2>';

		include("tpl/filter.tpl");

		echo '<div class="white">';

		$s = $NEWS->getList(979, '', '', '', '', $str, $search_where);

		$pager = new Pager($cnt, 40);

		$l = $NEWS->getList(979, $pager->pp, $pager->start, '', '', $str, $search_where, 0);

		include("tpl/listViewTable.tpl");

		include("_pager.php");

		echo '<a href="/profile/add_alimzhanov/" class="mailogo">Добавить запись</a>';

		echo '<br clear="all"><br clear="all"></div>';

}
else
{
	$o = $NEWS->getObject($CFG->oPageInfo->id, $id);

	$NEWS->putCount($o->id, $o->view);

	$CFG->oPageInfo->html_title = $o->name_company;

	include("tpl/body.tpl");
}
