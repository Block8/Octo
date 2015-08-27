<?php

/**
 * Migration base store for table: migration
 */

namespace Octo\System\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\System\Model\Migration;
use Octo\System\Model\MigrationCollection;

/**
 * Migration Base Store
 */
trait MigrationStoreBase
{
    protected function init()
    {
        $this->tableName = 'migration';
        $this->modelName = '\Octo\System\Model\Migration';
        $this->primaryKey = '';
    }
    /**
     * @param $value
     * @param $useConnection
     * @deprecated
     * @throws StoreException
     */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        throw new StoreException('getByPrimaryKey is not implemented for this store, as the table has no primary key.');
    }


}
