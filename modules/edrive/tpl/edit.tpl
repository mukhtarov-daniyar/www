<? $CFG->oPageInfo->html_title = 'Редактировать ЭлектроАВТО'; ?>
<h2><img alt="" src="/tpl/img/new/icon/auto_r.png">Редактировать ЭлектроАВТО</h2>

<div class="white">

  <form method="POST" class="add" action="/edrive/edit/">
    <input type="hidden" name="user_act" value="edit_up" />
    <input type="hidden" name="post_id" value="<?=$sp->id;?>" />

    <div class="kriteri">
       <span>Модель авто:</span>
       <select name="car" class="selectpicker" data-live-search="true">
         <option value="0" selected>Любая</option>
         <?
          $car = getSQLArrayO("SELECT * FROM my_edrive_car ORDER BY name ASC");
          for($i=0;$i<sizeof($car);$i++)
          {
            if($sp->car_id == $car[$i]->id) {$selected = '  selected  ';} else {  $selected = '';  }
            ?>
              <option value="<?=$car[$i]->id?>"<?=$selected?>><?=$car[$i]->name;?></option>
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
          if($sp->city_id == $city[$i]->id) {$selected = '  selected  ';} else {  $selected = '';  }
                 ?>
            <option value="<?=$city[$i]->id?>"<?=$selected?>><?=$city[$i]->name;?></option>
      <? } ?>
    </select>
  </div>

  <div class="kriteri">
    <span>Укажите порт:</span>
    <select name="port" class="selectpicker port">
       <option value="0" selected>Любой</option>
       <?
        $port = getSQLArrayO("SELECT * FROM my_edrive_car_port ORDER BY id ASC");
        for($i=0;$i<sizeof($port);$i++)
        {
          if($sp->port_id == $port[$i]->id) {$selected = '  selected  ';} else {  $selected = '';  }
          ?>
            <option value="<?=$port[$i]->id?>"<?=$selected?>><?=$port[$i]->name;?></option>
      <? } ?>
    </select>
  </div>

    <div class="kriteri" id="add_client_id">
      <input type="hidden" name="client" class="client" value="<?=$sp->client_id;?>" />
      <a href="#"><? $sql = getSQLRowO("SELECT name FROM my_face WHERE id='{$sp->client_id}'  "); echo $sql->name;?> <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
  <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
</svg> </a>
    </div>

    <input type="submit" value="Сохранить" class="btn  btn-danger" style="margin-left:380px; margin-top:15px;">

    <br><br><br>
  </form>
</div>

  <script>


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
