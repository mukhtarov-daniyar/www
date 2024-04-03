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


<div class="white">
<h2>Список лиц зарегистрированых в кешбеке</h2>

<style>
table.price {  width:100%; margin:0 auto; border-collapse: collapse; text-align:center; margin-bottom:20px; margin-top:20px;  font-family: 'segoeui'; text-transform:uppercase}
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align:middle; font-size:12px; color:#000;}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:15px; padding:0; margin:0;  padding:10px 10px; color:#fff;text-align:center;  vertical-align:middle}
</style>

<br>

 <?

            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}users WHERE visible='1' AND user_id = '{$CFG->USER->USER_ID}' ");


            for ($i=0; $i<sizeof($sql); $i++)
            {
                $pid .= $sql[$i]->id.', ';
            }


            $and_id = trim($pid, ", ");

            $sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}cashback WHERE visible=1 AND user_id IN({$and_id})  GROUP BY mobile  order by cdate DESC");

            for($y=0; $y<sizeof($sql); $y++)
            {
                $o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}news WHERE id='{$sql[$y]->page_id}'");
                ?>
                  <li style=" margin-left:30px; padding:3px; list-style:none"><a target="_blank" href="/record/<?=$o->id;?>"><?=$y+1;?>. <?=$o->name_company;?> - <?=$sql[$y]->mobile;?></a></li>
                <?
            }



 ?>
