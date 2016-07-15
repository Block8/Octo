<?php
namespace Octo\Event;

use b8\Config;

abstract class Listener
{
    /**
     * @var \b8\Config
     */
    protected $config;   

    abstract public function registerListeners(Manager $manager);

    public function init(Config $config)
    {
        $this->config = $config;
    }
}
