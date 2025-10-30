-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: iqm
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `question` (
  `idquestion` int NOT NULL AUTO_INCREMENT,
  `statement` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `tip` varchar(150) NOT NULL,
  `options` varchar(255) DEFAULT NULL,
  `black-list` varchar(1) DEFAULT NULL,
  `idsubject` int unsigned NOT NULL,
  PRIMARY KEY (`idquestion`),
  UNIQUE KEY `idquestion_UNIQUE` (`idquestion`),
  UNIQUE KEY `statement_UNIQUE` (`statement`),
  KEY `fk_question_subject_idx` (`idsubject`),
  CONSTRAINT `fk_question_subject` FOREIGN KEY (`idsubject`) REFERENCES `subject` (`idsubject`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (1,'Qual o resultado do produto interno de dois vetores ortogonais?','0','Produto Interno','Vetores perpendiculares.','1, -1, 0, A norma do primeiro vetor','0',1),(2,'Qual a unidade de medida da resistência elétrica?','Ohm','Lei de Ohm','É a letra grega ômega (Ω).','Volt, Ampère, Watt, Ohm','0',2),(3,'Qual lei afirma que a soma das correntes que entram em um nó é igual à soma das correntes que saem?','Lei de Kirchhoff das Correntes (LKC)','Análise de Nodos','Baseada na conservação de carga.','Lei de Ohm, Lei de Kirchhoff das Tensões, Lei de Kirchhoff das Correntes (LKC), Teorema de Thévenin','0',2),(4,'Qual transformada é usada para analisar sistemas LTI contínuos no tempo?','Transformada de Laplace','Análise de Sistemas','É a análoga da Transformada Z para tempo discreto.','Transformada de Fourier, Transformada Z, Transformada de Laplace, Wavelet','0',3),(5,'O que representa o termo PM (Phase Margin) no diagrama de Bode?','Margem de Fase','Estabilidade','Mede a estabilidade do sistema em relação à fase.','Ponto de Máximo, Margem de Ganho, Margem de Fase, Erro em Regime','0',4),(6,'Em um sistema de controle, o que é o \"overshoot\"?','Pico máximo da resposta além do valor final','Resposta Transitória','Acontece em sistemas subamortecidos.','Tempo de Acomodação, Pico máximo da resposta além do valor final, Erro de Regime, Frequência Natural','0',4),(7,'Qual componente semicondutor permite a passagem de corrente em apenas uma direção?','Diodo','Componentes Básicos','É usado em retificadores.','Transistor, Resistor, Diodo, Capacitor','0',5),(8,'Qual é a principal aplicação do Transistor BJT (Bipolar Junction Transistor)?','Amplificação e Chaveamento','Transistores','Pode operar nas regiões de corte, saturação ou ativa.','Regulagem de Tensão, Filtros, Medição de Temperatura, Amplificação e Chaveamento','0',5),(9,'Qual porta lógica executa a negação de uma AND?','NAND','Portas Lógicas','É a combinação de NOT e AND.','OR, NOR, XOR, NAND','0',6);
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-30 15:13:59
