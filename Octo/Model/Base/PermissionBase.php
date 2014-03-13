<?php

/**
 * Permission base model for table: permission
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Permission Base Model
 */
class PermissionBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'permission';

    /**
    * @var string
    */
    protected $modelName = 'Permission';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'user_id' => null,
        'uri' => null,
        'can_access' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'user_id' => 'getUserId',
        'uri' => 'getUri',
        'can_access' => 'getCanAccess',

        // Foreign key getters:
        'User' => 'getUser',
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'user_id' => 'setUserId',
        'uri' => 'setUri',
        'can_access' => 'setCanAccess',

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
        'user_id' => [
            'type' => 'int',
            'length' => 11,
            'default' => null,
        ],
        'uri' => [
            'type' => 'varchar',
            'length' => 500,
        ],
        'can_access' => [
            'type' => 'tinyint',
            'length' => 1,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'idx_quick_check' => ['columns' => 'user_id, uri, can_access'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
        'permission_ibfk_1' => [
            'local_col' => 'user_id',
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
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
    * Get the value of Uri / uri.
    *
    * @return string
    */
    public function getUri()
    {
        $rtn = $this->data['uri'];

        return $rtn;
    }

    /**
    * Get the value of CanAccess / can_access.
    *
    * @return int
    */
    public function getCanAccess()
    {
        $rtn = $this->data['can_access'];

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
    * Set the value of UserId / user_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setUserId($value)
    {
        $this->validateNotNull('UserId', $value);
        $this->validateInt('UserId', $value);

        if ($this->data['user_id'] === $value) {
            return;
        }

        $this->data['user_id'] = $value;
        $this->setModified('user_id');
    }

    /**
    * Set the value of Uri / uri.
    *
    * Must not be null.
    * @param $value string
    */
    public function setUri($value)
    {
        $this->validateNotNull('Uri', $value);
        $this->validateString('Uri', $value);

        if ($this->data['uri'] === $value) {
            return;
        }

        $this->data['uri'] = $value;
        $this->setModified('uri');
    }

    /**
    * Set the value of CanAccess / can_access.
    *
    * Must not be null.
    * @param $value int
    */
    public function setCanAccess($value)
    {
        $this->validateNotNull('CanAccess', $value);
        $this->validateInt('CanAccess', $value);

        if ($this->data['can_access'] === $value) {
            return;
        }

        $this->data['can_access'] = $value;
        $this->setModified('can_access');
    }

    /**
    * Get the User model for this Permission by Id.
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
