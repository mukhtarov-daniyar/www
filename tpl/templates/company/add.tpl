<br clear="all">

<div class="row tab">
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Бухгалтерия</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<div class="white">


<?

	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');

?>
<style type="text/css">
	.boxCompany { position:relative; width:105px; height:105px;}
	.boxCompany a.change {display: block;width:105px;height: 23px;text-align: center;padding: 3px 0 0 0;color: #ffffff;text-decoration: none;background: url(/tpl/img/title_profile.png) repeat center top; position: absolute; left: 0px !important;bottom: -5px; margin-left:0 !important}
	.btn{  display:block;margin:0 auto; m}
</style>



<form method="POST" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>">
	<input type="hidden" name="user_act" value="company" />
	<input type="hidden" name="logo_company" value="<?=$data['logo_company'];?>" id="logo_input">

    <div class="kriteri">
    	<span>Лого компании:</span>
        <div class="boxCompany" id="upAvatar">
            <a href="#"><img src="<?=$data['logo_company'];?>" id="logo_src" width="105" height="105" alt=""></a>
            <a href="#" class="change"><?=$CFG->Locale["ps2"];?></a>
        </div>
    </div>

    <div class="kriteri">
        <span>Название компании:</span>
        <input type="text" name="name" value="<?=$data['name']?>" <?=$e['name']?>>
    </div>

     <div class="kriteri">
        <span>Город:</span>
        <select name="city" <?=$e['city']?> class="selectpicker show-tick" data-live-search="true">
        	 <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
			   <?
                $city = SelectDataArray('city', 1, 'name ASC');
                for($i=0;$i<sizeof($city);$i++)
                {
                    ($data['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->name?></option>
              <? } ?>
        </select>
    </div>



     <div class="kriteri">
        <span>О компании:</span>
        <textarea style="width:400px; height:40px; font-size:14px;" name="text" <?=$e['text']?>><?=replace_r_n($data["text"])?></textarea>
    </div>

        <div class="kriteri">
            <span>Фраза для шифровки:</span>
            <input type="text" name="fraza" value="<?=$data['fraza']?>" <?=$e['fraza']?>>
        </div>


            <div class="kriteri">
                <span>Название валюты для компании:</span>
                <input type="text" name="currency" value="<?=$data['currency']?>" <?=$e['currency']?>>
            </div>

    <input type="submit" value="Сохранить" class="btn btn-danger" style="margin-left:350px; margin-top:15px;margin-bottom:15px;">

</form>

</div>



<script type="text/javascript">

	if( document.getElementById('upAvatar'))
	{
		var btn = $('#upAvatar');

		 new AjaxUpload(btn,
		  {
		   data: {'user_act' : 'upload_logo_company'},
		   name: 'avatar',
		   autoSubmit: true,
		   onComplete: function(file, response)
		   {
			response = $.parseJSON(response);

			if( typeof response.med == 'undefined' || response.med == null )
			{
			 	return false;
			}

			/ set view /
			$('#logo_src').attr('src', response.med);

			/ set value /
			document.getElementById('logo_input').value = response.big;
		   }
		  });
	}

</script>
