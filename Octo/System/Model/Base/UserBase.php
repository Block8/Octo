<?php

/**
 * User base model for table: user
 */

namespace Octo\System\Model\Base;

use Octo\Model;
use Octo\Store;

/**
 * User Base Model
 */
class UserBase extends Model
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

     public function getId()
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Email / email
     * @return string
     */

     public function getEmail()
     {
        $rtn = $this->data['email'];

        return $rtn;
     }
    
    /**
     * Get the value of Hash / hash
     * @return string
     */

     public function getHash()
     {
        $rtn = $this->data['hash'];

        return $rtn;
     }
    
    /**
     * Get the value of Name / name
     * @return string
     */

     public function getName()
     {
        $rtn = $this->data['name'];

        return $rtn;
     }
    
    /**
     * Get the value of IsAdmin / is_admin
     * @return int
     */

     public function getIsAdmin()
     {
        $rtn = $this->data['is_admin'];

        return $rtn;
     }
    
    /**
     * Get the value of IsHidden / is_hidden
     * @return int
     */

     public function getIsHidden()
     {
        $rtn = $this->data['is_hidden'];

        return $rtn;
     }
    
    /**
     * Get the value of DateAdded / date_added
     * @return DateTime
     */

     public function getDateAdded()
     {
        $rtn = $this->data['date_added'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of DateActive / date_active
     * @return DateTime
     */

     public function getDateActive()
     {
        $rtn = $this->data['date_active'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Active / active
     * @return int
     */

     public function getActive()
     {
        $rtn = $this->data['active'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     */
    public function setId(int $value)
    {

        $this->validateNotNull('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }
    
    /**
     * Set the value of Email / email
     * @param $value string
     */
    public function setEmail(string $value)
    {

        $this->validateNotNull('Email', $value);

        if ($this->data['email'] === $value) {
            return;
        }

        $this->data['email'] = $value;
        $this->setModified('email');
    }
    
    /**
     * Set the value of Hash / hash
     * @param $value string
     */
    public function setHash(string $value)
    {

        $this->validateNotNull('Hash', $value);

        if ($this->data['hash'] === $value) {
            return;
        }

        $this->data['hash'] = $value;
        $this->setModified('hash');
    }
    
    /**
     * Set the value of Name / name
     * @param $value string
     */
    public function setName($value)
    {



        if ($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;
        $this->setModified('name');
    }
    
    /**
     * Set the value of IsAdmin / is_admin
     * @param $value int
     */
    public function setIsAdmin(int $value)
    {

        $this->validateNotNull('IsAdmin', $value);

        if ($this->data['is_admin'] === $value) {
            return;
        }

        $this->data['is_admin'] = $value;
        $this->setModified('is_admin');
    }
    
    /**
     * Set the value of IsHidden / is_hidden
     * @param $value int
     */
    public function setIsHidden(int $value)
    {

        $this->validateNotNull('IsHidden', $value);

        if ($this->data['is_hidden'] === $value) {
            return;
        }

        $this->data['is_hidden'] = $value;
        $this->setModified('is_hidden');
    }
    
    /**
     * Set the value of DateAdded / date_added
     * @param $value DateTime
     */
    public function setDateAdded($value)
    {
        $this->validateDate('DateAdded', $value);


        if ($this->data['date_added'] === $value) {
            return;
        }

        $this->data['date_added'] = $value;
        $this->setModified('date_added');
    }
    
    /**
     * Set the value of DateActive / date_active
     * @param $value DateTime
     */
    public function setDateActive($value)
    {
        $this->validateDate('DateActive', $value);


        if ($this->data['date_active'] === $value) {
            return;
        }

        $this->data['date_active'] = $value;
        $this->setModified('date_active');
    }
    
    /**
     * Set the value of Active / active
     * @param $value int
     */
    public function setActive(int $value)
    {

        $this->validateNotNull('Active', $value);

        if ($this->data['active'] === $value) {
            return;
        }

        $this->data['active'] = $value;
        $this->setModified('active');
    }
    
    
    public function CalendarEvents()
    {
        return Store::get('CalendarEvent')->where('confirmed_by_user_id', $this->data['id']);
    }

    public function Files()
    {
        return Store::get('File')->where('user_id', $this->data['id']);
    }

    public function Logs()
    {
        return Store::get('Log')->where('user_id', $this->data['id']);
    }

    public function PageVersions()
    {
        return Store::get('PageVersion')->where('user_id', $this->data['id']);
    }

    public function Permissions()
    {
        return Store::get('Permission')->where('user_id', $this->data['id']);
    }
}
