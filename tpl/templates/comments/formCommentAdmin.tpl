<style>
/* COMMENTS VIEW */
.commentView { width:650px; clear:both; margin-left:35px; min-height:150px; padding:15px; margin:auto;  }
.commentView .userAvatar { width:110px; height:110px; float:left; border:solid 3px #61c3a8; background-color:#fff; margin-right:30px; }
.commentView .userName { font-size:17px; line-height:0px; color:#61c3a8; }
.commentView .textComment { padding-top:10px; text-align:justify; }
</style>

<?
    $newsInfo = new News($o->page_id);
    $news = $newsInfo->getObject($o->parent_id);
?>

<div class="commentView" id="post_<?=$o->id?>">

	<div style="padding:0 0 20px 0; text-align:center;">
		<strong>В теме: </strong><a target="_blank" href="<?=getFullXCodeByPageId($o->page_id)?><?=$o->parent_id?>#post_<?=$o->id?>"><?=$news->name?></a>  
	</div>
    
   <div class="userAvatar">
  <? if(!$user['guest']) { ?>
      <img src="<?=$user['avatar']?>" width="110"/>
  <? }else{ ?>
      <img src="/documents/avatar/noavatar.gif" width="110"/>
  <? } ?>     
   </div> 
   <div class="projectText">
	  <span class="userName"><?=$user['name']?></span>
	  <strong class="dateTime"><?=dateSQL2TEXT($o->date, "DD MN H:m / YYYY")?></strong>  
	  <div class="textComment"><?=nl2br(hs($o->body))?></div>
   
<? if($o->attachment != '') ?>
	<div style="float:right; vertical-align:top;">
	Прикрепленные файлы:
	<?
	$attach = explode(",", $o->attachment);
	for($i=0;$i<sizeof($attach);$i++)
	{ 
	?>
		<a id="review" style=" padding:7px 0 7px 0;" href="/<?=$CFG->GAL->makePreviewName($attach[$i], 600, 450, 2)?>"><img style="width:70px; margin-right:15px;" class="green_b" src="/<?=$CFG->GAL->makePreviewName($attach[$i], 70, 50, 2)?>" /></a>   
	<? 
	} 
	?>  
  	</div>
   </div>	
</div>
