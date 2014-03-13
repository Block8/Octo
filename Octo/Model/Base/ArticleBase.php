<?php

/**
 * Article base model for table: article
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Article Base Model
 */
class ArticleBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'article';

    /**
    * @var string
    */
    protected $modelName = 'Article';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'title' => null,
        'summary' => null,
        'user_id' => null,
        'category_id' => null,
        'author_id' => null,
        'content_item_id' => null,
        'created_date' => null,
        'updated_date' => null,
        'slug' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'title' => 'getTitle',
        'summary' => 'getSummary',
        'user_id' => 'getUserId',
        'category_id' => 'getCategoryId',
        'author_id' => 'getAuthorId',
        'content_item_id' => 'getContentItemId',
        'created_date' => 'getCreatedDate',
        'updated_date' => 'getUpdatedDate',
        'slug' => 'getSlug',

        // Foreign key getters:
        'User' => 'getUser',
        'Category' => 'getCategory',
        'Author' => 'getAuthor',
        'ContentItem' => 'getContentItem',
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'title' => 'setTitle',
        'summary' => 'setSummary',
        'user_id' => 'setUserId',
        'category_id' => 'setCategoryId',
        'author_id' => 'setAuthorId',
        'content_item_id' => 'setContentItemId',
        'created_date' => 'setCreatedDate',
        'updated_date' => 'setUpdatedDate',
        'slug' => 'setSlug',

        // Foreign key setters:
        'User' => 'setUser',
        'Category' => 'setCategory',
        'Author' => 'setAuthor',
        'ContentItem' => 'setContentItem',
    ];

    /**
    * @var array
    */
    public $columns = [
        'id' => [
            'type' => 'int',
            'length' => 11,
            'primary_key' => true,
            'auto_increment' => true,
            'default' => null,
        ],
        'title' => [
            'type' => 'varchar',
            'length' => 255,
            'nullable' => true,
            'default' => null,
        ],
        'summary' => [
            'type' => 'text',
            'nullable' => true,
            'default' => null,
        ],
        'user_id' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'category_id' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'author_id' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'content_item_id' => [
            'type' => 'char',
            'length' => 32,
            'nullable' => true,
            'default' => null,
        ],
        'created_date' => [
            'type' => 'datetime',
            'nullable' => true,
            'default' => null,
        ],
        'updated_date' => [
            'type' => 'datetime',
            'nullable' => true,
            'default' => null,
        ],
        'slug' => [
            'type' => 'varchar',
            'length' => 255,
            'default' => null,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'slug' => ['unique' => true, 'columns' => 'slug'],
        'user_id' => ['columns' => 'user_id'],
        'category_id' => ['columns' => 'category_id'],
        'author_id' => ['columns' => 'author_id'],
        'content_item_id' => ['columns' => 'content_item_id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
        'article_ibfk_1' => [
            'local_col' => 'user_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'user',
            'col' => 'id'
        ],
        'article_ibfk_2' => [
            'local_col' => 'category_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'category',
            'col' => 'id'
        ],
        'article_ibfk_3' => [
            'local_col' => 'author_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'user',
            'col' => 'id'
        ],
        'article_ibfk_4' => [
            'local_col' => 'content_item_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'content_item',
            'col' => 'id'
        ],
    ];

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
    * @uses \Octo\Store\UserStore::getById()
    * @uses \Octo\Model\User
    * @return \Octo\Model\User
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
        if ($value instanceof \Octo\Model\User) {
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
    * @param $value \Octo\Model\User
    */
    public function setUserObject(\Octo\Model\User $value)
    {
        return $this->setUserId($value->getId());
    }
    /**
    * Get the Category model for this Article by Id.
    *
    * @uses \Octo\Store\CategoryStore::getById()
    * @uses \Octo\Model\Category
    * @return \Octo\Model\Category
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
        if ($value instanceof \Octo\Model\Category) {
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
    * @param $value \Octo\Model\Category
    */
    public function setCategoryObject(\Octo\Model\Category $value)
    {
        return $this->setCategoryId($value->getId());
    }
    /**
    * Get the User model for this Article by Id.
    *
    * @uses \Octo\Store\UserStore::getById()
    * @uses \Octo\Model\User
    * @return \Octo\Model\User
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
        if ($value instanceof \Octo\Model\User) {
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
    * @param $value \Octo\Model\User
    */
    public function setAuthorObject(\Octo\Model\User $value)
    {
        return $this->setAuthorId($value->getId());
    }
    /**
    * Get the ContentItem model for this Article by Id.
    *
    * @uses \Octo\Store\ContentItemStore::getById()
    * @uses \Octo\Model\ContentItem
    * @return \Octo\Model\ContentItem
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
        if ($value instanceof \Octo\Model\ContentItem) {
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
    * @param $value \Octo\Model\ContentItem
    */
    public function setContentItemObject(\Octo\Model\ContentItem $value)
    {
        return $this->setContentItemId($value->getId());
    }
}
