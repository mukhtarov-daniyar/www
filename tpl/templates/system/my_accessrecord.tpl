<? if($sql->counts != 0) { ?>

<script type="text/javascript">
	$('#myModalBox').modal({backdrop: 'static', keyboard: false});
	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
	$(".modal-body").append('<p class="access"><strong><? echo SelectData('users', $sql->user_id); ?></strong>, в записи <strong>*<? echo $sql->page_id;?></strong> написал Вам важную заметку <p>');
	$(".modal-body").append('<div class="access_go"><center><button data-id="<? echo $sql->id; ?>" data-rel="1" style="margin-top:10px; padding-left: 15px !important; padding-right: 15px !important;" type="submit" class="btn btn-primary submit">Прочитать её</button> &nbsp; &nbsp; <button data-id="<? echo $sql->id; ?>" data-rel="2" style="margin-top:10px;" type="button" class="btn btn-default" data-dismiss="modal">Напомнить позже</button>	<div style="margin-top:10px; width:100%"><strong>Всего не прочитано: <?=$sql->counts;?></strong></div> 		</center></div>');


	$('.access_go button').on('click', function (e)
	{
		var id = $(this).attr('data-id');
		var type = $(this).attr('data-rel');

		$.ajax
		({
			url: "/static/accessalert/",
			type: "POST",
			data: {"type": type, "id": id },
			cache: true,
			beforeSend: function()
			{
				$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');
			},
			success: function(response)
			{
				$(document).ready(function(){
					$("#myModalBox").modal('hide');
				});

				if(type == 3)
				{
					$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');

					setTimeout(function() {window.location.reload();}, 0,1);
				}

				if(type == 1)
				{
					<?
					if(stristr($_SERVER['HTTP_USER_AGENT'], 'iPhone') === FALSE)
					{?>
						window.open("/<? echo $url;?>/<?=$sql->page_id;?>/#comment-post_<?=$sql->parent_id;?>", '_blank');
					<? }
					else
					{ ?>
						document.location.href = "/<? echo $url;?>/<?=$sql->page_id;?>/#comment-post_<?=$sql->parent_id;?>";
					<? }
					?>
				}
			}

		});

		e.preventDefault();
	});

</script>
<? } ?>
