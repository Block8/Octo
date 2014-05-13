<?php

namespace Octo\Categories;

use Octo;

class Module extends Octo\Module
{
    protected function requires()
    {
        $this->manager->enable('Octo', 'Media');
    }

    protected function getName()
    {
        return 'Categories';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
