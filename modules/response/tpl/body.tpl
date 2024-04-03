<h2><?=$vacancy->name;?></h2>

<article class="vacancies_body">  
                <div class="lite">
                	<? $user = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users WHERE id='{$o->user_id}'"); ?>
                    <a href="/<?=$CFG->SYS_LANG_NAME?>/profile/view/<?=$user->id?>/"><?=$user->name?><?=$user->lastname;?></a>
                    <span class="normal"><?=dateSQL2TEXT($user->dob, "DD MN YYYY")?></span>
                    <span class="normal"><?=$CFG->Locale["fi5"];?>:<? $sex = array(0=>$CFG->Locale["ps51"], 1=>$CFG->Locale["ps52"], 2=>$CFG->Locale["ps53"], 3=>$CFG->Locale["ps54"]); echo $sex[$user->experience];?></span>
                    <span class="normal"><?$city = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city  WHERE visible='1' and id='{$user->city}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC"); echo $city->name;?></span>
                </div>

            <div class="lite">
            	<? $vacancy = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news  WHERE id='{$o->vacancy_id}'"); ?><a href="/<?=$CFG->SYS_LANG_NAME?>/vacancy/<?=$vacancy->id?>/"><?=$vacancy->name?></a>
                
              <? $company = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}company  WHERE id='{$vacancy->company_id}'");?>
            	<span class="normal"><br><a href="/<?=$CFG->SYS_LANG_NAME?>/company/<?=$company->id;?>/"><?=$company->name_company;?></a></span>
            </div>

                <div class="lite" style="text-align:center">
                	<?=dateSQL2TEXT($o->cdate, "DD MN YYYY")?>
                    <span class="normal">
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
                </div>
</article>


<div class="text">
	<?=$o->body?>  
</div>

<?

if($CFG->USER->USER_STATUS == 3 )
	{ 
		if($o->edit != 1)  
			{ ?>
                    <form class="response"  method="POST">
                        <input type="submit" name="off" value="<?=$CFG->Locale["off"];?>" id="btnS" style="display:inline-block; width:120px; text-align:center; padding:10px; margin-bottom:20px;" />
                        <input type="submit" name="ok" value="<?=$CFG->Locale["ok"];?>"  id="btnS"  style="display:inline-block; width:120px; text-align:center; padding:10px; margin-bottom:20px;"/>   
                    </form>
		<? } 
        	else echo '<center>'.$CFG->Locale["response_off_r"].'</center><br>';
                
    }
	elseif($CFG->USER->USER_STATUS == 0)
    { $sex = array(0=>'', 1=>$CFG->Locale["visible1"], 2=>$CFG->Locale["visible2"], 3=>$CFG->Locale["visible1"], 4=>$CFG->Locale["visible2"]); echo '<center><h4>'.$sex[$o->visible].'</h4></center>'; 
    }

?>


<?

if($CFG->USER->USER_STATUS == 2 )
	{ 
		if($o->modern != 1)  
			{ ?>
                    <form class="response"  method="POST">
                        <input type="submit" name="ok" value="<?=$CFG->Locale["seeker2"]?>"  id="btnS"  style="display:inline-block; width:120px; text-align:center; padding:10px; margin-bottom:20px;"/>  
                        <input type="submit" name="off" value="<?=$CFG->Locale["seeker3"]?>" id="btnS" style="display:inline-block; width:120px; text-align:center; padding:10px; margin-bottom:20px;" /> 
                    </form>
		<? } 
        	else echo '<center>'.$CFG->Locale["response_off_r"].'</center><br>';
                
    }
	

?>


