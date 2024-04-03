<div class="customer">
	
    
    
    
    <div class="title">
        <div class="col-md-1 th">№</div>    	  	
        <div class="col-md-1 th"></div>    	  	
        <div class="col-md-3 th">Название компании</div>    	  	
        <div class="col-md-2 th">Дата созд.</div>    	  	
        <div class="col-md-2 th" style="width:18%;">Дата редакт.</div>    	  	   	  	
        <div class="col-md-2 th" style="width:20%;">Посл. правка</div>    	  	
	</div>
           <?
            for ($i=0; $i<sizeof($l); $i++)
                {
                    $o = $l[$i];   
            ?>
    <div class="row">       
        <div class="col-md-1 td"><div<?=$NEWS->newsColor($o->edate, $o->cdate);?>>*<?=$o->id?></div></div>    	  	
        <div class="col-md-1 td"><a href="<?=getFullXCodeByPageId($o->page_id);?><?=$o->id?>"><? echo logo_company($o->logo_company);?></a></div>    	  	
        <div class="col-md-3 td"><a href="<?=getFullXCodeByPageId($o->page_id);?><?=$o->id?>"><?=$o->name_company;?></a></div>    	  	
        <div class="col-md-2 td"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY");?> <span><? echo dateSQL2TEXT($o->cdate, "hh:mm");?></span></div>    	  	
        <div class="col-md-2 td" style="width:18%; text-align:center"><? if($o->edate == null) {echo '-';} else echo dateSQL2TEXT($o->edate, "DD.MM.YY").'<span>'.dateSQL2TEXT($o->edate, "hh:mm").'</span>';?></div>    	  	  	
        <div class="col-md-2 td" style="width:20%;"><? $resp = SelectDataRowOArray('users',$o->manager_id,0);   
         	$response = AndDataArray('comments', 'page_id', $o->id, 1, "cdate DESC");
                 
                 if(count($response) > 0)
                 {	
                    $user = SelectDataRowOArray('users', $response[0]->user_id, 0);
                    ?>
                    	<a href="/profile/view/<?=$user->id?>#view-notes" target="_blank" style="text-decoration:underline"><?=$user->name?></a>
                    <?
                 
                 }
                 else 
                 {
                 	?>
                    	<a href="/profile/view/<?=$resp->id?>#view-notes" target="_blank" style="text-decoration:underline"><?=$resp->name?></a>
                
                <? } ?>     </div>    	  	
    </div>       
             
                
    
           <?
                }
            ?>


</div>
  