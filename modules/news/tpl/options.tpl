<hr>
<span style=" font-family:'segoeui_sb'; font-size:16px; margin-right:10px;">Сформировать данные</span>
<select name="options_company" class="selectpicker show-tick" id="options_company">
  <option value="0">Выберите действие</option>
  <option value="5">Печать</option>
  <? if( $CFG->USER->USER_ID == 85 ||  $CFG->USER->USER_ID == 1 || $CFG->USER->USER_BOSS == 1 ||  $CFG->USER->USER_ID == 133) {?>
    <option value="6">Телефоны в формате .VCF</option>
    <option value="2">Список E-mail</option>
    <option value="9">Отметка для всех</option>
    <option value="7">Экспорт для смс</option>
  <? } ?>
  <? if( $CFG->USER->USER_ID == 85 ||  $CFG->USER->USER_ID == 1 || $CFG->USER->USER_BOSS = 1 ||  $CFG->USER->USER_ID == 133) {?>
    <option value="10">Рассылка Whatsapp</option>
  <? }?>
</select>
<hr>


<script type="text/javascript">



  $('#options_company').change(function()
  {
      var id = $(this).val();
      if(id == 5 || id == 2 || id == 6 || id == 10 || id == 7)
      {
        window.open(location.href+'&add='+id, '_blank');
      }

      /*  Отметка для всех  */
      if(id == 9 )
      {
        $('#myModalBox').modal({backdrop: 'static', keyboard: false});
      	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
      	$(".modal-body").append('<h4 class="modal-title"><center>Текст заметки для всех запсией.</center></h4>');
      	$(".modal-body").append('<textarea class="form-control" rows="2" style="margin-top:7px;" ></textarea>');
      	$(".modal-body").append('<center class="markS"><button style="margin-top:10px; margin-left:10px;"  data-rel="2" type="button" class="btn btn-default">Отправить</button></center>');

  			$('.markS > button').on('click', function(e)
  			{
  				var text = $('textarea.form-control').val();
  				$.ajax
  				({
  					url: location.href+'&add=3/json',
  					type: "GET",
  					data: {"text": text},
  					cache: true,
  						beforeSend: function()
  						{
  							$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
  						},
  						success: function(response)
  						{
  							response = $.parseJSON(response);
  							if(response.status == 1)
  							{
  								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">' + response.text + '</h3></center>');
  								window.location.reload();
  							}
  							if(response.status == 0)
  							{
  								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');
  								window.location.reload();
  							}
  						}
  				});
  			});

      }

  });
</script>
