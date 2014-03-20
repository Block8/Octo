<?php

namespace Octo\Media;

use Octo;

class Module extends Octo\Module
{
    protected function requires()
    {
        $this->manager->enable('Octo', 'Categories');
    }

    protected function getName()
    {
        return 'Media';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}