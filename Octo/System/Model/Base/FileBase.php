<?php

/**
 * File base model for table: file
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * File Base Model
 */
trait FileBase
{
    protected function init()
    {
        $this->tableName = 'file';
        $this->modelName = 'File';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['scope'] = null;
        $this->getters['scope'] = 'getScope';
        $this->setters['scope'] = 'setScope';
        $this->data['category_id'] = null;
        $this->getters['category_id'] = 'getCategoryId';
        $this->setters['category_id'] = 'setCategoryId';
        $this->data['filename'] = null;
        $this->getters['filename'] = 'getFilename';
        $this->setters['filename'] = 'setFilename';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['mime_type'] = null;
        $this->getters['mime_type'] = 'getMimeType';
        $this->setters['mime_type'] = 'setMimeType';
        $this->data['extension'] = null;
        $this->getters['extension'] = 'getExtension';
        $this->setters['extension'] = 'setExtension';
        $this->data['created_date'] = null;
        $this->getters['created_date'] = 'getCreatedDate';
        $this->setters['created_date'] = 'setCreatedDate';
        $this->data['updated_date'] = null;
        $this->getters['updated_date'] = 'getUpdatedDate';
        $this->setters['updated_date'] = 'setUpdatedDate';
        $this->data['user_id'] = null;
        $this->getters['user_id'] = 'getUserId';
        $this->setters['user_id'] = 'setUserId';
        $this->data['size'] = null;
        $this->getters['size'] = 'getSize';
        $this->setters['size'] = 'setSize';

        // Foreign keys:
        $this->getters['Category'] = 'getCategory';
        $this->setters['Category'] = 'setCategory';
        $this->getters['User'] = 'getUser';
        $this->setters['User'] = 'setUser';
    }
    /**
    * Get the value of Id / id.
    *
    * @return string
    */
    public function getId()
    {
        $rtn = $this->data['id'];

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
    * Get the value of Filename / filename.
    *
    * @return string
    */
    public function getFilename()
    {
        $rtn = $this->data['filename'];

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
    * Get the value of MimeType / mime_type.
    *
    * @return string
    */
    public function getMimeType()
    {
        $rtn = $this->data['mime_type'];

        return $rtn;
    }

    /**
    * Get the value of Extension / extension.
    *
    * @return string
    */
    public function getExtension()
    {
        $rtn = $this->data['extension'];

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
    * Get the value of UserId / user_id.
    *
    * @return int
    */
    public function getUserId()
    {
        $rtn = $this->data['user_id'];

        return $rtn;
    }

    /**
    * Get the value of Size / size.
    *
    * @return int
    */
    public function getSize()
    {
        $rtn = $this->data['size'];

        return $rtn;
    }

    /**
    * Set the value of Id / id.
    *
    * Must not be null.
    * @param $value string
    */
    public function setId($value)
    {
        $this->validateNotNull('Id', $value);
        $this->validateString('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
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
    * Set the value of Filename / filename.
    *
    * @param $value string
    */
    public function setFilename($value)
    {
        $this->validateString('Filename', $value);

        if ($this->data['filename'] === $value) {
            return;
        }

        $this->data['filename'] = $value;
        $this->setModified('filename');
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
    * Set the value of MimeType / mime_type.
    *
    * @param $value string
    */
    public function setMimeType($value)
    {
        $this->validateString('MimeType', $value);

        if ($this->data['mime_type'] === $value) {
            return;
        }

        $this->data['mime_type'] = $value;
        $this->setModified('mime_type');
    }

    /**
    * Set the value of Extension / extension.
    *
    * @param $value string
    */
    public function setExtension($value)
    {
        $this->validateString('Extension', $value);

        if ($this->data['extension'] === $value) {
            return;
        }

        $this->data['extension'] = $value;
        $this->setModified('extension');
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
    * Set the value of UserId / user_id.
    *
    * @param $value int
    */
    public function setUserId($value)
    {
        $this->validateInt('UserId', $value);

        if ($this->data['user_id'] === $value) {
            return;
        }

        $this->data['user_id'] = $value;
        $this->setModified('user_id');
    }

    /**
    * Set the value of Size / size.
    *
    * @param $value int
    */
    public function setSize($value)
    {
        $this->validateInt('Size', $value);

        if ($this->data['size'] === $value) {
            return;
        }

        $this->data['size'] = $value;
        $this->setModified('size');
    }

    /**
    * Get the Category model for this File by Id.
    *
    * @uses \Octo\System\Store\CategoryStore::getById()
    * @uses \Octo\System\Model\Category
    * @return \Octo\System\Model\Category
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
        if ($value instanceof \Octo\System\Model\Category) {
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
    * @param $value \Octo\System\Model\Category
    */
    public function setCategoryObject(\Octo\System\Model\Category $value)
    {
        return $this->setCategoryId($value->getId());
    }
    /**
    * Get the User model for this File by Id.
    *
    * @uses \Octo\System\Store\UserStore::getById()
    * @uses \Octo\System\Model\User
    * @return \Octo\System\Model\User
    */
    public function getUser()
    {
        $key = $this->getUserId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('User', 'Octo\System')->getById($key);
    }

    /**
    * Set User - Accepts an ID, an array representing a User or a User model.
    *
    * @param $value mixed
    */
    public function setUser($value)
    {
        // Is this an instance of User?
        if ($value instanceof \Octo\System\Model\User) {
            return $this->setUserObject($value);
        }

        // Is this an array representing a User item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setUserId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setUserId($value);
    }

    /**
    * Set User - Accepts a User model.
    *
    * @param $value \Octo\System\Model\User
    */
    public function setUserObject(\Octo\System\Model\User $value)
    {
        return $this->setUserId($value->getId());
    }
}
