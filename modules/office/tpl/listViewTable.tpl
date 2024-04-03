

<div class="customer">

    <div class="title">
        <div class="col-md-1 th">№</div>
        <div class="col-md-1 th"></div>
        <div class="col-md-3 th" style=" width:38%">Название компании</div>
        <div class="col-md-1 th">Дата созд.</div>
        <div class="col-md-1 th">Дата измен.</div>
        <div class="col-md-2 th">Автор записи</div>
	</div>
           <?
            for ($i=0; $i<sizeof($l); $i++)
                {
                    $o = $l[$i];
            ?>
    <div class="row">
        <div class="col-md-1 td"><div<?=$OFFICE->newsColor($o->edate, $o->cdate);?>>*<?=$o->id?></div></div>
        <div class="col-md-1 td"><a href="/office/<?=$o->id?>"><? logo_company($o->logo_company_mini);?></a></div>
        <div class="col-md-3 td" style=" width:38%"><a href="/office/<?=$o->id?>"><?=$o->name_company;?></a> <? if($o->access_id == 1) {?><br>(<span style="color:#F74140"><?=$CFG->Locale['fp67'];?></span>)<? } ?> </div>
        <div class="col-md-1 td"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY");?> <span><? echo dateSQL2TEXT($o->cdate, "hh:mm");?></span></div>
        <div class="col-md-1 td"><? if($o->edate == null) {echo '-';} else echo dateSQL2TEXT($o->edate, "DD.MM.YY").'<span>'.dateSQL2TEXT($o->edate, "hh:mm").'</span>';?></div>
        <div class="col-md-2 td"><a href="/profile/view/<?=$o->manager_id;?>"><? echo SelectDataName('users', 'name', $o->manager_id);?></a> </div>
    </div>



           <?
                }
            ?>


</div>
