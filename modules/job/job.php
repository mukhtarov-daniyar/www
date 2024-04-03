 <div class="row tab">
    <div class="col-md-6">
        <h1><a href="/<?=$CFG->SYS_LANG_NAME?>/seeker/"><?=$CFG->Locale["ps61"];?></a></h1>
    </div>    	
    <div class="col-md-6">
        <h1><a href="<?=$CFG->oPageInfo->_xcode?>" class="active"><?=$CFG->oPageInfo->name?></a></h1>
    </div>    	
</div>


<?
$NEWS = new Job();
$GAL = new Gallery();

if(($CFG->USER->USER_STATUS == 2) || ($CFG->USER->USER_STATUS == 1))
{
		
	if($_GET['status'] > 0) 
		{	
			$NEWS->newInsert($CFG->_GET_PARAMS[1], 3);

			//if(!$data["search"] == '')
				//{
				//	$search_where = "name LIKE '%{$data["search"]}%' AND";
				//}
		}
		

	if($CFG->_GET_PARAMS[0] == 'del')
	{

		$NEWS->delInsert($CFG->_GET_PARAMS[1]);
	}
	
	
	include("./modules/job/tpl/filter.tpl");

	$cnt = $NEWS->getCountSearch($search_where);
	
	$pager = new Pager($cnt, 10);
	$l = $NEWS->getList($pager->pp, $pager->start, '', '', $search_where);
	
	for ($i=0; $i<sizeof($l); $i++)
		{
			$o = $l[$i];
			include("./modules/job/tpl/listView.tpl");
		}
		
	include("_pager.php");
	
}
else
{
	redirect('/');	
}


?>