-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: crp_03
-- ------------------------------------------------------
-- Server version	5.7.25-log

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
-- Table structure for table `tx_tweprivacy_domain_model_type`
--

DROP TABLE IF EXISTS `tx_tweprivacy_domain_model_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_tweprivacy_domain_model_type` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3ver_count` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_move_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `needs_consent` smallint(5) unsigned DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tx_tweprivacy_domain_model_type`
--

LOCK TABLES `tx_tweprivacy_domain_model_type` WRITE;
/*!40000 ALTER TABLE `tx_tweprivacy_domain_model_type` DISABLE KEYS */;
INSERT INTO `tx_tweprivacy_domain_model_type` VALUES (1,1,1581668678,1581668678,1,0,0,256,0,0,NULL,_binary 'a:3:{s:16:\"sys_language_uid\";N;s:6:\"hidden\";N;s:5:\"title\";N;}',0,0,0,0,0,0,0,'Functional Cookies',0),(2,1,1581669393,1581668689,1,0,0,512,0,0,NULL,_binary 'a:4:{s:16:\"sys_language_uid\";N;s:6:\"hidden\";N;s:5:\"title\";N;s:13:\"needs_consent\";N;}',0,0,0,0,0,0,0,'Presentational Cookies',1),(3,1,1581669403,1581668708,1,0,0,768,0,0,NULL,_binary 'a:4:{s:16:\"sys_language_uid\";N;s:6:\"hidden\";N;s:5:\"title\";N;s:13:\"needs_consent\";N;}',0,0,0,0,0,0,0,'Marketing Cookies',1);
/*!40000 ALTER TABLE `tx_tweprivacy_domain_model_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tx_tweprivacy_domain_model_subject`
--

DROP TABLE IF EXISTS `tx_tweprivacy_domain_model_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_tweprivacy_domain_model_subject` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3ver_count` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_move_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` int(10) unsigned DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tx_tweprivacy_domain_model_subject`
--

LOCK TABLES `tx_tweprivacy_domain_model_subject` WRITE;
/*!40000 ALTER TABLE `tx_tweprivacy_domain_model_subject` DISABLE KEYS */;
INSERT INTO `tx_tweprivacy_domain_model_subject` VALUES (1,1,1581670129,1581669674,1,0,0,256,0,0,NULL,_binary 'a:7:{s:16:\"sys_language_uid\";N;s:6:\"hidden\";N;s:5:\"title\";N;s:4:\"type\";N;s:4:\"name\";N;s:10:\"identifier\";N;s:11:\"description\";N;}',0,0,0,0,0,0,0,'ePrivacy Consent','eprivacy.consent','<p>Used to store the selection of ePrivacy subjects (e.g. cookies) the user has given their consent to.</p>',1,'eprivacy_consent');
/*!40000 ALTER TABLE `tx_tweprivacy_domain_model_subject` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-14  9:55:37
