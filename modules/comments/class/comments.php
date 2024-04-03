<?

	interface onEventComment
	{
		public function onPostComment();
		public function onRateComment();
	}

	class Comments implements onEventComment
	{
		private $id;
		private $pageid;

		private $comment;
		private $maxrate = 1;

		private $moderate = 1;

		private $type = 0;
		private $lang;
		private $user;
		private $text;
		private $replycomment;

		private $addCommentTPL = "tpl/templates/comments/addComment.tpl";
		private $commentFormTPL = "tpl/templates/comments/formComment.tpl";
		private $commentFormAJAX = "tpl/templates/comments/formCommentAJAX.tpl";
		private $commentButtonTPL = "tpl/templates/comments/formButton.tpl";
		private $getFilter = '';
		private $putFilter = '';

		private $commercial;
		private $attachment;
		private $attachment_edd;
		private $attachment_music;

		private $reminder;

		private $premiumminus;
		private $premium;

		private $inform;
		private $access;

		private $task;
		private $typetaks;
		private $offtask;
		private $doer;
		private $observer;


		private $cashback;
		private $level = 0;

		protected $response;

		function __construct($pid)
		{
			global $CFG;

			$this->pageid = $pid;
		}

		function setPostId($id)
		{
			$this->id = $id;
		}

		function setCashback($int)
		{
			$this->cashback = $int;
		}

		function setLang($int)
		{
			$this->lang = $int;
		}

		function setUser($user)
		{
			$this->user = $user;
		}

		function setComment($int)
		{
			$this->comment = $int;
		}

		function setType($int)
		{
			$this->doer = $int;
		}

		function addCommercial($url)
		{
			$this->commercial = $url;
		}

		function addAccessrecord($url)
		{
			$this->accessrecord = $url;
		}

		function addAttachment($url)
		{
			$this->attachment = $url;
		}

		function eddAttachment($url)
		{
			$this->attachment_edd = $url;
		}

		function musAttachment($url)
		{
			$this->attachment_music = $url;
		}

		function Reminder($array)
		{
			$this->reminder = $array;
		}

		function PremiumMinus($array)
		{
			$this->premiumminus = $array;
		}

		function PremiumPlus($array)
		{
			$this->premium = $array;
		}

		function Big_3($int)
		{
			$this->big_3 = $int;
		}

		function Big_10($int)
		{
			$this->big_10 = $int;
		}

		function Important($str)
		{
			$this->important = $str;
		}


		function Access($array)
		{
			$this->access = $array;
		}

		function Inform($array)
		{
			$this->inform = $array;
		}

		function Task($int)
		{
			$this->task = $int;
		}

		function TypeTaks($value)
		{
			$this->typetaks = $value;
		}

		function OffTask($value)
		{
			$this->offtask = $value;
		}

		function Observer($value)
		{
			for ($x=0; $x<sizeof($value); $x++)
			{
				if($value[$x] > 0)
				{
					$id .= $value[$x].',';
				}
			}

			$idS = trim($id, ",");

			if($idS == !"")
				$CatIdAnd = $idS;

			$str = $CatIdAnd.",";

			$this->observer = trim($str,',');
		}

		function setText($txt)
		{
			$this->text = $txt;
		}


		function setReplyComment($int)
		{
			$this->replycomment = $int;
		}

		/* for int another filter */
		function setGetFilter($str)
		{
			$this->getFilter = $str;
		}

		function setPutFilter($str)
		{
			$this->putFilter = $str;
		}

		function setTemplate($src)
		{
			$this->commentFormTPL = $src;
		}

		function setTemplateForm($src)
		{
			$this->addCommentTPL = $src;
		}

		function setTemplateButtom($src)
		{
			$this->commentButtonTPL = $src;
		}

		function setMaxCommentRate($int)
		{
			$this->maxrate = $int;
		}

		function isModerate($bool)
		{
			if($bool)
			{
				$this->moderate = 0;
			}
			else
			{
				$this->moderate = 1;
			}
		}

		public function onRateComment()
		{
			global $CFG;

			$data = stripslashesarray($CFG->_POST_PARAMS);

			$ERATE = new Comments($this->pageid);
			$ERATE->setPostId($this->postid);
			$ERATE->setLang($CFG->SYS_LANG);
			$ERATE->setComment($data['comment_id']);
			$ERATE->updateRateComment('1');
		}

		public function onPostComment()
		{

			global $CFG;

			$data = stripslashesarray($CFG->_POST_PARAMS);

			if($CFG->FORM->setForm($data) == false) return;

			if($data['user_act'] == 'add_comment')
			{
				if($CFG->USER->USER_ID > 0)
				{
					$user = $CFG->USER->USER_ID;
				}
				else
				{
					$user['name'] = $data['name'];
					$user['email'] = $data['email'];
					$user['premium'] = $data['premium'];
					$user['user_premium'] = $data['user_premium'];

				}

				$this->setUser($user);
				$this->setReplyComment($data['pcomment']);

				$this->setText($data['text']);

				$this->addAttachment($data['attach_files_image']);
				$this->addCommercial($data['attach_сommercial']);
				$this->addAccessrecord($data['accessrecord']);


				$this->eddAttachment($data['attach_files']);
				$this->musAttachment($data['attach_files_music']);

				$this->Reminder($data['reminder']);

				$this->PremiumMinus($data["premium_minus"]);
				$this->PremiumPlus($data['premium']);

				$this->setCashback($data["cashback"]);

				$this->Big_3($data["big_3"]);
				$this->Big_10($data["big_10"]);

				$this->Important($data["important"]);


				$this->Inform($data["inform"]);
				$this->Access($data["access"]);


				if($data['task'] == 1)
				{
					$this->Task($data['task']);

					$this->TypeTaks($data["type_taks"]);
					$this->OffTask($_POST["off-task"]);

					$this->setType($_POST["doer"]);
					$this->Observer($_POST["observer"]);
				}

				$this->putComment(0);

				$CFG->FORM->CLEARSTATUS();

				$CFG->STATUS->OK = true;
				$CFG->STATUS->MESSAGE = "Ваше изменение успешно сохранено в базе.";


				return true;
			}

		}



		function updateRateComment($rate)
		{
			global $CFG;

			if($rate <= $this->maxrate)
			{
				$response = $this->getComment($this->comment);

				if(sizeof($response) > 0)
				{
					$newrate = $response->rate + $rate;

					$sql = "UPDATE {$CFG->DB_Prefix}comments SET rate = {$newrate} WHERE id='{$response->id}' AND user='{$response->user}' ";
					$CFG->DB->query($sql);

					/* USER UPDATE RATE */
					$CFG->USER->updateUserRate($response->user, $rate);

					return $newrate;
				}
				else
				{
					return -1;
				}
			}
			else
			{
				return false;
			}
		}

		function getList($limit=999999, $offset=0)
		{
			global $CFG;

			$response = $this->getListArray($limit, $offset);

			if(sizeof($response) > 0)
			{
				for($i=0; $i<sizeof($response); $i++)
				{

					$this->printPost($response[$i], $this->commentFormTPL, $i);
				}
			}
			else
			{
				include(EMPTYPOST);
			}
		}



		function getListAjax($limit=999999, $offset=0)
		{
			global $CFG;

			$response = $this->getListArray($limit, $offset);

			if(sizeof($response) > 0)
			{
				for($i=0; $i<sizeof($response); $i++)
				{

					$this->printPost($response[$i], $this->commentFormAJAX, $i);
				}
			}
			else
			{
				include(EMPTYPOST);
			}
		}

		function getListDeal($limit=999999, $offset=0)
		{
			global $CFG;

			$response = $this->getListArrayDeal($limit, $offset);

			if(sizeof($response) > 0)
			{
				for($i=0; $i<sizeof($response); $i++)
				{

					$this->printPost($response[$i], $this->commentFormTPL, $i);
				}
			}
			else
			{
				include(EMPTYPOST);
			}
		}





		function getListData($limit=999999, $offset=0)
		{
			global $CFG;
			$response = $this->getListArray($limit, $offset);
			return count($response);
		}


		function putComment()
		{
			global $CFG;


			//ini_set('error_reporting', E_ALL);
			//ini_set('display_errors', 1);
			//ini_set('display_startup_errors', 1);

			$user = $this->putUserInfo();
			$date = sqlDateNow();

			$edate =  sqlDateNow();
			$query  = "UPDATE {$CFG->DB_Prefix}news SET edate='{$edate}' WHERE id='{$this->id}'";
			$CFG->DB->query($query);

			$uppi = getSQLRowO("SELECT parent_id FROM {$CFG->DB_Prefix}news WHERE id='{$this->id}' ");

			$query  = "UPDATE {$CFG->DB_Prefix}news SET edate='{$edate}' WHERE id='{$uppi->parent_id}'";
			$CFG->DB->query($query);


			if($this->cashback > 0) {$this->UPcashback($this->cashback);}

			if($this->task == 1) {$status_taks = 1;} else {$status_taks = 0;}
			if($this->doer == NULL) {$doer = 0;} else {$doer = $this->doer;}
			if($this->typetaks == NULL) {$typetaks = 0;} else {$typetaks = $this->typetaks;}
			if($this->offtask == NULL) {$offtask = sqlDateNow();} else {$offtask = $this->offtask;}


			 $sql = "INSERT INTO {$CFG->DB_Prefix}comments (page_id, parent_id, user_id, text, commercial, accessrecord, attachments_image, attachments_file,  attach_files_music, reminder, task, doer, starttask, typetaks, offtask, observer, premiumplus, premiumminus, access, inform, cdate, cashback, visible, status_taks, parent_comment_id, important)
			VALUES ('{$this->id}', '{$this->pageid}', '{$CFG->USER->USER_ID}', '{$this->text}', '{$this->commercial}', '{$this->accessrecord}', '{$this->attachment}', '{$this->attachment_edd}', '{$this->attachment_music}', '{$this->reminder}', '{$status_taks}', '{$doer}', '{$date}', '{$typetaks}', '{$offtask}', '{$this->observer}', '{$this->premium}', '{$this->premiumminus}', '{$this->access}', '{$this->inform}', '{$date}', '{$this->cashback}', '{$this->moderate}', {$status_taks}, {$this->replycomment}, {$this->important})";

			$CFG->DB->query($sql);

			$last_id = $CFG->DB->lastId();

			/* Если комент ответ к коменту, отправляем письмо на Email*/
			$this->replycommentMail($this->replycomment);

			if($this->inform)
				Send_Inform($this->inform, $this->id);

			/* Если записали в базу комент */
			if($last_id > 0)
			{
						$this->reminderHtml($last_id);

						//Записываем id комента в запись кешбека если он есть
						if($this->cashback > 0)
						{
							$query  = "UPDATE {$CFG->DB_Prefix}cashback SET comment_id='{$last_id}' WHERE id='{$this->cashback}'";
							$CFG->DB->query($query);
						}

						// записываем id созданого комента для напоменания
						$sql = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}accessrecord WHERE page_id='{$this->id}' AND view=0 AND parent_id = 0");
						for ($x=0; $x<sizeof($sql); $x++)
						{
							$query  = "UPDATE {$CFG->DB_Prefix}accessrecord SET parent_id='{$last_id}', visible = 1 WHERE id='{$sql[$x]->id}'";
							$CFG->DB->query($query);
						}
						//

				/* Если ЗАДАН ШТРАФ, ТО разбираем его по id и активируем его visible в 1 и дописываем id созданой заметки, а сделает за нас все отдельная функция в _properties.php  */
				if($this->premiumminus)
				{
					Premium_Minus($this->premiumminus, $last_id);
				}

				if($this->premium)
				{
					Premium_Plus($this->premium,$last_id);
				}


				$respon = SelectDataRowOArray('news', $this->id);
				$user = SelectDataRowOArray('users', $respon->manager_id , 0);

				/* Определяем что это задача и продалжаем действие */
				if($this->task == 1)
				{
					$sendUser = SelectDataRowOArray('users', $this->doer , 0);

					if($this->doer !== $CFG->USER->USER_ID)
					{
						$subject = "Новая задача!";
						$body    = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile/view/'.$CFG->USER->USER_ID.'/" target="_blank"><strong>'.$CFG->USER->USER_NAME.'</strong></a> перед Вами поставил(а) новую задачу: <strong>'.$this->text.'</strong>. Запись <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/record/'.$this->id.'/" target="_blank"> *'.$this->id.'</a> </strong>';
						mailer($sendUser->email, $subject, $body );

					}
				}

			}

			return $last_id;
		}

		protected function UPcashback($id)
		{

			global $CFG;

			$query  = "UPDATE {$CFG->DB_Prefix}cashback SET visible=1 WHERE id='{$id}'";
			$CFG->DB->query($query);

			$o = getSQLRowO("SELECT mobile, price FROM {$CFG->DB_Prefix}cashback WHERE id='{$id}' ");

		if($CFG->USER->USER_DIRECTOR_ID == 153)
			{
				$text_sms = 'Вам начислен кешбек '.$o->price.' '.$CFG->USER->USER_CURRENCY.'. www.sepcom.ru';
				send_sms($o->mobile, $text_sms);
			}
		else
			{
				$text_sms = 'Вам начислен кешбек '.$o->price.' '.$CFG->USER->USER_CURRENCY.'. www.forsign.kz';
				send_sms($o->mobile, $text_sms);
			}

		}


		function getComment($id)
		{
			$response = $this->getObject($id);

			if(sizeof($response) > 0)
			{
				$this->printPost($response, $this->commentFormTPL);

			}
			else
			{
				include(ERRORREQUEST);
			}
		}

		function getCount()
		{
			global $CFG;

			$sql = "SELECT COUNT(id) FROM {$CFG->DB_Prefix}comments WHERE parent_id='{$this->pageid}' AND page_id='{$this->id}' AND visible=1 AND sys_language='{$CFG->SYS_LANG}'";
			return 1*getSQLField($sql);
		}

		function showFormComment()
		{
			global $CFG;

			$_SESSION['xnum'] = "".rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);

			include($this->addCommentTPL);
		}

		function showButton()
		{
			global $CFG;

			include($this->commentButtonTPL);
		}




		protected function wakeUpParentUser($id)
		{
			global $CFG;

			$oPageSet = getPageInfo($this->pageid);
			$response = $this->getComment($id);
			$user = $this->getUserInfo($response->user);

			$subj = $oPageSet->aOptions["subject"];
			$from = $oPageSet->aOptions["email"];
			$body = $oPageSet->aOptions["body"];

			$m = new ASMail();
			$m->setContentCharset("windows-1251");
			$m->setTo($user["email"]);
			$m->setFrom($from);
			$m->setSubject($subj);
			$m->addText($body);
			$m->addPattern("[%USERNAME%]", $user["name"]);
			$m->addPattern("[%TEXTREQUEST%]", $text);
			$m->addPattern("[%BACKURL%]", $this->getUrlPost());
			$m->send(1);
		}

		protected function getListArray($limit=10, $offset=0)
		{
			global $CFG;

			if(sizeof($this->response) > 0)
			{
				return $this->response;
			}

			$orAccess = $this->orAccess($this->id);
			$orAutorAccess = $this->orAutorAccess($this->id);

			if(big_access('view_note'))
			{
			   $view_note = " ";
			}
			else
			{
				$view_note = " AND my_comments.access = '0' ".$orAccess.' '.$orAutorAccess;
			}


/*
SELECT
		my_comments.id,

		my_accessrecord.autor_id AS my_accessrecord_id,

        my_users.name AS my_accessrecord_name,
        my_comments.user_id AS my_autor_user_id,
        autor_user.id AS my_autor_id,
        autor_user.name AS my_autor_name,
        autor_user.avatar AS my_autor_avatar

FROM my_comments

         LEFT JOIN my_accessrecord ON my_accessrecord.parent_id=my_comments.id
         LEFT JOIN my_users ON my_accessrecord.autor_id=my_users.id
         LEFT JOIN my_users AS autor_user ON my_comments.user_id=autor_user.id


WHERE  my_comments.page_id=34393 AND  my_comments.parent_id = 1000 AND  my_comments.visible=1  ORDER BY  my_comments.cdate DESC LIMIT 0, 999999
*/

			$sql = "  		SELECT
							my_comments.*,
							my_reminderhtml.id AS reminderhtml_id,
			        group_concat(my_accessrecord.autor_id) AS state,
							group_concat(my_accessrecord.view) AS state_view,
			        group_concat(my_users.name) AS state_name,
			        my_comments.user_id AS my_autor_user_id,
			        autor_user.id AS my_autor_id,
			        autor_user.name AS my_autor_name,
			        autor_user.avatar AS my_autor_avatar,
							autor_user.user_id AS my_autor_director_id,
							my_news.type_company_id AS my_news_type_company_id

			FROM my_comments

			         LEFT JOIN my_accessrecord ON my_accessrecord.parent_id=my_comments.id
			         LEFT JOIN my_users ON my_accessrecord.autor_id=my_users.id
							 LEFT JOIN my_users AS autor_user ON my_comments.user_id=autor_user.id
							 LEFT JOIN my_news ON my_news.id=my_comments.page_id
							 LEFT JOIN my_reminderhtml ON my_reminderhtml.coment_id=my_comments.id

			WHERE  my_comments.page_id={$this->id} AND  my_comments.parent_id ={$this->pageid} AND  my_comments.visible=1 {$view_note} GROUP BY  my_comments.id ORDER BY  my_comments.cdate DESC LIMIT  {$offset}, {$limit} ";

			$this->response = getSQLArrayO($sql);



			return $this->buildCommentPost($this->response);

			//$this->commentButtonTPL
		}





				protected function getListArrayDeal($limit=10, $offset=0)
				{
					global $CFG;

					if(sizeof($this->response) > 0)
					{
						return $this->response;
					}

					$orAccess = $this->orAccess($this->id);
					$orAutorAccess = $this->orAutorAccess($this->id);

					if(big_access('view_note'))
					{
					   $view_note = " ";
					}
					else
					{
						$view_note = " AND access = '0' ".$orAccess.' '.$orAutorAccess;
					}


					$sql = "SELECT * FROM {$CFG->DB_Prefix}comments WHERE page_id='{$this->id}' AND parent_id = '{$this->pageid}' AND visible=1  {$view_note}  ORDER BY cdate DESC LIMIT {$offset}, {$limit} ";

					$this->response = getSQLArrayO($sql);

					return $this->response;

					//$this->commentButtonTPL
				}





		protected function orAccess($pageid)
		{
			global $CFG;

			$array = getSQLArrayO("SELECT id, access FROM {$CFG->DB_Prefix}comments WHERE page_id='{$this->id}' AND visible=1 ");

			for($i=0; $i<sizeof($array); $i++)
			{
				if($array[$i]->access == "0") continue;

				$cat_id = explode(",", $array[$i]->access);

				for ($x=0; $x<sizeof($cat_id); $x++)
				{
					if($cat_id[$x] == $CFG->USER->USER_ID)
						$id .= $array[$i]->id.',';
				}
			}

			$idS = trim($id, ",");

				if($idS == !"")
					$newsIdAnd .= " OR my_comments.id in({$idS}) ";

			return $newsIdAnd;

		}


		protected function Borovikov($user_id)
		{
			global $CFG;

			//id Боровикова, суть - его компания не видет наши коменты а мы его

			$real_id = 569;

			$array = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}users WHERE user_id={$real_id} AND visible=1 ");

			for ($x=0; $x<sizeof($array); $x++)
			{
					$id .= $array[$x]->id.',';
			}
			$idS = trim($id, ",");

			if($user_id == $real_id)
			{
				$res = " AND user_id IN  ({$idS}) ";
			}
			else
			{
				$res = " AND user_id NOT IN  ({$idS}) ";
			}

			return $res;

		}


		protected function orAutorAccess($pageid)
		{
			global $CFG;

			$array = getSQLArrayO("SELECT id FROM {$CFG->DB_Prefix}comments WHERE page_id='{$this->id}' AND user_id = '{$CFG->USER->USER_ID}' AND visible=1 ");

			for($i=0; $i<sizeof($array); $i++)
			{
				if(is_numeric($array[$i]->id))
				{
						$id .= $array[$i]->id.',';
				}

			}
				$idS = trim($id, ",");

				if($idS == !"")
					$newsIdAnd .= " OR my_comments.id in({$idS}) ";

			return $newsIdAnd;

		}



		protected function getObject($id)
		{
			global $CFG;

			$sql = "SELECT * FROM {$CFG->DB_Prefix}comments WHERE page_id='{$this->id}' AND parent_id='{$this->pageid}' AND visible=1  AND id='{$id}'";

			return getSQLRowO($sql);
		}

		protected function getUserInfo($data)
		{
			global $CFG;

			if(is_numeric($data))
			{
				return $CFG->USER->getUserInfo($data);
			}
			else
			{
				return unserialize($data);
			}
		}

		protected function putAttachments()
		{
			if(is_array($this->attachment))
			{
				return implode(",", $this->attachment);
			}

			return $this->attachment;
		}

		protected function putUserInfo()
		{
			global $CFG;

			return $this->user;
		}

		protected function buildCommentPost($array)
		{

			for($i=0; $i<sizeof($array); $i++)
			{
				$comments[$array[$i]->parent_comment_id][] = $array[$i];
			}

			return $this->treePost($comments, 0);
		}


		protected function replycommentMail($int)
		{
			global $CFG;

			if($int > 0)
			{
				$cdate = sqlDateNow();

				$sql = getSQLRowO("SELECT user_id, page_id, id  FROM {$CFG->DB_Prefix}comments WHERE id='{$int}'");

				$res = getSQLRowO("SELECT email, id FROM {$CFG->DB_Prefix}users WHERE id='{$sql->user_id}'");

				$sql = "INSERT INTO {$CFG->DB_Prefix}replycomment (user_id, autor_id, page_id, parent_id, view, cdate, visible) VALUES ('{$res->id}', '{$CFG->USER->USER_ID}', '{$sql->page_id}', '{$sql->id}', 0, '{$cdate}', 1)";

				if ($CFG->DB->query($sql))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}


		protected function reminderHtml($int)
		{
			global $CFG;

			if($int > 0)
			{
				$time = time();
				$cdate = sqlDateNow();;

				if($this->big_3 == 3)
				{
					$sql = "INSERT INTO {$CFG->DB_Prefix}reminderhtml (page_id, coment_id, user_id, cdate, time_start, data, status, visible) VALUES ('{$this->id}', '{$int}', '{$CFG->USER->USER_ID}', '{$cdate}', '{$time}', '{$this->big_3}', 0, 1)";	$CFG->DB->query($sql);
				}

				if($this->big_10 == 10)
				{
					$sql = "INSERT INTO {$CFG->DB_Prefix}reminderhtml (page_id, coment_id, user_id, cdate, time_start, data, status, visible) VALUES ('{$this->id}', '{$int}', '{$CFG->USER->USER_ID}', '{$cdate}', '{$time}', '{$this->big_10}', 0, 1)";	$CFG->DB->query($sql);
				}


				return true;
			}
		}





		protected function treePost($comments, $parent)
		{
			$this->level++;
			$result = array();
			if(is_array($comments) && isset($comments[$parent]))
			{
				foreach($comments[$parent] as $comment)
				{
					$comment->level = $this->level;
					$result[] = $comment;
					$result = array_merge($result, $this->treePost($comments, $comment->id));
					$this->level--;
				}
			}
			else
			{
				return array();
			}

			return $result;
		}

		protected function printPost($o, $src)
		{
			global $CFG;

			$user = $this->getUserInfo($o->user);

			include($src);
		}

	}

	class ADMComments extends Comments
	{
		private $isAdmin;

		private $pageid;
		private $id;

		private $lang;

		function __construct($pid)
		{
			global $CFG;


			if($CFG->USER->is_loggedInAdmin())
			{
				parent::__construct($pid);

				$this->pageid = $pid;
				$this->isAdmin = true;
			}
			else
				die("You are is not admin!");
		}

		function setTemplate($src)
		{
			parent::setTemplate($src);
		}

		function setPostId($id)
		{
			parent::setPostId($id);

			$this->id = $id;
		}

		function getObject($id)
		{
			global $CFG;

			$sql = "SELECT * FROM {$CFG->DB_Prefix}comments WHERE page_id='{$this->id}' AND parent_id='{$this->pageid}'  AND id='{$id}'";

			return getSQLRowO($sql);
		}

	}

?>
