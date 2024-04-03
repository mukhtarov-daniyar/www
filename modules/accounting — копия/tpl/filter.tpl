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

if($CFG->USER->USER_ID == 565 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 547 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 608)
{ ?>
    <label class="company">
        <h3>Выберите компанию в которой с которой будете работать:</h3>
        <select name="company" class="selectpicker company"title="Выберите компанию в которой с которой будете работать">
            <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
            <?
            	if($CFG->USER->USER_ID == 85)
                {
                    $res[0]->id = 85; $res[0]->name = 'forSign Kazakhstan';
                    $res[1]->id = 318; $res[1]->name = 'Led Impress Urumqi';
               	}

              	if($CFG->USER->USER_ID == 547 || $CFG->USER->USER_ID == 565  || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 608  )
                {
                	$res[0]->id = 318; $res[0]->name = 'Led Impress Urumqi';
                	$res[1]->id = 85; $res[1]->name = 'forSign Kazakhstan';
               	}

                for($y=0;$y<sizeof($res);$y++)
                {
                    ($_COOKIE["company"] == $res[$y]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$res[$y]->id;?>" <?=$sel;?>><?=$res[$y]->name;?></option>
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

    <div class="col-md-12 filter_hide block">
		<form method="GET" enctype="multipart/form-data" class="response" action="/accounting/list_view/">

            <div class="col-md-12">
             <input type="text" name="search" value="<?=$data['search']?>" placeholder="Введите текст или уникальный номер"  id="full_search_input"<?=$e['search']?>/>
            </div>

            <div class="col-md-4">
                <p>Операции:</p>
                <select name="type" id="type_data" class="selectpicker show-tick" id="type">
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

            <div class="col-md-4 user">
                <p>Менеджер:</p>
                <select name="user" class="selectpicker show-tick" data-live-search="true">
                <option value="0" selected><?=$CFG->Locale["fi2"]?></option>
               <?
                $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1 AND personal = 0 AND user_id = {$director} ORDER BY name ASC");

                for($i=0;$i<sizeof($manager);$i++)
                {
                    ($data['user'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=$manager[$i]->name?></option>
              <? } ?>
                </select>
            </div>


            <div class="col-md-4">
                <p>Год:</p>
                <select name="year" class="year selectpicker show-tick">
               <?
                $year[0] = '0';
                $name[0] = 'За все время';

								$year[1] = '2020';
                $name[1] = '2020';

								$year[2] = '2019';
                $name[2] = '2019';

                $year[3] = '2018';
                $name[3] = '2018';

                $year[4] = '2017';
                $name[4] = '2017';

                $year[5] = '2016';
                $name[5] = '2016';

                for ($i = 0; $i <= 4; $i++)
                {
                    ($data['year'] == $year[$i]) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$year[$i];?>" <?=$sel?>><?=$name[$i];?></option>
              <? } ?>
                </select>
			</div>



            <div class="col-md-4">
                <div id="result"></div>
                <img src="/tpl/img/loading.gif" id="imgLoad" alt=""  />

                    <? if(($data['type'] > 0)){?>
                    <div class="okcity">
                        <?


                            $dataS = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE type_id='{$data[type]}' and user_id = '{$director}' and visible=1 order by name ASC");

                            echo '<select name="cat" class="selectpicker show-tick">';
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

            <div class="col-md-4"></div>

            <div class="col-md-4" id="cdate-buch-div">
                <p>Месяц:</p>
                <select name="cdate" class="selectpicker show-tick" id="cdate-buch">
               <?
                $month[0] = 'Любой';	$month[1] = 'Январь';	$month[2] = 'Февраль';	$month[3] = 'Март';	$month[4] = 'Апрель';	$month[5] = 'Май';	$month[6] = 'Июнь'; 	$month[7] = 'Июль';	$month[8] = 'Август';	$month[9] = 'Сентябрь';	$month[10] = 'Октябрь';	$month[11] = 'Ноябрь';	$month[12] = 'Декабрь';
                for ($i = 0; $i <= 12; $i++)
                {
                	//|| date('m')*1 == $i
                    ($data['cdate'] == $i ) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$i;?>" <?=$sel?>><?=$month[$i];?></option>
              <? } ?>
                </select>
			</div>



			<br clear="all">

            <div class="col-md-4">
            </div>

            <div class="col-md-4" style="text-align:center">
                <button type="submit"><span class="glyphicon glyphicon-search"></span> &nbsp; Поиск</button>
            </div>

            <div class="col-md-4">
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
		data: {"country": $(this).val(), "dir": <?=$director;?>},
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
