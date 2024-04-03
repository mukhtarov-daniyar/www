<? 
	$MODULE_ = getFullXCodeByPageId($CFG->pid); 
	$e = $CFG->FORM->getFailInputs();
    
?>

<?=showHeader2($CFG->Locale["ps34"]);?>
<div class="white">
    <article class="vacancies_body row">
        <div class="table-responsive kriteri" style="width:100%">
            <form method="POST" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>">
                <input type="hidden" name="user_act" value="update_profile" />
     
                <table class="view">
                  <tr>
                    <td><?=$CFG->Locale["zakup_v2"]?></td>
                    <td><input type="text" name="name" placeholder="<?=$CFG->Locale["ps7"];?>" value="<?=$data['name']?>"<?=$e['name']?> /></td>
                  </tr>
                  <tr>
                    <td><?=$CFG->Locale["ps11"];?></td>
                    <td>
                        <select name="pauls" class="selectpicker">
                       <? $sex = array(0=>$CFG->Locale["ps12"], 1=>$CFG->Locale["ps13"], 2=>$CFG->Locale["ps14"]);
                        for($z=0; $z<sizeof($sex); $z++)
                        {
                            ($data['pauls'] == $z) ? $sel = "selected" : $sel = "";?>
                            <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
                       <? } ?>
                        </select>
                    </td>
                  </tr>
                  <tr>
                    <td><?=$CFG->Locale["ps6"];?></td>
                    <td><input id="type-vse-date" type="text" name="dob" value="<?=$data['dob']?>"<?=$e['dob']?> placeholder="<?=$CFG->Locale["ps6"];?>"/></td>
                  </tr>    
                  <tr>
                    <td><?=$CFG->Locale["ps33"]?></td>
                    <td><input type="text" class="mobile" name="mobile" placeholder="<?=$CFG->Locale["ps33"];?>" value="<?=$data['mobile']?>"<?=$e['mobile']?> /></td>
                  </tr>   
                  
                  <tr>
                    <td>E-mail</td>
                    <td><input type="text" name="email" placeholder="Электроный адрес:" value="<?=$data['email']?>"<?=$e['email']?> /></td>
                  </tr>   
    
                  <tr>
                    <td><?=$CFG->Locale["ps15"];?></td>
                    <td>
                        <select name="city" class="selectpicker show-tick" data-live-search="true">
                       <? 
                        $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
                        for($i=0;$i<sizeof($city);$i++)
                        {	
                            ($data['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                            <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
                      <? } ?>
                        </select>
                    </td>
                    
                    
                  <tr>
                    <td>Страница для задач</td>
                    <td>
                        <select name="taks_id" class="selectpicker show-tick" data-live-search="true">
                        <option value="0">Выберите запись</option>
                       <? 
                        $taks_id = getSQLArrayO("SELECT id,name_company FROM {$CFG->DB_Prefix}news  WHERE visible='1' and manager_id='{$CFG->USER->USER_ID}' ORDER BY id ASC");
                        for($i=0;$i<sizeof($taks_id);$i++)
                        {	
                            ($data['taks_id'] == $taks_id[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                            <option value="<?=$taks_id[$i]->id?>"<?=$sel?>><?=$taks_id[$i]->name_company?></option>
                      <? } ?>
                        </select>
                   </td>
                    
                  <tr>
                    <td colspan="2"></td>
                  </tr>   
                    
    
                  <tr>
                    <td>Новый пароль</td>
                    <td><input type="password" name="passwd" placeholder="" value="<?=$data['passwd']?>"<?=$e['passwd']?> /></td>
                  </tr>   
                    
                  <tr>
                    <td>Повторите новый пароль</td>
                    <td><input type="password" name="passwd2" placeholder="" value="<?=$data['passwd2']?>"<?=$e['passwd2']?> /></td>
                  </tr>   
                    
                    
                  </tr>
            </table>
            
            <br clear="all">
            <center><input type="submit" value="<?=$CFG->Locale["ps20"];?>" class="btn none" /></center>
            </form> 
         </div>
    </article>
</div>