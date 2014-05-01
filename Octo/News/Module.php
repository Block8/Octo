<?php

namespace Octo\News;

use Octo;

class Module extends Octo\Module
{
    protected function requires()
    {
        $this->manager->enable('Octo', 'Articles');
    }

    protected function getName()
    {
        return 'News';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
