<?	if($this->MESSAGE != '')
{
	for($i=0; $i<sizeof($this->MESSAGE); $i++) 
		$alert = $this->MESSAGE[$i].'\n';
	
				
?>

	<script type="text/javascript">
		
		alert('<?=$alert?>');
	
	</script>

<? }?>