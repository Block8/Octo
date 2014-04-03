<?php

/**
 * GaSummaryView base store for table: ga_summary_view
 */

namespace Octo\Analytics\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Analytics\Model\GaSummaryView;

/**
 * GaSummaryView Base Store
 */
trait GaSummaryViewStoreBase
{
    protected function init()
    {
        $this->tableName = 'ga_summary_view';
        $this->modelName = '\Octo\Analytics\Model\GaSummaryView';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return GaSummaryView
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return GaSummaryView
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('GaSummaryView').'\Model\GaSummaryView', $useConnection);
        $query->select('*')->from('ga_summary_view')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get GaSummaryView by Id', 0, $ex);
        }
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return GaSummaryView
    */
    public function getByMetric($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('GaSummaryView').'\Model\GaSummaryView', $useConnection);
        $query->select('*')->from('ga_summary_view')->limit(1);
        $query->where('`metric` = :metric');
        $query->bind(':metric', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get GaSummaryView by Metric', 0, $ex);
        }
    }
}
