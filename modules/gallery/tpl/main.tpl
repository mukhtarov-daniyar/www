
                                                                    <li>
                                                                            <div class="imgBlogGal">
                                                                                <a href="<?=$CFG->oPageInfo->_xcode?><?=$o->id?>"><img src="<?=$img?>" alt="<?=$o->name?>"/></a>
                                                                                <a href="<?=$CFG->oPageInfo->_xcode?><?=$o->id?>" class="maskImg" title="<?=$o->name?>" rel="gal_2"><?=$o->name?></a>
                                                                                <div id="videoBlogOpen<?=$i+1?>">
                                                                                    <img src="<?=$o->gallery?>" width="800" alt="<?=$o->name?>" />
                                                                                </div>
                                                                            </div>
                                                                            <p class="date2"><?=dateSQL2TEXT($o->cdate, "DD MN YYYY");?></p>
                                                                            <a href="<?=$CFG->oPageInfo->_xcode?><?=$o->id?>" class="name"><?=$o->name?></a>
                                                                        </li>	
 