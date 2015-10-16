<?php

/**
 * ScheduledJob base model for table: scheduled_job
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * ScheduledJob Base Model
 */
trait ScheduledJobBase
{
    protected function init()
    {
        $this->tableName = 'scheduled_job';
        $this->modelName = 'ScheduledJob';

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
    * Get the value of Id / id.
    *
    * @return int
    */
    public function getId()
    {
        $rtn = $this->data['id'];

        return $rtn;
    }

    /**
    * Get the value of Type / type.
    *
    * @return string
    */
    public function getType()
    {
        $rtn = $this->data['type'];

        return $rtn;
    }

    /**
    * Get the value of Data / data.
    *
    * @return string
    */
    public function getData()
    {
        $rtn = $this->data['data'];

        return $rtn;
    }

    /**
    * Get the value of Frequency / frequency.
    *
    * @return int
    */
    public function getFrequency()
    {
        $rtn = $this->data['frequency'];

        return $rtn;
    }

    /**
    * Get the value of CurrentJobId / current_job_id.
    *
    * @return int
    */
    public function getCurrentJobId()
    {
        $rtn = $this->data['current_job_id'];

        return $rtn;
    }


    /**
    * Set the value of Id / id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setId($value)
    {
        $this->validateInt('Id', $value);
        $this->validateNotNull('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of Type / type.
    *
    * Must not be null.
    * @param $value string
    */
    public function setType($value)
    {
        $this->validateString('Type', $value);
        $this->validateNotNull('Type', $value);

        if ($this->data['type'] === $value) {
            return;
        }

        $this->data['type'] = $value;
        $this->setModified('type');
    }

    /**
    * Set the value of Data / data.
    *
    * Must not be null.
    * @param $value string
    */
    public function setData($value)
    {
        $this->validateString('Data', $value);
        $this->validateNotNull('Data', $value);

        if ($this->data['data'] === $value) {
            return;
        }

        $this->data['data'] = $value;
        $this->setModified('data');
    }

    /**
    * Set the value of Frequency / frequency.
    *
    * Must not be null.
    * @param $value int
    */
    public function setFrequency($value)
    {
        $this->validateInt('Frequency', $value);
        $this->validateNotNull('Frequency', $value);

        if ($this->data['frequency'] === $value) {
            return;
        }

        $this->data['frequency'] = $value;
        $this->setModified('frequency');
    }

    /**
    * Set the value of CurrentJobId / current_job_id.
    *
    * @param $value int
    */
    public function setCurrentJobId($value)
    {
        $this->validateInt('CurrentJobId', $value);

        // As this is a foreign key, empty values should be treated as null:
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
    * Get the Job model for this ScheduledJob by Id.
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

        return Factory::getStore('Job', 'Octo\System')->getById($key);
    }

    /**
    * Set CurrentJob - Accepts an ID, an array representing a Job or a Job model.
    *
    * @param $value mixed
    */
    public function setCurrentJob($value)
    {
        // Is this an instance of Job?
        if ($value instanceof \Octo\System\Model\Job) {
            return $this->setCurrentJobObject($value);
        }

        // Is this an array representing a Job item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setCurrentJobId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setCurrentJobId($value);
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
