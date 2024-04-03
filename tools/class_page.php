<?php
	class Page
	{
		var $pid = 0;
		var $changed = 0;
	/*
		var $parent_id = 0;
		var $title = "";
		var $menu_name = "";
		var $template = "";
		var $blocks = array();
		var $html_title = "";
		var $html_descr = "";
		var $html_kwrds = "";
	*/
		var $o = array();
		var $db;
	
	//	static $errCode = 0;
		static function errName($errCode)
		{
			$err = array("-", "Такой xcode уже существует.");
			return $err[ $errCode ];
		}
	
	
		static function createNew($name, $xcode, $type, $parent_id, &$errCode, $params=null)
		{
			GLOBAL $CFG, $db;
	
			$parent_id *= 1;
	
			$id = 1*getSQLField("SELECT id FROM {$CFG->DB_Prefix}pages WHERE xcode='".addslashes($xcode)."'");
			if ($id > 0)
			{
				$errCode = 1;
				return null;
			}
	
	
			$visible = 0;
			$menu_flag = 0;
			$pos = 0;
			if (is_array($params))
			{
				$pos = 1 * $params["pos"];
				$visible = 1 * $params["visible"];
				$menu_flag = 1 * $params["menu_flag"];
			}
	
			if ($pos<=0)
			{
				$pos = 10 + 1*getSQLField("SELECT max(pos) FROM {$CFG->DB_Prefix}pages WHERE parent_id='{$parent_id}'");
			}
	
			$sql  = "INSERT INTO {$CFG->DB_Prefix}pages (parent_id, xcode, name, menu_name, menu_flag, tmpl_u, tmpl_a, visible, pos, sys_language) VALUES (";
			$sql .= "'{$parent_id}', ";
			$sql .= "'".addSlashes($xcode)."', ";
			$sql .= "'".addSlashes($name)."', ";
			$sql .= "'".addSlashes($name)."', ";
			$sql .= "'{$menu_flag}', ";
			$sql .= "'".addSlashes($type)."', ";
			$sql .= "'', ";
			$sql .= "'{$visible}', ";
			$sql .= "'{$pos}', ";
			$sql .= "'{$CFG->SYS_LANG}')";
	
			$db->query($sql);
	
			return new Page( $db->last_id() );
		}
	
	
	
		function Page($pid=0, $lang=0)
		{
			GLOBAL $CFG;
	
			$this->pid = $pid;
	
			$this->db = createConnection();
	
			$this->o = $this->loadPageInfo($pid, $lang);
		}
	
	
		function loadPageInfo($pid=0, $lang=0)
		{
			GLOBAL $CFG;
	
			$pid *= 1;
			if ($pid <= 0)
				$pid = $this->pid;
	
			$sql = "SELECT * FROM {$CFG->DB_Prefix}pages WHERE id='{$pid}'";
			$o = getSQLRowA($sql);
	
			return $o;
		}
	
		function setTitle($str)
		{
			$this->o["name"] = $str;
		}
	
		function setParent($parent_id)
		{
			$this->o["parent_id"] = $parent_id;
		}
	
		function setType($type)
		{
			$this->o["tmpl_u"] = $type;
		}
	
		function setAccess($access)
		{
			$this->o["access"] = $access;
		}
	
		function addBlock($body, $title="", $visible=1, $pos=0)
		{
			GLOBAL $CFG;
	
			if ($pos<=0)
			{
				$pos = 10 + 1*getSQLField("SELECT max(pos) FROM {$CFG->DB_Prefix}docs WHERE page_id='{$this->pid}'");
			}
	
			$visible *= 1;
			$pos *= 1;
			$sql  = "INSERT INTO {$CFG->DB_Prefix}docs (page_id, title, body, visible, pos, sys_language) VALUES (";
			$sql .= "'{$this->pid}', ";
			$sql .= "'".addSlashes($title)."', ";
			$sql .= "'".addSlashes($body)."', ";
			$sql .= "'{$visible}', ";
			$sql .= "'{$pos}', ";
			$sql .= "'{$CFG->SYS_LANG}')";
	
			$this->db->query($sql);
			return $this->db->last_id();
		}
	
		function update()
		{
			GLOBAL $CFG;
	
			$sql  = "UPDATE {$CFG->DB_Prefix}pages SET ";
			$i = 0;
			while(list($k, $v)=each($this->o))
			{
				if (2*round($i/2) != $i)
				{
					if ($i>1)
						$sql .= ", ";
						$sql .= "`{$k}`='{$v}'";
				}
				$i++;
			}
			$sql .= " WHERE id='{$this->pid}'";
			$this->db->query($sql);
		}
	
	
		function addChild($menu_name, $tmpl, $visible=1, $pos=0, $url="")
		{
			GLOBAL $CFG;
	
			if ($pos<=0)
			{
				$pos = 10 + 1*getSQLField("SELECT max(pos) FROM {$CFG->DB_Prefix}pages WHERE parent_id='{$this->pid}'");
			}
	
			$sql  = "INSERT INTO {$CFG->DB_Prefix}pages (parent_id, name, menu_name, menu_flag, tmpl_u, tmpl_a, visible, pos, sys_language) VALUES (";
			$sql .= "'{$this->pid}', ";
			$sql .= "'".addSlashes($menu_name)."', ";
			$sql .= "'".addSlashes($menu_name)."', ";
			$sql .= "'0', ";
			$sql .= "'".addSlashes($tmpl)."', ";
			$sql .= "'".addSlashes($url)."', ";
			$sql .= "'{$visible}', ";
			$sql .= "'{$pos}', ";
			$sql .= "'{$CFG->SYS_LANG}')";
	
			$this->db->query($sql);
			return $this->db->last_id();
		}
	
		function getPID()
		{
			return $this->pid;
		}
	
	}