<?php
$queries = [];

$queries[] = "ALTER TABLE `line_item` ADD `ecard_hash` VARCHAR(32)  NULL  DEFAULT NULL  AFTER `meta_data`;";

