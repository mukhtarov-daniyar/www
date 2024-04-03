<?

$SPEED = new Speeds();

$id = $CFG->_GET_PARAMS[0];

switch ($id)
{

		// Вкл Выкл товары с 0
	case 'standards':

	SetCookie("standards", '',time()-1800, '/');

	if($_COOKIE['standards'] == 1)
	{
			SetCookie("standards", 0,time()+1800, '/');
	}
	elseif($_COOKIE['standards'] == '')
	{
		SetCookie("standards", 0,time()+1800, '/');
	}
	elseif($_COOKIE['standards'] == 0)
	{
		SetCookie("standards", 1,time()+1800, '/');
	}	else
	{
		SetCookie("standards", 1,time()+1800, '/');
	}

	header("Location: ".$_SERVER['HTTP_REFERER']);

		exit;
	break;

	//Сортировка списка товаров всей 1С
	case 'orders':
		SetCookie("orders", $CFG->_GET_PARAMS[1],time()+1800, '/');
		SetCookie("type", $CFG->_GET_PARAMS[2],time()+1800, '/');

		SetCookie("nomenclature_orders", '',time()-1800, '/');
		SetCookie("nomenclature_type", '',time()-1800, '/');

		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	break;

	//Сортировка списка для "Продажи за день"
	case 'nomenclature_orders':
		SetCookie("nomenclature_orders", $CFG->_GET_PARAMS[1],time()+1800, '/');
		SetCookie("nomenclature_type", $CFG->_GET_PARAMS[2],time()+1800, '/');

		SetCookie("orders", '',time()-1800, '/');
		SetCookie("type", '',time()-1800, '/');

		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	break;


	//Запись руководитель продаж
	case 'ruk':
		$SPEED->NomenclatureRuk($_POST);
		exit;
	break;

	//Ревизия
	case 'audit':
		$SPEED->Audit($_POST);
		exit;
	break;

	//Добавить товар в корзину
	case 'card':
		$SPEED->Card($_POST);
		exit;
	break;


	//Список корзину
	case 'card_list':
		$l = $SPEED->CardList($CFG->USER->USER_ID);

		include("tpl/card/list_view.tpl");
	break;


	//Список Касс
	case 'cashbox':
		include("tpl/cashbox/cashbox.tpl");
	break;


	//Плюсы за бары
	case 'bar_plus':
		$SPEED->BarPlus($_GET['cdate']);
		exit;
	break;


	// Премирование менеджеров. 1 Раз в сутки
	case 'motivationnomenclature':

		if(isset($CFG->_GET_PARAMS[1]))
		{
			$SPEED->MotivationNomenclature($CFG->_GET_PARAMS[1]);
		}
		else
		{
			redirect('/speedometer/motivationnomenclature/'.date('Y-m-d'));
			exit;
		}

	break;

		// Мотивации
		case 'motivation':
			include("tpl/motivation.tpl");
		break;


		// Мотивации
		case 'gkgd':
			include("tpl/gkgd.tpl");
		break;



	// Просмотр продаж за день
	case 'nomenclature_view':

		//Разбираем загруженный ранее файл с данными 1С в JSON
		if($CFG->_GET_PARAMS[1] == 'down_file_json')
		{
			echo $SPEED->Nomenclature(1);
			//redirect('/'.$CFG->oPageInfo->xcode.'/'.$CFG->_GET_PARAMS[0].'/?monthstart='.date('Y-m-01 00:00:00').'&monthend='.date('Y-m-t 23:59:59'));
			exit;
		}
		elseif($CFG->_GET_PARAMS[1] == 'nomenclature')
		{
			echo $SPEED->NomenclatureMain();
			exit;
		}
		else
		{
			if($CFG->FORM->setForm($_GET))
			{
				$data = $CFG->FORM->getFullForm();
				$str = $SPEED->getData($data);
			}

			include("tpl/filter_nomenclature.tpl");
			$l = $SPEED->getListNomenclature($str);
			include("tpl/nomenclature_array.tpl");
		}
	break;


	// Аналитика
	case 'analysis':
		if(isset($_POST['text']))
		{
				$SPEED->getListAnalysisinsert($_POST['text']);
				exit;
		}
		elseif(isset($_POST['id']))
		{
			$SPEED->getListAnalysisDel($_POST['id']);
			exit;
 		}
		else {
			$l = $SPEED->getListAnalysis();
			include("tpl/analysis/analysis_array.tpl");
		}
	break;


	// Поиск Контрагента по запросу
	case 'buyer':
			$buyer = $_POST['buyer'];
			$l = $SPEED->getListBuyer($buyer);
			include("tpl/nomenclature/buyer.tpl");
			exit;
	break;

	// Поиск поставщика по запросу
	case 'provider':
			$provider = $_POST['provider'];
			$l = $SPEED->getListProvider($provider);
			include("tpl/nomenclature/provider.tpl");
			exit;
	break;



	// Поиск Контрагента по запросу
	case 'more_default':
		if($CFG->FORM->setForm($_GET))
		{
			$data = $CFG->FORM->getFullForm();

			$str = $SPEED->getData($data);
		}
		$l = $SPEED->getList($str, $_GET["cnt"]);
		include("tpl/sklad/tovar_array.tpl");
		exit;
	break;


	// Вывод данных по расходам ЛИЛ
	case 'lil':
		$l = $SPEED->getDataLil();
		include("tpl/lil/array.tpl");
	break;

	// Вывод данных по id расходам ЛИЛ
	case 'lil_ajax_data':
		$res = $SPEED->getDataLilIdAjax((int)$_POST['id']);
		include("tpl/lil/item.tpl");
		exit;
	break;


	// Просмотр списка товаров всей 1С
	default:

		if($CFG->FORM->setForm($_GET))
		{
			$data = $CFG->FORM->getFullForm();

			$str = $SPEED->getData($data);
		}
		$l = $SPEED->getList($str);

		include("tpl/sklad/filter.tpl");
		include("tpl/sklad/tovar_array.tpl");
		include("tpl/sklad/foot.tpl");


		if($_GET['exel'] == 1)
		{
				$items['cdate']['name'] = date('d.m.Y H:i:s');
				$items['cdate']['data'] =  'Наличный склад:';

				//Определяем поисковую строку
				$items['search']['name'] = $_GET['search'];
				$items['search']['data'] =  'Что найти?';

				// Определяем склад
				for($i=0;$i<sizeof($warehouse);$i++)
				{
					if($warehouse[$i]->name == '') continue;
					if($data['warehouse'] != $warehouse[$i]->id) {}
					else{ $text_wh .= $warehouse[$i]->name; }
				}
				$items['warehouse']['name'] = $text_wh;
				$items['warehouse']['data'] =  'Склад:';

				//Определяем группу товаров
				for($i=0;$i<sizeof($group);$i++)
				{
					foreach($data['group'] as $res)
					{
						if($res != $group[$i]->id) { }
						else { $sklad_test .= $group[$i]->name.',';}
					}
			  }
				$sklad_test = trim($sklad_test, ',');

				$items['sklad']['name'] = $sklad_test;
				$items['sklad']['data'] =  'Группа товаров:';

				//print_r($items); exit;

				$SPEED->ExportExel($l, $items);
		}


	break;
}
