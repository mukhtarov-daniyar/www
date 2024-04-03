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

<div class="white">

<br clear="all">
<br clear="all">
<style type="text/css">
	.menu_parent_type_0 { padding-left:20px;padding-top:15px; font-size:18px;}
	.menu_parent_type_0 a{font-size:14px;}

	.menu_parent_type_1 { padding-left:20px;padding-top:15px; font-size:18px;}
	.menu_parent_type_1 a{font-size:14px;}
</style>


<div class="row">
    <div class="col-md-6">
    	<h2>Список категорий для <strong>ПРИХОДА</strong></h2>
        <div class="menu_parent_type_0">
            <?
            $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1  AND user_id = {$CFG->USER->USER_DIRECTOR_ID} AND parent_id = 0 AND parent_item_id = 0  AND type_id = 1 ORDER BY id ASC");

            for($y=0;$y<sizeof($res);$y++)
            {
                print_r($res[$y]->name.' ('.$res[$y]->id.') <a class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="0">добавить</a>');

                 $count = SelectDataCount('money_accounting', 'cat_id', $res[$y]->id);
                 if(!$count >0 )
                 {
                 	?>
                    	 |   <a class="del_parent" href="#" data-id="<?=$res[$y]->id;?>">удалить</a>
                    <?
                 }
                 echo '<br>';


                $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id  WHERE visible=1 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} AND parent_id = {$res[$y]->id} AND parent_item_id = 0  AND type_id = 1 ORDER BY id DESC");
                for($z=0;$z<sizeof($response);$z++)
                {
                     print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$response[$z]->name.' ('.$response[$z]->id.') <a  class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="'.$response[$z]->id.'">добавить</a> | <a class="del_parent" href="#" data-id="'.$response[$z]->id.'">удалить</a> <br>');

                    $data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1  AND user_id = {$CFG->USER->USER_DIRECTOR_ID} AND parent_id = {$res[$y]->id} AND parent_item_id = {$response[$z]->id}  AND type_id = 1 ORDER BY id DESC");
                    for($x=0;$x<sizeof($data);$x++)
                    {
                         print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data[$x]->name.'  ('.$data[$x]->id.')  <a class="del_parent"  href="#" data-id="'.$data[$x]->id.'">удалить</a> <br>');
                    }
                }
            }

            ?>
            <p>&nbsp;</p>
            <a class="add_parent" href="#" data-parent="0" data-parent_item="0">Добавить новый пункт</a>
        </div>
    </div>





    <div class="col-md-6">
        <h2>Список категорий для <strong>Расхода</strong></h2>
        <div class="menu_parent_type_1">
            <?
            $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} AND parent_id = 0 AND parent_item_id = 0 AND type_id = 2 ORDER BY id ASC");

            for($y=0;$y<sizeof($res);$y++)
            {

                print_r($res[$y]->name.' ('.$res[$y]->id.') <a class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="0">добавить</a>');

                 $count = SelectDataCount('money_accounting', 'cat_id', $res[$y]->id);
                 if(!$count >0 )
                 {
                 	?>
                    	 |   <a class="del_parent" href="#" data-id="<?=$res[$y]->id;?>">удалить</a>
                    <?
                 }
                 echo '<br>';

                $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1  AND user_id = {$CFG->USER->USER_DIRECTOR_ID} AND parent_id = {$res[$y]->id} AND parent_item_id = 0  AND type_id = 2 ORDER BY id DESC");
                for($z=0;$z<sizeof($response);$z++)
                {
                     print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$response[$z]->name.' ('.$response[$z]->id.') <a  class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="'.$response[$z]->id.'">добавить</a> | <a class="del_parent" href="#" data-id="'.$response[$z]->id.'">удалить</a> <br>');

                    $data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1  AND user_id = {$CFG->USER->USER_DIRECTOR_ID} AND parent_id = {$res[$y]->id} AND parent_item_id = {$response[$z]->id}  AND type_id = 2 ORDER BY id DESC");
                    for($x=0;$x<sizeof($data);$x++)
                    {
                         print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data[$x]->name.'  ('.$data[$x]->id.')  <a class="del_parent"  href="#" data-id="'.$data[$x]->id.'">удалить</a> <br>');
                    }
                }
            }

            ?>
            <p>&nbsp;</p>
            <a class="add_parent" href="#" data-parent="0" data-parent_item="0">Добавить новый пункт</a>
        </div>
    </div>
