-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--

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
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `sublevel` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'Home','Home','./',0,0,1),(2,'Projects','Projects','p/',0,0,2),(3,'Blog','Blog','blog/index.html',0,0,3),(4,'Gallery','Gallery','gallery/',0,0,4),(5,'About','About','about_us.html',0,0,5),(6,'Active','Active','p/active/',2,1,1),(7,'Inactive','Inactive','p/inactive/',2,1,2),(8,'Images','Images','gallery/showcase.html',4,1,1),(9,'Videos','Videos','gallery/videos.html',4,1,2),(10,'Programming','Programming','p/active/programming/',6,2,1),(11,'Programming','Programming','p/inactive/programming/',7,2,1),(12,'Web/HTML5','Web/HTML5','p/active/web/',6,2,2),(13,'Web/HTML5','Web/HTML5','p/inactive/web/',7,2,2),(14,'Bash','Bash','p/active/programming/bash/',10,3,3),(15,'Perl','Perl','p/active/programming/perl/',10,3,1),(16,'Python','Python','p/active/programming/python/',10,3,2),(17,'JavaScript','JavaScript','p/active/programming/web/js/',12,3,1),(18,'NodeJS','NodeJS','p/active/programming/web/js/nodejs/',17,4,1);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menutheme`
--

DROP TABLE IF EXISTS `menutheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menutheme` (
  `theme` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menutheme`
--

LOCK TABLES `menutheme` WRITE;
/*!40000 ALTER TABLE `menutheme` DISABLE KEYS */;
INSERT INTO `menutheme` VALUES ('blacktheme');
/*!40000 ALTER TABLE `menutheme` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-11-02 13:30:47
