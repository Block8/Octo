<?php

/**
 * Permission model collection
 */

namespace Octo\System\Model;

use Octo;
use b8\Model\Collection;

/**
 * Permission Model Collection
 */
class PermissionCollection extends Collection
{
    /**
     * Add a Permission model to the collection.
     * @param string $key
     * @param Permission $value
     * @return PermissionCollection
     */
    public function add($key, Permission $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Permission|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
