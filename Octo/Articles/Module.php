<?php

namespace Octo\Articles;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Articles';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}