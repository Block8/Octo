<?php

/**
 * Category base model for table: category
 */

namespace Octo\Categories\Model\Base;

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
        $this->data['image_id'] = null;
        $this->getters['image_id'] = 'getImageId';
        $this->setters['image_id'] = 'setImageId';
        $this->data['description'] = null;
        $this->getters['description'] = 'getDescription';
        $this->setters['description'] = 'setDescription';
        $this->data['position'] = null;
        $this->getters['position'] = 'getPosition';
        $this->setters['position'] = 'setPosition';

        // Foreign keys:
        $this->getters['Parent'] = 'getParent';
        $this->setters['Parent'] = 'setParent';
        $this->getters['Image'] = 'getImage';
        $this->setters['Image'] = 'setImage';
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
    * Get the value of ImageId / image_id.
    *
    * @return string
    */
    public function getImageId()
    {
        $rtn = $this->data['image_id'];

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
    * Get the value of Position / position.
    *
    * @return int
    */
    public function getPosition()
    {
        $rtn = $this->data['position'];

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
    * Set the value of ImageId / image_id.
    *
    * @param $value string
    */
    public function setImageId($value)
    {
        $this->validateString('ImageId', $value);

        if ($this->data['image_id'] === $value) {
            return;
        }

        $this->data['image_id'] = $value;
        $this->setModified('image_id');
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
    * Set the value of Position / position.
    *
    * @param $value int
    */
    public function setPosition($value)
    {
        $this->validateInt('Position', $value);

        if ($this->data['position'] === $value) {
            return;
        }

        $this->data['position'] = $value;
        $this->setModified('position');
    }
    /**
    * Get the Category model for this Category by Id.
    *
    * @uses \Octo\Categories\Store\CategoryStore::getById()
    * @uses \Octo\Categories\Model\Category
    * @return \Octo\Categories\Model\Category
    */
    public function getParent()
    {
        $key = $this->getParentId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Category', 'Octo\Categories')->getById($key);
    }

    /**
    * Set Parent - Accepts an ID, an array representing a Category or a Category model.
    *
    * @param $value mixed
    */
    public function setParent($value)
    {
        // Is this an instance of Category?
        if ($value instanceof \Octo\Categories\Model\Category) {
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
    * @param $value \Octo\Categories\Model\Category
    */
    public function setParentObject(\Octo\Categories\Model\Category $value)
    {
        return $this->setParentId($value->getId());
    }
    /**
    * Get the File model for this Category by Id.
    *
    * @uses \Octo\System\Store\FileStore::getById()
    * @uses \Octo\System\Model\File
    * @return \Octo\System\Model\File
    */
    public function getImage()
    {
        $key = $this->getImageId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('File', 'Octo\System')->getById($key);
    }

    /**
    * Set Image - Accepts an ID, an array representing a File or a File model.
    *
    * @param $value mixed
    */
    public function setImage($value)
    {
        // Is this an instance of File?
        if ($value instanceof \Octo\System\Model\File) {
            return $this->setImageObject($value);
        }

        // Is this an array representing a File item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setImageId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setImageId($value);
    }

    /**
    * Set Image - Accepts a File model.
    *
    * @param $value \Octo\System\Model\File
    */
    public function setImageObject(\Octo\System\Model\File $value)
    {
        return $this->setImageId($value->getId());
    }
}
