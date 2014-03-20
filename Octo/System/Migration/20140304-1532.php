<?php
$queries = [];

$queries[] = 'ALTER TABLE `permission` CHANGE `user_id` `user_id` INT(11)  UNSIGNED  NOT NULL;';

$queries[] = 'ALTER TABLE `permission` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
ON DELETE CASCADE ON UPDATE CASCADE;';
