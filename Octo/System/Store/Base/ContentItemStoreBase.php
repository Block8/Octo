<?php

/**
 * ContentItem base store for table: content_item

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\ContentItem;
use Octo\System\Model\ContentItemCollection;
use Octo\System\Store\ContentItemStore;

/**
 * ContentItem Base Store
 */
class ContentItemStoreBase extends Store
{
    /** @var ContentItemStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'content_item';

    /** @var string */
    protected $model = 'Octo\System\Model\ContentItem';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return ContentItemStore
     */
    public static function load() : ContentItemStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentItemStore(Connection::get());
        }

        return self::$instance;
    }

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
