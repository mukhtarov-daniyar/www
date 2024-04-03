<?

// Колхоз ну а че делать, добавляем новый город
if($_POST['type'] == 'add')
{
  $name = $_POST['text'];     $page_id = $_POST['country_training'];    $pos = $_POST['pos'];
  $CFG->DB->query( "INSERT INTO my_city (name, page_id, pos) VALUES ('{$name}','{$page_id}','{$pos}') 	");
  echo mysql_insert_id(); exit;
}

if($_POST['type'] == 'del')
{
  $id = $_POST['id'];
  $CFG->DB->query("UPDATE my_city SET visible = 0 WHERE id='{$id}'"  );
  exit;
}


if($_POST['type'] == 'edit')
{
  $id = $_POST['id'];
  $res = getSQLRowO(" SELECT * FROM my_city WHERE id = '{$id}' ");
  $respon = getSQLRowO(" SELECT name FROM my_country_training WHERE id = '{$res->page_id}' ");
  $response = json_encode(array('id' => $res->id, 'country_training' => $respon->name, 'name' => $res->name, 'pos' =>  $res->pos 	));
  echo $response;
  exit;
}


if($_POST['type'] == 'update')
{
    $id = $_POST['id'];
    $name = $_POST['name'];

    $country_training = $_POST['country_training'];
    if(!$country_training == 0)
    {
      $country = "	page_id = '{$country_training}',	";
    }
    
    $pos = $_POST['pos'];
    $CFG->DB->query("UPDATE my_city SET name = '{$name}',  {$country}  pos = '{$pos}'  WHERE id='{$id}'"  );
    exit;
}


$users_all = getSQLArrayO("SELECT * FROM my_country_training WHERE visible=1 ORDER BY name ASC");


?>


<style>
.modal-body .bootstrap-select { width:220px !important;}
</style>


<br clear="all">

<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Касса</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<div class="content">
  <h2 style="background:#fff"> Управление городами</h2>
  <div class="white" style="padding:0 25px; ">
    <a href="#" class="add_town"><strong>+ Добавить новый город</strong></a><br><br>
    <article class="vacancies_body row">
        <ul class="list-group">
        <?
            $cnt = 1;
            $sql = getSQLArrayO("SELECT * FROM my_city WHERE visible = 1 order by name ASC");
            foreach ($sql as $value)
            {
                ?>
                  <li class="list-group-item"><strong><?=$cnt;?>. <?=$value->name;?></strong> (<?=$value->pos;?>) <a href="#" data-id="<?=$value->id;?>" class="edit_town">Настроить</a> | <a class="del_town" data-id="<?=$value->id;?>" href="#">Удалить</a></li>
                <?
                $cnt ++;
            }
        ?>
        </ul>
    </article>
    <a href="#" class="add_town"><strong>+ Добавить новый город</strong></a>

    <br><br>
  </div>
</div>



<script>
/*  Добавить новый город  */
$(document).on('click','.add_town',function(e)
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false});
		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Добавить новый город</h4></div>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control text" placeholder="Название города" required></p>');
    $(".modal-body").append('<p><select class="selectpicker show-tick country_training list-view-manager" title="Выберите страну"><?  for ($i=0; $i<sizeof($users_all); $i++){ ?> <option value="<?=$users_all[$i]->id;?>"><?=$users_all[$i]->name;?></option> <? } ?></select></p>');
    $(".modal-body").append('<p><input type="text" class="form-control pos" style="width:70px" placeholder="Сортировочное значение" value="0"> сортировка</p>');
		$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Создать</button></p>');

    	$('.selectpicker').selectpicker();

		$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
		{
			var control_text = $('.form-control.text').val();
      var pos = $('.form-control.pos').val();
      var country_training = $('select.selectpicker.country_training').val();

			if(control_text == '')	{	alert("Введите название города");	}
			else
			{
				$.ajax
				({
					url: "/profile/company/85/town/",
					type: "POST",
					data: {"text": control_text, "country_training": country_training, "pos": pos, "type": "add" },
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


/*  Удалить город  */
$(document).on('click','.del_town',function(e)
	{
  	var id = $(this).attr('data-id');
    $('#myModalBox').modal({backdrop: 'static', keyboard: false});

				$.ajax
				({
					url: "/profile/company/85/town/",
					type: "POST",
					data: {"id": id, "type": "del",  },
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
		e.preventDefault();
	});
/*  Удалить кассу  */


/*  Настроить город  */
$(document).on('click','.edit_town',function(e)
	{
  	var id = $(this).attr('data-id');

    $('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

		$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

    $.ajax
    ({
      url: "/profile/company/85/town/",
      type: "POST",
      data: {"id": id, "type": "edit" },
      cache: true,
      success: function(response)
      {
        response = $.parseJSON(response);

        $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Редактировать :</h4></div>');
        $(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name" placeholder="Название" value="'+response.name+'" required></p>');

        $(".modal-body").append('<p><strong>Страна: <span style="color:#ff0000">'+ response.country_training +'</span></strong><br><select class="selectpicker show-tick manager list-view-manager pase country_training"><option value="0" section>Выберите страну</option><?  $res = getSQLArrayO("SELECT id, name FROM my_country_training WHERE visible = 1 order by name ASC");  foreach ($res as $value){  ?> <option value="<?=$value->id;?>"><?=$value->name;?></option>   <?  } ?></select></p>');

        $(".modal-body").append('<p><input type="text" class="form-control pos" style="width:70px" placeholder="Сортировочное значение" value="'+response.pos+'"> сортировка</p>');


        $(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Сохранить</button></p>');

        $('.selectpicker').selectpicker();

        $('.btn.btn-primary.submit').on('click', function(e)
  			{
          var name = $('input.form-control.name').val();
          var country_training = $('select.selectpicker.country_training').val();
          var pos = $('input.form-control.pos').val();

  				$.ajax
  				({
  					url: "/profile/company/85/town/",
  					type: "POST",
  					data: {"id": id,  "name": name,  "country_training": country_training , "pos": pos, "type": "update" },
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
