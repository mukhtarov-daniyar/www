<?php

include('_cfg.php');



//Call the core function
backup_tables($CFG->DB_Host, $CFG->DB_User, $CFG->DB_Password, $CFG->DB_Name, '*');

//Core function
function backup_tables($host, $user, $pass, $dbname, $tables = '*')
{
    $link = mysqli_connect($host,$user,$pass, $dbname);

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    mysqli_query($link, "SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }

    $return = '';
    //cycle through

    foreach($tables as $table)
    {
        $structure = 'backup/mysql/'.date('m').'/'.date('d').'/';
        if (!file_exists($structure))
        {
            mkdir($structure, 0777, true);
        }
        $output = '';
        $return_var = 0;
        $command = 'mysqldump --user='.$user.' --password='.$pass.' --host='.$host.' --skip-add-drop-table '.$dbname.' '.$table.' > '.$structure.$table.'.sql 2>&1';
        exec($command, $output, $return_var);
        if ($return_var !== 0) {
            echo "Error dumping table $table: " . implode("\n", $output);
        } else {
            echo "Table $table dumped successfully.\n";
        }
    }

}
