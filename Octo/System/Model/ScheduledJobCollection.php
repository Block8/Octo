<?php

/**
 * ScheduledJob model collection
 */

namespace Octo\System\Model;

use Block8\Database\Model\Collection;

/**
 * ScheduledJob Model Collection
 */
class ScheduledJobCollection extends Collection
{
    /**
     * Add a ScheduledJob model to the collection.
     * @param string $key
     * @param ScheduledJob $value
     * @return ScheduledJobCollection
     */
    public function addScheduledJob($key, ScheduledJob $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return ScheduledJob|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
