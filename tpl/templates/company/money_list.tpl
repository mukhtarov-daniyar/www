<br>
<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Бухгалтерия</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>


<div class="white">

    <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#record" data-toggle="tab">Самоначисления +</a></li>
          <li><a href="#deal" data-toggle="tab">Самоначисления -</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="record">
                <div class="respons_news" style="padding-left:20px;">
                	<br clear="all">
                    <?
                        $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE visible='1' AND user_id = '{$CFG->USER->USER_ID}' ");

                        for ($i=0; $i<sizeof($sql); $i++)
                        {
                            $pid .= $sql[$i]->id.', ';
                        }
                        $and_id = trim($pid, ", ");


                        $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_list WHERE visible='1' AND user_id > 0  AND manager_id IN({$and_id}) AND cdate >= '2019-05-01 00:00:00' order by cdate DESC");
                        for ($i=0; $i<sizeof($sql); $i++)
                        {
                            $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}users WHERE id='{$sql[$i]->manager_id}'");
                            $comment = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE id='{$sql[$i]->coment_id}'");

                            ?>
                                <a href="<?=getFullXCodeByPageId($comment->parent_id);?><?=$comment->page_id;?>#comment-post_<?=$comment->id;?>"><?=$o->name;?>, <? echo dateSQL2TEXT($sql[$i]->cdate, "DD MN hh:mm");?> - <strong><?=number_sum($sql[$i]->count);?> <?=$CFG->USER->USER_CURRENCY;?> </strong></a><br>
                            <?
                        }
                    ?>


                </div>
            </div>

            <div class="tab-pane" id="deal">
                <div class="respons_news" style="padding-left:20px;">
                	<br clear="all">
                    <?
                        $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE visible='1' AND user_id = '{$CFG->USER->USER_ID}' ");

                        for ($i=0; $i<sizeof($sql); $i++)
                        {
                            $pid .= $sql[$i]->id.', ';
                        }
                        $and_id = trim($pid, ", ");


                        $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_minus_list WHERE visible='1' AND user_id > 0 AND manager_id IN({$and_id}) AND cdate >= '2019-05-01 00:00:00' order by cdate DESC");
                        for ($i=0; $i<sizeof($sql); $i++)
                        {
                            $o = getSQLRowO("SELECT name FROM {$CFG->DB_Prefix}users WHERE id='{$sql[$i]->manager_id}'");
                            $comment = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE id='{$sql[$i]->coment_id}'");

                            ?>
                                <a href="<?=getFullXCodeByPageId($comment->parent_id);?><?=$comment->page_id;?>#comment-post_<?=$comment->id;?>"><?=$o->name;?>, <? echo dateSQL2TEXT($sql[$i]->cdate, "DD MN hh:mm");?> - <strong><?=number_sum($sql[$i]->count);?> <?=$CFG->USER->USER_CURRENCY;?> </strong></a><br>
                            <?
                        }
                    ?>

                 </div>
            </div>
       </div>

    </div>
</div>
