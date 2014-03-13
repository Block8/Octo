<?php
$queries = [];
$queries[] = "ALTER TABLE `article` ADD `publish_date` DATE  NULL  AFTER `slug`;";
$queries[] = "ALTER TABLE `article` MODIFY COLUMN `publish_date` DATE DEFAULT NULL AFTER `updated_date`;";
