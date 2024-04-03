<div class="text">
	<form method="GET" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>" class="filter">

        <div class="kriteri" style="margin-right:10px;">
        		<input style="width:480px;" type="text" name="login" placeholder="<?=$CFG->Locale["seeker1"];?>" value="<?=$data['login']?>"<?=$e['login']?>>
        </div>
        
		<input type="submit" value="<?=$CFG->Locale["search"];?>" id="btnS" style="padding:11px 40px; display:inline-block;">

	</form>
</div> 
