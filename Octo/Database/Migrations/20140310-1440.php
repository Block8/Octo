<?php
$queries = [];
$queries[] = 'CREATE TABLE `file_download` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` char(32) DEFAULT NULL,
  `downloaded` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
$queries[] = 'ALTER TABLE `file_download` ADD FOREIGN KEY (`file_id`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;';
