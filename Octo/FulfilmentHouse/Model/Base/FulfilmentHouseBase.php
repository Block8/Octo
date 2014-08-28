<?php

/**
 * FulfilmentHouse base model for table: fulfilment_house
 */

namespace Octo\FulfilmentHouse\Model\Base;

use b8\Store\Factory;

/**
 * FulfilmentHouse Base Model
 */
trait FulfilmentHouseBase
{
    protected function init()
    {
        $this->tableName = 'fulfilment_house';
        $this->modelName = 'FulfilmentHouse';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['name'] = null;
        $this->getters['name'] = 'getName';
        $this->setters['name'] = 'setName';
        $this->data['email_1'] = null;
        $this->getters['email_1'] = 'getEmail1';
        $this->setters['email_1'] = 'setEmail1';
        $this->data['email_2'] = null;
        $this->getters['email_2'] = 'getEmail2';
        $this->setters['email_2'] = 'setEmail2';
        $this->data['email_3'] = null;
        $this->getters['email_3'] = 'getEmail3';
        $this->setters['email_3'] = 'setEmail3';
        $this->data['email_copy'] = null;
        $this->getters['email_copy'] = 'getEmailCopy';
        $this->setters['email_copy'] = 'setEmailCopy';

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
    * Get the value of Name / name.
    *
    * @return string
    */
    public function getName()
    {
        $rtn = $this->data['name'];

        return $rtn;
    }

    /**
    * Get the value of Email1 / email_1.
    *
    * @return string
    */
    public function getEmail1()
    {
        $rtn = $this->data['email_1'];

        return $rtn;
    }

    /**
    * Get the value of Email2 / email_2.
    *
    * @return string
    */
    public function getEmail2()
    {
        $rtn = $this->data['email_2'];

        return $rtn;
    }

    /**
    * Get the value of Email3 / email_3.
    *
    * @return string
    */
    public function getEmail3()
    {
        $rtn = $this->data['email_3'];

        return $rtn;
    }

    /**
    * Get the value of EmailCopy / email_copy.
    *
    * @return string
    */
    public function getEmailCopy()
    {
        $rtn = $this->data['email_copy'];

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
    * Set the value of Name / name.
    *
    * @param $value string
    */
    public function setName($value)
    {
        $this->validateString('Name', $value);

        if ($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;
        $this->setModified('name');
    }

    /**
    * Set the value of Email1 / email_1.
    *
    * @param $value string
    */
    public function setEmail1($value)
    {
        $this->validateString('Email1', $value);

        if ($this->data['email_1'] === $value) {
            return;
        }

        $this->data['email_1'] = $value;
        $this->setModified('email_1');
    }

    /**
    * Set the value of Email2 / email_2.
    *
    * @param $value string
    */
    public function setEmail2($value)
    {
        $this->validateString('Email2', $value);

        if ($this->data['email_2'] === $value) {
            return;
        }

        $this->data['email_2'] = $value;
        $this->setModified('email_2');
    }

    /**
    * Set the value of Email3 / email_3.
    *
    * @param $value string
    */
    public function setEmail3($value)
    {
        $this->validateString('Email3', $value);

        if ($this->data['email_3'] === $value) {
            return;
        }

        $this->data['email_3'] = $value;
        $this->setModified('email_3');
    }

    /**
    * Set the value of EmailCopy / email_copy.
    *
    * @param $value string
    */
    public function setEmailCopy($value)
    {
        $this->validateString('EmailCopy', $value);

        if ($this->data['email_copy'] === $value) {
            return;
        }

        $this->data['email_copy'] = $value;
        $this->setModified('email_copy');
    }
}
