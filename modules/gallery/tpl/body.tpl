<?

	echo $o->body;
	if ($o->body == '') {}
	else{ echo '<br /><br /><hr />';}
	$gallery = explode(",", $o->gallery);
	for ($n=0; $n<sizeof($gallery); $n++)
	{
	$images = $GAL->makePreviewName($gallery[$n], 255, 255, 2);?>
	



                                                                    <li>
                                                                            <div class="imgBlogGal">
                                                                                <a href="#"><img src="<?=$images?>" alt="<?=$o->name?>"/></a>
                                                                                <a href="#videoBlogOpen<?=$n+1?>" class="maskImg fancybox" title="<?=$o->name?>" rel="gal_2"><?=$o->name?></a>
                                                                                <div id="videoBlogOpen<?=$n+1?>">
                                                                                    <img src="<?=$gallery[$n]?>" alt="<?=$o->name?>" />
                                                                                </div>
                                                                            </div>
                                                                        </li>	

	<?}?>



