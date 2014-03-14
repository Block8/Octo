<?php

/**
 * PageVersion base model for table: page_version
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * PageVersion Base Model
 */
trait PageVersionBase
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'page_version';

    /**
    * @var string
    */
    protected $modelName = 'PageVersion';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'page_id' => null,
        'version' => null,
        'title' => null,
        'short_title' => null,
        'description' => null,
        'meta_description' => null,
        'content_item_id' => null,
        'user_id' => null,
        'updated_date' => null,
        'template' => null,
        'image_id' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'page_id' => 'getPageId',
        'version' => 'getVersion',
        'title' => 'getTitle',
        'short_title' => 'getShortTitle',
        'description' => 'getDescription',
        'meta_description' => 'getMetaDescription',
        'content_item_id' => 'getContentItemId',
        'user_id' => 'getUserId',
        'updated_date' => 'getUpdatedDate',
        'template' => 'getTemplate',
        'image_id' => 'getImageId',

        // Foreign key getters:
        'ContentItem' => 'getContentItem',
        'Page' => 'getPage',
        'User' => 'getUser',
        'Image' => 'getImage',
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'page_id' => 'setPageId',
        'version' => 'setVersion',
        'title' => 'setTitle',
        'short_title' => 'setShortTitle',
        'description' => 'setDescription',
        'meta_description' => 'setMetaDescription',
        'content_item_id' => 'setContentItemId',
        'user_id' => 'setUserId',
        'updated_date' => 'setUpdatedDate',
        'template' => 'setTemplate',
        'image_id' => 'setImageId',

        // Foreign key setters:
        'ContentItem' => 'setContentItem',
        'Page' => 'setPage',
        'User' => 'setUser',
        'Image' => 'setImage',
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
        'page_id' => [
            'type' => 'char',
            'length' => 5,
        ],
        'version' => [
            'type' => 'int',
            'length' => 11,
        ],
        'title' => [
            'type' => 'varchar',
            'length' => 150,
            'nullable' => true,
            'default' => null,
        ],
        'short_title' => [
            'type' => 'varchar',
            'length' => 50,
            'nullable' => true,
            'default' => null,
        ],
        'description' => [
            'type' => 'varchar',
            'length' => 250,
            'nullable' => true,
            'default' => null,
        ],
        'meta_description' => [
            'type' => 'varchar',
            'length' => 250,
            'nullable' => true,
            'default' => null,
        ],
        'content_item_id' => [
            'type' => 'char',
            'length' => 32,
            'nullable' => true,
            'default' => null,
        ],
        'user_id' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'updated_date' => [
            'type' => 'datetime',
            'default' => null,
        ],
        'template' => [
            'type' => 'varchar',
            'length' => 250,
            'default' => 'default',        ],
        'image_id' => [
            'type' => 'char',
            'length' => 32,
            'nullable' => true,
            'default' => null,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'fk_page_version_page' => ['columns' => 'page_id'],
        'fk_page_version_user' => ['columns' => 'user_id'],
        'fk_page_version_content_item' => ['columns' => 'content_item_id'],
        'image_id' => ['columns' => 'image_id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
        'fk_page_version_content_item' => [
            'local_col' => 'content_item_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'content_item',
            'col' => 'id'
        ],
        'fk_page_version_page' => [
            'local_col' => 'page_id',
            'update' => 'CASCADE',
            'delete' => 'CASCADE',
            'table' => 'page',
            'col' => 'id'
        ],
        'fk_page_version_user' => [
            'local_col' => 'user_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'user',
            'col' => 'id'
        ],
        'page_version_ibfk_1' => [
            'local_col' => 'image_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'file',
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
    * Get the value of PageId / page_id.
    *
    * @return string
    */
    public function getPageId()
    {
        $rtn = $this->data['page_id'];

        return $rtn;
    }

    /**
    * Get the value of Version / version.
    *
    * @return int
    */
    public function getVersion()
    {
        $rtn = $this->data['version'];

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
    * Get the value of ShortTitle / short_title.
    *
    * @return string
    */
    public function getShortTitle()
    {
        $rtn = $this->data['short_title'];

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
    * Get the value of MetaDescription / meta_description.
    *
    * @return string
    */
    public function getMetaDescription()
    {
        $rtn = $this->data['meta_description'];

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
    * Get the value of Template / template.
    *
    * @return string
    */
    public function getTemplate()
    {
        $rtn = $this->data['template'];

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
    * Set the value of PageId / page_id.
    *
    * Must not be null.
    * @param $value string
    */
    public function setPageId($value)
    {
        $this->validateNotNull('PageId', $value);
        $this->validateString('PageId', $value);

        if ($this->data['page_id'] === $value) {
            return;
        }

        $this->data['page_id'] = $value;
        $this->setModified('page_id');
    }

    /**
    * Set the value of Version / version.
    *
    * Must not be null.
    * @param $value int
    */
    public function setVersion($value)
    {
        $this->validateNotNull('Version', $value);
        $this->validateInt('Version', $value);

        if ($this->data['version'] === $value) {
            return;
        }

        $this->data['version'] = $value;
        $this->setModified('version');
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
    * Set the value of ShortTitle / short_title.
    *
    * @param $value string
    */
    public function setShortTitle($value)
    {
        $this->validateString('ShortTitle', $value);

        if ($this->data['short_title'] === $value) {
            return;
        }

        $this->data['short_title'] = $value;
        $this->setModified('short_title');
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
    * Set the value of MetaDescription / meta_description.
    *
    * @param $value string
    */
    public function setMetaDescription($value)
    {
        $this->validateString('MetaDescription', $value);

        if ($this->data['meta_description'] === $value) {
            return;
        }

        $this->data['meta_description'] = $value;
        $this->setModified('meta_description');
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
    * Set the value of UpdatedDate / updated_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setUpdatedDate($value)
    {
        $this->validateNotNull('UpdatedDate', $value);
        $this->validateDate('UpdatedDate', $value);

        if ($this->data['updated_date'] === $value) {
            return;
        }

        $this->data['updated_date'] = $value;
        $this->setModified('updated_date');
    }

    /**
    * Set the value of Template / template.
    *
    * Must not be null.
    * @param $value string
    */
    public function setTemplate($value)
    {
        $this->validateNotNull('Template', $value);
        $this->validateString('Template', $value);

        if ($this->data['template'] === $value) {
            return;
        }

        $this->data['template'] = $value;
        $this->setModified('template');
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
    * Get the ContentItem model for this PageVersion by Id.
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
    /**
    * Get the Page model for this PageVersion by Id.
    *
    * @uses \Octo\Store\PageStore::getById()
    * @uses \Octo\Model\Page
    * @return \Octo\Model\Page
    */
    public function getPage()
    {
        $key = $this->getPageId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Page', 'Octo')->getById($key);
    }

    /**
    * Set Page - Accepts an ID, an array representing a Page or a Page model.
    *
    * @param $value mixed
    */
    public function setPage($value)
    {
        // Is this an instance of Page?
        if ($value instanceof \Octo\Model\Page) {
            return $this->setPageObject($value);
        }

        // Is this an array representing a Page item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setPageId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setPageId($value);
    }

    /**
    * Set Page - Accepts a Page model.
    *
    * @param $value \Octo\Model\Page
    */
    public function setPageObject(\Octo\Model\Page $value)
    {
        return $this->setPageId($value->getId());
    }
    /**
    * Get the User model for this PageVersion by Id.
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
    * Get the File model for this PageVersion by Id.
    *
    * @uses \Octo\Store\FileStore::getById()
    * @uses \Octo\Model\File
    * @return \Octo\Model\File
    */
    public function getImage()
    {
        $key = $this->getImageId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('File', 'Octo')->getById($key);
    }

    /**
    * Set Image - Accepts an ID, an array representing a File or a File model.
    *
    * @param $value mixed
    */
    public function setImage($value)
    {
        // Is this an instance of File?
        if ($value instanceof \Octo\Model\File) {
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
    * @param $value \Octo\Model\File
    */
    public function setImageObject(\Octo\Model\File $value)
    {
        return $this->setImageId($value->getId());
    }
}
