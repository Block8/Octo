<?php

$queries = [
    "CREATE TABLE `search_index` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(50) NOT NULL DEFAULT '',
  `model` varchar(50) NOT NULL DEFAULT '',
  `content_id` varchar(32) NOT NULL DEFAULT '',
  `instances` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_search` (`word`,`instances`,`model`,`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
];
