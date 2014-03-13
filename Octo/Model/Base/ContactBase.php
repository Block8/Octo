<?php

/**
 * Contact base model for table: contact
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Contact Base Model
 */
class ContactBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'contact';

    /**
    * @var string
    */
    protected $modelName = 'Contact';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'email' => null,
        'phone' => null,
        'title' => null,
        'gender' => null,
        'first_name' => null,
        'last_name' => null,
        'address' => null,
        'postcode' => null,
        'date_of_birth' => null,
        'company' => null,
        'marketing_optin' => null,
        'is_blocked' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'email' => 'getEmail',
        'phone' => 'getPhone',
        'title' => 'getTitle',
        'gender' => 'getGender',
        'first_name' => 'getFirstName',
        'last_name' => 'getLastName',
        'address' => 'getAddress',
        'postcode' => 'getPostcode',
        'date_of_birth' => 'getDateOfBirth',
        'company' => 'getCompany',
        'marketing_optin' => 'getMarketingOptin',
        'is_blocked' => 'getIsBlocked',

        // Foreign key getters:
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'email' => 'setEmail',
        'phone' => 'setPhone',
        'title' => 'setTitle',
        'gender' => 'setGender',
        'first_name' => 'setFirstName',
        'last_name' => 'setLastName',
        'address' => 'setAddress',
        'postcode' => 'setPostcode',
        'date_of_birth' => 'setDateOfBirth',
        'company' => 'setCompany',
        'marketing_optin' => 'setMarketingOptin',
        'is_blocked' => 'setIsBlocked',

        // Foreign key setters:
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
        'email' => [
            'type' => 'varchar',
            'length' => 250,
        ],
        'phone' => [
            'type' => 'varchar',
            'length' => 100,
            'nullable' => true,
            'default' => null,
        ],
        'title' => [
            'type' => 'varchar',
            'length' => 25,
            'nullable' => true,
            'default' => null,
        ],
        'gender' => [
            'type' => 'varchar',
            'length' => 25,
            'nullable' => true,
            'default' => null,
        ],
        'first_name' => [
            'type' => 'varchar',
            'length' => 100,
            'nullable' => true,
            'default' => null,
        ],
        'last_name' => [
            'type' => 'varchar',
            'length' => 100,
            'nullable' => true,
            'default' => null,
        ],
        'address' => [
            'type' => 'text',
            'nullable' => true,
            'default' => null,
        ],
        'postcode' => [
            'type' => 'varchar',
            'length' => 10,
            'nullable' => true,
            'default' => null,
        ],
        'date_of_birth' => [
            'type' => 'datetime',
            'nullable' => true,
            'default' => null,
        ],
        'company' => [
            'type' => 'varchar',
            'length' => 250,
            'nullable' => true,
            'default' => null,
        ],
        'marketing_optin' => [
            'type' => 'tinyint',
            'length' => 1,
        ],
        'is_blocked' => [
            'type' => 'tinyint',
            'length' => 1,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
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
