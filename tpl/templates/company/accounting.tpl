<br clear="all">

<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Касса</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<div class="content">
  <h2 style="background:#fff"> Управление кассами</h2>
  <div class="white" style="padding:0 25px; ">
    <article class="vacancies_body row">
        <ul class="list-group">
        <?
            $sql = getSQLArrayO("SELECT * FROM my_money_accounting_data WHERE  director_id = {$CFG->USER->USER_DIRECTOR_ID} AND visible = 1 ");
            foreach ($sql as $value)
            {
                ?>
                  <li class="list-group-item"><strong><?=$value->name;?></strong> <a href="#" data-id="<?=$value->id;?>" class="edit_accounting">Настроить</a> | <a class="del_accounting" data-id="<?=$value->id;?>" href="#">Удалить</a></li>
                <?
            }
        ?>
        </ul>
    </article>
    <a href="#" class="add_accounting"><strong>+ Добавить кассу</strong></a>
    <br><br>
    <a href="/profile/company/85/accounting_add_type"><strong>+ Добавить новую статью</strong></a>
    <br><br>
  </div>
</div>



<script>
/*  Добавить новую кассу  */
$(document).on('click','.add_accounting',function(e)
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false});
		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Создать новую кассу</h4></div>');
		$(".modal-body").append('<p><input type="hidden" class="form-control id" value="0"></p>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control text" placeholder="Название кассы" required></p>');
    $(".modal-body").append('<span style="color:#C0C0C0">Осуществлять все настройки с кассой можно после ее создания!</span>');
		$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Создать</button></p>');

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var control_text = $('.form-control.text').val();
			if(control_text == '')	{	alert("Введите название кассы");	}
			else
			{
				$.ajax
				({
					url: "/static/add_accounting/",
					type: "POST",
					data: {"text": control_text },
					cache: true,
						beforeSend: function()
						{
							$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{
              if(response > 0) {  $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');	setTimeout(function() {window.location.reload();}, 1000);} else {  $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Произошла ошибка, попробуйте еще раз :(</h4></div>');  }
						}
				});
			}
		});
		e.preventDefault();
	});
/*  Добавить новую кассу END */


/*  Удалить кассу  */
$(document).on('click','.del_accounting',function(e)
	{
  	var id = $(this).attr('data-id');
    $('#myModalBox').modal({backdrop: 'static', keyboard: false});

    $(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
    $(".modal-body").append('<h4 class="modal-title"><center>Вы действительно хотите удалить кассу?</center></h4>');
    $(".modal-body").append('<span style="color:#ff0000; display:block; text-align:center;">УЧТИТЕ!!! Все операции по данной кассе будут удалены!!!</span>');
    $(".modal-body").append('<center><button style="margin-top:10px;    padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Да</button> &nbsp; &nbsp; <button style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Нет</button></center>');

      $('.btn.btn-primary.submit').on('click', function(e)
			{
				$.ajax
				({
					url: "/static/del_accounting/",
					type: "POST",
					data: {"id": id },
					cache: true,
						beforeSend: function()
						{
                $(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
						},
						success: function(response)
						{
							$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');	setTimeout(function() {window.location.reload();}, 1000);
						}
				});
			});
		e.preventDefault();
	});
/*  Удалить кассу  */


/*  Настроить кассу  */
$(document).on('click','.edit_accounting',function(e)
	{
  	var id = $(this).attr('data-id');

    $('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

		$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

    $.ajax
    ({
      url: "/static/data_update_accounting/",
      type: "POST",
      data: {"id": id },
      cache: true,
      success: function(response)
      {
        response = $.parseJSON(response);

        $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Редактировать:</h4></div>');
        $(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name" placeholder="Название кассы" value="'+response.name+'" required></p>');

        $(".modal-body").append('<p><strong>Кассир кассы: <span style="color:#ff0000">'+ response.name_accounting +'</span></strong><br><select data-live-search="true" class="selectpicker show-tick manager list-view-manager pase accounting" title="Кассир кассы"><option value="0" section>Выберите кассира</option><?  $res = getSQLArrayO("SELECT id, name FROM my_users WHERE visible = 1 order by name ASC");  foreach ($res as $value){  ?> <option value="<?=$value->id;?>"><?=$value->name;?></option>   <?  } ?></select></p>');

          $(".modal-body").append('<p class="man"></p>');

        $.ajax({  url: "/static/data_access_accounting/",  type: "POST",  data: {"id": id, }, cache: true,  success: function(response)  {  $(".modal-body p.man").append(response);  $('.selectpicker').selectpicker();  $('.selectpicker').selectpicker('refresh');  }  });

        $(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Сохранить</button></p>');

        $('.selectpicker').selectpicker();

        $('.btn.btn-primary.submit').on('click', function(e)
  			{
          var name = $('input.form-control.name').val();
          var user_id = $('select.selectpicker.accounting').val();
          var access = $('select.selectpicker.access').val();

  				$.ajax
  				({
  					url: "/static/update_accounting/",
  					type: "POST",
  					data: {"id": id,  "name": name,  "user_id": user_id , "access": access  },
  					cache: true,
  						beforeSend: function()
  						{
                  $(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  						},
  						success: function(response)
  						{
  							$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Данные сохранены. Обновляем страницу...</center></h4></div>');	setTimeout(function() {window.location.reload();}, 1000);
  						}
  				});
  			});

      }
    });

		e.preventDefault();
	});
/*  Настроить кассу  */

</script>
