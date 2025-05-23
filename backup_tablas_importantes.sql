-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: herrerospro
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

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
-- Table structure for table `talleres`
--

DROP TABLE IF EXISTS `talleres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `talleres` (
  `id_taller` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `nombre_admin` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo','pendiente') DEFAULT 'pendiente',
  `activation_token` varchar(255) DEFAULT NULL,
  `tipo_plan` enum('basico','profesional','enterprise') DEFAULT 'basico',
  PRIMARY KEY (`id_taller`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talleres`
--

LOCK TABLES `talleres` WRITE;
/*!40000 ALTER TABLE `talleres` DISABLE KEYS */;
INSERT INTO `talleres` VALUES (1,'Taller Ejemplo','Pepe Andalucia','Direcci├│n Ejemplo','','1234567890','taller@ejemplo.com','logo.png','2024-12-13 21:03:18','inactivo',NULL,''),(2,'Taller El Yunque','Aparicio Herrnandez','','','3336973341','ahernandez@gmail.com',NULL,'2024-12-13 22:55:30','inactivo',NULL,'basico'),(3,'El Martillo','Pepe Andalucia','','','33 3162 0780','pepe@gmail.com',NULL,'2024-12-13 23:01:44','inactivo',NULL,'basico'),(4,'La solera Herreria Artistica','Andres Perez',NULL,'','14141414','aperez@gmail.com',NULL,'2024-12-13 23:03:51','activo',NULL,'basico'),(5,'La fragua','Jorge Mancera',NULL,'','33 3162 0780','jmancera@gmail.com',NULL,'2024-12-13 23:08:01','pendiente',NULL,'basico'),(6,'Herreria Cortez','Jesus Cortez',NULL,'','36973632','jcortez@gmail.com',NULL,'2024-12-13 23:14:20','activo',NULL,'basico'),(7,'Soldadura Solis','Carlos Solis jose',NULL,'','253614','csolisj@gmail.com',NULL,'2024-12-13 23:18:21','activo',NULL,'basico'),(8,'Herrer├¡a Vazquez','Paulo Su├írez ',NULL,'','33345882','psusrez@gmail.com',NULL,'2024-12-14 00:27:12','activo',NULL,'basico'),(9,'Taller de herreria Monreal','Arturo Monreal',NULL,'','1111222233','amonreal@gmail.com',NULL,'2024-12-15 18:39:24','pendiente',NULL,''),(10,'Taller Rojas','Paco Rojas ',NULL,'','12457869','info@tallerlimon.com',NULL,'2024-12-15 19:23:42','pendiente',NULL,''),(12,'Herrer├¡a Rubalcaba','Jos├® de Jes├║s Sanchez',NULL,'','3315289765','jjesus@gmail.com',NULL,'2024-12-15 20:55:13','pendiente',NULL,''),(13,'Taller Limon','Alejandro Limon',NULL,'','36973630','ales.limon@gmail.com',NULL,'2024-12-21 19:31:04','pendiente',NULL,''),(14,'Taller Vigotsky','Jose Vigotsky','','','362514','alejandro@tallerlimon.com',NULL,'2024-12-21 22:03:50','activo',NULL,'basico'),(15,'Antonio Taller de herreria','Antonio Basurto',NULL,'','251487','antonio@gmail.com',NULL,'2024-12-21 22:08:12','activo',NULL,''),(19,'Alejandro','tn',NULL,'','3336973341','alejandro@herrerospros.com',NULL,'2024-12-24 16:48:10','pendiente',NULL,'basico'),(20,'Taller El pollo','Jose Limon',NULL,'','3336973341','alejandro@herrerospro.com',NULL,'2024-12-24 16:54:36','pendiente',NULL,'basico'),(21,'Taller El perro','Pedro Chavez','las esperanza','','3336973341','POLIACERO_VERDE@GMAIL.COM',NULL,'2024-12-29 18:04:00','pendiente',NULL,''),(22,'Taller Cespedes','Franciscoi Cespedes','el verde','','14141414','fcespedes@gmail.com',NULL,'2024-12-29 18:12:41','pendiente',NULL,'basico'),(23,'Falcon Taller','Jorge Falcon','Donde sea lejos de aqui','','69864596','jfalcon@gmail.com',NULL,'2024-12-29 18:37:13','pendiente',NULL,''),(24,'El Barril del chavo','Alejandro jose','CARRETERA AL CASTILLO 1258, EL VERDE, CP 45694,  EL SALTO, JAL.','','14141414','elchavo@gmail.com',NULL,'2024-12-29 18:44:47','pendiente',NULL,'basico'),(25,'Taller ProFesional','Alex Limon','Carretara an Los logos del sur #25 ','','3334588268','limon@herrerospro.com',NULL,'2025-01-18 22:27:04','activo',NULL,''),(30,'HerreriaModerna Test','JUAN Prueba Test','Av. Test 123, Col. Prueba',NULL,'3334588268','herrerospro.dev@gmail.com',NULL,'2025-03-14 16:24:30','pendiente',NULL,'enterprise');
/*!40000 ALTER TABLE `talleres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes_contacto`
--

DROP TABLE IF EXISTS `mensajes_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `asunto` varchar(200) NOT NULL,
  `mensaje` text NOT NULL,
  `ip_remitente` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('nuevo','leido','respondido') DEFAULT 'nuevo',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes_contacto`
--

LOCK TABLES `mensajes_contacto` WRITE;
/*!40000 ALTER TABLE `mensajes_contacto` DISABLE KEYS */;
INSERT INTO `mensajes_contacto` VALUES (1,'Alejandro','ales.limon@gmail.com','','awopebnfopqw ef qweofnqwe fqwer f',NULL,NULL,'2024-12-25 00:06:33','nuevo','2024-12-25 00:06:33'),(2,'Alejandro','alejandro@tallerlimon.com','',' srf r fw r g e4gw45gy45 g54tg4w 5 yg 4g  \r\n 4',NULL,NULL,'2024-12-25 00:10:35','nuevo','2024-12-25 00:10:35'),(3,'Pedro ','alejandro@herrerospro.com','','Hola espero te encuentres bien ',NULL,NULL,'2024-12-25 00:16:02','nuevo','2024-12-25 00:16:02'),(4,'Alejandro','p.valeria.limon@gmail.com','','werrgverg',NULL,NULL,'2024-12-25 00:18:16','nuevo','2024-12-25 00:18:16'),(5,'rulas','ales.limon@gmail.com','','4rfg4',NULL,NULL,'2024-12-25 00:19:57','nuevo','2024-12-25 00:19:57'),(6,'Pepe','POLIACERO_VERDE@GMAIL.COM','','dadgqwe5yh 5y',NULL,NULL,'2024-12-25 00:22:50','nuevo','2024-12-25 00:22:50'),(7,'Alejandro','ales.limon@gmail.com','','45h ',NULL,NULL,'2024-12-25 00:25:47','nuevo','2024-12-25 00:25:47'),(8,'34','alejandro@tallerlimon.com','','34tg ',NULL,NULL,'2024-12-25 00:26:18','nuevo','2024-12-25 00:26:18'),(9,'Alejandro','ales.limon@gmail.com','','qerger ewrgerg ergwerg',NULL,NULL,'2024-12-25 00:30:36','nuevo','2024-12-25 00:30:36'),(10,'Alejandro','alejandro@herrerospro.com','','32rf 45g4g45 34g4 ',NULL,NULL,'2024-12-25 00:31:20','nuevo','2024-12-25 00:31:20'),(11,'Alejandro','ales.limon@gmail.com','Otro','Mensaje nuevo 1 de prueba','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','2025-03-13 23:59:04','nuevo','2025-03-13 23:59:04'),(12,'Pedro','ales.limon@gmail.com','Ventas','Cuanto cuesta','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','2025-03-14 00:38:08','nuevo','2025-03-14 00:38:08');
/*!40000 ALTER TABLE `mensajes_contacto` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-15 10:51:30
