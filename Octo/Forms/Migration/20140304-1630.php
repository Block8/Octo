<?php

$queries = [
    "CREATE TABLE `form` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `recipients` text,
  `definition` mediumtext NOT NULL,
  `thankyou_message` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

    "CREATE TABLE `submission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(11) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  `extra` mediumtext,
  `message` mediumtext,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `contact_id` (`contact_id`),
  CONSTRAINT `submission_ibfk_1`
    FOREIGN KEY (`form_id`) REFERENCES `form` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `submission_ibfk_2`
    FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
];
