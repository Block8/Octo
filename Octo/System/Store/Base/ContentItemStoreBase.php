<?php

/**
 * ContentItem base store for table: content_item

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\ContentItem;
use Octo\System\Model\ContentItemCollection;

/**
 * ContentItem Base Store
 */
class ContentItemStoreBase extends Store
{
    protected $table = 'content_item';
    protected $model = 'Octo\System\Model\ContentItem';
    protected $key = 'id';

    /**
    * @param $value
    * @return ContentItem|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a ContentItem object by Id.
     * @param $value
     * @return ContentItem|null
     */
    public function getById(string $value)
    {
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->cacheGet($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }

        $rtn = $this->where('id', $value)->first();
        $this->cacheSet($value, $rtn);

        return $rtn;
    }
}
