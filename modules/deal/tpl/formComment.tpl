
<div class="comment-block__comment-post" id="comment-post_<?=$o->id?>">
    <div class="data_user">
    
        <div class="name">
            <span class="name"><?=SelectData('users', $o->user_id, 0);?></span> |
            <span class="data"><?=dateSQL2TEXT($o->cdate, "DD MN YYYY, H:i ")?></span>
        </div> 
    
        <div class="comment-post-body">
        <?=$o->text;?>
        </div> 
    
    </div>
</div>