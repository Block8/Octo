<?php

/**
 * GaTopPage base model for table: ga_top_page
 */

namespace Octo\Analytics\Model\Base;

use b8\Store\Factory;

/**
 * GaTopPage Base Model
 */
trait GaTopPageBase
{
    protected function init()
    {
        $this->tableName = 'ga_top_page';
        $this->modelName = 'GaTopPage';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['updated'] = null;
        $this->getters['updated'] = 'getUpdated';
        $this->setters['updated'] = 'setUpdated';
        $this->data['pageviews'] = null;
        $this->getters['pageviews'] = 'getPageviews';
        $this->setters['pageviews'] = 'setPageviews';
        $this->data['unique_pageviews'] = null;
        $this->getters['unique_pageviews'] = 'getUniquePageviews';
        $this->setters['unique_pageviews'] = 'setUniquePageviews';
        $this->data['uri'] = null;
        $this->getters['uri'] = 'getUri';
        $this->setters['uri'] = 'setUri';
        $this->data['page_id'] = null;
        $this->getters['page_id'] = 'getPageId';
        $this->setters['page_id'] = 'setPageId';

        // Foreign keys:
        $this->getters['Page'] = 'getPage';
        $this->setters['Page'] = 'setPage';
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
    * Get the value of Updated / updated.
    *
    * @return \DateTime
    */
    public function getUpdated()
    {
        $rtn = $this->data['updated'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of Pageviews / pageviews.
    *
    * @return int
    */
    public function getPageviews()
    {
        $rtn = $this->data['pageviews'];

        return $rtn;
    }

    /**
    * Get the value of UniquePageviews / unique_pageviews.
    *
    * @return int
    */
    public function getUniquePageviews()
    {
        $rtn = $this->data['unique_pageviews'];

        return $rtn;
    }

    /**
    * Get the value of Uri / uri.
    *
    * @return string
    */
    public function getUri()
    {
        $rtn = $this->data['uri'];

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
    * Set the value of Updated / updated.
    *
    * @param $value \DateTime
    */
    public function setUpdated($value)
    {
        $this->validateDate('Updated', $value);

        if ($this->data['updated'] === $value) {
            return;
        }

        $this->data['updated'] = $value;
        $this->setModified('updated');
    }

    /**
    * Set the value of Pageviews / pageviews.
    *
    * @param $value int
    */
    public function setPageviews($value)
    {
        $this->validateInt('Pageviews', $value);

        if ($this->data['pageviews'] === $value) {
            return;
        }

        $this->data['pageviews'] = $value;
        $this->setModified('pageviews');
    }

    /**
    * Set the value of UniquePageviews / unique_pageviews.
    *
    * @param $value int
    */
    public function setUniquePageviews($value)
    {
        $this->validateInt('UniquePageviews', $value);

        if ($this->data['unique_pageviews'] === $value) {
            return;
        }

        $this->data['unique_pageviews'] = $value;
        $this->setModified('unique_pageviews');
    }

    /**
    * Set the value of Uri / uri.
    *
    * @param $value string
    */
    public function setUri($value)
    {
        $this->validateString('Uri', $value);

        if ($this->data['uri'] === $value) {
            return;
        }

        $this->data['uri'] = $value;
        $this->setModified('uri');
    }

    /**
    * Set the value of PageId / page_id.
    *
    * @param $value string
    */
    public function setPageId($value)
    {
        $this->validateString('PageId', $value);

        if ($this->data['page_id'] === $value) {
            return;
        }

        $this->data['page_id'] = $value;
        $this->setModified('page_id');
    }

    /**
    * Get the Page model for this GaTopPage by Id.
    *
    * @uses \Octo\Pages\Store\PageStore::getById()
    * @uses \Octo\Pages\Model\Page
    * @return \Octo\Pages\Model\Page
    */
    public function getPage()
    {
        $key = $this->getPageId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Page', 'Octo\Pages')->getById($key);
    }

    /**
    * Set Page - Accepts an ID, an array representing a Page or a Page model.
    *
    * @param $value mixed
    */
    public function setPage($value)
    {
        // Is this an instance of Page?
        if ($value instanceof \Octo\Pages\Model\Page) {
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
    * @param $value \Octo\Pages\Model\Page
    */
    public function setPageObject(\Octo\Pages\Model\Page $value)
    {
        return $this->setPageId($value->getId());
    }
}
