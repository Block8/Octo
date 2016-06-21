<?php

/**
 * Log base store for table: log

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\Log;
use Octo\System\Model\LogCollection;

/**
 * Log Base Store
 */
class LogStoreBase extends Store
{
    protected $table = 'log';
    protected $model = 'Octo\System\Model\Log';
    protected $key = 'id';

    /**
    * @param $value
    * @return Log|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a Log object by Id.
     * @param $value
     * @return Log|null
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
     * Get all Log objects by Type.
     * @return \Octo\System\Model\LogCollection
     */
    public function getByType($value, $limit = null)
    {
        return $this->where('type', $value)->get($limit);
    }

    /**
     * Gets the total number of Log by Type value.
     * @return int
     */
    public function getTotalByType($value) : int
    {
        return $this->where('type', $value)->count();
    }

    /**
     * Get all Log objects by Scope.
     * @return \Octo\System\Model\LogCollection
     */
    public function getByScope($value, $limit = null)
    {
        return $this->where('scope', $value)->get($limit);
    }

    /**
     * Gets the total number of Log by Scope value.
     * @return int
     */
    public function getTotalByScope($value) : int
    {
        return $this->where('scope', $value)->count();
    }

    /**
     * Get all Log objects by UserId.
     * @return \Octo\System\Model\LogCollection
     */
    public function getByUserId($value, $limit = null)
    {
        return $this->where('user_id', $value)->get($limit);
    }

    /**
     * Gets the total number of Log by UserId value.
     * @return int
     */
    public function getTotalByUserId($value) : int
    {
        return $this->where('user_id', $value)->count();
    }
}
