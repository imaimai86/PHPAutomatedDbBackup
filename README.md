# PHPAutomatedDbBackup

Credits: David Walsh for his db backup script. http://davidwalsh.name/backup-mysql-database-php

I implemented a script that can be run via cron, and can schedule different time for backing up diffrent tables.
It reads details of databases scheduled to back up at the toime of execution and create backup
You can schedule the backup of different databases at different time.
The time precision check for minutes only

How to setu-up

Move the code to your server under a directory say "DB_Backup"
Create a database, and a table with this structure

CREATE TABLE IF NOT EXISTS `table_credentials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL DEFAULT 'localhost',
  `db_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `scheduled_execution_time` time NOT NULL,
  `created_date_time` datetime NOT NULL,
  `updated_date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `execution_status` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `last_execution_date` datetime NOT NULL,
  `created_ip` varchar(50) NOT NULL,
  `updated_ip` varchar(50) NOT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `is_mannual` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

Now update config.php with your db username, password etc.

Add the credentials of databases you want to backup
If you want to backup 5 databases at 12:00 am and 5 other at 1:00 am the make the value of scheduled_execution_time '12:00:00' for the first 5 databases and '1:00:00' for the next 5 databases.

Now schedule the cron to to execute 

ROOT_DIRECTORY/DB_Backup/backup_db.php at 12:00 and 1:00

Thats it.

Any suggestion, updates and modification is welcome
