<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>    	
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Менеджеры</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/statistics/" class="active">Статистика</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<div class="text">

	<div class='text'>
    

<br clear="all">


<style>
	.vk_list { display:block;border-bottom: 1px solid #ce1053; padding:15px 0;}
	.vk_list:hover { background:#F2F2F2;}
	.vk_list .name{font-family: 'Helvetica'; white-space:nowrap;} .vk_list .name a{ font-size:18px; color:#333; line-height:25px; text-decoration:underline;}
	.vk_list .name span{ display:block; font-size:10px} 
	
	.vk_list .row.join .col-md-2 { text-align:center; color: #42648b;font-size:22px; line-height:20px;}
	.vk_list .row.join .col-md-2  a{ color: #42648b;}
	.vk_list .row.join .col-md-2 span{ text-align:center; font-size:14px; color:#7c7f82}
	.vk_list .act{font-family: 'Helvetica'; font-size:13px; color:#333; margin-top:10px;}
	
	.vk_list .row.join .col-md-10{font-family: 'Helvetica'; font-size:12px; color:#333; padding-top:10px; padding-left:50px;}
	
	.vk_list .row.join .jobday { display:block}
	.vk_list .row.join a.namber { color:#CA1B56; display: inline-block;font-family: 'Helvetica_b'; font-size:13px; text-decoration:underline}
	.vk_list .row.join a.cdate { color:#468847; display: inline-block;font-family: 'Helvetica_b'; font-size:12px; margin-left:10px; }
	.vk_list .row.join a.text { color:#42648b; font-size:14px; display:inline-block; margin-left:10px; line-height:20px;}
</style>



    <? $l = AndDataArray('users', 'user_id',$CFG->_GET_PARAMS[1], 0, "vdate DESC");
        for ($i=0; $i<sizeof($l); $i++)
            { $o = $l[$i]; 
            
            ?>	   

<div class="vk_list">
	
        <div class="row join">
        
            <div class="col-md-2">
            	<div class="name"><a href="/profile/view/<?=$o->id;?>/#view-notes"><?=$o->name;?></a><span><?=dateSQL2TEXT($o->vdate,"DD MN, hh:mm");?></span></div>
            </div> 
                
            <div class="col-md-2">
               <a href="/record/?users=<?=$o->id;?>"><?=count(AndDataArray('news', 'manager_id', $o->id))?></a> <br> <span>записей</span> 
            </div> 
              
            <div class="col-md-2">
               <?=count(AndDataArray('comments', 'user_id', $o->id))?> <br> <span>заметок</span> 
            </div> 
              

            <div class="col-md-2">
                <? $s = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE user_id='{$o->id}' AND task=1"); echo count($s)?> <br> <span>задач</span> 
            </div>   
            <br clear="all">

            <div class="col-md-2">
            	<div class="act">Последная активность:</div>
            </div> 

            <div class="col-md-10">
            
           
            <? 
            
            $y = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}comments WHERE user_id = '{$o->id}' order by cdate DESC LIMIT 7");

            
              	for ($r=0; $r<sizeof($y); $r++)
            	{ 
                	$w = $y[$r]; 
             ?>
            
                		<div class="jobday"><a href="#" class="namber">*<?=$w->page_id;?></a> <a href="#" class="cdate"><?=dateSQL2TEXT($w->vdate,"DD MN, hh:mm");?></a> <a href="" class="text"><? echo utf8_substr(strip_tags($w->text), 0, 80);?></a></div><? } ?>
          	
            
            </div> 
        </div> 

            <div class="col-md-12" style="text-align:right">
                <? 
                if ($o->visible == 1) { $color = 'style="color:#333"'; $act = 'Закрыть доступ' ; $visible = 0;}
                if ($o->visible == 0) { $color = 'style="color:#ccc"'; $act = 'Открыть доступ'; $visible = 1;} ?>
                
                <a <?=$color;?> href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/visible/<?=$visible?>/<?=$o->id?>"><?=$act?></a>
            </div>            
  			<br clear="all">
             
</div>

     <? } ?>

 
    
	</div>
</div>    
    <a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/add/" class="mailogo">Добавить менеджера</a> 