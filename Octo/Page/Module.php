<?php

namespace Octo\Page;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'Page';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
