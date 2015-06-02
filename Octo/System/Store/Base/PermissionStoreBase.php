<?php

/**
 * Permission base store for table: permission
 */

namespace Octo\System\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\System\Model\Permission;
use Octo\System\Model\PermissionCollection;

/**
 * Permission Base Store
 */
trait PermissionStoreBase
{
    protected function init()
    {
        $this->tableName = 'permission';
        $this->modelName = '\Octo\System\Model\Permission';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Permission
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Permission
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->getFromCache($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }


        $query = new Query($this->getNamespace('Permission').'\Model\Permission', $useConnection);
        $query->select('*')->from('permission')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            $result = $query->fetch();

            $this->setCache($value, $result);

            return $result;
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Permission by Id', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForUserId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Permission').'\Model\Permission', $useConnection);
        $query->from('permission')->where('`user_id` = :user_id');
        $query->bind(':user_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of Permission by UserId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return PermissionCollection
     */
    public function getByUserId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Permission').'\Model\Permission', $useConnection);
        $query->from('permission')->where('`user_id` = :user_id');
        $query->bind(':user_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return new PermissionCollection($query->fetchAll());
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Permission by UserId', 0, $ex);
        }

    }
}
