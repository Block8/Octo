<?php

namespace Octo\System\Controller;

use b8\Config;
use b8\Exception\HttpException\NotFoundException;
use Octo;

class AssetController extends Octo\Controller
{
    public function js($module, $name)
    {
        $paths = Config::getInstance()->get('Octo.paths.modules');
        $path = $paths[$module] . 'Public/js/' . $name . '.js';

        if (!file_exists($path)) {
            throw new NotFoundException('Asset ' . $module . '.' . $name . ' does not exist.');
        }

        $this->response->disableLayout();
        $this->response->setHeader('Content-Type', 'application/javascript');
        return file_get_contents($path);
    }
}