</div>
</div>





   <script type="text/javascript">

        $('.menu_parent_type_0 > a.del_parent').on('click', function (e)
		{
			var id = $(this).attr('data-id');

			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});
					$.ajax
					({
						url: "/static/record/",
						type: "POST",
						data: {"id": id, "type": 'parent' },
						cache: true,
							beforeSend: function()
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет удаление...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{
								if(response == 1)
								{
									$(".modal-body").html('<h4 class="modal-title"><center>Меню удалено.</center></h4>');

									$('.content').load(url + '/json');

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');

								}
								else
								{
									alert("Произошла ошибка!");

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');
								}

							}
					});



            e.preventDefault();
        });


        $('.menu_parent_type_0 > a.add_parent').on('click', function (e)
		{
			var parent = $(this).attr('data-parent');
			var parent_item = $(this).attr('data-parent_item');

		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});

		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name"  placeholder="Добавить новый пункт меню"></p>');

		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');


			$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
			{
				var text = $('input.form-control.name').val();

				if(text == '')
				{
					alert("Введите текст");
				}
				else
				{
					$.ajax
					({
						url: "/static/add_menu_parent/",
						type: "POST",
						data: {"text": text, "parent": parent, "parent_item": parent_item, "type_id": 1  },
						cache: true,
							beforeSend: function()
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет сохранение...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{
								if(response == 0)
								{
									alert("Произошла ошибка!");

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');
								}
								else
								{
									response = $.parseJSON(response);

									$(".modal-body").html('<h4 class="modal-title"><center>' + response.text + '</center></h4>');

									$('.content').load(url + '/json');

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');

								}

							}
					});
				}
			});
            e.preventDefault();
        });



        $('.menu_parent_type_1 > a.del_parent').on('click', function (e)
		{
			var id = $(this).attr('data-id');

			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});
					$.ajax
					({
						url: "/static/record/",
						type: "POST",
						data: {"id": id, "type": 'parent', "type_id": 1 },
						cache: true,
							beforeSend: function()
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет удаление...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{
								if(response == 1)
								{
									$(".modal-body").html('<h4 class="modal-title"><center>Меню удалено.</center></h4>');

									$('.content').load(url + '/json');

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');

								}
								else
								{
									alert("Произошла ошибка!");

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');
								}
							}
					});
            e.preventDefault();
        });






        $('.menu_parent_type_1 > a.add_parent').on('click', function (e)
		{
			var parent = $(this).attr('data-parent');
			var parent_item = $(this).attr('data-parent_item');

		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});

		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name"  placeholder="Добавить новый пункт меню"></p>');

		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');


			$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
			{
				var text = $('input.form-control.name').val();

				if(text == '')
				{
					alert("Введите текст");
				}
				else
				{
					$.ajax
					({
						url: "/static/add_menu_parent/",
						type: "POST",
						data: {"text": text, "parent": parent, "parent_item": parent_item , "type_id": 2 },
						cache: true,
							beforeSend: function()
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет сохранение...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
							},
							success: function(response)
							{
								if(response == 0)
								{
									alert("Произошла ошибка!");

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');
								}
								else
								{
									response = $.parseJSON(response);

									$(".modal-body").html('<h4 class="modal-title"><center>' + response.text + '</center></h4>');

									$('.content').load(url + '/json');

									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});

									$('.modal-body').html('');

								}

							}
					});
				}
			});

            e.preventDefault();
        });




    </script>
