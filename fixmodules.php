<?php

$namespace = 'Octo';

$dir = new DirectoryIterator('./'.$namespace.'/');

$modules = [];
$models = [];


foreach ($dir as $item) {
    if ($item->isDir() && !$item->isDot() && is_dir($item->getPathname() . '/Model/')) {
        $module = $item->getBasename();
        $moduleModels = getModels($module);

        foreach ($moduleModels as $model) {
            $models[$model] = $module;
        }

        $modules[] = $module;
    }
}

$find = [];
$replace = [];

foreach ($models as $model => $module) {
    $find[] = $namespace . '\\Model\\' . $model;
    $find[] = $namespace . '\\Store\\' . $model . 'Store';
    $replace[] = $namespace . '\\' . $module . '\\Model\\' . $model;
    $replace[] = $namespace . '\\' . $module . '\\Store\\' . $model . 'Store';
}

var_dump($find, $replace);


function getModels($module)
{
    global $namespace;

    $dir = new DirectoryIterator('./' . $namespace . '/' . $module . '/Model/');
    $rtn = [];

    foreach ($dir as $item) {
        if ($item->isFile()) {
            $rtn[] = $item->getBasename('.php');
        }
    }

    return $rtn;
}

function recursiveReplace($dir, $find, $replace)
{
    $files = glob($dir . '/*.php');

    foreach ($files as $file) {

    }
}