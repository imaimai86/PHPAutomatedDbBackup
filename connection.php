<?php

/*

* Connection file

*

*/

class connection {

    public $dbhost;

    public $dbname;

    public $dbpass;

    public $link;

    function __construct() {

        $this->dbhost = DB_HOST;

        $this->dbusername = DB_USERNAME;

        $this->dbname = DB_NAME;

        $this->dbpass = DB_PASS;

    }

    function select_database($link){

        @mysql_select_db($this->dbname)  or die('Database Selecton Porblem');

    }

    function connect(){

        $this->link = @mysql_connect($this->dbhost,$this->dbusername,$this->dbpass) or die('Database connection errors');

        $this->select_database($this->link);

    }

    function closeConnection(){

        @mysql_close($this->link);

    }

    function getLink(){

        $this->link = @mysql_connect($this->dbhost,$this->dbusername,$this->dbpass);

        return $this->link;

    }

    public  function execute($sql,$type='select-all'){

        if($sql!=''){
            switch ($type){
                case 'select':
                    $rs = @mysql_query($sql);
                    if($rs && mysql_num_rows($rs) > 0){

                        return mysql_fetch_object($rs);
                    }else{

                        return false;
                    }

                    break;
                case 'select-all':

                    $rs = @mysql_query($sql);
                    if($rs && mysql_num_rows($rs) > 0){
                        while ($row = mysql_fetch_object($rs)){
                            $result[] = $row;
                        }

                        return $result;
                    }else{
                        return false;

                    }

                    break;
                case 'insert':
                    $rs = @mysql_query($sql);
                    if($rs){

                        return mysql_insert_id();
                    }else{

                        return false;
                    }

                    break;
                case 'update': case 'delete' :

                    $rs = @mysql_query($sql);
                    if($rs){

                        return true;
                    }else{

                        return false;
                    }

                break;
            }
        }
    }
}

?>