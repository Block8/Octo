<?php
$queries = [];

$queries[] = "INSERT INTO `category` (`id`, `name`, `slug`, `scope`, `parent_id`)
VALUES
	(NULL, 'Images', 'images', 'images', NULL),
	(NULL, 'Files', 'files', 'files', NULL),
	(NULL, 'News', 'news', 'news', NULL);";

$queries[] = "CREATE TABLE `file` (
      `id` char(32) NOT NULL DEFAULT '',
      `scope` varchar(50) DEFAULT NULL,
      `category_id` int(11) unsigned DEFAULT NULL,
      `filename` varchar(255) DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `mime_type` varchar(50) DEFAULT NULL,
      `extension` varchar(10) DEFAULT NULL,
      `created_date` datetime DEFAULT NULL,
      `updated_date` datetime DEFAULT NULL,
      `user_id` int(11) unsigned DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `category_id` (`category_id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `file_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `file_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
