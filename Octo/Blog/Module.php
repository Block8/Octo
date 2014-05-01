<?php

namespace Octo\Blog;

use Octo;

class Module extends Octo\Module
{
    protected function requires()
    {
        $this->manager->enable('Octo', 'News');
    }

    protected function getName()
    {
        return 'Blog';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
