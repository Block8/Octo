<?php
$queries = [];

$queries[] = "INSERT INTO `category` (`id`, `name`, `slug`, `scope`, `parent_id`)
VALUES
	(NULL, 'Images', 'images', 'images', NULL),
	(NULL, 'Files', 'files', 'files', NULL),
	(NULL, 'News', 'news', 'news', NULL);";
