<?php
$queries = [];

$queries[] = "CREATE TABLE `content_block` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `title` varchar(100) NOT NULL DEFAULT '',
  `uri` varchar(500) NOT NULL DEFAULT '/',
  `group` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `position` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

