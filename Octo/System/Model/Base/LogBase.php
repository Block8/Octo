<?php

/**
 * Log base model for table: log
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\Log;

/**
 * Log Base Model
 */
abstract class LogBase extends Model
{
    protected function init()
    {
        $this->table = 'log';
        $this->model = 'Log';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['type'] = null;
        $this->getters['type'] = 'getType';
        $this->setters['type'] = 'setType';
        
        $this->data['scope'] = null;
        $this->getters['scope'] = 'getScope';
        $this->setters['scope'] = 'setScope';
        
        $this->data['scope_id'] = null;
        $this->getters['scope_id'] = 'getScopeId';
        $this->setters['scope_id'] = 'setScopeId';
        
        $this->data['user_id'] = null;
        $this->getters['user_id'] = 'getUserId';
        $this->setters['user_id'] = 'setUserId';
        
        $this->data['message'] = null;
        $this->getters['message'] = 'getMessage';
        $this->setters['message'] = 'setMessage';
        
        $this->data['log_date'] = null;
        $this->getters['log_date'] = 'getLogDate';
        $this->setters['log_date'] = 'setLogDate';
        
        $this->data['link'] = null;
        $this->getters['link'] = 'getLink';
        $this->setters['link'] = 'setLink';
        
        // Foreign keys:
        
        $this->getters['User'] = 'getUser';
        $this->setters['User'] = 'setUser';
        
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
     * @uses \Octo\System\Model\User
     * @return \Octo\System\Model\User
     */
    public function getUser()
    {
        $key = $this->getUserId();

        if (empty($key)) {
           return null;
        }

        return Store::get('User')->getById($key);
    }

    /**
     * Set User - Accepts an ID, an array representing a User or a User model.
     * @throws \Exception
     * @param $value mixed
     */
    public function setUser($value)
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setUserId($value);
        }

        // Is this an instance of User?
        if (is_object($value) && $value instanceof \Octo\System\Model\User) {
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
     * @param $value \Octo\System\Model\User
     */
    public function setUserObject(\Octo\System\Model\User $value)
    {
        return $this->setUserId($value->getId());
    }

}
