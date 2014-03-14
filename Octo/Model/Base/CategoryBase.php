<?php

/**
 * Category base model for table: category
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * Category Base Model
 */
trait CategoryBase
{
    protected function init()
    {
        $this->tableName = 'category';
        $this->modelName = 'Category';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['name'] = null;
        $this->getters['name'] = 'getName';
        $this->setters['name'] = 'setName';
        $this->data['slug'] = null;
        $this->getters['slug'] = 'getSlug';
        $this->setters['slug'] = 'setSlug';
        $this->data['scope'] = null;
        $this->getters['scope'] = 'getScope';
        $this->setters['scope'] = 'setScope';
        $this->data['parent_id'] = null;
        $this->getters['parent_id'] = 'getParentId';
        $this->setters['parent_id'] = 'setParentId';

        // Foreign keys:
        $this->getters['Parent'] = 'getParent';
        $this->setters['Parent'] = 'setParent';
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
    * Get the value of Name / name.
    *
    * @return string
    */
    public function getName()
    {
        $rtn = $this->data['name'];

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
    * Get the value of ParentId / parent_id.
    *
    * @return int
    */
    public function getParentId()
    {
        $rtn = $this->data['parent_id'];

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
    * Set the value of Name / name.
    *
    * @param $value string
    */
    public function setName($value)
    {
        $this->validateString('Name', $value);

        if ($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;
        $this->setModified('name');
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
    * Set the value of ParentId / parent_id.
    *
    * @param $value int
    */
    public function setParentId($value)
    {
        $this->validateInt('ParentId', $value);

        if ($this->data['parent_id'] === $value) {
            return;
        }

        $this->data['parent_id'] = $value;
        $this->setModified('parent_id');
    }

    /**
    * Get the Category model for this Category by Id.
    *
    * @uses \Octo\Store\CategoryStore::getById()
    * @uses \Octo\Model\Category
    * @return \Octo\Model\Category
    */
    public function getParent()
    {
        $key = $this->getParentId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Category', 'Octo')->getById($key);
    }

    /**
    * Set Parent - Accepts an ID, an array representing a Category or a Category model.
    *
    * @param $value mixed
    */
    public function setParent($value)
    {
        // Is this an instance of Category?
        if ($value instanceof \Octo\Model\Category) {
            return $this->setParentObject($value);
        }

        // Is this an array representing a Category item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setParentId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setParentId($value);
    }

    /**
    * Set Parent - Accepts a Category model.
    *
    * @param $value \Octo\Model\Category
    */
    public function setParentObject(\Octo\Model\Category $value)
    {
        return $this->setParentId($value->getId());
    }
}
