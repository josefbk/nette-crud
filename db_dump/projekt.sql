-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `projekt`;
CREATE TABLE `projekt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev_projektu` varchar(300) COLLATE utf8mb4_czech_ci NOT NULL,
  `datum_odevzdani_projektu` date NOT NULL,
  `typ_projektu` int(11) NOT NULL,
  `webovy_projekt` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;


-- 2017-08-20 17:41:30
