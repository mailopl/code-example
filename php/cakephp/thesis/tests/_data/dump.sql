-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.24-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4208
-- Date/time:                    2012-11-01 21:09:20
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table apigeum.columns
CREATE TABLE IF NOT EXISTS `columns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`feed_id`),
  KEY `fk_columns_feeds1_idx` (`feed_id`),
  CONSTRAINT `fk_columns_feeds1` FOREIGN KEY (`feed_id`) REFERENCES `feeds` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;

-- Dumping data for table apigeum.columns: ~10 rows (approximately)
/*!40000 ALTER TABLE `columns` DISABLE KEYS */;
INSERT INTO `columns` (`id`, `feed_id`, `name`) VALUES
	(101, 1, 'name'),
	(102, 1, 'color'),
	(103, 1, 'year'),
	(104, 1, ''),
	(105, 1, ''),
	(106, 1, ''),
	(107, 1, ''),
	(108, 1, ''),
	(109, 1, ''),
	(110, 1, '');
/*!40000 ALTER TABLE `columns` ENABLE KEYS */;


-- Dumping structure for table apigeum.feeds
CREATE TABLE IF NOT EXISTS `feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `description` text,
  `views_count` int(10) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `type` enum('free','premium') DEFAULT NULL,
  `likes` int(10) unsigned DEFAULT NULL,
  `rows_count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_feeds_users1_idx` (`user_id`),
  CONSTRAINT `fk_feeds_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table apigeum.feeds: ~1 rows (approximately)
/*!40000 ALTER TABLE `feeds` DISABLE KEYS */;
INSERT INTO `feeds` (`id`, `user_id`, `name`, `description`, `views_count`, `created`, `modified`, `type`, `likes`, `rows_count`) VALUES
	(1, 1, 'cars', 'Largest car database available via REST', 7, '2012-10-05 09:52:07', '2012-10-15 16:00:29', 'free', 2, 0);
/*!40000 ALTER TABLE `feeds` ENABLE KEYS */;


-- Dumping structure for table apigeum.rows
CREATE TABLE IF NOT EXISTS `rows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feed_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`feed_id`),
  KEY `fk_rows_feeds_idx` (`feed_id`),
  CONSTRAINT `fk_rows_feeds` FOREIGN KEY (`feed_id`) REFERENCES `feeds` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Dumping data for table apigeum.rows: ~6 rows (approximately)
/*!40000 ALTER TABLE `rows` DISABLE KEYS */;
INSERT INTO `rows` (`id`, `feed_id`) VALUES
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1);
/*!40000 ALTER TABLE `rows` ENABLE KEYS */;


-- Dumping structure for table apigeum.statistics_monthly
CREATE TABLE IF NOT EXISTS `statistics_monthly` (
  `id` int(10) unsigned NOT NULL,
  `feed_id` int(10) unsigned NOT NULL,
  `ip` int(11) DEFAULT NULL,
  `month` int(10) unsigned DEFAULT NULL,
  `year` int(10) unsigned DEFAULT NULL,
  `count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`,`feed_id`),
  KEY `fk_statistics_monthly_feeds1_idx` (`feed_id`),
  CONSTRAINT `fk_statistics_monthly_feeds1` FOREIGN KEY (`feed_id`) REFERENCES `feeds` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table apigeum.statistics_monthly: ~0 rows (approximately)
/*!40000 ALTER TABLE `statistics_monthly` DISABLE KEYS */;
/*!40000 ALTER TABLE `statistics_monthly` ENABLE KEYS */;


-- Dumping structure for table apigeum.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(42) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `feeds_count` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table apigeum.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `password`, `created`, `last_seen`, `feeds_count`) VALUES
	(1, 'wawrzyniak.mm@gmail.com', '249f0f8360be556e14f76b2e4e2135d5aff4beb0', NULL, NULL, NULL),
	(2, 'wawrzyniak.mm@gmail.com', '249f0f8360be556e14f76b2e4e2135d5aff4beb0', NULL, NULL, NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table apigeum.values
CREATE TABLE IF NOT EXISTS `values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `column_id` int(11) NOT NULL,
  `row_id` int(10) unsigned NOT NULL COMMENT 'wee need that to know that values: #1, #7 are in the same row.',
  `value` longtext,
  PRIMARY KEY (`id`,`column_id`,`row_id`),
  KEY `fk_values_columns1_idx` (`column_id`),
  KEY `fk_values_rows1_idx` (`row_id`),
  CONSTRAINT `fk_values_columns1` FOREIGN KEY (`column_id`) REFERENCES `columns` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_values_rows1` FOREIGN KEY (`row_id`) REFERENCES `rows` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

-- Dumping data for table apigeum.values: ~18 rows (approximately)
/*!40000 ALTER TABLE `values` DISABLE KEYS */;
INSERT INTO `values` (`id`, `column_id`, `row_id`, `value`) VALUES
	(62, 101, 16, 'cayenne'),
	(63, 102, 16, 'red'),
	(64, 101, 17, 'accent'),
	(65, 102, 17, 'red'),
	(66, 101, 18, 'tuareg'),
	(67, 102, 18, 'red'),
	(68, 101, 19, 'turan'),
	(69, 102, 19, 'black'),
	(70, 101, 20, 'panamera'),
	(71, 102, 20, 'red'),
	(72, 101, 21, 'porsche'),
	(73, 102, 21, 'panamera'),
	(74, 103, 21, '1990'),
	(75, 103, 20, '2010'),
	(76, 103, 19, '2011'),
	(77, 103, 18, '2012'),
	(78, 103, 17, '1999'),
	(79, 103, 16, '1996');
/*!40000 ALTER TABLE `values` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
