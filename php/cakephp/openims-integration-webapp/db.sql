-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.24-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4208
-- Date/time:                    2012-12-04 10:26:42
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table openims.parameters
CREATE TABLE IF NOT EXISTS `parameters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utility_function_id` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `default` varchar(45) DEFAULT NULL COMMENT 'domyslna wartosc parametru',
  PRIMARY KEY (`id`,`utility_function_id`),
  KEY `fk_parameters_functions_idx` (`utility_function_id`),
  CONSTRAINT `fk_parameters_functions` FOREIGN KEY (`utility_function_id`) REFERENCES `utility_functions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- Dumping data for table openims.parameters: ~5 rows (approximately)
/*!40000 ALTER TABLE `parameters` DISABLE KEYS */;
INSERT INTO `parameters` (`id`, `utility_function_id`, `name`, `default`) VALUES
	(1, 2, 'bitrate_scale', '69'),
	(2, 2, 'delay_scale', '88'),
	(3, 6, 'bitrate', '20'),
	(11, 4, 'bitrate9', '3'),
	(23, 4, 'bitrate', '');
/*!40000 ALTER TABLE `parameters` ENABLE KEYS */;


-- Dumping structure for table openims.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `submitter_id` int(10) NOT NULL COMMENT 'ID zglaszajacego',
  `user_id` int(10) NOT NULL COMMENT 'ID klienta',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'ID klienta',
  `session_id` varchar(50) NOT NULL,
  `amount` decimal(9,2) NOT NULL COMMENT 'naliczona kwota',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `submitter_id` (`submitter_id`),
  CONSTRAINT `FK_payments_users` FOREIGN KEY (`submitter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_payments_users_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table openims.payments: ~0 rows (approximately)
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;


-- Dumping structure for table openims.protocols
CREATE TABLE IF NOT EXISTS `protocols` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'klasa ruchu moze miec przypisana jedna dowolna funkcje',
  `name` varchar(45) DEFAULT NULL COMMENT 'np. TCP, UDP, skype',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table openims.protocols: ~7 rows (approximately)
/*!40000 ALTER TABLE `protocols` DISABLE KEYS */;
INSERT INTO `protocols` (`id`, `name`) VALUES
	(3, 'skype'),
	(4, 'http'),
	(5, 'ftp'),
	(6, 'ssh'),
	(7, 'tcp'),
	(9, 'torrent'),
	(10, 'USENET');
/*!40000 ALTER TABLE `protocols` ENABLE KEYS */;


-- Dumping structure for table openims.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'tabela sluzaca do logowania sie w systemie',
  `impi_id` int(10) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `surname` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL,
  `balance` decimal(9,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `impi_id` (`impi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='tabela uzupelniajaca do tabeli impi';

-- Dumping data for table openims.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `impi_id`, `name`, `surname`, `email`, `role`, `balance`) VALUES
	(2, 2, 'BOB', 'Dylan', 'bob@dylan.com', 'user', NULL),
	(3, 4, 'Alice', 'Alice', 'alice@alice.com', 'admin', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table openims.users_utility_functions
CREATE TABLE IF NOT EXISTS `users_utility_functions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT 'id uzytkownika',
  `utility_function_id` int(10) unsigned NOT NULL COMMENT 'id funkcji uzytecznosci',
  `protocol_id` int(10) unsigned NOT NULL COMMENT 'id protokolu (klasy np FTP)',
  PRIMARY KEY (`id`),
  KEY `FK_users_utility_functions_utility_functions` (`utility_function_id`),
  KEY `FK_users_utility_functions_protocols` (`protocol_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `FK_users_utility_functions_protocols` FOREIGN KEY (`protocol_id`) REFERENCES `protocols` (`id`),
  CONSTRAINT `FK_users_utility_functions_utility_functions` FOREIGN KEY (`utility_function_id`) REFERENCES `utility_functions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table openims.users_utility_functions: ~2 rows (approximately)
/*!40000 ALTER TABLE `users_utility_functions` DISABLE KEYS */;
INSERT INTO `users_utility_functions` (`id`, `user_id`, `utility_function_id`, `protocol_id`) VALUES
	(1, 2, 4, 3),
	(3, 2, 10, 4);
/*!40000 ALTER TABLE `users_utility_functions` ENABLE KEYS */;


-- Dumping structure for table openims.users_utility_functions_parameters
CREATE TABLE IF NOT EXISTS `users_utility_functions_parameters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_utility_function_id` int(10) unsigned NOT NULL,
  `parameter_id` int(10) unsigned NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_users_utility_functions_parameters_users_utility_functions` (`users_utility_function_id`),
  KEY `FK_users_utility_functions_parameters_parameters` (`parameter_id`),
  CONSTRAINT `FK_users_utility_functions_parameters_parameters` FOREIGN KEY (`parameter_id`) REFERENCES `parameters` (`id`),
  CONSTRAINT `FK_users_utility_functions_parameters_users_utility_functions` FOREIGN KEY (`users_utility_function_id`) REFERENCES `users_utility_functions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table openims.users_utility_functions_parameters: ~1 rows (approximately)
/*!40000 ALTER TABLE `users_utility_functions_parameters` DISABLE KEYS */;
INSERT INTO `users_utility_functions_parameters` (`id`, `users_utility_function_id`, `parameter_id`, `value`) VALUES
	(1, 1, 2, '90');
/*!40000 ALTER TABLE `users_utility_functions_parameters` ENABLE KEYS */;


-- Dumping structure for table openims.utility_functions
CREATE TABLE IF NOT EXISTS `utility_functions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL COMMENT 'unikalna nazwa np TCP_F1, TCP_F2, WWW_F3',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table openims.utility_functions: ~6 rows (approximately)
/*!40000 ALTER TABLE `utility_functions` DISABLE KEYS */;
INSERT INTO `utility_functions` (`id`, `name`) VALUES
	(4, 'FTP1'),
	(10, 'FTP2'),
	(6, 'FTP7'),
	(3, 'F_TCP'),
	(2, 'SKYPE_F2'),
	(1, 'WWW_F1');
/*!40000 ALTER TABLE `utility_functions` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
