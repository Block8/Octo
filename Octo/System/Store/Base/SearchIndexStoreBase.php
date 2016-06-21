<?php

/**
 * SearchIndex base store for table: search_index

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\SearchIndex;
use Octo\System\Model\SearchIndexCollection;

/**
 * SearchIndex Base Store
 */
class SearchIndexStoreBase extends Store
{
    protected $table = 'search_index';
    protected $model = 'Octo\System\Model\SearchIndex';
    protected $key = 'id';

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
