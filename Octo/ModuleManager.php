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
    
    public function isEnabled($module)
    {
    	foreach($this->modules as $modules) {
    		if(in_array($module, $modules)) {
    			return true;
    		}
    	}
        return false;
    }

    public function initialiseModules()
    {
        $this->autoloadModulesFromComposer();

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

    protected function autoloadModulesFromComposer()
    {
        $config = json_decode(file_get_contents(APP_PATH . 'composer.lock'), true);

        foreach ($config['packages'] as $package) {
            if (isset($package['autoload']['psr-4'])) {
                $this->autoloadComposerModule($package);
            }
        }
    }

    protected function autoloadComposerModule($package)
    {
        foreach ($package['autoload']['psr-4'] as $namespace => $path) {
            if (file_exists(APP_PATH . 'vendor/'. $package['name'] . '/' . $path . 'Module.php')) {
                if (class_exists($namespace . 'Module')) {
                    $parts = explode('\\', $namespace);
                    array_pop($parts);
                    $moduleName = array_pop($parts);
                    $namespace = implode('\\', $parts);

                    $this->enable($namespace, $moduleName);
                }
            }
        }
    }
}
