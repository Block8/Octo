<?php

/**
 * Setting model collection
 */

namespace Octo\System\Model;

use Octo;
use b8\Model\Collection;

/**
 * Setting Model Collection
 */
class SettingCollection extends Collection
{
    /**
     * Add a Setting model to the collection.
     * @param string $key
     * @param Setting $value
     * @return SettingCollection
     */
    public function add($key, Setting $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Setting|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
