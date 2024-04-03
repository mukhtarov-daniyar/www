

            <div class="col-md-4">
                <p>Компания:</p>
                <select name="company" class="selectpicker show-tick" id="manager">
                  <option value="0" selected>Любая</option>
                   <?
                    $manager = getSQLArrayO("SELECT name,id FROM {$CFG->DB_Prefix}company WHERE visible='1'   ");

                    foreach ($manager as $value)
                    {
                      ($data['company'] == $value->id) ? $sel = "selected" : $sel = "";
                      ?>
                          <option value="<?=$value->id?>"<?=$sel?>><?=$value->name;?></option>
                      <?
                    }
                  ?>
                </select>
                <img src="/tpl/img/loading.gif" id="imgLoadCompany" alt="" style="padding-left:20%; display:none" />

                <div id="resultCompany"></div>

                <div class="refresh">
                        <?
                            if($data["company"] > 0)
                            {
                                $dataS = getSQLArrayO("SELECT id, name FROM {$CFG->DB_Prefix}users WHERE visible=1 AND user_id = {$data[company]} ORDER BY name ASC");

                                if($dataS)
                                {
                                    echo ' <select name="users" class="selectpicker show-tick" data-live-search="true">';
                                    echo '<option value="0">'.$CFG->Locale['fp61'].'</option>';

                                    foreach ($dataS as $value)
                                    {
                                      ($data['users'] == $value->id) ? $sel = "selected" : $sel = "";
                                      ?>
                                          <option value="<?=$value->id?>"<?=$sel?>><?=$value->name;?></option>
                                      <?
                                    }
                                    echo '</select>';
                                }
                            }
                        ?>
                </div>
            </div>
