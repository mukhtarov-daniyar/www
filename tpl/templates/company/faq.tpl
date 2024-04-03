<?
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/elfinder.min.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/extras/editors.default.min.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/js/i18n/elfinder.ru.js"></script>');

	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">');
	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="/css/elfinder.min.css">');
	$CFG->SITE->addToHeaderHTML('<link rel="stylesheet" type="text/css" href="/css/theme.css">');

 ?>

<style>
.elfinder  { z-index:1000001}
.modal-body .bootstrap-select { width:100% !important;}


</style>

<br>
<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>    	
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Бухгалтерия</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<div class="white datas">
    
    <article class="vacancies_body row">
        <ul class="list-group">
<? 	
	$datas = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}faq WHERE visible=1 AND director_id = {$CFG->USER->USER_DIRECTOR_ID} AND page_id = 0 ORDER BY id ASC"); 
	for ($i=0; $i<sizeof($datas); $i++)
    { 
    	?> 
        <li class="list-group-item"><strong><?=$datas[$i]->name;?></strong>
        <a href="#" class="edit" data-id="<?=$datas[$i]->id;?>" data-title="<?=$datas[$i]->name;?>" data-img="<?=$datas[$i]->img;?>" data-intro="<?=$datas[$i]->text;?>" data-page_id="<?=$datas[$i]->page_id;?>" > Изменить</a> | 
        <a href="#" class="delete_faq" data-id="<?=$datas[$i]->id;?>"> Удалить</a>
        </li><?  } ?>
        </ul>
    </article>
    
    
	&nbsp; &nbsp;<a href="#" data-id="0" class="edit"><strong>Добавить FAQ</strong></a>
</div>

<script>
	var s = location.href;	url = s.split('#')[0];

	$(document).on('click','a.delete_faq',function(e) 
	{
		var id = $(this).attr('data-id');

		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

		$.ajax
		({
			url: "/static/add_faq/", 
			type: "POST",      
			data: {"id": id, "del": "del" },
			cache: true,			
				beforeSend: function() 
				{
					$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');			
				},
				success: function(response) 
				{							
					if(response == 1)
					{										
						$('.content').load(url + 'json',function()
						{	
							$(document).ready(function(){
								$("#myModalBox").modal('hide');
							});
							
							$('.modal-body').html('');
						});
					}
				}	
		});		

		e.preventDefault();			
	});



	$(document).on('click','.datas a.edit',function(e) 
	{
		var id = $(this).attr('data-id');
		var text = $(this).attr('data-title');
		var img = $(this).attr('data-img');
		var intro = $(this).attr('data-intro');
		var page_id = $(this).attr('data-page_id');

		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		
		if(id > 0)
		{		
			$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
			$(".modal-body").append('<p><input type="hidden" class="form-control id" value="'+id+'"></p>');
			$(".modal-body").append('<p><input style="width:440px " type="text" class="form-control text" value="'+text+'"></p>');
			$(".modal-body").append('<p><input type="hidden" class="form-control img" value="'+img+'"></p>');
			$(".modal-body").append('<textarea class="form-control intro" rows="4" style="margin-top:7px;" placeholder="Описание">'+intro+'</textarea>');
			$(".modal-body").append('<a href="#" id="file_wp">Прикрепить фото</a>');
			$(".modal-body").append('<div class="respons_img"></div>');
			$('.respons_img').html('<img src="'+img+'" style=" width:150px;">');
			$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Сохранить</button></p>');
		}
		else
		{
			$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Создать новый FAQ</h4></div>');
			$(".modal-body").append('<p><input type="hidden" class="form-control id" value="0"></p>');
			$(".modal-body").append('<p><input type="text" class="form-control text" placeholder="Название" required></p>');

			$(".modal-body").append('<p><input type="hidden" class="form-control img"></p>');
			$(".modal-body").append('<textarea class="form-control intro" rows="4" style="margin-top:7px;" placeholder="Описание"></textarea>');
			$(".modal-body").append('<a href="#" id="file_wp">Прикрепить фото</a>');
			$(".modal-body").append('<div class="respons_img"></div>');
			
			$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Сохранить</button></p>');
		}
		
		$('.selectpicker').selectpicker();
		
		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{	
			var control_id = $('.form-control.id').val();  
			var control_text = $('.form-control.text').val();  
			var control_img = $('.form-control.img').val(); 
			var control_intro = $('.form-control.intro').val(); 
			var page_id = $(".selectpicker.page_id").find("option:selected").val(); 
	
			if(control_text == '')
			{
				alert("Укажите все данные");	
			}
			else
			{		
				$.ajax
				({
					url: "/static/add_faq/", 
					type: "POST",      
					data: {"id": control_id, "text": control_text , "img": control_img, "intro": control_intro, "page_id": page_id },
					cache: true,			
						beforeSend: function() 
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');			
						},
						success: function(response) 
						{														
							if(response == 1)
							{										
								$('.content').load(url + 'json',function()
								{	
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');
								});
							}
						}	
				});		
			}
		});

		
		
		e.preventDefault();			
	});




$(document).on('click','#file_wp',function(e) 
{
	var fm = $('<div/>').dialogelfinder({
		url : '<? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID ==481){ echo '/php/connector.minimal.php';} else { echo '/php/connector.minimal.manager.php';} ?>',
		width : 840,
		lang : 'ru',
		useBrowserHistory: false,	
		commandsOptions : {
			getfile : {
				oncomplete : 'destroy',
			}
		},
		getFileCallback : function(files, fm)
		{
			var real_url = '/documents/' +files.path;
			
			var types = real_url.substring(real_url.lastIndexOf('.')+1, real_url.length) || real_url;
			
			
			if(types == 'jpg' || types == 'JPG' || types == 'jpeg' || types == 'GIF' || types == 'gif' || types == 'PNG' || types == 'png')
			{
				$('.respons_img').html('<img src="'+real_url+'" style=" width:150px;">');
				$('.form-control.img').val(real_url);									
			}
			else
			{
				alert('К сожалению вы можете прикрепить только фото');
			}						

			
							
		},
	}).dialogelfinder('instance');


	e.preventDefault();
});	


</script>