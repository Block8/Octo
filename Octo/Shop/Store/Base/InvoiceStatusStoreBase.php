<?php

/**
 * InvoiceStatus base store for table: invoice_status
 */

namespace Octo\Shop\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Shop\Store;
use Octo\Shop\Model\InvoiceStatus;

/**
 * InvoiceStatus Base Store
 */
trait InvoiceStatusStoreBase
{
    protected function init()
    {
        $this->tableName = 'invoice_status';
        $this->modelName = '\Octo\Shop\Model\InvoiceStatus';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return InvoiceStatus
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return InvoiceStatus
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('InvoiceStatus').'\Model\InvoiceStatus', $useConnection);
        $query->select('*')->from('invoice_status')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get InvoiceStatus by Id', 0, $ex);
        }
    }
}
