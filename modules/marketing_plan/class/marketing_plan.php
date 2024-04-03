<?

class Market
{
	function getDount($post)
	{
		global $CFG;
		
		
		$sql = "INSERT INTO {$CFG->DB_Prefix}tmp_whatsapp_rss_tmp (user_id, name,  mobile, status, visible) VALUES ('{$post[user]}', '{$post[name]}', '{$post[mobile]}', 0, 0)";
		if($CFG->DB->query($sql))
		{
			$o = getSQLRowO("SELECT id FROM {$CFG->DB_Prefix}tmp_whatsapp_rss_tmp WHERE status=0 AND visible = 0 order by id DESC");
			echo $o->id;
		}
		
		
	}
}

