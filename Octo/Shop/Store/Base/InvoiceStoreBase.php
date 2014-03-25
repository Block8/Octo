<?php

/**
 * Invoice base store for table: invoice
 */

namespace Octo\Shop\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Shop\Store;
use Octo\Shop\Model\Invoice;

/**
 * Invoice Base Store
 */
trait InvoiceStoreBase
{
    protected function init()
    {
        $this->tableName = 'invoice';
        $this->modelName = '\Octo\Shop\Model\Invoice';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Invoice
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Invoice
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Invoice').'\Model\Invoice', $useConnection);
        $query->select('*')->from('invoice')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Invoice by Id', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForInvoiceStatusId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Invoice').'\Model\Invoice', $useConnection);
        $query->from('invoice')->where('`invoice_status_id` = :invoice_status_id');
        $query->bind(':invoice_status_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of Invoice by InvoiceStatusId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return Invoice[]
     */
    public function getByInvoiceStatusId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Invoice').'\Model\Invoice', $useConnection);
        $query->from('invoice')->where('`invoice_status_id` = :invoice_status_id');
        $query->bind(':invoice_status_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Invoice by InvoiceStatusId', 0, $ex);
        }

    }
}
