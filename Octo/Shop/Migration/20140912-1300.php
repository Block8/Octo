<?php
$queries = [];

$queries[] = "CREATE TABLE `thankq` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(50) DEFAULT NULL,
  `invoice_id` int(11) unsigned DEFAULT NULL,
  `giftaid_id` varchar(50) DEFAULT NULL,
  `donation_id` varchar(50) DEFAULT NULL,
  `productgroups_amounts` text,
  `productgroups_donationids` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `thankq_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `rsm2000_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) unsigned DEFAULT NULL,
  `donation` decimal(10,2) DEFAULT NULL,
  `purchase` decimal(10,2) DEFAULT NULL,
  `raw_auth_message` text,
  `trans_time` int(11) unsigned DEFAULT NULL,
  `trans_id` varchar(20) DEFAULT NULL,
  `trans_status` char(2) DEFAULT NULL,
  `card_type` varchar(10) DEFAULT NULL,
  `base_status` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `security_pass` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `rsm2000_log_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";