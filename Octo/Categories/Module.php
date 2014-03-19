<?php

namespace Octo\Categories;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Categories';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}