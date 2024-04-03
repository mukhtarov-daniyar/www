<?
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>



<h2><img alt="" src="/tpl/img/new/icon/6_red.png"> <? echo $CFG->oPageInfo->name; ?></h2>


<div class="white">

<style>
	#file_upload { float:left; margin-left:130px; top:-3px; position:relative;cursor:pointer; }
	#file_upload_2 { width: 20px;height: 22px;font-size: 24px;background: url(/tpl/img/file.png) no-repeat center #D4D4D4;margin-top: 6px !important; padding: 17px !important; display: inline-block; border-radius: 3px; transition: all 0.2s;float: left;margin-right: 5px !important;cursor:pointer; margin-left:130px; top:-3px; position:relative}
	
	#file_upload { width: 20px;height: 22px;font-size: 24px;background: url(/tpl/img/file.png) no-repeat center #D4D4D4;margin-top: 6px !important; padding: 17px !important; display: inline-block; border-radius: 3px; transition: all 0.2s;float: left;margin-right: 5px !important;cursor:pointer; margin-left:130px; top:-3px; position:relative}
	
	
	#accounting h3 { padding:0; margin:0; display:block; text-align: left;  padding-bottom:10px; margin-bottom:20px; border-bottom:1px solid #BD2149 }
	#accounting .col-md-6 { border-right: 1px solid #CCCCCC;margin-top:20px; }
	#accounting .col-md-6:nth-child(2) { border:0} 
	#accounting .col-md-6 label{ display:block; width:100%; font-family:'Helvetica_r'; font-weight:100 !important; margin-bottom:20px; font-size:16px}
	#accounting .col-md-6 .selectpicker{padding:7px 10px; font-size:16px; display: inline-block; }
	.bootstrap-select { width:300px !important; margin-right:5px;}
	#accounting .col-md-6 input[type="text"]{ border-radius:3px; border:0; border:solid 1px #ccc; padding:7px 10px; font-size:18px; margin-right:10px; width:300px;}
	#accounting .col-md-6 textarea{ border-radius:3px; border:0; border:solid 1px #ccc; padding:7px 10px; font-size:18px; margin-right:10px; width:300px; display:block; height:80px;}
	#accounting .col-md-6 input[type="submit"]{padding: 9px 30px;font-family:'Helvetica_b'; border:0px;color: #FFF;background-color: #F84241;cursor: pointer; text-transform:uppercase;font-size:14px;margin-left:171px; border-radius:3px; display:block; }
	#accounting h4 { padding:0; margin:0; display:block; font-family:'Helvetica_medium';text-align: right;  margin-top:50px; margin-bottom:20px;}
</style>


<? if($CFG->USER->USER_ID == 565)
{ ?>
<label class="company">   
    <h3>Выберите компанию в которой с которой будете работать:</h3>
    <select name="company" class="selectpicker company"title="Выберите компанию в которой с которой будете работать">
        <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
        <? 
            $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}company WHERE visible=1 AND accounting = '{$CFG->USER->USER_ID}' ORDER BY id ASC");
            for($y=0;$y<sizeof($res);$y++)
            {
            	($_COOKIE["company"] == $res[$y]->id) ? $sel = "selected" : $sel = ""; ?>     
                <option value="<?=$res[$y]->id;?>" <?=$sel;?>><?=$res[$y]->name;?></option>'; 
                <? 
            }
        ?>
    </select>
    <button type="submit" class="company_ok">Выполнить</button>
</label>                           
<hr>
<? } ?>

<?
    if($_COOKIE["company"] > 0)
        $director = $_COOKIE["company"];
    else
        $director = $CFG->USER->USER_DIRECTOR_ID;
?>


