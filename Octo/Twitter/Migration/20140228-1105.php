<?php
$queries = [];

$queries[] = "INSERT INTO `setting` (`id`, `key`, `value`, `scope`, `hidden`)
VALUES
	(1, 'consumer_key', NULL, 'twitter', 0),
	(2, 'consumer_secret', NULL, 'twitter', 0),
	(3, 'access_token', NULL, 'twitter', 0),
	(4, 'access_token_secret', NULL, 'twitter', 0),
	(5, 'last_api_call', NULL, 'twitter', 1),
	(6, 'stream_search', NULL, 'twitter', 0);
";
