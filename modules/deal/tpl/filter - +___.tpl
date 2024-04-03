<?

$MODULE_ = getFullXCodeByPageId($CFG->pid); 
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();

?>
    <div class="col-md-12 filter_hide block">
		<form method="GET" enctype="multipart/form-data" class="response">

            <div class="col-md-8">  
             <input type="text" name="search" value="<?=$data['search']?>" placeholder=" &nbsp;<?=$CFG->Locale['search']?>"  id="full_search_input"<?=$e['search']?>/>
            </div>
            
            <div class="col-md-4">   
                <button type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp; &nbsp; Поиск</button>
            </div>
        
            <div class="col-md-4">   
                <p><?=$CFG->Locale["ps35"];?>:</p>
                <select name="sphere" class="selectpicker">
               <? 
                $sphere = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}sphere  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
                for($i=0;$i<sizeof($sphere);$i++)
                {	
                    ($data['sphere'] == $sphere[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$sphere[$i]->id?>"<?=$sel?>><?=$sphere[$i]->name?></option>
               <? } ?>
                </select>
            </div>
             
            <div class="col-md-4">
                <p><?=$CFG->Locale["ps15"];?></p>
                <select name="city" class="selectpicker">
               <? 
                $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
                for($i=0;$i<sizeof($city);$i++)
                {	
                    ($data['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
              <? } ?>
                </select>
            </div>
          
             <div class="col-md-4">
                <p><?=$CFG->Locale["ps36"];?>:</p>
                <select name="salary" class="selectpicker">
                <? $sex = array(0=>$CFG->Locale["fi1"], 1=>$CFG->Locale["ps37"], 2=>$CFG->Locale["ps38"], 3=>$CFG->Locale["ps39"], 4=>$CFG->Locale["ps40"], 5=>$CFG->Locale["ps41"], 6=>$CFG->Locale["ps42"], 7=>$CFG->Locale["ps43"]);
                for($z=0; $z<sizeof($sex); $z++)
                {
                    ($data['salary'] == $z) ? $sel = "selected" : $sel = "";?>
                    <option value="<?=$z?>"<?=$sel?>><?=$sex[$z]?></option>
               <? } ?>
                </select>
            </div> 
                
             <div class="col-md-4">
                <p><?=$CFG->Locale["ps50"];?>:</p>
               <? $sex = array(0=>$CFG->Locale["fi2"], 1=>$CFG->Locale["ps45"], 2=>$CFG->Locale["ps46"], 3=>$CFG->Locale["ps47"], 4=>$CFG->Locale["ps48"], 5=>$CFG->Locale["ps49"]);
                for($z=1; $z<sizeof($sex); $z++)
                {
                    ($data['employment'] == $z) ? $sel = " checked" : $sel = "";?>
                    <label class="radio ct-orange"><input type="radio" name="employment" data-toggle="radio" value="<?=$z?>"<?=$sel?> /><?=$sex[$z]?></label>
               <? } ?>
            </div> 
            
             <div class="col-md-4">
                <p><?=$CFG->Locale["ps30"];?>:</p>
               <? $sex = array(0=>$CFG->Locale["fi2"], 1=>$CFG->Locale["ps51"], 2=>$CFG->Locale["ps52"], 3=>$CFG->Locale["ps53"], 4=>$CFG->Locale["ps54"]);
                for($z=1; $z<sizeof($sex); $z++)
                {
                    ($data['experience'] == $z) ? $sel = " checked='checked'" : $sel = "";?>
                    <label class="radio ct-orange"><input type="radio" name="experience" data-toggle="radio" value="<?=$z?>"<?=$sel?> /><?=$sex[$z]?></label>
               <? } ?>
            </div> 
            
             <div class="col-md-4">
                <p><?=$CFG->Locale["fi9"];?>:</p>
               <? $sex = array(0=>$CFG->Locale["fi2"], 1=>$CFG->Locale["fi6"], 2=>$CFG->Locale["fi7"], 3=>$CFG->Locale["fi8"]);
                for($z=0; $z<sizeof($sex); $z++)
                {
                    ($data['cdate'] == $z) ? $sel = " checked='checked'" : $sel = "";?>
                    <label class="radio ct-orange"><input type="radio" name="cdate" data-toggle="radio" value="<?=$z?>"<?=$sel?> /><?=$sex[$z]?></label>
               <? } ?>
            </div> 
    
        </form>  
	</div>
