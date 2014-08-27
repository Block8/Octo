<?php
$queries = [];
$queries[] = "ALTER TABLE `contact` CHANGE `country_code` `country_code` VARCHAR(2)  NULL  DEFAULT NULL;";
$queries[] = "ALTER TABLE `invoice_adjustment` CHANGE `shipping_cost` `shipping_cost` DECIMAL(10,2)  NULL  DEFAULT NULL;";