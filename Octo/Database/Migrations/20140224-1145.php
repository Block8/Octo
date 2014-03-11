<?php

$queries = [
    "CREATE TABLE `user` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `email` varchar(250) NOT NULL,
      `hash` varchar(250) NOT NULL,
      `name` varchar(250) DEFAULT NULL,
      `is_admin` tinyint(1) NOT NULL DEFAULT '0',
      `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `idx_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "INSERT INTO `user` (`id`, `email`, `hash`, `name`, `is_admin`, `is_hidden`)
        VALUES
            (1, 'admin@re-systems.co.uk', '\$2y\$10\$ugFF9bOdEoFTDH05VoLRgeK/djl56hmk8hBU0N7PRTUGV6oNF6Deq', 'RE:SYSTEMS', 1, 1);",

    "CREATE TABLE `content_item` (
      `id` char(32) NOT NULL DEFAULT '',
      `content` longtext NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

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
      CONSTRAINT `article_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `article_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `article_ibfk_3` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `article_ibfk_4` FOREIGN KEY (`content_item_id`) REFERENCES `content_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE `category` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(255) DEFAULT NULL,
      `slug` varchar(255) DEFAULT NULL,
      `scope` varchar(100) DEFAULT NULL,
      `parent_id` int(11) unsigned DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `parent_id` (`parent_id`),
      CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE `page` (
      `id` char(5) NOT NULL DEFAULT '',
      `parent_id` char(5) DEFAULT NULL,
      `current_version_id` int(11) unsigned DEFAULT NULL,
      `uri` varchar(500) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`),
      UNIQUE KEY `uniq_page_uri` (`uri`(100)),
      KEY `fk_page_parent` (`parent_id`),
      KEY `fk_page_current_version` (`current_version_id`),
      CONSTRAINT `fk_page_current_version` FOREIGN KEY (`current_version_id`) REFERENCES `page_version` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `fk_page_parent` FOREIGN KEY (`parent_id`) REFERENCES `page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE `page_version` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `page_id` char(5) NOT NULL DEFAULT '',
      `version` int(11) NOT NULL DEFAULT '0',
      `title` varchar(150) DEFAULT NULL,
      `short_title` varchar(50) DEFAULT NULL,
      `description` varchar(250) DEFAULT NULL,
      `meta_description` varchar(250) DEFAULT NULL,
      `content_item_id` char(32) DEFAULT NULL,
      `user_id` int(11) unsigned DEFAULT NULL,
      `updated_date` datetime NOT NULL,
      `template` varchar(250) NOT NULL DEFAULT 'default',
      PRIMARY KEY (`id`),
      KEY `fk_page_version_page` (`page_id`),
      KEY `fk_page_version_user` (`user_id`),
      KEY `fk_page_version_content_item` (`content_item_id`),
      CONSTRAINT `fk_page_version_content_item` FOREIGN KEY (`content_item_id`) REFERENCES `content_item` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
      CONSTRAINT `fk_page_version_page` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `fk_page_version_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
];