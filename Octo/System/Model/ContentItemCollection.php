<?php

/**
 * ContentItem model collection
 */

namespace Octo\System\Model;

use Block8\Database\Model\Collection;

/**
 * ContentItem Model Collection
 */
class ContentItemCollection extends Collection
{
    /**
     * Add a ContentItem model to the collection.
     * @param string $key
     * @param ContentItem $value
     * @return ContentItemCollection
     */
    public function addContentItem($key, ContentItem $value)
    {
        return parent::add($key, $value);
    }

    /**
     * @param $key
     * @return ContentItem|null
     */
    public function get($key)
    {
        return parent::get($key);
    }
}
