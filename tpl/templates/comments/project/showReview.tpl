<div class="comment-block__comment-post comment-post_size-<?=$o->level?>" id="comment-post_<?=$o->id?>">
	<? 
    if($user['avatar'] == '') 
    {
    	$user['avatar'] = '/tpl/img/avatar/no_avatar.gif'; 
    }    
    ?>
    
    <img class="comment-post__avatar border-green-3" src="<?=$CFG->GAL->makePreviewName($user['avatar'], 25, 25, 2)?>" />
    
    <div class="comment-post__header">
      <span class="comment-post-header__author font-upper-weight"><?=$user['name']?></span>,
      <span class="comment-post-header__date font-upper-light"><?=dateSQL2TEXT($o->cdate, "DD MN H:m / YYYY")?></span>   
    </div>
    
    <div class="comment-post__post-text">
       <?=nl2br(hs($o->body))?>
    </div>
    
    <div class="elem-fly-right elem-both">
			<?			
				$USERRATE = new myRatingFromComment($this->pageid);
				$USERRATE->setPostId($this->id);		
				$USERRATE->setUserPostId($count);				
				$USERRATE->setSource($this->cache["summary"]);
			?>
			<div class="rate-1-<?=$o->id?> elem-both">      
			<? 
				$USERRATE->setType(1);
				$USERRATE->showStaticForm($USERRATE->getRate(), 'rate-1-' . $o->id);
			?>
			</div>
			<div class="rate-2-<?=$o->id?> elem-both">      
			<? 
				$USERRATE->setType(2);
				$USERRATE->showStaticForm($USERRATE->getRate(), 'rate-2-' . $o->id);
			?>
			</div>      
			<div class="rate-3-<?=$o->id?> elem-both">      
			<? 
				$USERRATE->setType(3);
				$USERRATE->showStaticForm($USERRATE->getRate(), 'rate-3-' . $o->id);
			?>
			</div>    
    </div>    
    
   <?
    if($o->attachment !== '')
    {
    
		$o->attachment = explode(',', $o->attachment);
    
    ?>
    
    <div class="comment-post__attachments">
    
   <?
    
    for($i=0; $i<sizeof($o->attachment); $i++)
    {
    	if($o->attachment[$i] !== '')
        {
            $image = $o->attachment[$i];
        
            $mini = $CFG->GAL->makePreviewName($image, 110, 80, 2);
            $full = $CFG->GAL->makePreviewName($image, 600, false, 2);
            
            echo '<a href="'.$full.'" rel="gallery"><img class="attachments__image border-green-3" src="'.$mini.'" /></a>';  
        }   
    }
    ?>
    
    </div>
    
   <?
    } 
    ?>
    
</div>