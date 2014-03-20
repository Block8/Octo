<?php

$queries = [
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