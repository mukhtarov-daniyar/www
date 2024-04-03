<h2><?=$CFG->oPageInfo->html_title;?></h2>
<?
	$o = SelectDataRowOArray('mailer_list_id', $CFG->USER->USER_ID); 
	
	
	
	$array = unserialize($o->array_id);
	
	for ($z=0; $z<sizeof($array); $z++)
	{
		$respons = SelectDataRowOArray('news', $array[$z]);
				
		$email[] 					.= $respons->email;
		$other_email[]				.= $respons->other_email;
		$name_client_email[] 		.= $respons->name_client_email;
		$name_director_email[] 		.= $respons->name_director_email;

		$name_director_mobile[] 		.= $respons->name_director_mobile;
		$name_client_mobile[] 			.= $respons->name_client_mobile;

	}

	echo "<br><br><strong>Почтовые ящики</strong>:<br>";
	for ($p=0; $p<sizeof($email); $p++)
		{	
			if($email[$p] == !"")
			{
				echo $email[$p].', ';
			}
		}


	echo "<br><br><strong>Дополнительные почтовые ящики</strong>:<br>";
	for ($p=0; $p<sizeof($other_email); $p++)
		{	
			if($other_email[$p] == !"")
			{
				echo $other_email[$p].', ';
			}
		}

	echo "<br><br><strong>Почтовые ящики контактирующех лиц</strong>:<br>";
	for ($p=0; $p<sizeof($name_client_email); $p++)
		{	
			if($name_client_email[$p] == !"")
			{
				echo $name_client_email[$p].', ';
			}
		}

	echo "<br><br><strong>Почтовые ящики директоров/учредителей</strong>:<br>";
	for ($p=0; $p<sizeof($name_director_email); $p++)
		{	
			if($name_director_email[$p] == !"")
			{
				echo $name_director_email[$p].', ';
			}
		}
		
		
		
	echo "<br><br><strong>Номера директора/учредителя</strong>:<br>";
	for ($p=0; $p<sizeof($name_director_mobile); $p++)
		{	
			if($name_director_mobile[$p] == !"")
			{
				echo $name_director_mobile[$p].', ';
			}
		}

	echo "<br><br><strong>Номера контактирующих</strong>:<br>";
	for ($p=0; $p<sizeof($name_client_mobile); $p++)
		{	
			if($name_client_mobile[$p] == !"")
			{
				echo $name_client_mobile[$p].', ';
			}
		}
