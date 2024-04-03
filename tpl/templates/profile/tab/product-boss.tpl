<? $id = $data['id'];


	$resp = getSQLArrayO("SELECT my_data_1c.price , my_data_1c.nameIM AS name,  my_data_1c.optionIM AS optionS, my_data_1c_nomenclature_ruk.* FROM my_data_1c_nomenclature_ruk LEFT JOIN my_data_1c ON	my_data_1c.id_product = my_data_1c_nomenclature_ruk.id_product WHERE my_data_1c_nomenclature_ruk.user_id = {$id}  ");

if($CFG->USER->USER_ID == $data['id'] || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 536) {
?>


<br clear="all">
	<table class="users" >
		<tr>
			<th style="width:50%"><strong>Товар</strong></th>
			<th style="width:15%"><strong>Процент по УУ</strong></th>
			<th style="width:15%"><strong>Процент по БУ</strong></th>
			<th style="width:20%"><strong>Стоймость (розн)</strong></th>
		</tr>

		<?
			foreach($resp as $datas)
					{
						?>
						<tr>
							<td><?=$datas->name;?> <?=$datas->optionS;?></td>
							<td><?=$datas->uu;?>%</td>
							<td><?=$datas->bu;?>%</td>
							<td><?=number_sum($datas->price);?> <?=$CFG->USER->USER_CURRENCY;?></td>
						</tr>
						<?
					}
		?>

	</table>
	<br clear="all">
	<br clear="all">

<? } ?>
