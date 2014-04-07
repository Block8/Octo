<?php
$queries = [];

$queries[] = "CREATE TABLE `order` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `total_paid` decimal(10,2) DEFAULT NULL,
  `order_status_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_id` (`order_status_id`),
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `order_status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(255) NOT NULL DEFAULT '',
  `protected` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";

$queries[] = "INSERT INTO `order_status` (`id`, `status`, `code`, `protected`)
VALUES
	(1, 'Open', 'new', 1);
";

$queries[] = "CREATE TABLE `item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `short_description` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `description` text CHARACTER SET latin1,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `line_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned DEFAULT NULL,
  `quantity` int(11) unsigned DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `line_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `line_item_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `line_item_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `item_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `file_id` char(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `item_file_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_file_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `order_adjustment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `scope` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_adjustment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "RENAME TABLE `order` TO `invoice`;";

$queries[] = "RENAME TABLE `order_adjustment` TO `invoice_adjustment`;";

$queries[] = "RENAME TABLE `order_status` TO `invoice_status`;";

$queries[] = "ALTER TABLE `invoice_adjustment` DROP FOREIGN KEY `invoice_adjustment_ibfk_1`;";

$queries[] = "ALTER TABLE `invoice_adjustment` CHANGE `order_id` `invoice_id` INT(10)  UNSIGNED  NOT NULL;";

$queries[] = "ALTER TABLE `invoice_adjustment` ADD FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

$queries[] = "ALTER TABLE `invoice` DROP FOREIGN KEY `invoice_ibfk_1`;";

$queries[] = "ALTER TABLE `invoice` CHANGE `order_status_id` `invoice_status_id` INT(11)  UNSIGNED  NULL  DEFAULT NULL;";

$queries[] = "ALTER TABLE `invoice` ADD FOREIGN KEY (`invoice_status_id`) REFERENCES `invoice_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;";

$queries[] = "ALTER TABLE `line_item` DROP FOREIGN KEY `line_item_ibfk_2`;";

$queries[] = "ALTER TABLE `line_item` CHANGE `order_id` `invoice_id` INT(11)  UNSIGNED  NOT NULL;";

$queries[] = "ALTER TABLE `line_item` ADD FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
