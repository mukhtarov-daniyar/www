<? 
$thisXcode = getFullXCodeByPageId($CFG->pid); 
$e = $CFG->FORM->getFailInputs();
$z = $CFG->FORM->getFullForm();
?>

                            <form method="POST" enctype="multipart/form-data" action="/<?=$CFG->SYS_LANG_NAME?>/auth/party">
                                <input type="hidden" name="user_act" value="enter_men" />
                                <input type="hidden" name="page_id" value="<?=$CFG->pid?>" />
                                <input type="hidden" name="sys_language" value="<?=$CFG->SYS_LANG?>" />
                                <div class="boxField left"><input type="text" placeholder="<?=$CFG->Locale["zakup_v1"]?>" name="lastname" value="<?=$z['lastname']?>"<?=$e['lastname']?>></div>
                                <div class="boxField right"><input type="text" placeholder="<?=$CFG->Locale["zakup_v2"]?>" name="name" value="<?=$z['name']?>"<?=$e['name']?>></div>
                                <div class="clear"></div>
                                <div class="boxField"><input type="text" placeholder="<?=$CFG->Locale["ps15"]?>" name="city" value="<?=$z['city']?>"<?=$e['city']?>></div>
                                <div class="boxField left"><input type="text" placeholder="<?=$CFG->Locale["ps6"]?> xxxx-xx-xx" name="bdate" value="<?=$z['bdate']?>"<?=$e['bdate']?>></div>
                                <div class="boxField right"><input type="text" placeholder="E-mail" name="email" value="<?=$z['email']?>"<?=$e['email']?>></div>
                                <div class="clear"></div>
                                <div class="boxField"><textarea name="info" cols="1" rows="1" placeholder="<?=$CFG->Locale["site-local25"]?>"<?=$e['info']?>><?=$z['info']?></textarea></div>
                                <div class="clear"></div>
                                <input type="submit" value="<?=$CFG->Locale["site-local26"]?>"  class="button">
                            </form>
                            
