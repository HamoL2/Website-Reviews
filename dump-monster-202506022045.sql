-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: monster
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.27-MariaDB

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
-- Table structure for table `bibita`
--

DROP TABLE IF EXISTS `bibita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bibita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `tipo` int(11) NOT NULL,
  `rarita` int(11) NOT NULL,
  `prezzo_range` int(11) NOT NULL CHECK (`prezzo_range` >= 0),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`),
  KEY `tipo` (`tipo`),
  KEY `rarita` (`rarita`),
  CONSTRAINT `bibita_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `tipo` (`id`),
  CONSTRAINT `bibita_ibfk_2` FOREIGN KEY (`rarita`) REFERENCES `rarita` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bibita`
--

LOCK TABLES `bibita` WRITE;
/*!40000 ALTER TABLE `bibita` DISABLE KEYS */;
/*!40000 ALTER TABLE `bibita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rarita`
--

DROP TABLE IF EXISTS `rarita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rarita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rarita`
--

LOCK TABLES `rarita` WRITE;
/*!40000 ALTER TABLE `rarita` DISABLE KEYS */;
/*!40000 ALTER TABLE `rarita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recensione`
--

DROP TABLE IF EXISTS `recensione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recensione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(50) NOT NULL,
  `descrizione` varchar(500) NOT NULL,
  `valutazione` int(11) NOT NULL CHECK (`valutazione` between 1 and 5),
  `mi_piace` int(10) unsigned DEFAULT 0,
  `bibita_id` int(11) NOT NULL,
  `registrato_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bibita_id` (`bibita_id`),
  KEY `registrato_id` (`registrato_id`),
  CONSTRAINT `recensione_ibfk_1` FOREIGN KEY (`bibita_id`) REFERENCES `bibita` (`id`),
  CONSTRAINT `recensione_ibfk_2` FOREIGN KEY (`registrato_id`) REFERENCES `registrato` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recensione`
--

LOCK TABLES `recensione` WRITE;
/*!40000 ALTER TABLE `recensione` DISABLE KEYS */;
/*!40000 ALTER TABLE `recensione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registrato`
--

DROP TABLE IF EXISTS `registrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(25) NOT NULL,
  `data_nascita` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ruolo` varchar(20) DEFAULT NULL,
  `data_creazione` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_aggiornamento` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrato`
--

LOCK TABLES `registrato` WRITE;
/*!40000 ALTER TABLE `registrato` DISABLE KEYS */;
/*!40000 ALTER TABLE `registrato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo`
--

DROP TABLE IF EXISTS `tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo`
--

LOCK TABLES `tipo` WRITE;
/*!40000 ALTER TABLE `tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voto`
--

DROP TABLE IF EXISTS `voto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recensione_id` int(11) DEFAULT NULL,
  `utente_id` int(11) DEFAULT NULL,
  `data_voto` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `recensione_id` (`recensione_id`,`utente_id`),
  KEY `voto_ibfk_2` (`utente_id`),
  CONSTRAINT `voto_ibfk_1` FOREIGN KEY (`recensione_id`) REFERENCES `recensione` (`id`) ON DELETE CASCADE,
  CONSTRAINT `voto_ibfk_2` FOREIGN KEY (`utente_id`) REFERENCES `registrato` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voto`
--

LOCK TABLES `voto` WRITE;
/*!40000 ALTER TABLE `voto` DISABLE KEYS */;
/*!40000 ALTER TABLE `voto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'monster'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-02 20:45:30
