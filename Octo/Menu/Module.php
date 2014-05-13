<?php

namespace Octo\Menu;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Menu';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
