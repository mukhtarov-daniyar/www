<?
$NEWS = new Response();
$GAL = new Gallery();

$id = $CFG->_GET_PARAMS[0];


if ($id <= 0){ 


	if($CFG->USER->USER_ID) 
	{
	}
	else
	{
		redirect($CFG->AUTHPAGE); 
	}


echo "<h2>".$CFG->oPageInfo->name."</h2>"; 
 
 
 		switch($CFG->_GET_PARAMS[0]) {
		
			case 'kat' :
			
			if (is_numeric($CFG->_GET_PARAMS[1]))
			{
				$dim = " and kat='{$CFG->_GET_PARAMS[1]}'"; 
			}
				
			break;
			
			case 'tags' :
			
				$tags_object = rawurldecode($CFG->_GET_PARAMS[1]);
				$tags_object = preg_replace("/'/",  "", $tags_object);
				$tags_link = " AND tags LIKE '%{$tags_object}%'";
				
			break;
			
			case 'cdate' :

				$arch = $_POST["cdate"];
				
				if(isset($arch))
				{
					$l = explode("_", $arch);
					$y = $l[0];
					$m = $l[1];
					$d = $l[2];
					
					if(is_numeric($y))
					if($y <= 2020)
					$cdataJ .=" AND YEAR(cdate)={$y}";
					
					if(is_numeric($m))
					if($m <= 12)
					$cdataJ .=" AND MONTH(cdate)={$m}";
					
					if(is_numeric($d))
					if($d <= 31)
					$cdataJ .=" AND DAY(cdate)={$d}";

				} 
			
			break;
			
			case 'print' :
			
			break;
			
		}

		
		if($CFG->FORM->setForm($_GET)) 
			{
				$data = $CFG->FORM->getFullForm();
				$str = $NEWS->getData($data);
				
				if(!$data["search"] == '')
					{
						$search_where = "body LIKE '%{$data["search"]}%' AND";
					}
			}
		
		include("./modules/response/tpl/filter.tpl");

		$cnt = $NEWS->getCountSearch(868,"", $search_where, $str);
		
		$pager = new Pager($cnt, 10);
		$l = $NEWS->getList(868, $pager->pp, $pager->start, '', '', $search_where, $str);

		for ($i=0; $i<sizeof($l); $i++)
			{
				$o = $l[$i];
				include("./modules/response/tpl/listView.tpl");
			}
			
		include("_pager.php");
}

else
	{

		$o = $NEWS->getObject($CFG->oPageInfo->id, $id);
		

		if($_POST['ok'])
		{
			if($CFG->USER->USER_STATUS == 2)
			{
				/* id | visible | edit | modern */
				$NEWS->putCount($o->id, 1, 0, 1);
				redirect($_SERVER['HTTP_REFERER']);
			}
			else
			{
				$NEWS->putCount($o->id, 3, 1, 1);
				redirect($_SERVER['HTTP_REFERER']);	
				
				/* Место для отправки емаил увидомления соискателю о том что его пригласили на собеседование */			
			}
		}
		elseif($_POST['off'])
		{
			if($CFG->USER->USER_STATUS == 2)
			{
				$NEWS->putCount($o->id, 2, 1, 1);
				redirect($_SERVER['HTTP_REFERER']);
				/* Место для отправки емаил увидомления соискателю о том что его отклик не прошел модерацию */	
			}
			else
			{
				$NEWS->putCount($o->id, 4, 1, 1);
				redirect($_SERVER['HTTP_REFERER']);
				/* Место для отправки емаил увидомления соискателю о том что отклик отклонил работадатель */			
			}
		}
		
		$vacancy = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news  WHERE id='{$o->vacancy_id}'");
	
		$CFG->oPageInfo->html_title = $vacancy->name;
		$CFG->oPageInfo->html_keywords = strip_tags($vacancy->name);
		$CFG->oPageInfo->html_description = utf8_substr(strip_tags($o->body), 0, 400);
		
		include("./modules/response/tpl/body.tpl");

}


?>