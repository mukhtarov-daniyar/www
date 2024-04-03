<?

$MODULE_ = getFullXCodeByPageId($CFG->pid);
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();


?>


    <div class="col-md-12 filter_hide block">
		<br clear="all">

		<form method="GET" enctype="multipart/form-data" class="response" action="/edrive/">


            <div class="col-md-4">
                <p>Авто:</p>
                <select name="car" class="selectpicker show-tick" id="car" data-live-search="true">
                <option value="0">Любой</option>
               <?
                $car =  getSQLArrayO("SELECT * FROM my_edrive_car_model order by id ASC");
                for($i=0;$i<sizeof($car);$i++)
                {
                 if($data['car'] == $car[$i]->id) { $sel = "selected"; }
                  ?>
                    <option value="<?=$car[$i]->id?>" <?=$sel?>><?=$car[$i]->name?></option>
              <? $sel = "";} ?>
                </select>
            </div>


            <div class="col-md-4">
                <p>Скоростной порт:</p>
                <select name="port[]" class="selectpicker show-tick" id="type" multiple="multiple" data-live-search="true">
                <option value="0">Любой</option>
               <?
                $port =  getSQLArrayO("SELECT * FROM my_edrive_car_port order by id ASC");
                for($i=0;$i<sizeof($port);$i++)
                {
                   foreach ($data['port'] as $arr) {  if($arr == $port[$i]->id) { $sel = "selected"; }
                } ?>
                    <option value="<?=$port[$i]->id?>" <?=$sel?>><?=$port[$i]->name?></option>
              <? $sel = "";} ?>
                </select>
            </div>


             <div class="col-md-4">
                <p>Местонахождение</p>
                <select name="country_training" id="country" class="selectpicker show-tick" data-live-search="true" title="Выберите страну">
                <option value="0" selected>Любое</option>
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

            <div class="col-md-4">
                <button type="submit"><span class="glyphicon glyphicon-search"></span> &nbsp; Поиск</button>
            </div>

            <div class="col-md-4">
            </div>


        </form>
	</div>


<br clear="all">


<script>

</script>
