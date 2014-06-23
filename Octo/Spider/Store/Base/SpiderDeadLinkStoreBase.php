<?php

/**
 * SpiderDeadLink base store for table: spider_dead_link
 */

namespace Octo\Spider\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Spider\Model\SpiderDeadLink;

/**
 * SpiderDeadLink Base Store
 */
trait SpiderDeadLinkStoreBase
{
    protected function init()
    {
        $this->tableName = 'spider_dead_link';
        $this->modelName = '\Octo\Spider\Model\SpiderDeadLink';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return SpiderDeadLink
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return SpiderDeadLink
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('SpiderDeadLink').'\Model\SpiderDeadLink', $useConnection);
        $query->select('*')->from('spider_dead_link')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get SpiderDeadLink by Id', 0, $ex);
        }
    }
}
