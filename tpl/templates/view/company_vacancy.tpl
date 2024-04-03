<? 
$news = getSQLRowO("SELECT user_id FROM {$CFG->DB_Prefix}news  WHERE id='{$CFG->_GET_PARAMS[0]}'");
$company = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}company  WHERE user_id='{$news->user_id}'");
?>
<div class="bunker">
    <h3><?=$company->name_company?></h3>
    <div class="stat">
    	<? if($company->logo_company)
        	{ ?>
            	<a href="/<?=$CFG->SYS_LANG_NAME?>/company/<?=$company->id?>"><img src="<?=$company->logo_company?>" alt="<?=$company->name_company?>"></a>
           <? }?>
    	<a href="/<?=$CFG->SYS_LANG_NAME?>/company/<?=$company->id?>"><?=utf8_substr(strip_tags($company->body), 0, 120); ?>...</a>
    </div>
</div>
