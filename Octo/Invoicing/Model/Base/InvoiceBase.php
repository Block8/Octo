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
        $this->data['uuid'] = null;
        $this->getters['uuid'] = 'getUuid';
        $this->setters['uuid'] = 'setUuid';
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
        $this->data['contact_id'] = null;
        $this->getters['contact_id'] = 'getContactId';
        $this->setters['contact_id'] = 'setContactId';
        $this->data['created_date'] = null;
        $this->getters['created_date'] = 'getCreatedDate';
        $this->setters['created_date'] = 'setCreatedDate';
        $this->data['updated_date'] = null;
        $this->getters['updated_date'] = 'getUpdatedDate';
        $this->setters['updated_date'] = 'setUpdatedDate';
        $this->data['due_date'] = null;
        $this->getters['due_date'] = 'getDueDate';
        $this->setters['due_date'] = 'setDueDate';
        $this->data['billing_address'] = null;
        $this->getters['billing_address'] = 'getBillingAddress';
        $this->setters['billing_address'] = 'setBillingAddress';
        $this->data['shipping_address'] = null;
        $this->getters['shipping_address'] = 'getShippingAddress';
        $this->setters['shipping_address'] = 'setShippingAddress';
        $this->data['shipping_cost'] = null;
        $this->getters['shipping_cost'] = 'getShippingCost';
        $this->setters['shipping_cost'] = 'setShippingCost';
        $this->data['country_code'] = null;
        $this->getters['country_code'] = 'getCountryCode';
        $this->setters['country_code'] = 'setCountryCode';

        // Foreign keys:
        $this->getters['InvoiceStatus'] = 'getInvoiceStatus';
        $this->setters['InvoiceStatus'] = 'setInvoiceStatus';
        $this->getters['Contact'] = 'getContact';
        $this->setters['Contact'] = 'setContact';
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
    * Get the value of Uuid / uuid.
    *
    * @return string
    */
    public function getUuid()
    {
        $rtn = $this->data['uuid'];

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
    * Get the value of ContactId / contact_id.
    *
    * @return int
    */
    public function getContactId()
    {
        $rtn = $this->data['contact_id'];

        return $rtn;
    }

    /**
    * Get the value of CreatedDate / created_date.
    *
    * @return \DateTime
    */
    public function getCreatedDate()
    {
        $rtn = $this->data['created_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of UpdatedDate / updated_date.
    *
    * @return \DateTime
    */
    public function getUpdatedDate()
    {
        $rtn = $this->data['updated_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of DueDate / due_date.
    *
    * @return \DateTime
    */
    public function getDueDate()
    {
        $rtn = $this->data['due_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of BillingAddress / billing_address.
    *
    * @return string
    */
    public function getBillingAddress()
    {
        $rtn = $this->data['billing_address'];

        return $rtn;
    }

    /**
    * Get the value of ShippingAddress / shipping_address.
    *
    * @return string
    */
    public function getShippingAddress()
    {
        $rtn = $this->data['shipping_address'];

        return $rtn;
    }

    /**
    * Get the value of ShippingCost / shipping_cost.
    *
    * @return float
    */
    public function getShippingCost()
    {
        $rtn = $this->data['shipping_cost'];

        return $rtn;
    }

    /**
    * Get the value of CountryCode / country_code.
    *
    * @return string
    */
    public function getCountryCode()
    {
        $rtn = $this->data['country_code'];

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
    * Set the value of Uuid / uuid.
    *
    * Must not be null.
    * @param $value string
    */
    public function setUuid($value)
    {
        $this->validateNotNull('Uuid', $value);
        $this->validateString('Uuid', $value);

        if ($this->data['uuid'] === $value) {
            return;
        }

        $this->data['uuid'] = $value;
        $this->setModified('uuid');
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
    * Set the value of ContactId / contact_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setContactId($value)
    {
        $this->validateNotNull('ContactId', $value);
        $this->validateInt('ContactId', $value);

        if ($this->data['contact_id'] === $value) {
            return;
        }

        $this->data['contact_id'] = $value;
        $this->setModified('contact_id');
    }

    /**
    * Set the value of CreatedDate / created_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setCreatedDate($value)
    {
        $this->validateNotNull('CreatedDate', $value);
        $this->validateDate('CreatedDate', $value);

        if ($this->data['created_date'] === $value) {
            return;
        }

        $this->data['created_date'] = $value;
        $this->setModified('created_date');
    }

    /**
    * Set the value of UpdatedDate / updated_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setUpdatedDate($value)
    {
        $this->validateNotNull('UpdatedDate', $value);
        $this->validateDate('UpdatedDate', $value);

        if ($this->data['updated_date'] === $value) {
            return;
        }

        $this->data['updated_date'] = $value;
        $this->setModified('updated_date');
    }

    /**
    * Set the value of DueDate / due_date.
    *
    * @param $value \DateTime
    */
    public function setDueDate($value)
    {
        $this->validateDate('DueDate', $value);

        if ($this->data['due_date'] === $value) {
            return;
        }

        $this->data['due_date'] = $value;
        $this->setModified('due_date');
    }

    /**
    * Set the value of BillingAddress / billing_address.
    *
    * @param $value string
    */
    public function setBillingAddress($value)
    {
        $this->validateString('BillingAddress', $value);

        if ($this->data['billing_address'] === $value) {
            return;
        }

        $this->data['billing_address'] = $value;
        $this->setModified('billing_address');
    }

    /**
    * Set the value of ShippingAddress / shipping_address.
    *
    * @param $value string
    */
    public function setShippingAddress($value)
    {
        $this->validateString('ShippingAddress', $value);

        if ($this->data['shipping_address'] === $value) {
            return;
        }

        $this->data['shipping_address'] = $value;
        $this->setModified('shipping_address');
    }

    /**
    * Set the value of ShippingCost / shipping_cost.
    *
    * Must not be null.
    * @param $value float
    */
    public function setShippingCost($value)
    {
        $this->validateNotNull('ShippingCost', $value);
        $this->validateFloat('ShippingCost', $value);

        if ($this->data['shipping_cost'] === $value) {
            return;
        }

        $this->data['shipping_cost'] = $value;
        $this->setModified('shipping_cost');
    }

    /**
    * Set the value of CountryCode / country_code.
    *
    * Must not be null.
    * @param $value string
    */
    public function setCountryCode($value)
    {
        $this->validateNotNull('CountryCode', $value);
        $this->validateString('CountryCode', $value);

        if ($this->data['country_code'] === $value) {
            return;
        }

        $this->data['country_code'] = $value;
        $this->setModified('country_code');
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
    /**
    * Get the Contact model for this Invoice by Id.
    *
    * @uses \Octo\System\Store\ContactStore::getById()
    * @uses \Octo\System\Model\Contact
    * @return \Octo\System\Model\Contact
    */
    public function getContact()
    {
        $key = $this->getContactId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Contact', 'Octo\System')->getById($key);
    }

    /**
    * Set Contact - Accepts an ID, an array representing a Contact or a Contact model.
    *
    * @param $value mixed
    */
    public function setContact($value)
    {
        // Is this an instance of Contact?
        if ($value instanceof \Octo\System\Model\Contact) {
            return $this->setContactObject($value);
        }

        // Is this an array representing a Contact item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setContactId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setContactId($value);
    }

    /**
    * Set Contact - Accepts a Contact model.
    *
    * @param $value \Octo\System\Model\Contact
    */
    public function setContactObject(\Octo\System\Model\Contact $value)
    {
        return $this->setContactId($value->getId());
    }
}
