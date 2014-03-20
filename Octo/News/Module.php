<?php

namespace Octo\News;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'News';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}