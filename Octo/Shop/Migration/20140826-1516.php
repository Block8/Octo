<?php
//LPP
$queries = [];
$queries[] = "ALTER TABLE `contact` CHANGE `country_code` `country_code` VARCHAR(3)  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE `invoice` CHANGE `shipping_cost` `shipping_cost` DECIMAL(10,2)  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE `invoice` CHANGE `country_code` `country_code` VARCHAR(3)  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE `invoice_adjustment` ADD `gift_aid` TINYINT(1)  NOT NULL  DEFAULT '0'  AFTER `gift_aid`;";