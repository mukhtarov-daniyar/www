<br>
<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Бухгалтерия</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>


<div class="white datas">

<div class="row">
  <form method="GET" enctype="multipart/form-data" class="response">

    <div class="col-md-4">
      <p>Местонахождение</p>
      <select name="country_training" id="country" class="selectpicker show-tick" data-live-search="true" title="Выберите страну">
        <option value="0" selected>Любая страна</option>
        <option value="449">Азербайджан</option>
        <option value="9">Беларусь</option>
        <option value="2">Казахстан</option>
        <option value="446">Кыргызстан</option>
        <option value="29">Россия</option>
        <option value="447">Узбекистан</option>
        <option value="448">Украина</option>
      </select>
      <div id="result"></div>
      <img src="/tpl/img/loading.gif" id="imgLoad" alt=""  />
    </div>

    <div class="col-md-4">
      <p>Компания:</p>
      <select name="company" class="selectpicker show-tick" id="manager">
        <option value="0" selected>Любая</option>
        <option value="85"selected>forSign.kz</option>
        <option value="90">LED Sign</option>
        <option value="92">LEDElement</option>
        <option value="601">Armaland</option>
        <option value="153">Сэпком</option>
        <option value="318">Led Impress Urumqi</option>
        <option value="579">Diodic</option>
      </select>
    </div>
    <div class="col-md-4">
      <p>&nbsp;</p>
      <button type="submit">Поиск</button>
    </div>

  </form>
</div>

<br clear="all">

<?
//print_r($_GET);

if($_GET['company'] > 0)
{

  $str .= " AND user_id = {$_GET[company]}";
}


//echo $str;
?>


    <article class="vacancies_body row">
        <ul class="list-group">
<?
	$datas = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}type_company WHERE visible=1 AND page_id = 868 ORDER BY pos,id ASC");
	for ($i=0; $i<sizeof($datas); $i++)
    {
    	?> <li class="list-group-item"><strong><?=$datas[$i]->name;?></strong> </li><?
    } ?>
        </ul>
    </article>
</div>
