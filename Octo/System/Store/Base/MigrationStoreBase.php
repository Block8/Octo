<?php

/**
 * Migration base store for table: migration

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\Migration;
use Octo\System\Model\MigrationCollection;
use Octo\System\Store\MigrationStore;

/**
 * Migration Base Store
 */
class MigrationStoreBase extends Store
{
    /** @var MigrationStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'migration';

    /** @var string */
    protected $model = 'Octo\System\Model\Migration';

    /** @var string */
    protected $key = 'version';

    /**
     * Return the database store for this model.
     * @return MigrationStore
     */
    public static function load() : MigrationStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new MigrationStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return Migration|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getByVersion($value);
    }


    /**
     * Get a Migration object by Version.
     * @param $value
     * @return Migration|null
     */
    public function getByVersion(int $value)
    {
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->cacheGet($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }

        $rtn = $this->where('version', $value)->first();
        $this->cacheSet($value, $rtn);

        return $rtn;
    }
}
