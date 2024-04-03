 
<!-- BEGIN CONTENT -->

	<div class="content">
		<div class="wrapper">
			<div class="mainCont">
				<div class="boxTitle"><?=showHeader($CFG->oPageInfo->name);?></div>				
				<div class="boxBlog">
					<div class="mainInfBlog">
						<div class="boxSiteMap">
                        	<ul>
							<?
                            
                                for ($i=0; $i<sizeof($aBlocks); $i++)
                                {
                                    showGenericBlock($aBlocks[$i], $aOptions);
                                }
                            
                                $m = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}pages WHERE {$CFG->lang_where_and} parent_id='{$CFG->pid}' AND visible='1' ORDER BY pos");
                                if (is_array($m))
                                {
                                    for ($i=0; $i<sizeof($m); $i++)
                                    {
                                        $url = getFullXCodeByPageId($m[$i]->id);
                                        if ($m[$i]->tmpl_u == "link")
                                            $url = getSQLField("SELECT url FROM {$CFG->DB_Prefix}docs WHERE {$CFG->lang} page_id='{$m[$i]->id}' AND visible='1' ORDER BY pos");
                                        $o = $m[$i];
                            
                            ?>
                            
                            <li class="end"><a href='<?=$url?>' class="map_menu"><?=hs($o->name)?></a></li>
                            
                           <?
                                    }
                                }
                            
                            ?>
							</ul>
						</div>
					</div>
				</div>			
			</div>	
		</div>		
	</div>	
			
<!-- CONTENT EOF   -->