<?php

namespace Octo;

use b8\Config;

abstract class Module
{
    public function __construct(Config &$config)
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
        $config->set('Octo', $octoConfig);
    }

    abstract protected function getName();
    abstract protected function getPath();
}