<br>

<div class="row tab">
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Касса</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<div class="white">


<style type="text/css">
	table.view td:nth-child(1) { width:50%;}
</style>


<article class="vacancies_body row">
    <div class="table-responsive">
        <table class="view">
        <tr>
        <td><img src="<?=makePreviewName($data['logo_company'], 120, 120, 2)?>" style="border-radius:50%; width:120px;"></td>
        <td><h3><?=$data['name']?></h3></td>
        </tr>

        <tr>
        <td>Город</td>
        <td><?=SelectData('city', $data['city']);?></td>
        </tr>

        <!--tr>
        <td>Кассир компании</td>
        <td><? $city = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users  WHERE visible='1' and id='{$data['accounting']}' "); echo $city->name;?></td>
      </tr!-->

        <tr>
        <td>ГлавБух компании (для модерации сделок)</td>
        <td><? $city = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}users  WHERE visible='1' and id='{$data['accountant_chief']}' "); echo $city->name;?></td>
        </tr>

        <tr>
        <td>О компании</td>
        <td><?=$data['text'];?></td>
        </tr>

        <tr>
        <td>Фраза для шифровки</td>
        <td><?=$data['fraza'];?></td>
        </tr>

        <tr>
        <td>Название валюты для компании</td>
        <td><?=$data['currency'];?></td>
        </tr>


        <? if(($data['id'] == $CFG->USER->USER_ID)){?>
        <tr>
        <td colspan="2" style="text-align:center"> <a href="/profile/company/<?=$data['id']?>/edit/" style=" color:#0074C6"><i class="glyphicon glyphicon-edit"></i>  Редактировать</a></td>
        </tr>
        <? } ?>
        </table>
    </div>
</article>

</div>
