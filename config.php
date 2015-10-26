<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/26/2015
 * Time: 11:54 AM
 */
$config = array(
    'DB_HOST' => 'localhost',
    'DB_USERNAME' => 'root',
    'DB_PASS' => '',
    'DB_NAME' => 'db_backup_credentials'
);

foreach ($config as $key => $value) {
    if (!defined("$key")) define($key, $value);
}