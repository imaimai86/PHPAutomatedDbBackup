<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/26/2015
 * Time: 10:07 AM
 */
class Db_Backup {

    private $db_host = 'localhost', $db_user,  $db_pass,  $db_name,  $db_tables = '*', $db_connection_link = false;
    private $is_exec, $is_dump, $dump_function = 'php_backup_tables', $dump_file_name;
    public $dump_location = 'DB_BU', $dump_prefix = 'db_bu_{db_name}_{date_time}.sql';
    public $connectionObj;

    /**
     * Constructor function
     * Checks environment and select appropriate backup function
     *
     */
    public function __construct() {
        $this->checkEnvironment();
    }

    public function __destruct()
    {
        if ($this->db_connection_link) {
            mysql_close($this->db_connection_link);
        }
    }

    public function executeBackup() {
        $result = false;
        if (!empty($this->dump_location)) {
            if (file_exists($this->dump_location) && is_dir($this->dump_location) && is_writable($this->dump_location)) {
                //echo "<br />$this->dump_function<br />";
                $result = $this->{$this->dump_function}();
            }
        }

        //Check if exec is enabled
        //Check if mysqldump function is available
        //If yes select function that uses mysqldump

        return $result;
    }

    private function checkEnvironment() {
        if (!file_exists($this->dump_location)) mkdir($this->dump_location);
        if (file_exists(!$this->dump_location) OR !is_dir($this->dump_location) OR !is_writable($this->dump_location)){
            //Send Email - permission issue

            exit("<br><br> Permission issue with dump location");
        }

        $this->is_exec = false;
        $this->is_dump = false;
        $this->dump_function = 'php_backup_tables';
    }

    public function setCredentials($options = array()) {
        if (!empty($options) && is_array($options)) {
            foreach ($options as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    function connectDb() {
        $this->db_connection_link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
        mysql_select_db($this->db_name, $this->db_connection_link);
        mysql_query("SET NAMES 'utf8'");
    }

    function php_backup_tables()
    {
        $this->connectDb();
        //echo "<br><br>Database: $this->db_name<br>";
        //get all of the tables
        if($this->db_tables == '*')
        {
            $this->db_tables = array();
            $result = mysql_query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'", $this->db_connection_link);
            while($row = mysql_fetch_row($result))
            {
                $this->db_tables[] = $row[0];
            }
        }
        else
        {
            $tables = is_array($this->db_tables) ? $this->db_tables : explode(',', $this->db_tables);
        }

        $return = "\r\n\r\n".' -- Generation Time: '.date('M d, Y at H:i').' '."\r\n\r\n";
        $return .= "\r\n\r\n".' SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";'."\r\n";
        $return .= "\r\n".' -- Table structures and data '."\r\n\r\n";
        //cycle through
        foreach($this->db_tables as $table)
        {
            //echo "<br>Table: $table";
            $return .= "\r\n -- Table structure of \"$table\" \r\n\r\n";
            $result = mysql_query('SELECT * FROM '.$table, $this->db_connection_link);
            $num_fields = mysql_num_fields($result);

            $return.= 'DROP TABLE IF EXISTS '.$table.';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table, $this->db_connection_link));
            $return.= "\r\n\r\n".$row2[1].";\r\n\r\n";

            for ($i = 0; $i < $num_fields; $i++)
            {
                while($row = mysql_fetch_row($result))
                {
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j<$num_fields; $j++)
                    {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) {
                            if ($row[$j] == null) {
                                $return .= "''";
                            } else {
                                $return .= '"' . $row[$j] . '"';
                            }
                        } else {
                            $return.= '""';
                        }
                        if ($j<($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\r\n";
                }
            }
            $return.="\r\n\r\n\r\n";
        }


        return $this->save_dump($return);
    }

    private function save_dump($return) {
        $this->prepare_dump_name();
        //save file
        $handle = fopen($this->dump_file_name.'.sql','w+');
        if (fwrite($handle, utf8_encode($return))) {
            fclose($handle);
            return true;
        } else {
            return false;
        }
    }

    private function prepare_dump_name() {
        $dump_file_name = !empty($this->dump_prefix) ? $this->dump_prefix : 'DB_BACKUP_'.$this->db_name;
        $dump_file_name = str_replace('{db_name}', $this->db_name, $dump_file_name);
        $dump_file_name = str_replace('{date_time}', date('Y.m.d.H.i.s'), $dump_file_name);
        $this->dump_file_name = (!empty($this->dump_location) ? $this->dump_location.'/' : '').$dump_file_name;
    }
}
