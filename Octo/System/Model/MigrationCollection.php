<?php

/**
 * Migration model collection
 */

namespace Octo\System\Model;

use Octo;
use b8\Model\Collection;

/**
 * Migration Model Collection
 */
class MigrationCollection extends Collection
{
    /**
     * Add a Migration model to the collection.
     * @param string $key
     * @param Migration $value
     * @return MigrationCollection
     */
    public function add($key, Migration $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Migration|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
