<? if($data->page_id == 1000 && $CFG->_GET_PARAMS[0] == $data->id)	exit; ?>
<script type="text/javascript">
	$('#myModalBox').modal({backdrop: 'static', keyboard: false});
	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

	$(".modal-body").append('<center><p class="access"><strong><? echo SelectData('users', $data->manager_id); ?></strong>, просит закрыть сделку <strong>*<? echo $data->id;?></strong> <p></center>');


	$(".modal-body").append('<div class="access_go"><center><button data-id="<?=$data->id; ?>" data-rel="1" style="margin-top:10px; padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Открыть</button> &nbsp; &nbsp; <button data-id="<? echo $data->id; ?>" data-rel="2" style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal">Одобрить без открытия</button></center></div>');

//href="/static/on_alarm_black/43601"
	$('.access_go button').on('click', function (e)
	{
		var id = $(this).attr('data-id');
		var type = $(this).attr('data-rel');


		if(type == 1)
		{
			window.open("/deal/<?=$data->id;?>/", '_blank');
		}
		else
		{
			window.open("/static/on_alarm_black/<?=$data->id;?>/", '_blank');
		}


		e.preventDefault();
	});

</script>
