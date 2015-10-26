<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/26/2015
 * Time: 10:18 AM
 */
include_once 'Db_BackupClass.php';


$dbBackupObj = new Db_Backup();
$options = array('db_user' => '****', 'db_name' => '*****', 'db_pass' => '*****', );
$dbBackupObj->setCredentials($options);
$dbBackupObj->executeBackup();
?>
<table>

</table>

