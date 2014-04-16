<?php

/**
 * ItemVariant base model for table: item_variant
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * ItemVariant Base Model
 */
trait ItemVariantBase
{
    protected function init()
    {
        $this->tableName = 'item_variant';
        $this->modelName = 'ItemVariant';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['item_id'] = null;
        $this->getters['item_id'] = 'getItemId';
        $this->setters['item_id'] = 'setItemId';
        $this->data['variant_id'] = null;
        $this->getters['variant_id'] = 'getVariantId';
        $this->setters['variant_id'] = 'setVariantId';
        $this->data['variant_option_id'] = null;
        $this->getters['variant_option_id'] = 'getVariantOptionId';
        $this->setters['variant_option_id'] = 'setVariantOptionId';
        $this->data['price_adjustment'] = null;
        $this->getters['price_adjustment'] = 'getPriceAdjustment';
        $this->setters['price_adjustment'] = 'setPriceAdjustment';

        // Foreign keys:
        $this->getters['Item'] = 'getItem';
        $this->setters['Item'] = 'setItem';
        $this->getters['Variant'] = 'getVariant';
        $this->setters['Variant'] = 'setVariant';
        $this->getters['VariantOption'] = 'getVariantOption';
        $this->setters['VariantOption'] = 'setVariantOption';
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
    * Get the value of VariantId / variant_id.
    *
    * @return int
    */
    public function getVariantId()
    {
        $rtn = $this->data['variant_id'];

        return $rtn;
    }

    /**
    * Get the value of VariantOptionId / variant_option_id.
    *
    * @return int
    */
    public function getVariantOptionId()
    {
        $rtn = $this->data['variant_option_id'];

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
    * Set the value of VariantId / variant_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setVariantId($value)
    {
        $this->validateNotNull('VariantId', $value);
        $this->validateInt('VariantId', $value);

        if ($this->data['variant_id'] === $value) {
            return;
        }

        $this->data['variant_id'] = $value;
        $this->setModified('variant_id');
    }

    /**
    * Set the value of VariantOptionId / variant_option_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setVariantOptionId($value)
    {
        $this->validateNotNull('VariantOptionId', $value);
        $this->validateInt('VariantOptionId', $value);

        if ($this->data['variant_option_id'] === $value) {
            return;
        }

        $this->data['variant_option_id'] = $value;
        $this->setModified('variant_option_id');
    }

    /**
    * Set the value of PriceAdjustment / price_adjustment.
    *
    * @param $value float
    */
    public function setPriceAdjustment($value)
    {
        $this->validateFloat('PriceAdjustment', $value);

        if ($this->data['price_adjustment'] === $value) {
            return;
        }

        $this->data['price_adjustment'] = $value;
        $this->setModified('price_adjustment');
    }

    /**
    * Get the Item model for this ItemVariant by Id.
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
    * Get the Variant model for this ItemVariant by Id.
    *
    * @uses \Octo\Shop\Store\VariantStore::getById()
    * @uses \Octo\Shop\Model\Variant
    * @return \Octo\Shop\Model\Variant
    */
    public function getVariant()
    {
        $key = $this->getVariantId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Variant', 'Octo\Shop')->getById($key);
    }

    /**
    * Set Variant - Accepts an ID, an array representing a Variant or a Variant model.
    *
    * @param $value mixed
    */
    public function setVariant($value)
    {
        // Is this an instance of Variant?
        if ($value instanceof \Octo\Shop\Model\Variant) {
            return $this->setVariantObject($value);
        }

        // Is this an array representing a Variant item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setVariantId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setVariantId($value);
    }

    /**
    * Set Variant - Accepts a Variant model.
    *
    * @param $value \Octo\Shop\Model\Variant
    */
    public function setVariantObject(\Octo\Shop\Model\Variant $value)
    {
        return $this->setVariantId($value->getId());
    }
    /**
    * Get the VariantOption model for this ItemVariant by Id.
    *
    * @uses \Octo\Shop\Store\VariantOptionStore::getById()
    * @uses \Octo\Shop\Model\VariantOption
    * @return \Octo\Shop\Model\VariantOption
    */
    public function getVariantOption()
    {
        $key = $this->getVariantOptionId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('VariantOption', 'Octo\Shop')->getById($key);
    }

    /**
    * Set VariantOption - Accepts an ID, an array representing a VariantOption or a VariantOption model.
    *
    * @param $value mixed
    */
    public function setVariantOption($value)
    {
        // Is this an instance of VariantOption?
        if ($value instanceof \Octo\Shop\Model\VariantOption) {
            return $this->setVariantOptionObject($value);
        }

        // Is this an array representing a VariantOption item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setVariantOptionId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setVariantOptionId($value);
    }

    /**
    * Set VariantOption - Accepts a VariantOption model.
    *
    * @param $value \Octo\Shop\Model\VariantOption
    */
    public function setVariantOptionObject(\Octo\Shop\Model\VariantOption $value)
    {
        return $this->setVariantOptionId($value->getId());
    }
}
