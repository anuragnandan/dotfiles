Vim�UnDo� ��9�G�=�-|����UX�u'�����P8S^��D�   0                                  W�n�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             W�n)     �               .   SCREATE DATABASE  IF NOT EXISTS `asterisk` /*!40100 DEFAULT CHARACTER SET latin1 */;   USE `asterisk`;   7-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)   --   ,-- Host: bop6atmsql02a    Database: asterisk   9-- ------------------------------------------------------   !-- Server version	5.5.40-36.1-log       A/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;   C/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;   A/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;   /*!40101 SET NAMES utf8 */;   +/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;   #/*!40103 SET TIME_ZONE='+00:00' */;   D/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;   S/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;   K/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;   8/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;       --   7-- Table structure for table `vicidial_scheduler_queue`   --       0DROP TABLE IF EXISTS `vicidial_scheduler_queue`;   >/*!40101 SET @saved_cs_client     = @@character_set_client */;   ,/*!40101 SET character_set_client = utf8 */;   )CREATE TABLE `vicidial_scheduler_queue` (   +  `recnum` int(11) NOT NULL AUTO_INCREMENT,   !  `schedule_id` int(11) NOT NULL,   ?  `starttime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',   Y  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,   (  `deleted` int(1) unsigned DEFAULT '0',     PRIMARY KEY (`recnum`)   =) ENGINE=InnoDB AUTO_INCREMENT=945127 DEFAULT CHARSET=latin1;   8/*!40101 SET character_set_client = @saved_cs_client */;   )/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;       '/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;   ;/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;   1/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;   ?/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;   A/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;   ?/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;   )/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;       (-- Dump completed on 2015-03-05  9:49:265�_�                             ����                                                                                                                                                                                                                                                                                                                                                             W�n3     �       "   /        �       "   .    5�_�                    !       ����                                                                                                                                                                                                                                                                                                                                                             W�n6     �       "   /        ``5�_�                    !   	    ����                                                                                                                                                                                                                                                                                                                                                             W�n:     �       "   /      	  `owner`5�_�                    !       ����                                                                                                                                                                                                                                                                                                                                                             W�nB     �       "   /        `owner` VARCHAR()5�_�                    !       ����                                                                                                                                                                                                                                                                                                                                                             W�nC     �       "   /        `owner` VARCHAR(50)5�_�                    !   !    ����                                                                                                                                                                                                                                                                                                                                                             W�nO     �       "   /      #  `owner` VARCHAR(50) DEFAULR NULL,5�_�      	              !       ����                                                                                                                                                                                                                                                                                                                                                             W�nT    �       "   /      #  `owner` VARCHAR(50) DEFAULR NULL,5�_�      
           	   "       ����                                                                                                                                                                                                                                                                                                                                                             W�n�     �   !   $   /        PRIMARY KEY (`recnum`)5�_�   	              
   #       ����                                                                                                                                                                                                                                                                                                                                                             W�n�     �   "   $   0      	   KEY ``5�_�   
                 #       ����                                                                                                                                                                                                                                                                                                                                                             W�n�     �   "   $   0         KEY `idx_deleted` ()5�_�                    #       ����                                                                                                                                                                                                                                                                                                                                                             W�n�     �   "   %   0         KEY `idx_deleted` (``)5�_�                     $        ����                                                                                                                                                                                                                                                                                                                                                             W�n�    �   #   $           5��