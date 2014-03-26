<?php

namespace Octo\Pages;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Pages';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
