<?php

/**
 * Setting model for table: setting
 */

namespace Octo\Model;

use Octo;
use Octo\Store;

/**
 * Setting Model
 * @uses Octo\Model\Base\SettingBase
 */
class Setting extends Octo\Model
{
    use Base\SettingBase;

    /**
     * Retrieve a setting
     *
     * @param $scope
     * @param $key
     * @return mixed
     */
    public static function get($scope, $key)
    {
        $settingStore = Store::get('Setting');
        return $settingStore->getSettingValue($scope, $key);
    }

    /**
     * Update a setting
     *
     * @param $scope
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function set($scope, $key, $value)
    {
        $settingStore = Store::get('Setting');
        $setting = $settingStore->getByScopeKey($scope, $key);
        $setting->setValue($value);
        $settingStore->save($setting);
    }
}
