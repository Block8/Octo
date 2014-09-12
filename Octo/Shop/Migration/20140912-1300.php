<?php
$queries = [];

$queries[] = "CREATE TABLE `thankq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(50) DEFAULT NULL,
  `invoice_id` int(11) unsigned DEFAULT NULL,
  `giftaid_result` varchar(50) DEFAULT NULL,
  `donation_id` varchar(50) DEFAULT NULL,
  `productgroups_amounts` text,
  `productgroups_donationids` text,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `thankq_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;";
