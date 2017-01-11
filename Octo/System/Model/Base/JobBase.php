<?php

/**
 * Job base model for table: job
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\Job;
use Octo\System\Store\JobStore;

/**
 * Job Base Model
 */
abstract class JobBase extends Model
{
    protected $table = 'job';
    protected $model = 'Job';
    protected $data = [
        'id' => null,
        'type' => null,
        'status' => 0,
        'date_created' => null,
        'date_updated' => null,
        'data' => null,
        'message' => null,
        'queue_id' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'type' => 'getType',
        'status' => 'getStatus',
        'date_created' => 'getDateCreated',
        'date_updated' => 'getDateUpdated',
        'data' => 'getData',
        'message' => 'getMessage',
        'queue_id' => 'getQueueId',
    ];

    protected $setters = [
        'id' => 'setId',
        'type' => 'setType',
        'status' => 'setStatus',
        'date_created' => 'setDateCreated',
        'date_updated' => 'setDateUpdated',
        'data' => 'setData',
        'message' => 'setMessage',
        'queue_id' => 'setQueueId',
    ];

    /**
     * Return the database store for this model.
     * @return JobStore
     */
    public static function Store() : JobStore
    {
        return JobStore::load();
    }

    /**
     * Get Job by primary key: id
     * @param int $id
     * @return Job|null
     */
    public static function get(int $id) : ?Job
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return Job
     */
    public function save() : Job
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save Job');
        }

        if (!($rtn instanceof Job)) {
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
     * Get the value of Status / status
     * @return int
     */

     public function getStatus() : int
     {
        $rtn = $this->data['status'];

        return $rtn;
     }
    
    /**
     * Get the value of DateCreated / date_created
     * @return DateTime
     */

     public function getDateCreated() : DateTime
     {
        $rtn = $this->data['date_created'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of DateUpdated / date_updated
     * @return DateTime
     */

     public function getDateUpdated() : DateTime
     {
        $rtn = $this->data['date_updated'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

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
     * Get the value of Message / message
     * @return string
     */

     public function getMessage() : ?string
     {
        $rtn = $this->data['message'];

        return $rtn;
     }
    
    /**
     * Get the value of QueueId / queue_id
     * @return int
     */

     public function getQueueId() : ?int
     {
        $rtn = $this->data['queue_id'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return Job
     */
    public function setId(int $value) : Job
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
     * @return Job
     */
    public function setType(string $value) : Job
    {

        if ($this->data['type'] !== $value) {
            $this->data['type'] = $value;
            $this->setModified('type');
        }

        return $this;
    }
    
    /**
     * Set the value of Status / status
     * @param $value int
     * @return Job
     */
    public function setStatus(int $value) : Job
    {

        if ($this->data['status'] !== $value) {
            $this->data['status'] = $value;
            $this->setModified('status');
        }

        return $this;
    }
    
    /**
     * Set the value of DateCreated / date_created
     * @param $value DateTime
     * @return Job
     */
    public function setDateCreated($value) : Job
    {
        $this->validateDate('DateCreated', $value);

        if ($this->data['date_created'] !== $value) {
            $this->data['date_created'] = $value;
            $this->setModified('date_created');
        }

        return $this;
    }
    
    /**
     * Set the value of DateUpdated / date_updated
     * @param $value DateTime
     * @return Job
     */
    public function setDateUpdated($value) : Job
    {
        $this->validateDate('DateUpdated', $value);

        if ($this->data['date_updated'] !== $value) {
            $this->data['date_updated'] = $value;
            $this->setModified('date_updated');
        }

        return $this;
    }
    
    /**
     * Set the value of Data / data
     * @param $value array
     * @return Job
     */
    public function setData($value) : Job
    {
        $this->validateJson($value);

        if ($this->data['data'] !== $value) {
            $this->data['data'] = $value;
            $this->setModified('data');
        }

        return $this;
    }
    
    /**
     * Set the value of Message / message
     * @param $value string
     * @return Job
     */
    public function setMessage(?string $value) : Job
    {

        if ($this->data['message'] !== $value) {
            $this->data['message'] = $value;
            $this->setModified('message');
        }

        return $this;
    }
    
    /**
     * Set the value of QueueId / queue_id
     * @param $value int
     * @return Job
     */
    public function setQueueId(?int $value) : Job
    {

        if ($this->data['queue_id'] !== $value) {
            $this->data['queue_id'] = $value;
            $this->setModified('queue_id');
        }

        return $this;
    }
    
    

    public function ScheduledJobs() : Query
    {
        return Store::get('ScheduledJob')->where('current_job_id', $this->data['id']);
    }
}
