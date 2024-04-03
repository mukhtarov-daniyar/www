 ﻿<? $GAL = new Gallery();?>
<!-- BEGIN CONTENT -->

	<div class="content">
		<div class="wrapper">
			<div class="mainCont">
				<div class="boxTitle"><h1><?=$CFG->oPageInfo->name?></h1></div>				
				<div class="boxBlog">					
					<div class="boxAnswers">
						<div class="tabAnmswer">
							<ul class="nav">
                            
	<?
        $sql = "SELECT id,name,xcode FROM {$CFG->DB_Prefix}pages WHERE parent_id='{$CFG->oPageInfo->parent_id}' AND visible='1' AND sys_language='{$CFG->SYS_LANG}' ORDER BY pos";
        $l1 = getSQLArrayO( $sql );
        for ($i1=0; $i1<sizeof($l1); $i1++)
        {
            $o1 = $l1[$i1];
            ($o1->id == $CFG->oPageInfo->id) ? $active =' current' : $active=""; 
    ?>
            <li><a id="cat_<?=$i1+1;?>" class="ymaps<?=$active?>" href="<?=getFullXCodeByPageId($o1->id);?>" title="<?=$o1->name;?>"><img src="/tpl/img/icon_<?=$o1->xcode;?>.png" width="18" height="16" alt=""> <span><?=$o1->name;?></span> <span class="border"></span></a></li>
   <? }?>
							</ul>
							<div class="clear"></div>
						</div>
						<div class="contAnswer">

								<div class="listBlog">
									<ul class="listPhotoGal">
                                    
									<?
                                    
                                    $GAL = new Gallery();
                                        
                                            if($CFG->_GET_PARAMS[0] <= 0)
                                            {
                                                $cnt3 = $GAL->getCount($CFG->pid);
                                                $pager = new Pager($cnt3, 12);
                                                $l = $GAL->getList($CFG->pid, $pager->pp, $pager->start, $langs.$sport);
                                                
                                                for ($i=0; $i<sizeof($l); $i++)
                                                {
                                                    $o = $l[$i];
                                                    $gallery = explode(",", $o->gallery);
                                                    $img = makePreviewName($gallery[0], 255, 255, 2);
                                                    
                                                    include('tpl/main.tpl');
                                                }
                                                    
                                                echo '<div style=" clear:both"></div>';	
                                                    
                                                if(empty($l)) 
                                                {
                                                    echo '<div style="text-align:center;padding-bottom:30px;">'.$CFG->Locale['search_nothing'].'</div>';
                                                }
                                                
                                         
                                    
                                            }
                                            else
                                                if(is_numeric($CFG->_GET_PARAMS[0]))
                                                {
                                                
                                                
                                                    $o = $GAL->getObject($CFG->pid, $CFG->_GET_PARAMS[0]);
                                                    $CFG->oPageInfo->html_title = $o->name;
                                                    $CFG->oPageInfo->html_keywords = strip_tags($o->name);
                                                    $CFG->oPageInfo->html_description = utf8_substr(strip_tags($o->body), 0, 400);
                                                    
                                                    include('tpl/body.tpl');
                                                }
                                                else
                                                {
                                                    redirect($CFG->oPageInfo->_xcode);
                                                }
                                    
                                    
                                            
                                    ?>

									</ul>
								</div>
								<div class="clear"></div>	
						
							
						</div>						
					</div> 					
				</div>	
				<div class="top_60"></div>

				<? include("_pager.php"); ?>
				
			</div>	
		</div>		
	</div>	
			
<!-- CONTENT EOF   -->