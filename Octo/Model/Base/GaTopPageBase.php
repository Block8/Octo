<?php

/**
 * GaTopPage base model for table: ga_top_page
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * GaTopPage Base Model
 */
class GaTopPageBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'ga_top_page';

    /**
    * @var string
    */
    protected $modelName = 'GaTopPage';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'updated' => null,
        'pageviews' => null,
        'unique_pageviews' => null,
        'uri' => null,
        'page_id' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'updated' => 'getUpdated',
        'pageviews' => 'getPageviews',
        'unique_pageviews' => 'getUniquePageviews',
        'uri' => 'getUri',
        'page_id' => 'getPageId',

        // Foreign key getters:
        'Page' => 'getPage',
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'updated' => 'setUpdated',
        'pageviews' => 'setPageviews',
        'unique_pageviews' => 'setUniquePageviews',
        'uri' => 'setUri',
        'page_id' => 'setPageId',

        // Foreign key setters:
        'Page' => 'setPage',
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
        'updated' => [
            'type' => 'datetime',
            'nullable' => true,
            'default' => null,
        ],
        'pageviews' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'unique_pageviews' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'uri' => [
            'type' => 'varchar',
            'length' => 255,
            'nullable' => true,
            'default' => null,
        ],
        'page_id' => [
            'type' => 'char',
            'length' => 5,
            'nullable' => true,
            'default' => null,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'uri' => ['unique' => true, 'columns' => 'uri'],
        'page_id' => ['columns' => 'page_id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
        'ga_top_page_ibfk_1' => [
            'local_col' => 'page_id',
            'update' => 'CASCADE',
            'delete' => 'SET NULL',
            'table' => 'page',
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
}
