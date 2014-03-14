<?php

// Set up constants:
if (!defined('CMS_PATH')) {
    define('CMS_PATH', dirname(__FILE__) . '/');
    define('CMS_BASE_PATH', dirname(CMS_PATH . '../'));
}

date_default_timezone_set('Europe/London');

// Set up autoloaders:
require_once(APP_PATH . 'vendor/autoload.php');

$loader = function ($class) {
    $file = str_replace(array('\\', '_'), '/', $class);
    $file .= '.php';

    if (substr($file, 0, 1) == '/') {
        $file = substr($file, 1);
    }

    if (is_file(APP_PATH . $file)) {
        include(APP_PATH . $file);
        return;
    }
};

spl_autoload_register($loader, true, true);

$_SETTINGS                                       = [];
$_SETTINGS['b8']['app']['namespace']             = 'Octo';
$_SETTINGS['b8']['app']['default_controller']    = null;
$_SETTINGS['b8']['view']['path']                 = CMS_PATH . 'View/';
$_SETTINGS['app']['namespaces']                   = ['default' => 'Octo'];

// Set up config:
if (is_file(APP_PATH . 'siteconfig.php')) {
    require_once(APP_PATH . 'siteconfig.php');
}

$config = new b8\Config($_SETTINGS);

if (!defined('IS_CONSOLE')) {
    try {
        $appClass = $config->get('site.namespace') . '\\Application';

        if (class_exists($appClass)) {
            $app = new $appClass($config);
        } else {
            $app = new Octo\Application($config);
        }

        $response = $app->handleRequest();

        die($response);

    } catch (Exception $ex) {
        // Global everything has broken catch-all handler.
        throw $ex;
    }
}
