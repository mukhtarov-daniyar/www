<?
	$MODULE_ = getFullXCodeByPageId($CFG->pid);
	$e = $CFG->FORM->getFailInputs();
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');

?>

<?=showHeader2($CFG->Locale["ps34"]);?>

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

<div class="text" style="padding-right:0; padding-left:0">




<form method="POST" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>">
    <input type="hidden" name="user_act" value="update_profile" />

        <table class="view">
          <tr>
            <td></td>
            <td><h1><?=$data['login']?></h1></td>
          </tr>

            <tr>
                <td><?=$CFG->Locale["name_company"];?></td>
                <td><input type="text" name="name_company" placeholder="<?=$CFG->Locale["name_company"];?>" value="<?=$data['name_company']?>"<?=$e['name_company']?> /></td>
            <tr>

          <tr>
            <td>Сфера деятельности:</td>
            <td>
                <select name="sphere" style="width:200px; position:static !important; padding:8px;">
               <?
                $sphere = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}sphere  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
                for($i=0;$i<sizeof($sphere);$i++)
                {
                    ($data['sphere'] == $sphere[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$sphere[$i]->id?>"<?=$sel?>><?=$sphere[$i]->name?></option>
              <? } ?>
                </select>
            </td>
          </tr>

            <tr>
                <td><?=$CFG->Locale["desc_company"];?></td>
                <td><textarea name="text_company"<?=$e['text_company']?>><?=$data['text_company']?></textarea></td>
            <tr>

         <tr>
            <td><?=$CFG->Locale["ps15"];?></td>
            <td>
                <select name="city" style="width:200px; position:static !important; padding:8px;">
               <?
                $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}city  WHERE visible='1' and sys_language='{$CFG->SYS_LANG}' ORDER BY id ASC");
                for($i=0;$i<sizeof($city);$i++)
                {
                    ($data['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
              <? } ?>
                </select>
            </td>
          </tr>


          <tr>
            <td>Контактное лицо ФИО:</td>
            <td><input type="text" name="name" placeholder="Контактное лицо ФИО" value="<?=$data['name']?>"<?=$e['name']?> /></td>
          </tr>


          <tr>
            <td><?=$CFG->Locale["ps33"]?></td>
            <td><input type="text" class="mobile" name="mobile" placeholder="<?=$CFG->Locale["ps33"];?>" value="<?=$data['mobile']?>"<?=$e['mobile']?> /></td>
          </tr>


    <tr>
        <td><?=$CFG->Locale["logo_company"];?></td>
        <td>
            <div class="boxCompany">
                <a href="#"><img src="<?=$data['logo_company'];?>" width="105" height="105" alt=""></a>
                <a href="#" id="ImgCompany" class="change"><?=$CFG->Locale["ps2"];?></a>
            </div>
        </td>
    <tr>
    </table>

    <input type="submit" value="<?=$CFG->Locale["ps20"];?>" class="btn save" />
</form>
 </div>




<script type="text/javascript">

	new AjaxUpload('#upAvatar',
	{
		data: {'user_act' : 'upload_avatar'},
		name: 'avatar',
		autoSubmit: true,
		onComplete: function(file, response)
		{
			alert('<?=$CFG->Locale["ps21"];?>');

			window.location.href = document.location.href;
		}
	});

	if( document.getElementById('upResume') )
	{
		new AjaxUpload('#upResume',
		{
			data: {'user_act' : 'upload_resume'},
			name: 'resume',
			autoSubmit: true,
			onComplete: function(file, response)
			{
				//alert('<?=$CFG->Locale["gofile"];?>');

				window.location.href = document.location.href;
			}
		});
	}

	if( document.getElementById('ImgCompany') )
	{
		new AjaxUpload('#ImgCompany',
		{
			data: {'user_act' : 'upload_logo_company'},
			name: 'logo',
			autoSubmit: true,
			onComplete: function(file, response)
			{
				window.location.href = document.location.href;
			}
		});
	}
</script>
