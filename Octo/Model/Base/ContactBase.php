<?php

/**
 * Contact base model for table: contact
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * Contact Base Model
 */
trait ContactBase
{
    protected function init()
    {
        $this->tableName = 'contact';
        $this->modelName = 'Contact';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['email'] = null;
        $this->getters['email'] = 'getEmail';
        $this->setters['email'] = 'setEmail';
        $this->data['phone'] = null;
        $this->getters['phone'] = 'getPhone';
        $this->setters['phone'] = 'setPhone';
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
    * Get the value of Email / email.
    *
    * @return string
    */
    public function getEmail()
    {
        $rtn = $this->data['email'];

        return $rtn;
    }

    /**
    * Get the value of Phone / phone.
    *
    * @return string
    */
    public function getPhone()
    {
        $rtn = $this->data['phone'];

        return $rtn;
    }

    /**
    * Get the value of Title / title.
    *
    * @return string
    */
    public function getTitle()
    {
        $rtn = $this->data['title'];

        return $rtn;
    }

    /**
    * Get the value of Gender / gender.
    *
    * @return string
    */
    public function getGender()
    {
        $rtn = $this->data['gender'];

        return $rtn;
    }

    /**
    * Get the value of FirstName / first_name.
    *
    * @return string
    */
    public function getFirstName()
    {
        $rtn = $this->data['first_name'];

        return $rtn;
    }

    /**
    * Get the value of LastName / last_name.
    *
    * @return string
    */
    public function getLastName()
    {
        $rtn = $this->data['last_name'];

        return $rtn;
    }

    /**
    * Get the value of Address / address.
    *
    * @return string
    */
    public function getAddress()
    {
        $rtn = $this->data['address'];

        return $rtn;
    }

    /**
    * Get the value of Postcode / postcode.
    *
    * @return string
    */
    public function getPostcode()
    {
        $rtn = $this->data['postcode'];

        return $rtn;
    }

    /**
    * Get the value of DateOfBirth / date_of_birth.
    *
    * @return \DateTime
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
    * Get the value of Company / company.
    *
    * @return string
    */
    public function getCompany()
    {
        $rtn = $this->data['company'];

        return $rtn;
    }

    /**
    * Get the value of MarketingOptin / marketing_optin.
    *
    * @return int
    */
    public function getMarketingOptin()
    {
        $rtn = $this->data['marketing_optin'];

        return $rtn;
    }

    /**
    * Get the value of IsBlocked / is_blocked.
    *
    * @return int
    */
    public function getIsBlocked()
    {
        $rtn = $this->data['is_blocked'];

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
    * Set the value of Email / email.
    *
    * Must not be null.
    * @param $value string
    */
    public function setEmail($value)
    {
        $this->validateNotNull('Email', $value);
        $this->validateString('Email', $value);

        if ($this->data['email'] === $value) {
            return;
        }

        $this->data['email'] = $value;
        $this->setModified('email');
    }

    /**
    * Set the value of Phone / phone.
    *
    * @param $value string
    */
    public function setPhone($value)
    {
        $this->validateString('Phone', $value);

        if ($this->data['phone'] === $value) {
            return;
        }

        $this->data['phone'] = $value;
        $this->setModified('phone');
    }

    /**
    * Set the value of Title / title.
    *
    * @param $value string
    */
    public function setTitle($value)
    {
        $this->validateString('Title', $value);

        if ($this->data['title'] === $value) {
            return;
        }

        $this->data['title'] = $value;
        $this->setModified('title');
    }

    /**
    * Set the value of Gender / gender.
    *
    * @param $value string
    */
    public function setGender($value)
    {
        $this->validateString('Gender', $value);

        if ($this->data['gender'] === $value) {
            return;
        }

        $this->data['gender'] = $value;
        $this->setModified('gender');
    }

    /**
    * Set the value of FirstName / first_name.
    *
    * @param $value string
    */
    public function setFirstName($value)
    {
        $this->validateString('FirstName', $value);

        if ($this->data['first_name'] === $value) {
            return;
        }

        $this->data['first_name'] = $value;
        $this->setModified('first_name');
    }

    /**
    * Set the value of LastName / last_name.
    *
    * @param $value string
    */
    public function setLastName($value)
    {
        $this->validateString('LastName', $value);

        if ($this->data['last_name'] === $value) {
            return;
        }

        $this->data['last_name'] = $value;
        $this->setModified('last_name');
    }

    /**
    * Set the value of Address / address.
    *
    * @param $value string
    */
    public function setAddress($value)
    {
        $this->validateString('Address', $value);

        if ($this->data['address'] === $value) {
            return;
        }

        $this->data['address'] = $value;
        $this->setModified('address');
    }

    /**
    * Set the value of Postcode / postcode.
    *
    * @param $value string
    */
    public function setPostcode($value)
    {
        $this->validateString('Postcode', $value);

        if ($this->data['postcode'] === $value) {
            return;
        }

        $this->data['postcode'] = $value;
        $this->setModified('postcode');
    }

    /**
    * Set the value of DateOfBirth / date_of_birth.
    *
    * @param $value \DateTime
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
    * Set the value of Company / company.
    *
    * @param $value string
    */
    public function setCompany($value)
    {
        $this->validateString('Company', $value);

        if ($this->data['company'] === $value) {
            return;
        }

        $this->data['company'] = $value;
        $this->setModified('company');
    }

    /**
    * Set the value of MarketingOptin / marketing_optin.
    *
    * Must not be null.
    * @param $value int
    */
    public function setMarketingOptin($value)
    {
        $this->validateNotNull('MarketingOptin', $value);
        $this->validateInt('MarketingOptin', $value);

        if ($this->data['marketing_optin'] === $value) {
            return;
        }

        $this->data['marketing_optin'] = $value;
        $this->setModified('marketing_optin');
    }

    /**
    * Set the value of IsBlocked / is_blocked.
    *
    * Must not be null.
    * @param $value int
    */
    public function setIsBlocked($value)
    {
        $this->validateNotNull('IsBlocked', $value);
        $this->validateInt('IsBlocked', $value);

        if ($this->data['is_blocked'] === $value) {
            return;
        }

        $this->data['is_blocked'] = $value;
        $this->setModified('is_blocked');
    }

}
