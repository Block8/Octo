<?php

/**
 * Discount base model for table: discount
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * Discount Base Model
 */
trait DiscountBase
{
    protected function init()
    {
        $this->tableName = 'discount';
        $this->modelName = 'Discount';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['description'] = null;
        $this->getters['description'] = 'getDescription';
        $this->setters['description'] = 'setDescription';
        $this->data['active'] = null;
        $this->getters['active'] = 'getActive';
        $this->setters['active'] = 'setActive';
        $this->data['item_single_title'] = null;
        $this->getters['item_single_title'] = 'getItemSingleTitle';
        $this->setters['item_single_title'] = 'setItemSingleTitle';
        $this->data['item_plural_title'] = null;
        $this->getters['item_plural_title'] = 'getItemPluralTitle';
        $this->setters['item_plural_title'] = 'setItemPluralTitle';

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
    * Get the value of Description / description.
    *
    * @return string
    */
    public function getDescription()
    {
        $rtn = $this->data['description'];

        return $rtn;
    }

    /**
    * Get the value of Active / active.
    *
    * @return int
    */
    public function getActive()
    {
        $rtn = $this->data['active'];

        return $rtn;
    }

    /**
    * Get the value of ItemSingleTitle / item_single_title.
    *
    * @return string
    */
    public function getItemSingleTitle()
    {
        $rtn = $this->data['item_single_title'];

        return $rtn;
    }

    /**
    * Get the value of ItemPluralTitle / item_plural_title.
    *
    * @return string
    */
    public function getItemPluralTitle()
    {
        $rtn = $this->data['item_plural_title'];

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
    * Set the value of Title / title.
    *
    * Must not be null.
    * @param $value string
    */
    public function setTitle($value)
    {
        $this->validateNotNull('Title', $value);
        $this->validateString('Title', $value);

        if ($this->data['title'] === $value) {
            return;
        }

        $this->data['title'] = $value;
        $this->setModified('title');
    }

    /**
    * Set the value of Description / description.
    *
    * @param $value string
    */
    public function setDescription($value)
    {
        $this->validateString('Description', $value);

        if ($this->data['description'] === $value) {
            return;
        }

        $this->data['description'] = $value;
        $this->setModified('description');
    }

    /**
    * Set the value of Active / active.
    *
    * Must not be null.
    * @param $value int
    */
    public function setActive($value)
    {
        $this->validateNotNull('Active', $value);
        $this->validateInt('Active', $value);

        if ($this->data['active'] === $value) {
            return;
        }

        $this->data['active'] = $value;
        $this->setModified('active');
    }

    /**
    * Set the value of ItemSingleTitle / item_single_title.
    *
    * @param $value string
    */
    public function setItemSingleTitle($value)
    {
        $this->validateString('ItemSingleTitle', $value);

        if ($this->data['item_single_title'] === $value) {
            return;
        }

        $this->data['item_single_title'] = $value;
        $this->setModified('item_single_title');
    }

    /**
    * Set the value of ItemPluralTitle / item_plural_title.
    *
    * @param $value string
    */
    public function setItemPluralTitle($value)
    {
        $this->validateString('ItemPluralTitle', $value);

        if ($this->data['item_plural_title'] === $value) {
            return;
        }

        $this->data['item_plural_title'] = $value;
        $this->setModified('item_plural_title');
    }
}
