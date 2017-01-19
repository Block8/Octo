<?php

/**
 * ScheduledJob base model for table: scheduled_job
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;

use Octo\System\Store\ScheduledJobStore;
use Octo\System\Model\ScheduledJob;
use Octo\System\Model\Job;

/**
 * ScheduledJob Base Model
 */
abstract class ScheduledJobBase extends Model
{
    protected $table = 'scheduled_job';
    protected $model = 'ScheduledJob';
    protected $data = [
        'id' => null,
        'type' => null,
        'data' => null,
        'frequency' => null,
        'current_job_id' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'type' => 'getType',
        'data' => 'getData',
        'frequency' => 'getFrequency',
        'current_job_id' => 'getCurrentJobId',
        'CurrentJob' => 'getCurrentJob',
    ];

    protected $setters = [
        'id' => 'setId',
        'type' => 'setType',
        'data' => 'setData',
        'frequency' => 'setFrequency',
        'current_job_id' => 'setCurrentJobId',
        'CurrentJob' => 'setCurrentJob',
    ];

    /**
     * Return the database store for this model.
     * @return ScheduledJobStore
     */
    public static function Store() : ScheduledJobStore
    {
        return ScheduledJobStore::load();
    }

    /**
     * Get ScheduledJob by primary key: id
     * @param int $id
     * @return ScheduledJob|null
     */
    public static function get(int $id) : ?ScheduledJob
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return ScheduledJob
     */
    public function save() : ScheduledJob
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save ScheduledJob');
        }

        if (!($rtn instanceof ScheduledJob)) {
            throw new \Exception('Unexpected ' . get_class($rtn) . ' received from save.');
        }

        $this->data = $rtn->toArray();

        return $this;
    }


    /**
     * Get the value of Id / id
     * @return int
     */
     public function getId() : int
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Type / type
     * @return string
     */
     public function getType() : string
     {
        $rtn = $this->data['type'];

        return $rtn;
     }
    
    /**
     * Get the value of Data / data
     * @return array
     */
     public function getData() : ?array
     {
        $rtn = $this->data['data'];

        $rtn = json_decode($rtn, true);

        if ($rtn === false) {
            $rtn = null;
        }

        return $rtn;
     }
    
    /**
     * Get the value of Frequency / frequency
     * @return int
     */
     public function getFrequency() : int
     {
        $rtn = $this->data['frequency'];

        return $rtn;
     }
    
    /**
     * Get the value of CurrentJobId / current_job_id
     * @return int
     */
     public function getCurrentJobId() : ?int
     {
        $rtn = $this->data['current_job_id'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return ScheduledJob
     */
    public function setId(int $value) : ScheduledJob
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Type / type
     * @param $value string
     * @return ScheduledJob
     */
    public function setType(string $value) : ScheduledJob
    {

        if ($this->data['type'] !== $value) {
            $this->data['type'] = $value;
            $this->setModified('type');
        }

        return $this;
    }
    
    /**
     * Set the value of Data / data
     * @param $value array
     * @return ScheduledJob
     */
    public function setData($value) : ScheduledJob
    {
        $this->validateJson($value);

        if ($this->data['data'] !== $value) {
            $this->data['data'] = $value;
            $this->setModified('data');
        }

        return $this;
    }
    
    /**
     * Set the value of Frequency / frequency
     * @param $value int
     * @return ScheduledJob
     */
    public function setFrequency(int $value) : ScheduledJob
    {

        if ($this->data['frequency'] !== $value) {
            $this->data['frequency'] = $value;
            $this->setModified('frequency');
        }

        return $this;
    }
    
    /**
     * Set the value of CurrentJobId / current_job_id
     * @param $value int
     * @return ScheduledJob
     */
    public function setCurrentJobId(?int $value) : ScheduledJob
    {

        // As this column is a foreign key, empty values should be considered null.
        if (empty($value)) {
            $value = null;
        }


        if ($this->data['current_job_id'] !== $value) {
            $this->data['current_job_id'] = $value;
            $this->setModified('current_job_id');
        }

        return $this;
    }
    

    /**
     * Get the Job model for this  by Id.
     *
     * @uses \Octo\System\Store\JobStore::getById()
     * @uses Job
     * @return Job|null
     */
    public function getCurrentJob() : ?Job
    {
        $key = $this->getCurrentJobId();

        if (empty($key)) {
           return null;
        }

        return Job::Store()->getById($key);
    }

    /**
     * Set CurrentJob - Accepts an ID, an array representing a Job or a Job model.
     * @throws \Exception
     * @param $value mixed
     * @return ScheduledJob
     */
    public function setCurrentJob($value) : ScheduledJob
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setCurrentJobId($value);
        }

        // Is this an instance of CurrentJob?
        if (is_object($value) && $value instanceof Job) {
            return $this->setCurrentJobObject($value);
        }

        // Is this an array representing a Job item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setCurrentJobId($value['id']);
        }

        // None of the above? That's a problem!
        throw new \Exception('Invalid value for CurrentJob.');
    }

    /**
     * Set CurrentJob - Accepts a Job model.
     *
     * @param $value Job
     * @return ScheduledJob
     */
    public function setCurrentJobObject(Job $value) : ScheduledJob
    {
        return $this->setCurrentJobId($value->getId());
    }
}
