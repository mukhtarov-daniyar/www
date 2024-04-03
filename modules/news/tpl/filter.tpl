<?
  $data = $CFG->FORM->getFullForm();
?>



<br>

    <div class="row tab">
        <div class="col-md-3">
            <h1 class="active"><a href="/record/">Компании</a></h1>
        </div>

        <div class="col-md-3">
            <h1><a href="/office/">Служебные записи</a></h1>
        </div>

        <? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 244 || $CFG->USER->USER_ID == 311 || $CFG->USER->USER_ID == 310 || $CFG->USER->USER_ID == 536) { ?>
          <div class="col-md-4">
              <h1><a href="/alimzhanov-history/">Мой дневник</a></h1>
          </div>
        <? } ?>

    </div>

    <div class="col-md-12 filter_hide block"><br>
		<form method="GET" enctype="multipart/form-data" class="response" action="/record/">
        <input type="hidden" name="company" value="<? echo $CFG->USER->USER_DIRECTOR_ID; ?>">

            <div class="col-md-4">
              <p>Введите слово</p>
              <input type="text" name="search" value="<?=$data['search']?>" id="full_search_input" <?=$e['search']?>/>
            </div>

            <div class="col-md-4">
                <p>Отрасль клиента:</p>
                <select name="type_company[]" class="selectpicker show-tick" multiple="multiple"  id="type" data-live-search="true">
                <option value="0">Любая</option>
               <?
                $type_company = AndDataArray('type_company', 'page_id', $CFG->oPageInfo->id, 1, $order = 'pos, id ASC');
                for($i=0;$i<sizeof($type_company);$i++)
                {

                	foreach ($data['type_company'] as $arr)
                    {
                    	if($arr == $type_company[$i]->id) { $sel = ' selected="selected"'; }
                    }
                 ?>
                    <option value="<?=$type_company[$i]->id?>"<?=$sel?>><?=$type_company[$i]->name;?></option>
              <?  $sel = ""; } ?>
                </select>
            </div>

            <!--adiv class="col-md-4">
                <p>Статус:</p>
                <select name="intensive" class="selectpicker show-tick">
                <option value="0"><?=$CFG->Locale["fi1"]?></option>
               <?
               $intensive[0] = "Горячий (послед. изм. 30 дн.)";
               $intensive[1] = "Теплый (послед. изм. 30-60 дн.)";
               $intensive[2] = "Холодный (послед. изм. 60-90 дн.)";
               $intensive[3] = "Ледянной (послед. изм. 90-120 дн.)";
               $intensive[4] = "Замороженный (послед. изм. больше 120 дн.)";

               for($i=0;$i<sizeof($intensive);$i++)
                {
                    ($data['intensive'] == $i+1) ? $sel = "selected" : $sel = ""; ?>
                    <option class="intensive_<?=$i+1;?>" value="<?=$i+1;?>" <?=$sel?>><?=$intensive[$i];?></option>
              <? } ?>
                </select>
            </div!-->

            <div class="col-md-4">
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

 			<?  include("./modules/news/tpl/manager/boss.tpl"); ?>



           <div class="col-md-4">
            	<button type="submit">Поиск</button>
            </div>
        </form>
	</div>


    <div class="col-md-12"  style="background:#fff;">
        <div class="col-md-4">
        </div>

        <div class="col-md-3">
        </div>

        <div class="col-md-5" style="padding:0">
            <? if($_COOKIE["ordername"] == NULL) $_COOKIE["ordername"] = 'По дате создания';  ?>

           <div class="btn-group" style="float:right;">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="font-family:'segoeui'; font-size:14px;"><?=$_COOKIE["ordername"];?> <span class="caret" style="color:#F8403E;"></span></button>
              <ul class="dropdown-menu" style="float:left !important">
                <li><a href="/cookie/?id=1">По дате создания</a></li>
                <li><a href="/cookie/?id=2">По дате изменения</a></li>
              </ul>
           </div>

        </div>

    </div>

<div style="clear:both"></div>
