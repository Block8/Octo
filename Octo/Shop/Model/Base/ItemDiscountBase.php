<?php

/**
 * ItemDiscount base model for table: item_discount
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * ItemDiscount Base Model
 */
trait ItemDiscountBase
{
    protected function init()
    {
        $this->tableName = 'item_discount';
        $this->modelName = 'ItemDiscount';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['item_id'] = null;
        $this->getters['item_id'] = 'getItemId';
        $this->setters['item_id'] = 'setItemId';
        $this->data['category_id'] = null;
        $this->getters['category_id'] = 'getCategoryId';
        $this->setters['category_id'] = 'setCategoryId';
        $this->data['discount_id'] = null;
        $this->getters['discount_id'] = 'getDiscountId';
        $this->setters['discount_id'] = 'setDiscountId';
        $this->data['discount_option_id'] = null;
        $this->getters['discount_option_id'] = 'getDiscountOptionId';
        $this->setters['discount_option_id'] = 'setDiscountOptionId';
        $this->data['price_adjustment'] = null;
        $this->getters['price_adjustment'] = 'getPriceAdjustment';
        $this->setters['price_adjustment'] = 'setPriceAdjustment';

        // Foreign keys:
        $this->getters['Item'] = 'getItem';
        $this->setters['Item'] = 'setItem';
        $this->getters['Discount'] = 'getDiscount';
        $this->setters['Discount'] = 'setDiscount';
        $this->getters['DiscountOption'] = 'getDiscountOption';
        $this->setters['DiscountOption'] = 'setDiscountOption';
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
    * Get the value of CategoryId / category_id.
    *
    * @return int
    */
    public function getCategoryId()
    {
        $rtn = $this->data['category_id'];

        return $rtn;
    }

    /**
    * Get the value of DiscountId / discount_id.
    *
    * @return int
    */
    public function getDiscountId()
    {
        $rtn = $this->data['discount_id'];

        return $rtn;
    }

    /**
    * Get the value of DiscountOptionId / discount_option_id.
    *
    * @return int
    */
    public function getDiscountOptionId()
    {
        $rtn = $this->data['discount_option_id'];

        return $rtn;
    }

    /**
    * Get the value of PriceAdjustment / price_adjustment.
    *
    * @return float
    */
    public function getPriceAdjustment()
    {
        $rtn = $this->data['price_adjustment'];

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
    * Set the value of CategoryId / category_id.
    *
    * @param $value int
    */
    public function setCategoryId($value)
    {
        $this->validateInt('CategoryId', $value);

        if ($this->data['category_id'] === $value) {
            return;
        }

        $this->data['category_id'] = $value;
        $this->setModified('category_id');
    }

    /**
    * Set the value of DiscountId / discount_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setDiscountId($value)
    {
        $this->validateNotNull('DiscountId', $value);
        $this->validateInt('DiscountId', $value);

        if ($this->data['discount_id'] === $value) {
            return;
        }

        $this->data['discount_id'] = $value;
        $this->setModified('discount_id');
    }

    /**
    * Set the value of DiscountOptionId / discount_option_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setDiscountOptionId($value)
    {
        $this->validateNotNull('DiscountOptionId', $value);
        $this->validateInt('DiscountOptionId', $value);

        if ($this->data['discount_option_id'] === $value) {
            return;
        }

        $this->data['discount_option_id'] = $value;
        $this->setModified('discount_option_id');
    }

    /**
    * Set the value of PriceAdjustment / price_adjustment.
    *
    * Must not be null.
    * @param $value float
    */
    public function setPriceAdjustment($value)
    {
        $this->validateNotNull('PriceAdjustment', $value);
        $this->validateFloat('PriceAdjustment', $value);

        if ($this->data['price_adjustment'] === $value) {
            return;
        }

        $this->data['price_adjustment'] = $value;
        $this->setModified('price_adjustment');
    }
    /**
    * Get the Item model for this ItemDiscount by Id.
    *
    * @uses \Octo\Invoicing\Store\ItemStore::getById()
    * @uses \Octo\Invoicing\Model\Item
    * @return \Octo\Invoicing\Model\Item
    */
    public function getItem()
    {
        $key = $this->getItemId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Item', 'Octo\Invoicing')->getById($key);
    }

    /**
    * Set Item - Accepts an ID, an array representing a Item or a Item model.
    *
    * @param $value mixed
    */
    public function setItem($value)
    {
        // Is this an instance of Item?
        if ($value instanceof \Octo\Invoicing\Model\Item) {
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
    * @param $value \Octo\Invoicing\Model\Item
    */
    public function setItemObject(\Octo\Invoicing\Model\Item $value)
    {
        return $this->setItemId($value->getId());
    }
    /**
    * Get the Discount model for this ItemDiscount by Id.
    *
    * @uses \Octo\Shop\Store\DiscountStore::getById()
    * @uses \Octo\Shop\Model\Discount
    * @return \Octo\Shop\Model\Discount
    */
    public function getDiscount()
    {
        $key = $this->getDiscountId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Discount', 'Octo\Shop')->getById($key);
    }

    /**
    * Set Discount - Accepts an ID, an array representing a Discount or a Discount model.
    *
    * @param $value mixed
    */
    public function setDiscount($value)
    {
        // Is this an instance of Discount?
        if ($value instanceof \Octo\Shop\Model\Discount) {
            return $this->setDiscountObject($value);
        }

        // Is this an array representing a Discount item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setDiscountId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setDiscountId($value);
    }

    /**
    * Set Discount - Accepts a Discount model.
    *
    * @param $value \Octo\Shop\Model\Discount
    */
    public function setDiscountObject(\Octo\Shop\Model\Discount $value)
    {
        return $this->setDiscountId($value->getId());
    }
    /**
    * Get the DiscountOption model for this ItemDiscount by Id.
    *
    * @uses \Octo\Shop\Store\DiscountOptionStore::getById()
    * @uses \Octo\Shop\Model\DiscountOption
    * @return \Octo\Shop\Model\DiscountOption
    */
    public function getDiscountOption()
    {
        $key = $this->getDiscountOptionId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('DiscountOption', 'Octo\Shop')->getById($key);
    }

    /**
    * Set DiscountOption - Accepts an ID, an array representing a DiscountOption or a DiscountOption model.
    *
    * @param $value mixed
    */
    public function setDiscountOption($value)
    {
        // Is this an instance of DiscountOption?
        if ($value instanceof \Octo\Shop\Model\DiscountOption) {
            return $this->setDiscountOptionObject($value);
        }

        // Is this an array representing a DiscountOption item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setDiscountOptionId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setDiscountOptionId($value);
    }

    /**
    * Set DiscountOption - Accepts a DiscountOption model.
    *
    * @param $value \Octo\Shop\Model\DiscountOption
    */
    public function setDiscountOptionObject(\Octo\Shop\Model\DiscountOption $value)
    {
        return $this->setDiscountOptionId($value->getId());
    }
}
