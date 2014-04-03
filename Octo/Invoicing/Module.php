<?php

namespace Octo\Invoicing;

use Octo;

class Module extends Octo\Module
{
    protected function requires()
    {
    }

    protected function getName()
    {
        return 'Invoicing';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
