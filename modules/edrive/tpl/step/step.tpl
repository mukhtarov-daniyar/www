<style>
.white h3 a { color:#000; font-size:15px; text-decoration:underline; cursor:pointer; display:block; margin:10px 0; margin-bottom:20px;}
table.price {  width:100%;margin-top:10px; border-collapse: collapse; font-size:10px; text-align: left; margin-bottom:20px;  font-family: 'segoeui'; }
table.price td {border: 1px solid #000;  padding:0; margin:0; padding:5px; vertical-align: top; font-size:12px; color:#000}
table.price th {color:#fff; border: 1px solid #000;background: #F84241; font-size:10px; padding:0; margin:0;  padding:5px 0; color:#fff;text-align: center;  vertical-align:middle; font-weight:100; font-family: 'segoeui_sb';}
.array_count { display: none}

table.price td.right { text-align: right;}
table.price th.center { text-align: center;}

table.price a { color: #fff}
table.price span.min { white-space: nowrap;display: block; font-size: 10px; }
table.price strong.record_count_hidden { display:block}
table.price span { display:block; font-size:10px; text-align:right; padding-right:5px;}

ul.country { margin:0; padding:0;}
ul.country li { margin:0; padding:0; display:inline-block; margin-right:15px;}

ul.country li a.act { background:#F84241; color:#fff; display:block; padding:5px 10px;}

.stat_list { display:block; font-family: 'segoeui'; margin-top:10px; margin-left:5px;}
.stat_list span{ display:block}
.stat_list span strong{ font-family:'segoeui_b'; }
</style>

<ul class="country">
<?
$id =  explode("-", $CFG->_GET_PARAMS[2]);

$res = getSQLArrayO("SELECT * FROM my_country_training WHERE visible='1' order by pos ASC ");
$cnt = 1;

foreach ($res as $value)
{		
		$city = getSQLRowO(" SELECT GROUP_CONCAT(id) FROM my_city WHERE page_id = {$value->id}  AND visible='1' ");
		$cg = $city->{'GROUP_CONCAT(id)'};
        
        $CFG->DB->query("SET SESSION group_concat_max_len = 600000000;");
		$count_s = getSQLRowO(" SELECT count(id), GROUP_CONCAT(id) FROM my_face  WHERE city_id IN ({$cg}) AND visible='1'  ");
        if($id[1] == $value->id) {$act = ' class="act" ';} else {$act = '';}
        
        $acts[$value->id][counts] = $count_s->{'count(id)'};
        $acts2[$value->id] = $count_s->{'count(id)'};
        $acts[$value->id][city] = $count_s->{'GROUP_CONCAT(id)'};
	?>
		<li><a href="/person/1/static/step-<?=$value->id;?>/DESC"<?=$act;?>><?=$value->name;?> (<?=$count_s->{'count(id)'};?>)</a></li>
    <?
$cnt ++;
}
?>
</ul>

<? $city = getSQLRowO(" SELECT name FROM my_country_training WHERE id = {$id[1]}  AND visible='1'  "); 

$face_s = $acts[$id[1]]['city'];

$hzs = getSQLRowO("  SELECT count(id) FROM my_face  WHERE marketing_id != ''  AND visible='1' AND id IN({$face_s }) ");
$hzs2 = getSQLRowO("  SELECT count(id) FROM my_face  WHERE marketing_id = ''  AND visible='1' AND id IN({$face_s }) ");

$itts = $hzs->{'count(id)'};
$itts2 = $hzs2->{'count(id)'};
 ?>

<div class="stat_list">
    <span>Лиц всего: <strong><?=array_sum($acts2);?></strong></span>
    <span>Лиц в <?=$city->name;?>: <strong><?=$acts[$id[1]]['counts'];?></strong></span>
    <span>Свободных лиц в <?=$city->name;?>: <strong><?=$itts;?></strong> (<strong><? echo number_sum($itts/($acts[$id[1]]['counts']/100)); ?>%</strong>)</span>
    <span>Привязанных лиц в <?=$city->name;?> <strong><?=$itts2;?></strong>: (<strong><? echo number_sum($itts2/($acts[$id[1]]['counts']/100)); ?>%</strong>)</span>
</div>

<?
$res = getSQLArrayO("SELECT * FROM my_city WHERE page_id='{$id[1]}'  AND visible='1'  order by id ASC ");
$zip = 0;
foreach ($res as $value)
{	
	$CFG->DB->query("SET SESSION group_concat_max_len = 600000000;");
	$manager = getSQLRowO("  SELECT count(id), GROUP_CONCAT(id) FROM my_face  WHERE city_id = {$value->id}  AND visible='1'  ");
    
    $face_id = $manager->{'GROUP_CONCAT(id)'};
    
    //Выводим пол М
    if($face_id == ''){$floor_m = 0; }
    else {  $floor_m = getSQLRowO("  SELECT count(id) FROM my_face  WHERE floor = 1  AND visible='1' AND id IN({$face_id}) ");  $floor_m = $floor_m->{'count(id)'};}

    //Выводим пол Ж
    if($face_id == ''){$floor_zh = 0; }
    else {  $floor_zh = getSQLRowO("  SELECT count(id) FROM my_face  WHERE floor = 2  AND visible='1' AND id IN({$face_id}) ");  $floor_zh = $floor_zh->{'count(id)'};}

    //С фото
    if($face_id == ''){$img = 0; }
    else {  $ie = getSQLRowO("  SELECT count(id) FROM my_face  WHERE img != ''  AND visible='1' AND id IN({$face_id}) ");  $img = $ie->{'count(id)'};}

    //Без фото
    if($face_id == ''){$img_no = 0; }
    else {  $i_n = getSQLRowO("  SELECT count(id) FROM my_face  WHERE img = ''  AND visible='1' AND id IN({$face_id}) ");  $img_no = $i_n->{'count(id)'};}

    //whatsapp
    if($face_id == ''){$whatsapp = 0; }
    else {  $whatsapp = getSQLRowO("  SELECT count(id) FROM my_face  WHERE whatsapp != ''  AND visible='1' AND id IN({$face_id}) ");  $whatsapp = $whatsapp->{'count(id)'};}

    //нет whatsapp
    if($face_id == ''){$whatsapp_no = 0; }
    else {  $whatsapp_no = getSQLRowO("  SELECT count(id) FROM my_face  WHERE whatsapp = ''  AND visible='1' AND id IN({$face_id}) ");  $whatsapp_no = $whatsapp_no->{'count(id)'};}

    //Email
    if($face_id == ''){$email = 0; }
    else {  $email = getSQLRowO("  SELECT count(id) FROM my_face  WHERE email != ''  AND visible='1' AND id IN({$face_id}) ");  $email = $email->{'count(id)'};}

    //нет Email
    if($face_id == ''){$whatsapp_no = 0; }
    else {  $email_no = getSQLRowO("  SELECT count(id) FROM my_face  WHERE email = ''  AND visible='1' AND id IN({$face_id}) ");  $email_no = $email_no->{'count(id)'};}
    
    //Имя
    if($face_id == ''){$name = 0; }
    else {  $name = getSQLRowO("  SELECT count(id) FROM my_face  WHERE name != ''  AND visible='1' AND id IN({$face_id}) ");  $name = $name->{'count(id)'};}

    //Имя отчество
    if($face_id == ''){$name_other = 0; }
    else {  $name_other = getSQLRowO("  SELECT count(id) FROM my_face  WHERE name_other != ''  AND visible='1' AND id IN({$face_id}) ");  $name_other = $name_other->{'count(id)'};}
  
    $data[$zip]['id'] = $value->id;
    $data[$zip]['name'] = $value->name;
    $data[$zip]['count'] = $manager->{'count(id)'};
    $data[$zip]['floor_m'] = $floor_m;
    $data[$zip]['floor_zh'] = $floor_zh;
    $data[$zip]['img'] = $img;
    $data[$zip]['img_no'] = $img_no;
    $data[$zip]['whatsapp'] = $whatsapp;
    $data[$zip]['whatsapp_no'] = $whatsapp_no;
    $data[$zip]['email'] = $email;
    $data[$zip]['email_no'] = $email_no;
    $data[$zip]['names'] = $name;
    $data[$zip]['name_other'] = $name_other;
    $zip ++;
}
$test = stable_usort($data, 'count', $CFG->_GET_PARAMS[3]);

//print_r($test);

?>

<table class="price">
	<tr>
    	<th>Название</th>
    	<th class="center"><a href="/person/1/static/<?=$CFG->_GET_PARAMS[2];?>/<? if($CFG->_GET_PARAMS[3] == 'DESC'){echo 'ASC';} if($CFG->_GET_PARAMS[3] == 'ASC'){echo 'DESC';} ?>">Кол-во</a><span class="record_count"></span></th>
        
        <th class="center">Мужчин<span class="floor_m_count"></span></th>
        <th class="center">Женщин<span class="floor_zh_count"></span></th>
        
        <th class="center">С WhatsApp<span class="whatsapp_count"></span></th>
        <th class="center">Без WhatsApp<span class="whatsapp_no_count"></span></th>
                 
        <th class="center">С фото<span class="img_count"></span></th>
        <th class="center">Без фото<span class="img_no_count"></span></th>
                 
        <th class="center">С Email<span class="email_count"></span></th>
        <th class="center">Без Email<span class="email_no_count"></span></th>
                 
        <!--th class="center">С Именем<span class="names_count"></span></th!-->
        <!--th class="center">Без Имени<span class="name_other_count"></span></th!-->
    </tr>
	<?
        foreach ($test as $value)
        {
        	if($value['count'] == 0) continue;
            
            //print_r($value['id'].',');
        	?>
                <tr>
                    <td><?=$value['name'];?></td>
                    <td class="right"><?=$value['count']; $itigo[] = $value['count']; ?></td>
                    
                    <td class="right" style="background:#F9FFB2;"><?=$value['floor_m']; $itigo_floor_m[] = $value['floor_m']; ?></td>
                    <td class="right" style="background:#F9FFB2;"><?=$value['floor_zh']; $itigo_floor_zh[] = $value['floor_zh']; ?></td>
                    
                    <td class="right" style="background:#FFD8D6;"><?=$value['whatsapp']; $itigo_whatsapp[] = $value['whatsapp']; ?></td>
                    <td class="right" style="background:#FFD8D6;"><?=$value['whatsapp_no']; $itigo_whatsapp_no[] = $value['whatsapp_no']; ?></td>
                    
                    <td class="right" style="background:#E2F1FF;"><?=$value['img']; $itigo_img[] = $value['img']; ?></td>
                    <td class="right" style="background:#E2F1FF;"><?=$value['img_no']; $itigo_img_no[] = $value['img_no']; ?></td>
                    
                    <td class="right" style="background:#F2FFF5;"><?=$value['email']; $itigo_email[] = $value['email']; ?></td>
                    <td class="right" style="background:#F2FFF5;"><?=$value['email_no']; $itigo_email_no[] = $value['email_no']; ?></td>
                    
                    <!--td class="right" style="background:#F4DDFF;"><?=$value['names']; $itigo_names[] = $value['names']; ?></td!-->
                    <!--td class="right" style="background:#F4DDFF;"><?=$value['name_other']; $itigo_name_other[] = $value['name_other']; ?></td!-->
                </tr>        	
            <?
        }
    ?>        
    <tr>
        <td class="right"><strong>Итого</strong></td>
        <td class="right"><strong class="record_count_hidden"><?=number_sum(array_sum($itigo)); ?></strong></td>
        <td class="right" style="background:#F9FFB2;"><strong class="floor_m_count_hidden"><?=number_sum(array_sum($itigo_floor_m)); ?></strong></td>
        <td class="right" style="background:#F9FFB2;"><strong class="floor_zh_count_hidden"><?=number_sum(array_sum($itigo_floor_zh)); ?></strong></td>
        <td class="right" style="background:#FFD8D6;"><strong class="whatsapp_count_hidden"><?=number_sum(array_sum($itigo_whatsapp)); ?></strong></td>
        <td class="right" style="background:#FFD8D6;"><strong class="whatsapp_no_count_hidden"><?=number_sum(array_sum($itigo_whatsapp_no)); ?></strong></td>
        <td class="right" style="background:#E2F1FF;"><strong class="img_count_hidden"><?=number_sum(array_sum($itigo_img)); ?></strong></td>
        <td class="right" style="background:#E2F1FF;"><strong class="img_no_count_hidden"><?=number_sum(array_sum($itigo_img_no)); ?></strong></td>
        <td class="right" style="background:#F2FFF5;"><strong class="email_count_hidden"><?=number_sum(array_sum($itigo_email)); ?></strong></td>
        <td class="right" style="background:#F2FFF5;"><strong class="email_no_count_hidden"><?=number_sum(array_sum($itigo_email_no)); ?></strong></td>
        <!--td class="right" style="background:#F4DDFF;"><strong class="names_count_hidden"><?=number_sum(array_sum($itigo_names)); ?></strong></td!-->
        <!--td class="right" style="background:#F4DDFF;"><strong class="name_other_count_hidden"><?=number_sum(array_sum($itigo_name_other)); ?></strong></td!-->
    </tr>        	
</table>


<script type="text/javascript">

	$('.record_count').html($('.record_count_hidden').html());
	$('.floor_m_count').html($('.floor_m_count_hidden').html());
	$('.floor_zh_count').html($('.floor_zh_count_hidden').html());
	$('.whatsapp_count').html($('.whatsapp_count_hidden').html());
	$('.whatsapp_no_count').html($('.whatsapp_no_count_hidden').html());
	$('.img_count').html($('.img_count_hidden').html());
	$('.img_no_count').html($('.img_no_count_hidden').html());
	$('.email_count').html($('.email_count_hidden').html());
	$('.email_no_count').html($('.email_no_count_hidden').html());
	$('.names_count').html($('.names_count_hidden').html());
	$('.name_other_count').html($('.name_other_count_hidden').html());


</script>