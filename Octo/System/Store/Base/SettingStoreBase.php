<?php

/**
 * Setting base store for table: setting

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\Setting;
use Octo\System\Model\SettingCollection;

/**
 * Setting Base Store
 */
class SettingStoreBase extends Store
{
    protected $table = 'setting';
    protected $model = 'Octo\System\Model\Setting';
    protected $key = 'id';

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
