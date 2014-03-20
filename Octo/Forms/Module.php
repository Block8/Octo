<?php

namespace Octo\Forms;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Forms';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}