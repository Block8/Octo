<?php

/**
 * Setting base store for table: setting

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\Setting;
use Octo\System\Model\SettingCollection;
use Octo\System\Store\SettingStore;

/**
 * Setting Base Store
 */
class SettingStoreBase extends Store
{
    /** @var SettingStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'setting';

    /** @var string */
    protected $model = 'Octo\System\Model\Setting';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return SettingStore
     */
    public static function load() : SettingStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new SettingStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return Setting|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a Setting object by Id.
     * @param $value
     * @return Setting|null
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
     * Get all Setting objects by Key.
     * @return \Octo\System\Model\SettingCollection
     */
    public function getByKey($value, $limit = null)
    {
        return $this->where('key', $value)->get($limit);
    }

    /**
     * Gets the total number of Setting by Key value.
     * @return int
     */
    public function getTotalByKey($value) : int
    {
        return $this->where('key', $value)->count();
    }

    /**
     * Get all Setting objects by Scope.
     * @return \Octo\System\Model\SettingCollection
     */
    public function getByScope($value, $limit = null)
    {
        return $this->where('scope', $value)->get($limit);
    }

    /**
     * Gets the total number of Setting by Scope value.
     * @return int
     */
    public function getTotalByScope($value) : int
    {
        return $this->where('scope', $value)->count();
    }
}
