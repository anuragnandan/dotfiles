Vim�UnDo� q	���G��x=�/�Y��ɮ�������   9                                   Y{�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Y{a     �               S   SCREATE DATABASE  IF NOT EXISTS `asterisk` /*!40100 DEFAULT CHARACTER SET latin1 */;   USE `asterisk`;   7-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)   --   ,-- Host: bop6atmsql02a    Database: asterisk   9-- ------------------------------------------------------   !-- Server version	5.5.40-36.1-log       A/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;   C/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;   A/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;   /*!40101 SET NAMES utf8 */;   +/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;   #/*!40103 SET TIME_ZONE='+00:00' */;   D/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;   S/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;   K/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;   8/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;       --   1-- Table structure for table `vicidial_admin_log`   --       *DROP TABLE IF EXISTS `vicidial_admin_log`;   >/*!40101 SET @saved_cs_client     = @@character_set_client */;   ,/*!40101 SET character_set_client = utf8 */;   #CREATE TABLE `vicidial_admin_log` (   9  `admin_log_id` int(9) unsigned NOT NULL AUTO_INCREMENT,   !  `event_date` datetime NOT NULL,     `user` varchar(20) NOT NULL,   $  `ip_address` varchar(15) NOT NULL,   '  `event_section` varchar(30) NOT NULL,   �  `event_type` enum('ADD','COPY','LOAD','RESET','MODIFY','DELETE','SEARCH','LOGIN','LOGOUT','CLEAR','OVERRIDE','EXPORT','OTHER') DEFAULT 'OTHER',   #  `record_id` varchar(50) NOT NULL,   %  `event_code` varchar(255) NOT NULL,     `event_sql` text,     `event_notes` text,   ,  PRIMARY KEY (`admin_log_id`,`event_date`),     KEY `user` (`user`),   (  KEY `event_section` (`event_section`),      KEY `record_id` (`record_id`),      KEY `event_dts` (`event_date`)   =) ENGINE=InnoDB AUTO_INCREMENT=1889001 DEFAULT CHARSET=latin1   1/*!50100 PARTITION BY RANGE (to_days(event_date))   =(PARTITION p201402 VALUES LESS THAN (735630) ENGINE = InnoDB,   = PARTITION p201403 VALUES LESS THAN (735658) ENGINE = InnoDB,   = PARTITION p201404 VALUES LESS THAN (735689) ENGINE = InnoDB,   = PARTITION p201405 VALUES LESS THAN (735719) ENGINE = InnoDB,   = PARTITION p201406 VALUES LESS THAN (735750) ENGINE = InnoDB,   = PARTITION p201407 VALUES LESS THAN (735780) ENGINE = InnoDB,   = PARTITION p201408 VALUES LESS THAN (735811) ENGINE = InnoDB,   = PARTITION p201409 VALUES LESS THAN (735842) ENGINE = InnoDB,   = PARTITION p201410 VALUES LESS THAN (735872) ENGINE = InnoDB,   = PARTITION p201411 VALUES LESS THAN (735903) ENGINE = InnoDB,   = PARTITION p201412 VALUES LESS THAN (735933) ENGINE = InnoDB,   = PARTITION p201501 VALUES LESS THAN (735964) ENGINE = InnoDB,   = PARTITION p201502 VALUES LESS THAN (735995) ENGINE = InnoDB,   = PARTITION p201503 VALUES LESS THAN (736023) ENGINE = InnoDB,   = PARTITION p201504 VALUES LESS THAN (736054) ENGINE = InnoDB,   = PARTITION p201505 VALUES LESS THAN (736084) ENGINE = InnoDB,   = PARTITION p201506 VALUES LESS THAN (736115) ENGINE = InnoDB,   = PARTITION p201507 VALUES LESS THAN (736145) ENGINE = InnoDB,   = PARTITION p201508 VALUES LESS THAN (736176) ENGINE = InnoDB,   = PARTITION p201509 VALUES LESS THAN (736207) ENGINE = InnoDB,   = PARTITION p201510 VALUES LESS THAN (736237) ENGINE = InnoDB,   = PARTITION p201511 VALUES LESS THAN (736268) ENGINE = InnoDB,   = PARTITION p201512 VALUES LESS THAN (736298) ENGINE = InnoDB,   = PARTITION p201601 VALUES LESS THAN (736329) ENGINE = InnoDB,   = PARTITION p201602 VALUES LESS THAN (736360) ENGINE = InnoDB,   = PARTITION p201603 VALUES LESS THAN (736389) ENGINE = InnoDB,   A PARTITION p201604 VALUES LESS THAN (736420) ENGINE = InnoDB) */;   8/*!40101 SET character_set_client = @saved_cs_client */;   )/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;       '/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;   ;/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;   1/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;   ?/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;   A/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;   ?/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;   )/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;       (-- Dump completed on 2015-03-05  9:49:105�_�                    -        ����                                                                                                                                                                                                                                                                                                                            G   <       -           v        Y{�    �   ,   .   S      =(PARTITION p201402 VALUES LESS THAN (735630) ENGINE = InnoDB,   = PARTITION p201403 VALUES LESS THAN (735658) ENGINE = InnoDB,   = PARTITION p201404 VALUES LESS THAN (735689) ENGINE = InnoDB,   = PARTITION p201405 VALUES LESS THAN (735719) ENGINE = InnoDB,   = PARTITION p201406 VALUES LESS THAN (735750) ENGINE = InnoDB,   = PARTITION p201407 VALUES LESS THAN (735780) ENGINE = InnoDB,   = PARTITION p201408 VALUES LESS THAN (735811) ENGINE = InnoDB,   = PARTITION p201409 VALUES LESS THAN (735842) ENGINE = InnoDB,   = PARTITION p201410 VALUES LESS THAN (735872) ENGINE = InnoDB,   = PARTITION p201411 VALUES LESS THAN (735903) ENGINE = InnoDB,   = PARTITION p201412 VALUES LESS THAN (735933) ENGINE = InnoDB,   = PARTITION p201501 VALUES LESS THAN (735964) ENGINE = InnoDB,   = PARTITION p201502 VALUES LESS THAN (735995) ENGINE = InnoDB,   = PARTITION p201503 VALUES LESS THAN (736023) ENGINE = InnoDB,   = PARTITION p201504 VALUES LESS THAN (736054) ENGINE = InnoDB,   = PARTITION p201505 VALUES LESS THAN (736084) ENGINE = InnoDB,   = PARTITION p201506 VALUES LESS THAN (736115) ENGINE = InnoDB,   = PARTITION p201507 VALUES LESS THAN (736145) ENGINE = InnoDB,   = PARTITION p201508 VALUES LESS THAN (736176) ENGINE = InnoDB,   = PARTITION p201509 VALUES LESS THAN (736207) ENGINE = InnoDB,   = PARTITION p201510 VALUES LESS THAN (736237) ENGINE = InnoDB,   = PARTITION p201511 VALUES LESS THAN (736268) ENGINE = InnoDB,   = PARTITION p201512 VALUES LESS THAN (736298) ENGINE = InnoDB,   = PARTITION p201601 VALUES LESS THAN (736329) ENGINE = InnoDB,   = PARTITION p201602 VALUES LESS THAN (736360) ENGINE = InnoDB,   = PARTITION p201603 VALUES LESS THAN (736389) ENGINE = InnoDB,   A PARTITION p201604 VALUES LESS THAN (736420) ENGINE = InnoDB) */;5�_�                    -        ����                                                                                                                                                                                                                                                                                                                            -   ;       -           v        Y{p     �   ,   H   S      ) */;5��