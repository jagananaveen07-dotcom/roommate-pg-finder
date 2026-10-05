-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: roommate.db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin@123');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) DEFAULT NULL,
  `pg_name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `rent` int(11) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(30) DEFAULT 'Confirmed',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (3,'vinay@gmail.com','Royal Stay PG','Chennai',8000,'2026-08-03 09:58:26','Confirmed'),(4,'vinay@gmail.com','Sunrise PG','Bangalore',7000,'2026-08-05 17:33:10','Confirmed'),(6,'vinay@gmail.com','Elite Residency','Bangalore',9000,'2026-08-06 08:22:47','Confirmed');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_images`
--

DROP TABLE IF EXISTS `pg_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pg_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pg_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` varchar(100) DEFAULT 'Room View',
  `is_primary` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `pg_id` (`pg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_images`
--

LOCK TABLES `pg_images` WRITE;
/*!40000 ALTER TABLE `pg_images` DISABLE KEYS */;
INSERT INTO `pg_images` VALUES (1,1,'Images/pgs/pg_bed_1.jpg','Room & Bed View',1),(2,1,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(3,1,'Images/pgs/pg_ext_2.jpg','Building & Common Area',0),(4,2,'Images/pgs/pg_bed_6.jpg','Room & Bed View',1),(5,2,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(6,2,'Images/pgs/pg_ext_3.jpg','Building & Common Area',0),(7,3,'Images/pgs/pg_bed_8.jpg','Room & Bed View',1),(8,3,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(9,3,'Images/pgs/pg_ext_4.jpg','Building & Common Area',0),(10,4,'Images/pgs/pg_double_1.jpg','Room & Bed View',1),(11,4,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(12,4,'Images/pgs/pg_building_1.jpg','Building & Common Area',0),(13,5,'Images/pgs/pg_single_2.jpg','Room & Bed View',1),(14,5,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(15,5,'Images/pgs/pg_living_1.jpg','Building & Common Area',0),(16,6,'Images/pgs/pg_bed_6.jpg','Room & Bed View',1),(17,6,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(18,6,'Images/pgs/pg_living_2.jpg','Building & Common Area',0),(19,7,'Images/pgs/pg_bed_indian_double.jpg','Room & Bed View',1),(20,7,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(21,7,'Images/pgs/pg_study_1.jpg','Building & Common Area',0),(22,8,'Images/pgs/pg_double_1.jpg','Room & Bed View',1),(23,8,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(24,8,'Images/pgs/pg_ext_1.jpg','Building & Common Area',0),(25,9,'Images/pgs/pg_bed_5.jpg','Room & Bed View',1),(26,9,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(27,9,'Images/pgs/pg_ext_2.jpg','Building & Common Area',0),(28,10,'Images/pgs/pg_bed_2.jpg','Room & Bed View',1),(29,10,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(30,10,'Images/pgs/pg_ext_3.jpg','Building & Common Area',0),(31,11,'Images/pgs/pg_bed_8.jpg','Room & Bed View',1),(32,11,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(33,11,'Images/pgs/pg_ext_4.jpg','Building & Common Area',0),(34,12,'Images/pgs/pg_double_1.jpg','Room & Bed View',1),(35,12,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(36,12,'Images/pgs/pg_building_1.jpg','Building & Common Area',0),(37,13,'Images/pgs/pg_bed_1.jpg','Room & Bed View',1),(38,13,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(39,13,'Images/pgs/pg_living_1.jpg','Building & Common Area',0),(40,14,'Images/pgs/pg_bed_6.jpg','Room & Bed View',1),(41,14,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(42,14,'Images/pgs/pg_living_2.jpg','Building & Common Area',0),(43,15,'Images/pgs/pg_bed_indian_double.jpg','Room & Bed View',1),(44,15,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(45,15,'Images/pgs/pg_study_1.jpg','Building & Common Area',0),(46,16,'Images/pgs/pg_single_1.jpg','Room & Bed View',1),(47,16,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(48,16,'Images/pgs/pg_ext_1.jpg','Building & Common Area',0),(49,17,'Images/pgs/pg_bed_5.jpg','Room & Bed View',1),(50,17,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(51,17,'Images/pgs/pg_ext_2.jpg','Building & Common Area',0),(52,18,'Images/pgs/pg_bed_2.jpg','Room & Bed View',1),(53,18,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(54,18,'Images/pgs/pg_ext_3.jpg','Building & Common Area',0),(55,19,'Images/pgs/pg_bed_indian_double.jpg','Room & Bed View',1),(56,19,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(57,19,'Images/pgs/pg_ext_4.jpg','Building & Common Area',0),(58,20,'Images/pgs/pg_bed_4.jpg','Room & Bed View',1),(59,20,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(60,20,'Images/pgs/pg_building_1.jpg','Building & Common Area',0),(61,21,'Images/pgs/pg_bed_1.jpg','Room & Bed View',1),(62,21,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(63,21,'Images/pgs/pg_living_1.jpg','Building & Common Area',0),(64,22,'Images/pgs/pg_bed_6.jpg','Room & Bed View',1),(65,22,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(66,22,'Images/pgs/pg_living_2.jpg','Building & Common Area',0),(67,23,'Images/pgs/pg_bed_8.jpg','Room & Bed View',1),(68,23,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(69,23,'Images/pgs/pg_study_1.jpg','Building & Common Area',0),(70,24,'Images/pgs/pg_double_1.jpg','Room & Bed View',1),(71,24,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(72,24,'Images/pgs/pg_ext_1.jpg','Building & Common Area',0),(73,25,'Images/pgs/pg_single_2.jpg','Room & Bed View',1),(74,25,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(75,25,'Images/pgs/pg_ext_2.jpg','Building & Common Area',0),(76,26,'Images/pgs/pg_bed_6.jpg','Room & Bed View',1),(77,26,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(78,26,'Images/pgs/pg_ext_3.jpg','Building & Common Area',0),(79,27,'Images/pgs/pg_bed_indian_double.jpg','Room & Bed View',1),(80,27,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(81,27,'Images/pgs/pg_ext_4.jpg','Building & Common Area',0),(82,28,'Images/pgs/pg_double_1.jpg','Room & Bed View',1),(83,28,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(84,28,'Images/pgs/pg_building_1.jpg','Building & Common Area',0),(85,29,'Images/pgs/pg_bed_5.jpg','Room & Bed View',1),(86,29,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(87,29,'Images/pgs/pg_living_1.jpg','Building & Common Area',0),(88,30,'Images/pgs/pg_bed_2.jpg','Room & Bed View',1),(89,30,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(90,30,'Images/pgs/pg_living_2.jpg','Building & Common Area',0),(91,31,'Images/pgs/pg_bed_8.jpg','Room & Bed View',1),(92,31,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(93,31,'Images/pgs/pg_study_1.jpg','Building & Common Area',0),(94,32,'Images/pgs/pg_double_1.jpg','Room & Bed View',1),(95,32,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(96,32,'Images/pgs/pg_ext_1.jpg','Building & Common Area',0),(97,33,'Images/pgs/pg_bed_1.jpg','Room & Bed View',1),(98,33,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(99,33,'Images/pgs/pg_ext_2.jpg','Building & Common Area',0),(100,34,'Images/pgs/pg_bed_6.jpg','Room & Bed View',1),(101,34,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(102,34,'Images/pgs/pg_ext_3.jpg','Building & Common Area',0),(103,35,'Images/pgs/pg_bed_indian_double.jpg','Room & Bed View',1),(104,35,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(105,35,'Images/pgs/pg_ext_4.jpg','Building & Common Area',0),(106,36,'Images/pgs/pg_single_1.jpg','Room & Bed View',1),(107,36,'Images/pgs/pg_mess_2.jpg','Dining & Mess Hall',0),(108,36,'Images/pgs/pg_building_1.jpg','Building & Common Area',0),(109,37,'Images/pgs/pg_bed_3.jpg','Room & Bed View',1),(110,37,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(111,37,'Images/pgs/pg_living_1.jpg','Building & Common Area',0),(112,38,'Images/pgs/pg_bed_2.jpg','Room & Bed View',1),(113,38,'Images/pgs/pg_mess_4.jpg','Dining & Mess Hall',0),(114,38,'Images/pgs/pg_living_2.jpg','Building & Common Area',0),(115,39,'Images/pgs/pg_bed_indian_double.jpg','Room & Bed View',1),(116,39,'Images/pgs/pg_dining_1.jpg','Dining & Mess Hall',0),(117,39,'Images/pgs/pg_study_1.jpg','Building & Common Area',0),(118,40,'Images/pgs/pg_bed_4.jpg','Room & Bed View',1),(119,40,'Images/pgs/pg_mess_1.jpg','Dining & Mess Hall',0),(120,40,'Images/pgs/pg_ext_1.jpg','Building & Common Area',0),(121,42,'Images/pgs/pg_bed_3.jpg','Room & Bed View',1),(122,42,'Images/pgs/pg_mess_3.jpg','Dining & Mess Hall',0),(123,42,'Images/pgs/pg_ext_3.jpg','Building & Common Area',0);
/*!40000 ALTER TABLE `pg_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pg_reviews`
--

DROP TABLE IF EXISTS `pg_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pg_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pg_id` int(11) NOT NULL,
  `reviewer_name` varchar(100) NOT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pg_id` (`pg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pg_reviews`
--

LOCK TABLES `pg_reviews` WRITE;
/*!40000 ALTER TABLE `pg_reviews` DISABLE KEYS */;
INSERT INTO `pg_reviews` VALUES (1,1,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(2,1,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(3,2,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(4,2,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(5,3,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(6,3,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(7,4,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(8,4,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(9,5,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(10,5,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(11,6,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(12,6,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(13,7,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(14,7,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(15,8,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(16,8,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(17,9,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(18,9,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(19,10,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(20,10,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(21,11,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(22,11,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(23,12,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(24,12,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(25,13,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(26,13,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(27,14,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(28,14,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(29,15,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(30,15,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(31,16,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(32,16,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(33,17,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(34,17,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(35,18,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(36,18,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(37,19,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(38,19,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(39,20,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(40,20,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(41,21,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(42,21,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(43,22,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(44,22,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(45,23,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(46,23,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(47,24,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(48,24,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(49,25,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(50,25,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(51,26,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(52,26,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(53,27,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(54,27,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(55,28,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(56,28,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(57,29,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(58,29,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(59,30,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(60,30,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(61,31,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(62,31,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(63,32,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(64,32,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(65,33,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(66,33,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(67,34,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(68,34,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(69,35,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(70,35,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(71,36,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(72,36,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(73,37,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(74,37,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(75,38,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(76,38,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(77,39,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18'),(78,39,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(79,40,'Rahul Sharma',5,'Very clean rooms and the 3 times food is hygienic and tastes just like home. High-speed WiFi is great for work from home.','2026-08-19 01:32:18'),(80,40,'Priya Nair',5,'Safe environment with biometric entry and CCTV. The warden and housekeeping staff are very responsive and polite.','2026-08-19 01:32:18'),(81,42,'Karthik Reddy',4,'Great location close to bus stops and tech parks. Power backup works reliably during power cuts. Value for money!','2026-08-19 01:32:18'),(82,42,'Ananya Sen',5,'Spacious rooms with proper ventilation and clean attached washrooms with hot water. Highly recommended for freshers.','2026-08-19 01:32:18');
/*!40000 ALTER TABLE `pg_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pgs`
--

DROP TABLE IF EXISTS `pgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pg_name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `rent` int(11) DEFAULT NULL,
  `sharing` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `availability` varchar(20) NOT NULL DEFAULT 'Available',
  `rating` decimal(2,1) NOT NULL DEFAULT 4.5,
  `reviews_count` int(11) NOT NULL DEFAULT 25,
  `address` varchar(255) DEFAULT '',
  `amenities` text DEFAULT NULL,
  `image1` varchar(255) DEFAULT 'Images/pgs/pg_bed_1.jpg',
  `image2` varchar(255) DEFAULT 'Images/pgs/pg_mess_1.jpg',
  `image3` varchar(255) DEFAULT 'Images/pgs/pg_ext_1.jpg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pgs`
--

LOCK TABLES `pgs` WRITE;
/*!40000 ALTER TABLE `pgs` DISABLE KEYS */;
INSERT INTO `pgs` VALUES (1,'Sunrise PG','Bangalore',7000,'2 Sharing','WiFi, Food, Laundry','Available',4.5,25,'HSR Layout Sector 2, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_1.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_ext_2.jpg'),(2,'Green Nest PG','Hyderabad',6500,'3 Sharing','AC Rooms, WiFi','Available',4.7,32,'Hitec City Phase 2, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_6.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_ext_3.jpg'),(3,'Royal Stay PG','Chennai',8000,'Single','Near Metro Station','Available',4.8,39,'Sholinganallur Junction, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_8.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_ext_4.jpg'),(4,'Happy Homes PG','Visakhapatnam',5500,'2 Sharing','Food Included','Available',4.6,46,'Gajuwaka Main Road, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_double_1.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_building_1.jpg'),(5,'Elite Residency','Bangalore',9000,'Single','Gym, WiFi','Available',4.4,53,'Whitefield ITPL Main Rd, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_single_2.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_living_1.jpg'),(6,'Comfort PG','Hyderabad',6000,'3 Sharing','Laundry, Security','Available',4.9,60,'Madhapur Near Cyber Towers, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_6.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_living_2.jpg'),(7,'Blue Sky PG','Chennai',7500,'2 Sharing','AC, Food','Available',4.5,67,'Velachery Main Road, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_indian_double.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_study_1.jpg'),(8,'Lake View PG','Visakhapatnam',5800,'2 Sharing','Near Beach','Available',4.6,74,'Beach Road Near RK Beach, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_double_1.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_ext_1.jpg'),(9,'City Comfort PG','Bangalore',7000,'3 Sharing','WiFi, Parking','Available',4.7,81,'BTM Layout 2nd Stage, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_5.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_ext_2.jpg'),(10,'Urban Nest PG','Hyderabad',7200,'Single','Attached Bathroom','Available',4.3,88,'Kukatpally Housing Board, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_2.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_ext_3.jpg'),(11,'Metro PG','Chennai',8200,'Single','Near Bus Stop','Available',4.5,95,'Adyar 2nd Main Road, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_8.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_ext_4.jpg'),(12,'Palm Residency','Visakhapatnam',6100,'2 Sharing','WiFi','Available',4.7,102,'MVP Colony Sector 3, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_double_1.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_building_1.jpg'),(13,'Golden PG','Bangalore',7600,'2 Sharing','Food, Laundry','Available',4.8,24,'Marathahalli Bridge, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_1.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_living_1.jpg'),(14,'Dream Stay','Hyderabad',6900,'3 Sharing','24x7 Security','Available',4.6,31,'Hitec City Phase 2, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_6.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_living_2.jpg'),(15,'Ocean View PG','Visakhapatnam',6700,'2 Sharing','Sea View','Available',4.4,38,'Dwaraka Nagar 2nd Lane, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_indian_double.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_study_1.jpg'),(16,'Smart Living PG','Chennai',8300,'Single','Gym','Available',4.9,45,'Perungudi OMR, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_single_1.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_ext_1.jpg'),(17,'Budget PG','Bangalore',5000,'4 Sharing','Affordable Stay','Available',4.5,52,'Electronic City Phase 1, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_5.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_ext_2.jpg'),(18,'Prime Residency','Hyderabad',8700,'Single','AC, WiFi','Available',4.6,59,'Madhapur Near Cyber Towers, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_2.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_ext_3.jpg'),(19,'Cozy Corner PG','Chennai',6400,'2 Sharing','Food Included','Available',4.7,66,'Velachery Main Road, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_indian_double.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_ext_4.jpg'),(20,'Fresh Living PG','Visakhapatnam',5900,'3 Sharing','Laundry, WiFi','Available',4.3,73,'Beach Road Near RK Beach, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_4.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_building_1.jpg'),(21,'Sunrise PG','Bangalore',7000,'2 Sharing','WiFi, Food, Laundry','Available',4.5,80,'Koramangala 4th Block, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_1.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_living_1.jpg'),(22,'Green Nest PG','Hyderabad',6500,'3 Sharing','AC Rooms, WiFi','Available',4.7,87,'Kukatpally Housing Board, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_6.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_living_2.jpg'),(23,'Royal Stay PG','Chennai',8000,'Single','Near Metro Station','Available',4.8,94,'Adyar 2nd Main Road, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_8.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_study_1.jpg'),(24,'Happy Homes PG','Visakhapatnam',5500,'2 Sharing','Food Included','Available',4.6,101,'MVP Colony Sector 3, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_double_1.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_ext_1.jpg'),(25,'Elite Residency','Bangalore',9000,'Single','Gym, WiFi','Available',4.4,23,'Indiranagar 100ft Road, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_single_2.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_ext_2.jpg'),(26,'Comfort PG','Hyderabad',6000,'3 Sharing','Laundry, Security','Available',4.9,30,'Hitec City Phase 2, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_6.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_ext_3.jpg'),(27,'Blue Sky PG','Chennai',7500,'2 Sharing','AC, Food','Available',4.5,37,'Sholinganallur Junction, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_indian_double.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_ext_4.jpg'),(28,'Lake View PG','Visakhapatnam',5800,'2 Sharing','Near Beach','Available',4.6,44,'Gajuwaka Main Road, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_double_1.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_building_1.jpg'),(29,'City Comfort PG','Bangalore',6800,'3 Sharing','WiFi, Parking','Available',4.7,51,'HSR Layout Sector 2, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_5.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_living_1.jpg'),(30,'Urban Nest PG','Hyderabad',7200,'Single','Attached Bathroom','Available',4.3,58,'Madhapur Near Cyber Towers, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_2.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_living_2.jpg'),(31,'Metro PG','Chennai',8200,'Single','Near Bus Stop','Available',4.5,65,'Velachery Main Road, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_8.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_study_1.jpg'),(32,'Palm Residency','Visakhapatnam',6100,'2 Sharing','WiFi','Available',4.7,72,'Beach Road Near RK Beach, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_double_1.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_ext_1.jpg'),(33,'Golden PG','Bangalore',7600,'2 Sharing','Food, Laundry','Available',4.8,79,'Whitefield ITPL Main Rd, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_1.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_ext_2.jpg'),(34,'Dream Stay','Hyderabad',6900,'3 Sharing','24x7 Security','Available',4.6,86,'Kukatpally Housing Board, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_6.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_ext_3.jpg'),(35,'Ocean View PG','Visakhapatnam',6700,'2 Sharing','Sea View','Available',4.4,93,'Madhurawada IT SEZ, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_indian_double.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_ext_4.jpg'),(36,'Smart Living PG','Chennai',8300,'Single','Gym','Available',4.9,100,'OMR Thoraipakkam, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_single_1.jpg','Images/pgs/pg_mess_2.jpg','Images/pgs/pg_building_1.jpg'),(37,'Budget PG','Bangalore',5000,'4 Sharing','Affordable Stay','Available',4.5,22,'BTM Layout 2nd Stage, Bangalore','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_3.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_living_1.jpg'),(38,'Prime Residency','Hyderabad',8700,'Single','AC, WiFi','Available',4.6,29,'Hitec City Phase 2, Hyderabad','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_2.jpg','Images/pgs/pg_mess_4.jpg','Images/pgs/pg_living_2.jpg'),(39,'Cozy Corner PG','Chennai',6400,'2 Sharing','Food Included','Available',4.7,36,'Sholinganallur Junction, Chennai','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_indian_double.jpg','Images/pgs/pg_dining_1.jpg','Images/pgs/pg_study_1.jpg'),(40,'Fresh Living PG','Visakhapatnam',5900,'3 Sharing','Laundry, WiFi','Available',4.3,43,'Gajuwaka Main Road, Visakhapatnam','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_4.jpg','Images/pgs/pg_mess_1.jpg','Images/pgs/pg_ext_1.jpg'),(42,'kinder joy gardens','Bengaluru',6800,'double','very peaceful atmosphere','Available',4.7,57,'BTM Layout 1st Stage, Bengaluru','3 Times South & North Indian Food, High-Speed Wi-Fi (100 Mbps), Daily Room Cleaning & Housekeeping, Attached Bathroom with 24x7 Hot Water (Geyser), Fully Automatic Washing Machine, 24x7 Power Backup & Lift, CCTV Surveillance & Biometric Access, RO Purified Drinking Water, Individual Cupboard with Lock, Study Table & Chair, 2-Wheeler Parking','Images/pgs/pg_bed_3.jpg','Images/pgs/pg_mess_3.jpg','Images/pgs/pg_ext_3.jpg');
/*!40000 ALTER TABLE `pgs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommate_requests`
--

DROP TABLE IF EXISTS `roommate_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommate_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_email` varchar(100) DEFAULT NULL,
  `receiver_email` varchar(100) DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommate_requests`
--

LOCK TABLES `roommate_requests` WRITE;
/*!40000 ALTER TABLE `roommate_requests` DISABLE KEYS */;
INSERT INTO `roommate_requests` VALUES (1,'praadeep@gmail.com','vinay@gmail.com','2026-08-03 09:10:00','Accepted'),(2,'naveen@gmail.com','vinay@gmail.com','2026-08-09 06:01:11','Accepted'),(3,'vinay@gmail.com','naveen@gmail.com','2026-08-09 06:17:49','Accepted');
/*!40000 ALTER TABLE `roommate_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roommates`
--

DROP TABLE IF EXISTS `roommates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roommates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `budget` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roommates`
--

LOCK TABLES `roommates` WRITE;
/*!40000 ALTER TABLE `roommates` DISABLE KEYS */;
INSERT INTO `roommates` VALUES (1,'vinay','vinay@gmail.com','Bangalore',10000,'Male','non-smoker,friendly'),(2,'Jagana Naveen','naveen@gmail.com','Bangalore',10000,'Male','gggoof boyyyyyyyyyyyyy');
/*!40000 ALTER TABLE `roommates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saved_pgs`
--

DROP TABLE IF EXISTS `saved_pgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saved_pgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) DEFAULT NULL,
  `pg_name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `rent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saved_pgs`
--

LOCK TABLES `saved_pgs` WRITE;
/*!40000 ALTER TABLE `saved_pgs` DISABLE KEYS */;
INSERT INTO `saved_pgs` VALUES (3,'praadeep@gmail.com','City Comfort PG','Bangalore',6800),(8,'vinay@gmail.com','Golden PG','Bangalore',7600),(9,'vinay@gmail.com','Urban Nest PG','Hyderabad',7200),(13,'vinay@gmail.com','Dream Stay','Hyderabad',6900),(14,'vinay@gmail.com','Fresh Living PG','Visakhapatnam',5900);
/*!40000 ALTER TABLE `saved_pgs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `budget` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Vinay Konathala','vinay@gmail.com','$2y$10$xas/Kg61Yu82lGGAubJEO.qNBmWy56BVS6.4JbyEj87IepYBUKE.m','Bangalore',10000,'Male','sdfghjksdfgh'),(5,'jagana naveen','naveen@gmail.com','$2y$10$RWR0o9gry2h8n9fZ0WFT.ektxC/hwQaxGENuVP8BcHEPEL9wU038G','Visakhapatnam',10000,'Male','jwihwdiwhdiwhdidqccnqnoihoihpffhpf nlfy89fhefdnvnnu');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-19  7:03:25
