<h2>Запуск рассылок Whatsapp</h2>

<div class="white">       
	<div id="status_0"></div><br clear="all">
	<div id="status_1"></div><br clear="all">
	<div id="status_2"></div><br clear="all">
	<div id="status_3"></div><br clear="all">
	<div id="status_4"></div><br clear="all">
	<div id="status_5"></div><br clear="all">
	<div id="status_6"></div><br clear="all">
	<div id="status_7"></div><br clear="all">


</div>


<script>
	<?
	$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}tmp_whatsapp_namber WHERE visible='1' AND wp_id > 0 order by id ASC ");
	$vse = count($sql)-1;
	for ($i=0; $i<sizeof($sql); $i++)
	{
		?>	
			function d_<?=$i;?>() 
			{		
				$.ajax({ url: "/whatsapp/json_get_messages/<?=$sql[$i]->namber;?>", type: "GET", cache: true, async:false, success: function(response) { $("#status_<?=$i;?>").html("<?=$sql[$i]->namber;?> " + response);		}	});
				cb();	
				<? if($vse == $i) {?> setInterval(function() { location.reload();
				}, 20000); <?}?>		
			}
				
		<? $n.= 'd_'.$i.', '; 
	}
	?>
	
	var fns = [ <?=trim($n, ", ");?>];
	
	function cb()
	{
		var fn = fns.shift();
		if( typeof fn == 'function' ) fn.call();
	}
	
	setInterval(function()  
	{
		cb();
	}, 10000);
</script>