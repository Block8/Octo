<?php

namespace Octo\GatewayStripe;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'GatewayStripe';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
