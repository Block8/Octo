<?php
$queries = [];
$queries[] = "ALTER TABLE `user` ADD `date_added` DATE  NULL  AFTER `is_hidden`;";
$queries[] = "ALTER TABLE `user` ADD `active` TINYINT(1)  NULL  DEFAULT '1'  AFTER `date_added`;";
