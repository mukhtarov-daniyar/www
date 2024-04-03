<?php
/*
 * MySQL interface
 */

class DB_Sql {

  /* public: connection parameters */
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";

  var $Link_ID  = 0;
  var $Query_ID = 0;
  var $Record   = array();
  var $Row      = 0;

  var $Errno    = 0;
  var $Error    = "";

  var $dbs   = array();
  var $tbls  = array();
  var $dbtbl = "";

  var $QUERYCOUNT = 0;

  var $Auto_Free = 0; # Set this to 1 for automatic pg_freeresult on
                      # last record.

	var $cache = array();

  /* public: constructor */
  function DB_Sql($Database = "", $Host = "localhost", $User = "", $Password = "") {
      $this->Database=$Database;
      $this->Host=$Host;
      $this->User=$User;
      $this->Password=$Password;
  }



  function ifadd($add, $me) {
      if("" != $add) return " ".$me.$add;
  }

  function connect_only() {
      if ( 0 == $this->Link_ID ) {
//          echo "[".$this->Host."] [".$this->Database."] [".$this->User."] [".$this->Password."]";
          $this->Link_ID=mysql_connect($this->Host, $this->User, $this->Password);
          if (!$this->Link_ID) {
              $this->halt("Link-ID == false, Connect failed");
          }
      }
  }

  function connect() {
		global $CFG;
      if ( 0 == $this->Link_ID ) {
//          echo "[".$this->Host."] [".$this->Database."] [".$this->User."] [".$this->Password."]";
          $this->Link_ID=mysql_connect($this->Host, $this->User, $this->Password);

//	$f = fopen("{$_SERVER["DOCUMENT_ROOT"]}/sql.log", "a");
//	fputs($f, "Connect....\r\n");
//	fclose($f);

          if (!$this->Link_ID) {
              $this->halt("Link-ID == false, Connect failed");
          }
          $res = mysql_select_db($this->Database, $this->Link_ID);
          if (!$res) {
              $this->halt("Link-ID == false, Select database failed");
          }

			if(!$CFG->EMULATE_UTF8)
			{
				$this->query("SET CHARACTER SET utf8");
				$this->query("SET NAMES utf8");
			}
/*
$this->query ("set character_set_client='cp1251'");
$this->query ("set character_set_results='cp1251'");
$this->query ("set collation_connection='cp1251_general_ci'");
*/

      }
  }

  function query($Query_String)
  {
	global $CFG;
    $this->connect();

#   printf("<br>Debug: query = %s<br>\n", $Query_String);

//	$f = fopen("{$_SERVER["DOCUMENT_ROOT"]}/sql.log", "a");
//	fputs($f, $Query_String."\r\n");
//	fclose($f);

	if ($CFG->EMULATE_UTF8)
		$Query_String = utf8win1251($Query_String);
    $this->Query_ID = mysql_query($Query_String, $this->Link_ID);
    $this->Row   = 0;

    $this->Error = mysql_error();
    $this->Errno = mysql_errno();
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }

