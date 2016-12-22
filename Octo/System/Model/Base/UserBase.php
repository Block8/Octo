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

/**
 * User Base Model
 */
abstract class UserBase extends Model
{
    protected function init()
    {
        $this->table = 'user';
        $this->model = 'User';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['email'] = null;
        $this->getters['email'] = 'getEmail';
        $this->setters['email'] = 'setEmail';
        
        $this->data['hash'] = null;
        $this->getters['hash'] = 'getHash';
        $this->setters['hash'] = 'setHash';
        
        $this->data['name'] = null;
        $this->getters['name'] = 'getName';
        $this->setters['name'] = 'setName';
        
        $this->data['is_admin'] = null;
        $this->getters['is_admin'] = 'getIsAdmin';
        $this->setters['is_admin'] = 'setIsAdmin';
        
        $this->data['is_hidden'] = null;
        $this->getters['is_hidden'] = 'getIsHidden';
        $this->setters['is_hidden'] = 'setIsHidden';
        
        $this->data['date_added'] = null;
        $this->getters['date_added'] = 'getDateAdded';
        $this->setters['date_added'] = 'setDateAdded';
        
        $this->data['date_active'] = null;
        $this->getters['date_active'] = 'getDateActive';
        $this->setters['date_active'] = 'setDateActive';
        
        $this->data['active'] = null;
        $this->getters['active'] = 'getActive';
        $this->setters['active'] = 'setActive';
        
        // Foreign keys:
        
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
