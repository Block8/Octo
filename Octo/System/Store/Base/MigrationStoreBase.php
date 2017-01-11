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
    protected $key = '';

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

}
