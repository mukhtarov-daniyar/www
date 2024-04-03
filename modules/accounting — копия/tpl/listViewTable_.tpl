

<br clear="all">
<div class="table-responsive">

       
       
 	<script>
		$(function(){
			$('[data-toggle="tooltip"]').tooltip();
		});
  	</script>
       
   <!--table class="users activS"!-->
       
        <table class="users ">
            <tr>  
                <th style="width:10% !important"><strong>Сумма</strong></th>
                <th style="width:10% !important"><strong>Дата</strong></th>
                <th style="width:10% !important"><strong>Счет</strong></th>
                <th style="width:10% !important"><strong>Менеджер</strong></th>
                <th style="width:55% !important"><strong>Комментарий</strong></th>
               	<th style="width:5% !important"></th>
            </tr>
    
           <?
            for ($i=0; $i<sizeof($l); $i++)
                {
                    $o = $l[$i]; 
                     
                    if($o->type_id == 2) $bg = ' class="yellow"'; else $bg = '';
                    
                    $type_id[1] = 'Приход';  $type_id[2] = 'Расход'; 
                    
                    $img = explode ('/',$o->attachment);
                    for ($y=0; $y<sizeof($img); $y++)
                    {
                        if($img[$y] == "" || $img[$y] == "0") continue;
                        $pr .= $img[$y].'/';
                    }

                ?>        
            <tr<?=$bg;?>>
                <td style="width:10% !important"><strong style="color:#BD2149"><?=number_format($o->price, 0, ' ', ' '); ?>     <?  $ss[] = $o->price;  $array[] = array_sum($ss);   ?> <? if($o->attachment) { ?> <a href="/<?=substr($pr, 0, -1);?>" target="_blank" style=" display:inline-block; width:10px;"><img src="/tpl/img/new/attach.png" style=" display:inline-block;width:10px;"></a> </strong><? } ?></td>
                <td style="width:10% !important"><strong style="color:#468847"><? echo dateSQL2TEXT($o->cdate, "DD.MM.YY, hh:mm");?></strong></td>
                <td style="width:10% !important"><div class="cat" data-toggle="tooltip" data-placement="bottom" title="<? echo $str = SelectData('money_accounting_type_id', $o->cat_id); ?>"><strong><?=$o->cat_id;?></strong> - <? echo $str = SelectData('money_accounting_type_id', $o->cat_id); ?></div></td>
                <td style="width:10% !important"><? $str = SelectDataRowOArray('users', $o->user_id, 0); ?> <a href="/profile/view/<?=$str->id;?>" target="_blank"><?=$str->name;?></a></td>
                <td style="width:55% !important"><div class="text"  data-toggle="tooltip" data-placement="bottom" title="<? echo strip_tags($o->text); ?>"><? echo strip_tags($o->text); ?> </div></td> 
                <td style="width:5% !important"><div class="tes_bi_<?=$o->id;?>" data-toggle="tooltip" data-placement="bottom" title="<? echo strip_tags($o->intro); ?>"><div class="buch_status <? if($o->status == 1) {echo 'show';}?> id_<?=$o->id;?>" data-id="<?=$o->id;?>" data-rel="<?=$o->status;?>"></div></div></td>     
            </tr>
           <? $pr = '';
                }
            ?>


            <tr>
                <td colspan="6" style="text-align:left; font-size:14px;">
                	<strong> Общая сумма по операциям: <? $itogo = array_reverse($array); echo number_sum($itogo[0]);?></strong>  
                	<hr>
                    <strong> Итог в кассе: <? echo $ACCOUNT->sum($CFG->USER->USER_DIRECTOR_ID);?></strong>
                </td>
            </tr>


        </table>
    </div>            
    
<style> 
.buch_status { background: no-repeat center url('/tpl/img/ok_off.png'); background-size:80%; cursor:pointer; display:inline-block; width:20px; height:15px;}
.buch_status.show {background: no-repeat center url('/tpl/img/ok_on.png'); background-size:80%;  cursor:pointer; display:inline-block !important; width:20px; height:15px;}
</style>

<? if($CFG->USER->USER_ID == 536){?>   
<script>
$(document).ready(function()
{
	$('.buch_status').live('click', function (e) 
	{	
		var id = $(this).attr('data-id');
		var status = $(this).attr('data-rel');
	
		
		if(status == 0)
		{
			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});
	
			$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Введите Ваш комментарий</h4></div>');
			$(".modal-body").append('<p><textarea class="form-control text" rows="2" style="margin-bottom:15px;"></textarea> <div id="textareaFeedback" class="ps_1"></div></p>');
			$(".modal-body").append('<p><button type="submit" class="btn btn-primary submit">Отправить</button></p>');
			
				$('.btn.submit').on('click', function(e)
				{	
					var textarea = $('.form-control.text').val();
					
					$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 
					
					$.ajax
					({
						url: "/accounting/status/", 
						type: "POST",      
						data: {"id": id, "status": status, "desc": textarea},
						cache: true,			
						success: function(response) 
						{
							response = $.parseJSON(response);
							
							if(response.status == 1)
							{		

								$('.buch_status.id_' + id).addClass('show');
								$('.tes_bi_' + id).attr('title', response.text);
								$('.tes_bi_' + id).attr('data-original-title', response.text);

								$(document).ready(function()
								{
									$("#myModalBox").modal('hide');
								});
									
								$(".modal-body").html(''); 
							}
							
						}
					
					});				
					
					e.preventDefault();

				
				});		
		}
	
		e.preventDefault();
			
	});
	
	
});
</script>

<? } ?>  