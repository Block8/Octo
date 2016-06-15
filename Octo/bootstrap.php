<?php

// Set up constants:
if (!defined('CMS_PATH')) {
    define('CMS_PATH', dirname(__FILE__) . '/');
    define('CMS_BASE_PATH', realpath(CMS_PATH . '../') . '/');
}

if (isset($_SERVER['APPLICATION_ENV']) && !defined('CMS_ENV')) {
    define('CMS_ENV', $_SERVER['APPLICATION_ENV']);
}

if (!defined('APP_PATH')) {
    if (stripos(CMS_BASE_PATH, 'vendor/block8/octo') !== false) {
        define('APP_PATH', realpath(CMS_BASE_PATH . '../../../') . '/');
    } else {
        define('APP_PATH', getcwd() . '/');
    }
}

date_default_timezone_set('Europe/London');

error_reporting(E_ALL ^ E_STRICT);

// Set up xdebug
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

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
$_SETTINGS['app']['namespaces']                   = [];

$config = new b8\Config($_SETTINGS);
$moduleManager = new Octo\ModuleManager();
$moduleManager->setConfig($config);
$moduleManager->enable('Octo', 'System');

// Set up config:
if (is_file(APP_PATH . 'siteconfig.php')) {
    require_once(APP_PATH . 'siteconfig.php');
}

$_SETTINGS['site']['full_admin_url'] = $_SETTINGS['site']['url'] . '/' . $_SETTINGS['site']['admin_uri'];
$_SETTINGS['Octo']['AssetManager'] = new \Octo\AssetManager();

$config->setArray($_SETTINGS);
$moduleManager->initialiseModules();

$templatePath = realpath(APP_PATH . $_SETTINGS['site']['namespace'] . '/Template');
define('SITE_TEMPLATE_PATH', $templatePath);

if (is_dir($templatePath)) {
    $settings = $config->get('Octo');
    $settings['paths']['templates'][] = $templatePath . '/';
    $config->set('Octo', $settings);
}

//set up ADMIN_URI constant, throws exception if the config value isn't set.
if (!defined('ADMIN_URI')) {
    if ($config->get('site.admin_uri') === null) {
        throw new Exception('site.admin_uri has not been set in the siteconfig');
    } else {
        define('ADMIN_URI', $config->get('site.admin_uri'));
    }
}

$adminTemplatePath = realpath(APP_PATH . $_SETTINGS['site']['namespace'] . '/Admin/Template');

if (is_dir($adminTemplatePath)) {
    $settings = $config->get('Octo');
    $settings['paths']['admin_templates'][] = $adminTemplatePath . '/';
    $config->set('Octo', $settings);
}

$rtn = 0;
exec('wkhtmltopdf --version 2>&1', $out, $rtn);

if ($rtn == 0) {
    define('SYSTEM_PDF_AVAILABLE', true);
} else {
    define('SYSTEM_PDF_AVAILABLE', false);
}


$cachePath = realpath(APP_PATH . 'storage/cache/');

if ($cachePath && is_dir($cachePath) && is_writeable($cachePath)) {
    define('OCTO_CACHE_PATH', $cachePath);
    define('OCTO_CACHE_ENABLED', true);
} else {
    define('OCTO_CACHE_PATH', null);
    define('OCTO_CACHE_ENABLED', false);
}

// Define whether or not we have queue functionality available:
$host = $config->get('Octo.worker.host');

define('OCTO_QUEUE_ENABLED', !empty($host));

$queue = $config->get('Octo.worker.queue', 'Octo.' . md5($config->get('site.namespace')));

define('OCTO_QUEUE', $queue);


if (!defined('IS_CONSOLE')) {
    try {
        $appClass = $config->get('site.namespace') . '\\Application';

        if (class_exists($appClass)) {
            $app = new $appClass($config);
        } else {
            $app = new Octo\Application($config);
        }

        $response = $app->handleRequest();
        print $response;
    } catch (Exception $ex) {
        // Global everything has broken catch-all handler.
        throw $ex;
    }
}
