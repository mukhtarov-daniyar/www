<? $NEWS = new News();




	if($CFG->USER->USER_VIEW_LOADER == 1)
	{
		redirect("/office/");

	}

?>

<h2><img alt="" src="/tpl/img/new/icon/1_red.png">Главная</h2>

<div class="white">

<article class="activity">
    <h3>Моя активность</h3>
    <div class="day scrollbar-dynamic">
        <div class="page-content">

            <div class="table-responsive">
                <?

								$sql = "SELECT
																my_news.name_company AS name_companys,
																my_comments.page_id, my_comments.text, my_comments.parent_id, my_comments.id, my_comments.cdate
																FROM my_comments LEFT JOIN my_news ON my_news.id=my_comments.page_id WHERE my_comments.visible='1' AND my_comments.user_id='{$CFG->USER->USER_ID}' ORDER BY my_comments.cdate DESC limit 50";

                $response = getSQLArrayO( $sql);

                ?>
                <table class="users activS">
                    <tr>
                        <th><strong>№</strong></th>
                        <th><strong>Дата заметки</strong></th>
                        <th><strong>Название компании</strong></th>
                        <th><strong>Заметка</strong></th>
                    </tr>
                           <?
                           $response = array_map("unserialize", array_unique( array_map("serialize", $response) ));

                            for ($i=0; $i<sizeof($response); $i++)
                                {
                                    $o = $response[$i];
                                    if($o->page_id)
                                    {
                           ?>
                    <tr>
                        <td><a href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>/"><div<?=$NEWS->newsColor($o->cdate, $o->cdate);?>><span>*<?=$o->page_id?></span></div></a></td>
                        <td class="cdate"><a href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>/#comment-post_<?=$o->id;?>"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY")?><br><span><? echo dateSQL2TEXT($o->cdate, "hh:mm")?></span></a></td>
                        <td class="name"><a href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>/#comment-post_<?=$o->id;?>"><? echo $o->name_companys;?> </a></td>
                        <td><a href="<?=getFullXCodeByCommentsId($o->parent_id);?><?=$o->page_id;?>/#comment-post_<?=$o->id;?>"><? echo utf8_substr(strip_tags($o->text), 0, 120);?></a></td>
                    </tr>

                   <? }

                   } ?>
                </table>
            </div>


        </div>
    </div>
 </article>


<article class="moneys">
    <h3>БАЛАНС</h3>
	<?
    $lik = money_count($CFG->USER->USER_ID);
    $dis = money_count_minus($CFG->USER->USER_ID);
    $sum = $lik + $dis;
    $plus = round($lik*100/$sum)*1;
    $min = round($dis*100/$sum)*1;

    if(is_nan($plus) && is_nan($min)) {$plus = 50; $min = 50;}
    ?>
    <div class="polls_text">
        <div class="lik"><?=$lik;?> <?=$CFG->USER->USER_CURRENCY;?>.</div>
        <div class="dis">-<?=$dis;?> <?=$CFG->USER->USER_CURRENCY;?>.</div>
    </div>

    <div class="polls">
        <div class="lik" style="width:<?=$plus;?>%"></div>
        <div class="dis" style="width:<?=$min;?>%"></div>
    </div>

    <br clear="all">

    <div class="day scrollbar-dynamic">
        <div class="page-content">
            <div class="table-responsive">
                <table>
                  <tr>
                    <th>Дата</th>
                    <th>Операция</th>
                    <th>Сумма</th>
                   </tr>
                    <?
                        $t = date('t')*1;
                        $y = date('Y')*1;
                        $m = date('m');
                        $query = "SELECT cdate,count,page_id, coment_id FROM {$CFG->DB_Prefix}money_list WHERE visible = 1 AND user_id = {$CFG->USER->USER_ID} AND cdate >= '{$y}-{$m}-01 00:00:00' AND cdate <= '{$y}-{$m}-{$t} 23:59:59' ORDER BY cdate DESC";
                        $o = getSQLArrayO($query);
                        for ($y=0; $y<sizeof($o); $y++)
                        {
                            $z = getSQLRowO("SELECT name_company FROM {$CFG->DB_Prefix}news WHERE id='{$o[$y]->page_id}'");
                            ?>
                             <tr>
                                <td><?=dateSQL2TEXT($o[$y]->cdate, "DD.MM.YY");?><span><?=dateSQL2TEXT($o[$y]->cdate, "hh:mm");?></span></td>
                                <td><? MoneyOperacions($o[$y]->count); ?> <a href="/record/<?=$o[$y]->page_id;?>#comment-post_<?=$o[$y]->coment_id;?>"><?=$z->name_company;?></a></td>
                                <td><? echo $o[$y]->count; ?> <?=$CFG->USER->USER_CURRENCY;?></td>
                            </tr>
                            <?
                        }
                    ?>
                 </table>
            </div>
        </div>
    </div>
 </article>


<article class="alarm" style="width:58%">
    <h3>Мои сделки</h3>
    <div class="day scrollbar-dynamic">
        <div class="page-content">

            <div class="table-responsive">
                    <?
                    $response = getSQLArrayO("SELECT cdate,id,name_company,price   FROM {$CFG->DB_Prefix}news WHERE manager_id='{$CFG->USER->USER_ID}' AND page_id=1000 AND visible=1 ORDER BY cdate DESC limit 30 ");
                    ?>
                    <table>
                      <tr>
                        <th>Дата</th>
                        <th>Операция</th>
                        <th>Сумма</th>
                       </tr>


                <?
                        for ($y=0; $y<sizeof($response); $y++)
                        {
                            ?>
                             <tr>
                                <td><?=dateSQL2TEXT($response[$y]->cdate, "DD.MM.YY");?> <span><?=dateSQL2TEXT($response[$y]->cdate, "hh:mm");?></span></td>
                                <td><a href="/deal/<?=$response[$y]->id;?>"><?=$response[$y]->name_company;?></a></td>
                                <td><? echo $response[$y]->price; ?> <?=$CFG->USER->USER_CURRENCY;?></td>
                            </tr>
                            <?
                        }

						?>

                    </table>
            </div>

        </div>
    </div>
 </article>

<br><br><br><br><br>


</div>
