<?php

namespace Octo\System\Controller;

use b8\Config;
use b8\Exception\HttpException\NotFoundException;
use Octo;

class AssetController extends Octo\Controller
{
    public function js($module)
    {
        $parts = array_slice(func_get_args(), 1);
        $name = implode('/', $parts);
        $path = $this->getPath($module) . '/js/' . $name . '.js';
        $rtn = '';

        if (file_exists($path)) {
            $rtn = file_get_contents($path);
        }

        $this->response->disableLayout();
        $this->response->setHeader('Content-Type', 'application/javascript');
        return $rtn;
    }

    public function css($module)
    {
        $parts = array_slice(func_get_args(), 1);
        $name = implode('/', $parts);
        $path = $this->getPath($module) . '/css/' . $name . '.css';
        $rtn = '';

        if (file_exists($path)) {
            $rtn = file_get_contents($path);
        }

        $this->response->disableLayout();
        $this->response->setHeader('Content-Type', 'text/css');
        return $rtn;
    }

    public function img($module)
    {
        $parts = array_slice(func_get_args(), 1);
        $name = implode('/', $parts);
        $path = $this->getPath($module) . '/img/' . $name;

        if (!file_exists($path)) {
            throw new NotFoundException('Asset ' . $module . '::' . $name . ' does not exist.');
        }

        $this->response->disableLayout();
        return file_get_contents($path);
    }

    protected function getPath($module)
    {
        if ($module == 'site') {
            $siteNamespace = $this->config->get('site.namespace');
            return APP_PATH . $siteNamespace . '/Public/';
        }

        $paths = Config::getInstance()->get('Octo.paths.modules');
        return $paths[$module] . 'Public/';
    }
}