<?php
namespace Octo;

use Octo\Event\Manager;

/**
 * Class Event
 * @package Octo
 */
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

    public static function getEventManager()
    {
        if (empty(self::$manager)) {
            self::init();
        }

        return self::$manager;
    }
}
