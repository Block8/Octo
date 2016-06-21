<?php

/**
 * Job base store for table: job

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\Job;
use Octo\System\Model\JobCollection;

/**
 * Job Base Store
 */
class JobStoreBase extends Store
{
    protected $table = 'job';
    protected $model = 'Octo\System\Model\Job';
    protected $key = 'id';

    /**
    * @param $value
    * @return Job|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a Job object by Id.
     * @param $value
     * @return Job|null
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
     * Get all Job objects by Type.
     * @return \Octo\System\Model\JobCollection
     */
    public function getByType($value, $limit = null)
    {
        return $this->where('type', $value)->get($limit);
    }

    /**
     * Gets the total number of Job by Type value.
     * @return int
     */
    public function getTotalByType($value) : int
    {
        return $this->where('type', $value)->count();
    }

    /**
     * Get all Job objects by Status.
     * @return \Octo\System\Model\JobCollection
     */
    public function getByStatus($value, $limit = null)
    {
        return $this->where('status', $value)->get($limit);
    }

    /**
     * Gets the total number of Job by Status value.
     * @return int
     */
    public function getTotalByStatus($value) : int
    {
        return $this->where('status', $value)->count();
    }
}
