<?php

namespace Octo\System;

use Octo;

class Module extends Octo\Module
{
    protected function getName()
    {
        return 'System';
    }

    protected function getPath()
    {
        return dirname(__FILE__) . '/';
    }

    public function init()
    {
        $app = $this->config->get('Octo');
        $app['bypass_auth']['session'] = true;
        $this->config->set('Octo', $app);

        return parent::init();
    }
}
