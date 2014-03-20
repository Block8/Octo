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
    $find[] = 'namespace Octo\Model;';
    $find[] = 'namespace Octo\Store;';
    $replace[] = 'namespace ' . $namespace . '\\' . $module . '\\Model;';
    $replace[] = 'namespace ' . $namespace . '\\' . $module . '\\Store;';

    $find[] = 'namespace Octo\Model\Base;';
    $find[] = 'namespace Octo\Store\Base;';
    $replace[] = 'namespace ' . $namespace . '\\' . $module . '\\Model\\Base;';
    $replace[] = 'namespace ' . $namespace . '\\' . $module . '\\Store\\Base;';

    $find[] = $namespace . '\\Model\\' . $model;
    $find[] = $namespace . '\\Model\\Base\\' . $model;
    $find[] = $namespace . '\\Store\\' . $model . 'Store';
    $find[] = $namespace . '\\Store\\Base\\' . $model . 'StoreBase';
    $replace[] = $namespace . '\\' . $module . '\\Model\\' . $model;
    $replace[] = $namespace . '\\' . $module . '\\Model\\Base\\' . $model . 'Base';
    $replace[] = $namespace . '\\' . $module . '\\Store\\' . $model . 'Store';
    $replace[] = $namespace . '\\' . $module . '\\Store\\Base\\' . $model . 'StoreBase';
}

recursiveReplace('./'.$namespace.'/', $find, $replace);

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
    $files = new DirectoryIterator($dir);

    foreach ($files as $file) {
        if ($file->isDot()) {
            continue;
        }

        if ($file->isDir()) {
            recursiveReplace($file->getPathname(), $find, $replace);
        }

        if ($file->isFile() && $file->getExtension() == 'php') {
            $content = file_get_contents($file->getPathname());
            $content = str_replace($find, $replace, $content);
            file_put_contents($file->getPathname(), $content);
        }
    }
}