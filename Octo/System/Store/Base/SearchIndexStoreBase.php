<?php

/**
 * SearchIndex base store for table: search_index

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\SearchIndex;
use Octo\System\Model\SearchIndexCollection;
use Octo\System\Store\SearchIndexStore;

/**
 * SearchIndex Base Store
 */
class SearchIndexStoreBase extends Store
{
    /** @var SearchIndexStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'search_index';

    /** @var string */
    protected $model = 'Octo\System\Model\SearchIndex';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return SearchIndexStore
     */
    public static function load() : SearchIndexStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new SearchIndexStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return SearchIndex|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a SearchIndex object by Id.
     * @param $value
     * @return SearchIndex|null
     */
    public function getById(int $value)
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

    /**
     * Get all SearchIndex objects by Word.
     * @return \Octo\System\Model\SearchIndexCollection
     */
    public function getByWord($value, $limit = null)
    {
        return $this->where('word', $value)->get($limit);
    }

    /**
     * Gets the total number of SearchIndex by Word value.
     * @return int
     */
    public function getTotalByWord($value) : int
    {
        return $this->where('word', $value)->count();
    }
}
