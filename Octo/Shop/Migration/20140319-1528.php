<?php
$queries = [];
$queries[] = "ALTER TABLE `item` ADD `price` DECIMAL(10,2)  NOT NULL  AFTER `active`;";
$queries[] = "ALTER TABLE `item` MODIFY COLUMN `price` DECIMAL(10,2) NOT NULL AFTER `description`;";
$queries[] = "ALTER TABLE `item` ADD `expiry_date` DATETIME  NULL  AFTER `active`;";
