<?php

namespace Octo;

use b8\Config;

abstract class Controller extends \b8\Controller
{
    /**
     * b8 framework requires that controllers have an init() method
     */
    public function init()
    {

    }

    public function handleAction($action, $params)
    {
        $output = parent::handleAction($action, $params);
        $this->response->setContent($output);

        return $this->response;
    }

    public static function getClass($controller) {
        $config = Config::getInstance();
        $siteModules = $config->get('site.modules');

        foreach ($siteModules as $namespace => $modules) {
            foreach ($modules as $module) {
                $class = "\\{$namespace}\\{$module}\\Controller\\{$controller}Controller";

                if (class_exists($class)) {
                    return $class;
                }
            }
        }

        return null;
    }
}
