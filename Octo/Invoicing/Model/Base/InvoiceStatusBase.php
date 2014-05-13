<?php

/**
 * InvoiceStatus base model for table: invoice_status
 */

namespace Octo\Invoicing\Model\Base;

use b8\Store\Factory;

/**
 * InvoiceStatus Base Model
 */
trait InvoiceStatusBase
{
    protected function init()
    {
        $this->tableName = 'invoice_status';
        $this->modelName = 'InvoiceStatus';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['status'] = null;
        $this->getters['status'] = 'getStatus';
        $this->setters['status'] = 'setStatus';
        $this->data['code'] = null;
        $this->getters['code'] = 'getCode';
        $this->setters['code'] = 'setCode';
        $this->data['protected'] = null;
        $this->getters['protected'] = 'getProtected';
        $this->setters['protected'] = 'setProtected';

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
    * Get the value of Status / status.
    *
    * @return string
    */
    public function getStatus()
    {
        $rtn = $this->data['status'];

        return $rtn;
    }

    /**
    * Get the value of Code / code.
    *
    * @return string
    */
    public function getCode()
    {
        $rtn = $this->data['code'];

        return $rtn;
    }

    /**
    * Get the value of Protected / protected.
    *
    * @return int
    */
    public function getProtected()
    {
        $rtn = $this->data['protected'];

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
    * Set the value of Status / status.
    *
    * Must not be null.
    * @param $value string
    */
    public function setStatus($value)
    {
        $this->validateNotNull('Status', $value);
        $this->validateString('Status', $value);

        if ($this->data['status'] === $value) {
            return;
        }

        $this->data['status'] = $value;
        $this->setModified('status');
    }

    /**
    * Set the value of Code / code.
    *
    * Must not be null.
    * @param $value string
    */
    public function setCode($value)
    {
        $this->validateNotNull('Code', $value);
        $this->validateString('Code', $value);

        if ($this->data['code'] === $value) {
            return;
        }

        $this->data['code'] = $value;
        $this->setModified('code');
    }

    /**
    * Set the value of Protected / protected.
    *
    * Must not be null.
    * @param $value int
    */
    public function setProtected($value)
    {
        $this->validateNotNull('Protected', $value);
        $this->validateInt('Protected', $value);

        if ($this->data['protected'] === $value) {
            return;
        }

        $this->data['protected'] = $value;
        $this->setModified('protected');
    }
}
