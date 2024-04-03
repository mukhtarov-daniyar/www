<? $company = getSQLRowO("SELECT name_company,id FROM {$CFG->DB_Prefix}company  WHERE visible='1' and user_id='{$o->user_id}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC");?>

<h2><?=$o->name?></h2>

        <article class="vacancies_body">
                <div class="lite"><?=$o->name?></div>
                <div class="lite"><span class="company"><a href="/<?=$CFG->SYS_LANG_NAME;?>/company/<?=$company->id;?>/"><?=$company->name_company;?></a></span></div>
                <div class="lite">
                	<span class="sity"><span class="none"><? $city = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city  WHERE visible='1' and id='{$o->city_id}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC"); echo $city->name;?></span>  <span style="float:right"><?=dateSQL2TEXT($o->cdate, "DD MN YYYY")?></span></span>
                    <span class="price"><? $sex = array(1=>$CFG->Locale["ps37"], 2=>$CFG->Locale["ps38"], 3=>$CFG->Locale["ps39"], 4=>$CFG->Locale["ps40"], 5=>$CFG->Locale["ps41"], 6=>$CFG->Locale["ps42"], 7=>$CFG->Locale["ps43"]); echo ($sex[$o->salary_id]); ?></span>
                </div>
        </article>

<div class="text">
<strong><?=$CFG->Locale['ps31'];?></strong>:<? $sphere = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}sphere WHERE visible='1' and id='{$o->sphere_id}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC");echo $sphere->name;?><br>
<strong><?=$CFG->Locale['ps15'];?></strong><? $city = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city  WHERE visible='1' and id='{$o->city_id}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC"); echo $city->name;?><br>
<strong><?=$CFG->Locale['ps30'];?></strong>:<? $sex = array(1=>$CFG->Locale["ps51"], 2=>$CFG->Locale["ps52"], 3=>$CFG->Locale["ps53"], 4=>$CFG->Locale["ps54"]); echo $sex[$o->experience_id]; ?><br>
<strong><?=$CFG->Locale['ps50'];?></strong>:<? $sex = array(1=>$CFG->Locale["ps45"], 2=>$CFG->Locale["ps46"], 3=>$CFG->Locale["ps47"], 4=>$CFG->Locale["ps48"], 5=>$CFG->Locale["ps49"]); echo $sex[$o->employment_id]; ?><br><br><br>

   <? if($o->requirement != '' || $o->requirement != ' ') { ?>
        <strong><?=$CFG->Locale["setting1"];?>:</strong><br>
       <?=nl2br($o->requirement)?><br><br>
   <? }?>
    
   <? if($o->duties != '' || $o->duties != ' ') { ?>
        <strong><?=$CFG->Locale["setting2"];?>:</strong><br>
       <?=nl2br($o->duties)?><br><br>
   <? }?>
    
   <? if($o->conditions != '' || $o->conditions != ' ') { ?>
        <strong><?=$CFG->Locale["setting3"];?>:</strong><br>
       <?=nl2br($o->conditions)?><br><br>
   <? }?>   
</div>


 
 
<?
if($CFG->USER->USER_STATUS === '0')
	{	
    	$sql = "SELECT * FROM {$CFG->DB_Prefix}response WHERE vacancy_id='{$o->id}' and user_id='{$CFG->USER->USER_ID}'";
		$z = getSQLRowO($sql);
            	
		if($z->id)
		{
        	echo '<center>Вы уже откликались на эту вакансию.</center>';
        }
		else 
		{ ?>
<div class="text">
<hr> 
    <strong>Отклик:</strong><br>
        <form class="response"  method="post" action="">
            <input type="text" name="q" placeholder="Сопроводительный текст" />
            <input type="submit" name="send" value="<?=$CFG->Locale["send"];?>" id="btnS" />
        </form>
</div>     
            
      <?}
    ?>
	<?}
elseif(($CFG->USER->USER_STATUS == 2) || ($CFG->USER->USER_STATUS == 1))
	{
		//echo 'Я менеджер';
	}
elseif($CFG->USER->USER_STATUS == 3)
	{
		//echo 'Я работадатель';
	}
elseif($CFG->USER->USER_STATUS == '')
	{ ?>
		<center>Для отклика на вакансию необходимо войти или зарегистрироваться</center>
		<center><br><a href="/<?=$CFG->SYS_LANG_NAME?>/auth/" id="btnS" style="width:80px; margin-top:0;">Войти</a></center>
	<? }

?> 
  
  
    
    <br>
