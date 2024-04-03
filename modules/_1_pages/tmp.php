<?

$urlpage = "/admin/main.php?mod=_1_pages&part=main?mod=_1_pages&part=tmp";

$names = array(
             "project" => "Твой мониторнинг",
             "vote" => "Твой голос",
			 "comments" => "Вид комментариев"
              );

$tmp_l = array(
             "comments" => "formComment.tpl",
			 "project" => "formMain.tpl,formShow.tpl",
			 "vote" => "formMain.tpl,formShow.tpl",
			 "form" => "formReg.tpl,formSov.tpl,formIdea.tpl,formVote.tpl,formTest.tpl"
              );

$modules = array(
               "comments" => "../modules/monitor/class/tpl/",
               "project" => "../modules/project/tpl/",
			   "vote" => "../modules/vote/tpl/",
			   "form" => "../modules/form/tpl/"
              );

function page_process() {
	
	global $_POST, $urlpage, $modules, $tmp_l;

	if($_POST['content'] && $_POST['template']) {
		
		$path = $modules[$_POST['module']].$_POST['template']; 
		
		$tpl_f = fopen($path, 'w+');
		         fwrite($tpl_f, $_POST['content']);
				 fclose($tpl_f);
		
	}
	
}
	


function page_show() {
	
	global $urlpage, $modules, $tmp_l, $names;
	
	echo $_SESSION['path'];
	
	if(!$_GET['tpl'])
	
	   foreach($modules as $name => $path) {
		
		   echo '<a class="menu" href="'.$urlpage.'&tpl='.$name.'">'.$name.'</a>';
		
	   }
	
	else {
		
		echo '<h1>'.$names[$_GET['tpl']].'</h1>';
		
		if(!$_GET['html']) {
			
		   $tpl = explode(',', $tmp_l[$_GET['tpl']]);	
		
		   for($i=0;$i<sizeof($tpl);$i++) {
			  
			  $tpl_url = $modules[$_GET['tpl']].$tpl[$i]; 
		
		      echo '<a class="menu" href="'.$urlpage.'&tpl='.$_GET['tpl'].'&html='.$tpl[$i].'">'.$tpl[$i].'</a>';
		   
		   }
		   
		} else {
			
			  $path = $modules[$_GET['tpl']].$_GET['html']; 
			
		      $content = file_get_contents($path);
		
		      echo '<h1>'.$_GET['html'].'</h1>';
			  echo '<form method="POST" action="'.$urlpage.'">';
			  echo '<input type="hidden" name="module" value="'.$_GET['tpl'].'" />';
			  echo '<input type="hidden" name="template" value="'.$_GET['html'].'" />';
			  echo '<textarea name="content">'.hs($content).'</textarea>';
			  echo '<input type="submit" value="Сохранить" />';
			  echo '</form>';
	
		}
		
	}
	
}

?>