<?php

/**
 * Contact base model for table: contact
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\Contact;

/**
 * Contact Base Model
 */
abstract class ContactBase extends Model
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

     public function getId() : int
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Email / email
     * @return string
     */

     public function getEmail() : ?string
     {
        $rtn = $this->data['email'];

        return $rtn;
     }
    
    /**
     * Get the value of PasswordHash / password_hash
     * @return string
     */

     public function getPasswordHash() : ?string
     {
        $rtn = $this->data['password_hash'];

        return $rtn;
     }
    
    /**
     * Get the value of Phone / phone
     * @return string
     */

     public function getPhone() : ?string
     {
        $rtn = $this->data['phone'];

        return $rtn;
     }
    
    /**
     * Get the value of Mobile / mobile
     * @return string
     */

     public function getMobile() : ?string
     {
        $rtn = $this->data['mobile'];

        return $rtn;
     }
    
    /**
     * Get the value of Title / title
     * @return string
     */

     public function getTitle() : ?string
     {
        $rtn = $this->data['title'];

        return $rtn;
     }
    
    /**
     * Get the value of Gender / gender
     * @return string
     */

     public function getGender() : ?string
     {
        $rtn = $this->data['gender'];

        return $rtn;
     }
    
    /**
     * Get the value of FirstName / first_name
     * @return string
     */

     public function getFirstName() : ?string
     {
        $rtn = $this->data['first_name'];

        return $rtn;
     }
    
    /**
     * Get the value of LastName / last_name
     * @return string
     */

     public function getLastName() : ?string
     {
        $rtn = $this->data['last_name'];

        return $rtn;
     }
    
    /**
     * Get the value of Address / address
     * @return array
     */

     public function getAddress() : ?array
     {
        $rtn = $this->data['address'];

        $rtn = json_decode($rtn, true);

        if ($rtn === false) {
            $rtn = null;
        }

        return $rtn;
     }
    
    /**
     * Get the value of Postcode / postcode
     * @return string
     */

     public function getPostcode() : ?string
     {
        $rtn = $this->data['postcode'];

        return $rtn;
     }
    
    /**
     * Get the value of DateOfBirth / date_of_birth
     * @return DateTime
     */

     public function getDateOfBirth() : ?DateTime
     {
        $rtn = $this->data['date_of_birth'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Company / company
     * @return string
     */

     public function getCompany() : ?string
     {
        $rtn = $this->data['company'];

        return $rtn;
     }
    
    /**
     * Get the value of MarketingOptin / marketing_optin
     * @return int
     */

     public function getMarketingOptin() : int
     {
        $rtn = $this->data['marketing_optin'];

        return $rtn;
     }
    
    /**
     * Get the value of IsBlocked / is_blocked
     * @return int
     */

     public function getIsBlocked() : int
     {
        $rtn = $this->data['is_blocked'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return Contact
     */
    public function setId(int $value) : Contact
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
     * @return Contact
     */
    public function setEmail(?string $value) : Contact
    {

        if ($this->data['email'] !== $value) {
            $this->data['email'] = $value;
            $this->setModified('email');
        }

        return $this;
    }
    
    /**
     * Set the value of PasswordHash / password_hash
     * @param $value string
     * @return Contact
     */
    public function setPasswordHash(?string $value) : Contact
    {

        if ($this->data['password_hash'] !== $value) {
            $this->data['password_hash'] = $value;
            $this->setModified('password_hash');
        }

        return $this;
    }
    
    /**
     * Set the value of Phone / phone
     * @param $value string
     * @return Contact
     */
    public function setPhone(?string $value) : Contact
    {

        if ($this->data['phone'] !== $value) {
            $this->data['phone'] = $value;
            $this->setModified('phone');
        }

        return $this;
    }
    
    /**
     * Set the value of Mobile / mobile
     * @param $value string
     * @return Contact
     */
    public function setMobile(?string $value) : Contact
    {

        if ($this->data['mobile'] !== $value) {
            $this->data['mobile'] = $value;
            $this->setModified('mobile');
        }

        return $this;
    }
    
    /**
     * Set the value of Title / title
     * @param $value string
     * @return Contact
     */
    public function setTitle(?string $value) : Contact
    {

        if ($this->data['title'] !== $value) {
            $this->data['title'] = $value;
            $this->setModified('title');
        }

        return $this;
    }
    
    /**
     * Set the value of Gender / gender
     * @param $value string
     * @return Contact
     */
    public function setGender(?string $value) : Contact
    {

        if ($this->data['gender'] !== $value) {
            $this->data['gender'] = $value;
            $this->setModified('gender');
        }

        return $this;
    }
    
    /**
     * Set the value of FirstName / first_name
     * @param $value string
     * @return Contact
     */
    public function setFirstName(?string $value) : Contact
    {

        if ($this->data['first_name'] !== $value) {
            $this->data['first_name'] = $value;
            $this->setModified('first_name');
        }

        return $this;
    }
    
    /**
     * Set the value of LastName / last_name
     * @param $value string
     * @return Contact
     */
    public function setLastName(?string $value) : Contact
    {

        if ($this->data['last_name'] !== $value) {
            $this->data['last_name'] = $value;
            $this->setModified('last_name');
        }

        return $this;
    }
    
    /**
     * Set the value of Address / address
     * @param $value array
     * @return Contact
     */
    public function setAddress($value) : Contact
    {
        $this->validateJson($value);

        if ($this->data['address'] !== $value) {
            $this->data['address'] = $value;
            $this->setModified('address');
        }

        return $this;
    }
    
    /**
     * Set the value of Postcode / postcode
     * @param $value string
     * @return Contact
     */
    public function setPostcode(?string $value) : Contact
    {

        if ($this->data['postcode'] !== $value) {
            $this->data['postcode'] = $value;
            $this->setModified('postcode');
        }

        return $this;
    }
    
    /**
     * Set the value of DateOfBirth / date_of_birth
     * @param $value DateTime
     * @return Contact
     */
    public function setDateOfBirth($value) : Contact
    {
        $this->validateDate('DateOfBirth', $value);

        if ($this->data['date_of_birth'] !== $value) {
            $this->data['date_of_birth'] = $value;
            $this->setModified('date_of_birth');
        }

        return $this;
    }
    
    /**
     * Set the value of Company / company
     * @param $value string
     * @return Contact
     */
    public function setCompany(?string $value) : Contact
    {

        if ($this->data['company'] !== $value) {
            $this->data['company'] = $value;
            $this->setModified('company');
        }

        return $this;
    }
    
    /**
     * Set the value of MarketingOptin / marketing_optin
     * @param $value int
     * @return Contact
     */
    public function setMarketingOptin(int $value) : Contact
    {

        if ($this->data['marketing_optin'] !== $value) {
            $this->data['marketing_optin'] = $value;
            $this->setModified('marketing_optin');
        }

        return $this;
    }
    
    /**
     * Set the value of IsBlocked / is_blocked
     * @param $value int
     * @return Contact
     */
    public function setIsBlocked(int $value) : Contact
    {

        if ($this->data['is_blocked'] !== $value) {
            $this->data['is_blocked'] = $value;
            $this->setModified('is_blocked');
        }

        return $this;
    }
    
    
}
