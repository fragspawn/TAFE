-- MySQL dump 10.16  Distrib 10.2.13-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: sqs
-- ------------------------------------------------------
-- Server version	10.2.13-MariaDB

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
-- Table structure for table `cohort`
--

DROP TABLE IF EXISTS `cohort`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cohort` (
  `cohort_ID` int(11) NOT NULL AUTO_INCREMENT,
  `cohort_CODE` varchar(64) NOT NULL,
  `cohort_COS` date NOT NULL,
  PRIMARY KEY (`cohort_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cohort`
--

LOCK TABLES `cohort` WRITE;
/*!40000 ALTER TABLE `cohort` DISABLE KEYS */;
INSERT INTO `cohort` VALUES (1,'webbies 2','2018-06-24');
/*!40000 ALTER TABLE `cohort` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue` (
  `queue_ID` int(11) NOT NULL AUTO_INCREMENT,
  `student_NO` bigint(20) NOT NULL,
  `queue_TITLE` varchar(256) NOT NULL,
  `queue_DESC` varchar(2048) NOT NULL,
  `queue_DATE` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`queue_ID`),
  KEY `student_NO` (`student_NO`),
  CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`student_NO`) REFERENCES `student` (`student_NO`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
INSERT INTO `queue` VALUES (10,4567,'asdfasd','asdfasd','2018-04-02 09:17:28'),(16,7890,'asdfasdf','asdfasdf','2018-04-02 09:55:49');
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `student_NO` bigint(20) NOT NULL,
  `cohort_ID` int(11) NOT NULL,
  PRIMARY KEY (`student_NO`),
  KEY `cohort_ID` (`cohort_ID`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`cohort_ID`) REFERENCES `cohort` (`cohort_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES (9103624316,1),(9105462916,1),(8320092002,1),(9070599600,1),(3105292714,1),(4102506217,1),(1103096815,1),(7102529717,1);

/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-02  9:58:35
