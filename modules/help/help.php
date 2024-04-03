<h2><img alt="" src="/tpl/img/new/icon/1_red.png">Меморандум</h2>

<div class="white">
	<?
		$respon = getSQLRowO(" SELECT  big_text FROM my_company WHERE id = '{$CFG->USER->USER_DIRECTOR_ID}' ");
		echo $respon->big_text;
	?>
</div>
