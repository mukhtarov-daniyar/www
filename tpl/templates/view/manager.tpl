<?
	$sql = "SELECT * FROM {$CFG->DB_Prefix}news WHERE view=0";
	$response = getSQLArrayo($sql);
?>

<li><a href="/">Главная</a></li>
<li><a href="/record/">Записи</a></li>
<li><a href="/record/">Добавить</a></li>