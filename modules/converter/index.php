<?php

	require_once('autoload.php');
	
	define('API_KEY', '064a8663417f5b5dd3f2409dc7837253');

	# НОВОЕ ЗАДАНИЕ
	$job = new \SwaggerClient\models\Job();

	# Параметры конверсии, что будешь конвертировать
	$conversion = new \SwaggerClient\models\Conversion();
	$conversion->category = 'audio';
	$conversion->target = 'acc';

	# Параметры задания, можно несколько конверсий туда пихнуть
	$job->conversion = array($conversion);

	# Инициализируешь свой файл
	$inputFile = new \SwaggerClient\models\InputFile();
	
	$inputFile->source = 'https://upload.wikimedia.org/wikipedia/en/0/04/Rayman_2_music_sample.ogg';

	# Задаешь заданию свой исходный файл
	$job->input = array($inputFile);

	# Вызываешт класс API
	$job_api = new \SwaggerClient\JobsApi();

	# Кидаешь апишке задание
	$created_job = $job_api->jobsPost(API_KEY, $job);

	# результат, вернет id задание, этот id потом нужен для просмотра статуса конвертирования
	//print_r($created_job);
?>

<a href="/info.php?id=<?=$created_job->id?>&token=<?=$created_job->token?>&conversion_id=<?=$created_job->conversion[0]->id?>&input_id=<?=$created_job->input[0]->id?>">Посмотреть статус выполнения</a>