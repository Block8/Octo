<?php
$queries = [];

$queries[] = "CREATE TABLE `permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `uri` varchar(500) NOT NULL DEFAULT '',
  `can_access` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_quick_check` (`user_id`,`uri`(255),`can_access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$queries[] = "CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `scope` varchar(32) DEFAULT NULL,
  `scope_id` varchar(32) NOT NULL DEFAULT '',
  `user_id` int(11) unsigned DEFAULT NULL,
  `message` varchar(500) NOT NULL DEFAULT '',
  `log_date` datetime NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scope` (`scope`),
  KEY `type` (`type`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";