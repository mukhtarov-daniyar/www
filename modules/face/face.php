<?
$FACE = new Face();
$NEWS = new News();

$id = $CFG->_GET_PARAMS[0];

//Связывваем люцо
if($CFG->_GET_PARAMS[0] == 'face_to_face')
{
	$t_l = $_POST['page_id'];	$s_l = $_POST['id'];
	if($t_l > 0 && $s_l > 0)
	{
			echo $FACE->FaceToFace($t_l, $s_l);
	}
	exit;
}
//Удалить связанное люцо
if($CFG->_GET_PARAMS[0] == 'client_id_dell')
{
	$t_l = $_POST['page_id'];	$s_l = $_POST['id'];
	$FACE->DellFaceToFace($t_l, $s_l);
	exit;
}

//Записываем % скидки в ИМ
if($CFG->_GET_PARAMS[0] == 'order_skidos')
{
		$FACE->upMobileUser($CFG->_POST_PARAMS);
		exit;
}

//Записываем скидки для товаров
if($CFG->_GET_PARAMS[0] == 'order_skidos_vender')
{
		$FACE->AddProductDiscount($CFG->_POST_PARAMS);
		exit;
}


if ($id <= 0)
{
		if($CFG->FORM->setForm($_GET))
		{
			$data = $CFG->FORM->getFullForm();
			$str = $FACE->getData($data);

			if(!$data["search"] == '' && !isset($_GET["add"]))
			{
				$namber = explode("*", $data["search"]);

				if(is_numeric($namber[1]) > 0 )
				{
					$z = $NEWS->getObject(868, $namber[1]);

					if(count($z) > 0 )
					{
						redirect('/record/'.$z->id);
					}
				}

				$apost = apost($data["search"]);
			 	$search_where .= " AND (my_face.name LIKE '%{$apost}%' OR my_face.mobile LIKE '%{$apost}%' OR my_face.email LIKE '%{$apost}%' OR my_face.name_other LIKE '%{$apost}%' OR my_face.info LIKE '%{$apost}%' OR my_face.whatsapp LIKE '%{$apost}%' OR my_face.international LIKE '%{$apost}%' ) ";
			}
			elseif($_GET["add"] == 1)
			{

				//set_time_limit(600);
				$apost = apost($data["search"]);
				$search_where .= " AND (my_face.name LIKE '%{$apost}%' OR my_face.mobile LIKE '%{$apost}%' OR my_face.email LIKE '%{$apost}%' OR my_face.name_other LIKE '%{$apost}%' OR my_face.info LIKE '%{$apost}%' OR my_face.whatsapp LIKE '%{$apost}%' OR my_face.international LIKE '%{$apost}%' ) ";

				$s = $FACE->getList(1012, '', '', '', '', $str, $search_where);
				$FACE->updateArrayId($s, $_GET["add"]);
			}
			elseif($_GET["add"] == 2 && $data["search"] != '')
			{

				$apost = apost($data["search"]);
				$search_where .= " AND (my_face.name LIKE '%{$apost}%'  OR my_face.name_other LIKE '%{$apost}%' OR my_face.email LIKE '%{$apost}%' OR my_face.mobile LIKE '%{$apost}%' OR my_face.info LIKE '%{$apost}%' OR my_face.whatsapp LIKE '%{$apost}%' OR my_face.international LIKE '%{$apost}%' ) ";

				$s = $FACE->getList(1012, '', '', '', '', $str, $search_where);
				$FACE->updateList($s, $_GET["add"]);

			}
			elseif($_GET["add"] == 2)
			{
				$s = $FACE->getList(1012, '', '', '', '', $str, $search_where);

				$FACE->updateList($s, $_GET["add"]);
			}
			elseif($_GET["add"] == 3)
			{
				$s = $FACE->getList(1012, '', '', '', '', $str, $search_where);

				$FACE->updateList($s, $_GET["add"]);
			}
		}

		$cnt = $FACE->getCount(1012,"", $str, $search_where, 0);

		echo ' <h2><img alt="" src="/tpl/img/new/icon/2_red.png">Лиц ('.$cnt.')</h2>';

		include("./modules/face/tpl/filter.tpl");

		echo '<div class="white">';

		$pager = new Pager($cnt, 40);

		$l = $FACE->getList(1012, $pager->pp, $pager->start, '', '', $str, $search_where, 0);


		include("./modules/face/tpl/listViewTable.tpl");

		include($_SERVER['DOCUMENT_ROOT'] . "/_pager.php");

		if( $CFG->USER->USER_ID == 85 ||  $CFG->USER->USER_ID == 1 || $CFG->USER->USER_BOSS = 1 ||  $CFG->USER->USER_ID == 133)
		{
			echo '<br><a href="'.$_SERVER["REQUEST_URI"].'&add=1" target="_blank" class="mailogo" >Рассылка Whatsapp</a>';
		}

		if( $CFG->USER->USER_ID == 85 ||  $CFG->USER->USER_ID == 1 || $CFG->USER->USER_BOSS == 1)
		{
			echo '<a href="'.$_SERVER["REQUEST_URI"].'&add=2" target="_blank" class="mailogo">Экспорт контактов</a>';
			echo '<a href="'.$_SERVER["REQUEST_URI"].'&add=3" target="_blank" class="mailogo">Экспорт для смс</a><br clear="all">';
		}

		echo '</div>';

}
elseif($CFG->_GET_PARAMS[1] == 'history')
{
		include("./modules/face/tpl/history.tpl");
}
elseif($CFG->_GET_PARAMS[1] == 'static')
{
	echo ' <h2><img alt="" src="/tpl/img/new/icon/2_red.png">Статистика лиц</h2>';
	echo '<div class="white">';
	echo '<h3><a href="/person/"><i class="glyphicon glyphicon-arrow-left"></i> ВЕРНУТЬСЯ В ЛИЦА</a></h3>';
	include('tpl/step/step.tpl');
	echo '</div>';
}
else
{
	if(is_numeric($CFG->_GET_PARAMS[0]))
	{
		$o = $FACE->getObject($CFG->oPageInfo->id, $CFG->_GET_PARAMS[0]);
	}
	else
	{
		$sql = getSQLRowO("SELECT * FROM my_face WHERE mobile LIKE '%{$CFG->_GET_PARAMS[0]}%' ");
		$o = $FACE->getObject($CFG->oPageInfo->id, $sql->id);
	}

	$CFG->oPageInfo->html_title = $o->name;

	include("./modules/face/tpl/body.tpl");

}
