<?php
$queries = [];

$queries[] = "ALTER TABLE `category` ADD `image` CHAR(32)  NULL  DEFAULT NULL  AFTER `parent_id`;";
$queries[] = "ALTER TABLE `category` ADD FOREIGN KEY (`image`) REFERENCES `file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;";
$queries[] = "ALTER TABLE `category` ADD `description` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `image`;";
$queries[] = "ALTER TABLE `category` CHANGE `image` `image_id` CHAR(32)  CHARACTER SET utf8  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE `category` ADD INDEX (`slug`);";
$queries[] = "ALTER TABLE `category` ADD UNIQUE INDEX (`scope`, `slug`);";
