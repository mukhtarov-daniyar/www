<? 
	$sphere = getSQLRowA("SELECT * FROM {$CFG->DB_Prefix}sphere  WHERE id='{$o->sphere}' and sys_language='{$CFG->SYS_LANG}'");
    $city = getSQLRowA("SELECT * FROM {$CFG->DB_Prefix}city WHERE id='{$o->city}' and sys_language='{$CFG->SYS_LANG}'");
    $country_training = getSQLRowA("SELECT * FROM {$CFG->DB_Prefix}country_training  WHERE id='{$o->country_training}' and sys_language='{$CFG->SYS_LANG}' ORDER BY name ASC");
     
?>

<tr>
    <td><a href="/<?=$CFG->SYS_LANG_NAME;?>/profile/view/<?=$o->id?>/" target="_blank"><?=$o->name?></a></td>
    <td><?=$o->lastname?></td>
    <td><?=$o->mobile?></td>
    <td><?=$o->login?></td>
    <td><?=$city["name"]?></td>
    <td><?=$sphere["name"]?></td>
    <td><?=$country_training["name"]?></td>
    <td><?=$o->on_training?>-<?=$o->off_training?></td>
    <td><? $sex = array(0=>'Нет', 1=>'Да'); echo $sex[$o->scholar];?></td>
    <td>
       <? if ($o->resume) {?>
            <a href="/<?=$data['resume'];?>" class="btn" target="_blank"><?=$CFG->Locale["ps57"];?></a>
       <?} else {?>
            Нет
       <? }?>
    </td>
</tr>


