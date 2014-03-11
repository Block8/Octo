<?php
$queries = [];

$queries[] = "ALTER TABLE file ADD size INT(11);";

$queries[] = "ALTER TABLE `category` ADD INDEX (`scope`);";