<?php

/**
 * FulfilmentHouse base store for table: fulfilment_house
 */

namespace Octo\FulfilmentHouse\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\FulfilmentHouse\Model\FulfilmentHouse;

/**
 * FulfilmentHouse Base Store
 */
trait FulfilmentHouseStoreBase
{
    protected function init()
    {
        $this->tableName = 'fulfilment_house';
        $this->modelName = '\Octo\FulfilmentHouse\Model\FulfilmentHouse';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return FulfilmentHouse
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return FulfilmentHouse
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('FulfilmentHouse').'\Model\FulfilmentHouse', $useConnection);
        $query->select('*')->from('fulfilment_house')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get FulfilmentHouse by Id', 0, $ex);
        }
    }
}
