
<style>
	.vk_list { display:block;border-bottom: 1px solid #ce1053; padding:15px 0 !important;}
	.vk_list .name{font-family: 'segoeui_sb';  text-align:center; display:block;}
	.vk_list .name a{ font-size:18px; color:#333; line-height:25px; text-decoration:underline; display:block;white-space:nowrap}
	.vk_list .name span{ font-size:12px; display:block; white-space:nowrap; text-align:center}

	.vk_list  .col-md-2 { text-align:center; color: #42648b;font-size:22px; line-height:20px;}
	.vk_list  .col-md-2  a{ color: #42648b;}
	.vk_list  .col-md-2 span{ font-family: 'segoeui'; text-align:center; font-size:14px; color:#7c7f82}
	.vk_list .act{font-family: 'segoeui'; font-size:13px; color:#333; margin-top:10px;}
	.vk_list .avatar { display:block; text-align:center;}
	.vk_list .avatar img { border-radius:50%; margin-top:15px; width:100%; border:solid 1px #CCCCCC}
	.vk_list  .col-md-10{font-family: 'segoeui'; font-size:12px; color:#333; padding-top:10px; padding-left:50px;}

	.vk_list  .jobday { display:block}
	.vk_list  a.namber { color:#CA1B56; display: inline-block;font-family: 'segoeui_b'; font-size:13px; text-decoration:underline}
	.vk_list  a.cdate { color:#468847; display: inline-block;font-family: 'segoeui_b'; font-size:12px; margin-left:10px; }
	.vk_list  a.text { font-family: 'segoeui'; color:#42648b; font-size:14px; display:inline-block; margin-left:10px; line-height:20px;}
	.vk_list .load_id { padding:7px 15px !important;font-family: 'segoeui';}

	.nav.nav-tabs li a{font-family:'segoeui_sb'; text-transform:uppercase; font-size:14px;}

@media screen and (min-width:100px) and (max-width:768px)
{
	.vk_list .avatar img {width:40%;}
	a.mailogo { display:none}
}
</style>


<h2>Информация по менеджерам </h2>
 <div class="white" style="overflow:hidden">


					<?
                  $l = getSQLArrayO("SELECT  id, name, vdate, visible, avatar, status, access, dismissed FROM {$CFG->DB_Prefix}users WHERE user_id='{$CFG->USER->USER_DIRECTOR_ID}' AND visible = 1 order by name,vdate DESC");

                    for ($i=0; $i<sizeof($l); $i++)
                    {
                        $o = $l[$i];
						?>
                        <div class="vk_list">
                                <div class="row join">
                                    <div class="col-md-3 name">
                                        <a href="/profile/view/<?=$o->id;?>/#view-notes"><?=$o->name;?></a><span><?=dateSQL2TEXT($o->vdate,"DD MN, hh:mm");?></span>
                                        <div class="col-md-12 avatar">
                                            <img src="<?=$o->avatar;?>">
                                        </div>
                                    </div>

                                    <div class="col-md-9 list user_<?=$o->id;?>">

																			<?
																				$res = getSQLArrayO("SELECT parent_id, page_id, id, cdate, text FROM {$CFG->DB_Prefix}comments WHERE user_id = '{$o->id}' AND visible = 1 AND access =0 AND text !='' order by cdate DESC LIMIT 11");

																				for ($z=0; $z<sizeof($res); $z++)
												                { ?>
		                										<div class="jobday" style="width:100%; overflow:hidden; white-space: nowrap">
																						<a href="<?=getFullXCodeByCommentsId($res[$z]->parent_id);?><?=$res[$z]->page_id;?>/#<?=$res[$z]->id;?>" class="namber">*<?=$res[$z]->page_id;?></a>
																						<a href="<?=getFullXCodeByCommentsId($res[$z]->parent_id);?><?=$res[$z]->page_id;?>/#<?=$res[$z]->id;?>" class="cdate"><? echo dateSQL2TEXT($res[$z]->cdate, "DD MN, hh:mm")?></a>
																						<a href="<?=getFullXCodeByCommentsId($res[$z]->parent_id);?><?=$res[$z]->page_id;?>/#<?=$res[$z]->id;?>" class="text"><? echo utf8_substr(strip_tags($res[$z]->text), 0, 55); ?></a>
																				</div>
																			<? } ?>

                                    </div>
                                </div>

																<? if(count($res) > 0) {?>
                                <div class="col-md-12 more">
                                    <div class="load_id users_<?=$o->id;?>" data-id="<?=$o->id;?>">Загрузить еще</div>
                                </div>
															<? } ?>






                                <br clear="all">
                        </div>
                     <? } ?>



	</div>
