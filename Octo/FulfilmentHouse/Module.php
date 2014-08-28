<?php

namespace Octo\FulfilmentHouse;

use Octo;

class Module extends Octo\Module
{

    protected function getName()
    {
        return 'FulfilmentHouse';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
