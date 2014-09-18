<?php

/**
 * Rsm2000Log base store for table: rsm2000_log
 */

namespace Octo\GatewayRSM2000\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\GatewayRSM2000\Model\Rsm2000Log;

/**
 * Rsm2000Log Base Store
 */
trait Rsm2000LogStoreBase
{
    protected function init()
    {
        $this->tableName = 'rsm2000_log';
        $this->modelName = '\Octo\GatewayRSM2000\Model\Rsm2000Log';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Rsm2000Log
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Rsm2000Log
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Rsm2000Log').'\Model\Rsm2000Log', $useConnection);
        $query->select('*')->from('rsm2000_log')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Rsm2000Log by Id', 0, $ex);
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

        $query = new Query($this->getNamespace('Rsm2000Log').'\Model\Rsm2000Log', $useConnection);
        $query->from('rsm2000_log')->where('`invoice_id` = :invoice_id');
        $query->bind(':invoice_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of Rsm2000Log by InvoiceId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return Rsm2000Log[]
     */
    public function getByInvoiceId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Rsm2000Log').'\Model\Rsm2000Log', $useConnection);
        $query->from('rsm2000_log')->where('`invoice_id` = :invoice_id');
        $query->bind(':invoice_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Rsm2000Log by InvoiceId', 0, $ex);
        }

    }
}
