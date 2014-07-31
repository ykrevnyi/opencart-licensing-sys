-- --------------------------------------------------------
-- Сервер:                       127.0.0.1
-- Версія сервера:               5.6.17 - MySQL Community Server (GPL)
-- ОС сервера:                   Win64
-- HeidiSQL Версія:              8.3.0.4769
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for таблиця laravel_license.keys
CREATE TABLE IF NOT EXISTS `keys` (
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
  KEY `keys_module_code_foreign` (`module_code`),
  KEY `keys_module_type_foreign` (`module_type`),
  KEY `keys_transaction_id_foreign` (`transaction_id`),
  CONSTRAINT `keys_module_code_foreign` FOREIGN KEY (`module_code`) REFERENCES `modules` (`code`),
  CONSTRAINT `keys_module_type_foreign` FOREIGN KEY (`module_type`) REFERENCES `module_type` (`id`),
  CONSTRAINT `keys_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table laravel_license.keys: ~0 rows (приблизно)
DELETE FROM `keys`;
/*!40000 ALTER TABLE `keys` DISABLE KEYS */;
INSERT INTO `keys` (`id`, `key`, `domain`, `module_code`, `module_type`, `transaction_id`, `active`, `expired_at`, `created_at`, `updated_at`) VALUES
	(330, 'DEMO', 'opencart.dev', 'menu', 4, NULL, 1, '2014-06-30 13:58:27', '2014-07-24 13:58:27', '2014-07-24 13:58:27');
/*!40000 ALTER TABLE `keys` ENABLE KEYS */;


-- Dumping structure for таблиця laravel_license.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table laravel_license.migrations: ~9 rows (приблизно)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`migration`, `batch`) VALUES
	('2014_06_14_094413_create_modules_table', 1),
	('2014_06_14_094622_add_unique_index_to_modules_code_field', 1),
	('2014_06_14_095011_create_transactions_table', 1),
	('2014_06_14_095428_add_timestamps_to_modules_table', 1),
	('2014_06_14_095646_create_module_type_table', 1),
	('2014_06_14_112146_add_foreign_key_to_module_type_table', 1),
	('2014_06_14_112451_create_keys_table', 1),
	('2014_06_14_120956_add_foreign_keys_to_keys_table', 1),
	('2014_06_14_130908_add_active_field_to_modules_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;


-- Dumping structure for таблиця laravel_license.modules
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `price` float(8,2) NOT NULL,
  `image` text COLLATE utf8_unicode_ci NOT NULL,
  `pay_description` text COLLATE utf8_unicode_ci NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(4) NOT NULL,
  `regular_payment` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table laravel_license.modules: ~2 rows (приблизно)
DELETE FROM `modules`;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` (`id`, `code`, `version`, `price`, `image`, `pay_description`, `downloads`, `created_at`, `updated_at`, `active`, `regular_payment`) VALUES
	(1, 'menu', '0.2', 30.00, 'http://www.developersnippets.com/wp-content/uploads/2009/10/css_dropdown_menu.jpg', 'email::domain::type', 220, '2014-07-02 09:34:48', '2014-07-24 13:58:27', 1, 1),
	(4, 'menu2', '0.2', 10.00, 'http://www.developersnippets.com/wp-content/uploads/2009/10/css_dropdown_menu.jpg', 'email::domain::type', 29, '2014-07-02 09:34:48', '2014-07-18 08:27:37', 1, 0);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;


-- Dumping structure for таблиця laravel_license.modules_language
CREATE TABLE IF NOT EXISTS `modules_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(10) unsigned NOT NULL,
  `language_code` varchar(50) NOT NULL DEFAULT 'en',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_modules_language_modules` (`module_id`),
  CONSTRAINT `FK_modules_language_modules` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table laravel_license.modules_language: ~2 rows (приблизно)
DELETE FROM `modules_language`;
/*!40000 ALTER TABLE `modules_language` DISABLE KEYS */;
INSERT INTO `modules_language` (`id`, `module_id`, `language_code`, `name`, `description`, `category`) VALUES
	(1, 1, 'en', 'Menu localized', 'Desc localized', 'Management'),
	(2, 1, 'ru', 'Меню', 'Описание меню', 'Менеджмент');
/*!40000 ALTER TABLE `modules_language` ENABLE KEYS */;


-- Dumping structure for таблиця laravel_license.module_type
CREATE TABLE IF NOT EXISTS `module_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(10) unsigned NOT NULL,
  `price` float(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_type_module_id_foreign` (`module_id`),
  CONSTRAINT `module_type_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table laravel_license.module_type: ~4 rows (приблизно)
DELETE FROM `module_type`;
/*!40000 ALTER TABLE `module_type` DISABLE KEYS */;
INSERT INTO `module_type` (`id`, `module_id`, `price`) VALUES
	(1, 1, 0.00),
	(2, 1, 10.00),
	(3, 1, 12.00),
	(4, 1, 100.00);
/*!40000 ALTER TABLE `module_type` ENABLE KEYS */;


-- Dumping structure for таблиця laravel_license.module_type_language
CREATE TABLE IF NOT EXISTS `module_type_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_type_id` int(10) unsigned NOT NULL,
  `language_code` varchar(50) NOT NULL DEFAULT 'en',
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_module_type_language_module_type` (`module_type_id`),
  CONSTRAINT `FK_module_type_language_module_type` FOREIGN KEY (`module_type_id`) REFERENCES `module_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table laravel_license.module_type_language: ~8 rows (приблизно)
DELETE FROM `module_type_language`;
/*!40000 ALTER TABLE `module_type_language` DISABLE KEYS */;
INSERT INTO `module_type_language` (`id`, `module_type_id`, `language_code`, `name`) VALUES
	(1, 1, 'en', 'Basic'),
	(4, 2, 'en', 'Pro'),
	(5, 3, 'en', 'Pro Extended'),
	(6, 4, 'en', 'Max'),
	(7, 1, 'ru', 'Базовый'),
	(8, 2, 'ru', 'Профи'),
	(9, 3, 'ru', 'Профи Плюс'),
	(10, 4, 'ru', 'Максимальный');
/*!40000 ALTER TABLE `module_type_language` ENABLE KEYS */;


-- Dumping structure for таблиця laravel_license.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
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
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table laravel_license.transactions: ~136 rows (приблизно)
DELETE FROM `transactions`;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` (`id`, `ik_co_id`, `ik_co_prs_id`, `ik_inv_id`, `ik_inv_st`, `ik_inv_crt`, `ik_inv_prc`, `ik_trn_id`, `ik_pm_no`, `ik_desc`, `ik_pw_via`, `ik_am`, `ik_cur`, `ik_co_rfn`, `ik_ps_price`, `ik_sign`, `created_at`, `updated_at`) VALUES
	(102, '5370b755bf4efccb31ad6f90', '201348095960', '28626575', 'success', '2014-07-07 14:10:57', '2014-07-07 14:10:57', '', 'menu', 'testemail@gmail.com::license.dev::1', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '4lSH0+pweY2X0aUjzFTPqw==', '2014-07-08 13:35:20', '2014-07-08 13:35:20'),
	(128, '5370b755bf4efccb31ad6f90', '201348095960', '28677262', 'success', '2014-07-09 17:19:44', '2014-07-09 17:19:44', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'N2TdE0LQO1ZkRX2Acrs5dA==', '2014-07-09 14:21:22', '2014-07-09 14:21:22'),
	(129, '5370b755bf4efccb31ad6f90', '201348095960', '28677322', 'success', '2014-07-09 17:21:59', '2014-07-09 17:21:59', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'iiJSq7iz8kbJAt8FkTmkig==', '2014-07-09 14:22:19', '2014-07-09 14:22:19'),
	(130, '5370b755bf4efccb31ad6f90', '201348095960', '28677250', 'success', '2014-07-09 17:18:55', '2014-07-09 17:18:55', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'AhpNyuzprzC9lHduW5eLhw==', '2014-07-09 14:22:22', '2014-07-09 14:22:22'),
	(131, '5370b755bf4efccb31ad6f90', '201348095960', '28677262', 'success', '2014-07-09 17:19:44', '2014-07-09 17:19:44', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'N2TdE0LQO1ZkRX2Acrs5dA==', '2014-07-09 14:22:22', '2014-07-09 14:22:22'),
	(132, '5370b755bf4efccb31ad6f90', '201348095960', '28677198', 'success', '2014-07-09 17:16:33', '2014-07-09 17:16:33', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'JaUS5NHkS13MjD38fuRL+w==', '2014-07-09 14:23:22', '2014-07-09 14:23:22'),
	(133, '5370b755bf4efccb31ad6f90', '201348095960', '28677370', 'success', '2014-07-09 17:23:46', '2014-07-09 17:23:46', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'Xd81XEAkG15rowFGTeVIDQ==', '2014-07-09 14:24:05', '2014-07-09 14:24:05'),
	(134, '5370b755bf4efccb31ad6f90', '201348095960', '28677064', 'success', '2014-07-09 17:10:12', '2014-07-09 17:10:12', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'R3ZyyXOMUNFbEW/BsDLE6Q==', '2014-07-09 14:24:21', '2014-07-09 14:24:21'),
	(135, '5370b755bf4efccb31ad6f90', '201348095960', '28677442', 'success', '2014-07-09 17:27:21', '2014-07-09 17:27:21', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'lrZQnNjzL9jI0RY7+gt43g==', '2014-07-09 14:27:45', '2014-07-09 14:27:45'),
	(136, '5370b755bf4efccb31ad6f90', '201348095960', '28677475', 'success', '2014-07-09 17:28:49', '2014-07-09 17:28:49', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'mdAb5SdXBt5JWlsHjT0NNw==', '2014-07-09 14:29:08', '2014-07-09 14:29:08'),
	(137, '5370b755bf4efccb31ad6f90', '201348095960', '28677516', 'success', '2014-07-09 17:30:31', '2014-07-09 17:30:31', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'JT/57d/CKpcg9uxxGr3G+Q==', '2014-07-09 14:30:50', '2014-07-09 14:30:50'),
	(138, '5370b755bf4efccb31ad6f90', '201348095960', '28677780', 'success', '2014-07-09 17:41:01', '2014-07-09 17:41:01', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'ZuJ8fx+bmmouhljm08vNOQ==', '2014-07-09 14:41:20', '2014-07-09 14:41:20'),
	(139, '5370b755bf4efccb31ad6f90', '201348095960', '28677797', 'success', '2014-07-09 17:41:59', '2014-07-09 17:41:59', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', '5XfWXrD052vO+4l9/cqt4A==', '2014-07-09 14:42:23', '2014-07-09 14:42:23'),
	(140, '5370b755bf4efccb31ad6f90', '201348095960', '28677780', 'success', '2014-07-09 17:41:01', '2014-07-09 17:41:01', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'ZuJ8fx+bmmouhljm08vNOQ==', '2014-07-09 14:42:24', '2014-07-09 14:42:24'),
	(141, '5370b755bf4efccb31ad6f90', '201348095960', '28677816', 'success', '2014-07-09 17:42:50', '2014-07-09 17:42:50', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'OKO4NpSu/V6YHbQPhqN9EQ==', '2014-07-09 14:43:09', '2014-07-09 14:43:09'),
	(142, '5370b755bf4efccb31ad6f90', '201348095960', '28677780', 'success', '2014-07-09 17:41:01', '2014-07-09 17:41:01', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'ZuJ8fx+bmmouhljm08vNOQ==', '2014-07-09 14:43:21', '2014-07-09 14:43:21'),
	(143, '5370b755bf4efccb31ad6f90', '201348095960', '28677797', 'success', '2014-07-09 17:41:59', '2014-07-09 17:41:59', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', '5XfWXrD052vO+4l9/cqt4A==', '2014-07-09 14:43:21', '2014-07-09 14:43:21'),
	(144, '5370b755bf4efccb31ad6f90', '201348095960', '28677891', 'success', '2014-07-09 17:46:16', '2014-07-09 17:46:16', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'G94fHDjvnsLWH1HGy/xQyg==', '2014-07-09 14:46:36', '2014-07-09 14:46:36'),
	(145, '5370b755bf4efccb31ad6f90', '201348095960', '28677891', 'success', '2014-07-09 17:46:16', '2014-07-09 17:46:16', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'G94fHDjvnsLWH1HGy/xQyg==', '2014-07-09 14:47:22', '2014-07-09 14:47:22'),
	(146, '5370b755bf4efccb31ad6f90', '201348095960', '28677929', 'success', '2014-07-09 17:47:38', '2014-07-09 17:47:38', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'k88dBujoZTPPt15/zZ41rw==', '2014-07-09 14:47:58', '2014-07-09 14:47:58'),
	(147, '5370b755bf4efccb31ad6f90', '201348095960', '28677891', 'success', '2014-07-09 17:46:16', '2014-07-09 17:46:16', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'G94fHDjvnsLWH1HGy/xQyg==', '2014-07-09 14:48:21', '2014-07-09 14:48:21'),
	(148, '5370b755bf4efccb31ad6f90', '201348095960', '28677946', 'success', '2014-07-09 17:48:49', '2014-07-09 17:48:49', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'hF6A3vToesg3CpmBQZivww==', '2014-07-09 14:49:09', '2014-07-09 14:49:09'),
	(149, '5370b755bf4efccb31ad6f90', '201348095960', '28678284', 'success', '2014-07-09 18:03:45', '2014-07-09 18:03:45', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JSo3JIYwuFESEKwNrNcziw==', '2014-07-09 15:04:04', '2014-07-09 15:04:04'),
	(150, '5370b755bf4efccb31ad6f90', '201348095960', '28678284', 'success', '2014-07-09 18:03:45', '2014-07-09 18:03:45', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JSo3JIYwuFESEKwNrNcziw==', '2014-07-09 15:04:21', '2014-07-09 15:04:21'),
	(151, '5370b755bf4efccb31ad6f90', '201348095960', '28678284', 'success', '2014-07-09 18:03:45', '2014-07-09 18:03:45', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JSo3JIYwuFESEKwNrNcziw==', '2014-07-09 15:05:21', '2014-07-09 15:05:21'),
	(152, '5370b755bf4efccb31ad6f90', '201348095960', '28678331', 'success', '2014-07-09 18:05:38', '2014-07-09 18:05:38', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '2SFuW6alcMypObvs3U2Eeg==', '2014-07-09 15:05:57', '2014-07-09 15:05:57'),
	(153, '5370b755bf4efccb31ad6f90', '201348095960', '28678284', 'success', '2014-07-09 18:03:45', '2014-07-09 18:03:45', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JSo3JIYwuFESEKwNrNcziw==', '2014-07-09 15:06:25', '2014-07-09 15:06:25'),
	(154, '5370b755bf4efccb31ad6f90', '201348095960', '28676546', 'success', '2014-07-09 16:45:21', '2014-07-09 16:45:21', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'sg3M0EMTU+Q01gCt+crazQ==', '2014-07-09 15:08:21', '2014-07-09 15:08:21'),
	(155, '5370b755bf4efccb31ad6f90', '201348095960', '28678396', 'success', '2014-07-09 18:08:42', '2014-07-09 18:08:42', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JUjqJCQbsekeF1I7q6wpNw==', '2014-07-09 15:09:02', '2014-07-09 15:09:02'),
	(156, '5370b755bf4efccb31ad6f90', '201348095960', '28678456', 'success', '2014-07-09 18:10:45', '2014-07-09 18:10:45', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '15tZ95qViK0LeSd4PV+Z7Q==', '2014-07-09 15:11:05', '2014-07-09 15:11:05'),
	(157, '5370b755bf4efccb31ad6f90', '201348095960', '28678456', 'success', '2014-07-09 18:10:45', '2014-07-09 18:10:45', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '15tZ95qViK0LeSd4PV+Z7Q==', '2014-07-09 15:11:22', '2014-07-09 15:11:22'),
	(158, '5370b755bf4efccb31ad6f90', '201348095960', '28678456', 'success', '2014-07-09 18:10:45', '2014-07-09 18:10:45', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '15tZ95qViK0LeSd4PV+Z7Q==', '2014-07-09 15:12:27', '2014-07-09 15:12:27'),
	(159, '5370b755bf4efccb31ad6f90', '201348095960', '28678456', 'success', '2014-07-09 18:10:45', '2014-07-09 18:10:45', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '15tZ95qViK0LeSd4PV+Z7Q==', '2014-07-09 15:13:21', '2014-07-09 15:13:21'),
	(160, '5370b755bf4efccb31ad6f90', '201348095960', '28678456', 'success', '2014-07-09 18:10:45', '2014-07-09 18:10:45', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '15tZ95qViK0LeSd4PV+Z7Q==', '2014-07-09 15:14:21', '2014-07-09 15:14:21'),
	(161, '5370b755bf4efccb31ad6f90', '201348095960', '28678456', 'success', '2014-07-09 18:10:45', '2014-07-09 18:10:45', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '15tZ95qViK0LeSd4PV+Z7Q==', '2014-07-09 15:17:21', '2014-07-09 15:17:21'),
	(162, '5370b755bf4efccb31ad6f90', '201348095960', '28678728', 'success', '2014-07-09 18:21:30', '2014-07-09 18:21:30', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'dlH0VAHvB/jWgQ4MQXxlvw==', '2014-07-09 15:21:50', '2014-07-09 15:21:50'),
	(163, '5370b755bf4efccb31ad6f90', '201348095960', '28678755', 'success', '2014-07-09 18:23:13', '2014-07-09 18:23:13', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'gajwsrhCjEzI8pcyJAs6gA==', '2014-07-09 15:23:33', '2014-07-09 15:23:33'),
	(164, '5370b755bf4efccb31ad6f90', '201348095960', '28678456', 'success', '2014-07-09 18:10:45', '2014-07-09 18:10:45', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '15tZ95qViK0LeSd4PV+Z7Q==', '2014-07-09 15:24:21', '2014-07-09 15:24:21'),
	(165, '5370b755bf4efccb31ad6f90', '201348095960', '28679315', 'success', '2014-07-09 18:46:52', '2014-07-09 18:46:52', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JRhny27PiXrI5Ma4tEqC5w==', '2014-07-09 15:47:12', '2014-07-09 15:47:12'),
	(166, '5370b755bf4efccb31ad6f90', '201348095960', '28679315', 'success', '2014-07-09 18:46:52', '2014-07-09 18:46:52', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JRhny27PiXrI5Ma4tEqC5w==', '2014-07-09 15:47:23', '2014-07-09 15:47:23'),
	(167, '5370b755bf4efccb31ad6f90', '201348095960', '28679315', 'success', '2014-07-09 18:46:52', '2014-07-09 18:46:52', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JRhny27PiXrI5Ma4tEqC5w==', '2014-07-09 15:48:21', '2014-07-09 15:48:21'),
	(168, '5370b755bf4efccb31ad6f90', '201348095960', '28679356', 'success', '2014-07-09 18:48:45', '2014-07-09 18:48:45', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'WywQE+24fGhKazY4yf32uA==', '2014-07-09 15:49:05', '2014-07-09 15:49:05'),
	(169, '5370b755bf4efccb31ad6f90', '201348095960', '28679315', 'success', '2014-07-09 18:46:52', '2014-07-09 18:46:52', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JRhny27PiXrI5Ma4tEqC5w==', '2014-07-09 15:49:22', '2014-07-09 15:49:22'),
	(170, '5370b755bf4efccb31ad6f90', '201348095960', '28679453', 'success', '2014-07-09 18:52:02', '2014-07-09 18:52:02', '', 'menu', 'testemail@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'OTt91Nhny4JkNPmG44lUlQ==', '2014-07-09 15:52:22', '2014-07-09 15:52:22'),
	(171, '5370b755bf4efccb31ad6f90', '201348095960', '28679487', 'success', '2014-07-09 18:53:20', '2014-07-09 18:53:20', '', 'menu', 'testemail@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'HkdpetqdVDqQRFinaomXiA==', '2014-07-09 15:53:40', '2014-07-09 15:53:40'),
	(172, '5370b755bf4efccb31ad6f90', '201348095960', '28676546', 'success', '2014-07-09 16:45:21', '2014-07-09 16:45:21', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'sg3M0EMTU+Q01gCt+crazQ==', '2014-07-09 17:24:22', '2014-07-09 17:24:22'),
	(173, '5370b755bf4efccb31ad6f90', '201348095960', '28676546', 'success', '2014-07-09 16:45:21', '2014-07-09 16:45:21', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'sg3M0EMTU+Q01gCt+crazQ==', '2014-07-09 23:32:22', '2014-07-09 23:32:22'),
	(174, '5370b755bf4efccb31ad6f90', '201348095960', '28625960', 'success', '2014-07-07 13:39:27', '2014-07-07 13:39:27', '', 'menu', 'testemail@gmail.com::license.dev::1', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'RuqUOrGagdqIe0Qi+eyEJg==', '2014-07-10 10:17:23', '2014-07-10 10:17:23'),
	(175, '5370b755bf4efccb31ad6f90', '201348095960', '28626575', 'success', '2014-07-07 14:10:57', '2014-07-07 14:10:57', '', 'menu', 'testemail@gmail.com::license.dev::1', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '4lSH0+pweY2X0aUjzFTPqw==', '2014-07-10 10:48:21', '2014-07-10 10:48:21'),
	(176, '5370b755bf4efccb31ad6f90', '201348095960', '28676546', 'success', '2014-07-09 16:45:21', '2014-07-09 16:45:21', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'sg3M0EMTU+Q01gCt+crazQ==', '2014-07-10 16:10:31', '2014-07-10 16:10:31'),
	(177, '5370b755bf4efccb31ad6f90', '201348095960', '28676546', 'success', '2014-07-09 16:45:21', '2014-07-09 16:45:21', '', 'menu', 'testemail@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'sg3M0EMTU+Q01gCt+crazQ==', '2014-07-12 13:23:42', '2014-07-12 13:23:42'),
	(178, '5370b755bf4efccb31ad6f90', '201348095960', '28771385', 'success', '2014-07-14 10:23:19', '2014-07-14 10:23:19', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'jF1IESUWeLpTE8BL6tsUqQ==', '2014-07-14 07:23:41', '2014-07-14 07:23:41'),
	(179, '5370b755bf4efccb31ad6f90', '201348095960', '28771939', 'success', '2014-07-14 11:00:13', '2014-07-14 11:00:13', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'oxDRFwh5EjBUCfSEO9jYXw==', '2014-07-14 08:00:35', '2014-07-14 08:00:35'),
	(180, '5370b755bf4efccb31ad6f90', '201348095960', '28772775', 'success', '2014-07-14 11:49:49', '2014-07-14 11:49:49', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'zM/ATxpXwKME7LU1AwkB6g==', '2014-07-14 08:50:11', '2014-07-14 08:50:11'),
	(181, '5370b755bf4efccb31ad6f90', '201348095960', '28773008', 'success', '2014-07-14 12:03:11', '2014-07-14 12:03:11', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'zpjZhlJtVYXPTO/ATwQg/g==', '2014-07-14 09:03:34', '2014-07-14 09:03:34'),
	(182, '5370b755bf4efccb31ad6f90', '201348095960', '28781240', 'success', '2014-07-14 19:02:53', '2014-07-14 19:02:53', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'XwZD+r/NlLfTJHffnOqAmw==', '2014-07-14 16:03:16', '2014-07-14 16:03:16'),
	(183, '5370b755bf4efccb31ad6f90', '201348095960', '28781298', 'success', '2014-07-14 19:06:08', '2014-07-14 19:06:08', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'X/+mPdlr40SrG/EAZgfAcQ==', '2014-07-14 16:06:31', '2014-07-14 16:06:31'),
	(184, '5370b755bf4efccb31ad6f90', '201348095960', '28781366', 'success', '2014-07-14 19:08:54', '2014-07-14 19:08:54', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'A1PgM9hEWUxakXsNVJqz/Q==', '2014-07-14 16:09:17', '2014-07-14 16:09:17'),
	(185, '5370b755bf4efccb31ad6f90', '201348095960', '28781923', 'success', '2014-07-14 19:32:19', '2014-07-14 19:32:19', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '288/hpqzjrOO7/bpCyxq0g==', '2014-07-14 16:32:42', '2014-07-14 16:32:42'),
	(186, '5370b755bf4efccb31ad6f90', '201348095960', '28782355', 'success', '2014-07-14 19:52:06', '2014-07-14 19:52:06', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'VMtEjUdRE8YeLN28cekDZQ==', '2014-07-14 16:52:28', '2014-07-14 16:52:28'),
	(187, '5370b755bf4efccb31ad6f90', '201348095960', '28782377', 'success', '2014-07-14 19:53:15', '2014-07-14 19:53:15', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'GxiP2ityIVibhKTJtfh/Uw==', '2014-07-14 16:53:37', '2014-07-14 16:53:37'),
	(188, '5370b755bf4efccb31ad6f90', '201348095960', '28791582', 'success', '2014-07-15 10:56:54', '2014-07-15 10:56:54', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '2bhfuziN2bX4FTqzuZ7n0g==', '2014-07-15 07:57:17', '2014-07-15 07:57:17'),
	(189, '5370b755bf4efccb31ad6f90', '201348095960', '28798312', 'success', '2014-07-15 16:54:33', '2014-07-15 16:54:33', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', '/QN6p7A1Svxuk0n1ux3ylg==', '2014-07-15 13:54:56', '2014-07-15 13:54:56'),
	(190, '5370b755bf4efccb31ad6f90', '201348095960', '28837139', 'success', '2014-07-17 16:13:52', '2014-07-17 16:13:52', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'pCNBvgO8lAdgcwGt2v2ufg==', '2014-07-17 13:14:16', '2014-07-17 13:14:16'),
	(191, '5370b755bf4efccb31ad6f90', '201348095960', '28852240', 'success', '2014-07-18 12:27:26', '2014-07-18 12:27:26', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'JxWpcCsxTSr/HsJ5TnN/Kg==', '2014-07-18 09:27:50', '2014-07-18 09:27:50'),
	(192, '5370b755bf4efccb31ad6f90', '201348095960', '28853180', 'success', '2014-07-18 13:25:20', '2014-07-18 13:25:20', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'VQWYgENlNin/jmSrGno3hA==', '2014-07-18 10:25:45', '2014-07-18 10:25:45'),
	(193, '5370b755bf4efccb31ad6f90', '201348095960', '28854463', 'success', '2014-07-18 14:37:59', '2014-07-18 14:37:59', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '/SBW76nynA4rU5UOGuo92g==', '2014-07-18 11:38:24', '2014-07-18 11:38:24'),
	(194, '5370b755bf4efccb31ad6f90', '201348095960', '28854496', 'success', '2014-07-18 14:39:54', '2014-07-18 14:39:54', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '+Q3ChfCpBjpUkHEPB9oyBw==', '2014-07-18 11:40:19', '2014-07-18 11:40:19'),
	(195, '5370b755bf4efccb31ad6f90', '201348095960', '28855176', 'success', '2014-07-18 15:17:09', '2014-07-18 15:17:09', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'ojIg4YkodZC5YNT6auD5OQ==', '2014-07-18 12:17:34', '2014-07-18 12:17:34'),
	(196, '5370b755bf4efccb31ad6f90', '201348095960', '28856775', 'success', '2014-07-18 16:44:50', '2014-07-18 16:44:50', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', '2/GFFmZk1BUdCgVEKLFEdw==', '2014-07-18 13:45:15', '2014-07-18 13:45:15'),
	(197, '5370b755bf4efccb31ad6f90', '201348095960', '28856787', 'success', '2014-07-18 16:45:40', '2014-07-18 16:45:40', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', '6R9frD/wMJEr2OrAsW6N0A==', '2014-07-18 13:46:05', '2014-07-18 13:46:05'),
	(198, '5370b755bf4efccb31ad6f90', '201348095960', '28857040', 'success', '2014-07-18 16:59:50', '2014-07-18 16:59:50', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '1.99', 'USD', '1.9601', '2.02', 'h31p8ndh2uVSXS+X73FdCQ==', '2014-07-18 14:00:15', '2014-07-18 14:00:15'),
	(199, '5370b755bf4efccb31ad6f90', '201348095960', '28857225', 'success', '2014-07-18 17:10:02', '2014-07-18 17:10:02', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '11.99', 'USD', '11.8101', '12.17', 'Oz+QyX4oj6ZB/l4cBynEMg==', '2014-07-18 14:10:27', '2014-07-18 14:10:27'),
	(200, '5370b755bf4efccb31ad6f90', '201348095960', '28857315', 'success', '2014-07-18 17:14:45', '2014-07-18 17:14:45', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'l/DRVlAWNmlqwSCJKzF1AQ==', '2014-07-18 14:15:11', '2014-07-18 14:15:11'),
	(201, '5370b755bf4efccb31ad6f90', '201348095960', '28857365', 'success', '2014-07-18 17:16:42', '2014-07-18 17:16:42', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '1.99', 'USD', '1.9601', '2.02', 'lWHxE18LICkFHiGL4FjcQg==', '2014-07-18 14:17:07', '2014-07-18 14:17:07'),
	(202, '5370b755bf4efccb31ad6f90', '201348095960', '28857539', 'success', '2014-07-18 17:24:40', '2014-07-18 17:24:40', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'ZLD1CM5EeswOtd1h1dtB/A==', '2014-07-18 14:25:05', '2014-07-18 14:25:05'),
	(203, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:26:23', '2014-07-21 21:26:23'),
	(204, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:26:29', '2014-07-21 21:26:29'),
	(205, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:27:29', '2014-07-21 21:27:29'),
	(206, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:28:29', '2014-07-21 21:28:29'),
	(207, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:29:29', '2014-07-21 21:29:29'),
	(208, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:32:29', '2014-07-21 21:32:29'),
	(209, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:39:29', '2014-07-21 21:39:29'),
	(210, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 21:58:29', '2014-07-21 21:58:29'),
	(211, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-21 22:48:30', '2014-07-21 22:48:30'),
	(212, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-22 01:04:29', '2014-07-22 01:04:29'),
	(213, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-22 07:12:29', '2014-07-22 07:12:29'),
	(214, '5370b755bf4efccb31ad6f90', '201348095960', '28922990', 'success', '2014-07-22 11:13:24', '2014-07-22 11:13:24', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'uflPFZteCuqyek9uWVDOew==', '2014-07-22 08:13:52', '2014-07-22 08:13:52'),
	(215, '5370b755bf4efccb31ad6f90', '201348095960', '28922990', 'success', '2014-07-22 11:13:24', '2014-07-22 11:13:24', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'uflPFZteCuqyek9uWVDOew==', '2014-07-22 08:14:29', '2014-07-22 08:14:29'),
	(216, '5370b755bf4efccb31ad6f90', '201348095960', '28922990', 'success', '2014-07-22 11:13:24', '2014-07-22 11:13:24', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'uflPFZteCuqyek9uWVDOew==', '2014-07-22 08:15:29', '2014-07-22 08:15:29'),
	(217, '5370b755bf4efccb31ad6f90', '201348095960', '28923028', 'success', '2014-07-22 11:15:43', '2014-07-22 11:15:43', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'MN+2OC4eWAHiFYwxuDwD6w==', '2014-07-22 08:16:11', '2014-07-22 08:16:11'),
	(218, '5370b755bf4efccb31ad6f90', '201348095960', '28922990', 'success', '2014-07-22 11:13:24', '2014-07-22 11:13:24', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'uflPFZteCuqyek9uWVDOew==', '2014-07-22 08:16:29', '2014-07-22 08:16:29'),
	(219, '5370b755bf4efccb31ad6f90', '201348095960', '28923067', 'success', '2014-07-22 11:17:59', '2014-07-22 11:17:59', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '0ImlAgDfX3uxsnB5ElG2Nw==', '2014-07-22 08:18:27', '2014-07-22 08:18:27'),
	(220, '5370b755bf4efccb31ad6f90', '201348095960', '28923201', 'success', '2014-07-22 11:25:34', '2014-07-22 11:25:34', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'zqZVUyjNfRSSYbcvAWG7lg==', '2014-07-22 08:26:02', '2014-07-22 08:26:02'),
	(221, '5370b755bf4efccb31ad6f90', '201348095960', '28923415', 'success', '2014-07-22 11:35:27', '2014-07-22 11:35:27', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'yC74rB3x2mh1eNv6Okt91Q==', '2014-07-22 08:35:54', '2014-07-22 08:35:54'),
	(222, '5370b755bf4efccb31ad6f90', '201348095960', '28926943', 'success', '2014-07-22 14:23:21', '2014-07-22 14:23:21', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '20.00', 'USD', '19.7000', '20.30', 'FkfMorhBOV90VDSLHTfRyg==', '2014-07-22 11:23:48', '2014-07-22 11:23:48'),
	(223, '5370b755bf4efccb31ad6f90', '201348095960', '28926996', 'success', '2014-07-22 14:25:13', '2014-07-22 14:25:13', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'vCpbqbavgbhTtHImsp5QNQ==', '2014-07-22 11:25:40', '2014-07-22 11:25:40'),
	(224, '5370b755bf4efccb31ad6f90', '201348095960', '28927114', 'success', '2014-07-22 14:31:07', '2014-07-22 14:31:07', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', '35K3Sk3L4xA2EQkAlPjURA==', '2014-07-22 11:31:39', '2014-07-22 11:31:39'),
	(225, '5370b755bf4efccb31ad6f90', '201348095960', '28927149', 'success', '2014-07-22 14:32:43', '2014-07-22 14:32:43', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', '44hcl4TIVDxgcTtKkS/4HA==', '2014-07-22 11:33:10', '2014-07-22 11:33:10'),
	(226, '5370b755bf4efccb31ad6f90', '201348095960', '28927161', 'success', '2014-07-22 14:33:04', '2014-07-22 14:33:04', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'MCzaV2WhLgjgklsUToOIJg==', '2014-07-22 11:33:31', '2014-07-22 11:33:31'),
	(227, '5370b755bf4efccb31ad6f90', '201348095960', '28927906', 'success', '2014-07-22 15:08:43', '2014-07-22 15:08:43', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'vrX1Hu0h1ESgPO618gA7Qw==', '2014-07-22 12:09:10', '2014-07-22 12:09:10'),
	(228, '5370b755bf4efccb31ad6f90', '201348095960', '28928483', 'success', '2014-07-22 15:35:26', '2014-07-22 15:35:26', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'OdFBgIQ43OjoLlgvGfOUAA==', '2014-07-22 12:35:53', '2014-07-22 12:35:53'),
	(229, '5370b755bf4efccb31ad6f90', '201348095960', '28928587', 'success', '2014-07-22 15:40:18', '2014-07-22 15:40:18', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'zHI7JyfKAStSmrznf9BqJw==', '2014-07-22 12:40:46', '2014-07-22 12:40:46'),
	(230, '5370b755bf4efccb31ad6f90', '201348095960', '28928787', 'success', '2014-07-22 15:49:13', '2014-07-22 15:49:13', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'NmxcX0Bp+Yx8XxNF5u+qBQ==', '2014-07-22 12:49:41', '2014-07-22 12:49:41'),
	(231, '5370b755bf4efccb31ad6f90', '201348095960', '28929540', 'success', '2014-07-22 16:25:49', '2014-07-22 16:25:49', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'yTO8eEz+DL76gPFFXAkjGw==', '2014-07-22 13:26:17', '2014-07-22 13:26:17'),
	(232, '5370b755bf4efccb31ad6f90', '201348095960', '28930176', 'success', '2014-07-22 16:54:29', '2014-07-22 16:54:29', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '110.00', 'USD', '108.3500', '111.65', 'izxco1mqVkcH1V7T+jFN5w==', '2014-07-22 13:54:57', '2014-07-22 13:54:57'),
	(233, '5370b755bf4efccb31ad6f90', '201348095960', '28930269', 'success', '2014-07-22 16:58:14', '2014-07-22 16:58:14', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '130.00', 'USD', '128.0500', '131.95', 'InOyziXwf6M7nVO++JZXlg==', '2014-07-22 13:58:41', '2014-07-22 13:58:41'),
	(234, '5370b755bf4efccb31ad6f90', '201348095960', '28930365', 'success', '2014-07-22 17:02:24', '2014-07-22 17:02:24', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'fvAqpPAQ82OlXHIHqBwf4w==', '2014-07-22 14:02:52', '2014-07-22 14:02:52'),
	(235, '5370b755bf4efccb31ad6f90', '201348095960', '28930456', 'success', '2014-07-22 17:05:55', '2014-07-22 17:05:55', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'ymTtQYHx43XpjoNQ0o5NbQ==', '2014-07-22 14:06:23', '2014-07-22 14:06:23'),
	(236, '5370b755bf4efccb31ad6f90', '201348095960', '28930498', 'success', '2014-07-22 17:07:52', '2014-07-22 17:07:52', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'Sfg73xDjHgyu3MOwcrRCCQ==', '2014-07-22 14:08:23', '2014-07-22 14:08:23'),
	(237, '5370b755bf4efccb31ad6f90', '201348095960', '28930554', 'success', '2014-07-22 17:10:20', '2014-07-22 17:10:20', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'vkwxfE83nSkdVUpnHsO30w==', '2014-07-22 14:10:48', '2014-07-22 14:10:48'),
	(238, '5370b755bf4efccb31ad6f90', '201348095960', '28930583', 'success', '2014-07-22 17:11:38', '2014-07-22 17:11:38', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '10.00', 'USD', '9.8500', '10.15', 'NSjRy6zdfdPf+Yn/dQTHrg==', '2014-07-22 14:12:06', '2014-07-22 14:12:06'),
	(239, '5370b755bf4efccb31ad6f90', '201348095960', '28930603', 'success', '2014-07-22 17:12:54', '2014-07-22 17:12:54', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'kUw7JLuc2wT6gFTK0q8dQA==', '2014-07-22 14:13:22', '2014-07-22 14:13:22'),
	(240, '5370b755bf4efccb31ad6f90', '201348095960', '28931385', 'success', '2014-07-22 17:50:04', '2014-07-22 17:50:04', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '130.00', 'USD', '128.0500', '131.95', '3zgCsj1nJvn1j5+sVmiw7w==', '2014-07-22 14:50:32', '2014-07-22 14:50:32'),
	(241, '5370b755bf4efccb31ad6f90', '201348095960', '28931571', 'success', '2014-07-22 17:57:38', '2014-07-22 17:57:38', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '80.00', 'USD', '78.8000', '81.20', 'dM+NZF0uXRlvSRqMT1qVjg==', '2014-07-22 14:58:06', '2014-07-22 14:58:06'),
	(242, '5370b755bf4efccb31ad6f90', '201348095960', '28931826', 'success', '2014-07-22 18:10:14', '2014-07-22 18:10:14', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'St+DZshtfBxJ3qiJVvUNhw==', '2014-07-22 15:10:41', '2014-07-22 15:10:41'),
	(243, '5370b755bf4efccb31ad6f90', '201348095960', '28931968', 'success', '2014-07-22 18:16:24', '2014-07-22 18:16:24', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '50.00', 'USD', '49.2500', '50.75', 'JiOdlx8IaafMsS9jCO54Ug==', '2014-07-22 15:16:52', '2014-07-22 15:16:52'),
	(244, '5370b755bf4efccb31ad6f90', '201348095960', '28932510', 'success', '2014-07-22 18:40:05', '2014-07-22 18:40:05', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '42.00', 'USD', '41.3700', '42.63', 'yw1inCF80FBKpyAUH5UZUw==', '2014-07-22 15:40:33', '2014-07-22 15:40:33'),
	(245, '5370b755bf4efccb31ad6f90', '201348095960', '28932601', 'success', '2014-07-22 18:43:27', '2014-07-22 18:43:27', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::4', 'test_interkassa_test_xts', '100.00', 'USD', '98.5000', '101.50', 'seyGFCDnSQcg9UhqHGEOwg==', '2014-07-22 15:43:55', '2014-07-22 15:43:55'),
	(246, '5370b755bf4efccb31ad6f90', '201348095960', '28917757', 'success', '2014-07-22 00:25:56', '2014-07-22 00:25:56', '', 'menu', 'yuriikrevnyi@gmail.com::license.dev::2', 'test_interkassa_test_xts', '30.00', 'USD', '29.5500', '30.45', 'u0JNCi/Pov5xXug4jndl9g==', '2014-07-22 23:50:31', '2014-07-22 23:50:31'),
	(247, '5370b755bf4efccb31ad6f90', '201348095960', '28943874', 'success', '2014-07-23 10:50:44', '2014-07-23 10:50:44', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', '0dXsV4FxSuLf+WTXGdaREA==', '2014-07-23 07:51:12', '2014-07-23 07:51:12'),
	(248, '5370b755bf4efccb31ad6f90', '201348095960', '28944007', 'success', '2014-07-23 10:57:47', '2014-07-23 10:57:47', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'REwC8i1wnUpiwjq2Edqrvg==', '2014-07-23 07:58:15', '2014-07-23 07:58:15'),
	(249, '5370b755bf4efccb31ad6f90', '201348095960', '28944368', 'success', '2014-07-23 11:18:27', '2014-07-23 11:18:27', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'QIwNrK++I7L7uxoy+AQ3/w==', '2014-07-23 08:18:56', '2014-07-23 08:18:56'),
	(250, '5370b755bf4efccb31ad6f90', '201348095960', '28944747', 'success', '2014-07-23 11:38:42', '2014-07-23 11:38:42', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '42.00', 'USD', '41.3700', '42.63', '65tfFKVb7EZc15dLAUgoLQ==', '2014-07-23 08:39:10', '2014-07-23 08:39:10'),
	(251, '5370b755bf4efccb31ad6f90', '201348095960', '28945027', 'success', '2014-07-23 11:54:28', '2014-07-23 11:54:28', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'sHIygdCeEAhYIU8Nl8JYJg==', '2014-07-23 08:54:56', '2014-07-23 08:54:56'),
	(252, '5370b755bf4efccb31ad6f90', '201348095960', '28964803', 'success', '2014-07-24 11:51:33', '2014-07-24 11:51:33', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '31.00', 'USD', '30.5350', '31.47', 'nie93+NbnnYOsqIpdvqR8w==', '2014-07-24 08:52:02', '2014-07-24 08:52:02'),
	(253, '5370b755bf4efccb31ad6f90', '201348095960', '28966410', 'success', '2014-07-24 13:22:25', '2014-07-24 13:22:25', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::1', 'test_interkassa_test_xts', '31.00', 'USD', '30.5350', '31.47', 'xXu00/WSdopnjLJqgEfWng==', '2014-07-24 10:22:54', '2014-07-24 10:22:54'),
	(254, '5370b755bf4efccb31ad6f90', '201348095960', '28967047', 'success', '2014-07-24 13:55:54', '2014-07-24 13:55:54', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'xlJheUwlOkPbB9DxTPg9Hg==', '2014-07-24 10:56:23', '2014-07-24 10:56:23'),
	(255, '5370b755bf4efccb31ad6f90', '201348095960', '28967864', 'success', '2014-07-24 14:36:53', '2014-07-24 14:36:53', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'fRTJ130z983G/tpAvhHbNA==', '2014-07-24 11:37:21', '2014-07-24 11:37:21'),
	(256, '5370b755bf4efccb31ad6f90', '201348095960', '28967947', 'success', '2014-07-24 14:40:53', '2014-07-24 14:40:53', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '42.00', 'USD', '41.3700', '42.63', 'X0ZU6cNPopBG98h9hcDRWA==', '2014-07-24 11:41:22', '2014-07-24 11:41:22'),
	(257, '5370b755bf4efccb31ad6f90', '201348095960', '28969074', 'success', '2014-07-24 15:37:26', '2014-07-24 15:37:26', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'rrHWtpQ23EuDDz2RoZ16EQ==', '2014-07-24 12:37:55', '2014-07-24 12:37:55'),
	(258, '5370b755bf4efccb31ad6f90', '201348095960', '28969780', 'success', '2014-07-24 16:14:33', '2014-07-24 16:14:33', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '42.00', 'USD', '41.3700', '42.63', 'XtCfHpVK/bMGs8k2Hyu0jQ==', '2014-07-24 13:15:03', '2014-07-24 13:15:03'),
	(259, '5370b755bf4efccb31ad6f90', '201348095960', '28970112', 'success', '2014-07-24 16:33:24', '2014-07-24 16:33:24', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'DkPvjFK5l0P6AObv24HtZw==', '2014-07-24 13:33:53', '2014-07-24 13:33:53'),
	(260, '5370b755bf4efccb31ad6f90', '201348095960', '28970202', 'success', '2014-07-24 16:36:16', '2014-07-24 16:36:16', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '42.00', 'USD', '41.3700', '42.63', 'JgCKM6DyHyTqSuBEcl5aOQ==', '2014-07-24 13:36:45', '2014-07-24 13:36:45'),
	(261, '5370b755bf4efccb31ad6f90', '201348095960', '28970416', 'success', '2014-07-24 16:46:17', '2014-07-24 16:46:17', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::2', 'test_interkassa_test_xts', '40.00', 'USD', '39.4000', '40.60', 'yVo6896ika1rkW1Q+zt84w==', '2014-07-24 13:46:46', '2014-07-24 13:46:46'),
	(262, '5370b755bf4efccb31ad6f90', '201348095960', '28970467', 'success', '2014-07-24 16:48:38', '2014-07-24 16:48:38', '', 'menu', 'yuriikrevnyi@gmail.com::opencart.dev::3', 'test_interkassa_test_xts', '42.00', 'USD', '41.3700', '42.63', 'tTUstjuES1AZkH0UPX4YKQ==', '2014-07-24 13:49:07', '2014-07-24 13:49:07');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;


-- Dumping structure for таблиця laravel_license.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table laravel_license.users: ~9 rows (приблизно)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 's111dsdsd@maiul.ru', '$2y$10$c4kpgNp6l1Xv9R8RIsm12uiHiX8zRF9oR2EbSCUkzojm6BH7axwTy', 'HRJpHC9jERgaHcpjsDG5xhcmlxmSSWTHbSb1fHCQCgcEoTXntWGpCOPGXqLG', '2014-07-31 12:10:32', '2014-07-31 12:10:34'),
	(2, 'sd11111sdsd@maiul.ru', '$2y$10$/2DSh//Uxc6WXmbjNFab7uYyzjKiStSxFXFd3uc/26T.5tcQzL9Ie', 'cc59SdPKfhT784jlnqwdI8a2P2VsysEz4EdRjShMe0DBy81AkLdUfHsLYupy', '2014-07-31 12:10:41', '2014-07-31 12:12:13'),
	(3, 'ss@ss.ss', '$2y$10$MCp2KXPN/Z/.9OHC70LqG.zayYQ7JvTvyXpgENke0R9Yg.ynaeqvm', 'ItWz03BCv0ODLPzbLslImJnr2vvnY2EEfTVszWSQnnCcnmdv0XNOeQZhUWU7', '2014-07-31 12:12:23', '2014-07-31 12:25:56'),
	(4, 's111212s@ss.ss', '$2y$10$gHZ67r81ru/xTe2BM74LmelOZ.eIO8JU1FC5chkivAZ1t3kxRU27q', '2mK747Sl9XvNLakxIn4F8aqCtuw0jbwa6sDv1BupcJNQQesKLFeoSFqgDdPl', '2014-07-31 12:28:02', '2014-07-31 12:28:05'),
	(5, 'test@tttt.tt', '$2y$10$yD/7B4gpftu1H6GMMVK1jOfCq.2OXHdwCv37.eSqBfeR0sxJeVDTq', 'lATqHkc3yuc03s89C8EWUoYu96NaXKdbDykXwJIN47MfIRzkOBg8hV0cXFOf', '2014-07-31 12:39:09', '2014-07-31 12:39:13'),
	(6, 'djomaxxx@mail.ru', '$2y$10$MHlODyGknwAVhxOf2tMLgOipLNTVhQN76yhfE8k/qFvp25Pr/Ohw2', 'dPFSfITFXLimoBK7YGDMyEv51hXLPvTUUTBov0ZBAmHLzI5iPMSWRYHBJpq6', '2014-07-31 12:39:40', '2014-07-31 14:31:47'),
	(7, 'ol@ol.ru', '$2y$10$Hy16evACzpQ4HiTshv1R.epNUD64mO1jc.28wdsMCeG6KsI6RdeKe', 'wCw9RmUG6cZuE85R4Ay2oHBlQVhPqOYBYcAkDzHEcsnQpfHwfLiLwNx1XahO', '2014-07-31 14:08:35', '2014-07-31 14:08:36'),
	(8, 'test@tttt.tterer', '$2y$10$6r4TmQqqlMj/CjmadILONed/.Cw13q6hubGFcHHZw0c6NI8/3QU0a', '1sGJPEyV4l69yI5VysVe6KmrDMLGdysOgXm5ooFPkjjYupsnQxiWhcjYsj8Z', '2014-07-31 14:12:39', '2014-07-31 14:12:42'),
	(9, 'test@tttt.ttee', '$2y$10$j8MThVKBm0JYhOqWWydjTeK4vpubBT75iVkSnnbH4m.Kt5OeJ2bSC', 'DFKwIcNlu3s7fNCnc5897SxhJJ0ibbSct7BTdEGcUv0cGMemUP2bv3iwL1MB', '2014-07-31 14:26:55', '2014-07-31 14:26:57');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
