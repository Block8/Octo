<?php

namespace Octo\GatewayRSM2000;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'GatewayRSM2000';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }
}
