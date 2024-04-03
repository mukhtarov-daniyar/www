<?
	if($data->page_id == 1000 && $CFG->_GET_PARAMS[0] == $data->id)	exit;
	$namebuch = SelectDataName('users', 'name', $CFG->USER->USER_ACCOUNTING_CHIEF);
?>
<script type="text/javascript">
	$('#myModalBox').modal({backdrop: 'static', keyboard: false});
	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

	$(".modal-body").append('<center><p class="access">ГлавБух <?=$namebuch;?>, одобрила закрытие сделки <strong>*<?echo $data->id;?></strong> <br> Автор сделки: <strong><? echo SelectData('users', $data->manager_id); ?></strong> <p></center>');

	$(".modal-body").append('<div class="access_go"><center><button data-id="<?=$data->id; ?>" data-rel="1" style="margin-top:10px; padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Открыть</button> &nbsp; &nbsp; <button data-id="<? echo $data->id; ?>" data-rel="2" style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal">Одобрить без открытия</button> &nbsp; &nbsp; <button data-id="<? echo $data->id; ?>" data-rel="3" style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal">Скрыть на 20 минут</button> <br><br><strong><? print_r(count($real)); ?> На модерации</strong></center></div>');

	$('.access_go button').on('click', function (e)
	{
		var id = $(this).attr('data-id');
		var type = $(this).attr('data-rel');

		if(type == 1)
		{
			window.open("/deal/<?=$data->id;?>/", '_blank');
		}
		else if (type == 2)
		{
			window.open("/static/on_alarm_boss/<?=$data->id;?>/", '_blank');

		}
		else if (type == 3)
		{
			$.ajax
			({
				url: "/static/deal_stop_20/",
				type: "POST",
				data: {"record": id},
				cache: true,
				beforeSend: function()
				{
					$('#myModalBox').modal({backdrop: 'static', keyboard: false});

					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
				},
				success: function(response)
				{
					setTimeout(function() {window.location.reload();}, 0,9);
				}
			});

		}



		e.preventDefault();
	});

</script>
