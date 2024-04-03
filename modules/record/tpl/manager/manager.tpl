            <div class="col-md-4">
                <p>Менеджер:</p>
                <select name="users" class="selectpicker show-tick" data-live-search="true">
                <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
               <? 
                $manager = SelectDataParent('users', 'user_id', $CFG->USER->USER_DIRECTOR_ID, 0);

                for($i=0;$i<sizeof($manager);$i++)
                {	
                    ($data['users'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=$manager[$i]->name?></option>
              <? } ?>
                </select>
            </div>
