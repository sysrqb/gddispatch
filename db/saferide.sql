-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: saferide
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.6

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rides`
--

LOCK TABLES `rides` WRITE;
/*!40000 ALTER TABLE `rides` DISABLE KEYS */;
INSERT INTO `rides` VALUES (1,'blah','',0,0,0,0,'','','','','0000-00-00','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',''),(2,'Matt','',0,0,0,0,'','there','','Nothing','0000-00-00','waiting','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','MM'),(3,'test2','',0,4,0,0,'5','here','','nuttin','2010-08-28','waiting','2010-08-28 05:12:24','0000-00-00 00:00:00','0000-00-00 00:00:00','Null');
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

-- Dump completed on 2010-08-28  9:52:05
