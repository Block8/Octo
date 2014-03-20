<?php

namespace Octo\Blog;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Blog';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}