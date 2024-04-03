


<? $thisXcode = getFullXCodeByPageId($CFG->pid); 

?>
<?=showHeader2($CFG->oPageInfo->html_title);?>



<style type="text/css">


table.view{  width:100%}
table.view td {font-size:14px; vertical-align:middle}
table.view td:nth-child(1) { width:40%;text-align:right; padding-right:30px; color:#7C8187 } 

.boxCompany { position:relative;}
.boxCompany a.change {display: block;width:105px;height: 23px;text-align: center;padding: 3px 0 0 0;color: #ffffff;text-decoration: none;background: url(/tpl/img/title_profile.png) repeat center top;position: absolute; left: 0px;bottom: -0px;}
.boxCompany.avatars a.change { left:148px; bottom:4px; }

	
table.view span { margin-top:10px; display:block;}
table.view input { width:240px;font-size:15px; padding-left:5px; padding-top:5px; padding-bottom:5px}
table.view textarea { width:97%; height:80px; }
table.view  select { width:250px !important;}

.btn{  text-align:center; background:#ce1053; color:#FFF; border:0; padding:10px 30px; margin:0 auto; text-transform:uppercase; }
.btn.save { display:block; text-align:center; margin-top:20px;}
a.btn { text-decoration:none; margin-top:0px; display:inline-block}

</style>



<div class="enter_user text">



<form name="register" method="POST" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>" class="contact-form">
    <input type='hidden' name='user_act' value='role' />
    <input type='hidden' name='page_id' value='<?=$CFG->pid?>' />


    <input type="radio" name="status" id="dva1" value="1" checked="checked"/><label for="dva1"><?=$CFG->Locale['ps24']?></label> 
    <input type="radio" name="status" id="dva2" value="2"/><label for="dva2"><?=$CFG->Locale['ps25']?></label>
    <br>
    <input type="text" name="captcha" maxlength="4" placeholder="<?=$CFG->Locale['captcha_enter']?>"<?=$e['captcha']?>  class="captcha" /><img src="/xnum.php" style="vertical-align:middle;"/>
    
    <button type="submit" class="btn btn-info font-size-16 font-upper-light" class="bot"><?=$CFG->Locale['zakup_v13']?></button>
</form>

</div>	




