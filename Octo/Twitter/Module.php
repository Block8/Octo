<?php

namespace Octo\Twitter;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Twitter';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}