<?php

/**
 * Setting model collection
 */

namespace Octo\System\Model;

use Block8\Database\Model\Collection;

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
    public function addSetting($key, Setting $value)
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
