<?php

/**
 * Log base model for table: log
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Log Base Model
 */
class LogBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'log';

    /**
    * @var string
    */
    protected $modelName = 'Log';

    /**
    * @var array
    */
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

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'type' => 'getType',
        'scope' => 'getScope',
        'scope_id' => 'getScopeId',
        'user_id' => 'getUserId',
        'message' => 'getMessage',
        'log_date' => 'getLogDate',
        'link' => 'getLink',

        // Foreign key getters:
        'User' => 'getUser',
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'type' => 'setType',
        'scope' => 'setScope',
        'scope_id' => 'setScopeId',
        'user_id' => 'setUserId',
        'message' => 'setMessage',
        'log_date' => 'setLogDate',
        'link' => 'setLink',

        // Foreign key setters:
        'User' => 'setUser',
    ];

    /**
    * @var array
    */
    public $columns = [
        'id' => [
            'type' => 'int',
            'length' => 11,
            'primary_key' => true,
            'auto_increment' => true,
            'default' => null,
        ],
        'type' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'scope' => [
            'type' => 'varchar',
            'length' => 32,
            'nullable' => true,
            'default' => null,
        ],
        'scope_id' => [
            'type' => 'varchar',
            'length' => 32,
        ],
        'user_id' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'message' => [
            'type' => 'varchar',
            'length' => 500,
        ],
        'log_date' => [
            'type' => 'datetime',
            'default' => null,
        ],
        'link' => [
            'type' => 'varchar',
            'length' => 500,
            'nullable' => true,
            'default' => null,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'scope' => ['columns' => 'scope'],
        'type' => ['columns' => 'type'],
        'user_id' => ['columns' => 'user_id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
        'log_ibfk_1' => [
            'local_col' => 'user_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'user',
            'col' => 'id'
        ],
    ];

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
    * @uses \Octo\Store\UserStore::getById()
    * @uses \Octo\Model\User
    * @return \Octo\Model\User
    */
    public function getUser()
    {
        $key = $this->getUserId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('User', 'Octo')->getById($key);
    }

    /**
    * Set User - Accepts an ID, an array representing a User or a User model.
    *
    * @param $value mixed
    */
    public function setUser($value)
    {
        // Is this an instance of User?
        if ($value instanceof \Octo\Model\User) {
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
    * @param $value \Octo\Model\User
    */
    public function setUserObject(\Octo\Model\User $value)
    {
        return $this->setUserId($value->getId());
    }
}
