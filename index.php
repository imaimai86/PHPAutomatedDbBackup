<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/26/2015
 * Time: 10:18 AM
 */
include_once 'Db_BackupClass.php';


$dbBackupObj = new Db_Backup();
$options = array('db_user' => 'root', 'db_name' => 'admin_wiki_new', 'db_pass' => '', );
$dbBackupObj->setCredentials($options);
$dbBackupObj->executeBackup();
