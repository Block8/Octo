<?php

/**
 * Item base model for table: item
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * Item Base Model
 */
trait ItemBase
{
    protected function init()
    {
        $this->tableName = 'item';
        $this->modelName = 'Item';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['category_id'] = null;
        $this->getters['category_id'] = 'getCategoryId';
        $this->setters['category_id'] = 'setCategoryId';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['short_description'] = null;
        $this->getters['short_description'] = 'getShortDescription';
        $this->setters['short_description'] = 'setShortDescription';
        $this->data['description'] = null;
        $this->getters['description'] = 'getDescription';
        $this->setters['description'] = 'setDescription';
        $this->data['price'] = null;
        $this->getters['price'] = 'getPrice';
        $this->setters['price'] = 'setPrice';
        $this->data['created_date'] = null;
        $this->getters['created_date'] = 'getCreatedDate';
        $this->setters['created_date'] = 'setCreatedDate';
        $this->data['updated_date'] = null;
        $this->getters['updated_date'] = 'getUpdatedDate';
        $this->setters['updated_date'] = 'setUpdatedDate';
        $this->data['active'] = null;
        $this->getters['active'] = 'getActive';
        $this->setters['active'] = 'setActive';
        $this->data['expiry_date'] = null;
        $this->getters['expiry_date'] = 'getExpiryDate';
        $this->setters['expiry_date'] = 'setExpiryDate';
        $this->data['slug'] = null;
        $this->getters['slug'] = 'getSlug';
        $this->setters['slug'] = 'setSlug';

        // Foreign keys:
        $this->getters['Category'] = 'getCategory';
        $this->setters['Category'] = 'setCategory';
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
    * Get the value of ShortDescription / short_description.
    *
    * @return string
    */
    public function getShortDescription()
    {
        $rtn = $this->data['short_description'];

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
    * Get the value of Price / price.
    *
    * @return float
    */
    public function getPrice()
    {
        $rtn = $this->data['price'];

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
    * Get the value of ExpiryDate / expiry_date.
    *
    * @return \DateTime
    */
    public function getExpiryDate()
    {
        $rtn = $this->data['expiry_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of Slug / slug.
    *
    * @return string
    */
    public function getSlug()
    {
        $rtn = $this->data['slug'];

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
    * Set the value of ShortDescription / short_description.
    *
    * @param $value string
    */
    public function setShortDescription($value)
    {
        $this->validateString('ShortDescription', $value);

        if ($this->data['short_description'] === $value) {
            return;
        }

        $this->data['short_description'] = $value;
        $this->setModified('short_description');
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
    * Set the value of Price / price.
    *
    * Must not be null.
    * @param $value float
    */
    public function setPrice($value)
    {
        $this->validateNotNull('Price', $value);
        $this->validateFloat('Price', $value);

        if ($this->data['price'] === $value) {
            return;
        }

        $this->data['price'] = $value;
        $this->setModified('price');
    }

    /**
    * Set the value of CreatedDate / created_date.
    *
    * @param $value \DateTime
    */
    public function setCreatedDate($value)
    {
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
    * @param $value \DateTime
    */
    public function setUpdatedDate($value)
    {
        $this->validateDate('UpdatedDate', $value);

        if ($this->data['updated_date'] === $value) {
            return;
        }

        $this->data['updated_date'] = $value;
        $this->setModified('updated_date');
    }

    /**
    * Set the value of Active / active.
    *
    * @param $value int
    */
    public function setActive($value)
    {
        $this->validateInt('Active', $value);

        if ($this->data['active'] === $value) {
            return;
        }

        $this->data['active'] = $value;
        $this->setModified('active');
    }

    /**
    * Set the value of ExpiryDate / expiry_date.
    *
    * @param $value \DateTime
    */
    public function setExpiryDate($value)
    {
        $this->validateDate('ExpiryDate', $value);

        if ($this->data['expiry_date'] === $value) {
            return;
        }

        $this->data['expiry_date'] = $value;
        $this->setModified('expiry_date');
    }

    /**
    * Set the value of Slug / slug.
    *
    * @param $value string
    */
    public function setSlug($value)
    {
        $this->validateString('Slug', $value);

        if ($this->data['slug'] === $value) {
            return;
        }

        $this->data['slug'] = $value;
        $this->setModified('slug');
    }

    /**
    * Get the Category model for this Item by Id.
    *
    * @uses \Octo\Shop\Store\CategoryStore::getById()
    * @uses \Octo\Shop\Model\Category
    * @return \Octo\Shop\Model\Category
    */
    public function getCategory()
    {
        $key = $this->getCategoryId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Category', 'Octo\Categories')->getById($key);
    }

    /**
    * Set Category - Accepts an ID, an array representing a Category or a Category model.
    *
    * @param $value mixed
    */
    public function setCategory($value)
    {
        // Is this an instance of Category?
        if ($value instanceof \Octo\Shop\Model\Category) {
            return $this->setCategoryObject($value);
        }

        // Is this an array representing a Category item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setCategoryId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setCategoryId($value);
    }

    /**
    * Set Category - Accepts a Category model.
    *
    * @param $value \Octo\Shop\Model\Category
    */
    public function setCategoryObject(\Octo\Shop\Model\Category $value)
    {
        return $this->setCategoryId($value->getId());
    }
}
