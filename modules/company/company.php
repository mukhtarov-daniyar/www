<? 
	

	
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	
	
?>

<?=showHeader2($CFG->Locale["ps34"]);?>

<style type="text/css">
	.boxCompany { position:relative; width:105px; height:105px;}
	.boxCompany a.change {display: block;width:105px;height: 23px;text-align: center;padding: 3px 0 0 0;color: #ffffff;text-decoration: none;background: url(/tpl/img/title_profile.png) repeat center top; position: absolute; left: 0px !important;bottom: -5px; margin-left:0 !important}
	.btn{  display:block;margin:0 auto; m}
</style>

<?


			if($CFG->SITE->isAjax() == false)
			{
				if($_POST["user_act"] == 'upload_avatar')
				{
					
					if( $CFG->USER->checkExtFile($_FILES['avatar']) == 'image' )
					{
						$big = $CFG->USER->cropUserAvatar($_FILES['avatar'], 'default');
						
						$big = trim($big, '/'); $big = '/' . $big;
		
						$response = array('big' => $big);
				
					}
					else
					{	
						$CFG->STATUS->OK = true;
						$CFG->STATUS->MESSAGE = $CFG->Locale["error"];
						redirect($_SERVER["HTTP_REFERER"]);
					}
				}
			}

if($CFG->USER->USER_BOSS == 1){
	
		if($_POST["name"] ==! "")
		{
				$data = $CFG->_POST_PARAMS;
				$CFG->FORM->setForm($data);
				
			
				$e = $CFG->FORM->getFailInputs();
				$respons = $CFG->FORM->getFullForm();
				
			if ($CFG->FORM->setForm($data) == 1)
			{  
				if($CFG->USER->updateCompanyDataArray($data) == false)
				{
					$CFG->STATUS->ERROR = true; 
					$CFG->STATUS->MESSAGE = 'Что то пошло не так :(';
				}
		
			}
	
			
		}
		else
		{
			$data = SelectDataRowOArray('company', $CFG->USER->USER_ID);
			
			$data = (array)$data;
	
		}
		
}
else
{
	$CFG->STATUS->ERROR = true; 
	$CFG->STATUS->MESSAGE = 'Что то пошло не так :(';
	redirect('/'); 	
}



?>




<form method="POST" enctype="multipart/form-data" action="<?=$_SERVER['REQUEST_URI']?>">

	<input type="hidden" name="logo_company" value="<?=$data['logo_company'];?>" id="logo_input">
    
    <div class="kriteri">
    	<span>Лого компании:</span>
        <div class="boxCompany" id="upAvatar">
            <a href="#"><img src="<?=$data['logo_company'];?>" width="105" height="105" alt=""></a>
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
        <textarea style="width:600px; height:250px; font-size:14px;" name="text" <?=$e['text']?>><?=replace_r_n($data["text"])?></textarea>
    </div>

    <input type="submit" value="Сохранить" class="btn btn-danger" style="margin-left:350px; margin-top:15px;margin-bottom:15px;">

</form> 



<script type="text/javascript">

	if( document.getElementById('upAvatar'))
	{
		var btn = $('#upAvatar');
		 new AjaxUpload(btn, 
		  {
		   data: {'user_act' : 'upload_avatar'},
		   name: 'avatar',
		   autoSubmit: true,
		   onComplete: function(file, response) 
		   {
				document.location.href = window.location.href;
		   }
		  });   
	}

</script>