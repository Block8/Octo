<?php

namespace Octo;

use b8\Config;
use b8\Store\Factory;

abstract class Store extends \b8\Store
{
    /**
     * @param string $store Name of the store you want to load.
     * @return \b8\Store
     */
    public static function get($store)
    {
        $namespace = null;
        $namespaces = Config::getInstance()->get('Octo.namespaces', []);

        if (array_key_exists($store, $namespaces)) {
            $namespace = $namespaces[$store];
        }

        return Factory::getStore($store, $namespace);
    }
}
