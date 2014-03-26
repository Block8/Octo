<?php

namespace Octo;

use b8\Config;
use Octo\ModuleManager;

abstract class Module
{
    /**
     * @var ModuleManager
     */
    protected $manager;

    /**
     * @var \b8\Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $namespace;

    public function __construct(ModuleManager &$manager, Config &$config, $namespace)
    {
        $this->manager = $manager;
        $this->config = $config;
        $this->namespace = $namespace;

        if (method_exists($this, 'requires')) {
            $this->requires();
        }
    }

    public function init()
    {
        $base = $this->getPath();
        $templatePath = $base . 'Template/';
        $adminTemplatePath = $base . 'Admin/Template/';
        $octoConfig = $this->config->get('Octo', []);

        if (is_dir($templatePath)) {
            $octoConfig['paths']['templates'][$this->getName()] = $templatePath;
        }

        if (is_dir($adminTemplatePath)) {
            $octoConfig['paths']['admin_templates'][$this->getName()] = $adminTemplatePath;
        }

        $octoConfig['paths']['modules'][$this->getName()] = $base;
        $octoConfig['paths']['namespaces'][$this->namespace . '\\' . $this->getName()] = $base;

        if (!isset($octoConfig['namespaces']['blocks'])) {
            $octoConfig['namespaces']['blocks'] = [];
        }

        $blocks = $this->getBlocks($this->namespace);
        $octoConfig['namespaces']['blocks'] = array_merge($octoConfig['namespaces']['blocks'], $blocks);

        $app = $this->config->get('app', []);
        $app['namespaces'] = array_merge($app['namespaces'], $this->getModels($this->namespace));

        $this->config->set('app', $app);
        $this->config->set('Octo', $octoConfig);
    }

    abstract protected function getName();
    abstract protected function getPath();

    protected function getModels($namespace)
    {
        $modelPath = $this->getPath() . 'Model/';

        if (!is_dir($modelPath)) {
            return [];
        }

        $dir = new \DirectoryIterator($modelPath);
        $rtn = [];

        foreach ($dir as $item) {
            if ($item->isFile() && $item->getExtension() == 'php') {
                $modelName = $item->getBasename('.php');
                $rtn[$modelName] = $namespace . '\\' . $this->getName();
            }
        }

        return $rtn;
    }

    protected function getBlocks($namespace)
    {
        $blockPath = $this->getPath() . 'Block/';

        if (!is_dir($blockPath)) {
            return [];
        }

        $dir = new \DirectoryIterator($blockPath);
        $rtn = [];

        foreach ($dir as $item) {
            if ($item->isFile() && $item->getExtension() == 'php') {
                $modelName = $item->getBasename('.php');
                $rtn[$modelName] = $namespace . '\\' . $this->getName();
            }
        }

        return $rtn;
    }
}