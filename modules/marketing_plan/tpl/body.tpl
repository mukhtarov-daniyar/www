<h2><img alt="" src="/tpl/img/new/icon/3_red.png"> <?=$CFG->oPageInfo->html_title;?></h2>

<div class="white">

<button id="add">Добавить новый план</button>


<style type="text/css">

#add { display:inline-block; margin-bottom:10px; background: #F84241; border:0; padding:10px 15px;  font-family: 'segoeui_sb'; color:#FFF;  text-transform:uppercase; border-radius:5px  !important}

table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:14px; text-align:center; margin-bottom:20px;  font-family: 'segoeui'; text-transform:uppercase}
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align:middle; font-size:12px; color:#000;}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:15px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}

</style>
    <table class="price">
        <tr>
          <th>Название</th>
          <th>Автор</th>
          <th>Абонент</th>
          <th>Время работы</th>

          <th>Статус</th>
        </tr>
                   <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>

              <td></td>
            </tr>       
                   
    </table>



<script type="text/javascript">

/* поиск в шапке. включение расширеного поиска*/
$('#add').on('click', function()
{
	$('#myModalBox').modal({backdrop: 'static', keyboard: false}) ;
	$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
	$(".modal-body").append('<div class="modal-header"><h4 style=" text-align:center;" class="modal-title">Добавить новый маркетинг план.</h4></div>');
	$(".modal-body").append('<p><input type="text" class="form-control name" value="" placeholder="Название рассылки"></p>');	
	$(".modal-body").append('<p><input type="text" class="form-control mobile" value="" placeholder="Номер абонента"></p>');	
	$(".modal-body").append('<button type="button" class="btn btn-warning" style="    background: #F8403E;">Следующий шаг</button>');

	$('.mobile').inputmask("+7-999-999-99-99");
	

	$(document).on('click','button.btn.btn-warning',function(e)
	{
		var name = $('.form-control.name').val();
		var mobile = $('.form-control.mobile').val();
		
		
			
				$.ajax
				({
					url: "/marketing_plan/data/", 
					type: "POST",      
					data: {"name": name, "mobile": mobile, "user": <?=$CFG->USER->USER_ID;?>},
					cache: true,			
						beforeSend: function() 
						{			
							
						},
						success: function(response) 
						{
							

						}
				});
				
		
	});
	
});

</script>


</div>
