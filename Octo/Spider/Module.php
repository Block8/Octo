<?php

namespace Octo\Spider;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Spider';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