    return $this->Query_ID;
  }

  function last_id()
  {
    return mysql_insert_id($this->Link_ID);
  }

  function next_record()
  {
	global $CFG;
    $this->Record = @mysql_fetch_array($this->Query_ID);
	if ($CFG->EMULATE_UTF8)
		while(@list($k, $v) = @each($this->Record))
			$this->Record[$k] = win2utf8($v);

    $this->Error = mysql_error();
    $this->Errno = mysql_errno();

    $stat = is_array($this->Record);
    if (!$stat && $this->Auto_Free) {
      mysql_freeresult($this->Query_ID);
      $this->Query_ID = 0;
    }
    return $stat;
  }

  function fetch_object()
  {
	global $CFG;
    $res = @mysql_fetch_object($this->Query_ID);
	if ($CFG->EMULATE_UTF8)
		while(@list($k, $v) = @each($res))
			$res->$k = win2utf8($v);
	return $res;
  }

  function fetch_array()
  {
	global $CFG;
    $res = @mysql_fetch_array($this->Query_ID);
	if ($CFG->EMULATE_UTF8)
		while(@list($k, $v) = @each($res))
			$res[$k] = win2utf8($v);
	return $res;
  }

  function seek($pos) {
    $this->Row = $pos;
  }

  function lock($table, $mode = "write") {
    if ($mode == "write") {
      $result = mysql_query("lock table $table", $this->Link_ID);
    } else {
      $result = 1;
    }
    return $result;
  }

  function unlock() {
    return mysql_query("commit", $this->Link_ID);
  }

  function metadata($table) {
    $count = 0;
    $id    = 0;
    $res   = array();

    $this->connect();
    $id = mysql_query("select * from $table", $this->Link_ID);
    if ($id < 0) {
      $this->Error = mysql_error();
      $this->Errno = mysql_errno();
      $this->halt("Metadata query failed.");
    }
    $count = mysql_num_fields($id);

    for ($i=0; $i<$count; $i++) {
      $res[$i]["table"] = $table;
      $res[$i]["name"]  = mysql_field_name($id, $i);
      $res[$i]["type"]  = mysql_field_type($id, $i);
      $res[$i]["len"]   = mysql_field_len($id, $i);
      $res[$i]["flags"] = "";
    }

    mysql_freeresult($id);
    return $res;
  }

  function affected_rows() {
    return mysql_affected_rows($this->Query_ID);
  }

  function num_rows() {
    if ($this->Query_ID < 2)
     return 0;
    return mysql_num_rows($this->Query_ID);
  }

  function num_fields() {
    if ($this->Query_ID < 2)
     return 0;
    return mysql_num_fields($this->Query_ID);
  }

  function nf() {
    return $this->num_rows();
  }

  function np() {
    print $this->num_rows();
  }

  function f($Name) {
    return $this->Record[$Name];
  }

  function p($Name) {
    print $this->Record[$Name];
  }

  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
      $this->Errno,
      $this->Error);
    die("Session halted.");
  }

  function table_names() {
    $result = mysql_listtables ($this->Database);
    if ($result<1)
        return false;
    $i = 0;
    $this->tbls = array();
    while ($i < mysql_num_rows ($result)) {
        $return[$i]["table_name"] = mysql_tablename ($result, $i);
        $return[$i]["tablespace_name"] = $this->Database;
        $return[$i]["database"] = $this->Database;
        $this->tbls[strtoupper($return[$i]["table_name"])] = 1;
//echo "<br>".strtoupper($return[$i]["table_name"]);
        $i++;
    }
    $this->dbtbl = $this->Database;
    return $return;
  }

  function db_names() {
    $result = mysql_listdbs();
    if ($result<1)
        return false;
    $i = 0;
    $this->dbs = array();
    while ($i < mysql_num_rows ($result)) {
        $return[$i] = mysql_dbname ($result, $i);
        $this->dbs[strtoupper($return[$i])] = 1;
//echo "<br>".strtoupper($return[$i]);
        $i++;
    }
    return $return;
  }

  function db_exist($db) {
//    if (sizeof($this->dbs)==0)
       $this->db_names();
    if ($this->dbs[strtoupper($db)] == 1)
       return 1;
    else
       return 0;
  }

  function table_exist($tbl) {
//    if (sizeof($this->tbls)==0)
       $this->table_names();
    if ($this->tbls[strtoupper($tbl)] == 1)
       return 1;
    else
       return 0;
  }

}


function getSQLArrayO($sql, $db=0)
{
	global $db;
	if (!$db)
		$db = createConnection();

	if (isset($db->cache[$sql]))
		return $db->cache[$sql];

	$db->query($sql);
	$list = array();
	for($i=0; $i<$db->num_rows(); $i++)
	{
		$list[$i] = $db->fetch_object();
	}
	$db->cache[$sql] = $list;
	$db->QUERYCOUNT++;
	return $list;
}


function getSQLArrayA($sql, $db=0)
{
	global $db;
	if (!$db)
		$db = createConnection();

	if (isset($db->cache[$sql]))
		return $db->cache[$sql];

	$db->query($sql);
	$list = array();
	for($i=0; $i<$db->num_rows(); $i++)
	{
		$list[$i] = $db->fetch_array();
	}
	$db->cache[$sql] = $list;
	$db->QUERYCOUNT++;
	return $list;
}



function getSQLField($sql, $db=0)
{
	global $db;
	if (!$db)
		$db = createConnection();

	if (isset($db->cache[$sql]))
		return $db->cache[$sql];

	$db->query($sql);
	$list = null;
	if ($db->num_rows())
	{
		$list = $db->fetch_array();
		$db->cache[$sql] = $list[0];
		return $list[0];
	}
	$db->cache[$sql] = null;
	$db->QUERYCOUNT++;
	return null;
}


function getSQLRowO($sql, $db=0)
{
	global $db;

	if (!$db)
		$db = createConnection();

	if (isset($db->cache[$sql]))
		return $db->cache[$sql];

	$db->query($sql);
	$list = array();
	if($db->num_rows())
	{
		$list = $db->fetch_object();
	}
	$db->cache[$sql] = $list;
	$db->QUERYCOUNT++;
	return $list;
}



function getSQLRowA($sql, $db=0)
{
	global $db;
	if (!$db)
		$db = createConnection();

	if (isset($db->cache[$sql]))
		return $db->cache[$sql];

	$db->query($sql);
	$list = array();
	if($db->num_rows())
	{
		$list = $db->fetch_array();
	}
	$db->cache[$sql] = $list;
	$db->QUERYCOUNT++;
	return $list;
}
?>
