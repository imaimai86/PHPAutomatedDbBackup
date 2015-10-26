<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/26/2015
 * Time: 10:18 AM
 */
include_once 'Db_BackupClass.php';
include_once 'config.php';
include_once 'connection.php';

$connection = new connection();
$connection->connect();

$current_time = date('Y-m-d H:i');
//$current_time = '12:01';


$sql = "SELECT * FROM  `table_credentials` WHERE DATE_FORMAT(scheduled_execution_time,'%H:%i') = '$current_time' ";
$result = $connection->execute($sql);

$dbList = array();
if ($result) {
    $dbList = $result;
}

if (!empty($dbList) && is_array($dbList)) {
    foreach ($dbList as $db) {
        //Update status - in progress
        $sql = "UPDATE `table_credentials` SET execution_status = '1' WHERE id = $db->id";
        $connection->execute($sql, 'update');
        //Execute backup
        $dbBackupObj = new Db_Backup();
        $options = array('db_user' => $db->user_name, 'db_name' => $db->db_name, 'db_pass' => $db->password, 'db_host' => $db->host, );
        $dbBackupObj->setCredentials($options);
        $result = $dbBackupObj->executeBackup();
        unset($dbBackupObj);
        $connection->connect();
        if ($result == true) {
            //Update status - Finished
            $sql = "UPDATE `table_credentials` SET execution_status = '0' WHERE id = $db->id";
            $connection->execute($sql, 'update');
        } else {
            //Update status - Failed
            $sql = "UPDATE `table_credentials` SET execution_status = '3' WHERE id = $db->id";
            $connection->execute($sql, 'update');
            //Send email

        }
    }
}
//Close after fetching db details
if ($connection->link) mysql_close($connection->link);
die();



