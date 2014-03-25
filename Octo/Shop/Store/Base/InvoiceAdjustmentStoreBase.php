<?php

/**
 * InvoiceAdjustment base store for table: invoice_adjustment
 */

namespace Octo\Shop\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Shop\Store;
use Octo\Shop\Model\InvoiceAdjustment;

/**
 * InvoiceAdjustment Base Store
 */
trait InvoiceAdjustmentStoreBase
{
    protected function init()
    {
        $this->tableName = 'invoice_adjustment';
        $this->modelName = '\Octo\Shop\Model\InvoiceAdjustment';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return InvoiceAdjustment
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return InvoiceAdjustment
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('InvoiceAdjustment').'\Model\InvoiceAdjustment', $useConnection);
        $query->select('*')->from('invoice_adjustment')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get InvoiceAdjustment by Id', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForInvoiceId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('InvoiceAdjustment').'\Model\InvoiceAdjustment', $useConnection);
        $query->from('invoice_adjustment')->where('`invoice_id` = :invoice_id');
        $query->bind(':invoice_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of InvoiceAdjustment by InvoiceId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return InvoiceAdjustment[]
     */
    public function getByInvoiceId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('InvoiceAdjustment').'\Model\InvoiceAdjustment', $useConnection);
        $query->from('invoice_adjustment')->where('`invoice_id` = :invoice_id');
        $query->bind(':invoice_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get InvoiceAdjustment by InvoiceId', 0, $ex);
        }

    }
}
