

<div class="customer">

    <div class="title">
        <div class="col-md-1 th">№</div>
        <div class="col-md-1 th"></div>
        <div class="col-md-3 th">Название компании</div>
        <div class="col-md-1 th">Дата созд.</div>
        <div class="col-md-1 th">Дата измен.</div>
        <div class="col-md-2 th">Город</div>
        <div class="col-md-2 th">Автор</div>
	</div>
           <?
            for ($i=0; $i<sizeof($l); $i++)
                {
                    $o = $l[$i];
            ?>
    <div class="row">
        <div class="col-md-1 td"><div style="white-space:nowrap; display:inline-block; color:#999; "><?=$i+1;?></div> <div<?=$NEWS->newsColor($o->edate, $o->cdate);?> style=" display:inline-block;">*<?=$o->id?></div></div>
        <div class="col-md-1 td"><? logo_company($o->logo_company_mini);?></div>
        <div class="col-md-3 td"><a href="/record/<?=$o->id?>"><?=$o->name_company;?></a> <? if($o->access_id == 1) {?><br>(<span style="color:#F74140">Скрытая запись</span>)<? } ?> </div>
        <div class="col-md-1 td"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY");?> <span><? echo dateSQL2TEXT($o->cdate, "hh:mm");?></span></div>
        <div class="col-md-1 td"><? if($o->edate == null) {echo '-';} else echo dateSQL2TEXT($o->edate, "DD.MM.YY").'<span>'.dateSQL2TEXT($o->edate, "hh:mm").'</span>';?></div>
        <div class="col-md-2 td"><? $city = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}city WHERE visible='1' and id='{$o->city_id}'");	echo $city->name; ?></div>
        <div class="col-md-2 td"><? $resp = SelectDataNameArray('users', 'name, id', $o->manager_id); ?>
          <a href="/profile/view/<?=$resp->id?>#view-notes" target="_blank" style="text-decoration:underline"><?=$resp->name?></a>
        </div>
    </div>



           <?
                }
            ?>


</div>
