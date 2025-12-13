-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: dlms
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account` (
  `AccountID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  PRIMARY KEY (`AccountID`),
  UNIQUE KEY `EmployeeID` (`EmployeeID`),
  UNIQUE KEY `Username` (`Username`),
  KEY `account_ibfk_2` (`RoleID`),
  CONSTRAINT `account_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`),
  CONSTRAINT `account_ibfk_2` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1,'ahmad','123456',420656035,2);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custlic`
--

DROP TABLE IF EXISTS `custlic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custlic` (
  `CustLicID` int(11) NOT NULL AUTO_INCREMENT,
  `CustID` varchar(20) NOT NULL,
  `LicenseNumber` int(11) NOT NULL,
  `LTID` int(11) NOT NULL,
  `FirstIssueDate` date NOT NULL,
  `ExpireDate` date NOT NULL,
  PRIMARY KEY (`CustLicID`),
  UNIQUE KEY `LicenseNumber` (`LicenseNumber`),
  UNIQUE KEY `unique_custid` (`CustID`),
  KEY `custlic_ibfk_3` (`LTID`),
  CONSTRAINT `custlic_ibfk_1` FOREIGN KEY (`CustID`) REFERENCES `customer` (`CustIDNo`),
  CONSTRAINT `custlic_ibfk_2` FOREIGN KEY (`LicenseNumber`) REFERENCES `license` (`LicenseNumber`),
  CONSTRAINT `custlic_ibfk_3` FOREIGN KEY (`LTID`) REFERENCES `licensetype` (`LTID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custlic`
--

LOCK TABLES `custlic` WRITE;
/*!40000 ALTER TABLE `custlic` DISABLE KEYS */;
INSERT INTO `custlic` VALUES (8,'420656035',43737389,1,'2025-12-09','2028-12-09'),(9,'420657223',80732444,1,'2025-12-09','2030-12-09'),(10,'420258159',44163016,2,'2025-12-10','2030-12-10'),(11,'420663379',95907043,2,'2025-12-11','2030-12-11'),(12,'420660003',20212259,2,'2025-12-13','2030-12-13');
/*!40000 ALTER TABLE `custlic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `CustIDNo` varchar(20) NOT NULL,
  `FName` varchar(100) NOT NULL,
  `SName` varchar(100) NOT NULL,
  `ThName` varchar(100) DEFAULT NULL,
  `LName` varchar(100) NOT NULL,
  `BirthDate` date NOT NULL,
  `BloodGroup` varchar(5) NOT NULL,
  `Address` varchar(255) NOT NULL,
  PRIMARY KEY (`CustIDNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES ('420258159','Mohammed','Hazem','Mohammed','Hussein','2004-10-13','O+','Tulkarem Baqa Ash-Sharqiyah'),('420656035','Ahmad','Ashraf','Mahmoud','Hussein','2005-02-04','B-','Tulkarem Baqa Ash-Sharqiyah'),('420657223','Ahmad','Hasan','Mohammed','Jaber','2005-02-12','O+','Tulkarem Baqa Ash-Sharqiyah'),('420660003','Malik','Jehad','Ahmad','Haj Ibraheem','2005-02-23','O+','Tulkarem Nazlet Issa'),('420663379','Ali','Samer','Rasmi','Jaber','2005-06-09','O+','Tulkarem Baqa Ash-Sharqiyah');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email` (
  `EmailID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  PRIMARY KEY (`EmailID`),
  KEY `email_ibfk_1` (`EmployeeID`),
  CONSTRAINT `email_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email`
--

LOCK TABLES `email` WRITE;
/*!40000 ALTER TABLE `email` DISABLE KEYS */;
INSERT INTO `email` VALUES (1,'ahmad.husien4@gmail.com',420656035);
/*!40000 ALTER TABLE `email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `SecondName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  PRIMARY KEY (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (420656035,'Ahmad','Ashraf','Hussein');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `license`
--

DROP TABLE IF EXISTS `license`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `license` (
  `LicenseNumber` int(11) NOT NULL,
  `IssueDate` date NOT NULL,
  PRIMARY KEY (`LicenseNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `license`
--

LOCK TABLES `license` WRITE;
/*!40000 ALTER TABLE `license` DISABLE KEYS */;
INSERT INTO `license` VALUES (20212259,'2025-12-13'),(43737389,'2025-12-13'),(44163016,'2025-12-10'),(80732444,'2025-12-09'),(95907043,'2025-12-11');
/*!40000 ALTER TABLE `license` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licensetype`
--

DROP TABLE IF EXISTS `licensetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `licensetype` (
  `LTID` int(11) NOT NULL AUTO_INCREMENT,
  `LTName` varchar(100) NOT NULL,
  `LTDescription` text DEFAULT NULL,
  PRIMARY KEY (`LTID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licensetype`
--

LOCK TABLES `licensetype` WRITE;
/*!40000 ALTER TABLE `licensetype` DISABLE KEYS */;
INSERT INTO `licensetype` VALUES (1,'A','A – for two-wheeled vehicles, with horse power limits set at: A2 – engine capacity up to 125 cc and 14.6 HP, A1 – 47.46 HP, A – no limit'),(2,'B','B – for passenger vehicles up to 3.5 tons and up to 8 passengers not including driver'),(3,'C','C for trucks up to 12 tons and no tonnage limit.'),(4,'D','D – for large passenger vehicles.'),(5,'E','E – for commercial motor vehicles, up to 3.5 tons (including attachments)'),(6,'1','1 – for tractors (not included in EU standards)\n');
/*!40000 ALTER TABLE `licensetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licenseupdate`
--

DROP TABLE IF EXISTS `licenseupdate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `licenseupdate` (
  `LicenseNumber` int(11) NOT NULL,
  `UpdateID` int(11) NOT NULL,
  `LTID` int(11) DEFAULT NULL,
  `IssueDate` date NOT NULL,
  `ExpireDate` date NOT NULL,
  PRIMARY KEY (`LicenseNumber`,`UpdateID`),
  KEY `licenseupdate_ibfk_2` (`LTID`),
  CONSTRAINT `licenseupdate_ibfk_1` FOREIGN KEY (`LicenseNumber`) REFERENCES `license` (`LicenseNumber`),
  CONSTRAINT `licenseupdate_ibfk_2` FOREIGN KEY (`LTID`) REFERENCES `licensetype` (`LTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licenseupdate`
--

LOCK TABLES `licenseupdate` WRITE;
/*!40000 ALTER TABLE `licenseupdate` DISABLE KEYS */;
INSERT INTO `licenseupdate` VALUES (43737389,1,1,'2025-12-09','2030-12-09'),(43737389,2,1,'2025-12-09','2025-12-09'),(43737389,3,1,'2025-12-09','2026-12-09');
/*!40000 ALTER TABLE `licenseupdate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phoneno`
--

DROP TABLE IF EXISTS `phoneno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `phoneno` (
  `PhoneID` int(11) NOT NULL AUTO_INCREMENT,
  `PhoneNo` varchar(20) NOT NULL,
  `CustID` varchar(20) NOT NULL,
  PRIMARY KEY (`PhoneID`),
  UNIQUE KEY `PhoneNo` (`PhoneNo`),
  KEY `phoneno_ibfk_1` (`CustID`),
  CONSTRAINT `phoneno_ibfk_1` FOREIGN KEY (`CustID`) REFERENCES `customer` (`CustIDNo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phoneno`
--

LOCK TABLES `phoneno` WRITE;
/*!40000 ALTER TABLE `phoneno` DISABLE KEYS */;
INSERT INTO `phoneno` VALUES (1,'0569276122','420656035'),(2,'0537313532','420656035'),(3,'0569593081','420657223'),(4,'0594631076','420258159'),(5,'0566123596','420663379'),(6,'0599397532','420660003');
/*!40000 ALTER TABLE `phoneno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `RoleID` int(11) NOT NULL AUTO_INCREMENT,
  `RoleName` varchar(100) NOT NULL,
  PRIMARY KEY (`RoleID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Normal'),(2,'Admin');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test` (
  `TestID` int(11) NOT NULL AUTO_INCREMENT,
  `Grade` int(11) NOT NULL,
  `TestType` varchar(100) NOT NULL,
  `CustomerID` varchar(20) NOT NULL,
  `LTID` int(11) NOT NULL,
  PRIMARY KEY (`TestID`),
  KEY `test_ibfk_1` (`CustomerID`),
  KEY `test_ibfk_2` (`LTID`),
  CONSTRAINT `test_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustIDNo`),
  CONSTRAINT `test_ibfk_2` FOREIGN KEY (`LTID`) REFERENCES `licensetype` (`LTID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` VALUES (1,28,'Theory','420656035',1),(2,26,'Practical','420656035',1),(3,27,'Theory','420657223',1),(4,26,'Practical','420657223',1),(5,26,'Theory','420258159',2),(6,27,'Practical','420258159',2),(7,28,'Theory','420663379',2),(8,26,'Practical','420663379',2),(9,26,'Theory','420660003',2),(10,27,'Practical','420660003',2);
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-13 20:05:20
