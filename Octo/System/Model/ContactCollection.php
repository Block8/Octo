<?php

/**
 * Contact model collection
 */

namespace Octo\System\Model;

use Octo;
use b8\Model\Collection;

/**
 * Contact Model Collection
 */
class ContactCollection extends Collection
{
    /**
     * Add a Contact model to the collection.
     * @param string $key
     * @param Contact $value
     * @return ContactCollection
     */
    public function add($key, Contact $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Contact|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
