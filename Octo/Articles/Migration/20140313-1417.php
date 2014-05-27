<?php
$queries = [];
$queries[] = "ALTER TABLE `article` ADD `use_in_email` tinyint(1) DEFAULT 0;";
