            <div class="col-md-4">
                <p>Компания:</p>
                <select name="company" class="selectpicker show-tick" id="manager">
                <option value="0" selected><?=$CFG->Locale["fi1"]?></option>
               <? 
                $manager = AndDataArray('company', 'page_id', 0, 1, 'pos ASC');
               
                
                for($i=0;$i<sizeof($manager);$i++)
                {	
                    ($data['company'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=$manager[$i]->name?></option>
              <? } ?>
                </select>
                <img src="/tpl/img/loading.gif" id="imgLoadCompany" alt="" style="padding-left:20%; display:none" />
               
                <div id="resultCompany"></div>
                
                <div class="refresh">
                        <?
                            if($data["company"] > 0)
                            {	
                            
                       			$dataS = AndDataArray('users','user_id', $data["company"], 0);
                       
                                if($dataS)
                                {
                                    echo ' <select name="users" class="selectpicker show-tick">';
                                    echo '<option value="0">Любой менеджер</option>';
                                    for($z=0;$z<sizeof($dataS);$z++)
                                    {	
                                        ($data['users'] == $dataS[$z]->id) ? $sel = "selected" : $sel = "";
                                    
                                    ?>
                                        
                                      <option value="<?=$dataS[$z]->id?>"<?=$sel?>><?=$dataS[$z]->name;?></option>
                                    
                                    <? }
                                    echo '</select>';
                                }
                            }
                        ?>
                </div>
            </div>
