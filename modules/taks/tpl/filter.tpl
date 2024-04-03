<div class="text">
	<form method="GET" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>" class="filter">

        <div class="kriteri" style="margin-right:10px;">
            <span>Выбрать из списка:</span>
            <select name="status">
           <? 
            $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE visible='1' ORDER BY id ASC");
            for($i=0;$i<sizeof($city);$i++){?>
            	<option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
          <? } ?>
            </select>
        </div>
        
		<input type="submit" value="Добавить" id="btnS" style="padding:7px 40px; display:inline-block;">

	</form>
</div> 
