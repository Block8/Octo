<?php

/**
 * Job model collection
 */

namespace Octo\System\Model;

use Block8\Database\Model\Collection;

/**
 * Job Model Collection
 */
class JobCollection extends Collection
{
    /**
     * Add a Job model to the collection.
     * @param string $key
     * @param Job $value
     * @return JobCollection
     */
    public function addJob($key, Job $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return Job|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
