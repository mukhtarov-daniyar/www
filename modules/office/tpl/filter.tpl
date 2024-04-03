<?

$MODULE_ = getFullXCodeByPageId($CFG->pid);
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();



?>

<? if($data['type_company'][0] != '10011980'){?>
<style>.city_div { display: none;} </style>
<? } ?>

<br>

    <div class="row tab">
        <div class="col-md-3">
            <h1><a href="/record/">Клиентские</a></h1>
        </div>
        <div class="col-md-3">
            <h1 class="active"><a href="/office/">Служебные</a></h1>
        </div>

        <? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 244 || $CFG->USER->USER_ID == 311 || $CFG->USER->USER_ID == 310) { ?>
        <div class="col-md-4">
            <h1><a href="/alimzhanov-history/">Личный дневник</a></h1>
        </div>
        <? } ?>

    </div>



    <div class="col-md-12 filter_hide block">
		<br clear="all">
		<form method="GET" enctype="multipart/form-data" class="response" action="/office/">

            <div class="col-md-12">
             <input type="text" name="search" value="<?=$data['search']?>" placeholder="Поиск по служебным"  id="full_search_input"<?=$e['search']?>/>
            </div>

 			<?  include("./modules/news/tpl/manager/manager.tpl");	 ?>


            <div class="col-md-4">
                <p>Тип записи:</p>
                <select name="type_company[]" class="selectpicker show-tick" id="type">
                <option value="0">Любая</option>
               <?
                $type_company = AndDataArray('type_company', 'page_id', $CFG->oPageInfo->id, 1, $order = 'pos ASC');

                for($i=0;$i<sizeof($type_company);$i++)
                {
                    ($data['type_company'][0] == $type_company[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$type_company[$i]->id?>" <?=$sel?>><?=$type_company[$i]->name?></option>
              <? } ?>
                </select>

            </div>

            <div class="col-md-4 city_div">
                <p>Выберите страну</p>
                <select name="country_training" id="country" class="selectpicker show-tick" >
                <option value="0" selected>Любая</option>
               <?
                $country_training = SelectDataArray('country_training', 1, 'name ASC');
                for($i=0;$i<sizeof($country_training);$i++)
                {
                    ($data['country_training'] == $country_training[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$country_training[$i]->id?>"<?=$sel?>><?=$country_training[$i]->name;?></option>
              <? } ?>
                </select>

                <div id="result"></div>
                <img src="/tpl/img/loading.gif" id="imgLoad" alt=""  />

                <? if(($data['country_training'] > 0)){?>
                <div class="okcity">
                	<?
                    	$dataS = SelectDataParent('city','page_id',$data['country_training']);
                        echo ' <select name="city" class="selectpicker show-tick" data-live-search="true" >';
                        echo ' <option value="0" selected>Любой</option>';
                        for($z=0;$z<sizeof($dataS);$z++)
                        {
                        	$key = array_search($dataS[$z]->id, $data['city']);

                            $hz = $data['city'];

                        	if($hz == $dataS[$z]->id)
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


    <div class="col-md-12" style="background:#fff">
        <div class="col-md-4">
        </div>

        <div class="col-md-4">
        </div>

        <div class="col-md-4">
            <? if($_COOKIE["ordername"] == "") $_COOKIE["ordername"] = "Дата создания"; ?>

           <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown"><?=$_COOKIE["ordername"];?> <span class="caret"></span></button>
              <ul class="dropdown-menu" style="float:left !important">
                <li><a href="/cookie/?id=1">Дата создания</a></li>
                <li><a href="/cookie/?id=2">Дата изменения</a></li>
              </ul>
           </div>
        </div>

    </div>

<br clear="all">


<script>
$('.response select#type').change(function()
{
    var selected = $(this).val();
    if (selected == "10011980")
    {
      $(".response .city_div").show();
      $('.selectpicker').selectpicker();
    }
    else
    {
      $(".city_div").hide();
      $("#result").html('');

      $('#country').val('0');
      $(".city_div .dropdown-menu li").removeClass('selected');
      $(".city_div .dropdown-menu li").removeClass('active');

      $(".city_div .dropdown-menu li:first").addClass('active');
      $(".city_div .dropdown-menu li:first").addClass('selected');
      $(".city_div .btn-group.bootstrap-select button .pull-left").html('Любой');
      $('.selectpicker.city').selectpicker();

      $('#country').selectpicker();
    }
});
</script>
