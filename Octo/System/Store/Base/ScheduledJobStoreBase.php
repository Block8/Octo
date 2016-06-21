<?php

/**
 * ScheduledJob base store for table: scheduled_job

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\ScheduledJob;
use Octo\System\Model\ScheduledJobCollection;

/**
 * ScheduledJob Base Store
 */
class ScheduledJobStoreBase extends Store
{
    protected $table = 'scheduled_job';
    protected $model = 'Octo\System\Model\ScheduledJob';
    protected $key = 'id';

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
