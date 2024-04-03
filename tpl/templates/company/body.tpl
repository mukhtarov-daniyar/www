<h2><? echo $CFG->oPageInfo->name; ?></h2>

<style>

#accounting h3 { padding:0; margin:0; display:block; text-align: left;  padding-bottom:10px; margin-bottom:20px; border-bottom:1px solid #BD2149 }
#accounting .col-md-6 { border-right: 1px solid #CCCCCC;margin-top:50px; }
#accounting .col-md-6:nth-child(2) { border:0} 
#accounting .col-md-6 label{ display:block; width:100%; font-family:'Helvetica_r'; font-weight:100 !important; margin-bottom:20px; font-size:18px}
#accounting .col-md-6 .selectpicker{padding:7px 10px; font-size:18px; display: inline-block; }
.bootstrap-select { width:300px !important; margin-right:5px;}
#accounting .col-md-6 input[type="text"]{ border-radius:3px; border:0; border:solid 1px #ccc; padding:7px 10px; font-size:18px; margin-right:10px; width:300px;}
#accounting .col-md-6 textarea{ border-radius:3px; border:0; border:solid 1px #ccc; padding:7px 10px; font-size:18px; margin-right:10px; width:300px; display:block; height:80px;}
#accounting .col-md-6 input[type="submit"]{padding: 9px 30px;font-family:'Helvetica_b'; border:0px;color: #FFF;background-color: #CE1053;cursor: pointer; text-transform:uppercase;font-size:14px;margin-left:171px; border-radius:3px; display:block; }
#accounting h4 { padding:0; margin:0; display:block; font-family:'Helvetica_medium';text-align: right;  margin-top:50px; margin-bottom:20px;}

</style>




<div class="row" id="accounting">
     <div class="col-md-6">
        <h3>Приход</h3>
        <form method="POST">
        
        	<input type="hidden" class="type" name="type" value="0" >
            <input type="hidden" class="author" name="author" value="<?=$CFG->USER->USER_ID;?>" >
        
            <label>
                <input type="text" class="price" name="search" value="" required pattern="^[ 0-9]+$">Сумма в <?=$CFG->USER->USER_CURRENCY;?>. 
            </label>
             
            <label>   
                <select name="type" class="selectpicker cat" data-live-search="true" title="Выберите счет">
                    <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
                    <? 
                        $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = 0 AND parent_item_id = 0 AND type_id = 0  ORDER BY id ASC");
                        
                        for($y=0;$y<sizeof($res);$y++)
                        {
                            echo '<option value="'.$res[$y]->id.'">'.$res[$y]->name.' ('.$res[$y]->id.')</option>';
                            
                                $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = 0 AND type_id = 0 ORDER BY id DESC");
                                for($z=0;$z<sizeof($response);$z++)
                                {
                                    echo '<option value="'.$response[$z]->id.'">&nbsp;&nbsp;&nbsp;'.$response[$z]->name.' ('.$response[$z]->id.')</option>';
                            
                                        $data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = {$response[$z]->id} AND type_id = 0 ORDER BY id DESC");
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
                    $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY name ASC");
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
            
            <input type="submit" id="send_data" value="Принять">                                              
        </form>
     </div>
         
     
     <div class="col-md-6">
        <h3>Расход</h3>
        <form class="form2" method="POST">
        
        	<input type="hidden" class="type_2" name="type" value="1" >
            <input type="hidden" class="author_2" name="author" value="<?=$CFG->USER->USER_ID;?>" >
        
            <label>
                <input type="text" class="price_2" name="price" value="" required pattern="^[ 0-9]+$">Сумма в <?=$CFG->USER->USER_CURRENCY;?>. 
            </label>

            <label>   
                <select name="type" class="selectpicker cat_2" data-live-search="true" title="Выберите счет">
                    <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
                    <? 
                        $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = 0 AND parent_item_id = 0 AND type_id = 1  ORDER BY id ASC");
                        
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
                <select name="type" class="selectpicker user_2" data-live-search="true">
                    <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
                   <? 
                    $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY name ASC");
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
            
            <input type="submit" id="send_data_2" value="Выдать">                                              
        </form>

     </div>
     
    <div class="col-md-12">
        <h4>Итого на счету: 
          <?
          
            $day = date('t')*1;
            $year = date('Y')*1;
            $month_clear = date('m')*1;
            //AND (cdate >= '{$year}-{$month_clear}-01 00:00:00') AND (cdate <= '{$year}-{$month_clear}-{$day} 23:59:59'); 
            //AND (cdate >= '{$year}-{$month_clear}-01 00:00:00') AND (cdate <= '{$year}-{$month_clear}-{$day} 23:59:59');
            
            $z = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$CFG->USER->USER_DIRECTOR_ID}' AND type_id = 0 AND visible='1' ");
            
            $p = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$CFG->USER->USER_DIRECTOR_ID}' AND type_id = 1 AND visible='1' ");
            
            $plus = $z[0]->{'SUM(price)'};
            $minus = $p[0]->{'SUM(price)'};
            
           echo number_format($plus-$minus, 0, ' ', ' '); echo ' '.$CFG->USER->USER_CURRENCY;
            
           ?>
        </h4>
    </div>    
     
</div>           



   <script type="text/javascript">

        $('#send_data').on('click', function (e) 
		{			
			var type = $('input.type').val();
			var price = $('input.price').val();
			var author = $('input.author').val();
			
			
			var cat = $('select.selectpicker.cat').val();
			var user = $('select.selectpicker.user').val();
			var text = $('textarea.text').val();

            	
				$.ajax
				({
					url: "/accounting/record/", 
					type: "POST",      
					data: {"type": type, "price": price, "cat": cat, "user": user , "text": text , "author": author },
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

							}		
												
							if(response.status == 0)
							{
								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');	
								
							}						
						}	
				});
				
		
			
            e.preventDefault();
        });
		
		

		
		
        $('#send_data_2').on('click', function (e) 
		{
			var type = $('input.type_2').val();
			var price = $('input.price_2').val();
			var author = $('input.author_2').val();

			var cat = $('select.selectpicker.cat_2').val();
			var user = $('select.selectpicker.user_2').val();
			var text = $('textarea.text_2').val();

				$.ajax
				({
					url: "/accounting/record_minus/", 
					type: "POST",      
					data: {"type": type, "price": price, "cat": cat, "user": user , "text": text , "author": author },
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

							}		
												
							if(response.status == 0)
							{
								$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">' + response.text + '</h3></center>');	
								
							}							
						}	
				});

            e.preventDefault();
        });
		

        
		
		
    </script>
