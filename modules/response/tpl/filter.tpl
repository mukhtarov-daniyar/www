<?

$MODULE_ = getFullXCodeByPageId($CFG->pid); 
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();

?>

<div class="text">
	<form method="GET" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>" class="filter">
    
		<input type="text" name="search" placeholder="Поиск" value="<?=$data['search']?>"<?=$e['search']?>>
 

        <? if($CFG->USER->USER_STATUS == 3) { ?>
         
        
                <div class="kriteri">
                    <span><?=$CFG->Locale["VACANCIES"];?>:</span>
                    <select name="vacancy">
                     <option value="0"><?=$CFG->Locale["fi1"];?></option>
                    <? 
                     
                    
                     
                     	$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE user_id='{$CFG->USER->USER_ID}'";
						$y = getSQLArrayO($sql);
                        
                        for($x=0; $x<sizeof($y); $x++)
                        {  
                        	 ($data['vacancy'] == $y[$x]->id) ? $sel = "selected" : $sel = "";
                             
  
                        ?>
                            <option value="<?=$y[$x]->id?>"<?=$sel?>><?=$y[$x]->name?></option>
                       <?}  ?>
                   </select>
                </div>   
                
                
                   
       <? } ?>

		<input type="submit" value="Поиск" id="btnS" style="padding:7px 40px;">

	</form>
</div> 
    