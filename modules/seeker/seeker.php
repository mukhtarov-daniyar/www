 <div class="row tab">
    <div class="col-md-6">
        <h1><a href="<?=$CFG->oPageInfo->_xcode?>" class="active"><?=$CFG->oPageInfo->name?></a></h1>
    </div>    	
    <div class="col-md-6">
        <h1 class="active"><a href="/<?=$CFG->SYS_LANG_NAME?>/job/"><?=$CFG->Locale["ps62"];?></a></h1>
    </div>    	
</div>

<?
$NEWS = new Seeker();
$GAL = new Gallery();

if(($CFG->USER->USER_STATUS == 2) || ($CFG->USER->USER_STATUS == 1))
{
		
		if($CFG->FORM->setForm($_GET)) 
			{
				$data = $CFG->FORM->getFullForm();
				
				if(isset($data["login"]))
					{
						$search_where = "login LIKE '%{$data["login"]}%' AND";
					}
			}
	
	include("./modules/seeker/tpl/filter.tpl");

	$cnt = $NEWS->getCountSearch('','',$search_where);
	
	$pager = new Pager($cnt, 20);
	$l = $NEWS->getList($pager->pp, $pager->start, '', '', $search_where);
	
	
	echo "	<div class='text'>
			<table class='users' cellspacing='0' cellpadding='0' border='1'>";
	echo "
		<tr>
			<th>Имя</th>
			<th>Фамилия</th>
			<th>Телефон</th>
			<th>E-mail</th>
			<th>Город</th>
			<th>Деятельность</th>
			<th>Страна обуч.</th>
			<th>Годы обуч.</th>
			<th>стд. Болашак</th>
			<th>Резюме</th>
		</tr>
	";

	
	for ($i=0; $i<sizeof($l); $i++)
		{
			$o = $l[$i];
			include("./modules/seeker/tpl/listView.tpl");
		}
	echo "	</table>
			</div>";
		
	include("_pager.php");
	
}
else
{
	redirect('/');	
}

  
?>