<div class="row" id="accounting">
     <div class="col-md-6">

        <h3>Приход</h3>
        <form method="POST">
        
        	<input type="hidden" class="type" name="type" value="1" >
            <input type="hidden" class="author" name="author" value="<?=$CFG->USER->USER_ID;?>" >
            
            <input type="hidden" class="attachment" name="attachment" value="" >
        
            <label>
                <input type="text" class="price" name="search" value="" required pattern="^[ 0-9]+$">Сумма 
            </label>
             
            <label>   
                <select name="type" class="selectpicker cat" data-live-search="true" title="Выберите счет">
                    <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
                    <? 
                        $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND user_id = '{$director}' AND parent_id = 0 AND parent_item_id = 0 AND type_id = 1  ORDER BY id ASC");
                        
                        for($y=0;$y<sizeof($res);$y++)
                        {
                            echo '<option value="'.$res[$y]->id.'">'.$res[$y]->name.' ('.$res[$y]->id.')</option>';
                            
                                $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = 0 AND type_id = 1 ORDER BY id DESC");
                                for($z=0;$z<sizeof($response);$z++)
                                {
                                    echo '<option value="'.$response[$z]->id.'">&nbsp;&nbsp;&nbsp;'.$response[$z]->name.' ('.$response[$z]->id.')</option>';
                            
                                        $data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = {$response[$z]->id} AND type_id = 1 ORDER BY id DESC");
                                        for($x=0;$x<sizeof($data);$x++)
                                        {
                                            echo '<option value="'.$data[$x]->id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data[$x]->name.' ('.$data[$x]->id.')</option>';
                                        }
                                }
                            
                        }
                    ?>
                </select>
                Счет 
            </label>                           
                                      
            <label>   
                <select name="type" class="selectpicker user" data-live-search="true">
                    <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
                   <? 
                    $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND user_id = {$director} ORDER BY name ASC");
                    for($i=0;$i<sizeof($manager); $i++)
                    {
                    	 ?>
                        <option value="<?=$manager[$i]->id?>"><?=$manager[$i]->name?></option>
                  <? } ?>
                 </select>
                Принято от 
            </label>                           
                       
            <label>
                <textarea class="text" placeholder="Комментарий" name="text"></textarea>
            </label> 
            
            <label>
                <div id="file_upload" title="Можно прикрепить аттач"></div>
            </label> 
            
            <input type="submit" id="send_data" value="Принять">
      
            <br clear="all">
            <div class="input-form__attachments_files"></div>
                                            
        </form>
        
     </div>
         
     
     
     
     
     <div class="col-md-6">
        <h3>Расход</h3>
        <form class="form2" method="POST">
        
        	<input type="hidden" class="type_2" name="type" value="2" >
            <input type="hidden" class="author_2" name="author" value="<?=$CFG->USER->USER_ID;?>" >
            
            <input type="hidden" class="attachment_2" name="attachment_2" value="" >
        
            <label>
                <input type="text" class="price_2" name="price" value="" required pattern="^[ 0-9]+$">Сумма в
            </label>

            <label>   
                <select name="type" class="selectpicker cat_2" data-live-search="true" title="Выберите счет">
                    <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
                    <? 
                        $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND user_id = '{$director}' AND parent_id = 0 AND parent_item_id = 0 AND type_id = 2  ORDER BY id ASC");
                        
                        for($y=0;$y<sizeof($res);$y++)
                        {
                            echo '<option value="'.$res[$y]->id.'">'.$res[$y]->name.' ('.$res[$y]->id.')</option>';
                            
                                $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = 0 AND type_id = 2 ORDER BY id DESC");
                                for($z=0;$z<sizeof($response);$z++)
                                {
                                    echo '<option value="'.$response[$z]->id.'">&nbsp;&nbsp;&nbsp;'.$response[$z]->name.' ('.$response[$z]->id.')</option>';
                            
                                        $data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = {$response[$z]->id} AND type_id = 2 ORDER BY id DESC");
                                        for($x=0;$x<sizeof($data);$x++)
                                        {
                                            echo '<option value="'.$data[$x]->id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data[$x]->name.' ('.$data[$x]->id.')</option>';
                                        }
                                }
                        }
                    ?>
                 </select>
                Счет 
            </label>                           
                                      
            <label>   
                <select name="type" class="selectpicker user_2" data-live-search="true">
                    <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
                   <? 
                    $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND user_id = {$director} ORDER BY name ASC");
                    for($i=0;$i<sizeof($manager); $i++)
                    {
                    	 ?>
                        <option value="<?=$manager[$i]->id?>"><?=$manager[$i]->name?></option>
                  <? } ?>
                 </select>
                Выдано 
            </label>                           
                       
            <label>
                <textarea class="text_2" placeholder="Комментарий" name="text"></textarea>
            </label> 
            
            <label>
                <div id="file_upload_2" title="Можно прикрепить аттач"></div>
            </label>   
                        
            <input type="submit" id="send_data_2" value="Выдать"> 
            
             <br clear="all">
            <div class="input-form__attachments_files_2"></div>                     
                                                        
        </form>

     </div>
     
    <div class="col-md-12">
        <h4>Итого на счету: <? echo $ACCOUNT->sum($director);?></h4>
    </div>    
     
