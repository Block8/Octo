<?php

$queries = [
    "CREATE TABLE `category` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `slug` varchar(255) DEFAULT NULL,
      `scope` varchar(100) DEFAULT NULL,
      `parent_id` int(11) unsigned DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `parent_id` (`parent_id`),
      CONSTRAINT `category_ibfk_1`
        FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
];
