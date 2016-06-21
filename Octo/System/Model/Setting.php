<?php

/**
 * Setting model for table: setting
 */

namespace Octo\System\Model;

use Octo;
use Octo\Store;

/**
 * Setting Model
 * @uses Octo\System\Model\Base\SettingBaseBase
 */
class Setting extends Base\SettingBase
{
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

    public static function getForScope($scope)
    {
        /** @var \Octo\System\Store\SettingStore $settingStore */
        $settingStore = Store::get('Setting');
        $settings = $settingStore->getByScope($scope);
        $rtn = [];

        foreach ($settings as $setting) {
            $rtn[$setting->getKey()] = $setting->getValue();
        }

        return $rtn;
    }

    public static function setForScope($scope, array $settings)
    {
        /** @var \Octo\System\Store\SettingStore $settingStore */
        $settingStore = Store::get('Setting');

        foreach ($settings as $key => $value) {
            $setting = new self();
            $setting->setKey($key);
            $setting->setValue($value);
            $setting->setScope($scope);

            $settingStore->replace($setting);
        }
    }

    public static function getAllAsArray()
    {
        /** @var \Octo\System\Store\SettingStore $settingStore */
        $settingStore = Store::get('Setting');
        $settings = $settingStore->all();

        $rtn = [];

        foreach ($settings as $setting) {
            $rtn[$setting->getScope() . '_' . $setting->getKey()] = $setting->getValue();
        }

        return $rtn;
    }
}
