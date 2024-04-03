
            <div class="col-md-4">
                <p><?=$CFG->Locale['fp59'];?>:</p>
                <select name="users" class="selectpicker show-tick" data-live-search="true">
                <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
               <? 
                $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND dismissed = 0 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} AND status IN (1) ORDER BY name ASC");

                for($i=0;$i<sizeof($manager);$i++)
                {	
                    ($data['users'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=translit($manager[$i]->name);?></option>
              <? } ?>
                </select>
            </div>
