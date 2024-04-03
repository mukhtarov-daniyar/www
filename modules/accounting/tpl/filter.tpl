<?

$MODULE_ = getFullXCodeByPageId($CFG->pid);
$e = $CFG->FORM->getFailInputs();


if($CFG->USER->USER_ID == 318)
{
	?>
		<style>
			form. col-md-4.user { display:none !important}
			.users th:nth-child(4n) { display:none}
			.users td:nth-child(4n) { display:none}

			.users th:nth-child(5n) { display:none}
			.users td:nth-child(5n) { display:none}


			.users td:nth-child(1n) { width:15% !important;}
			.users td:nth-child(2n) { width:20% !important;}
			.users td:nth-child(3n) { width:20% !important;}
			.users td:nth-child(7n) { width:55% !important; text-align:center}
			.users td .text{ width:100% !important ;}
		</style>


	<?
}
 ?>
<style>
		.response #cdatestart, .response #cdateoff { border: 0; border: solid 1px #ccc; padding: 6px; width: 220px;}
</style>


    <label class="company">
        <h3>Выберите кассу с которой будете работать:</h3>
        <select name="company" class="selectpicker company"title="Выберите компанию в которой с которой будете работать">
            <?
								$res = getSQLArrayO("SELECT * FROM my_money_accounting_data_access WHERE user_id ='{$CFG->USER->USER_ID}' AND visible = 1 ");
            		for($y=0;$y<sizeof($res);$y++)
                {
									$name = getSQLRowO(" SELECT name FROM my_money_accounting_data WHERE id = {$res[$y]->data_id} ");
									($CFG->_GET_PARAMS[1] == $res[$y]->data_id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$res[$y]->data_id;?>" <?=$sel;?>><?=$name->name;?></option>
            <? } ?>
        </select>
        <button type="submit" class="company_ok" style="display:none;">Выполнить</button>
    </label>
    <hr>

    <div class="col-md-12 filter_hide block">
		<form method="GET" enctype="multipart/form-data" class="response" action="/accounting/list_view/<?=$CFG->_GET_PARAMS[1];?>/">

            <div class="col-md-12">
             <input type="text" name="search" value="<?=$data['search']?>" placeholder="Введите текст или уникальный номер"  id="full_search_input"<?=$e['search']?>/>
            </div>


            <div class="col-md-4">
                <p>Операции:</p>
                <select name="type" id="type_data" class="selectpicker show-tick">
                <option value="0" selected><?=$CFG->Locale["fi1"]?></option>
               <?
                $type[0] = 1; 	$type[1] = 2;		 $type_name[0] = 'Приход';  $type_name[1] = 'Расход';
                for($i=0;$i<sizeof($type);$i++)
                {
                    ($data['type'] == $type[$i]) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$type[$i];?>" <?=$sel?>><?=$type_name[$i];?></option>
              <? } ?>
                </select>
            </div>

						<div class="col-md-4">
 	 						<div id="result"></div>
 	 						<img src="/tpl/img/loading.gif" id="imgLoad" alt=""  />

 	 								<? if(($data['type'] > 0)){?>
 	 								<div class="okcity">
										<p>Статья</p>
 	 										<?
 	 												$dataS = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE type_id='{$data[type]}' and user_id = '{$CFG->USER->USER_DIRECTOR_ID}' and visible=1 order by name ASC");

 	 												echo '<select name="cat" class="selectpicker show-tick" data-live-search="true">';
 	 												echo '<option value="0" selected>'.$CFG->Locale["fi2"].'</option>';
 	 												for($z=0;$z<sizeof($dataS);$z++)
 	 												{
 	 														if($data['cat'] == $dataS[$z]->id)
 	 														{
 	 																$sel = " selected";
 	 														}
 	 														else
 	 														{
 	 																$sel = "";
 	 														}
 	 												?>
 	 													<option value="<?=$dataS[$z]->id?>" <?=$sel?>><?=$dataS[$z]->name;?></option>
 	 												<? }
 	 												echo ' </select>';
 	 										?>
 	 										</div>
 	 								<? }?>
 	 				</div>


						<div class="col-md-4 user">
                <p>Менеджер:</p>
                <select name="user" class="selectpicker show-tick" data-live-search="true">
                <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
               <?
                $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND personal = 0 AND user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY name ASC");

                for($i=0;$i<sizeof($manager);$i++)
                {
                    ($data['user'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=$manager[$i]->name?></option>
              <? } ?>
                </select>
            </div>
						<div class="col-md-12">
						</div>






						<div class="col-md-4">
							 <p>Период начало:</p>
							 <input name="monthstart" value="<? if($_GET['monthstart'] !=''){ echo $_GET['monthstart']; } else { echo date('Y-m-01'); } ?>" id="cdatestart">
					 </div>

					 <div class="col-md-4">
							<p>Период конец:</p>
							<input name="monthend" value="<? if($_GET['monthend'] !=''){ echo $_GET['monthend']; } else { echo date('Y-m-01');  } ?>" id="cdateoff">
					 </div>

            <div class="col-md-4">
                <button type="submit"><span class="glyphicon glyphicon-search"></span> &nbsp; Поиск</button>
            </div>

        </form>
	</div>


<br clear="all">


<script type="text/javascript">
/* Фильтр. Выбор операции */
$("#type_data").change(function()
{
	$("#imgLoad").show();
	$(".okcity").html('');

	$.ajax
	({
		url: "/accounting/ajax/",
		type: "POST",
		data: {"country": $(this).val(), "dir": <?=$CFG->USER->USER_DIRECTOR_ID;?>},
		cache: true,
		success: function(response)
		{
			if(response == 0)
			{
				$("#result").html('');
				$("#imgLoad").hide();
			}
			else
			{
				$("#result").html(response);
				$('.selectpicker').selectpicker();
				$("#imgLoad").hide();

			}
		}
	});
});
/* Фильтр. Выбор операции. End */
</script>




<script>
	$('.year option:selected').each(function()
	{
		if($(this).val() == 0)
		{
			$("#cdate-buch").val(0);
			$("#cdate-buch-div").hide();
			$('.year.selectpicker').selectpicker('refresh');
		}
	});

	$('.year.selectpicker').change(function ()
	{
    	var selectedText = $(this).find("option:selected").val();

		if(selectedText != 0)
		{
			$("#cdate-buch").val(selectedText);
			$("#cdate-buch-div").show();
		}
		else
		{
			$("#cdate-buch").val(0);
			$("#cdate-buch-div").hide();
			$('.year.selectpicker').selectpicker('refresh');
		}

	});

</script>
