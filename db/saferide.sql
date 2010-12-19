-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: saferide
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.6-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contacted`
--

DROP TABLE IF EXISTS `contacted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacted` (
  `num` int(50) NOT NULL AUTO_INCREMENT,
  `carnum` int(3) NOT NULL,
  `ridedate` datetime DEFAULT '0000-00-00 00:00:00',
  `contacttime` datetime DEFAULT '0000-00-00 00:00:00',
  `reason` varchar(50) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacted`
--

LOCK TABLES `contacted` WRITE;
/*!40000 ALTER TABLE `contacted` DISABLE KEYS */;
INSERT INTO `contacted` VALUES (1,1,'2010-09-02 00:00:00','2010-09-02 22:37:58','called'),(2,1,'2010-09-02 00:00:00','2010-09-02 22:38:02','called'),(3,1,'2010-09-17 00:00:00','2010-09-17 00:15:53','called'),(4,1,'2010-09-17 00:00:00','2010-09-17 00:15:57','called'),(5,1,'2010-09-17 00:00:00','2010-09-17 00:17:56','called'),(6,1,'2010-09-17 00:00:00','2010-09-17 00:45:54','called'),(7,1,'2010-09-17 00:00:00','2010-09-17 00:52:30','called'),(8,5,'2010-11-30 00:00:00','2010-11-30 00:57:49','called'),(9,5,'2010-11-30 00:00:00','2010-11-30 04:48:21','called');
/*!40000 ALTER TABLE `contacted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rides`
--

DROP TABLE IF EXISTS `rides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rides` (
  `num` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `cell` varchar(15) NOT NULL,
  `requested` int(10) NOT NULL DEFAULT '0',
  `riders` int(10) NOT NULL DEFAULT '0',
  `precar` int(10) NOT NULL DEFAULT '0',
  `car` int(10) NOT NULL DEFAULT '0',
  `pickup` varchar(200) NOT NULL,
  `fromloc` varchar(100) DEFAULT NULL,
  `dropoff` varchar(200) NOT NULL,
  `notes` text NOT NULL,
  `clothes` varchar(200) NOT NULL,
  `ridedate` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(50) NOT NULL,
  `timetaken` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timeassigned` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `timedone` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `loc` varchar(100) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rides`
--

LOCK TABLES `rides` WRITE;
/*!40000 ALTER TABLE `rides` DISABLE KEYS */;
INSERT INTO `rides` VALUES (1,'blah','',0,0,0,0,'',NULL,'','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(2,'Matt','',0,0,0,0,'',NULL,'there','','Nothing','0000-00-00','waiting','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','MM'),(3,'test2','',0,4,0,3,'5',NULL,'here','','nuttin','2010-08-28','waiting','2010-08-28 05:12:24','2010-08-30 21:20:36','0000-00-00 00:00:00','Null'),(4,'kjdsh','',0,0,5,5,'',NULL,'','sdv','','2010-08-30','riding','2010-08-30 20:53:14','2010-08-30 21:26:26','0000-00-00 00:00:00','N'),(5,'test5','3457654326',0,0,4,5,'67',NULL,'nowhere','f','blue','2010-08-30','waiting','2010-08-30 21:14:27','2010-08-30 21:21:54','0000-00-00 00:00:00','Other'),(6,'wfvddv','3457897658',0,0,4,4,'98',NULL,'','fwa','','2010-08-31','riding','2010-08-31 00:03:12','2010-08-31 22:20:12','2010-08-31 23:08:13','Sh'),(7,'asoi','9876523451',0,0,8,8,'87',NULL,'xc','','x','2010-08-31','riding','2010-08-31 03:14:00','2010-08-31 23:07:11','2010-08-31 22:20:58','Sh'),(8,'543','3732568732',0,2,0,3,'65',NULL,'av','d      ','gevf','2010-09-01','riding','2010-09-01 01:32:23','2010-09-01 15:29:28','0000-00-00 00:00:00','Sh'),(9,'Blah','6282039261',0,2,0,2,'69',NULL,'uh huh!','dsfsc   ','you really wanna know?','2010-09-01','riding','2010-09-01 15:32:05','2010-09-02 15:37:15','0000-00-00 00:00:00','N'),(10,'Blah','6543242347',0,1,0,3,'3',NULL,'xzc ','zx          ','z x','2010-09-02','done','2010-09-02 00:11:42','2010-09-02 15:46:54','2010-09-02 19:37:12','B'),(11,'Blah','',0,0,0,0,'',NULL,'','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(12,'Blah','',0,0,0,0,'',NULL,'','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(13,'Blah','6282039261',0,0,0,0,'',NULL,'','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(14,'Blah','6282039261',0,0,0,0,'',NULL,'','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(15,'Blah','6282039261',0,0,0,2,'',NULL,'','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(16,'Blah','6282039261',0,0,0,2,'69',NULL,'','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(17,'Blah','6282039261',0,0,0,2,'69',NULL,'uh huh!','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(18,'Blah','6282039261',0,0,0,2,'69',NULL,'uh huh!','dsfsc   ','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(19,'Blah','6282039261',0,0,0,2,'69',NULL,'uh huh!','dsfsc   ','you really wanna know?','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(20,'Blah','6282039261',0,0,0,2,'69',NULL,'uh huh!','dsfsc   ','you really wanna know?','2010-09-01','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(21,'Blah','6282039261',0,0,0,2,'69',NULL,'uh huh!','dsfsc   ','you really wanna know?','2010-09-01','riding','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(22,'Blah','6282039261',0,0,0,2,'69',NULL,'uh huh!','dsfsc   ','you really wanna know?','2010-09-01','riding','2010-09-01 15:32:05','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(23,'Blah','6282039261',0,0,0,2,'69',NULL,'uh huh!','dsfsc   ','you really wanna know?','2010-09-01','riding','2010-09-01 15:32:05','0000-00-00 00:00:00','0000-00-00 00:00:00','N'),(24,'Blah','6543242347',0,2,5,5,'3',NULL,'xzc ','zx       ','z x','2010-09-02','done','2010-09-02 00:11:42','2010-09-02 15:48:47','2010-09-02 19:37:23','B'),(25,'wfg','6739850238',0,5,0,3,'54',NULL,'aefjlvkhn','v ','69','2010-09-02','done','2010-09-02 15:50:03','2010-09-02 15:50:52','2010-09-02 19:37:19','BS'),(26,'wfg','6739850238',0,49,0,1,'54',NULL,'aefjlvkhn','v ','69','2010-09-02','done','2010-09-02 15:50:03','2010-09-02 19:35:11','2010-09-02 22:14:58','BS'),(27,'iu3qgheakjnv','4499559968',0,0,0,0,'54',NULL,'sdc','cd','c','2010-09-02','cancelled','2010-09-02 15:51:22','0000-00-00 00:00:00','2010-09-02 19:35:49','MM'),(28,'','',0,1,6,6,'2',NULL,'','','','2010-09-02','done','2010-09-02 15:53:28','2010-09-02 19:37:07','2010-09-02 22:36:37','Null'),(29,'Jeremy','5555555555',0,4,5,2,'McMahon','Null','Northwest',' ','blue','2010-09-02','done','2010-09-02 15:59:40','2010-09-02 19:36:21','2010-09-02 22:36:29','NW'),(30,'new','',0,2,0,12,'2',NULL,'','','','2010-09-02','done','2010-09-02 18:25:11','2010-09-02 19:36:56','2010-09-02 21:07:51','Null'),(31,'Jeremy','5555555555',0,8,0,3,'McMahon',NULL,'Northwest','','blue','2010-09-02','done','2010-09-02 15:59:40','2010-09-02 19:36:48','2010-09-02 22:36:34','NW'),(32,'Matt','5555555555',0,1,3,1,'Russel','NW','Beldon',' ','Bakini','2010-09-03','done','2010-09-04 01:51:27','2010-09-04 01:55:02','2010-09-04 03:04:59','Al'),(33,'Matt','5555555555',0,2,3,3,'Russel','NW','Beldon','  ','Bakini','2010-09-03','done','2010-09-04 01:51:27','2010-09-04 01:55:15','2010-09-04 15:29:27','Al'),(34,'Matt','2039217325',0,3,0,0,'418 Belden','Al','121 Whitney','','Blue Shirt, Jean Shorts','2010-09-04','waiting','2010-09-04 18:55:39','0000-00-00 00:00:00','0000-00-00 00:00:00','E'),(35,'Matt','2039217325',0,3,1,1,'Belden','Al','Whitney',' ','Blue Shirt, Jean Shorts','2010-09-06','done','2010-09-05 16:38:37','2010-09-05 17:27:33','2010-09-05 19:12:25','E'),(36,'Person','numberssss',0,1,0,4,'Towers','T','Carriage','','Trees','2010-09-06','done','2010-09-05 19:13:17','2010-09-06 18:47:26','2010-09-06 18:47:38','Car'),(37,'Chris','5555555555',0,5,5,5,'5 Hunting Lodge','Other','','  ','','2010-09-06','riding','2010-09-06 18:45:47','2010-09-06 18:47:17','0000-00-00 00:00:00','GS'),(38,'Emmy','4444444444',0,10,0,2,'','HTA','','','','2010-09-06','riding','2010-09-06 18:48:31','2010-09-06 18:48:43','0000-00-00 00:00:00','HL'),(39,'Emmy','4444444444',0,10,0,1,'','HTA','','','','2010-09-06','riding','2010-09-06 18:48:31','2010-09-06 18:48:58','0000-00-00 00:00:00','HL'),(40,'Emmy','4444444444',0,24,0,3,'','HTA','','','','2010-09-06','riding','2010-09-06 18:48:31','2010-09-06 18:49:10','0000-00-00 00:00:00','HL'),(41,'test','',0,54,0,1,'ewf','Null','sdf','waf','','2010-09-17','riding','2010-09-17 00:09:39','2010-09-17 00:16:09','0000-00-00 00:00:00','Null'),(42,'svzx ','',0,65,0,5,'','Null','c','f','x','2010-09-17','riding','2010-09-17 00:52:46','2010-09-17 00:52:52','0000-00-00 00:00:00','Null'),(43,'blh','5555555555',0,3,0,1,'McMahon','MM','Busby','','Red Silk Hat','2010-10-24','riding','2010-10-24 17:19:50','2010-10-24 17:19:58','0000-00-00 00:00:00','BS'),(44,'bob','2323432554',0,4,0,5,'carriage','Null','hiolltop',' ','red cpoat','2010-11-30','waiting','2010-11-30 00:56:14','2010-11-30 00:57:14','2010-11-30 00:57:32','Null');
/*!40000 ALTER TABLE `rides` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-18 13:12:22
