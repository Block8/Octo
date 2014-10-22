<?php
$queries = [];

$queries[] = "CREATE TABLE `ecard` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` text,
  `to_email` varchar(12800) DEFAULT NULL,
  `from_email` varchar(128) DEFAULT NULL,
  `item_id` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `ecard_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
