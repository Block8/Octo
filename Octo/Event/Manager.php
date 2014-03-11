<?php
namespace Octo\Event;

use b8\Config;
use Octo\Event\Listener;

class Manager
{
    protected $listenerClasses = [];
    protected $listeners = [];

    public function init()
    {
        $this->config = Config::getInstance();
        $siteNs = $this->config->get('site.namespace');
        $sitePath = APP_PATH . $siteNs . '/Event/Listener/';
        $siteNs = $siteNs . '\\Event\\Listener\\';

        $this->registerListeners(CMS_PATH . 'Event/Listener/', '\\Octo\\Event\\Listener\\');
        $this->registerListeners($sitePath, $siteNs);
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
                    $class->registerListeners($this);
                }
            }
        }
    }

    public function registerListener($event, $callback)
    {
        if (!array_key_exists($event, $this->listeners)) {
            $this->listeners[$event] =  [];
        }

        $this->listeners[$event][] = $callback;
    }

    public function triggerEvent($event, &$data = null)
    {
        if (!isset($this->listeners[$event])) {
            return true;
        }

        $rtn = true;

        foreach ($this->listeners[$event] as $callback) {
            if (!$callback($data)) {
                $rtn = false;
            }
        }

        return $rtn;
    }
}
