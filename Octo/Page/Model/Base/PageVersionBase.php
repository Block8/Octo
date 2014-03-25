<?php

/**
 * PageVersion base model for table: page_version
 */

namespace Octo\Page\Model\Base;

use b8\Store\Factory;

/**
 * PageVersion Base Model
 */
trait PageVersionBase
{
    protected function init()
    {
        $this->tableName = 'page_version';
        $this->modelName = 'PageVersion';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['page_id'] = null;
        $this->getters['page_id'] = 'getPageId';
        $this->setters['page_id'] = 'setPageId';
        $this->data['version'] = null;
        $this->getters['version'] = 'getVersion';
        $this->setters['version'] = 'setVersion';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['short_title'] = null;
        $this->getters['short_title'] = 'getShortTitle';
        $this->setters['short_title'] = 'setShortTitle';
        $this->data['description'] = null;
        $this->getters['description'] = 'getDescription';
        $this->setters['description'] = 'setDescription';
        $this->data['meta_description'] = null;
        $this->getters['meta_description'] = 'getMetaDescription';
        $this->setters['meta_description'] = 'setMetaDescription';
        $this->data['content_item_id'] = null;
        $this->getters['content_item_id'] = 'getContentItemId';
        $this->setters['content_item_id'] = 'setContentItemId';
        $this->data['user_id'] = null;
        $this->getters['user_id'] = 'getUserId';
        $this->setters['user_id'] = 'setUserId';
        $this->data['updated_date'] = null;
        $this->getters['updated_date'] = 'getUpdatedDate';
        $this->setters['updated_date'] = 'setUpdatedDate';
        $this->data['template'] = null;
        $this->getters['template'] = 'getTemplate';
        $this->setters['template'] = 'setTemplate';
        $this->data['image_id'] = null;
        $this->getters['image_id'] = 'getImageId';
        $this->setters['image_id'] = 'setImageId';

        // Foreign keys:
        $this->getters['ContentItem'] = 'getContentItem';
        $this->setters['ContentItem'] = 'setContentItem';
        $this->getters['Page'] = 'getPage';
        $this->setters['Page'] = 'setPage';
        $this->getters['User'] = 'getUser';
        $this->setters['User'] = 'setUser';
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
    * @uses \Octo\Page\Store\ContentItemStore::getById()
    * @uses \Octo\Page\Model\ContentItem
    * @return \Octo\Page\Model\ContentItem
    */
    public function getContentItem()
    {
        $key = $this->getContentItemId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('ContentItem', 'Octo\System')->getById($key);
    }

    /**
    * Set ContentItem - Accepts an ID, an array representing a ContentItem or a ContentItem model.
    *
    * @param $value mixed
    */
    public function setContentItem($value)
    {
        // Is this an instance of ContentItem?
        if ($value instanceof \Octo\Page\Model\ContentItem) {
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
    * @param $value \Octo\Page\Model\ContentItem
    */
    public function setContentItemObject(\Octo\Page\Model\ContentItem $value)
    {
        return $this->setContentItemId($value->getId());
    }
    /**
    * Get the Page model for this PageVersion by Id.
    *
    * @uses \Octo\Page\Store\PageStore::getById()
    * @uses \Octo\Page\Model\Page
    * @return \Octo\Page\Model\Page
    */
    public function getPage()
    {
        $key = $this->getPageId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Page', 'Octo\Page')->getById($key);
    }

    /**
    * Set Page - Accepts an ID, an array representing a Page or a Page model.
    *
    * @param $value mixed
    */
    public function setPage($value)
    {
        // Is this an instance of Page?
        if ($value instanceof \Octo\Page\Model\Page) {
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
    * @param $value \Octo\Page\Model\Page
    */
    public function setPageObject(\Octo\Page\Model\Page $value)
    {
        return $this->setPageId($value->getId());
    }
    /**
    * Get the User model for this PageVersion by Id.
    *
    * @uses \Octo\Page\Store\UserStore::getById()
    * @uses \Octo\Page\Model\User
    * @return \Octo\Page\Model\User
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
        if ($value instanceof \Octo\Page\Model\User) {
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
    * @param $value \Octo\Page\Model\User
    */
    public function setUserObject(\Octo\Page\Model\User $value)
    {
        return $this->setUserId($value->getId());
    }
    /**
    * Get the File model for this PageVersion by Id.
    *
    * @uses \Octo\Page\Store\FileStore::getById()
    * @uses \Octo\Page\Model\File
    * @return \Octo\Page\Model\File
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
        if ($value instanceof \Octo\Page\Model\File) {
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
    * @param $value \Octo\Page\Model\File
    */
    public function setImageObject(\Octo\Page\Model\File $value)
    {
        return $this->setImageId($value->getId());
    }
}
