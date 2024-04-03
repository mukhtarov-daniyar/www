<?

if($_GET["id"])
{	
	$sql = getSQLArrayO("SELECT * FROM wp_calculator WHERE type='{$_GET[id]}' order by id DESC");
	if(count($sql) > 0)
	{
		echo '<br clear="all"><select name="module" class="selectpicker show-tick" id="select_module">';
		for ($i=0; $i<sizeof($sql); $i++)
		{
			echo '<option value="'.$sql[$i]->id.'">'.$sql[$i]->text.'</option>';		
		}			
		echo '</select>';
	}
	else echo 0;
	exit;
}




/* $array["product"] = array(	'name' => '>P 3,33 mm rgb (160*320) - Внутр.',
							'price' => 20.6,
							'power' => 4,
							'resive' => 6,
							'motherboard' => 1);
*/

?>

<style>
.calculator p { padding:0; margin:0; font-family: 'segoeui_sb';}
.calculator .response_modul { margin-left:15px; margin-top:15px;  font-family: 'segoeui_sb';}
.calculator .col-md-12 { display:block; margin-top:15px;}
.calculator input[type="number"] { padding:5px;}
form.calculator .parse { display:none} 
</style>


<div class="content">	
	<h2>Расчет стоимости самосборного экрана</h2>
	<div class="white" style="padding-left:30px;">
    
    	<div class="calculator">
 		<form method="GET" class="calculator">       
            <div class="col-md-12">
                <select name="module" class="selectpicker show-tick" id="module">
                	<option value="0">Выберите тип модуля:</option>
                    <option value="1">Indoor</option>
                    <option value="2">Outdoor</option>
                </select> 
                
                <div class="type_module"> </div>         
            </div> 
        
        	<div class="parse">
                <div class="col-md-12">
                    <p>Кол-во модулей в длину:</p>
                     <input type="number" class="length" value="0">           
                </div> 
                
                <div class="col-md-12">
                    <p>Кол-во модулей в ширину:</p>
                     <input type="number" class="width" value="0">           
                </div> 
                
                <input type="submit" value="Расчитать" class="btn  btn-danger" style="margin-left:15px; margin-top:15px;">
                
                <div class="response_modul"></div>
			</div>            
        </form>
          
        </div>
    	<br clear="all">
    
    <br clear="all">
    <br clear="all">
    <br clear="all">  
    
	</div>
</div>

<script>

$("#module").change(function()
{
	$('.type_module').html('');
	$("form.calculator .parse").fadeOut();
	
	var id = $(this).val();
	
	if(id > 0)
	{
	
		$.ajax
		({
			url: "/calculator/", 
			type: "GET",      
			data: {"id": id},
			cache: true,			
			beforeSend: function() 
			{
				$("#myModalBox").modal('show');
	
				$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');	
			},
			success: function(response) 
			{
				if(response == 0)
				{
					$(".modal-body").html('<h4 class="modal-title"><center>К сожалению таких модулей нет.</center></h4>');	
				}
				else
				{
					setTimeout(function() 
					{
						$('.type_module').html(response);
						$('.selectpicker').selectpicker();
						$("#myModalBox").modal('hide');
						$("form.calculator .parse").fadeIn();	
					}, 1000);
					
				}
			}
		
		});	
	}
});


$('input.btn.btn-danger').on('click', function(e)
{
	$('.response_modul').html('')
	
	var select_module = $('#select_module').val();
	var select_length = $('input.length').val();
	var select_width = $('input.width').val();

		$.ajax
		({
			url: "/static/calculator/", 
			type: "POST",      
			data: {"select_module": select_module, "select_length": select_length, "select_width": select_width},
			cache: true,			
			beforeSend: function() 
			{
				//$("#myModalBox").modal('show');
	
				//$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');	
			},
			success: function(response) 
			{
				$('.response_modul').html(response);
			}
		
		});	
	
	e.preventDefault();
	
});
	

</script>
