<?php
$queries = [];

$queries[] = "CREATE TABLE `discount` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `discount_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) unsigned NOT NULL,
  `amount_initial` int(11) NOT NULL,
  `amount_final` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  CONSTRAINT `discount_option_ibfk_1` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `item_discount` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned DEFAULT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  `discount_id` int(11) unsigned NOT NULL,
  `discount_option_id` int(11) unsigned NOT NULL,
  `price_adjustment` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_id_2` (`item_id`,`discount_id`,`discount_option_id`),
  UNIQUE KEY `item_id_3` (`item_id`,`discount_id`,`discount_option_id`),
  KEY `item_id` (`item_id`),
  KEY `variant_id` (`discount_id`),
  KEY `variant_option_id` (`discount_option_id`),
  CONSTRAINT `item_discount_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_discount_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_discount_ibfk_3` FOREIGN KEY (`discount_option_id`) REFERENCES `discount_option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";