<?php

/**
 * User base model for table: user
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * User Base Model
 */
trait UserBase
{
    protected function init()
    {
        $this->tableName = 'user';
        $this->modelName = 'User';

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
    * Get the value of Hash / hash.
    *
    * @return string
    */
    public function getHash()
    {
        $rtn = $this->data['hash'];

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
    * Get the value of IsAdmin / is_admin.
    *
    * @return int
    */
    public function getIsAdmin()
    {
        $rtn = $this->data['is_admin'];

        return $rtn;
    }

    /**
    * Get the value of IsHidden / is_hidden.
    *
    * @return int
    */
    public function getIsHidden()
    {
        $rtn = $this->data['is_hidden'];

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
    * Set the value of Hash / hash.
    *
    * Must not be null.
    * @param $value string
    */
    public function setHash($value)
    {
        $this->validateNotNull('Hash', $value);
        $this->validateString('Hash', $value);

        if ($this->data['hash'] === $value) {
            return;
        }

        $this->data['hash'] = $value;
        $this->setModified('hash');
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
    * Set the value of IsAdmin / is_admin.
    *
    * Must not be null.
    * @param $value int
    */
    public function setIsAdmin($value)
    {
        $this->validateNotNull('IsAdmin', $value);
        $this->validateInt('IsAdmin', $value);

        if ($this->data['is_admin'] === $value) {
            return;
        }

        $this->data['is_admin'] = $value;
        $this->setModified('is_admin');
    }

    /**
    * Set the value of IsHidden / is_hidden.
    *
    * Must not be null.
    * @param $value int
    */
    public function setIsHidden($value)
    {
        $this->validateNotNull('IsHidden', $value);
        $this->validateInt('IsHidden', $value);

        if ($this->data['is_hidden'] === $value) {
            return;
        }

        $this->data['is_hidden'] = $value;
        $this->setModified('is_hidden');
    }

}
