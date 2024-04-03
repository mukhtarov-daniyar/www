
<?

if(is_numeric($CFG->_GET_PARAMS[0]) > 0)
{
	
	$data = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}receipt WHERE id='{$CFG->_GET_PARAMS[0]}' "); ?>
	
    <style type="text/css">
		@font-face{ font-family:'chek'; src: url('/tpl/font/dot_fonts/dotf1.ttf');}
		div {font-family:'chek'; font-size:9px; color:#000; line-height:20px; text-align:center}
	</style>


	<div>
	 <? 
	 $array = unserialize($data->name);
	 $arrays = unserialize($data->counts);
	 $arrayp = unserialize($data->price);
	
	 echo '<span>ИП «Алимжанова И. А.»</span><br>';
	 echo 'Добро пожаловать<br>';
	
	 if($array  ==! "") 
	 { 
		for ($y=0; $y<sizeof($array); $y++) 
		{
			
			echo $y+1; echo '.'.$array[$y].' - '.$arrayp[$y].'тг. - '.$arrays[$y].'шт. - '.$arrays[$y]*$arrayp[$y].'тг. <br>';
			if(is_numeric($arrays[$y]) < 0) break; 
			
			$ob .=$arrays[$y]*$arrayp[$y].',';
		}	
	 }
	
	$ob = explode(",", $ob);
	$ob = array_sum($ob);
	 
	echo 'Итого: '.$ob.'тг.<br>';
	echo $data->cdate.'<br>';
	echo 'ИНН: 851121450862<br>';
	echo '* * * * *<br>';
	echo 'Спасибо за покупку!<br>';
	echo '* * * * *<br><br>';
	echo '<span style="color:#E0E0E0">.</span>';
	echo '</div>';
	$obsh = 0;
	exit;
}

?>


<div class="content">	
	<h2>Товарный чек</h2>
    <div class="white">
        <style type="text/css">

table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:14px; text-align:center; margin-bottom:20px;  font-family: 'segoeui'; text-transform:uppercase}
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align:middle; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:15px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}

#upAvatars { font-size:14px; text-transform:uppercase; padding:15px 35px; display: inline-block !important;  background: #F84241;  display:block; margin-top:10px; margin-bottom:30px; font-family: 'segoeui_sb'; color:#FFF; border-radius:5px;}

</style>
<a href="#" id="upAvatars">Создать новый чек</a>
  

  <table class="price">
    <tr>
      <th>Чек</th>
      <th>Время</th>
      <th>Автор</th>
      <th>Товар</th>
      <th>Сумма</th>
    </tr>
       
<?
$query = "SELECT * FROM {$CFG->DB_Prefix}receipt ORDER BY cdate DESC";
$o = getSQLArrayO($query);

for ($t=0; $t<sizeof($o); $t++)
{
	?>
        <tr>
          <td><a href="/receipt/<?=$o[$t]->id;?>">Чек №<?=$o[$t]->id;?></a></td>
          <td><?=$o[$t]->cdate;?></td>
          <td><? $users = SelectDataRowOArray('users', $o[$t]->user_id, 0); print_r($users->name); ?></td>
          <td>
		  <?
		  

	 $arr = unserialize($o[$t]->name);
	 $arrs = unserialize($o[$t]->counts);
	 $arrp = unserialize($o[$t]->price);
		 
		for ($r=0; $r<sizeof($arr); $r++) 
		{
			
			echo $r+1; echo '.'.$arr[$r].' - '.$arrp[$r].'тг. - '.$arrs[$r].'шт. - '.$arrs[$r]*$arrp[$r].'тг. <br>';
			if(is_numeric($arrs[$r]) < 0) break; 
			
			$obsh .=$arrs[$r]*$arrp[$r].',';
		}	
			
			$obsh = explode(",", $obsh);
			$obsh = array_sum($obsh);
	
		  
		  
		  ?>
          </td>
          <td><? echo $obsh; $obsh = 0;?> тг.</td>
        </tr>

	<? 
}



?>      
       
   </table>

</div>


    </div>
</div>

<script type="text/javascript"> 
	$(document).on('click','#upAvatars',function(e) 
	{
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});
		
		$(".modal-body").html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 style=" text-align:center;" class="modal-title">Создать новый чек</h4></div>');
				
		$(".modal-body").append('<form class="test"><p><input type="text" class="form-control name" name="name[]" placeholder="Описание" style=" width:300px; display:inline-block"> <input type="text" class="form-control count" name="count[]" placeholder="0" style="display:inline-block; width:50px;"> шт.   <input type="text" class="form-control price" name="price[]" placeholder="0" style="display:inline-block; width:80px; margin-left:10px"> сумма</p></form>');

		$(".modal-body").append('<div class="client_mask"></div>');

		$(".modal-body").append('<p><a href="#" class="add_input">+ Добавить еще поле</a></p>');
		
		$(".modal-body").append('<p><button type="button" class="btn btn-primary">Создать</button></p><br clear="all"></form>');

		$('.selectpicker').selectpicker();
		
		
					$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
					{	
					
					
						var name = $('input.form-control.name').val();
						var count = $('input.form-control.count').val();

						if(name == null)
						{
							alert("Введите наменование");	
						}
						else
						{												
							$.ajax
							({
	
								url: "/static/receipt/", 
								type: "POST",      
								data: $("form.test").serialize(),
								cache: true,			
									beforeSend: function() 
									{
										$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
									},
									success: function(response) 
									{
										
										window.open(response, 'window name', 'window settings');
										
										 $(document).ready(function(){
											$("#myModalBox").modal('hide');
										});
										
										$('.modal-body').html('');
										
										setTimeout(function() {window.location.reload();}, 0,1);
										
									}	
							});
							e.preventDefault();	
							
						}
					});			


		e.preventDefault();			
	});
	
	
	$(document).on('click','.add_input',function(e) 
	{
		$(".client_mask").append('<form class="test"><p><input type="text" class="form-control name" name="name[]" placeholder="Описание" style=" width:300px; display:inline-block"> <input type="text" class="form-control count" name="count[]" placeholder="0" style="display:inline-block; width:50px;"> шт.   <input type="text" class="form-control price" name="price[]" placeholder="0" style="display:inline-block; width:80px; margin-left:10px"> сумма</p></form>');

		e.preventDefault();	
	});

	
	
	
</script>