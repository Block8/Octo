<?php
namespace Octo\Event;

use Octo\Event\Manager;

abstract class Listener
{
    abstract public function registerListeners(Manager $manager);
}
