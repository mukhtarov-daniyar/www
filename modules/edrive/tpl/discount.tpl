



<div class="white">

  <style type="text/css">
    .skidos .default_signimpress, .skidos .default_led { background: none; border: 0; width:80px; margin: 0 auto; border: solid 1px #404040; border-radius:5px !important; text-align: center; padding: 0; padding: 3px 0; padding-left: 10px;}
    .skidos td, .skidos th { text-align: center;}
    .skidos button.btn.signimpress, .skidos button.btn.led { font-size: 12px; padding: 0; margin-left:5px; padding: 4px 10px;border-radius:5px !important; position: relative;top: -2px;}
    .vender #vender, .vender #skidos_number{ width:120px; border: 0; border-radius: 3px !important; border: solid 1px #404040; font-size: 12px; padding: 5px; position: relative; left:20px; text-align: center; }
    .skidos .bootstrap-select { width: 150px !important}
    .skidos .btn.vender_add{ font-size: 12px; padding: 0; margin-left:5px; padding: 6px 20px;border-radius:5px !important; position: relative;top:0px;}
  </style>

  <article class="vacancies_body row">
    <table class="table skidos">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Артикул</th>
          <th scope="col">Сайт</th>
          <th scope="col">Скидка %</th>
          <th scope="col">Автор</th>
        </tr>
      </thead>
      <tbody>
        <tr title="Стандартная скидка на товары в ИМ molotok.kz" data-toggle="tooltip" data-placement="left"  style="background:#FFCEAD">
          <th scope="row">1</th>
          <td>@molotok.kz</td>
          <td>molotok.kz</td>
          <td><input value="<?=$data->default_signimpress;?>" name="default_signimpress" class="default_signimpress" type="number">  <button type="button" data-id="default_signimpress" class="btn btn-danger signimpress">Сохранить</button></td>
          <td>Default</td>
        </tr>
        <tr title="Дополнительная скидка за хорошие отношения на товары в ИМ molotok.kz" data-toggle="tooltip" data-placement="left" style="background:#FFCEAD">
          <th scope="row">2</th>
          <td>Хорошие отношения</td>
          <td>molotok.kz</td>
          <td><input value="<?=$data->good_signimpress;?>" name="good_signimpress" class="default_signimpress" type="number">  <button type="button" data-id="good_signimpress" class="btn btn-danger signimpress">Сохранить</button></td>
          <td>Default</td>
        </tr>

        <tr title="Стандартная скидка на товары в ИМ led.ru" data-toggle="tooltip" data-placement="left" style=" background:#F4C3C3">
          <th scope="row">3</th>
          <td>@led.ru</td>
          <td>led.ru</td>
          <td><input value="<?=$data->default_led;?>" name="default_led" class="default_signimpress" type="number">  <button type="button" data-id="default_led" class="btn btn-danger signimpress">Сохранить</button></td>
          <td>Default</td>
        </tr>
        <tr title="Дополнительная скидка за хорошие отношения на товары в ИМ led.ru" data-toggle="tooltip" data-placement="left" style="background:#F4C3C3">
          <th scope="row">4</th>
          <td>Хорошие отношения</td>
          <td>led.ru</td>
          <td><input value="<?=$data->good_led;?>" name="good_led" class="default_signimpress" type="number">  <button type="button" data-id="good_led" class="btn btn-danger signimpress">Сохранить</button></td>
          <td>Default</td>
        </tr>
        <?
          $res = getSQLArrayO("SELECT * FROM my_face_vendor WHERE page_id='{$CFG->_GET_PARAMS[0]}'  ");

          $cnt = 5;
          foreach($res as $value)
          {

            $test = getSQLRowO(" SELECT name FROM my_data_1c WHERE vendor = '{$value->vendor}' ");
            ?>
            <tr title="<?=$test->name;?>" data-toggle="tooltip" data-placement="left">
              <th scope="row"><?=$cnt;?></th>
              <td><?=$value->vendor;?>
                  <a href="#" class="del_face_vender" data-id="<?=$value->id;?>" style="display:block; font-size:10px;" title="УДАЛИТЬ? - <?=$test->name;?>" data-toggle="tooltip" data-placement="right">удалить</a></td>
              <td><? if($value->site == 1){ echo 'molotok.kz';} elseif($value->site == 2){ echo 'led.ru';}  ?></td>
              <td><?=$value->number;?></td>
              <td <? if($value->cdate !='') { echo '  title=" '.dateSQL2TEXT($value->cdate, "DD.MM.YY").' " ';}?> data-toggle="tooltip" data-placement="top"><?=SelectData('users', $value->user_id);?></td>
            </tr>
            <?  $cnt++;
          }
        ?>
        <tr class="vender">
          <td colspan="2"><input type="number" name="vender" value="" placeholder="Артикул"  id="vender"/></td>
          <td>
            <select name="site" class="selectpicker show-tick site">
              <option value="1" selected>molotok.kz</option>
              <option value="2">led.ru</option>
            </select>
          </td>
          <td><input type="number" name="skidos" value="" placeholder="Скидка %"  id="skidos_number"/></td>
          <td><button type="button" class="btn btn-danger vender_add">Сохранить</button></td>
        </tr>

      </tbody>
    </table>
  </article>
</div>


<script>
	$(document).on('click','.btn.signimpress',function(e)
	{
    var id = $(this).attr('data-id');
    var res = $('.skidos input[name='+id+']' ).val();

    $('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;

    $.ajax
      ({
        url: "/person/order_skidos/",
        type: "POST",
        data: {"id": id , "res": res, "page_id": "<?=$CFG->_GET_PARAMS[0];?>",   },
        cache: true,
          beforeSend: function()
          {
            $(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
          },
          success: function(response)
          {
            $(document).ready(function()
            {
              setTimeout(function() {window.location.reload();}, 3000);
            });
          }
      });
	});


  $(document).on('click','.btn.vender_add',function(e)
  {
    var vendor = $('.skidos input#vender' ).val();
    var site =  $('.skidos .selectpicker.site').val();
    var skidos_number = $('.skidos input#skidos_number' ).val();

    if(vender == '' || skidos_number == '')
    {
      alert('Укажите корректно артикул и процент скидки!');
      return false;
    }

    $.ajax
      ({
        url: "/person/order_skidos_vender/",
        type: "POST",
        data: {"vendor": vendor , "site": site,  "skidos_number": skidos_number, "page_id": "<?=$CFG->_GET_PARAMS[0];?>", "user_id": "<?=$CFG->USER->USER_ID;?>", "mobile": "<?=$o->mobile;?>"  },
        cache: true,
          beforeSend: function()
          {
            $('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
            $(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
          },
          success: function(response)
          {
            if(response > 0)
            {
              $(document).ready(function()
              {
                $(".modal-body").html('<h4 class="modal-title"><center>Товар для скидки добавлен! Перезагружаем страницу...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
                setTimeout(function() {window.location.reload();}, 3000);
              });
            }

            if(response == 0)
            {
              $(".modal-body").html('<h4 class="modal-title"><center>Такой товар не существует в базе 1С! Мы перезагрузим страницу а Вы корректно введите артикул.</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
              setTimeout(function() {window.location.reload();}, 5000);
            }
          }
      });


  });


</script>
