<?php
namespace Octo\Event;

use b8\Config;
use Octo\Event\Listener;

class Manager
{
    protected $config;
    protected $listenerClasses = [];
    protected $listeners = [];

    public function init()
    {
        $this->config = Config::getInstance();
        $siteModules = array_reverse($this->config->get('ModuleManager')->getEnabled());

        foreach ($siteModules as $namespace => $modules) {
            foreach ($modules as $module) {
                $path = $this->config->get('Octo.paths.modules.' . $module, '') . 'Event/';
                $this->registerListeners($path, $namespace . '\\' . $module . '\\Event\\');
            }
        }
    }

    protected function registerListeners($directory, $namespace)
    {
        $files = glob($directory . '*.php');

        foreach ($files as $file) {
            require_once($file);
            $className = $namespace . basename($file, '.php');

            if (class_exists($className)) {
                $class = new $className();

                if ($class instanceof Listener) {
                    $class->init($this->config);
                    $class->registerListeners($this);
                }
            }
        }
    }

    public function registerListener($event, $callback, $priority = false)
    {
        if (!array_key_exists($event, $this->listeners)) {
            $this->listeners[$event] =  [];
        }

        if ($priority) {
            array_unshift($this->listeners[$event], $callback);
        } else {
            array_push($this->listeners[$event], $callback);
        }
    }

    public function triggerEvent($event, &$data = null)
    {
        if (!isset($this->listeners[$event])) {
            return true;
        }

        $rtn = true;

        foreach ($this->listeners[$event] as $callback) {
            $continue = true;

            if (!$callback($data, $continue)) {
                $rtn = false;
            }

            if (!$continue) {
                break;
            }
        }

        return $rtn;
    }
}
