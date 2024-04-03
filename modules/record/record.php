<? 
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/upload.js"></script>');
	$CFG->SITE->addToHeaderHTML('<script type="text/javascript" src="/tpl/js/jquery.fileupload.js"></script>');
?>
<h2>Загрузить список E-mail</h2>
<div class="white">


<style>
table.price {  width:100%; margin:0 auto; border-collapse: collapse; font-size:16px; text-align:center; margin-bottom:20px;  font-family: 'segoeui'; text-transform:uppercase}
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align:middle; font-size:14px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:15px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle}
#upAvatar { font-size:18px; display:block; margin-top:10px; margin-bottom:0px;     font-family: 'segoeui_sb';}
#upAvatar img{ width:20px; padding-right:5px; }
span.intro { color:#ABA9A9;font-family: 'segoeui'; font-size:11px; text-transform:uppercase; display:block; padding-left:23px; margin-bottom:15px;}

.modal-body .bootstrap-select {width:190px !important;font-size:14px !important; margin-right:30px !important }
.modal-body button[type=button].btn.btn-primary { float:none !important; margin-top:0 !important; padding:4px 10px !important; width:100px !important}
</style>


<a href="#" id="upAvatar"><img src="/tpl/img/new/attach.png">Загрузить TXT файл</a>
<span class="intro">Список E-mail в файле должен быть через запятую</span>
 
  
  <table class="price">
    <tr>
      <th>Файл</th>
      <th>Кол. E-mail</th>
      <th>Занесено</th>
      <th>Статус</th>
      <th>Дата загрузки</th>
    </tr>
	<?
		//$cdata = sqlDateNow();
        $res = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_file WHERE user_id='{$CFG->USER->USER_ID}' AND visible = 1 order by id DESC");
        for($r=0; $r<sizeof($res); $r++) 
        { 
            ?>
            <tr>
              <td><a href="/<?=$res[$r]->file_url;?>"><?=$res[$r]->file_name;?></a></td>
              <td><?=$res[$r]->count_email;?></td>
              <td><?=$res[$r]->count_email_ok;?></td>
              <td><? if($res[$r]->status == 1){echo 'Проверено';}else {echo 'В процессе';}?></td>
              <td><? if($res[$r]->cdate != NULL) {echo dateSQL2TEXT($res[$r]->cdate, "DD.MM.YY hh:mm"); }?></td>
            </tr>
            
            <?
        }
    ?>
  </table>

</div>


<script type="text/javascript">

if( document.getElementById('upAvatar') )
{

    var btn = $('#upAvatar');

     new AjaxUpload(btn, 
      {
       data: {'user_act' : 'upload_txt'},
       name: 'txt',
       action: '/static/up_txt/',
       autoSubmit: true,
		   onSubmit: function() 
		   {
				$(document).ready(function(){
					$("#myModalBox").modal('show');
				});
				
				$(".modal-body").html('<h4 class="modal-title"><center>Идет загрузка файла, подождите пожалуйста...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 
				
			},
			
			
			onComplete: function(file, response) 
			{
				
				$(".modal-body").html('<div class="modal-header"><h4 class="modal-title"><center>Файл загружен!<br> Выберите критерий и месторасположение.<br><br></center></h4></div>');
				
				$(".modal-body").append('<select  class="selectpicker show-tick manager list-view-manager" data-live-search="true"  multiple="multiple"  title="Тип компании"><option value="0">Любой</option><? $users = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}type_company WHERE page_id='868' order by pos ASC");for ($i=0; $i<sizeof($users); $i++){ ?> <option value="<?=$users[$i]->id;?>"><?=$users[$i]->name;?></option> <? } ?></select>');
				
				$(".modal-body").append('<select class="selectpicker show-tick manager list-city" data-live-search="true"  title="Месторасположения"><option value="0">Любой город</option><? $users = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}city WHERE visible='1' order by name ASC");for ($i=0; $i<sizeof($users); $i++){ ?> <option value="<?=$users[$i]->id;?>"><?=$users[$i]->name;?></option> <? } ?></select>');

				$(".modal-body").append('<button type="button" class="btn btn-primary">Сохранить</button><br clear="all">');

				$('.selectpicker').selectpicker();
		
				isotope();

				$('.btn.btn-primary').live('click', function(e)
				{	
					var type = $('.list-view-manager').val();
					var city = $('.list-city').val();
					var id = response;
	
					$.ajax
					({
						url: "/static/up_txt_type/", 
						type: "POST",      
						data: {"id": id, "type": type, "city": city},
						cache: true,			
							beforeSend: function() 
							{					
								$(".modal-body").html('<h4 class="modal-title"><center>Пожалуйста, подождите...</center></h4><br clear="all"><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%;"></div></div>'); 
							},
							success: function(response) 
							{
								if(response == 1)
								{
									$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> <center><h3 class="modal-title">Все данные сохранены!<br> Добавление новых записей будет происходить автоматически. <br>По завершению процесса вы будете извещены.</h3></center>');
									$('.content').load(url + '/json');
	
								}							
								if(response == 0)
								{
									$(".modal-body").html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><center><h3 class="modal-title">Произошла ошибка.</h3></center>');	
									$('.content').load(url + '/json');
									
								}
								
							}
					});
					
					e.preventDefault();
				
				});			

		   }
		   
		 }); 
}

</script>   