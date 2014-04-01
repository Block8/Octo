<?php
$queries = [];
$queries[] = "ALTER TABLE `variant_option` CHANGE `option` `option_title` VARCHAR(255)  CHARACTER SET utf8  NOT NULL  DEFAULT '';";
$queries[] = "ALTER TABLE `variant_option` ADD UNIQUE INDEX (`option_title`, `variant_id`);";
$queries[] = "ALTER TABLE `variant_option` ADD `position` INT(11)  UNSIGNED  NULL  DEFAULT NULL  AFTER `option_title`;";
$queries[] = "ALTER TABLE `item_file` CHANGE `position` `position` INT(1)  NULL  DEFAULT NULL;";
