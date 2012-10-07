-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.25a - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-09-16 23:38:56
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table eljot.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `unique` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ebay_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_seat` tinyint(1) DEFAULT '0',
  `frame` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bonus` text COLLATE utf8_unicode_ci,
  `wheels` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_receipt` date DEFAULT NULL,
  `date_of_payment` date DEFAULT NULL,
  `insert_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_UNIQUE` (`unique`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table eljot.orders: ~12 rows (approximately)
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`id`, `unique`, `model`, `invoice_id`, `ebay_id`, `color`, `is_seat`, `frame`, `bonus`, `wheels`, `client`, `date_of_receipt`, `date_of_payment`, `insert_time`, `update_time`) VALUES
	(103, '461', 'vesper', '', '', 'c, grafit+czarna skóra (stone)', 0, 'biały', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 'pompa', 'Dzevad Kahmianovic helligkreuzsteinach', '2012-09-26', '2012-09-29', '2012-09-01 23:49:30', '2012-09-01 23:51:23'),
	(104, '462', 'vesper v8', '', '', 'b1', 0, 'nikiel', 'Sed nec risus non nunc porta malesuada vehicula at ante. Ut at nisl pulvinar elit pharetra auctor eleifend sed lacus.', 'pompa irys', 'carina kniese meinerzhagen', '2012-08-09', '2012-08-09', '2012-09-01 23:49:31', '2012-09-02 00:13:24'),
	(105, '463', 'tessa', '', '', 'biały', 0, 'czarny', 'Ut aliquam convallis felis, sit amet aliquam justo posuere non. Aenean mattis lectus ac mauris imperdiet in blandit tellus accumsan.', 'imitacja', 'gosia pellis langenhagen', '2012-09-01', NULL, '2012-09-01 23:49:31', NULL),
	(106, '464', 'vesper v8', '', '', 'b5', 0, 'nikiel', ' Sed eu aliquet erat. Sed ornare vehicula mi, et pellentesque lectus porttitor eu. ', 'pompa irys', 'desiree oehmig neidenstein', '2012-08-30', NULL, '2012-09-01 23:49:31', NULL),
	(107, '465', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-15', NULL, '2012-09-01 23:49:31', NULL),
	(108, '466', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-15', NULL, '2012-09-01 23:49:31', NULL),
	(109, '467', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-15', NULL, '2012-09-01 23:49:31', NULL),
	(110, '468', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-07', NULL, '2012-09-01 23:49:31', NULL),
	(111, '469', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-08', NULL, '2012-09-01 23:49:31', NULL),
	(112, '470', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-06', NULL, '2012-09-01 23:49:31', NULL),
	(113, '471', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-15', NULL, '2012-09-01 23:49:31', NULL),
	(114, '472', 'tessa', '', '', 'niebieski', 0, 'czarny', 'Morbi mi diam, consequat fringilla semper nec, dignissim ut leo. ', 'imitacja', 'ronald cario kiobikau', '2012-09-15', NULL, '2012-09-01 23:49:31', NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
