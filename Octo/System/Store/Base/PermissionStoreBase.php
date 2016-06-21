<?php

/**
 * Permission base store for table: permission

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\Permission;
use Octo\System\Model\PermissionCollection;

/**
 * Permission Base Store
 */
class PermissionStoreBase extends Store
{
    protected $table = 'permission';
    protected $model = 'Octo\System\Model\Permission';
    protected $key = 'id';

    /**
    * @param $value
    * @return Permission|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a Permission object by Id.
     * @param $value
     * @return Permission|null
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
     * Get all Permission objects by UserId.
     * @return \Octo\System\Model\PermissionCollection
     */
    public function getByUserId($value, $limit = null)
    {
        return $this->where('user_id', $value)->get($limit);
    }

    /**
     * Gets the total number of Permission by UserId value.
     * @return int
     */
    public function getTotalByUserId($value) : int
    {
        return $this->where('user_id', $value)->count();
    }
}
