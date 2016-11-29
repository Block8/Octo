<?php

namespace Octo;

use b8\Config;
use Octo\Admin\Controller as AdminController;
use Octo\Http\Response;

abstract class Controller extends \b8\Controller
{
    /**
     * @var Octo\AssetManager
     */
    public $assets;

    /**
     * @var \Octo\Http\Response
     */
    protected $response;

    /**
     * b8 framework requires that controllers have an init() method
     */
    public function init()
    {
        Event::trigger('Controller.Loaded', $this);
        Event::trigger('Controller.Loaded.Public', $this);

        $this->assets = Config::getInstance()->get('Octo.AssetManager');
    }

    public function handleAction($action, $params, $raw = false)
    {
        $output = parent::handleAction($action, $params);

        if ($raw || $output instanceof Response) {
            return $output;
        }

        $this->response->setContent($output);
        return $this->response;
    }

    public static function getClass($controller)
    {
        $config = Config::getInstance();
        $siteModules = $config->get('ModuleManager')->getEnabled();

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

    protected function redirect($to)
    {
        if (!in_array(substr($to, 0, 6), ['http:/', 'https:'])) {
            $to = $this->config->get('site.url') . $to;
        }

        $this->response->setResponseCode(302);
        $this->response->setHeader('Location', $to);

        return $this->response;
    }
    
    public function json($data)
    {
        $this->response->disableLayout();
        $this->response->type('application/json');
        $this->response->setContent(json_encode($data));
        return $this->response;
    }

    public function raw($data)
    {
        $this->response->disableLayout();
        $this->response->setContent($data);
        return $this->response;
    }
}
