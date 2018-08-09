Vim�UnDo� �D��v��)1�m�$b��$��c�"P2��N�+�`   #   COLLATE='latin1_swedish_ci'                             Wj�H    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Wj�     �               ^   SCREATE DATABASE  IF NOT EXISTS `asterisk` /*!40100 DEFAULT CHARACTER SET latin1 */;   USE `asterisk`;   7-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)   --   ,-- Host: bop6atmsql02a    Database: asterisk   9-- ------------------------------------------------------   !-- Server version	5.5.40-36.1-log       A/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;   C/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;   A/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;   /*!40101 SET NAMES utf8 */;   +/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;   #/*!40103 SET TIME_ZONE='+00:00' */;   D/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;   S/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;   K/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;   8/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;       --   3-- Table structure for table `vicidial_live_agents`   --       ,DROP TABLE IF EXISTS `vicidial_live_agents`;   >/*!40101 SET @saved_cs_client     = @@character_set_client */;   ,/*!40101 SET character_set_client = utf8 */;   %CREATE TABLE `vicidial_live_agents` (   :  `live_agent_id` int(9) unsigned NOT NULL AUTO_INCREMENT,   "  `user` varchar(20) DEFAULT NULL,   #  `server_ip` varchar(15) NOT NULL,   (  `conf_exten` varchar(20) DEFAULT NULL,   (  `extension` varchar(100) DEFAULT NULL,   V  `status` enum('READY','QUEUE','INCALL','PAUSED','CLOSER','LOCKED') DEFAULT 'PAUSED',   %  `lead_id` int(9) unsigned NOT NULL,   )  `campaign_id` varchar(20) DEFAULT NULL,   &  `uniqueid` varchar(20) DEFAULT NULL,   &  `callerid` varchar(25) DEFAULT NULL,   &  `channel` varchar(100) DEFAULT NULL,   +  `random_id` int(8) unsigned DEFAULT NULL,   )  `last_call_time` datetime DEFAULT NULL,   ^  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,   +  `last_call_finish` datetime DEFAULT NULL,   1  `closer_campaigns` varchar(10000) DEFAULT NULL,   ,  `call_server_ip` varchar(15) DEFAULT NULL,   "  `user_level` int(2) DEFAULT '0',   &  `comments` varchar(20) DEFAULT NULL,   +  `campaign_weight` tinyint(1) DEFAULT '0',   1  `calls_today` smallint(5) unsigned DEFAULT '0',   *  `external_hangup` varchar(1) DEFAULT '',   *  `external_status` varchar(6) DEFAULT '',   *  `external_pause` varchar(20) DEFAULT '',   *  `external_dial` varchar(100) DEFAULT '',   0  `external_ingroups` varchar(512) DEFAULT NULL,   /  `external_blended` enum('0','1') DEFAULT '0',   1  `external_igb_set_user` varchar(20) DEFAULT '',   5  `external_update_fields` enum('0','1') DEFAULT '0',   8  `external_update_fields_data` varchar(255) DEFAULT '',   1  `external_timer_action` varchar(20) DEFAULT '',   :  `external_timer_action_message` varchar(255) DEFAULT '',   <  `external_timer_action_seconds` mediumint(7) DEFAULT '-1',   -  `agent_log_id` int(9) unsigned DEFAULT '0',   ,  `last_state_change` datetime DEFAULT NULL,   0  `agent_territories` varchar(512) DEFAULT NULL,   0  `outbound_autodial` enum('Y','N') DEFAULT 'N',   8  `manager_ingroup_set` enum('Y','N','SET') DEFAULT 'N',   !  `login_time` datetime NOT NULL,      PRIMARY KEY (`live_agent_id`),      KEY `random_id` (`random_id`),   *  KEY `last_call_time` (`last_call_time`),   .  KEY `last_update_time` (`last_update_time`),   .  KEY `last_call_finish` (`last_call_finish`),   F  KEY `idx_lead_queue` (`status`,`lead_id`,`server_ip`,`campaign_id`),     KEY `callerid` (`callerid`),   0  KEY `outbound_autodial` (`outbound_autodial`),     KEY `user` (`user`),      KEY `extension` (`extension`),   4  KEY `manager_ingroup_set` (`manager_ingroup_set`),     KEY `uniqueid` (`uniqueid`),   $  KEY `campaign_id` (`campaign_id`),     KEY `status` (`status`),   G  KEY `idx_server_ip_last_update_time` (`server_ip`,`last_update_time`)   >) ENGINE=InnoDB AUTO_INCREMENT=4550839 DEFAULT CHARSET=latin1;   8/*!40101 SET character_set_client = @saved_cs_client */;   )/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;       '/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;   ;/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;   1/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;   ?/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;   A/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;   ?/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;   )/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;       (-- Dump completed on 2015-03-05  9:49:175�_�                       *    ����                                                                                                                                                                                                                                                                                                                                                             Wj�     �         ^      ,DROP TABLE IF EXISTS `vicidial_live_agents`;5�_�                            ����                                                                                                                                                                                                                                                                                                                                       ]           v        Wj�(     �              D   %CREATE TABLE `vicidial_live_agents` (   :  `live_agent_id` int(9) unsigned NOT NULL AUTO_INCREMENT,   "  `user` varchar(20) DEFAULT NULL,   #  `server_ip` varchar(15) NOT NULL,   (  `conf_exten` varchar(20) DEFAULT NULL,   (  `extension` varchar(100) DEFAULT NULL,   V  `status` enum('READY','QUEUE','INCALL','PAUSED','CLOSER','LOCKED') DEFAULT 'PAUSED',   %  `lead_id` int(9) unsigned NOT NULL,   )  `campaign_id` varchar(20) DEFAULT NULL,   &  `uniqueid` varchar(20) DEFAULT NULL,   &  `callerid` varchar(25) DEFAULT NULL,   &  `channel` varchar(100) DEFAULT NULL,   +  `random_id` int(8) unsigned DEFAULT NULL,   )  `last_call_time` datetime DEFAULT NULL,   ^  `last_update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,   +  `last_call_finish` datetime DEFAULT NULL,   1  `closer_campaigns` varchar(10000) DEFAULT NULL,   ,  `call_server_ip` varchar(15) DEFAULT NULL,   "  `user_level` int(2) DEFAULT '0',   &  `comments` varchar(20) DEFAULT NULL,   +  `campaign_weight` tinyint(1) DEFAULT '0',   1  `calls_today` smallint(5) unsigned DEFAULT '0',   *  `external_hangup` varchar(1) DEFAULT '',   *  `external_status` varchar(6) DEFAULT '',   *  `external_pause` varchar(20) DEFAULT '',   *  `external_dial` varchar(100) DEFAULT '',   0  `external_ingroups` varchar(512) DEFAULT NULL,   /  `external_blended` enum('0','1') DEFAULT '0',   1  `external_igb_set_user` varchar(20) DEFAULT '',   5  `external_update_fields` enum('0','1') DEFAULT '0',   8  `external_update_fields_data` varchar(255) DEFAULT '',   1  `external_timer_action` varchar(20) DEFAULT '',   :  `external_timer_action_message` varchar(255) DEFAULT '',   <  `external_timer_action_seconds` mediumint(7) DEFAULT '-1',   -  `agent_log_id` int(9) unsigned DEFAULT '0',   ,  `last_state_change` datetime DEFAULT NULL,   0  `agent_territories` varchar(512) DEFAULT NULL,   0  `outbound_autodial` enum('Y','N') DEFAULT 'N',   8  `manager_ingroup_set` enum('Y','N','SET') DEFAULT 'N',   !  `login_time` datetime NOT NULL,      PRIMARY KEY (`live_agent_id`),      KEY `random_id` (`random_id`),   *  KEY `last_call_time` (`last_call_time`),   .  KEY `last_update_time` (`last_update_time`),   .  KEY `last_call_finish` (`last_call_finish`),   F  KEY `idx_lead_queue` (`status`,`lead_id`,`server_ip`,`campaign_id`),     KEY `callerid` (`callerid`),   0  KEY `outbound_autodial` (`outbound_autodial`),     KEY `user` (`user`),      KEY `extension` (`extension`),   4  KEY `manager_ingroup_set` (`manager_ingroup_set`),     KEY `uniqueid` (`uniqueid`),   $  KEY `campaign_id` (`campaign_id`),     KEY `status` (`status`),   G  KEY `idx_server_ip_last_update_time` (`server_ip`,`last_update_time`)   >) ENGINE=InnoDB AUTO_INCREMENT=4550839 DEFAULT CHARSET=latin1;   8/*!40101 SET character_set_client = @saved_cs_client */;   )/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;       '/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;   ;/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;   1/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;   ?/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;   A/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;   ?/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;   )/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;       (-- Dump completed on 2015-03-05  9:49:175�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�8     �               3-- Table structure for table `vicidial_live_agents`5�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�<     �               -- Table structure for table ``5�_�                       ,    ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�@     �                �             5�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               CREATE TABLE ``5�_�      	                 &    ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               'CREATE TABLE `vicidial_status_flags` ()5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               ``)5�_�   	              
          ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               `flag_id` INT())5�_�   
                        ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               ``)5�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               `name` VARCHAR())5�_�                       %    ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               '`name` VARCHAR(40) NOT NULL DEFAULT '')5�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               ``)5�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               `client_id` INT())5�_�                       &    ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �               (`client_id` INT(11) NOT NULL DEFAULT '')5�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �                PRIMARY KEY ())5�_�                           ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G     �                 PRIMARY KEY (``))5�_�                        	    ����                                                                                                                                                                                                                                                                                                                               1                 v       Wj�G    �          #      COLLATE='latin1_swedish_ci'�      !   "      
COLLATE=''5��