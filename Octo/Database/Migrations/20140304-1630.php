<?php

$queries = [
    "CREATE TABLE `contact` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL DEFAULT '',
  `phone` varchar(100) DEFAULT NULL,
  `title` varchar(25) DEFAULT NULL,
  `gender` varchar(25) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `address` text,
  `postcode` varchar(10) DEFAULT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `company` varchar(250) DEFAULT NULL,
  `marketing_optin` tinyint(1) NOT NULL DEFAULT '0',
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE `form` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `recipients` text,
  `definition` mediumtext NOT NULL,
  `thankyou_message` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE `submission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(11) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  `extra` mediumtext,
  `message` mediumtext,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `contact_id` (`contact_id`),
  CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
];
