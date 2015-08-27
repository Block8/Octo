<?php
/**
 * @copyright    Copyright 2015, Block 8 Limited.
 * @link         https://www.block8.co.uk/
 */

define('IS_CONSOLE', true);
require_once(dirname(__FILE__) . '/bootstrap.php');

$writeServers = $config->get('b8.database.servers.write');

if (!is_array($writeServers)) {
    $writeServers = [$writeServers];
}

$paths = [];

foreach ($config->get('Octo.paths.modules') as $path) {
    $paths[] = $path . 'Migration/';
}

$conf = [
    'paths' => [
        'migrations' => $paths,
    ],

    'environments' => [
        'default_migration_table' => 'migration',
        'default_database' => 'octo',
        'octo' => [
            'adapter' => 'mysql',
            'host' => end($writeServers),
            'name' => $config->get('b8.database.name'),
            'user' => $config->get('b8.database.username'),
            'pass' => $config->get('b8.database.password'),
        ],
    ],
];

return $conf;
