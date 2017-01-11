<?php

/**
 * ScheduledJob base store for table: scheduled_job

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\ScheduledJob;
use Octo\System\Model\ScheduledJobCollection;
use Octo\System\Store\ScheduledJobStore;

/**
 * ScheduledJob Base Store
 */
class ScheduledJobStoreBase extends Store
{
    /** @var ScheduledJobStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'scheduled_job';

    /** @var string */
    protected $model = 'Octo\System\Model\ScheduledJob';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return ScheduledJobStore
     */
    public static function load() : ScheduledJobStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new ScheduledJobStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return ScheduledJob|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a ScheduledJob object by Id.
     * @param $value
     * @return ScheduledJob|null
     */
    public function getById(int $value)
    {
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->cacheGet($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }

        $rtn = $this->where('id', $value)->first();
        $this->cacheSet($value, $rtn);

        return $rtn;
    }

    /**
     * Get all ScheduledJob objects by CurrentJobId.
     * @return \Octo\System\Model\ScheduledJobCollection
     */
    public function getByCurrentJobId($value, $limit = null)
    {
        return $this->where('current_job_id', $value)->get($limit);
    }

    /**
     * Gets the total number of ScheduledJob by CurrentJobId value.
     * @return int
     */
    public function getTotalByCurrentJobId($value) : int
    {
        return $this->where('current_job_id', $value)->count();
    }
}
