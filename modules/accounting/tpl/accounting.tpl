<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>    	
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/"  class="active">Бухгалтерия</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<br clear="all">
<br clear="all">
<style type="text/css">
	table.view td:nth-child(1) { width:50%;} 
	.menu_parent_type_0 { padding-left:20px;padding-top:15px; font-size:18px;}
	.menu_parent_type_0 a{font-size:14px;}
	.menu_parent_type_1 { padding-left:20px;padding-top:15px; font-size:18px;}
	.menu_parent_type_1 a{font-size:14px;}
</style>


<div class="row">
    <div class="col-md-6">
    	<h2>Список категорий для <strong>ПРИХОДА</strong></h2>
        <div class="menu_parent_type_0">
            <? 
            $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = 0 AND parent_item_id = 0  AND type_id = 0 ORDER BY id ASC");
            
            for($y=0;$y<sizeof($res);$y++)
            {
                print_r($res[$y]->name.' ('.$res[$y]->id.') <a class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="0">добавить</a> | <a class="del_parent" href="#" data-id="'.$res[$y]->id.'">удалить</a> <br>');
                
                $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = 0  AND type_id = 0 ORDER BY id DESC");
                for($z=0;$z<sizeof($response);$z++)
                {
                     print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$response[$z]->name.' ('.$response[$z]->id.') <a  class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="'.$response[$z]->id.'">добавить</a> | <a class="del_parent" href="#" data-id="'.$response[$z]->id.'">удалить</a> <br>');
            
                    $data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = {$response[$z]->id}  AND type_id = 0 ORDER BY id DESC");
                    for($x=0;$x<sizeof($data);$x++)
                    {
                         print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data[$x]->name.'  ('.$data[$x]->id.')  <a class="del_parent"  href="#" data-id="'.$data[$x]->id.'">удалить</a> <br>');
                    }
                }
            }
            
            ?>
            <p>&nbsp;</p> 
            <a class="add_parent" href="#" data-parent="0" data-parent_item="0">Добавить новый пункт</a>
        </div>
    </div>    	
    
    <div class="col-md-6">
        <h2>Список категорий для <strong>Расхода</strong></h2>
        <div class="menu_parent_type_1">
            <? 
            $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = 0 AND parent_item_id = 0 AND type_id = 1 ORDER BY id ASC");
            
            for($y=0;$y<sizeof($res);$y++)
            {
                print_r($res[$y]->name.' ('.$res[$y]->id.') <a class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="0">добавить</a> | <a class="del_parent" href="#" data-id="'.$res[$y]->id.'">удалить</a> <br>');
                
                $response = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = 0  AND type_id = 1 ORDER BY id DESC");
                for($z=0;$z<sizeof($response);$z++)
                {
                     print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$response[$z]->name.' ('.$response[$z]->id.') <a  class="add_parent" href="#" data-parent="'.$res[$y]->id.'" data-parent_item="'.$response[$z]->id.'">добавить</a> | <a class="del_parent" href="#" data-id="'.$response[$z]->id.'">удалить</a> <br>');
            
                    $data = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE visible=1 AND parent_id = {$res[$y]->id} AND parent_item_id = {$response[$z]->id}  AND type_id = 1 ORDER BY id DESC");
                    for($x=0;$x<sizeof($data);$x++)
                    {
                         print_r('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data[$x]->name.'  ('.$data[$x]->id.')  <a class="del_parent"  href="#" data-id="'.$data[$x]->id.'">удалить</a> <br>');
                    }
                }
            }
            
            ?>
            <p>&nbsp;</p> 
            <a class="add_parent" href="#" data-parent="0" data-parent_item="0">Добавить новый пункт</a>
        </div>       
    </div>
</div>



    <div class="col-md-12">
        <h2>Список операций</h2>
	</div>
    
    <br clear="all">
    <br clear="all">
    
        <table class="users activS">
            <tr>  
                <th><strong></strong></th>
                <th><strong>Сумма</strong></th>
                <th><strong>Код операции</strong></th>
                <th><strong>Дата</strong></th>
                <th><strong>Счет</strong></th>
                <th><strong>Принято</strong></th>
                <th><strong>Комментарий</strong></th>
            </tr>
            <?
            
                $day = date('t')*1;
                $year = date('Y')*1;
                $m = date('m')*1;
              
                $type_id[0] = 'Приход'; 
                $type_id[1] = 'Расход'; 
                
                $number = cal_days_in_month(CAL_GREGORIAN, $m, $year);
                
                $query = "SELECT * FROM {$CFG->DB_Prefix}money_accounting WHERE visible = 1  AND (cdate >= '{$year}-{$m}-01 00:00:00') AND (cdate <= '{$year}-{$m}-{$number} 23:59:59') ORDER BY cdate DESC ";
                $o = getSQLArrayO($query);
                
                for ($y=0; $y<sizeof($o); $y++)
                {	
                
                    if($o[$y]->type_id == 1) $bg = ' class="yellow"'; else $bg = '';
                    ?>        
                      <tr<?=$bg;?>>
                        <td><strong><? echo $type_id[$o[$y]->type_id];?></strong></td>
                        <td><strong><?=$o[$y]->price; ?> <?=$CFG->USER->USER_CURRENCY;?></strong></td>
                        <td><strong><?=$o[$y]->time; ?> </strong></td>
                        <td><strong style="color:#468847"><? echo dateSQL2TEXT($o[$y]->cdate, "DD.MM.YY, hh:mm");?></strong></td>
                        <td><? echo $str = SelectData('money_accounting_type_id', $o[$y]->cat_id); ?></td>
                        <td><? echo $str = SelectData('users', $o[$y]->user_id); ?></td>
                        <td style="width:40%"><? echo strip_tags($o[$y]->text); ?></td>
                      </tr>
                    <?		
                }
            ?>
             
                  <tr>
                    <td><strong> Итого в кассе:         <?
          
            $day = date('t')*1;
            $year = date('Y')*1;
            $month_clear = date('m')*1;
            //AND (cdate >= '{$year}-{$month_clear}-01 00:00:00') AND (cdate <= '{$year}-{$month_clear}-{$day} 23:59:59'); 
            //AND (cdate >= '{$year}-{$month_clear}-01 00:00:00') AND (cdate <= '{$year}-{$month_clear}-{$day} 23:59:59');
            
            $z = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$CFG->USER->USER_DIRECTOR_ID}' AND type_id = 0 AND visible='1' ");
            
            $p = getSQLArrayO("SELECT SUM(price) FROM {$CFG->DB_Prefix}money_accounting WHERE director_id='{$CFG->USER->USER_DIRECTOR_ID}' AND type_id = 1 AND visible='1' ");
            
            $plus = $z[0]->{'SUM(price)'};
            $minus = $p[0]->{'SUM(price)'};
            
            echo $plus-$minus; echo ' '.$CFG->USER->USER_CURRENCY;
            
           ?>
