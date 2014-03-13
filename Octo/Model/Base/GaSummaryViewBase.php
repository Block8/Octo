<?php

/**
 * GaSummaryView base model for table: ga_summary_view
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * GaSummaryView Base Model
 */
class GaSummaryViewBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'ga_summary_view';

    /**
    * @var string
    */
    protected $modelName = 'GaSummaryView';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'updated' => null,
        'value' => null,
        'metric' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'updated' => 'getUpdated',
        'value' => 'getValue',
        'metric' => 'getMetric',

        // Foreign key getters:
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'updated' => 'setUpdated',
        'value' => 'setValue',
        'metric' => 'setMetric',

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
        'updated' => [
            'type' => 'datetime',
            'nullable' => true,
            'default' => null,
        ],
        'value' => [
            'type' => 'int',
            'length' => 11,
            'nullable' => true,
            'default' => null,
        ],
        'metric' => [
            'type' => 'varchar',
            'length' => 255,
            'nullable' => true,
            'default' => null,
        ],
    ];

    /**
    * @var array
    */
    public $indexes = [
        'PRIMARY' => ['unique' => true, 'columns' => 'id'],
        'metric' => ['unique' => true, 'columns' => 'metric'],
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
    * Get the value of Value / value.
    *
    * @return int
    */
    public function getValue()
    {
        $rtn = $this->data['value'];

        return $rtn;
    }

    /**
    * Get the value of Metric / metric.
    *
    * @return string
    */
    public function getMetric()
    {
        $rtn = $this->data['metric'];

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
    * Set the value of Value / value.
    *
    * @param $value int
    */
    public function setValue($value)
    {
        $this->validateInt('Value', $value);

        if ($this->data['value'] === $value) {
            return;
        }

        $this->data['value'] = $value;
        $this->setModified('value');
    }

    /**
    * Set the value of Metric / metric.
    *
    * @param $value string
    */
    public function setMetric($value)
    {
        $this->validateString('Metric', $value);

        if ($this->data['metric'] === $value) {
            return;
        }

        $this->data['metric'] = $value;
        $this->setModified('metric');
    }

}
