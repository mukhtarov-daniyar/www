<?	$CFG->oPageInfo->html_title = 'Настройки Whatsapp'; ?>


<h2>Настройки Whatsapp</h2>

<div class="white">
<a href="/whatsapp_new/static/">« Вернутся назад</a>

<style>
form { display:block; margin-top:20px;}
form .namber { display:block; font-size:12px; margin-bottom:20px;}

form .namber .rand_start{ width:30px;text-align:center; display:inline-block; vertical-align:top}
form .namber .rand_off{ width:30px;text-align:center; display:inline-block; vertical-align:top}

form .namber .time_start_hour{ width:30px;text-align:center; display:inline-block; vertical-align:top}
form .namber .time_start_minute{ width:30px;text-align:center; display:inline-block; vertical-align:top}

form .namber .time_off_hour{ width:30px;text-align:center; display:inline-block; vertical-align:top}
form .namber .time_off_minute{ width:30px;text-align:center; display:inline-block; vertical-align:top}

form .namber .rand{  display:inline-block; text-align:center; vertical-align:top; margin-left:20px;}
form .namber .time_start{  display:inline-block; text-align:center; vertical-align:top; margin-left:20px;}

form .namber .submit{  display:inline-block; text-align: left; vertical-align:top; margin-left:20px;}
form .namber .submit input { display:inline-block; margin-top:3px; }
form .namber .status{ display:inline-block; text-align:center; vertical-align:top; margin-left:20px; text-transform:uppercase; font-size:14px; color: #3C3; font-family: 'segoeui_sb';}

form .namber .status .yellow { color:#FC0;}
form .namber .status .blue { color: #0CF;}
form .namber .status .error { color: #f00;}



</style>



<form method="POST" class="response">


<?
	$data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE visible=1 ");


    for ($y=0; $y<sizeof($data); $y++)
    {
    	?>
        	<div class="namber">
        	    <input type="hidden" name="id[]" value="<?=$data[$y]->id?>">



                <div class="rand">
                	<input type="text" name="name[]" value="<?=$data[$y]->name?>">
                	<input type="text" name="namber[]" value="<?=$data[$y]->namber?>" style="display:block; margin-top:2px;">
                </div>

                <div class="rand">
                	<input type="text" class="rand_start" name="rand_start[]" value="<?=$data[$y]->rand_start?>">
                	<input type="text" class="rand_off" name="rand_off[]" value="<?=$data[$y]->rand_off?>">
                    <br>Тайминг между<br>сообщениями в мин.
                </div>

                <div class="time_start">
                    <input type="text" class="time_start_hour" name="time_start_hour[]" value="<?=$data[$y]->time_start_hour?>">
                    <input type="text" class="time_start_minute" name="time_start_minute[]" value="<?=$data[$y]->time_start_minute?>">
                    -
                    <input type="text" class="time_off_hour" name="time_off_hour[]" value="<?=$data[$y]->time_off_hour?>">
                    <input type="text" class="time_off_minute" name="time_off_minute[]" value="<?=$data[$y]->time_off_minute?>">
                    <br>Периуд времени рассылок<br>по времени Астаны
                </div>


				<div class="submit">
                 	<textarea style="width:0px; height:0px; overflow:hidden; position:absolute; right:0; bottom:0; border:0;" id="text-example_<?=$data[$y]->id;?>"><?=$_SERVER[REQUEST_SCHEME].'://'.$_SERVER[SERVER_NAME];?>/whatsapp/qr/<?=$data[$y]->namber;?></textarea>

                	<input type="submit" value="QR CODE" class="btn-clipboard_<?=$data[$y]->id;?>" data-clipboard-target="#text-example_<?=$data[$y]->id;?>" ><br>

                    <script type="text/javascript">
					$('.btn-clipboard_<?=$data[$y]->id;?>').on('click', function(e)
					{
						new ClipboardJS('.btn-clipboard_<?=$data[$y]->id;?>');

						$('.alert').html('Ссылка на QR code для номера <?=$data[$y]->namber;?> cкопирована!');
						$('.alert').animate({'opacity':'show'}, 1000);
						$('.alert').animate({'opacity':'hide'}, 4000);

						e.preventDefault();
					});
                    </script>
                    <? if($CFG->USER->USER_ID == 1) {?>
                	<input type="submit" value="CLEAR" data-id="<?=$data[$y]->wp_id;?>" class="clear"><br>
    				<input type="submit" value="RESTART"  data-id="<?=$data[$y]->wp_id;?>" class="restart">
                    <? } ?>
                </div>

				<div class="status">
                    <?
                        $url = 'https://eu36.chat-api.com/instance'.$data[$y]->wp_id.'/status?token='.$data[$y]->token.'';

                        $file = file_get_contents($url);
                        $json_a = json_decode($file, true);

                        if($json_a["accountStatus"] == 'authenticated')
                        {
                            echo 'Работает';
                        }
                        elseif($json_a["accountStatus"] == 'loading')
                        {
                            echo '<span class="yellow">Нет интернета</span>';
                        }
                        elseif($json_a["accountStatus"] == 'got qr code')
                        {
                            echo '<span class="blue">Сессия отключена</span>';
                        }
                        else
                        {
                            echo '<span class="error">ERROR</span>';
                        }
                    ?>
                </div>
            </div>
        <?
    }

?>

<input type="submit" value="Сохранить" class="btn btn-refreh" style=" margin-left:15px;">
</form>
<br><br><br><br>
</div>


<div class="respons"></div>

<script>


	$(document).on('click','input.btn.btn-refreh',function(e)
	{
		var msg = $('form.response').serialize();

		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

		$.ajax(
		{
			url:'/whatsapp_new/wp_options/',
			data: msg,
			type: 'POST',
			beforeSend: function()
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(status)
			{
				setTimeout(function() {location.href = '/whatsapp_new/options/';}, 0,1);
			}
		});
		e.preventDefault();
	});


	$(document).on('click','input.clear',function(e)
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

		var id = $(this).attr('data-id');

		$.ajax(
		{
			url:'https://us-central1-app-chat-api-com.cloudfunctions.net/cleanInstance',
			data:{"uid": "eUBSJoV8XwcJJVCKPU9QkXtTNi02", "instanceId":id, },
			type: 'POST',
			beforeSend: function()
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Очищаем аккаунт...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(status)
			{
				$('#myModalBox').modal('hide') ;
				setTimeout(function() {window.location.reload();}, 0,1);
			}
		});
		e.preventDefault();
	});

	$(document).on('click','input.restart',function(e)
	{
		$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

		var id = $(this).attr('data-id');

		$.ajax(
		{
			url:'https://us-central1-app-chat-api-com.cloudfunctions.net/reloadInstance',
			data:{"uid": "eUBSJoV8XwcJJVCKPU9QkXtTNi02", "instanceId":id, },
			type: 'POST',
			beforeSend: function()
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Перезагружаем аккаунт...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(status)
			{
				$('#myModalBox').modal('hide') ;
				setTimeout(function() {window.location.reload();}, 0,1);
			}
		});
		e.preventDefault();
	});
</script>
