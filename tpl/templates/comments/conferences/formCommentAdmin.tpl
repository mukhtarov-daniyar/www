
<!--ANSWER<?=$o->id?> START-->
<div class="text" style="width:600px; margin:auto;">
    <div class="mon_inner">
        <div style="background-color:#fff; padding:10px; border:1px #CCCCCC solid;">
            <div style="border-bottom:1px #666 dashed;">
                <strong><?=hs($user["name"])?> (<?=hs($user["email"])?>)<? if($user["guest"]) echo " (Гость)"; ?></strong>
                <strong style="float:right;"><?=dateSQL2TEXT($o->cdate, "DD.MM.YYYY H:m")?></strong>   
            </div>  
            <div style="padding-top:10px;">    
                <strong>Вопрос:</strong>
                <div style="text-indent:20px;">
                   <?=nl2br($o->answer)?>
                </div>
            </div>
        </div>
       <? if($o->request != '') { ?>
        <div style="background-image:url('/tpl/img/bg_black.png'); padding:10px; border-radius:0 0 5px 5px;">
            <strong><font color="white">Ответ:</font></strong>
            <div style="text-indent:20px;">
               <?=nl2br($o->request)?>
            </div>
        </div> 
       <? } ?>        
    </div>
</div>

<!--ANSWER<?=$o->id?> END-->
