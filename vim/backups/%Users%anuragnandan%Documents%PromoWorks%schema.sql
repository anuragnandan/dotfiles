Vim�UnDo� 4��M�sH#�W�=i�9hf��E�W!Y�;+�  �   CREATE DATABASE `boc1651`;o            
       
   
   
    ZZ    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Z3     �              �   ># ************************************************************   # Sequel Pro SQL dump   # Version 4529   #   # http://www.sequelpro.com/   (# https://github.com/sequelpro/sequelpro   #   ,# Host: bos6prosql01 (MySQL 5.5.51-38.2-log)   # Database: boc1651   ,# Generation Time: 2017-11-17 16:54:04 +0000   ># ************************************************************           A/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;   C/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;   A/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;   /*!40101 SET NAMES utf8 */;   S/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;   K/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;   8/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;           # Dump of table 16510010_p0007   ># ------------------------------------------------------------       &DROP TABLE IF EXISTS `16510010_p0007`;       CREATE TABLE `16510010_p0007` (   +  `recnum` int(11) NOT NULL AUTO_INCREMENT,     `dnis` tinytext NOT NULL,     `ani` tinytext NOT NULL,     `machine` tinytext NOT NULL,     `line` tinytext NOT NULL,      `startdate` tinytext NOT NULL,      `starttime` tinytext NOT NULL,     `realdate` tinytext NOT NULL,     `realtime` tinytext NOT NULL,   !  `callstatus` tinytext NOT NULL,     `callflow` tinytext NOT NULL,     `complete` tinytext NOT NULL,     `stopdate` tinytext NOT NULL,     `stoptime` tinytext NOT NULL,     `duration` tinytext NOT NULL,     `confnum` tinytext NOT NULL,     `report` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     PRIMARY KEY (`recnum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               $# Dump of table 16510010_p0007_users   ># ------------------------------------------------------------       ,DROP TABLE IF EXISTS `16510010_p0007_users`;       %CREATE TABLE `16510010_p0007_users` (   ,  `usernum` int(11) NOT NULL AUTO_INCREMENT,     `id` tinytext NOT NULL,      `firstname` tinytext NOT NULL,     `lastname` tinytext NOT NULL,     `email` tinytext NOT NULL,   !  `datepassed` tinytext NOT NULL,   #  `confirmation` tinytext NOT NULL,     `pass` tinytext NOT NULL,     `storeid` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `correct` tinytext NOT NULL,     PRIMARY KEY (`usernum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               # Dump of table 16510010_p0008   ># ------------------------------------------------------------       &DROP TABLE IF EXISTS `16510010_p0008`;       CREATE TABLE `16510010_p0008` (   +  `recnum` int(11) NOT NULL AUTO_INCREMENT,     `dnis` tinytext NOT NULL,     `ani` tinytext NOT NULL,     `machine` tinytext NOT NULL,     `line` tinytext NOT NULL,      `startdate` tinytext NOT NULL,      `starttime` tinytext NOT NULL,     `realdate` tinytext NOT NULL,     `realtime` tinytext NOT NULL,   !  `callstatus` tinytext NOT NULL,     `callflow` tinytext NOT NULL,     `complete` tinytext NOT NULL,     `stopdate` tinytext NOT NULL,     `stoptime` tinytext NOT NULL,     `duration` tinytext NOT NULL,     `confnum` tinytext NOT NULL,     `report` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     PRIMARY KEY (`recnum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               $# Dump of table 16510010_p0008_users   ># ------------------------------------------------------------       ,DROP TABLE IF EXISTS `16510010_p0008_users`;       %CREATE TABLE `16510010_p0008_users` (   ,  `usernum` int(11) NOT NULL AUTO_INCREMENT,     `id` tinytext NOT NULL,      `firstname` tinytext NOT NULL,     `lastname` tinytext NOT NULL,     `email` tinytext NOT NULL,   !  `datepassed` tinytext NOT NULL,   #  `confirmation` tinytext NOT NULL,     `pass` tinytext NOT NULL,     `storeid` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `correct` tinytext NOT NULL,     PRIMARY KEY (`usernum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               # Dump of table 16510010_p0009   ># ------------------------------------------------------------       &DROP TABLE IF EXISTS `16510010_p0009`;       CREATE TABLE `16510010_p0009` (   +  `recnum` int(11) NOT NULL AUTO_INCREMENT,     `dnis` tinytext NOT NULL,     `ani` tinytext NOT NULL,     `machine` tinytext NOT NULL,     `line` tinytext NOT NULL,      `startdate` tinytext NOT NULL,      `starttime` tinytext NOT NULL,     `realdate` tinytext NOT NULL,     `realtime` tinytext NOT NULL,   !  `callstatus` tinytext NOT NULL,     `callflow` tinytext NOT NULL,     `complete` tinytext NOT NULL,     `stopdate` tinytext NOT NULL,     `stoptime` tinytext NOT NULL,     `duration` tinytext NOT NULL,     `confnum` tinytext NOT NULL,     `report` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     `q12` tinytext NOT NULL,     `q13` tinytext NOT NULL,     `q14` tinytext NOT NULL,     `q15` tinytext NOT NULL,     `q16` tinytext NOT NULL,     `q17` tinytext NOT NULL,     `q18` tinytext NOT NULL,     `q19` tinytext NOT NULL,     `q20` tinytext NOT NULL,     `q21` tinytext NOT NULL,     `q22` tinytext NOT NULL,     `q23` tinytext NOT NULL,     `q24` tinytext NOT NULL,     `q25` tinytext NOT NULL,     PRIMARY KEY (`recnum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               $# Dump of table 16510010_p0009_users   ># ------------------------------------------------------------       ,DROP TABLE IF EXISTS `16510010_p0009_users`;       %CREATE TABLE `16510010_p0009_users` (   ,  `usernum` int(11) NOT NULL AUTO_INCREMENT,     `id` tinytext NOT NULL,      `firstname` tinytext NOT NULL,     `lastname` tinytext NOT NULL,     `email` tinytext NOT NULL,   !  `datepassed` tinytext NOT NULL,   #  `confirmation` tinytext NOT NULL,     `pass` tinytext NOT NULL,     `storeid` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     `q12` tinytext NOT NULL,     `q13` tinytext NOT NULL,     `q14` tinytext NOT NULL,     `q15` tinytext NOT NULL,     `q16` tinytext NOT NULL,     `q17` tinytext NOT NULL,     `q18` tinytext NOT NULL,     `q19` tinytext NOT NULL,     `q20` tinytext NOT NULL,     `q21` tinytext NOT NULL,     `q22` tinytext NOT NULL,     `q23` tinytext NOT NULL,     `q24` tinytext NOT NULL,     `q25` tinytext NOT NULL,     `correct` tinytext NOT NULL,     PRIMARY KEY (`usernum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               # Dump of table 16510010_p0010   ># ------------------------------------------------------------       &DROP TABLE IF EXISTS `16510010_p0010`;       CREATE TABLE `16510010_p0010` (   +  `recnum` int(11) NOT NULL AUTO_INCREMENT,     `dnis` tinytext NOT NULL,     `ani` tinytext NOT NULL,     `machine` tinytext NOT NULL,     `line` tinytext NOT NULL,      `startdate` tinytext NOT NULL,      `starttime` tinytext NOT NULL,     `realdate` tinytext NOT NULL,     `realtime` tinytext NOT NULL,   !  `callstatus` tinytext NOT NULL,     `callflow` tinytext NOT NULL,     `complete` tinytext NOT NULL,     `stopdate` tinytext NOT NULL,     `stoptime` tinytext NOT NULL,     `duration` tinytext NOT NULL,     `confnum` tinytext NOT NULL,     `report` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     PRIMARY KEY (`recnum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               $# Dump of table 16510010_p0010_users   ># ------------------------------------------------------------       ,DROP TABLE IF EXISTS `16510010_p0010_users`;       %CREATE TABLE `16510010_p0010_users` (   ,  `usernum` int(11) NOT NULL AUTO_INCREMENT,     `id` tinytext NOT NULL,      `firstname` tinytext NOT NULL,     `lastname` tinytext NOT NULL,     `email` tinytext NOT NULL,   !  `datepassed` tinytext NOT NULL,   #  `confirmation` tinytext NOT NULL,     `pass` tinytext NOT NULL,     `storeid` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     `correct` tinytext NOT NULL,     PRIMARY KEY (`usernum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               # Dump of table 16510010_p0011   ># ------------------------------------------------------------       &DROP TABLE IF EXISTS `16510010_p0011`;       CREATE TABLE `16510010_p0011` (   +  `recnum` int(11) NOT NULL AUTO_INCREMENT,     `dnis` tinytext NOT NULL,     `ani` tinytext NOT NULL,     `machine` tinytext NOT NULL,     `line` tinytext NOT NULL,      `startdate` tinytext NOT NULL,      `starttime` tinytext NOT NULL,     `realdate` tinytext NOT NULL,     `realtime` tinytext NOT NULL,   !  `callstatus` tinytext NOT NULL,     `callflow` tinytext NOT NULL,     `complete` tinytext NOT NULL,     `stopdate` tinytext NOT NULL,     `stoptime` tinytext NOT NULL,     `duration` tinytext NOT NULL,     `confnum` tinytext NOT NULL,     `report` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     `q12` tinytext NOT NULL,     `q13` tinytext NOT NULL,     PRIMARY KEY (`recnum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               $# Dump of table 16510010_p0011_users   ># ------------------------------------------------------------       ,DROP TABLE IF EXISTS `16510010_p0011_users`;       %CREATE TABLE `16510010_p0011_users` (   ,  `usernum` int(11) NOT NULL AUTO_INCREMENT,     `id` tinytext NOT NULL,      `firstname` tinytext NOT NULL,     `lastname` tinytext NOT NULL,     `email` tinytext NOT NULL,   !  `datepassed` tinytext NOT NULL,   #  `confirmation` tinytext NOT NULL,     `pass` tinytext NOT NULL,     `storeid` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     `q12` tinytext NOT NULL,     `q13` tinytext NOT NULL,     `correct` tinytext NOT NULL,     PRIMARY KEY (`usernum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               # Dump of table 16510010_p0012   ># ------------------------------------------------------------       &DROP TABLE IF EXISTS `16510010_p0012`;       CREATE TABLE `16510010_p0012` (   +  `recnum` int(11) NOT NULL AUTO_INCREMENT,     `dnis` tinytext NOT NULL,     `ani` tinytext NOT NULL,     `machine` tinytext NOT NULL,     `line` tinytext NOT NULL,      `startdate` tinytext NOT NULL,      `starttime` tinytext NOT NULL,     `realdate` tinytext NOT NULL,     `realtime` tinytext NOT NULL,   !  `callstatus` tinytext NOT NULL,     `callflow` tinytext NOT NULL,     `complete` tinytext NOT NULL,     `stopdate` tinytext NOT NULL,     `stoptime` tinytext NOT NULL,     `duration` tinytext NOT NULL,     `confnum` tinytext NOT NULL,     `report` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     PRIMARY KEY (`recnum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;               $# Dump of table 16510010_p0012_users   ># ------------------------------------------------------------       ,DROP TABLE IF EXISTS `16510010_p0012_users`;       %CREATE TABLE `16510010_p0012_users` (   ,  `usernum` int(11) NOT NULL AUTO_INCREMENT,     `id` tinytext NOT NULL,      `firstname` tinytext NOT NULL,     `lastname` tinytext NOT NULL,     `email` tinytext NOT NULL,   !  `datepassed` tinytext NOT NULL,   #  `confirmation` tinytext NOT NULL,     `pass` tinytext NOT NULL,     `storeid` tinytext NOT NULL,     `q1` tinytext NOT NULL,     `q2` tinytext NOT NULL,     `q3` tinytext NOT NULL,     `q4` tinytext NOT NULL,     `q5` tinytext NOT NULL,     `q6` tinytext NOT NULL,     `q7` tinytext NOT NULL,     `q8` tinytext NOT NULL,     `q9` tinytext NOT NULL,     `q10` tinytext NOT NULL,     `q11` tinytext NOT NULL,     `correct` tinytext NOT NULL,     PRIMARY KEY (`usernum`)   ') ENGINE=InnoDB DEFAULT CHARSET=latin1;                   )/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;   '/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;   ;/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;   ?/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;   A/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;   ?/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Z7     �        �       �        �    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z=     �        �      CREATE DATABASE ``5�_�                          ����                                                                                                                                                                                                                                                                                                                                                             ZE     �        �      CREATE DATABASE ``5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZK     �        �      CREATE DATABASE `boc1651`5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZP     �        �      USE ``5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             ZV     �        �      USE `boc1651;`5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                             ZW     �        �      USE `boc1651`�        �    5�_�   	               
          ����                                                                                                                                                                                                                                                                                                                                                             ZY    �        �      CREATE DATABASE `boc1651`;o5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z>     �        �       CREATE DATABASE `boc1651;<%  %>`5��