

<? if($sql->conts > 0) {?>

<script type="text/javascript">

	$('.alert').animate({'opacity':'show'}, 1500);

	$('.alert').html('В записи <strong>*<? echo $sql->page_id; ?></strong> к Вашей заметке поступил ответил <br> <a class="replycomment" data-id="<? echo $sql->id;?>" href="#"><strong>Прочитать ответ</strong></a> &nbsp; &nbsp; Всего ответов: <? echo $sql->conts; ?>');

	$('a.replycomment').on('click', function (e)
	{
		var id = $(this).attr('data-id');

		$.ajax
		({
			url: "/static/replycommentalert/",
			type: "POST",
			data: {"id": id },
			cache: true,
			beforeSend: function()
			{
				$('#myModalBox').modal({backdrop: 'static', keyboard: false});
				$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				$("#myModalBox").modal('hide');

				if(response == 1)
				{
					window.open("/<? echo $url;?>/<?=$sql->page_id;?>/#comment-post_<?=$sql->parent_id;?>", '_blank');
				}
			}

		});

		e.preventDefault();
	});

</script>

<? } ?>
