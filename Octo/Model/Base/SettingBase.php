<?php

/**
 * Setting base model for table: setting
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * Setting Base Model
 */
trait SettingBase
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'setting';

    /**
    * @var string
    */
    protected $modelName = 'Setting';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'key' => null,
        'value' => null,
        'scope' => null,
        'hidden' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'key' => 'getKey',
        'value' => 'getValue',
        'scope' => 'getScope',
        'hidden' => 'getHidden',

        // Foreign key getters:
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'key' => 'setKey',
        'value' => 'setValue',
        'scope' => 'setScope',
        'hidden' => 'setHidden',

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
        'key' => [
            'type' => 'varchar',
            'length' => 100,
        ],
        'value' => [
            'type' => 'text',
            'nullable' => true,
            'default' => null,
        ],
        'scope' => [
            'type' => 'varchar',
            'length' => 100,
        ],
        'hidden' => [
            'type' => 'tinyint',
            'length' => 1,
            'nullable' => true,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'key' => ['unique' => true, 'columns' => 'key, scope'],
        'scope' => ['columns' => 'scope'],
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
    * Get the value of Key / key.
    *
    * @return string
    */
    public function getKey()
    {
        $rtn = $this->data['key'];

        return $rtn;
    }

    /**
    * Get the value of Value / value.
    *
    * @return string
    */
    public function getValue()
    {
        $rtn = $this->data['value'];

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
    * Get the value of Hidden / hidden.
    *
    * @return int
    */
    public function getHidden()
    {
        $rtn = $this->data['hidden'];

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
    * Set the value of Key / key.
    *
    * Must not be null.
    * @param $value string
    */
    public function setKey($value)
    {
        $this->validateNotNull('Key', $value);
        $this->validateString('Key', $value);

        if ($this->data['key'] === $value) {
            return;
        }

        $this->data['key'] = $value;
        $this->setModified('key');
    }

    /**
    * Set the value of Value / value.
    *
    * @param $value string
    */
    public function setValue($value)
    {
        $this->validateString('Value', $value);

        if ($this->data['value'] === $value) {
            return;
        }

        $this->data['value'] = $value;
        $this->setModified('value');
    }

    /**
    * Set the value of Scope / scope.
    *
    * Must not be null.
    * @param $value string
    */
    public function setScope($value)
    {
        $this->validateNotNull('Scope', $value);
        $this->validateString('Scope', $value);

        if ($this->data['scope'] === $value) {
            return;
        }

        $this->data['scope'] = $value;
        $this->setModified('scope');
    }

    /**
    * Set the value of Hidden / hidden.
    *
    * @param $value int
    */
    public function setHidden($value)
    {
        $this->validateInt('Hidden', $value);

        if ($this->data['hidden'] === $value) {
            return;
        }

        $this->data['hidden'] = $value;
        $this->setModified('hidden');
    }

}
