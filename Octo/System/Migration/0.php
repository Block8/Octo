<?php

$queries = [
    "CREATE TABLE `migration` (
      `id` varchar(50) NOT NULL DEFAULT '',
      `date_run` datetime DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
];
