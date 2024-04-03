<?
	$res = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_rss_tmp WHERE id='{$CFG->_GET_PARAMS[1]}'");

print_r($res);



	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/elfinder.min.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/extras/editors.default.min.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/i18n/elfinder.ru.js"></script>');

	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">');
	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="/css/elfinder.min.css">');
	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="/css/theme.css">');

 ?>    



<h2>Новый маркетинг план для абонента: <?=$res->mobile?></h2>

<div class="white">       
 


<script>
	$(function(){ $('[data-toggle="tooltip"]').tooltip(); });
</script>       


<style>
span.glyphicon { display:inline-block; margin-left:10px; font-size:16px; cursor:pointer; vertical-align: top}
.tooltip {     font-family: 'segoeui_sb'; letter-spacing:0; }
.kriteri {     font-family: 'segoeui_sb'; }
.kriteri input{ font-size:12px; margin-bottom:10px;}


textarea.text {width:410px; height:70px; min-height:inherit; font-size:12px; display:inline-block;}

</style>




<form method="POST" class="response">
	<input type="hidden" name="id" value="<?=$CFG->_GET_PARAMS[0];?>" />
    
    <input type="hidden" name="img" id="logo_input" value="" />  
    
     <div class="kriteri" style="width:700px;">
    	<span>Название</span>
        <input type="text" name="name" id="name" value="<?=$res->name?>" placeholder="Название рассылки" disabled="disabled" /> 
    </div>   

     <div class="kriteri" style="width:700px;">
    	<span>Whatsapp номер</span>
        <input type="text" name="mobile" id="mobile" value="<?=$res->mobile?>" placeholder="Номер Whatsapp" disabled="disabled" /> 
    </div>
       
     <div class="kriteri" style="width:700px;">
    	<span>Выберите дату и время</span>
        <input type="text" name="cdate" id="mobile" value="<?=$res->mobile?>" placeholder="Номер Whatsapp" disabled="disabled" /> 
    </div>
       
       
       
       
    <div class="kriteri">
		<div class="mask_text"></div>
    </div>

     <div class="kriteri">
        <a href="#" class="a_text"  data-toggle="tooltip" data-placement="top" title="Добавить новое событие" data-original-title="Добавить новое событие">Добавить событие +</a>
     </div>     

    
    
      
    
        <a href="#" class="change" id="file_upload_filebox">
        <div class="boxPhotoProfile">
        	<img id="upAvatar" src="/tpl/img/noavatar.jpg" alt="" style="border-radius:0px; width:70px; height:auto" /> 
            <span style="color:#333">Рекомендуется прикреплять картинку не более 100 кб.</span>
        </div>
    	</a>
    
    <input type="submit" value="Продолжить" class="btn btn-danger" style="margin-left:100px; margin-top:15px;">
</form>
</div>

<script>



	$(document).on('click','.a_text',function(e) 
	{
		$('.mask_text').append('<span>Отправитель.</span>  <select name="send"<?=$e['send']?> class="selectpicker show-tick"><? 
            $city = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE visible='1' ORDER BY id ASC");
            
            for($i=0;$i<sizeof($city);$i++)
            {	
                ($data['city'] == $city[$i]->id) ? $sel = "selected" : $sel = ""; ?><option value="<?=$city[$i]->id?>"<?=$sel?>><?=$city[$i]->namber;?> - <?=$city[$i]->name;?></option><? } ?></select></div> ');
		 
		e.preventDefault();	
		
	});


	
	$(document).on('click','input.btn.btn-danger',function(e) 
	{
		var msg = $('form.response').serialize();
		
		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

		$.ajax(
		{				
			url:'/static/whatsapp_active/',
			data: msg,
			type: 'POST',
			beforeSend: function() 
			{					
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 
			},
			success: function(status) 
			{
				setTimeout(function() {location.href = '/whatsapp_new/static/';}, 0,1);				
			}
		});			
		e.preventDefault();	
	});
	


$('#file_upload_filebox').bind('click', function(e)
{
	var fm = $('<div/>').dialogelfinder({
		url : '<? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID ==481 || $CFG->USER->USER_ID ==133 || $CFG->USER->USER_ID ==536){ echo '/php/connector.minimal.php';} else { echo '/php/connector.minimal.manager.php';} ?>',
		height : 600,
		lang : 'ru',
		 resizable: false,
		 useBrowserHistory: false,
		commandsOptions : {
			getfile : {
				oncomplete : 'destroy',
			},
			
		},
		 quicklook : {
                    sharecadMimes : ['image/vnd.dwg', 'image/vnd.dxf', 'model/vnd.dwf', 'application/vnd.hp-hpgl', 'application/plt', 'application/step', 'model/iges', 'application/vnd.ms-pki.stl', 'application/sat', 'image/cgm', 'application/x-msmetafile'],
                    googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/postscript', 'application/rtf'],
                    officeOnlineMimes : ['application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.presentation']
                },
		getFileCallback : function(files, fm)
		{
			var real_url = '/documents/' +files.path;
			
			var types = real_url.substring(real_url.lastIndexOf('.')+1, real_url.length) || real_url;

			if(types == 'jpg' || types == 'JPG' || types == 'jpeg' || types == 'GIF' || types == 'gif' || types == 'PNG' || types == 'png')
			{
		
				$('#logo_input').val(real_url);
				$('#upAvatar').attr('src', real_url);		
			}
			else
			{
				alert('К сожалению возможно прикрепить только картинку!');				
			}						
		},
		
		
	}).dialogelfinder('instance');

	e.preventDefault();

});		
	
	
	
</script>