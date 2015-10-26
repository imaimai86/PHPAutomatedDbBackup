-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 26, 2015 at 01:06 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_backup_credentials`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_credentials`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `table_credentials`
--

INSERT INTO `table_credentials` (`id`, `title`, `host`, `db_name`, `user_name`, `password`, `scheduled_execution_time`, `created_date_time`, `updated_date_time`, `execution_status`, `last_execution_date`, `created_ip`, `updated_ip`, `is_active`, `is_mannual`) VALUES
(1, 'Caducious Medicals', 'localhost', 'caduciousmedical', 'root', '', '12:01:00', '0000-00-00 00:00:00', '2015-10-26 13:05:23', '0', '0000-00-00 00:00:00', '', '', '1', '1'),
(2, 'WikiVoters', 'localhost', 'admin_wiki_new', 'root', '', '12:01:00', '0000-00-00 00:00:00', '2015-10-26 13:05:23', '0', '0000-00-00 00:00:00', '', '', '1', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
