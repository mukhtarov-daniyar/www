<? $CFG->oPageInfo->html_title = 'Добавить ЭлектроАВТО'; ?>
<h2><img alt="" src="/tpl/img/new/icon/auto_r.png">Добавить ЭлектроАВТО</h2>

<div class="white">

  <form method="POST" class="add" action="/edrive/add/up/">
  	<input type="hidden" name="user_act" value="add_up" />

    <div class="kriteri">
       <span>Модель авто:</span>
       <select name="car" class="selectpicker" data-live-search="true">
         <option value="0" selected>Любая</option>
         <?
          $car = getSQLArrayO("SELECT * FROM my_edrive_car ORDER BY name ASC");
          for($i=0;$i<sizeof($car);$i++)
          {
            ?>
              <option value="<?=$car[$i]->id?>"<?=$sel?>><?=$car[$i]->name;?></option>
        <? } ?>
       </select>
   </div>

  <div class="kriteri">
    <span>Город:</span>
    <select name="city" class="selectpicker city" data-live-search="true">
       <option value="0" selected>Любой</option>
       <?
        $city = SelectDataArray('city', 1, 'name ASC');
        for($i=0;$i<sizeof($city);$i++)
        {
            //($CFG->USER->USER_CITY == $city[$i]->id) ? $sel = "selected" : $sel = "";
                 ?>
            <option value="<?=$city[$i]->id?>"<?=$sel?>><?=translit($city[$i]->name);?></option>
      <? } ?>
    </select>
  </div>

  <div class="kriteri">
    <span>Скоростной порт:</span>
    <select name="port" class="selectpicker port">
       <option value="0" selected>Любой</option>
       <?
        $port = getSQLArrayO("SELECT * FROM my_edrive_car_port ORDER BY id ASC");
        for($i=0;$i<sizeof($port);$i++)
        {
          ?>
            <option value="<?=$port[$i]->id?>"<?=$sel?>><?=$port[$i]->name;?></option>
      <? } ?>
    </select>
  </div>

    <div class="kriteri" id="add_client_id">
      <input type="hidden" name="client" class="client" value="0" />
      <a href="#">+ Добавить владельца</a>
    </div>

    <input type="submit" value="Сохранить" class="btn  btn-danger" style="margin-left:380px; margin-top:15px;">

    <br><br><br>
  </form>
</div>

  <script>
  $(document).on('click','form.add input.btn-danger',function(e)
  {
      var data = $('form.add').serialize();

      $('#myModalBox').modal({backdrop: 'static', keyboard: false});
      $(".modal-body").html('<h4 class="modal-title"><center>Сохраняем...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

      $.ajax({
      	url: '/edrive/add/up/',
      	data: data,
      	type: 'POST',
        success: function(status)
      	{
          if(status > 1)
          {
            $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><center>Обновляем страницу...</center></h4></div>');
            setTimeout(function() { document.location.href = '/edrive/';  }, 1000);
          }
          if(status == 0)
          {
            $(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Заполните все поля!</h4></div>');
          }
        }
      });
     e.preventDefault();
  });





  $(document).on('click','#add_client_id',function(e)
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false});

		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Добавить владельца авто</h4></div>');

		$(".modal-body").append('<p><input type="text" class="form-control name mobile" value="" placeholder="Начните вводить телефон или ФИО..."></p>');
		$(".modal-body").append('<p class="add_new_profile"><a href="/profile/add_person/ "target="_blank">+ создать новое физ. лицо<a></p>');
		$(".modal-body").append('<div id="form-control-Feedback"></div>');

		$('.selectpicker').selectpicker();


		$('input.form-control.name').keyup(function()
		{
				var curLength = $('input.form-control.name').val().length;
				var curText = $('input.form-control.name').val();

				if (curLength > 2)
				{
					$('input.form-control.name').removeClass('warning');
					$('#form-control-Feedback').html('');

					$.ajax
					({
						url: "/static/client_id_search/",
						type: "POST",
						data: {"text": curText},
						cache: true,
						beforeSend: function()
						{
						},
						success: function(response)
						{
							if(response != 0) {$("#form-control-Feedback").html('<div class="loadnemes"> '+response+' </div>');	}else{$("#form-control-Feedback").html('Ничего не найдено :(');
            }

						$('.modal-body').off('click').on('click', 'a.a_go', function(e)
						{
							var id = $(this).attr('data-id');
							var mobile = $(this).attr('data-mobile');
							var name = $(this).attr('data-name');
							var avatar = $(this).attr('data-avatar');

              $('#add_client_id a').html(name);
              $('#add_client_id .client').val(id);

              $("#myModalBox").modal('hide');

							e.preventDefault();
						});

					}

					});

					$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
					{

					});
				}
				else
				{
					$('input.form-control.name').addClass('warning');
					$('#form-control-Feedback').text('Минимум 3 символа...');
				}
		});


		e.preventDefault();
	});




  </script>
