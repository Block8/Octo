<?php

$queries = [

    "CREATE TABLE `article` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `title` varchar(255) DEFAULT NULL,
      `summary` text,
      `user_id` int(11) unsigned DEFAULT NULL,
      `category_id` int(11) unsigned DEFAULT NULL,
      `author_id` int(11) unsigned DEFAULT NULL,
      `content_item_id` char(32) DEFAULT NULL,
      `created_date` datetime DEFAULT NULL,
      `updated_date` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `category_id` (`category_id`),
      KEY `author_id` (`author_id`),
      KEY `content_item_id` (`content_item_id`),
      CONSTRAINT `article_ibfk_1`
        FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `article_ibfk_2`
        FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `article_ibfk_3`
        FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `article_ibfk_4`
        FOREIGN KEY (`content_item_id`) REFERENCES `content_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

];
