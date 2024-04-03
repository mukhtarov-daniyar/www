<article class="row">
    <a href="<?=getFullXCodeByPageId($o->page_id);?><?=$o->id?>">
      <div class="col-md-7"><strong><?=$o->name?></strong><br><? $company = getSQLRowO("SELECT name_company FROM {$CFG->DB_Prefix}company  WHERE visible='1' and user_id='{$o->user_id}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC"); echo ecrane($company->name_company);?></div>
      <div class="col-md-2"></div>
      <div class="col-md-3">
      	<span class="sity"><? $city = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city  WHERE visible='1' and id='{$o->city_id}' and sys_language='{$CFG->SYS_LANG}' ORDER BY pos DESC"); echo $city->name;?></span>
        <span style="float:right"><?=dateSQL2TEXT($o->cdate, "DD MN YYYY")?></span>
        <center><strong><? $sex = array(1=>$CFG->Locale["ps37"], 2=>$CFG->Locale["ps38"], 3=>$CFG->Locale["ps39"], 4=>$CFG->Locale["ps40"], 5=>$CFG->Locale["ps41"], 6=>$CFG->Locale["ps42"], 7=>$CFG->Locale["ps43"]); echo ($sex[$o->salary_id]); ?></strong></center>
                        	
                    <span class="price"></span>

                   <? if($CFG->USER->USER_STATUS == 2) { ?>                    
                         <span class="price" style="font-weight:100; padding-top:15px;"><? $sex = array(0=>$CFG->Locale["vacancy0"], 1=>$CFG->Locale["vacancy1"], 2=>$CFG->Locale["vacancy2"]); echo ($sex[$o->visible]); ?></span>           
                   <? }?>
                    
                                        
                   <? if($CFG->USER->USER_STATUS == 3) { 
                    
                    		if($CFG->USER->USER_ID == $o->user_id)
                            {?>
                                        
                         <span class="price" style="font-weight:100; padding-top:15px;"><? $sex = array(0=>$CFG->Locale["vacancy0"], 1=>$CFG->Locale["vacancy1"], 2=>$CFG->Locale["vacancy2"]); echo ($sex[$o->visible]); ?></span>           
                   <? }}?>                  
      </div>
    </a>
</article>
