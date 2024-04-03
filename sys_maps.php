<?php


$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-Language: en-US,en;q=0.9,ru;q=0.8\r\n".
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.50 Safari/537.36 OPR/65.0.3467.16 (Edition beta)\r\n"
  )
);

$context = stream_context_create($options);
$String = file_get_contents('http://api.electrify.az/api/charger/', false, $context);



var_dump($String);



print_r( 123) ; exit;
