<?php
$queries = [];

$queries[] = "CREATE TABLE `tweet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `twitter_id` varchar(50) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `html_text` text,
  `screenname` varchar(50) DEFAULT NULL,
  `posted` datetime DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `scope` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `twitter_id` (`twitter_id`),
  KEY `screenname` (`screenname`),
  KEY `scope` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";