
<? 
	$MODULE_ = getFullXCodeByPageId($CFG->pid); 
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
    
?>

<div class="row tab">
    <div class="col-md-6">
        <h1><a href="/<?=$CFG->SYS_LANG_NAME?>/<?=$CFG->oPageInfo->xcode?>/step_1/" class="active">Я соискатель</a></h1>
    </div>    	
    <div class="col-md-6">
        <h1 class="active"><a href="/<?=$CFG->SYS_LANG_NAME?>/<?=$CFG->oPageInfo->xcode?>/step_2/">Я работодатель</a></h1>
    </div>
</div>



<form name="register" method="POST" enctype="multipart/form-o" action="<?=$_SERVER['REQUEST_URI']?>" class="contact-form">
    <input type='hidden' name='user_act' value='save_user' />
    <input type='hidden' name='status' value='0' />
    <input type='hidden' name='cdate' value='<?=sqlDateNow();?>' />
        		
    <div class="row join">
    
        <br clear="all">
        <br clear="all">
        <br clear="all">
    
        <div class="col-md-3 avatar">
            <div class="boxPhotoProfile">
                <a href="#" id="upAvatar" class="change"><img src="/tpl/img/noavatar.png<?=$o['avatar'];?>" alt=""></a>
            </div>
        </div> 
            
        <div class="col-md-6 o">
            <p class="brend">Все поля обязательными для заполнения</p>
    
            <p><?=$CFG->Locale["site-local21"]?>
            <input type="text" name="login" value="<?=$o['login']?>"<?=$e['login']?> tooltipText="<?=$CFG->Locale["tooltipText1"]?>" /></p>
            
            <p><?=$CFG->Locale["password"]?>
            <input type="password" name="passwd" value="<?=$o['passwd']?>"<?=$e['passwd']?> /></p>
            
            <p><?=$CFG->Locale["site-local9"]?>
            <input type="password" name="passwd2" value="<?=$o['passwd2']?>"<?=$e['passwd2']?> /></p>
        </div>   
        
        <div class="col-md-3">
        </div> 
        <br clear="all">
        <br clear="all">
        <br clear="all">
    
    
    
    
    
        <div class="col-md-2">
        </div>  
        
        <div class="col-md-8">
            Краткая информация
            <hr>
        </div>  
        
        <div class="col-md-2">
        </div>  
        
        
        
        
        
        
        <div class="col-md-3 avatar">
        </div> 
            
        <div class="col-md-6 o">
            <p><?=$CFG->Locale["zakup_v2"]?>
            <input type="text" name="name" value="<?=$o['name']?>"<?=$e['name']?> /></p>
             
            <p><?=$CFG->Locale["zakup_v1"]?>
            <input type="text" name="lastname" value="<?=$o['lastname']?>"<?=$e['lastname']?> /></p>
            
            <p><?=$CFG->Locale["ps11"];?> 
            <select name="pauls"<?=$e['pauls']?> class="selectpicker">
                   <? $sex = array(0=>$CFG->Locale["ps12"], 1=>$CFG->Locale["ps13"], 2=>$CFG->Locale["ps14"]);
                    for($z=0; $z<sizeof($sex); $z++)
                    {
                        ($o['pauls'] == $z) ? $sel = "selected" : $sel = "";?>
                        <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
                   <? } ?>
                    </select></p>
             
             <p><?=$CFG->Locale["ps6"];?>
             <input id="type-vse-date" type="text" name="dob" value="<?=$o['dob']?>"<?=$e['dob']?> /></p>
               
             <p><?=$CFG->Locale["ps33"]?>
             <input type="text" class="mobile" name="mobile" value="<?=$o['mobile']?>"<?=$e['mobile']?> /></p>
             
             <p><?=$CFG->Locale["ps15"];?>
             <select name="city"<?=$e['city']?>  class="selectpicker">
                   <? 
                    $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
                    for($i=0;$i<sizeof($city);$i++)
                    {	
                        ($o['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                        <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
                  <? } ?>
                    </select></p> 
        </div>   
    
        
        <div class="col-md-3">
        </div> 
        
        
        
        
        <br clear="all">
        <br clear="all">
        <br clear="all">
        
        
        
        
        <div class="col-md-2">
        </div>  
        
        <div class="col-md-8">
            Образование
            <hr>
        </div>  
        
        <div class="col-md-2">
        </div>  
        
       
       
        <div class="col-md-3">
        </div>   	
        <div class="col-md-6 o">
            <p><?=$CFG->Locale["ps44"];?>
            <select name="country_training"  class="selectpicker">
               <? 
                $country_training = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}country_training  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY name ASC");
                for($i=0;$i<sizeof($country_training);$i++)
                {	
                    ($o['country_training'] == $country_training[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                 <option value="<?=$country_training[$i]->id?>"<?=$sel?>><?=$country_training[$i]->name?></option>
               <? } ?>
             </select></p>
          
            <p>Учебное заведение
            <input type="text" name="school" placeholder="" value="<?=$o['school']?>"<?=$e['school']?> /></p>
        
            <p>Годы обучения
            <select name="on_training" style="width:100px !important; position:static !important; padding:8px;">
            <? for($z=1991; $z<date("Y"); $z++)
                {
                    ($o['on_training'] == $z) ? $sel = "selected" : $sel = "";?>
                    <option value="<?=$z?>"<?=$sel?>><?=$z?></option>
               <? } ?>
             </select>
             
            &nbsp;&nbsp;до
            <select name="off_training" style="width:100px !important; position:static !important; padding:8px;">
            <? for($z=1995; $z<date("Y")+4; $z++)
                {
                    ($o['off_training'] == $z) ? $sel = "selected" : $sel = "";?>
                    <option value="<?=$z?>"<?=$sel?>><?=$z?></option>
               <? } ?>
             </select>
             </p>
     
            <p>Стипендиат программы "Болашак"
                <select name="scholar"  class="selectpicker">
                <? $sex = array(0=>'Нет', 1=>'Да');
                for($z=0; $z<sizeof($sex); $z++)
                {
                    ($o['scholar'] == $z) ? $sel = "selected" : $sel = "";?>
                    <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
               <? } ?>
                </select>
             </p>
    
        </div>   
        <div class="col-md-3">
        </div> 
      
       
       
          
        <br clear="all">
        <br clear="all">
        <br clear="all">
        
        
        
        
        <div class="col-md-2">
        </div>  
        
        <div class="col-md-8">
            Карьера
            <hr>
        </div>  
        
        <div class="col-md-2">
        </div>  
        
     
       
        <div class="col-md-3">
        </div>   	
        <div class="col-md-6 o">
            <p><?=$CFG->Locale["ps35"];?>
            <select name="sphere" class="selectpicker">
               <? 
                $sphere = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}sphere  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY name ASC");
                for($i=0;$i<sizeof($sphere);$i++)
                {	
                    ($o['sphere'] == $sphere[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$sphere[$i]->id?>"<?=$sel?>><?=$sphere[$i]->name?></option>
              <? } ?>
             </select>
            </p>
    
            <p><?=$CFG->Locale["ps30"];?>
            <select name="experience" class="selectpicker">
                <? $sex = array(0=>$CFG->Locale["ps51"], 1=>$CFG->Locale["ps52"], 2=>$CFG->Locale["ps53"], 3=>$CFG->Locale["ps54"]);
                    for($z=0; $z<sizeof($sex); $z++)
                    {
                        ($o['experience'] == $z) ? $sel = "selected" : $sel = "";?>
                        <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
                   <? } ?>
             </select>
            </p>
    
            <p><?=$CFG->Locale["ps50"];?>
            <select name="employment" class="selectpicker">
                <? $sex = array(0=>$CFG->Locale["ps45"], 1=>$CFG->Locale["ps46"], 2=>$CFG->Locale["ps47"], 3=>$CFG->Locale["ps48"], 4=>$CFG->Locale["ps49"]);
                for($z=0; $z<sizeof($sex); $z++)
                {
                    ($o['employment'] == $z) ? $sel = "selected" : $sel = "";?>
                    <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
               <? } ?>
            </select>
            </p>
    
            <p><?=$CFG->Locale["ps36"];?>
            <select name="salary" class="selectpicker">
               <? $sex = array(0=>$CFG->Locale["ps37"], 1=>$CFG->Locale["ps38"], 2=>$CFG->Locale["ps39"], 3=>$CFG->Locale["ps40"], 4=>$CFG->Locale["ps41"], 5=>$CFG->Locale["ps42"], 6=>$CFG->Locale["ps43"]);
                for($z=0; $z<sizeof($sex); $z++)
                {
                    ($o['salary'] == $z) ? $sel = "selected" : $sel = "";?>
                    <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
               <? } ?>
            </select>
            </p>
    
            <p><?=$CFG->Locale["ps55"];?>
            <input type="text" name="resume" value="<?=$o['resume']?>"<?=$e['resume']?> />
            </p>
            
             <button type="submit" class="btn"><?=$CFG->Locale['ps20']?></button>
    
        </div>   
        <div class="col-md-3">
        </div> 
    </div>

    
</form> 


