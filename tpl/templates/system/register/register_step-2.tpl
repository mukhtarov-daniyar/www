
<? 
	$MODULE_ = getFullXCodeByPageId($CFG->pid); 
	$e = $CFG->FORM->getFailInputs();
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
    
?>






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


<div class="row tab">
    <div class="col-md-6">
        <h1><a href="/<?=$CFG->SYS_LANG_NAME?>/<?=$CFG->oPageInfo->xcode?>/step_1/">Я соискатель</a></h1>
    </div>    	
    <div class="col-md-6">
        <h1><a href="/<?=$CFG->SYS_LANG_NAME?>/<?=$CFG->oPageInfo->xcode?>/step_2/" class="active">Я работодатель</a></h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h1><a href="/<?=$CFG->SYS_LANG_NAME?>/<?=$CFG->oPageInfo->xcode?>/step_1/">Я соискатель</a></h1>
    </div>    	
</div>




<div class="text" style="padding-right:0; padding-left:0">




<form name="register" method="POST" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>" class="contact-form">
    <input type='hidden' name='user_act' value='save_company' />
    <input type='hidden' name='status' value='3' />
    <input type='hidden' name='cdate' value='<?=sqlDateNow();?>' />
    
        <table class="view">
          <tr>
            <td><?=$CFG->Locale["site-local21"]?></td>
            <td><input type="text" name="login" placeholder="<?=$CFG->Locale["ps7"];?>" value="<?=$o['login']?>"<?=$e['login']?> tooltipText="<?=$CFG->Locale["tooltipText1"]?>" /></td>
          </tr>
          
          <tr>
            <td>Контактное лицо ФИО:</td>
            <td><input type="text" name="name" placeholder="Контактное лицо ФИО:" value="<?=$o['name']?>"<?=$e['name']?> /></td>
          </tr>
          
          
          <tr>
            <td><?=$CFG->Locale["password"]?></td>
            <td><input type="password" name="passwd" placeholder="<?=$CFG->Locale["password"];?>" value="<?=$o['passwd']?>"<?=$e['passwd']?> /></td>
          </tr>
          <tr>
            <td><?=$CFG->Locale["site-local9"]?></td>
            <td><input type="password" name="passwd2" placeholder="<?=$CFG->Locale["site-local9"];?>" value="<?=$o['passwd2']?>"<?=$e['passwd2']?> /></td>
          </tr>

      
              
          <tr>
            <td><?=$CFG->Locale["ps33"]?></td>
            <td><input type="text" class="mobile" name="mobile" placeholder="<?=$CFG->Locale["ps33"];?>" value="<?=$o['mobile']?>"<?=$e['mobile']?> /></td>
          </tr>   
          <tr>
            <td><?=$CFG->Locale["ps15"];?></td>
            <td>
                <select name="city"<?=$e['city']?>>
               <? 
                $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
                for($i=0;$i<sizeof($city);$i++)
                {	
                    ($o['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
              <? } ?>
                </select>
            </td>
          </tr>
       
    
       <tr>
        <td><?=$CFG->Locale["ps35"];?>:</td>
        <td>
            <select name="sphere"<?=$e['sphere']?>>
           <? 
            $sphere = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}sphere  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
            for($i=0;$i<sizeof($sphere);$i++)
            {	
                ($o['sphere'] == $sphere[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                <option value="<?=$sphere[$i]->id?>"<?=$sel?>><?=$sphere[$i]->name?></option>
          <? } ?>
            </select>
         </td>
      </tr>            

    <tr>        
        <td><?=$CFG->Locale["name_company"];?></td>
        <td><input type="text" name="name_company" placeholder="<?=$CFG->Locale["name_company"];?>" value="<?=$o['name_company']?>"<?=$e['name_company']?> /></td>
    <tr>          

    <tr>        
        <td><?=$CFG->Locale["desc_company"];?></td>
        <td><textarea name="text_company"<?=$e['text_company']?>><?=$o['text_company']?></textarea></td>
    <tr>






    </table>

    <input type="submit" value="<?=$CFG->Locale["ps20"];?>" class="btn save" />
</form> 
 </div>

