<?php

/**
 * LineItem base model for table: line_item
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * LineItem Base Model
 */
trait LineItemBase
{
    protected function init()
    {
        $this->tableName = 'line_item';
        $this->modelName = 'LineItem';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['invoice_id'] = null;
        $this->getters['invoice_id'] = 'getInvoiceId';
        $this->setters['invoice_id'] = 'setInvoiceId';
        $this->data['item_id'] = null;
        $this->getters['item_id'] = 'getItemId';
        $this->setters['item_id'] = 'setItemId';
        $this->data['quantity'] = null;
        $this->getters['quantity'] = 'getQuantity';
        $this->setters['quantity'] = 'setQuantity';
        $this->data['item_price'] = null;
        $this->getters['item_price'] = 'getItemPrice';
        $this->setters['item_price'] = 'setItemPrice';
        $this->data['line_price'] = null;
        $this->getters['line_price'] = 'getLinePrice';
        $this->setters['line_price'] = 'setLinePrice';

        // Foreign keys:
        $this->getters['Item'] = 'getItem';
        $this->setters['Item'] = 'setItem';
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
    * Get the value of ItemId / item_id.
    *
    * @return int
    */
    public function getItemId()
    {
        $rtn = $this->data['item_id'];

        return $rtn;
    }

    /**
    * Get the value of Quantity / quantity.
    *
    * @return int
    */
    public function getQuantity()
    {
        $rtn = $this->data['quantity'];

        return $rtn;
    }

    /**
    * Get the value of ItemPrice / item_price.
    *
    * @return float
    */
    public function getItemPrice()
    {
        $rtn = $this->data['item_price'];

        return $rtn;
    }

    /**
    * Get the value of LinePrice / line_price.
    *
    * @return float
    */
    public function getLinePrice()
    {
        $rtn = $this->data['line_price'];

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
    * Set the value of ItemId / item_id.
    *
    * @param $value int
    */
    public function setItemId($value)
    {
        $this->validateInt('ItemId', $value);

        if ($this->data['item_id'] === $value) {
            return;
        }

        $this->data['item_id'] = $value;
        $this->setModified('item_id');
    }

    /**
    * Set the value of Quantity / quantity.
    *
    * @param $value int
    */
    public function setQuantity($value)
    {
        $this->validateInt('Quantity', $value);

        if ($this->data['quantity'] === $value) {
            return;
        }

        $this->data['quantity'] = $value;
        $this->setModified('quantity');
    }

    /**
    * Set the value of ItemPrice / item_price.
    *
    * @param $value float
    */
    public function setItemPrice($value)
    {
        $this->validateFloat('ItemPrice', $value);

        if ($this->data['item_price'] === $value) {
            return;
        }

        $this->data['item_price'] = $value;
        $this->setModified('item_price');
    }

    /**
    * Set the value of LinePrice / line_price.
    *
    * @param $value float
    */
    public function setLinePrice($value)
    {
        $this->validateFloat('LinePrice', $value);

        if ($this->data['line_price'] === $value) {
            return;
        }

        $this->data['line_price'] = $value;
        $this->setModified('line_price');
    }

    /**
    * Get the Item model for this LineItem by Id.
    *
    * @uses \Octo\Shop\Store\ItemStore::getById()
    * @uses \Octo\Shop\Model\Item
    * @return \Octo\Shop\Model\Item
    */
    public function getItem()
    {
        $key = $this->getItemId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Item', 'Octo\Shop')->getById($key);
    }

    /**
    * Set Item - Accepts an ID, an array representing a Item or a Item model.
    *
    * @param $value mixed
    */
    public function setItem($value)
    {
        // Is this an instance of Item?
        if ($value instanceof \Octo\Shop\Model\Item) {
            return $this->setItemObject($value);
        }

        // Is this an array representing a Item item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setItemId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setItemId($value);
    }

    /**
    * Set Item - Accepts a Item model.
    *
    * @param $value \Octo\Shop\Model\Item
    */
    public function setItemObject(\Octo\Shop\Model\Item $value)
    {
        return $this->setItemId($value->getId());
    }
    /**
    * Get the Invoice model for this LineItem by Id.
    *
    * @uses \Octo\Shop\Store\InvoiceStore::getById()
    * @uses \Octo\Shop\Model\Invoice
    * @return \Octo\Shop\Model\Invoice
    */
    public function getInvoice()
    {
        $key = $this->getInvoiceId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Invoice', 'Octo\Shop')->getById($key);
    }

    /**
    * Set Invoice - Accepts an ID, an array representing a Invoice or a Invoice model.
    *
    * @param $value mixed
    */
    public function setInvoice($value)
    {
        // Is this an instance of Invoice?
        if ($value instanceof \Octo\Shop\Model\Invoice) {
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
    * @param $value \Octo\Shop\Model\Invoice
    */
    public function setInvoiceObject(\Octo\Shop\Model\Invoice $value)
    {
        return $this->setInvoiceId($value->getId());
    }
}
