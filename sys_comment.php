<?
	require_once('_properties.php');

	$data = stripslashesarray($_POST);

	switch($data['user_act']) 
	{
		case 'attachment_to_comment' :
				
			$file = $_FILES['comment-file'];
		
			switch($CFG->USER->checkExtFile($file))
			{
				case 'image' :
				
					try
					{			
						$big = $CFG->USER->cropUserAvatar($_FILES['comment-file'], 'default');
						$med = $CFG->USER->cropUserAvatar($_FILES['comment-file'], 'defaultAvatar');

						$json['type'] = 'image';
						$json['name'] = $file['name'];
						$json['url'] = '/' . $big;	
						$json['url_mini'] = '/' . $med;

					}
					catch (IllegalArgumentException $ex)
					{
					}
					catch (FileNotFoundException $ex)
					{				
					}
				
				break;
				
				case 'other' :
				
					$FILE = new Files();
					$FILE->setPath('documents/news/');
					$FILE->addFile($file);
					
					
					$json['type'] = 'other';
					$json['name'] = $file['name'];
					$json['url'] = $FILE->getUrlFile('/');
				
				break;
			}
			
			if($json ==! "")
			{
				echo json_encode($json);
			}
			else 
			{
				echo json_encode(0);
			}
			
			
		break;
	}
	
?>