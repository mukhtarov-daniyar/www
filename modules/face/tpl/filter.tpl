<?

$MODULE_ = getFullXCodeByPageId($CFG->pid);
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();



?>



    <div class="col-md-12 filter_hide block">
		<br clear="all">
		<form method="GET" enctype="multipart/form-data" class="response" action="/person/">

            <div class="col-md-12">
             <input type="text" name="search" value="<?=$data['search']?>" placeholder="Поиск по физ. лицам"  id="full_search_input"<?=$e['search']?>/>
            </div>

 			<?  include("./modules/news/tpl/manager/boss.tpl");	 ?>


            <div class="col-md-4">
                <p>Маркетинг план:</p>
                <select name="marketing[]" class="selectpicker show-tick" id="type" multiple="multiple" data-live-search="true">
                <option value="0">Любой</option>
               <?

                $type_company = AndDataArray('type_company_portrait', 'page_id', 868, 1, $order = 'pos ASC');

                for($i=0;$i<sizeof($type_company);$i++)
                {
                   foreach ($data['marketing'] as $arr) {  if($arr == $type_company[$i]->id) { $sel = "selected"; }
                } ?>
                    <option value="<?=$type_company[$i]->id?>" <?=$sel?>><?=$type_company[$i]->name?></option>
              <? $sel = "";} ?>
                </select>

            </div>

            <div class="col-md-4">
                <p>Пол:</p>
                <select name="floor" class="selectpicker show-tick">
                    <option value="0">Любой</option>
                    <?
                        $floor[0]->id = 1; $floor[0]->name = 'Мужской';
                        $floor[1]->id = 2; $floor[1]->name = 'Женский';

                        for($i=0;$i<sizeof($floor);$i++)
                        {
                            if($data['floor'] == $floor[$i]->id) { $sel = 'selected';} else {$sel = '';}
                        ?>
                            <option value="<?=$floor[$i]->id;?>" <?=$sel?>><?=$floor[$i]->name?></option>
                      <? } ?>
                </select>
            </div>

             <div class="col-md-4">
                <p>Местонахождение (<a href="/person/1/static/step-2/DESC" style="font-size:12px; text-decoration: none; color:#F84241;font-family: 'segoeui_sb';">статистика</a>)</p>
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
                <li><a href="/cookie_face/?id=1/">Дата создания</a></li>
                <li><a href="/cookie_face/?id=2/">Дата изменения</a></li>
              </ul>
           </div>
        </div>

    </div>

<br clear="all">
