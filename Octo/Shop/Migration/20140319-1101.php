<?php
$queries = [];
$queries[] = "ALTER TABLE `invoice_status` ADD `price` DECIMAL(10,2)  NULL  DEFAULT NULL  AFTER `protected`;";

$queries[] = "CREATE TABLE `variant` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `variant_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `variant_id` int(11) unsigned NOT NULL,
  `option` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `variant_id` (`variant_id`),
  CONSTRAINT `variant_option_ibfk_1`
    FOREIGN KEY (`variant_id`) REFERENCES `variant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `item_variant` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(11) unsigned NOT NULL,
  `variant_id` int(11) unsigned NOT NULL,
  `variant_option_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `variant_id` (`variant_id`),
  KEY `variant_option_id` (`variant_option_id`),
  CONSTRAINT `item_variant_ibfk_3`
    FOREIGN KEY (`variant_option_id`) REFERENCES `variant_option` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_variant_ibfk_1`
    FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_variant_ibfk_2`
    FOREIGN KEY (`variant_id`) REFERENCES `variant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
