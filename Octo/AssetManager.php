<?php

namespace Octo;

class AssetManager
{
    protected $css = [];
    protected $js = [];
    protected $image = [];
    protected $externalJs = [];

    public function addCss($module, $name)
    {
        $this->css[$module . '.' . $name] = ['module' => $module, 'name' => $name];
    }

    public function addJs($module, $name)
    {
        $this->js[$module . '.' . $name] = ['module' => $module, 'name' => $name];
    }

    public function addExternalJs($url)
    {
        $this->externalJs[$url] = $url;
    }

    public function getCss()
    {
        return $this->css;
    }

    public function getJs()
    {
        return $this->js;
    }

    public function getExternalJs()
    {
        return $this->externalJs;
    }
}