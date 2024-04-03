
<article class="vacancies">  
            <a href="/<?=$CFG->SYS_LANG_NAME;?>/response/<?=$o->id?>/">
                <div class="lite">
                	<? $user = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$o->user_id}'"); echo $user->name.' '.$user->lastname;?>
                    <span class="normal"><span class="company"><?=dateSQL2TEXT($user->dob, "DD MN YYYY")?></span></span>
                    <span class="normal company"><?=$CFG->Locale["fi5"];?>:<? $sex = array(0=>$CFG->Locale["ps51"], 1=>$CFG->Locale["ps52"], 2=>$CFG->Locale["ps53"], 3=>$CFG->Locale["ps54"]); echo $sex[$user->experience];?></span>
                    <span class="normal company"><?$city = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city  WHERE visible='1' and id='{$user->city}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC"); echo $city->name;?></span>
                </div>

            <div class="lite">
            	<? $vacancy = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news  WHERE id='{$o->vacancy_id}'"); echo $vacancy->name; $company = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}company  WHERE id='{$vacancy->company_id}'");?>
            	<span class="normal"><br><span class="company"><?=$company->name_company;?></span></span>
            </div>

                <div class="lite" style="text-align:center">
                	<?=dateSQL2TEXT($o->cdate, "DD MN YYYY")?>
                    <span class="normal"><span class="company">
                    	<br>
                        
<? if($CFG->USER->USER_STATUS == 3 || $CFG->USER->USER_STATUS == 2)
	{
    	$sex = array(0=>$CFG->Locale["respons0"], 1=>$CFG->Locale["respons1"], 2=>$CFG->Locale["respons2"], 3=>$CFG->Locale["respons3"], 4=>$CFG->Locale["respons4"]); echo $sex[$o->visible]; 
    }
	else
	{ 
    	$sex = array(0=>$CFG->Locale["respons0"], 1=>$CFG->Locale["respons0"], 2=>$CFG->Locale["respons4"], 3=>$CFG->Locale["respons3"], 4=>$CFG->Locale["respons4"]); echo $sex[$o->visible]; 
    }
?>                        
                        
                        </span>
                    </span>
                </div>
            </a>
</article>

