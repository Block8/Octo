<?php

/**
 * Job base store for table: job

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\Job;
use Octo\System\Model\JobCollection;
use Octo\System\Store\JobStore;

/**
 * Job Base Store
 */
class JobStoreBase extends Store
{
    /** @var JobStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'job';

    /** @var string */
    protected $model = 'Octo\System\Model\Job';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return JobStore
     */
    public static function load() : JobStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new JobStore(Connection::get());
        }

        return self::$instance;
    }

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
