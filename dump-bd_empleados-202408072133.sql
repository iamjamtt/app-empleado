-- MySQL dump 10.13  Distrib 8.2.0, for macos13 (arm64)
--
-- Host: localhost    Database: bd_empleados
-- ------------------------------------------------------
-- Server version	8.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Area`
--

DROP TABLE IF EXISTS `Area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Area` (
  `AreaID` int NOT NULL AUTO_INCREMENT,
  `AreaNombre` varchar(100) NOT NULL,
  `AreaSalarioBase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`AreaID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Area`
--

LOCK TABLES `Area` WRITE;
/*!40000 ALTER TABLE `Area` DISABLE KEYS */;
INSERT INTO `Area` VALUES (1,'Área de Informática',1500.00),(2,'Área Productiva',1800.00),(3,'Área de Marketing',2000.00),(4,'Área de Contabilidad',2200.00);
/*!40000 ALTER TABLE `Area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Empleado`
--

DROP TABLE IF EXISTS `Empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Empleado` (
  `EmpID` int NOT NULL AUTO_INCREMENT,
  `EmpCodigo` varchar(8) NOT NULL,
  `EmpDNI` varchar(8) NOT NULL,
  `EmpNombres` varchar(100) NOT NULL,
  `EmpApellidoPaterno` varchar(100) NOT NULL,
  `EmpApellidoMaterno` varchar(100) NOT NULL,
  `GeneroID` int DEFAULT NULL,
  `AreaID` int DEFAULT NULL,
  `ModalidadID` int DEFAULT NULL,
  `JornadaID` int DEFAULT NULL,
  `EmpFechaInicio` date NOT NULL,
  `EmpFechaNacimiento` date NOT NULL,
  `EmpFechaIngreso` date NOT NULL,
  `EmpCorreoElectronico` varchar(100) NOT NULL,
  PRIMARY KEY (`EmpID`),
  KEY `GeneroID` (`GeneroID`),
  KEY `AreaID` (`AreaID`),
  KEY `ModalidadID` (`ModalidadID`),
  KEY `JornadaID` (`JornadaID`),
  CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`GeneroID`) REFERENCES `Genero` (`GeneroID`),
  CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`AreaID`) REFERENCES `Area` (`AreaID`),
  CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`ModalidadID`) REFERENCES `ModalidadContrato` (`ModalidadID`),
  CONSTRAINT `empleado_ibfk_4` FOREIGN KEY (`JornadaID`) REFERENCES `JornadaLaboral` (`JornadaID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Empleado`
--

LOCK TABLES `Empleado` WRITE;
/*!40000 ALTER TABLE `Empleado` DISABLE KEYS */;
INSERT INTO `Empleado` VALUES (2,'EMP002','72155069','Jamt Americo','Mendoza','Flores',1,3,1,1,'2022-06-20','2000-10-24','2022-06-22','mendoza_jamt@app.empleado.com'),(3,'EMP004','76441899','Jim','Maldonado','Maynas',1,2,1,2,'2011-01-01','1999-09-06','2011-01-01','maldonado_jim@app.empleado.com'),(4,'EMP005','72155068','Valentino Americo','Mendoza','Flores',1,4,1,2,'2020-01-01','2004-09-28','2020-01-01','mendoza_valentino@app.empleado.com');
/*!40000 ALTER TABLE `Empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Genero`
--

DROP TABLE IF EXISTS `Genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Genero` (
  `GeneroID` int NOT NULL AUTO_INCREMENT,
  `GeneroNombre` varchar(50) NOT NULL,
  PRIMARY KEY (`GeneroID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Genero`
--

LOCK TABLES `Genero` WRITE;
/*!40000 ALTER TABLE `Genero` DISABLE KEYS */;
INSERT INTO `Genero` VALUES (1,'Masculino'),(2,'Femenino');
/*!40000 ALTER TABLE `Genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `JornadaLaboral`
--

DROP TABLE IF EXISTS `JornadaLaboral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `JornadaLaboral` (
  `JornadaID` int NOT NULL AUTO_INCREMENT,
  `JornadaNombre` varchar(50) NOT NULL,
  PRIMARY KEY (`JornadaID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `JornadaLaboral`
--

LOCK TABLES `JornadaLaboral` WRITE;
/*!40000 ALTER TABLE `JornadaLaboral` DISABLE KEYS */;
INSERT INTO `JornadaLaboral` VALUES (1,'TC'),(2,'TP');
/*!40000 ALTER TABLE `JornadaLaboral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ModalidadContrato`
--

DROP TABLE IF EXISTS `ModalidadContrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ModalidadContrato` (
  `ModalidadID` int NOT NULL AUTO_INCREMENT,
  `ModalidadNombre` varchar(50) NOT NULL,
  PRIMARY KEY (`ModalidadID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ModalidadContrato`
--

LOCK TABLES `ModalidadContrato` WRITE;
/*!40000 ALTER TABLE `ModalidadContrato` DISABLE KEYS */;
INSERT INTO `ModalidadContrato` VALUES (1,'Plazo Indeterminado');
/*!40000 ALTER TABLE `ModalidadContrato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Operacion`
--

DROP TABLE IF EXISTS `Operacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Operacion` (
  `OperacionID` int NOT NULL AUTO_INCREMENT,
  `EmpID` int DEFAULT NULL,
  `OperacionBeneficios` varchar(255) DEFAULT NULL,
  `OperacionMontoBeneficios` decimal(10,2) DEFAULT NULL,
  `OperacionMesesBeneficios` varchar(255) DEFAULT NULL,
  `OperacionBonoProductividad` decimal(10,2) DEFAULT NULL,
  `OperacionMesAsignacionBono` int DEFAULT NULL,
  PRIMARY KEY (`OperacionID`),
  KEY `EmpID` (`EmpID`),
  CONSTRAINT `operacion_ibfk_1` FOREIGN KEY (`EmpID`) REFERENCES `Empleado` (`EmpID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Operacion`
--

LOCK TABLES `Operacion` WRITE;
/*!40000 ALTER TABLE `Operacion` DISABLE KEYS */;
INSERT INTO `Operacion` VALUES (1,2,'Gratificación de Julio y Diciembre',500.00,'\"[\\\"7\\\",\\\"12\\\"]\"',200.00,7),(2,4,'Gratificación de Julio y Diciembre',500.00,'\"[\\\"7\\\",\\\"12\\\"]\"',200.00,5),(3,3,'Vacaciones',300.00,'\"[\\\"7\\\",\\\"8\\\"]\"',NULL,NULL);
/*!40000 ALTER TABLE `Operacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'bd_empleados'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-07 21:33:38
