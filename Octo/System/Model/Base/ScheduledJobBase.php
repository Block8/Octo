<?php

/**
 * ScheduledJob base model for table: scheduled_job
 */

namespace Octo\System\Model\Base;

use Octo\Model;
use Octo\Store;

/**
 * ScheduledJob Base Model
 */
class ScheduledJobBase extends Model
{
    protected function init()
    {
        $this->table = 'scheduled_job';
        $this->model = 'ScheduledJob';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['type'] = null;
        $this->getters['type'] = 'getType';
        $this->setters['type'] = 'setType';
        
        $this->data['data'] = null;
        $this->getters['data'] = 'getData';
        $this->setters['data'] = 'setData';
        
        $this->data['frequency'] = null;
        $this->getters['frequency'] = 'getFrequency';
        $this->setters['frequency'] = 'setFrequency';
        
        $this->data['current_job_id'] = null;
        $this->getters['current_job_id'] = 'getCurrentJobId';
        $this->setters['current_job_id'] = 'setCurrentJobId';
        
        // Foreign keys:
        
        $this->getters['CurrentJob'] = 'getCurrentJob';
        $this->setters['CurrentJob'] = 'setCurrentJob';
        
    }

    
    /**
     * Get the value of Id / id
     * @return int
     */

     public function getId()
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Type / type
     * @return string
     */

     public function getType()
     {
        $rtn = $this->data['type'];

        return $rtn;
     }
    
    /**
     * Get the value of Data / data
     * @return array|null
     */

     public function getData()
     {
        $rtn = $this->data['data'];

        $rtn = json_decode($rtn, true);

        if (empty($rtn)) {
            $rtn = null;
        }

        return $rtn;
     }
    
    /**
     * Get the value of Frequency / frequency
     * @return int
     */

     public function getFrequency()
     {
        $rtn = $this->data['frequency'];

        return $rtn;
     }
    
    /**
     * Get the value of CurrentJobId / current_job_id
     * @return int
     */

     public function getCurrentJobId()
     {
        $rtn = $this->data['current_job_id'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     */
    public function setId(int $value)
    {

        $this->validateNotNull('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }
    
    /**
     * Set the value of Type / type
     * @param $value string
     */
    public function setType(string $value)
    {

        $this->validateNotNull('Type', $value);

        if ($this->data['type'] === $value) {
            return;
        }

        $this->data['type'] = $value;
        $this->setModified('type');
    }
    
    /**
     * Set the value of Data / data
     * @param $value array|null
     */
    public function setData($value)
    {
        $this->validateJson($value);
        $this->validateNotNull('Data', $value);

        if ($this->data['data'] === $value) {
            return;
        }

        $this->data['data'] = $value;
        $this->setModified('data');
    }
    
    /**
     * Set the value of Frequency / frequency
     * @param $value int
     */
    public function setFrequency(int $value)
    {

        $this->validateNotNull('Frequency', $value);

        if ($this->data['frequency'] === $value) {
            return;
        }

        $this->data['frequency'] = $value;
        $this->setModified('frequency');
    }
    
    /**
     * Set the value of CurrentJobId / current_job_id
     * @param $value int
     */
    public function setCurrentJobId($value)
    {


        // As this column is a foreign key, empty values should be considered null.
        if (empty($value)) {
            $value = null;
        }



        if ($this->data['current_job_id'] === $value) {
            return;
        }

        $this->data['current_job_id'] = $value;
        $this->setModified('current_job_id');
    }
    
    
    /**
     * Get the Job model for this  by Id.
     *
     * @uses \Octo\System\Store\JobStore::getById()
     * @uses \Octo\System\Model\Job
     * @return \Octo\System\Model\Job
     */
    public function getCurrentJob()
    {
        $key = $this->getCurrentJobId();

        if (empty($key)) {
           return null;
        }

        return Store::get('Job')->getById($key);
    }

    /**
     * Set CurrentJob - Accepts an ID, an array representing a Job or a Job model.
     * @throws \Exception
     * @param $value mixed
     */
    public function setCurrentJob($value)
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setCurrentJobId($value);
        }

        // Is this an instance of CurrentJob?
        if (is_object($value) && $value instanceof \Octo\System\Model\Job) {
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
     * @param $value \Octo\System\Model\Job
     */
    public function setCurrentJobObject(\Octo\System\Model\Job $value)
    {
        return $this->setCurrentJobId($value->getId());
    }
}
