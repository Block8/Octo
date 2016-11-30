<?php

/**
 * Contact base model for table: contact
 */

namespace Octo\System\Model\Base;

use DateTime;
use Octo\Model;
use Octo\Store;

/**
 * Contact Base Model
 */
class ContactBase extends Model
{
    protected function init()
    {
        $this->table = 'contact';
        $this->model = 'Contact';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['email'] = null;
        $this->getters['email'] = 'getEmail';
        $this->setters['email'] = 'setEmail';
        
        $this->data['password_hash'] = null;
        $this->getters['password_hash'] = 'getPasswordHash';
        $this->setters['password_hash'] = 'setPasswordHash';
        
        $this->data['phone'] = null;
        $this->getters['phone'] = 'getPhone';
        $this->setters['phone'] = 'setPhone';
        
        $this->data['mobile'] = null;
        $this->getters['mobile'] = 'getMobile';
        $this->setters['mobile'] = 'setMobile';
        
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        
        $this->data['gender'] = null;
        $this->getters['gender'] = 'getGender';
        $this->setters['gender'] = 'setGender';
        
        $this->data['first_name'] = null;
        $this->getters['first_name'] = 'getFirstName';
        $this->setters['first_name'] = 'setFirstName';
        
        $this->data['last_name'] = null;
        $this->getters['last_name'] = 'getLastName';
        $this->setters['last_name'] = 'setLastName';
        
        $this->data['address'] = null;
        $this->getters['address'] = 'getAddress';
        $this->setters['address'] = 'setAddress';
        
        $this->data['postcode'] = null;
        $this->getters['postcode'] = 'getPostcode';
        $this->setters['postcode'] = 'setPostcode';
        
        $this->data['date_of_birth'] = null;
        $this->getters['date_of_birth'] = 'getDateOfBirth';
        $this->setters['date_of_birth'] = 'setDateOfBirth';
        
        $this->data['company'] = null;
        $this->getters['company'] = 'getCompany';
        $this->setters['company'] = 'setCompany';
        
        $this->data['marketing_optin'] = null;
        $this->getters['marketing_optin'] = 'getMarketingOptin';
        $this->setters['marketing_optin'] = 'setMarketingOptin';
        
        $this->data['is_blocked'] = null;
        $this->getters['is_blocked'] = 'getIsBlocked';
        $this->setters['is_blocked'] = 'setIsBlocked';
        
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
     * Get the value of PasswordHash / password_hash
     * @return string
     */

     public function getPasswordHash()
     {
        $rtn = $this->data['password_hash'];

        return $rtn;
     }
    
    /**
     * Get the value of Phone / phone
     * @return string
     */

     public function getPhone()
     {
        $rtn = $this->data['phone'];

        return $rtn;
     }
    
    /**
     * Get the value of Mobile / mobile
     * @return string
     */

     public function getMobile()
     {
        $rtn = $this->data['mobile'];

        return $rtn;
     }
    
    /**
     * Get the value of Title / title
     * @return string
     */

     public function getTitle()
     {
        $rtn = $this->data['title'];

        return $rtn;
     }
    
    /**
     * Get the value of Gender / gender
     * @return string
     */

     public function getGender()
     {
        $rtn = $this->data['gender'];

        return $rtn;
     }
    
    /**
     * Get the value of FirstName / first_name
     * @return string
     */

     public function getFirstName()
     {
        $rtn = $this->data['first_name'];

        return $rtn;
     }
    
    /**
     * Get the value of LastName / last_name
     * @return string
     */

     public function getLastName()
     {
        $rtn = $this->data['last_name'];

        return $rtn;
     }
    
    /**
     * Get the value of Address / address
     * @return string
     */

     public function getAddress()
     {
        $rtn = $this->data['address'];

        return $rtn;
     }
    
    /**
     * Get the value of Postcode / postcode
     * @return string
     */

     public function getPostcode()
     {
        $rtn = $this->data['postcode'];

        return $rtn;
     }
    
    /**
     * Get the value of DateOfBirth / date_of_birth
     * @return DateTime
     */

     public function getDateOfBirth()
     {
        $rtn = $this->data['date_of_birth'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Company / company
     * @return string
     */

     public function getCompany()
     {
        $rtn = $this->data['company'];

        return $rtn;
     }
    
    /**
     * Get the value of MarketingOptin / marketing_optin
     * @return int
     */

     public function getMarketingOptin()
     {
        $rtn = $this->data['marketing_optin'];

        return $rtn;
     }
    
    /**
     * Get the value of IsBlocked / is_blocked
     * @return int
     */

     public function getIsBlocked()
     {
        $rtn = $this->data['is_blocked'];

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
    public function setEmail($value)
    {



        if ($this->data['email'] === $value) {
            return;
        }

        $this->data['email'] = $value;
        $this->setModified('email');
    }
    
    /**
     * Set the value of PasswordHash / password_hash
     * @param $value string
     */
    public function setPasswordHash($value)
    {



        if ($this->data['password_hash'] === $value) {
            return;
        }

        $this->data['password_hash'] = $value;
        $this->setModified('password_hash');
    }
    
    /**
     * Set the value of Phone / phone
     * @param $value string
     */
    public function setPhone($value)
    {



        if ($this->data['phone'] === $value) {
            return;
        }

        $this->data['phone'] = $value;
        $this->setModified('phone');
    }
    
    /**
     * Set the value of Mobile / mobile
     * @param $value string
     */
    public function setMobile($value)
    {



        if ($this->data['mobile'] === $value) {
            return;
        }

        $this->data['mobile'] = $value;
        $this->setModified('mobile');
    }
    
    /**
     * Set the value of Title / title
     * @param $value string
     */
    public function setTitle($value)
    {



        if ($this->data['title'] === $value) {
            return;
        }

        $this->data['title'] = $value;
        $this->setModified('title');
    }
    
    /**
     * Set the value of Gender / gender
     * @param $value string
     */
    public function setGender($value)
    {



        if ($this->data['gender'] === $value) {
            return;
        }

        $this->data['gender'] = $value;
        $this->setModified('gender');
    }
    
    /**
     * Set the value of FirstName / first_name
     * @param $value string
     */
    public function setFirstName($value)
    {



        if ($this->data['first_name'] === $value) {
            return;
        }

        $this->data['first_name'] = $value;
        $this->setModified('first_name');
    }
    
    /**
     * Set the value of LastName / last_name
     * @param $value string
     */
    public function setLastName($value)
    {



        if ($this->data['last_name'] === $value) {
            return;
        }

        $this->data['last_name'] = $value;
        $this->setModified('last_name');
    }
    
    /**
     * Set the value of Address / address
     * @param $value string
     */
    public function setAddress($value)
    {



        if ($this->data['address'] === $value) {
            return;
        }

        $this->data['address'] = $value;
        $this->setModified('address');
    }
    
    /**
     * Set the value of Postcode / postcode
     * @param $value string
     */
    public function setPostcode($value)
    {



        if ($this->data['postcode'] === $value) {
            return;
        }

        $this->data['postcode'] = $value;
        $this->setModified('postcode');
    }
    
    /**
     * Set the value of DateOfBirth / date_of_birth
     * @param $value DateTime
     */
    public function setDateOfBirth($value)
    {
        $this->validateDate('DateOfBirth', $value);


        if ($this->data['date_of_birth'] === $value) {
            return;
        }

        $this->data['date_of_birth'] = $value;
        $this->setModified('date_of_birth');
    }
    
    /**
     * Set the value of Company / company
     * @param $value string
     */
    public function setCompany($value)
    {



        if ($this->data['company'] === $value) {
            return;
        }

        $this->data['company'] = $value;
        $this->setModified('company');
    }
    
    /**
     * Set the value of MarketingOptin / marketing_optin
     * @param $value int
     */
    public function setMarketingOptin(int $value)
    {

        $this->validateNotNull('MarketingOptin', $value);

        if ($this->data['marketing_optin'] === $value) {
            return;
        }

        $this->data['marketing_optin'] = $value;
        $this->setModified('marketing_optin');
    }
    
    /**
     * Set the value of IsBlocked / is_blocked
     * @param $value int
     */
    public function setIsBlocked(int $value)
    {

        $this->validateNotNull('IsBlocked', $value);

        if ($this->data['is_blocked'] === $value) {
            return;
        }

        $this->data['is_blocked'] = $value;
        $this->setModified('is_blocked');
    }
    
    
    public function Apologys()
    {
        return Store::get('Apology')->where('contact_id', $this->data['id']);
    }

    public function Attendees()
    {
        return Store::get('Attendee')->where('contact_id', $this->data['id']);
    }

    public function Submissions()
    {
        return Store::get('Submission')->where('contact_id', $this->data['id']);
    }
}
