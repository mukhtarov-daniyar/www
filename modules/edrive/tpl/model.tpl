<? $CFG->oPageInfo->html_title = 'Модель авто'; ?>
<h2><img alt="" src="/tpl/img/new/icon/auto_r.png">Модель авто</h2>


<div class="white datas">

    <article class="vacancies_body row">
        <ul class="list-group">
<?
	$datas = getSQLArrayO("SELECT * FROM my_edrive_car_model  ORDER BY id ASC");
	for ($i=0; $i<sizeof($datas); $i++)
    {
    	?> <li class="list-group-item"><strong><?=$datas[$i]->name;?></strong>
        <ul>
          <?
          $cars = getSQLArrayO("SELECT * FROM my_edrive_car WHERE model_id = '{$datas[$i]->id}'  ORDER BY id ASC");
          for ($y=0; $y<sizeof($cars); $y++)
          {
            ?>
            <li><a href="#" data-id="<?=$cars[$y]->id;?>"><?=$cars[$y]->name;?></a>
              <a style="font-size:10px;" href="/edrive/add/dell_car/<?=$cars[$y]->id;?>"><i title="Удалить эту марку авто?" data-toggle="tooltip" data-placement="bottom" class="glyphicon glyphicon-trash" data-id="76297" ></i></a>
            </li>
            <?
          }
          ?>
        </ul>
        <a href="#" data-id="<?=$datas[$i]->id;?>" class="add_puts" style="font-size:12px;"><strong>Добавить модель авто</strong></a>
      </li>
      <?
    } ?>
        </ul>
    </article>
</div>

<script>
	var s = location.href;	url = s.split('#')[0];

  $(document).on('click','a.add_puts',function(e)
	{
		var id = $(this).attr('data-id');

    $(document).ready(function()
    {
      $("#myModalBox").modal('show');
    });

    $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Добавить новую модель авто</h4></div>');
    $(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control text" placeholder="Введите название" required></p>');
    $(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Сохранить</button></p>');

    $('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var control_text = $('.form-control.text').val();

			if(control_text == '')
			{
				alert("Укажите название");
			}
			else
			{
				$.ajax
				({
					url: "/edrive/add/up_car/",
					type: "POST",
					data: {"id": id, "text": control_text },
					cache: true,
						beforeSend: function()
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{
							if(response > 0)
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
			$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Сохранить</button></p>');
		}
		else
		{
			$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Добавить новую модель авто</h4></div>');
			$(".modal-body").append('<p><input type="hidden" class="form-control id" value="0"></p>');
			$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control text" placeholder="Название модели авто" required></p>');
			$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Сохранить</button></p>');
		}

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var control_id = $('.form-control.id').val();
			var control_text = $('.form-control.text').val();

			if(control_text == '')
			{
				alert("Укажите все данные");
			}
			else
			{
				$.ajax
				({
					url: "/edrive/add/up_model/",
					type: "POST",
					data: {"id": control_id, "text": control_text },
					cache: true,
						beforeSend: function()
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{
							if(response > 0)
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
