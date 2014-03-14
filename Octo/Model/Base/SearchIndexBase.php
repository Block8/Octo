<?php

/**
 * SearchIndex base model for table: search_index
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * SearchIndex Base Model
 */
trait SearchIndexBase
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'search_index';

    /**
    * @var string
    */
    protected $modelName = 'SearchIndex';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'word' => null,
        'model' => null,
        'content_id' => null,
        'instances' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'word' => 'getWord',
        'model' => 'getModel',
        'content_id' => 'getContentId',
        'instances' => 'getInstances',

        // Foreign key getters:
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'word' => 'setWord',
        'model' => 'setModel',
        'content_id' => 'setContentId',
        'instances' => 'setInstances',

        // Foreign key setters:
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
        'word' => [
            'type' => 'varchar',
            'length' => 50,
        ],
        'model' => [
            'type' => 'varchar',
            'length' => 50,
        ],
        'content_id' => [
            'type' => 'varchar',
            'length' => 32,
        ],
        'instances' => [
            'type' => 'int',
            'length' => 11,
            'default' => 1,        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'idx_search' => ['columns' => 'word, instances, model, content_id'],
    ];

    /**
    * @var array
    */
    public $foreignKeys = [
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
    * Get the value of Word / word.
    *
    * @return string
    */
    public function getWord()
    {
        $rtn = $this->data['word'];

        return $rtn;
    }

    /**
    * Get the value of Model / model.
    *
    * @return string
    */
    public function getModel()
    {
        $rtn = $this->data['model'];

        return $rtn;
    }

    /**
    * Get the value of ContentId / content_id.
    *
    * @return string
    */
    public function getContentId()
    {
        $rtn = $this->data['content_id'];

        return $rtn;
    }

    /**
    * Get the value of Instances / instances.
    *
    * @return int
    */
    public function getInstances()
    {
        $rtn = $this->data['instances'];

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
    * Set the value of Word / word.
    *
    * Must not be null.
    * @param $value string
    */
    public function setWord($value)
    {
        $this->validateNotNull('Word', $value);
        $this->validateString('Word', $value);

        if ($this->data['word'] === $value) {
            return;
        }

        $this->data['word'] = $value;
        $this->setModified('word');
    }

    /**
    * Set the value of Model / model.
    *
    * Must not be null.
    * @param $value string
    */
    public function setModel($value)
    {
        $this->validateNotNull('Model', $value);
        $this->validateString('Model', $value);

        if ($this->data['model'] === $value) {
            return;
        }

        $this->data['model'] = $value;
        $this->setModified('model');
    }

    /**
    * Set the value of ContentId / content_id.
    *
    * Must not be null.
    * @param $value string
    */
    public function setContentId($value)
    {
        $this->validateNotNull('ContentId', $value);
        $this->validateString('ContentId', $value);

        if ($this->data['content_id'] === $value) {
            return;
        }

        $this->data['content_id'] = $value;
        $this->setModified('content_id');
    }

    /**
    * Set the value of Instances / instances.
    *
    * Must not be null.
    * @param $value int
    */
    public function setInstances($value)
    {
        $this->validateNotNull('Instances', $value);
        $this->validateInt('Instances', $value);

        if ($this->data['instances'] === $value) {
            return;
        }

        $this->data['instances'] = $value;
        $this->setModified('instances');
    }

}
