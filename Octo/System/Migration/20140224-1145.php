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
            (1, 'admin', '$2y$10$2Qf/2vM0VRJP4pEvjco.CO7TKpBXsWtIjYQS9ekrRyvV3XSD994pK', 'Admin', 1, 1);",

    "CREATE TABLE `content_item` (
      `id` char(32) NOT NULL DEFAULT '',
      `content` longtext NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
];