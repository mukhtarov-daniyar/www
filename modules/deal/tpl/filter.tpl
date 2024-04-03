<?

$MODULE_ = getFullXCodeByPageId($CFG->pid);
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();



?>




    <div class="col-md-12 filter_hide block">
		<br clear="all">
		<form method="GET" enctype="multipart/form-data" class="response" action="/deal/">

            <div class="col-md-12">
             <input type="text" name="search" value="<?=$data['search']?>" placeholder="Поиск по сделка"  id="full_search_input"<?=$e['search']?>/>
            </div>

            <div class="col-md-4">
                <p>Тип сделок:</p>
                <select name="deal_parent" class="selectpicker show-tick" id="type">
                	<option value="0">Все</option>
                	<option value="868"<? if($_GET["deal_parent"] == 868){ echo ' selected';}?>>Клиентские</option>
                	<option value="976"<? if($_GET["deal_parent"] == 976){ echo ' selected';}?>>Служебные</option>
                </select>
            </div>


            <div class="col-md-4">
                <p>Автор</p>
                <select name="users" class="selectpicker show-tick" data-live-search="true">
                <option value="0" selected>Любой</option>
               <?
                $manager = getSQLArrayO("SELECT id, name, taks_id FROM {$CFG->DB_Prefix}users WHERE visible=1  AND user_id = {$CFG->USER->USER_DIRECTOR_ID} ORDER BY name ASC");
                for($i=0;$i<sizeof($manager);$i++)
                {
                    ($data['users'] == $manager[$i]->id) ? $sel = "selected" : $sel = ""; ?>
                    <option value="<?=$manager[$i]->id?>"<?=$sel?>><?=$manager[$i]->name;?></option>
              <? } ?>
                </select>
            </div>


            <div class="col-md-4">
                <p>Сумма сделок:</p>
                <select name="price" class="selectpicker show-tick" id="type">
                	<option value="0">Любая</option>
                	<option value="1"<? if($_GET["price"] == 1){ echo ' selected';}?>>От 0 до 200 000 тг</option>
                	<option value="2"<? if($_GET["price"] == 2){ echo ' selected';}?>>От 200 000 тг до 1 000 000 тг</option>
                	<option value="3"<? if($_GET["price"] == 3){ echo ' selected';}?>>От 1 000 000 тг</option>
                </select>
            </div>


            <div class="col-md-4">
                <p>Статус:</p>
                <select name="visible" class="selectpicker show-tick" id="type">
                	<option value="0">Любая</option>
                	<option value="1"<? if($_GET["visible"] == 1){ echo ' selected';}?>>В работе</option>
                	<option value="2"<? if($_GET["visible"] == 2){ echo ' selected';}?>>Закрытая</option>
                </select>
            </div>



            <div class="col-md-4" id="resultS"></div>

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
                <li><a href="/cookie/?id=1/">Дата создания</a></li>
                <li><a href="/cookie/?id=2/">Дата изменения</a></li>
              </ul>
           </div>
            <div style="float:right; line-height:35px;"> Сортировать по:</div>
        </div>

    </div>

<br clear="all">
