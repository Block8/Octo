<?php
$queries = [];

$queries[] = "CREATE TABLE 'spider_dead_link' (
  'id' int(11) unsigned NOT NULL AUTO_INCREMENT,
  'url' varchar(255) DEFAULT NULL,
  'parent_url' varchar(255) DEFAULT NULL,
  'first_scan_epoch' INTEGER DEFAULT NULL,  
  'last_scan_epoch' INTEGER DEFAULT NULL,
  'http_response_code' INTEGER DEFAULT NULL,  
  PRIMARY KEY ('id')
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