</div>           


   <script type="text/javascript">
   
		$(document).on('click','#file_upload',function(e) 
		{
			var btn = $('#file_upload');
			
			 new AjaxUpload(btn, 
			  {
			   data: {'pid_id' : '00000001'},
			   name: 'file',
			   action: '/static/file_upload/',
	
			   onSubmit: function() 
			   {
					$(document).ready(function(){
						$("#myModalBox").modal('show');
					});
					
					$(".modal-body").html('<h4 class="modal-title"><center>Идет загрузка файла, подождите пожалуйста...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 
					
				},
				onComplete: function(file, response) 
				{				
					$(".modal-body").html('');
					$("#myModalBox").modal('hide');
									
					try
					{
						response = $.parseJSON(response);
					}
					catch(exception)
					{
						return false;					
					}
	
					$('input[name=attachment]').val('');
					$('input[name=attachment]').val(jQuery('input[name=attachment]').val() + response.url);
					$('.input-form__attachments_files').html('<strong>Вы прикрепили файл!</strong>');						
			   }
			   
			 }); 
			e.preventDefault();
		});
	
        $('#send_data').on('click', function (e) 
		{			
			var type = $('input.type').val();
			var price = $('input.price').val();
			var author = $('input.author').val();
			var attachment = $('input.attachment').val();
			
			var cat = $('select.selectpicker.cat').val();
			var user = $('select.selectpicker.user').val();
			var text = $('textarea.text').val();

            	if(price > 0)
				{
					$.ajax
					({
						url: "/accounting/record/", 
						type: "POST",      
						data: {"type": type, "price": price, "cat": cat, "user": user , "text": text , "author": author , "attachment": attachment },
						cache: true,			
							beforeSend: function() 
							{
								$(document).ready(function(){
									$("#myModalBox").modal('show');
								});			
												
								$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
							},
							success: function(response) 
							{
								response = $.parseJSON(response);
						
								if(response.status == 1)
								{
									$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">' + response.text + '</h3></center>');
									$('.content').load(url + '/json');
									$('.selectpicker').selectpicker();
								}		
													
								if(response.status == 0)
								{
									$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');	
									
								}
								
								$('.selectpicker').selectpicker();						
							}	
					});
				
				}
		
			
            e.preventDefault();
        });
		
		

		
		
		
		
		
		
		
		
		
		
		$(document).on('click','#file_upload_2',function(e) 
		{
			var btn_2 = $('#file_upload_2');
			
			 new AjaxUpload(btn_2, 
			  {
			   data: {'pid_id' : '00000001'},
			   name: 'file',
			   action: '/static/file_upload/',
	
			   onSubmit: function() 
			   {
					$(document).ready(function(){
						$("#myModalBox").modal('show');
					});
					
					$(".modal-body").html('<h4 class="modal-title"><center>Идет загрузка файла, подождите пожалуйста...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 
					
				},
				onComplete: function(file, response) 
				{				
					$(".modal-body").html('');
					$("#myModalBox").modal('hide');
									
					try
					{
						response = $.parseJSON(response);
					}
					catch(exception)
					{
						return false;					
					}
	
					$('input[name=attachment_2]').val('');
					$('input[name=attachment_2]').val(jQuery('input[name=attachment_2]').val() + response.url);
					$('.input-form__attachments_files_2').html('<strong>Вы прикрепили файл!</strong>');						
			   }
			   
			 }); 
			e.preventDefault();
		});
		
        $('#send_data_2').on('click', function (e) 
		{

			var type = $('input.type_2').val();
			var price = $('input.price_2').val();
			var author = $('input.author_2').val();
			var attachment = $('input.attachment_2').val();

			var cat = $('select.selectpicker.cat_2').val();
			var user = $('select.selectpicker.user_2').val();
			var text = $('textarea.text_2').val();

				if(price > 0)
				{
					$.ajax
					({
						url: "/accounting/record_minus/", 
						type: "POST",      
						data: {"type": type, "price": price, "cat": cat, "user": user , "text": text , "author": author , "attachment": attachment },
						cache: true,			
							beforeSend: function() 
							{
								$(document).ready(function(){
									$("#myModalBox").modal('show');
								});			
												
								$(".modal-body").html('<h4 class="modal-title"><center>Подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
							},
							success: function(response) 
							{					
								response = $.parseJSON(response);
						
								if(response.status == 1)
								{
									$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">' + response.text + '</h3></center>');
									$('.content').load(url + '/json');
									$('.selectpicker').selectpicker();
	
								}		
													
								if(response.status == 0)
								{
									$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');	
									
								}	
								$('.selectpicker').selectpicker();						
							}	
					});
				}
				
            e.preventDefault();
        });
    </script>

</div>