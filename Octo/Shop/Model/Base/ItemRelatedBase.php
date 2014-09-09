<?php

/**
 * ItemRelated base model for table: item_related
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * ItemRelated Base Model
 */
trait ItemRelatedBase
{
    protected function init()
    {
        $this->tableName = 'item_related';
        $this->modelName = 'ItemRelated';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['item_id'] = null;
        $this->getters['item_id'] = 'getItemId';
        $this->setters['item_id'] = 'setItemId';
        $this->data['related_item_id'] = null;
        $this->getters['related_item_id'] = 'getRelatedItemId';
        $this->setters['related_item_id'] = 'setRelatedItemId';

        // Foreign keys:
        $this->getters['Item'] = 'getItem';
        $this->setters['Item'] = 'setItem';
        $this->getters['RelatedItem'] = 'getRelatedItem';
        $this->setters['RelatedItem'] = 'setRelatedItem';
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
    * Get the value of RelatedItemId / related_item_id.
    *
    * @return int
    */
    public function getRelatedItemId()
    {
        $rtn = $this->data['related_item_id'];

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
    * Must not be null.
    * @param $value int
    */
    public function setItemId($value)
    {
        $this->validateNotNull('ItemId', $value);
        $this->validateInt('ItemId', $value);

        if ($this->data['item_id'] === $value) {
            return;
        }

        $this->data['item_id'] = $value;
        $this->setModified('item_id');
    }

    /**
    * Set the value of RelatedItemId / related_item_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setRelatedItemId($value)
    {
        $this->validateNotNull('RelatedItemId', $value);
        $this->validateInt('RelatedItemId', $value);

        if ($this->data['related_item_id'] === $value) {
            return;
        }

        $this->data['related_item_id'] = $value;
        $this->setModified('related_item_id');
    }
    /**
    * Get the Item model for this ItemRelated by Id.
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
    * Get the Item model for this ItemRelated by Id.
    *
    * @uses \Octo\Invoicing\Store\ItemStore::getById()
    * @uses \Octo\Invoicing\Model\Item
    * @return \Octo\Invoicing\Model\Item
    */
    public function getRelatedItem()
    {
        $key = $this->getRelatedItemId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Item', 'Octo\Invoicing')->getById($key);
    }

    /**
    * Set RelatedItem - Accepts an ID, an array representing a Item or a Item model.
    *
    * @param $value mixed
    */
    public function setRelatedItem($value)
    {
        // Is this an instance of Item?
        if ($value instanceof \Octo\Invoicing\Model\Item) {
            return $this->setRelatedItemObject($value);
        }

        // Is this an array representing a Item item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setRelatedItemId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setRelatedItemId($value);
    }

    /**
    * Set RelatedItem - Accepts a Item model.
    *
    * @param $value \Octo\Invoicing\Model\Item
    */
    public function setRelatedItemObject(\Octo\Invoicing\Model\Item $value)
    {
        return $this->setRelatedItemId($value->getId());
    }
}
