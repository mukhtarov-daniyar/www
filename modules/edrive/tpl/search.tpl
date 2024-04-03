<?

$MODULE_ = getFullXCodeByPageId($CFG->pid); 
$e = $CFG->FORM->getFailInputs();
$data = $CFG->FORM->getFullForm();

?>

<style>.link_search { font-family: 'segoeui_sb'; color:#2A6496}</style>

<?

$sear = $data["search"];

$z = getSQLArrayO("SELECT id, name FROM my_users WHERE (name LIKE '%{$sear}%' OR email LIKE '%{$sear}%' OR mobile LIKE '%{$sear}%') ORDER BY cdate DESC LIMIT 0, 10 ");

if(count($z) > 0)
{
	echo '<strong>Поиск по профилям:</strong><br>';
    
for($i=0; $i<sizeof($z); $i++)
{
   echo '&nbsp; &nbsp; <a target="_blank" class="link_search" href="/profile/view/'.$z[$i]->id.'">'.$z[$i]->name.'</a><br>';
}

}
?>