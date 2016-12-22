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
    protected static $settings = [];

    protected static function load()
    {
        /** @var \Octo\System\Store\SettingStore $settingStore */
        $settingStore = Store::get('Setting');
        $settings = $settingStore->all();

        self::$settings = [];

        foreach ($settings as $setting) {
            self::$settings[$setting->getScope() . '_' . $setting->getKey()] = $setting->getValue();
            self::$settings[$setting->getScope()][$setting->getKey()] = $setting->getValue();
        }
    }

	/**
     * Retrieve a setting
     *
     * @param $scope
     * @param $key
     * @return mixed
     */
    public static function get($scope, $key)
    {
        if (!count(self::$settings)) {
            self::load();
        }

        if (isset(self::$settings[$scope][$key])) {
            return self::$settings[$scope][$key];
        }

        return null;
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

        self::load();
    }

    public static function getForScope($scope)
    {
        return self::$settings[$scope];
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

        self::load();
    }

    public static function getAllAsArray()
    {
        if (!count(self::$settings)) {
            self::load();
        }

        return self::$settings;
    }
}
