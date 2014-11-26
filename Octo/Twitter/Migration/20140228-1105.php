<?php
$queries = [];

$queries[] = "INSERT INTO `setting` (`key`, `value`, `scope`, `hidden`)
VALUES
	('consumer_key', NULL, 'twitter', 0),
	('consumer_secret', NULL, 'twitter', 0),
	('access_token', NULL, 'twitter', 0),
	('access_token_secret', NULL, 'twitter', 0),
	('last_api_call', NULL, 'twitter', 1),
	('stream_search', NULL, 'twitter', 0);
";
