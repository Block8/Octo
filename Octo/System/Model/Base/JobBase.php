<?php

/**
 * Job base model for table: job
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * Job Base Model
 */
trait JobBase
{
    protected function init()
    {
        $this->tableName = 'job';
        $this->modelName = 'Job';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['type'] = null;
        $this->getters['type'] = 'getType';
        $this->setters['type'] = 'setType';
        $this->data['status'] = null;
        $this->getters['status'] = 'getStatus';
        $this->setters['status'] = 'setStatus';
        $this->data['date_created'] = null;
        $this->getters['date_created'] = 'getDateCreated';
        $this->setters['date_created'] = 'setDateCreated';
        $this->data['date_updated'] = null;
        $this->getters['date_updated'] = 'getDateUpdated';
        $this->setters['date_updated'] = 'setDateUpdated';
        $this->data['data'] = null;
        $this->getters['data'] = 'getData';
        $this->setters['data'] = 'setData';
        $this->data['message'] = null;
        $this->getters['message'] = 'getMessage';
        $this->setters['message'] = 'setMessage';
        $this->data['queue_id'] = null;
        $this->getters['queue_id'] = 'getQueueId';
        $this->setters['queue_id'] = 'setQueueId';

        // Foreign keys:
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
    * Get the value of Status / status.
    *
    * @return int
    */
    public function getStatus()
    {
        $rtn = $this->data['status'];

        return $rtn;
    }

    /**
    * Get the value of DateCreated / date_created.
    *
    * @return \DateTime
    */
    public function getDateCreated()
    {
        $rtn = $this->data['date_created'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of DateUpdated / date_updated.
    *
    * @return \DateTime
    */
    public function getDateUpdated()
    {
        $rtn = $this->data['date_updated'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

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
    * Get the value of Message / message.
    *
    * @return string
    */
    public function getMessage()
    {
        $rtn = $this->data['message'];

        return $rtn;
    }

    /**
    * Get the value of QueueId / queue_id.
    *
    * @return int
    */
    public function getQueueId()
    {
        $rtn = $this->data['queue_id'];

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
    * Set the value of Status / status.
    *
    * Must not be null.
    * @param $value int
    */
    public function setStatus($value)
    {
        $this->validateInt('Status', $value);
        $this->validateNotNull('Status', $value);

        if ($this->data['status'] === $value) {
            return;
        }

        $this->data['status'] = $value;
        $this->setModified('status');
    }

    /**
    * Set the value of DateCreated / date_created.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setDateCreated($value)
    {
        $this->validateDate('DateCreated', $value);
        $this->validateNotNull('DateCreated', $value);

        if ($this->data['date_created'] === $value) {
            return;
        }

        $this->data['date_created'] = $value;
        $this->setModified('date_created');
    }

    /**
    * Set the value of DateUpdated / date_updated.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setDateUpdated($value)
    {
        $this->validateDate('DateUpdated', $value);
        $this->validateNotNull('DateUpdated', $value);

        if ($this->data['date_updated'] === $value) {
            return;
        }

        $this->data['date_updated'] = $value;
        $this->setModified('date_updated');
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
    * Set the value of Message / message.
    *
    * Must not be null.
    * @param $value string
    */
    public function setMessage($value)
    {
        $this->validateString('Message', $value);
        $this->validateNotNull('Message', $value);

        if ($this->data['message'] === $value) {
            return;
        }

        $this->data['message'] = $value;
        $this->setModified('message');
    }

    /**
    * Set the value of QueueId / queue_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setQueueId($value)
    {
        $this->validateInt('QueueId', $value);
        $this->validateNotNull('QueueId', $value);

        if ($this->data['queue_id'] === $value) {
            return;
        }

        $this->data['queue_id'] = $value;
        $this->setModified('queue_id');
    }
}
