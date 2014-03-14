<?php

/**
 * ContentItem base model for table: content_item
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * ContentItem Base Model
 */
trait ContentItemBase
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'content_item';

    /**
    * @var string
    */
    protected $modelName = 'ContentItem';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'content' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'content' => 'getContent',

        // Foreign key getters:
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'content' => 'setContent',

        // Foreign key setters:
    ];

    /**
    * @var array
    */
    public $columns = [
        'id' => [
            'type' => 'char',
            'length' => 32,
            'primary_key' => true,
        ],
        'content' => [
            'type' => 'longtext',
            'default' => null,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
    ];

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
    * Get the value of Content / content.
    *
    * @return string
    */
    public function getContent()
    {
        $rtn = $this->data['content'];

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
    * Set the value of Content / content.
    *
    * Must not be null.
    * @param $value string
    */
    public function setContent($value)
    {
        $this->validateNotNull('Content', $value);
        $this->validateString('Content', $value);

        if ($this->data['content'] === $value) {
            return;
        }

        $this->data['content'] = $value;
        $this->setModified('content');
    }

}
