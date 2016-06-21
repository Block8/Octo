<?php

/**
 * SearchIndex model collection
 */

namespace Octo\System\Model;

use Block8\Database\Model\Collection;

/**
 * SearchIndex Model Collection
 */
class SearchIndexCollection extends Collection
{
    /**
     * Add a SearchIndex model to the collection.
     * @param string $key
     * @param SearchIndex $value
     * @return SearchIndexCollection
     */
    public function addSearchIndex($key, SearchIndex $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return SearchIndex|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
