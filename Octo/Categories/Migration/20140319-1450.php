<?php
$queries = [];
$queries[] = "ALTER TABLE `category` ADD `position` INT(11)  UNSIGNED  NULL  DEFAULT NULL  AFTER `description`;";
