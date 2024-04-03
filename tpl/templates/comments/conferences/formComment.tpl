	<?
		$author = &$CFG->BOX;
	?>

<div class="comment-block__comment-post comment-post_size-<?=$o->level?>" id="comment-post_<?=$o->id?>">
	<? if($user['avatar'] == '') $user['avatar'] = '/tpl/img/avatar/no_avatar.gif'; ?>
    <img class="comment-post__avatar border-green-3" src="<?=$CFG->GAL->makePreviewName($user['avatar'], 25, 25, 2)?>" />
    
    <div class="comment-post__header">
      <span class="comment-post-header__author font-upper-weight"><?=$user['name']?></span>,
      <span class="comment-post-header__date font-upper-light"><?=dateSQL2TEXT($o->cdate, "DD MN H:m / YYYY")?></span>   
    </div>
    
    <div class="comment-post__post-text">
       <?=nl2br(hs($o->body))?>
    </div>
 
   <? 
	if($o->request != '') 
	{ 
	?>
		<div class="conference-reply__block">
			<?
				if($author->img !== '')
				{
					$avatar = $author->img;
				}
				else
				{
					$avatar = '/documents/avatar/noavatar.gif';
				}
			?>
			<img src="<?=$avatar?>" class="conference-reply__avatar border-green-2" />
	
			<div class="conference-reply_inner-block 1tv-ru-copyright">
				<div class="font-upper-weight font-size-18">
					<?=$author->name?>
				</div>
				<div class="conference-reply__text">
					<div class="conference-reply__abstract"></div>
					<?=$o->request?>
				</div>
			</div>
		</div>
	<?
	}
	?>
</div>