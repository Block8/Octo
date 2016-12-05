<?php

namespace Octo;

class AssetManager
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected $assets = [];
    protected $thirdParty = ['js' => [], 'css' => []];

    public function __construct()
    {
        self::$instance = $this;
    }

    public function addCss($module, $name, $head = true)
    {
        $this->assets[md5('css.' . $module . '.' . $name)] =  [
            'type' => 'css',
            'module' => $module,
            'name' => $name,
            'head' => $head,
        ];
    }

    public function addJs($module, $name, $head = false)
    {
        $this->assets[md5('js.' . $module . '.' . $name)] =  [
            'type' => 'js',
            'module' => $module,
            'name' => $name,
            'head' => $head,
        ];
    }

    public function addThirdParty($type, $module, $name, $head = false)
    {
        $this->assets[md5('third-party.' . $module . '.' . $name)] =  [
            'type' => 'third-party.'.$type,
            'module' => $module,
            'name' => $name,
            'head' => $head,
        ];
    }

    public function addExternalJs($url, $head = false)
    {
        $this->assets[md5('external.' . $url)] =  [
            'type' => 'external',
            'url' => $url,
            'head' => $head,
        ];
    }

    public function getAssets()
    {
        return $this->assets;
    }

    public function reset()
    {
        $this->assets = [];
    }
}