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
	$datas = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}type_company WHERE visible=1 AND page_id = 868 ORDER BY pos,id ASC"); 
	for ($i=0; $i<sizeof($datas); $i++)
    { 
    	?> <li class="list-group-item"><strong><?=$datas[$i]->name;?></strong> <strong>(<?=$datas[$i]->pos;?>)</strong> <a href="#" class="edit" data-pos="<?=$datas[$i]->pos;?>" data-title="<?=$datas[$i]->name;?>" data-id="<?=$datas[$i]->id;?>"> изменить</a> </li><? 
    } ?>
        </ul>
    </article>
	&nbsp; &nbsp;<a href="#" data-id="0" class="edit"><strong>Добавить категорию</strong></a>
</div>

<script>
	var s = location.href;	url = s.split('#')[0];

	$(document).on('click','.datas a.del',function(e) 
	{
		var id = $(this).attr('data-id');

		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});

		$.ajax
		({
			url: "/static/add_type_company_ot/", 
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
		var pos = $(this).attr('data-pos');

		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});
		
		if(id > 0)
		{
						
			$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
			$(".modal-body").append('<p><input type="hidden" class="form-control id" value="'+id+'"></p>');
			$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control text" value="'+text+'"></p>');
			$(".modal-body").append('<p><input style="width:70px " type="tel" class="form-control pos" value="'+pos+'"></p>');
			$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Отправить</button></p>');

		}
		else
		{
			$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Создать новую категорию</h4></div>');
			$(".modal-body").append('<p><input type="hidden" class="form-control id" value="0"></p>');
			$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control text" placeholder="Название категории" required></p>');
			$(".modal-body").append('<p><input style="width:70px " type="tel" class="form-control pos" value="10" required></p>');
			$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Отправить</button></p>');
		}
		
		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{	
			var control_id = $('.form-control.id').val();  
			var control_text = $('.form-control.text').val();  
			var control_pos = $('.form-control.pos').val();  
	
			if(control_text == '')
			{
				alert("Укажите все данные");	
			}
			else
			{		
				$.ajax
				({
					url: "/static/add_type_company_ot/", 
					type: "POST",      
					data: {"id": control_id, "text": control_text , "pos": control_pos },
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

</script>