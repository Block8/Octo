<?php

namespace Octo;

use b8\Config;

abstract class Module
{
    public function __construct(Config &$config, $namespace)
    {
        $base = $this->getPath();
        $templatePath = $base . 'Template/';
        $adminTemplatePath = $base . 'Admin/Template/';
        $octoConfig = $config->get('Octo', []);

        if (is_dir($templatePath)) {
            $octoConfig['paths']['templates'][] = $templatePath;
        }

        if (is_dir($adminTemplatePath)) {
            $octoConfig['paths']['admin_templates'][] = $adminTemplatePath;
        }

        $octoConfig['paths']['modules'][$this->getName()] = $base;
        $octoConfig['paths']['namespaces'][$namespace . '\\' . $this->getName()] = $base;

        if (!isset($octoConfig['namespaces']['blocks'])) {
            $octoConfig['namespaces']['blocks'] = [];
        }

        $octoConfig['namespaces']['blocks'] = array_merge($octoConfig['namespaces']['blocks'], $this->getBlocks($namespace));

        $app = $config->get('app', []);
        $app['namespaces'] = array_merge($app['namespaces'], $this->getModels($namespace));

        $config->set('app', $app);
        $config->set('Octo', $octoConfig);
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