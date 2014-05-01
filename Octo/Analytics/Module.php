<?php

namespace Octo\Analytics;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Analytics';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
