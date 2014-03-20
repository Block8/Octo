<?php

namespace Octo\System;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'System';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}