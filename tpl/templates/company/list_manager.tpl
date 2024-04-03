<br>

<div class="row tab">
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/">Моя компания</a></h1>
    </div>
    <div class="col-md-3">
        <h1 class="active"><a href="/profile/company/<?=$CFG->USER->USER_ID?>/manager/">Самоначисления</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/money/">Касса</a></h1>
    </div>
    <div class="col-md-3">
        <h1><a href="/profile/company/<?=$CFG->USER->USER_ID?>/other/">Дополнительно</a></h1>
    </div>
</div>

<style>
	.maimoney table { width:100%;}
	.maimoney table th{ font-size:13px; font-family:'segoeui_sb'; text-align:center; font-weight:100; width:auto;padding-top:3px; padding-bottom:3px; }
	.maimoney table td{ font-size:11px; font-family:'segoeui'; text-align:center; font-weight:100; width:auto;padding-top:3px; padding-bottom:3px; }
	.maimoney table td span {display:block;color: #F84241;}
	.maimoney table td a{display:block;}
	.maimoney table tr{ border-bottom:1px #ccc solid; }

</style>

<div class="white">

    <label class="company">
        <h3 style="display:inline-block; margin-right:10px; position:relative; top:3px;">Выберите год:</h3>
        <select name="year" class="selectpicker year"title="Выберите год">
            <?

            $currentYear = date("Y");

            for ($years = 2016; $years <= $currentYear; $years++)
            {
                ($_COOKIE["year"] == $years ) ? $sel = "selected" : $sel = "";
              ?>
                <option value="<?=$years;?>" <?=$sel;?>><?=$years;?></option>
              <?
            }


            ?>
        </select>
        <button type="submit" class="year_ok">Выполнить</button>
    </label>

    <?
        if($_COOKIE["year"] > 0)
            $year = $_COOKIE["year"];
        else
            $year = date('Y')*1;  ;
    ?>

	<div class="maimoney">
    <table>
      <tr>
        <th>Менеджер</th>
        <th>Январь</th>
        <th>Февраль</th>
        <th>Март</th>
        <th>Апрель</th>
        <th>Май</th>
        <th>Июнь</th>
        <th>Июль</th>
        <th>Август</th>
        <th>Сентябрь</th>
        <th>Октябрь</th>
        <th>Ноябрь</th>
        <th>Декабрь</th>
      </tr>
     <?
      $l = getSQLArrayO("SELECT id, name, vdate FROM {$CFG->DB_Prefix}users WHERE visible=1  AND user_id = {$CFG->USER->USER_DIRECTOR_ID}  ORDER BY name ASC");

        for ($i=0; $i<sizeof($l); $i++)
            { $o = $l[$i];

            	//$ndate = date('Y-m-d H:i:s', time() - (30 * 24 * 60 * 60));

				          //if ($ndate > $o->vdate ) continue;
            ?>
      <tr>
        <td><a href="/profile/view/<?=$o->id;?>/#money" target="_blank"><?=$o->name;?></a></td>
        <td><? echo SelectDataRowOMonth($year, 1,  $o->id); $array_1[] = SelectDataRowOMonthSumPlus($year, 1,  $o->id); ?> <? $array_minus_1[] = SelectDataRowOMonthSumMinus($year, 1,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 2,  $o->id); $array_2[] = SelectDataRowOMonthSumPlus($year, 2,  $o->id); ?> <? $array_minus_2[] = SelectDataRowOMonthSumMinus($year, 2,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 3,  $o->id); $array_3[] = SelectDataRowOMonthSumPlus($year, 3,  $o->id); ?> <? $array_minus_3[] = SelectDataRowOMonthSumMinus($year, 3,  $o->id);?></td>
        <td><? echo SelectDataRowOMonth($year, 4,  $o->id); $array_4[] = SelectDataRowOMonthSumPlus($year, 4,  $o->id); ?> <? $array_minus_4[] = SelectDataRowOMonthSumMinus($year, 4,  $o->id);?></td>
        <td><? echo SelectDataRowOMonth($year, 5,  $o->id); $array_5[] = SelectDataRowOMonthSumPlus($year, 5,  $o->id); ?> <? $array_minus_5[] = SelectDataRowOMonthSumMinus($year, 5,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 6,  $o->id); $array_6[] = SelectDataRowOMonthSumPlus($year, 6,  $o->id); ?> <? $array_minus_6[] = SelectDataRowOMonthSumMinus($year, 6,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 7,  $o->id); $array_7[] = SelectDataRowOMonthSumPlus($year, 7,  $o->id); ?> <? $array_minus_7[] = SelectDataRowOMonthSumMinus($year, 7,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 8,  $o->id); $array_8[] = SelectDataRowOMonthSumPlus($year, 8,  $o->id); ?> <? $array_minus_8[] = SelectDataRowOMonthSumMinus($year, 8,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 9,  $o->id); $array_9[] = SelectDataRowOMonthSumPlus($year, 9,  $o->id); ?> <? $array_minus_9[] = SelectDataRowOMonthSumMinus($year, 9,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 10,  $o->id); $array_10[] = SelectDataRowOMonthSumPlus($year, 10,  $o->id); ?> <? $array_minus_10[] = SelectDataRowOMonthSumMinus($year, 10,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 11,  $o->id); $array_11[] = SelectDataRowOMonthSumPlus($year, 11,  $o->id); ?> <? $array_minus_11[] = SelectDataRowOMonthSumMinus($year, 11,  $o->id); ?></td>
        <td><? echo SelectDataRowOMonth($year, 12,  $o->id); $array_12[] = SelectDataRowOMonthSumPlus($year, 12,  $o->id); ?> <? $array_minus_12[] = SelectDataRowOMonthSumMinus($year, 12,  $o->id); ?></td>
      </tr>
	<? } ?>
      <tr style="font-weight:bold;">
        <td>Итого:</td>
        <td><? echo number_sum(array_sum($array_1)); ?> <span>-<? echo number_sum(array_sum($array_minus_1)); ?></span></td>
        <td><? echo number_sum(array_sum($array_2)); ?> <span>-<? echo number_sum(array_sum($array_minus_2)); ?></td>
        <td><? echo number_sum(array_sum($array_3)); ?> <span>-<? echo number_sum(array_sum($array_minus_3)); ?></td>
        <td><? echo number_sum(array_sum($array_4)); ?> <span>-<? echo number_sum(array_sum($array_minus_4)); ?></td>
        <td><? echo number_sum(array_sum($array_5)); ?> <span>-<? echo number_sum(array_sum($array_minus_5)); ?></td>
        <td><? echo number_sum(array_sum($array_6)); ?> <span>-<? echo number_sum(array_sum($array_minus_6)); ?></td>
        <td><? echo number_sum(array_sum($array_7)); ?> <span>-<? echo number_sum(array_sum($array_minus_7)); ?></td>
        <td><? echo number_sum(array_sum($array_8)); ?> <span>-<? echo number_sum(array_sum($array_minus_8)); ?></td>
        <td><? echo number_sum(array_sum($array_9)); ?> <span>-<? echo number_sum(array_sum($array_minus_9)); ?></td>
        <td><? echo number_sum(array_sum($array_10)); ?> <span>-<? echo number_sum(array_sum($array_minus_10)); ?></td>
        <td><? echo number_sum(array_sum($array_11)); ?> <span>-<? echo number_sum(array_sum($array_minus_11)); ?></td>
        <td><? echo number_sum(array_sum($array_12)); ?> <span>-<? echo number_sum(array_sum($array_minus_12)); ?></td>
      </tr>

    </table>

</div>
</div>
