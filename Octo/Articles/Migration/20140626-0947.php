<?php

$queries = [];
$queries[] = "ALTER TABLE `article` ADD `guest_author_name` VARCHAR(255) DEFAULT NULL;";
$queries[] = "ALTER TABLE `article` ADD `guest_company_name` VARCHAR(255) DEFAULT NULL;";
$queries[] = "ALTER TABLE `article` ADD `guest_company_url` VARCHAR(255) DEFAULT NULL;";
