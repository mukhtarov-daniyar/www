<?php

	require_once('autoload.php');

	define('API_KEY', '064a8663417f5b5dd3f2409dc7837253');

	$job_api = new \SwaggerClient\JobsApi();
	$info = $job_api->jobsJobIdGet($_GET['token'], API_KEY, $_GET['id']);

	switch($info->status->code)
	{
		case 'completed':
			$output_api = new \SwaggerClient\OutputApi();
			$output = $output_api->jobsJobIdOutputGet($_GET['conversion_id'], $_GET['input_id'], $_GET['token'], API_KEY, $_GET['id']);
	
			echo 'Файл успешно конвертунлся: <a href="' . $output[0]->uri . '">Скачать</a>';
		break;
		
		case 'queued':
			echo 'В очереди (обнови страницу)';
		break;
	
		case 'downloading':
			echo 'Скачивается на сервер конвертации (обнови страницу)';
		break;
		
		case 'pending':
			echo 'Подготовка к конвертации (обнови страницу)';
		break;
	
		case 'processing':
			echo 'В процессе (обнови страницу)';
		break;
	
		case 'failed':
			echo 'Произошла ошибка: ' . $info->status->info;
		break;		
	}