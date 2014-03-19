<?php
$queries = [];

$queries[] = "ALTER TABLE `setting` ADD `hidden` TINYINT(1)  NULL  DEFAULT '0'  AFTER `scope`;";