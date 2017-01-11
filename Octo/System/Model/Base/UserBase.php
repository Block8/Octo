<?php

/**
 * User base model for table: user
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\User;
use Octo\System\Store\UserStore;

/**
 * User Base Model
 */
abstract class UserBase extends Model
{
    protected $table = 'user';
    protected $model = 'User';
    protected $data = [
        'id' => null,
        'email' => null,
        'hash' => null,
        'name' => null,
        'is_admin' => 0,
        'is_hidden' => 0,
        'date_added' => null,
        'date_active' => null,
        'active' => 1,
    ];

    protected $getters = [
        'id' => 'getId',
        'email' => 'getEmail',
        'hash' => 'getHash',
        'name' => 'getName',
        'is_admin' => 'getIsAdmin',
        'is_hidden' => 'getIsHidden',
        'date_added' => 'getDateAdded',
        'date_active' => 'getDateActive',
        'active' => 'getActive',
    ];

    protected $setters = [
        'id' => 'setId',
        'email' => 'setEmail',
        'hash' => 'setHash',
        'name' => 'setName',
        'is_admin' => 'setIsAdmin',
        'is_hidden' => 'setIsHidden',
        'date_added' => 'setDateAdded',
        'date_active' => 'setDateActive',
        'active' => 'setActive',
    ];

    /**
     * Return the database store for this model.
     * @return UserStore
     */
    public static function Store() : UserStore
    {
        return UserStore::load();
    }

    /**
     * Get User by primary key: id
     * @param int $id
     * @return User|null
     */
    public static function get(int $id) : ?User
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return User
     */
    public function save() : User
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save User');
        }

        if (!($rtn instanceof User)) {
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
     * Get the value of Email / email
     * @return string
     */
     public function getEmail() : string
     {
        $rtn = $this->data['email'];

        return $rtn;
     }
    
    /**
     * Get the value of Hash / hash
     * @return string
     */
     public function getHash() : string
     {
        $rtn = $this->data['hash'];

        return $rtn;
     }
    
    /**
     * Get the value of Name / name
     * @return string
     */
     public function getName() : ?string
     {
        $rtn = $this->data['name'];

        return $rtn;
     }
    
    /**
     * Get the value of IsAdmin / is_admin
     * @return int
     */
     public function getIsAdmin() : int
     {
        $rtn = $this->data['is_admin'];

        return $rtn;
     }
    
    /**
     * Get the value of IsHidden / is_hidden
     * @return int
     */
     public function getIsHidden() : int
     {
        $rtn = $this->data['is_hidden'];

        return $rtn;
     }
    
    /**
     * Get the value of DateAdded / date_added
     * @return DateTime
     */
     public function getDateAdded() : ?DateTime
     {
        $rtn = $this->data['date_added'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of DateActive / date_active
     * @return DateTime
     */
     public function getDateActive() : ?DateTime
     {
        $rtn = $this->data['date_active'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Active / active
     * @return int
     */
     public function getActive() : int
     {
        $rtn = $this->data['active'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return User
     */
    public function setId(int $value) : User
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Email / email
     * @param $value string
     * @return User
     */
    public function setEmail(string $value) : User
    {

        if ($this->data['email'] !== $value) {
            $this->data['email'] = $value;
            $this->setModified('email');
        }

        return $this;
    }
    
    /**
     * Set the value of Hash / hash
     * @param $value string
     * @return User
     */
    public function setHash(string $value) : User
    {

        if ($this->data['hash'] !== $value) {
            $this->data['hash'] = $value;
            $this->setModified('hash');
        }

        return $this;
    }
    
    /**
     * Set the value of Name / name
     * @param $value string
     * @return User
     */
    public function setName(?string $value) : User
    {

        if ($this->data['name'] !== $value) {
            $this->data['name'] = $value;
            $this->setModified('name');
        }

        return $this;
    }
    
    /**
     * Set the value of IsAdmin / is_admin
     * @param $value int
     * @return User
     */
    public function setIsAdmin(int $value) : User
    {

        if ($this->data['is_admin'] !== $value) {
            $this->data['is_admin'] = $value;
            $this->setModified('is_admin');
        }

        return $this;
    }
    
    /**
     * Set the value of IsHidden / is_hidden
     * @param $value int
     * @return User
     */
    public function setIsHidden(int $value) : User
    {

        if ($this->data['is_hidden'] !== $value) {
            $this->data['is_hidden'] = $value;
            $this->setModified('is_hidden');
        }

        return $this;
    }
    
    /**
     * Set the value of DateAdded / date_added
     * @param $value DateTime
     * @return User
     */
    public function setDateAdded($value) : User
    {
        $this->validateDate('DateAdded', $value);

        if ($this->data['date_added'] !== $value) {
            $this->data['date_added'] = $value;
            $this->setModified('date_added');
        }

        return $this;
    }
    
    /**
     * Set the value of DateActive / date_active
     * @param $value DateTime
     * @return User
     */
    public function setDateActive($value) : User
    {
        $this->validateDate('DateActive', $value);

        if ($this->data['date_active'] !== $value) {
            $this->data['date_active'] = $value;
            $this->setModified('date_active');
        }

        return $this;
    }
    
    /**
     * Set the value of Active / active
     * @param $value int
     * @return User
     */
    public function setActive(int $value) : User
    {

        if ($this->data['active'] !== $value) {
            $this->data['active'] = $value;
            $this->setModified('active');
        }

        return $this;
    }
    
    

    public function Logs() : Query
    {
        return Store::get('Log')->where('user_id', $this->data['id']);
    }

    public function Permissions() : Query
    {
        return Store::get('Permission')->where('user_id', $this->data['id']);
    }
}
