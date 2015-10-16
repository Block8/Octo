<?php

/**
 * ScheduledJob base store for table: scheduled_job
 */

namespace Octo\System\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\System\Model\ScheduledJob;
use Octo\System\Model\ScheduledJobCollection;

/**
 * ScheduledJob Base Store
 */
trait ScheduledJobStoreBase
{
    protected function init()
    {
        $this->tableName = 'scheduled_job';
        $this->modelName = '\Octo\System\Model\ScheduledJob';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ScheduledJob
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ScheduledJob
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


        $query = new Query($this->getNamespace('ScheduledJob').'\Model\ScheduledJob', $useConnection);
        $query->select('*')->from('scheduled_job')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            $result = $query->fetch();

            $this->setCache($value, $result);

            return $result;
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ScheduledJob by Id', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForCurrentJobId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ScheduledJob').'\Model\ScheduledJob', $useConnection);
        $query->from('scheduled_job')->where('`current_job_id` = :current_job_id');
        $query->bind(':current_job_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of ScheduledJob by CurrentJobId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return ScheduledJobCollection
     */
    public function getByCurrentJobId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ScheduledJob').'\Model\ScheduledJob', $useConnection);
        $query->from('scheduled_job')->where('`current_job_id` = :current_job_id');
        $query->bind(':current_job_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return new ScheduledJobCollection($query->fetchAll());
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ScheduledJob by CurrentJobId', 0, $ex);
        }

    }
}
