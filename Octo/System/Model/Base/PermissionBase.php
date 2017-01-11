<?php

/**
 * Permission base model for table: permission
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\Permission;
use Octo\System\Store\PermissionStore;

/**
 * Permission Base Model
 */
abstract class PermissionBase extends Model
{
    protected $table = 'permission';
    protected $model = 'Permission';
    protected $data = [
        'id' => null,
        'user_id' => null,
        'uri' => null,
        'can_access' => 0,
    ];

    protected $getters = [
        'id' => 'getId',
        'user_id' => 'getUserId',
        'uri' => 'getUri',
        'can_access' => 'getCanAccess',
        'User' => 'getUser',
    ];

    protected $setters = [
        'id' => 'setId',
        'user_id' => 'setUserId',
        'uri' => 'setUri',
        'can_access' => 'setCanAccess',
        'User' => 'setUser',
    ];

    /**
     * Return the database store for this model.
     * @return PermissionStore
     */
    public static function Store() : PermissionStore
    {
        return PermissionStore::load();
    }

    /**
     * Get Permission by primary key: id
     * @param int $id
     * @return Permission|null
     */
    public static function get(int $id) : ?Permission
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return Permission
     */
    public function save() : Permission
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save Permission');
        }

        if (!($rtn instanceof Permission)) {
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
     * Get the value of UserId / user_id
     * @return int
     */

     public function getUserId() : int
     {
        $rtn = $this->data['user_id'];

        return $rtn;
     }
    
    /**
     * Get the value of Uri / uri
     * @return string
     */

     public function getUri() : string
     {
        $rtn = $this->data['uri'];

        return $rtn;
     }
    
    /**
     * Get the value of CanAccess / can_access
     * @return int
     */

     public function getCanAccess() : int
     {
        $rtn = $this->data['can_access'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return Permission
     */
    public function setId(int $value) : Permission
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of UserId / user_id
     * @param $value int
     * @return Permission
     */
    public function setUserId(int $value) : Permission
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
     * Set the value of Uri / uri
     * @param $value string
     * @return Permission
     */
    public function setUri(string $value) : Permission
    {

        if ($this->data['uri'] !== $value) {
            $this->data['uri'] = $value;
            $this->setModified('uri');
        }

        return $this;
    }
    
    /**
     * Set the value of CanAccess / can_access
     * @param $value int
     * @return Permission
     */
    public function setCanAccess(int $value) : Permission
    {

        if ($this->data['can_access'] !== $value) {
            $this->data['can_access'] = $value;
            $this->setModified('can_access');
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
