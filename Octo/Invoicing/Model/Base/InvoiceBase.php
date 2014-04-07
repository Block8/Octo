<?php

/**
 * Invoice base model for table: invoice
 */

namespace Octo\Invoicing\Model\Base;

use b8\Store\Factory;

/**
 * Invoice Base Model
 */
trait InvoiceBase
{
    protected function init()
    {
        $this->tableName = 'invoice';
        $this->modelName = 'Invoice';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['reference'] = null;
        $this->getters['reference'] = 'getReference';
        $this->setters['reference'] = 'setReference';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['subtotal'] = null;
        $this->getters['subtotal'] = 'getSubtotal';
        $this->setters['subtotal'] = 'setSubtotal';
        $this->data['total'] = null;
        $this->getters['total'] = 'getTotal';
        $this->setters['total'] = 'setTotal';
        $this->data['total_paid'] = null;
        $this->getters['total_paid'] = 'getTotalPaid';
        $this->setters['total_paid'] = 'setTotalPaid';
        $this->data['invoice_status_id'] = null;
        $this->getters['invoice_status_id'] = 'getInvoiceStatusId';
        $this->setters['invoice_status_id'] = 'setInvoiceStatusId';

        // Foreign keys:
        $this->getters['InvoiceStatus'] = 'getInvoiceStatus';
        $this->setters['InvoiceStatus'] = 'setInvoiceStatus';
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
    * Get the value of Reference / reference.
    *
    * @return string
    */
    public function getReference()
    {
        $rtn = $this->data['reference'];

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
    * Get the value of Subtotal / subtotal.
    *
    * @return float
    */
    public function getSubtotal()
    {
        $rtn = $this->data['subtotal'];

        return $rtn;
    }

    /**
    * Get the value of Total / total.
    *
    * @return float
    */
    public function getTotal()
    {
        $rtn = $this->data['total'];

        return $rtn;
    }

    /**
    * Get the value of TotalPaid / total_paid.
    *
    * @return float
    */
    public function getTotalPaid()
    {
        $rtn = $this->data['total_paid'];

        return $rtn;
    }

    /**
    * Get the value of InvoiceStatusId / invoice_status_id.
    *
    * @return int
    */
    public function getInvoiceStatusId()
    {
        $rtn = $this->data['invoice_status_id'];

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
    * Set the value of Reference / reference.
    *
    * @param $value string
    */
    public function setReference($value)
    {
        $this->validateString('Reference', $value);

        if ($this->data['reference'] === $value) {
            return;
        }

        $this->data['reference'] = $value;
        $this->setModified('reference');
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
    * Set the value of Subtotal / subtotal.
    *
    * Must not be null.
    * @param $value float
    */
    public function setSubtotal($value)
    {
        $this->validateNotNull('Subtotal', $value);
        $this->validateFloat('Subtotal', $value);

        if ($this->data['subtotal'] === $value) {
            return;
        }

        $this->data['subtotal'] = $value;
        $this->setModified('subtotal');
    }

    /**
    * Set the value of Total / total.
    *
    * Must not be null.
    * @param $value float
    */
    public function setTotal($value)
    {
        $this->validateNotNull('Total', $value);
        $this->validateFloat('Total', $value);

        if ($this->data['total'] === $value) {
            return;
        }

        $this->data['total'] = $value;
        $this->setModified('total');
    }

    /**
    * Set the value of TotalPaid / total_paid.
    *
    * @param $value float
    */
    public function setTotalPaid($value)
    {
        $this->validateFloat('TotalPaid', $value);

        if ($this->data['total_paid'] === $value) {
            return;
        }

        $this->data['total_paid'] = $value;
        $this->setModified('total_paid');
    }

    /**
    * Set the value of InvoiceStatusId / invoice_status_id.
    *
    * @param $value int
    */
    public function setInvoiceStatusId($value)
    {
        $this->validateInt('InvoiceStatusId', $value);

        if ($this->data['invoice_status_id'] === $value) {
            return;
        }

        $this->data['invoice_status_id'] = $value;
        $this->setModified('invoice_status_id');
    }

    /**
    * Get the InvoiceStatus model for this Invoice by Id.
    *
    * @uses \Octo\Invoicing\Store\InvoiceStatusStore::getById()
    * @uses \Octo\Invoicing\Model\InvoiceStatus
    * @return \Octo\Invoicing\Model\InvoiceStatus
    */
    public function getInvoiceStatus()
    {
        $key = $this->getInvoiceStatusId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('InvoiceStatus', 'Octo\Invoicing')->getById($key);
    }

    /**
    * Set InvoiceStatus - Accepts an ID, an array representing a InvoiceStatus or a InvoiceStatus model.
    *
    * @param $value mixed
    */
    public function setInvoiceStatus($value)
    {
        // Is this an instance of InvoiceStatus?
        if ($value instanceof \Octo\Invoicing\Model\InvoiceStatus) {
            return $this->setInvoiceStatusObject($value);
        }

        // Is this an array representing a InvoiceStatus item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setInvoiceStatusId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setInvoiceStatusId($value);
    }

    /**
    * Set InvoiceStatus - Accepts a InvoiceStatus model.
    *
    * @param $value \Octo\Invoicing\Model\InvoiceStatus
    */
    public function setInvoiceStatusObject(\Octo\Invoicing\Model\InvoiceStatus $value)
    {
        return $this->setInvoiceStatusId($value->getId());
    }
}
