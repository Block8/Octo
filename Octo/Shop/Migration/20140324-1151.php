<?php
$queries = [];

$queries[] = "ALTER TABLE `item` ADD `slug` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `expiry_date`;";
$queries[] = "ALTER TABLE `item` ADD UNIQUE INDEX (`slug`);";
