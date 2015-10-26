<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/26/2015
 * Time: 10:07 AM
 */
class Db_Backup {

    private $db_host = 'localhost', $db_user,  $db_pass,  $db_name,  $db_tables = '*', $db_connection_link = false;
    private $is_exec, $is_dump, $dump_function = 'php_backup_tables';
    public $dump_location = '', $dump_prefix = 'db_bu_{db_name}_{date_time}.sql';

    /**
     * Constructor function
     * Checks environment and select appropriate backup function
     *
     */
    public function __construct() {
        $this->checkEnvironment();
    }

    public function executeBackup() {

        echo "<br />$this->dump_function<br />";
        $this->{$this->dump_function}();
        //Check if exec is enabled
        //Check if mysqldump function is available

    }

    private function checkEnvironment() {
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

    function php_backup_tables()
    {
        //$host = ,$user,$pass,$name,$tables = '*'

        $this->db_connection_link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
        mysql_select_db($this->db_name, $this->db_connection_link);

        //get all of the tables
        if($this->db_tables == '*')
        {
            $this->db_tables = array();
            $result = mysql_query('SHOW TABLES');
            while($row = mysql_fetch_row($result))
            {
                $this->db_tables[] = $row[0];
            }
        }
        else
        {
            $tables = is_array($this->db_tables) ? $this->db_tables : explode(',', $this->db_tables);
        }

        //cycle through
        foreach($this->db_tables as $table)
        {
            $return = '';
            $result = mysql_query('SELECT * FROM '.$table);
            $num_fields = mysql_num_fields($result);

            $return.= 'DROP TABLE '.$table.';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
            $return.= "\n\n".$row2[1].";\n\n";

            for ($i = 0; $i < $num_fields; $i++)
            {
                while($row = mysql_fetch_row($result))
                {
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j<$num_fields; $j++)
                    {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                        if ($j<($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }


        $this->save_dump($return);
    }

    private function save_dump($return) {
        $this->prepare_dump_name();
        //save file
        $handle = fopen($this->dump_file_name.'.sql','w+');
        fwrite($handle,$return);
        fclose($handle);
    }

    private function prepare_dump_name() {
        $dump_file_name = !empty($this->dump_prefix) ? $this->dump_prefix : 'DB_BACKUP_'.$this->db_name;
        $dump_file_name = str_replace('{db_name}', $this->db_name, $dump_file_name);
        $dump_file_name = str_replace('{date_time}', date('Y.m.d.H.i.s'), $dump_file_name);
        $this->dump_file_name = $this->dump_location.$dump_file_name;
    }
}
