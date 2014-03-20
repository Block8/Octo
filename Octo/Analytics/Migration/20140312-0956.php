<?php
$queries = [];
$queries[] = 'CREATE TABLE `ga_page_view` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`date` date DEFAULT NULL,
`updated` datetime DEFAULT NULL,
`value` int(11) DEFAULT NULL,
`metric` varchar(255) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
$queries[] = "ALTER TABLE `ga_page_view` ADD UNIQUE INDEX (`metric`, `date`);";
$queries[] = "CREATE TABLE `ga_summary_view` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `updated` datetime DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `metric` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `metric` (`metric`)
) ENGINE=InnoDB AUTO_INCREMENT=379 DEFAULT CHARSET=utf8;";
$queries[] = "CREATE TABLE `ga_top_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `updated` datetime DEFAULT NULL,
  `pageviews` int(11) DEFAULT NULL,
  `unique_pageviews` int(11) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `page_id` char(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `ga_top_page_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$queries[] = "INSERT INTO `setting` (`id`, `key`, `value`, `scope`, `hidden`)
VALUES
	(NULL, 'ga_email', NULL, 'analytics', 0),
	(NULL, 'ga_password', NULL, 'analytics', 0),
	(NULL, 'ga_profile_id', NULL, 'analytics', 0);";
