<?php

namespace Octo;

class AssetManager
{
    protected $css = [];
    protected $js = [];
    protected $image = [];

    public function addCss($module, $name)
    {
        $this->css[] = ['module' => $module, 'name' => $name];
    }

    public function addJs($module, $name)
    {
        $this->js[] = ['module' => $module, 'name' => $name];
    }

    public function getCss()
    {
        return $this->css;
    }

    public function getJs()
    {
        return $this->js;
    }
}