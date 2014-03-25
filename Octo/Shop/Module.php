<?php

namespace Octo\Shop;

use Octo;

class Module extends Octo\Module
{
    protected function requires()
    {
        $this->manager->enable('Octo', 'Categories');
        $this->manager->enable('Octo', 'Media');
    }

    protected function getName()
    {
        return 'Shop';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
