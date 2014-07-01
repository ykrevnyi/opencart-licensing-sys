# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.16)
# Database: laravel_license
# Generation Time: 2014-07-01 05:33:10 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `keys`;

CREATE TABLE `keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domain` text COLLATE utf8_unicode_ci NOT NULL,
  `module_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_type` int(10) unsigned NOT NULL,
  `transaction_id` int(10) unsigned DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `expired_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keys_key_unique` (`key`),
  KEY `keys_module_code_foreign` (`module_code`),
  KEY `keys_module_type_foreign` (`module_type`),
  KEY `keys_transaction_id_foreign` (`transaction_id`),
  CONSTRAINT `keys_module_code_foreign` FOREIGN KEY (`module_code`) REFERENCES `modules` (`code`),
  CONSTRAINT `keys_module_type_foreign` FOREIGN KEY (`module_type`) REFERENCES `module_type` (`id`),
  CONSTRAINT `keys_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `keys` WRITE;
/*!40000 ALTER TABLE `keys` DISABLE KEYS */;

INSERT INTO `keys` (`id`, `key`, `domain`, `module_code`, `module_type`, `transaction_id`, `active`, `expired_at`, `created_at`, `updated_at`)
VALUES
	(16,'DEMO','test.test','menu',2,NULL,1,'2014-07-06 15:36:45','2014-06-29 15:36:45','2014-06-29 15:36:45');

/*!40000 ALTER TABLE `keys` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`migration`, `batch`)
VALUES
	('2014_06_14_094413_create_modules_table',1),
	('2014_06_14_094622_add_unique_index_to_modules_code_field',1),
	('2014_06_14_095011_create_transactions_table',1),
	('2014_06_14_095428_add_timestamps_to_modules_table',1),
	('2014_06_14_095646_create_module_type_table',1),
	('2014_06_14_112146_add_foreign_key_to_module_type_table',1),
	('2014_06_14_112451_create_keys_table',1),
	('2014_06_14_120956_add_foreign_keys_to_keys_table',1),
	('2014_06_14_130908_add_active_field_to_modules_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table module_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `module_type`;

CREATE TABLE `module_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(10) unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `price` float(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_type_module_id_foreign` (`module_id`),
  CONSTRAINT `module_type_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `module_type` WRITE;
/*!40000 ALTER TABLE `module_type` DISABLE KEYS */;

INSERT INTO `module_type` (`id`, `module_id`, `name`, `price`)
VALUES
	(2,1,'Basic',0.00),
	(4,1,'Pro',40.00);

/*!40000 ALTER TABLE `module_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` float(8,2) NOT NULL,
  `image` text COLLATE utf8_unicode_ci NOT NULL,
  `download_path` text COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pay_description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;

INSERT INTO `modules` (`id`, `code`, `name`, `price`, `image`, `download_path`, `category`, `pay_description`, `created_at`, `updated_at`, `active`)
VALUES
	(1,'menu','Menu module',10.00,'','','Management','Hey, you should buy this one!!!','2014-06-28 20:58:42','2014-06-28 20:58:42',1);

/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ik_co_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_co_prs_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_inv_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_inv_st` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_inv_crt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_inv_prc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_trn_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_pm_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_pw_via` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_am` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_cur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_co_rfn` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_ps_price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ik_sign` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;

INSERT INTO `transactions` (`id`, `ik_co_id`, `ik_co_prs_id`, `ik_inv_id`, `ik_inv_st`, `ik_inv_crt`, `ik_inv_prc`, `ik_trn_id`, `ik_pm_no`, `ik_desc`, `ik_pw_via`, `ik_am`, `ik_cur`, `ik_co_rfn`, `ik_ps_price`, `ik_sign`, `created_at`, `updated_at`)
VALUES
	(2,'','','','','','','','','','','','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
