<?php
namespace Octo;

use Octo\Event\Manager;

class Event
{
    /**
     * @var \Octo\Event\Manager
     */
    protected static $manager;

    /**
     * Set up the event handling functionality.
     */
    public static function init()
    {
        self::$manager = new Manager();
        self::$manager->init();
    }

    /**
     * Trigger a set of event handlers, if present.
     * @param $event
     * @param $data
     * @return bool
     */
    public static function trigger($event, &$data)
    {
        if (!isset(self::$manager)) {
            self::init();
        }

        return self::$manager->triggerEvent($event, $data);
    }
}
