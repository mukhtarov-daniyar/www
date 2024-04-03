<? 
	$MODULE_ = getFullXCodeByPageId($CFG->pid); 
?>


<?=showHeader2($CFG->oPageInfo->name);?>

<div class="text">
<style type="text/css">
	table.view{  width:100%}
	table.view td {font-size:14px;}
	table.view td:nth-child(1) { width:40%;text-align:right; padding-right:30px; color:#7C8187 } 
</style>


<table class="view">
  <tr>
    <td><img src="<?=$data['logo_company'];?>" width="114" height="105" alt="<?=$data['logo_company']?> "></td>
    <td><h1><?=$data['name_company']?></h1><? if($data['id'] == $CFG->USER->USER_ID){?> <a href="/<?=$CFG->SYS_LANG_NAME?>/profile/edit/<?=$CFG->USER->USER_ID?>"><?=$CFG->Locale["ps22"];?></a><? } ?></td>
  </tr>
  <tr>
    <td>Сфера деятельности:</td>
    <td><? $sphere = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}sphere  WHERE visible='1' and id='{$data['sphere']}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC");echo $sphere->name;?></td>
  </tr>
  <tr>
    <td>Описание деятельности:</td>
    <td><?=$data['text_company']?></td>
  </tr>
  <tr>
    <td><?=$CFG->Locale["ps15"];?></td>
    <td><? $city = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' and id='{$data['city']}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC"); echo $city->name;?></td>
  </tr>
  <tr>
    <td>Контактное лицо:</td>
    <td><?=$data['name']?></td>
  </tr>  
  <tr>
    <td>Контакты:</td>
    <td><?=$data['login']?>,<?=$data['mobile']?></td>
  </tr>  
</table>
</div>