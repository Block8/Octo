<?php

/**
 * Article base model for table: article
 */

namespace Octo\Articles\Model\Base;

use b8\Store\Factory;

/**
 * Article Base Model
 */
trait ArticleBase
{
    protected function init()
    {
        $this->tableName = 'article';
        $this->modelName = 'Article';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['summary'] = null;
        $this->getters['summary'] = 'getSummary';
        $this->setters['summary'] = 'setSummary';
        $this->data['user_id'] = null;
        $this->getters['user_id'] = 'getUserId';
        $this->setters['user_id'] = 'setUserId';
        $this->data['category_id'] = null;
        $this->getters['category_id'] = 'getCategoryId';
        $this->setters['category_id'] = 'setCategoryId';
        $this->data['author_id'] = null;
        $this->getters['author_id'] = 'getAuthorId';
        $this->setters['author_id'] = 'setAuthorId';
        $this->data['content_item_id'] = null;
        $this->getters['content_item_id'] = 'getContentItemId';
        $this->setters['content_item_id'] = 'setContentItemId';
        $this->data['created_date'] = null;
        $this->getters['created_date'] = 'getCreatedDate';
        $this->setters['created_date'] = 'setCreatedDate';
        $this->data['updated_date'] = null;
        $this->getters['updated_date'] = 'getUpdatedDate';
        $this->setters['updated_date'] = 'setUpdatedDate';
        $this->data['slug'] = null;
        $this->getters['slug'] = 'getSlug';
        $this->setters['slug'] = 'setSlug';

        // Foreign keys:
        $this->getters['User'] = 'getUser';
        $this->setters['User'] = 'setUser';
        $this->getters['Category'] = 'getCategory';
        $this->setters['Category'] = 'setCategory';
        $this->getters['Author'] = 'getAuthor';
        $this->setters['Author'] = 'setAuthor';
        $this->getters['ContentItem'] = 'getContentItem';
        $this->setters['ContentItem'] = 'setContentItem';
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
    * Get the value of Summary / summary.
    *
    * @return string
    */
    public function getSummary()
    {
        $rtn = $this->data['summary'];

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
    * Get the value of AuthorId / author_id.
    *
    * @return int
    */
    public function getAuthorId()
    {
        $rtn = $this->data['author_id'];

        return $rtn;
    }

    /**
    * Get the value of ContentItemId / content_item_id.
    *
    * @return string
    */
    public function getContentItemId()
    {
        $rtn = $this->data['content_item_id'];

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
    * Set the value of Summary / summary.
    *
    * @param $value string
    */
    public function setSummary($value)
    {
        $this->validateString('Summary', $value);

        if ($this->data['summary'] === $value) {
            return;
        }

        $this->data['summary'] = $value;
        $this->setModified('summary');
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
    * Set the value of AuthorId / author_id.
    *
    * @param $value int
    */
    public function setAuthorId($value)
    {
        $this->validateInt('AuthorId', $value);

        if ($this->data['author_id'] === $value) {
            return;
        }

        $this->data['author_id'] = $value;
        $this->setModified('author_id');
    }

    /**
    * Set the value of ContentItemId / content_item_id.
    *
    * @param $value string
    */
    public function setContentItemId($value)
    {
        $this->validateString('ContentItemId', $value);

        if ($this->data['content_item_id'] === $value) {
            return;
        }

        $this->data['content_item_id'] = $value;
        $this->setModified('content_item_id');
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
    * Set the value of Slug / slug.
    *
    * Must not be null.
    * @param $value string
    */
    public function setSlug($value)
    {
        $this->validateNotNull('Slug', $value);
        $this->validateString('Slug', $value);

        if ($this->data['slug'] === $value) {
            return;
        }

        $this->data['slug'] = $value;
        $this->setModified('slug');
    }

    /**
    * Get the User model for this Article by Id.
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

        return Factory::getStore('User', 'Octo')->getById($key);
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
    /**
    * Get the Category model for this Article by Id.
    *
    * @uses \Octo\Categories\Store\CategoryStore::getById()
    * @uses \Octo\Categories\Model\Category
    * @return \Octo\Categories\Model\Category
    */
    public function getCategory()
    {
        $key = $this->getCategoryId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Category', 'Octo')->getById($key);
    }

    /**
    * Set Category - Accepts an ID, an array representing a Category or a Category model.
    *
    * @param $value mixed
    */
    public function setCategory($value)
    {
        // Is this an instance of Category?
        if ($value instanceof \Octo\Categories\Model\Category) {
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
    * @param $value \Octo\Categories\Model\Category
    */
    public function setCategoryObject(\Octo\Categories\Model\Category $value)
    {
        return $this->setCategoryId($value->getId());
    }
    /**
    * Get the User model for this Article by Id.
    *
    * @uses \Octo\System\Store\UserStore::getById()
    * @uses \Octo\System\Model\User
    * @return \Octo\System\Model\User
    */
    public function getAuthor()
    {
        $key = $this->getAuthorId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('User', 'Octo')->getById($key);
    }

    /**
    * Set Author - Accepts an ID, an array representing a User or a User model.
    *
    * @param $value mixed
    */
    public function setAuthor($value)
    {
        // Is this an instance of User?
        if ($value instanceof \Octo\System\Model\User) {
            return $this->setAuthorObject($value);
        }

        // Is this an array representing a User item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setAuthorId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setAuthorId($value);
    }

    /**
    * Set Author - Accepts a User model.
    *
    * @param $value \Octo\System\Model\User
    */
    public function setAuthorObject(\Octo\System\Model\User $value)
    {
        return $this->setAuthorId($value->getId());
    }
    /**
    * Get the ContentItem model for this Article by Id.
    *
    * @uses \Octo\System\Store\ContentItemStore::getById()
    * @uses \Octo\System\Model\ContentItem
    * @return \Octo\System\Model\ContentItem
    */
    public function getContentItem()
    {
        $key = $this->getContentItemId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('ContentItem', 'Octo')->getById($key);
    }

    /**
    * Set ContentItem - Accepts an ID, an array representing a ContentItem or a ContentItem model.
    *
    * @param $value mixed
    */
    public function setContentItem($value)
    {
        // Is this an instance of ContentItem?
        if ($value instanceof \Octo\System\Model\ContentItem) {
            return $this->setContentItemObject($value);
        }

        // Is this an array representing a ContentItem item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setContentItemId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setContentItemId($value);
    }

    /**
    * Set ContentItem - Accepts a ContentItem model.
    *
    * @param $value \Octo\System\Model\ContentItem
    */
    public function setContentItemObject(\Octo\System\Model\ContentItem $value)
    {
        return $this->setContentItemId($value->getId());
    }
}
