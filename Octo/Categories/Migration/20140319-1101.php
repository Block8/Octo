<?php
$queries = [];

$queries[] = "ALTER TABLE `category` ADD `image_id` CHAR(32)  NULL  DEFAULT NULL  AFTER `parent_id`;";
$queries[] = "ALTER TABLE `category` ADD FOREIGN KEY (`image_id`) REFERENCES `file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;";
$queries[] = "ALTER TABLE `category` ADD `description` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `image_id`;";
$queries[] = "ALTER TABLE `category` ADD INDEX (`slug`);";
$queries[] = "ALTER TABLE `category` ADD UNIQUE INDEX (`scope`, `slug`);";
