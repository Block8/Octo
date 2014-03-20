<?php

namespace Octo;

use b8\Config;
use Octo\Module;

class ModuleManager
{
    /**
     * @var \b8\Config
     */
    protected $config;

    protected $modules;

    protected $moduleObjects;

    public function __construct()
    {
        $this->modules = [];
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
        $this->config->set('ModuleManager', $this);
    }

    public function getEnabled()
    {
        return $this->modules;
    }

    public function initialiseModules()
    {
        foreach ($this->moduleObjects as $module) {
            $module->init();
        }
    }

    public function enable($namespace, $module)
    {
        if (!isset($this->modules[$namespace])) {
            $this->modules[$namespace] = [];
        }

        if (!in_array($module, $this->modules[$namespace])) {
            $this->modules[$namespace][] = $module;

            // Create the object:
            $class = '\\' . $namespace . '\\' . $module . '\\Module';
            $this->moduleObjects[$module] = new $class($this, $this->config, $namespace);
        }
    }
}