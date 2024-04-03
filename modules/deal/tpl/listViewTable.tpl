

<style>
.customer { width:100%; margin:0; padding-top:20px; background:#FFFFFF;font-family:'segoeui';}
.customer .th{ white-space:nowrap;  text-align:center}

.customer .th:nth-child(1) { width:15%}
.customer .th:nth-child(2) { width:34%}
.customer .th:nth-child(3) { width:12%}
.customer .th:nth-child(4) { width:12%}
.customer .th:nth-child(5) { width:12%}
.customer .th:nth-child(6) { width:15%}

.customer .td:nth-child(1) { width:15%}
.customer .td:nth-child(2) { width:34%}
.customer .td:nth-child(3) { width:12%;text-align:left}
.customer .td:nth-child(4) { width:12%;text-align:left}
.customer .td:nth-child(5) { width:12%}
.customer .td:nth-child(6) { width:15%}

.customer .td:nth-child(3) span {
    color: #999;
    display: block;
}

</style>




<div class="customer">

    <div class="title">
        <div class="col-md-1 th">№</div>
        <div class="col-md-4 th">Название компании</div>
        <div class="col-md-1 th">Дата созд.</div>
        <div class="col-md-1 th">Дата измен.</div>
        <div class="col-md-2 th">Сумма</div>
        <div class="col-md-2 th">Автор сделки</div>
	</div>
           <?


            for ($i=0; $i<sizeof($l); $i++)
                {

            ?>
    <div class="row">
        <div class="col-md-1 td"><div<?=$DEAL->newsColor($l[$i]->edate, $l[$i]->cdate);?>>*<?=$l[$i]->id?></div></div>
        <div class="col-md-4 td"><a href="/deal/<?=$l[$i]->id?>"><?=$l[$i]->name_company;?></a> <? if($l[$i]->access_id == 1) {?><br>(<span style="color:#F74140"><?=$CFG->Locale['fp67'];?></span>)<? } ?> </div>
        <div class="col-md-1 td"><? echo dateSQL2TEXT($l[$i]->cdate, "DD.MM.YY");?> <span><? echo dateSQL2TEXT($l[$i]->cdate, "hh:mm");?></span></div>
        <div class="col-md-1 td"><? if($l[$i]->edate == null) {echo '-';} else echo dateSQL2TEXT($l[$i]->edate, "DD.MM.YY").'<span>'.dateSQL2TEXT($l[$i]->edate, "hh:mm").'</span>';?></div>
        <div class="col-md-2 td">	<? echo  number_sum($l[$i]->price); echo ' '.$CFG->USER->USER_CURRENCY;?></div>
        <div class="col-md-2 td"><? $resp = SelectDataRowOArray('users',$l[$i]->manager_id,0); ?> <a href="/profile/view/<?=$resp->id;?>#view-notes" target="_blank" style="text-decoration:underline"><?=$resp->name;?></a></div>
    </div>



           <?
                }
            ?>


</div>
