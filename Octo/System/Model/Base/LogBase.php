<?php

/**
 * Log base model for table: log
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;

use Octo\System\Store\LogStore;
use Octo\System\Model\Log;
use Octo\System\Model\User;

/**
 * Log Base Model
 */
abstract class LogBase extends Model
{
    protected $table = 'log';
    protected $model = 'Log';
    protected $data = [
        'id' => null,
        'type' => null,
        'scope' => null,
        'scope_id' => null,
        'user_id' => null,
        'message' => null,
        'log_date' => null,
        'link' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'type' => 'getType',
        'scope' => 'getScope',
        'scope_id' => 'getScopeId',
        'user_id' => 'getUserId',
        'message' => 'getMessage',
        'log_date' => 'getLogDate',
        'link' => 'getLink',
        'User' => 'getUser',
    ];

    protected $setters = [
        'id' => 'setId',
        'type' => 'setType',
        'scope' => 'setScope',
        'scope_id' => 'setScopeId',
        'user_id' => 'setUserId',
        'message' => 'setMessage',
        'log_date' => 'setLogDate',
        'link' => 'setLink',
        'User' => 'setUser',
    ];

    /**
     * Return the database store for this model.
     * @return LogStore
     */
    public static function Store() : LogStore
    {
        return LogStore::load();
    }

    /**
     * Get Log by primary key: id
     * @param int $id
     * @return Log|null
     */
    public static function get(int $id) : ?Log
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return Log
     */
    public function save() : Log
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save Log');
        }

        if (!($rtn instanceof Log)) {
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
     * @return int
     */
     public function getType() : ?int
     {
        $rtn = $this->data['type'];

        return $rtn;
     }
    
    /**
     * Get the value of Scope / scope
     * @return string
     */
     public function getScope() : ?string
     {
        $rtn = $this->data['scope'];

        return $rtn;
     }
    
    /**
     * Get the value of ScopeId / scope_id
     * @return string
     */
     public function getScopeId() : ?string
     {
        $rtn = $this->data['scope_id'];

        return $rtn;
     }
    
    /**
     * Get the value of UserId / user_id
     * @return int
     */
     public function getUserId() : ?int
     {
        $rtn = $this->data['user_id'];

        return $rtn;
     }
    
    /**
     * Get the value of Message / message
     * @return string
     */
     public function getMessage() : string
     {
        $rtn = $this->data['message'];

        return $rtn;
     }
    
    /**
     * Get the value of LogDate / log_date
     * @return DateTime
     */
     public function getLogDate() : DateTime
     {
        $rtn = $this->data['log_date'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Link / link
     * @return string
     */
     public function getLink() : ?string
     {
        $rtn = $this->data['link'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return Log
     */
    public function setId(int $value) : Log
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Type / type
     * @param $value int
     * @return Log
     */
    public function setType(?int $value) : Log
    {

        if ($this->data['type'] !== $value) {
            $this->data['type'] = $value;
            $this->setModified('type');
        }

        return $this;
    }
    
    /**
     * Set the value of Scope / scope
     * @param $value string
     * @return Log
     */
    public function setScope(?string $value) : Log
    {

        if ($this->data['scope'] !== $value) {
            $this->data['scope'] = $value;
            $this->setModified('scope');
        }

        return $this;
    }
    
    /**
     * Set the value of ScopeId / scope_id
     * @param $value string
     * @return Log
     */
    public function setScopeId(?string $value) : Log
    {

        if ($this->data['scope_id'] !== $value) {
            $this->data['scope_id'] = $value;
            $this->setModified('scope_id');
        }

        return $this;
    }
    
    /**
     * Set the value of UserId / user_id
     * @param $value int
     * @return Log
     */
    public function setUserId(?int $value) : Log
    {

        // As this column is a foreign key, empty values should be considered null.
        if (empty($value)) {
            $value = null;
        }


        if ($this->data['user_id'] !== $value) {
            $this->data['user_id'] = $value;
            $this->setModified('user_id');
        }

        return $this;
    }
    
    /**
     * Set the value of Message / message
     * @param $value string
     * @return Log
     */
    public function setMessage(string $value) : Log
    {

        if ($this->data['message'] !== $value) {
            $this->data['message'] = $value;
            $this->setModified('message');
        }

        return $this;
    }
    
    /**
     * Set the value of LogDate / log_date
     * @param $value DateTime
     * @return Log
     */
    public function setLogDate($value) : Log
    {
        $this->validateDate('LogDate', $value);

        if ($this->data['log_date'] !== $value) {
            $this->data['log_date'] = $value;
            $this->setModified('log_date');
        }

        return $this;
    }
    
    /**
     * Set the value of Link / link
     * @param $value string
     * @return Log
     */
    public function setLink(?string $value) : Log
    {

        if ($this->data['link'] !== $value) {
            $this->data['link'] = $value;
            $this->setModified('link');
        }

        return $this;
    }
    

    /**
     * Get the User model for this  by Id.
     *
     * @uses \Octo\System\Store\UserStore::getById()
     * @uses User
     * @return User|null
     */
    public function getUser() : ?User
    {
        $key = $this->getUserId();

        if (empty($key)) {
           return null;
        }

        return User::Store()->getById($key);
    }

    /**
     * Set User - Accepts an ID, an array representing a User or a User model.
     * @throws \Exception
     * @param $value mixed
     * @return Log
     */
    public function setUser($value) : Log
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setUserId($value);
        }

        // Is this an instance of User?
        if (is_object($value) && $value instanceof User) {
            return $this->setUserObject($value);
        }

        // Is this an array representing a User item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setUserId($value['id']);
        }

        // None of the above? That's a problem!
        throw new \Exception('Invalid value for User.');
    }

    /**
     * Set User - Accepts a User model.
     *
     * @param $value User
     * @return Log
     */
    public function setUserObject(User $value) : Log
    {
        return $this->setUserId($value->getId());
    }
}
