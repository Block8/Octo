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

];