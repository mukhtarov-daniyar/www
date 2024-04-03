<div class="col-md-4">
    <p><?=$CFG->Locale['fp60'];?>:</p>
    <select name="company" class="selectpicker show-tick" id="manager">
    <option value="0" selected><?=$CFG->Locale["fi1"]?></option>
   <? 
 	$company_id = $CFG->USER->USER_ID.','.$CFG->USER->ACCESS_COMPANY;
    $sql = "SELECT * FROM {$CFG->DB_Prefix}company WHERE visible='1' AND id IN ({$company_id}) ";
    $manager = getSQLArrayO($sql);
    
    for($i=0;$i<sizeof($manager);$i++)
    {	
        ($data['company'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
        <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=translit($manager[$i]->name);?></option>
  <? } ?>
    </select>
    <img src="/tpl/img/loading.gif" id="imgLoadCompany" alt="" style="padding-left:20%; display:none" />
   
    <div id="resultCompany"></div>
    
    <div class="refresh">
            <?
                if($data["company"] > 0)
                {	
                
                    
                    $dataS = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND personal = 0 AND user_id = {$data[company]} ORDER BY name ASC");
           
                    if($dataS)
                    {
                        echo ' <select name="users" class="selectpicker show-tick">';
                        echo '<option value="0">'.$CFG->Locale['fp61'].'</option>';
                        for($z=0;$z<sizeof($dataS);$z++)
                        {	
                            ($data['users'] == $dataS[$z]->id) ? $sel = "selected" : $sel = "";
                        
                        ?>
                            
                          <option value="<?=$dataS[$z]->id?>"<?=$sel?>><?=translit($dataS[$z]->name);?></option>
                        
                        <? }
                        echo '</select>';
                    }
                }
            ?>
    </div>
</div>
