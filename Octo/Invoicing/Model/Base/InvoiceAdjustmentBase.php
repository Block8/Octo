<?php

/**
 * InvoiceAdjustment base model for table: invoice_adjustment
 */

namespace Octo\Invoicing\Model\Base;

use b8\Store\Factory;

/**
 * InvoiceAdjustment Base Model
 */
trait InvoiceAdjustmentBase
{
    protected function init()
    {
        $this->tableName = 'invoice_adjustment';
        $this->modelName = 'InvoiceAdjustment';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['invoice_id'] = null;
        $this->getters['invoice_id'] = 'getInvoiceId';
        $this->setters['invoice_id'] = 'setInvoiceId';
        $this->data['scope'] = null;
        $this->getters['scope'] = 'getScope';
        $this->setters['scope'] = 'setScope';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['value'] = null;
        $this->getters['value'] = 'getValue';
        $this->setters['value'] = 'setValue';
        $this->data['data'] = null;
        $this->getters['data'] = 'getData';
        $this->setters['data'] = 'setData';

        // Foreign keys:
        $this->getters['Invoice'] = 'getInvoice';
        $this->setters['Invoice'] = 'setInvoice';
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
    * Get the value of InvoiceId / invoice_id.
    *
    * @return int
    */
    public function getInvoiceId()
    {
        $rtn = $this->data['invoice_id'];

        return $rtn;
    }

    /**
    * Get the value of Scope / scope.
    *
    * @return string
    */
    public function getScope()
    {
        $rtn = $this->data['scope'];

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
    * Get the value of Value / value.
    *
    * @return float
    */
    public function getValue()
    {
        $rtn = $this->data['value'];

        return $rtn;
    }

    /**
    * Get the value of Data / data.
    *
    * @return string
    */
    public function getData()
    {
        $rtn = $this->data['data'];

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
    * Set the value of InvoiceId / invoice_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setInvoiceId($value)
    {
        $this->validateNotNull('InvoiceId', $value);
        $this->validateInt('InvoiceId', $value);

        if ($this->data['invoice_id'] === $value) {
            return;
        }

        $this->data['invoice_id'] = $value;
        $this->setModified('invoice_id');
    }

    /**
    * Set the value of Scope / scope.
    *
    * @param $value string
    */
    public function setScope($value)
    {
        $this->validateString('Scope', $value);

        if ($this->data['scope'] === $value) {
            return;
        }

        $this->data['scope'] = $value;
        $this->setModified('scope');
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
    * Set the value of Value / value.
    *
    * @param $value float
    */
    public function setValue($value)
    {
        $this->validateFloat('Value', $value);

        if ($this->data['value'] === $value) {
            return;
        }

        $this->data['value'] = $value;
        $this->setModified('value');
    }

    /**
    * Set the value of Data / data.
    *
    * @param $value string
    */
    public function setData($value)
    {
        $this->validateString('Data', $value);

        if ($this->data['data'] === $value) {
            return;
        }

        $this->data['data'] = $value;
        $this->setModified('data');
    }
    /**
    * Get the Invoice model for this InvoiceAdjustment by Id.
    *
    * @uses \Octo\Invoicing\Store\InvoiceStore::getById()
    * @uses \Octo\Invoicing\Model\Invoice
    * @return \Octo\Invoicing\Model\Invoice
    */
    public function getInvoice()
    {
        $key = $this->getInvoiceId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Invoice', 'Octo\Invoicing')->getById($key);
    }

    /**
    * Set Invoice - Accepts an ID, an array representing a Invoice or a Invoice model.
    *
    * @param $value mixed
    */
    public function setInvoice($value)
    {
        // Is this an instance of Invoice?
        if ($value instanceof \Octo\Invoicing\Model\Invoice) {
            return $this->setInvoiceObject($value);
        }

        // Is this an array representing a Invoice item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setInvoiceId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setInvoiceId($value);
    }

    /**
    * Set Invoice - Accepts a Invoice model.
    *
    * @param $value \Octo\Invoicing\Model\Invoice
    */
    public function setInvoiceObject(\Octo\Invoicing\Model\Invoice $value)
    {
        return $this->setInvoiceId($value->getId());
    }
}
