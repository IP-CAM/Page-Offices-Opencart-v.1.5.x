-- ----------------------------
-- Table structure for `oc_offices`
-- ----------------------------
DROP TABLE IF EXISTS `oc_offices`;
CREATE TABLE `oc_offices` (
  `office_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `sort_order` int(3) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `phone` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  PRIMARY KEY (`office_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `oc_offices_descriptions`
-- ----------------------------
DROP TABLE IF EXISTS `oc_offices_descriptions`;
CREATE TABLE `oc_offices_descriptions` (
  `office_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`office_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
