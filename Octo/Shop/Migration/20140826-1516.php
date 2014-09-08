<?php
//LPP
$queries = [];
$queries[] = "ALTER TABLE `contact` CHANGE `country_code` `country_code` VARCHAR(3)  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE `invoice` CHANGE `shipping_cost` `shipping_cost` DECIMAL(10,2)  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE `invoice` CHANGE `country_code` `country_code` VARCHAR(3)  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE invoice AUTO_INCREMENT = 10;"; //UniqueId for RSM is taken from invoice.id,, but it needs to be 2 digits min.
$queries[] = "ALTER TABLE `invoice_adjustment` ADD `gift_aid` TINYINT(1)  NOT NULL  DEFAULT '0'  AFTER `gift_aid`;";
$queries[] = "ALTER TABLE `item` ADD `activeCopy` TINYINT(1)  NULL  DEFAULT '1'  AFTER `fulfilment_house_id`;";
$queries[] = "ALTER TABLE `item_file` ADD UNIQUE INDEX (`item_id`, `file_id`);"; //one image/file per product