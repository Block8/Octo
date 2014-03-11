<?php

$queries = [
    "ALTER TABLE `page_version` ADD `image_id` CHAR(32)  NULL  DEFAULT NULL  AFTER `template`;",
    "ALTER TABLE `page_version` CHANGE `image_id` `image_id` CHAR(32)  CHARACTER SET utf8  NULL  DEFAULT NULL;",
    "ALTER TABLE `page_version` ADD FOREIGN KEY (`image_id`) REFERENCES `file` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;",
];



