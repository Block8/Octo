<?php

/**
 * Permission base store for table: permission

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\Permission;
use Octo\System\Model\PermissionCollection;
use Octo\System\Store\PermissionStore;

/**
 * Permission Base Store
 */
class PermissionStoreBase extends Store
{
    /** @var PermissionStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'permission';

    /** @var string */
    protected $model = 'Octo\System\Model\Permission';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return PermissionStore
     */
    public static function load() : PermissionStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new PermissionStore(Connection::get());
        }

        return self::$instance;
    }

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