</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
            
         </table>



   <script type="text/javascript">

        $('.menu_parent_type_0 > a.del_parent').on('click', function (e) 
		{
			var id = $(this).attr('data-id');
			
			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});
					$.ajax
					({
						url: "/static/record/", 
						type: "POST",
						data: {"id": id, "type": 'parent' },      
						cache: true,			
							beforeSend: function() 
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет удаление...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
							},
							success: function(response) 
							{
								if(response == 1)
								{									
									$(".modal-body").html('<h4 class="modal-title"><center>Меню удалено.</center></h4>');  
									
									$('.content').load(url + '/json');
									
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');

								}
								else
								{
									alert("Произошла ошибка!");	
																		
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');
								}
								
							}	
					});		



            e.preventDefault();
        });


        $('.menu_parent_type_0 > a.add_parent').on('click', function (e) 
		{
			var parent = $(this).attr('data-parent');
			var parent_item = $(this).attr('data-parent_item');

		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});
		
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name"  placeholder="Добавить новый пункт меню"></p>');

		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');


			$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
			{	
				var text = $('input.form-control.name').val();

				if(text == '')
				{
					alert("Введите текст");	
				}
				else
				{		
					$.ajax
					({
						url: "/static/add_menu_parent/", 
						type: "POST",
						data: {"text": text, "parent": parent, "parent_item": parent_item },      
						cache: true,			
							beforeSend: function() 
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет сохранение...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
							},
							success: function(response) 
							{
								if(response == 0)
								{
									alert("Произошла ошибка!");	
																		
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');
								}
								else
								{
									response = $.parseJSON(response);
									
									$(".modal-body").html('<h4 class="modal-title"><center>' + response.text + '</center></h4>');  
									
									$('.content').load(url + '/json');
									
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');
								
								}
								
							}	
					});		
				}
			});
            e.preventDefault();
        });
		
		
		
        $('.menu_parent_type_1 > a.del_parent').on('click', function (e) 
		{
			var id = $(this).attr('data-id');
			
			$(document).ready(function(){
				$("#myModalBox").modal('show');
			});
					$.ajax
					({
						url: "/static/record/", 
						type: "POST",
						data: {"id": id, "type": 'parent', "type_id": 1 },      
						cache: true,			
							beforeSend: function() 
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет удаление...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
							},
							success: function(response) 
							{
								if(response == 1)
								{									
									$(".modal-body").html('<h4 class="modal-title"><center>Меню удалено.</center></h4>');  
									
									$('.content').load(url + '/json');
									
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');

								}
								else
								{
									alert("Произошла ошибка!");	
																		
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');
								}
							}	
					});		
            e.preventDefault();
        });


        $('.menu_parent_type_1 > a.add_parent').on('click', function (e) 
		{
			var parent = $(this).attr('data-parent');
			var parent_item = $(this).attr('data-parent_item');
			
		$(document).ready(function(){
			$("#myModalBox").modal('show');
		});
		
		$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
		$(".modal-body").append('<p><input style="width:440px " type="tel" class="form-control name"  placeholder="Добавить новый пункт меню"></p>');

		$(".modal-body").append('<button type="submit" class="btn btn-primary" >Сохранить</button>');


			$('.modal-body').off('click').on('click', 'button.btn.btn-primary', function(e)
			{	
				var text = $('input.form-control.name').val();

				if(text == '')
				{
					alert("Введите текст");	
				}
				else
				{		
					$.ajax
					({
						url: "/static/add_menu_parent/", 
						type: "POST",
						data: {"text": text, "parent": parent, "parent_item": parent_item , "type_id": 1 },      
						cache: true,			
							beforeSend: function() 
							{
								$(".modal-body").html('<h4 class="modal-title"><center>Идет сохранение...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>');  						
							},
							success: function(response) 
							{
								if(response == 0)
								{
									alert("Произошла ошибка!");	
																		
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');
								}
								else
								{
									response = $.parseJSON(response);
									
									$(".modal-body").html('<h4 class="modal-title"><center>' + response.text + '</center></h4>');  
									
									$('.content').load(url + '/json');
									
									$(document).ready(function(){
										$("#myModalBox").modal('hide');
									});
									
									$('.modal-body').html('');
								
								}
								
							}	
					});		
				}
			});
			
            e.preventDefault();
        });
		
		
		

    </script>