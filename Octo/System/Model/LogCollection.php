<?php

/**
 * Log model collection
 */

namespace Octo\System\Model;

use Octo;
use b8\Model\Collection;

/**
 * Log Model Collection
 */
class LogCollection extends Collection
{
    /**
     * Add a Log model to the collection.
     * @param string $key
     * @param Log $value
     * @return LogCollection
     */
    public function addLog($key, Log $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Log|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
