<?php

/**
 * Log base model for table: log
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * Log Base Model
 */
trait LogBase
{
    protected function init()
    {
        $this->tableName = 'log';
        $this->modelName = 'Log';

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
    * @return int
    */
    public function getType()
    {
        $rtn = $this->data['type'];

        return $rtn;
    }

    /**
    * Get the value of Scope / scope.
    *
    * @return string
    */
    public function getScope()
    {
        $rtn = $this->data['scope'];

        return $rtn;
    }

    /**
    * Get the value of ScopeId / scope_id.
    *
    * @return string
    */
    public function getScopeId()
    {
        $rtn = $this->data['scope_id'];

        return $rtn;
    }

    /**
    * Get the value of UserId / user_id.
    *
    * @return int
    */
    public function getUserId()
    {
        $rtn = $this->data['user_id'];

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
    * Get the value of LogDate / log_date.
    *
    * @return \DateTime
    */
    public function getLogDate()
    {
        $rtn = $this->data['log_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of Link / link.
    *
    * @return string
    */
    public function getLink()
    {
        $rtn = $this->data['link'];

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
        $this->validateNotNull('Id', $value);
        $this->validateInt('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of Type / type.
    *
    * @param $value int
    */
    public function setType($value)
    {
        $this->validateInt('Type', $value);

        if ($this->data['type'] === $value) {
            return;
        }

        $this->data['type'] = $value;
        $this->setModified('type');
    }

    /**
    * Set the value of Scope / scope.
    *
    * @param $value string
    */
    public function setScope($value)
    {
        $this->validateString('Scope', $value);

        if ($this->data['scope'] === $value) {
            return;
        }

        $this->data['scope'] = $value;
        $this->setModified('scope');
    }

    /**
    * Set the value of ScopeId / scope_id.
    *
    * Must not be null.
    * @param $value string
    */
    public function setScopeId($value)
    {
        $this->validateNotNull('ScopeId', $value);
        $this->validateString('ScopeId', $value);

        if ($this->data['scope_id'] === $value) {
            return;
        }

        $this->data['scope_id'] = $value;
        $this->setModified('scope_id');
    }

    /**
    * Set the value of UserId / user_id.
    *
    * @param $value int
    */
    public function setUserId($value)
    {
        $this->validateInt('UserId', $value);

        if ($this->data['user_id'] === $value) {
            return;
        }

        $this->data['user_id'] = $value;
        $this->setModified('user_id');
    }

    /**
    * Set the value of Message / message.
    *
    * Must not be null.
    * @param $value string
    */
    public function setMessage($value)
    {
        $this->validateNotNull('Message', $value);
        $this->validateString('Message', $value);

        if ($this->data['message'] === $value) {
            return;
        }

        $this->data['message'] = $value;
        $this->setModified('message');
    }

    /**
    * Set the value of LogDate / log_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setLogDate($value)
    {
        $this->validateNotNull('LogDate', $value);
        $this->validateDate('LogDate', $value);

        if ($this->data['log_date'] === $value) {
            return;
        }

        $this->data['log_date'] = $value;
        $this->setModified('log_date');
    }

    /**
    * Set the value of Link / link.
    *
    * @param $value string
    */
    public function setLink($value)
    {
        $this->validateString('Link', $value);

        if ($this->data['link'] === $value) {
            return;
        }

        $this->data['link'] = $value;
        $this->setModified('link');
    }
    /**
    * Get the User model for this Log by Id.
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

        return Factory::getStore('User', 'Octo\System')->getById($key);
    }

    /**
    * Set User - Accepts an ID, an array representing a User or a User model.
    *
    * @param $value mixed
    */
    public function setUser($value)
    {
        // Is this an instance of User?
        if ($value instanceof \Octo\System\Model\User) {
            return $this->setUserObject($value);
        }

        // Is this an array representing a User item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setUserId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setUserId($value);
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
