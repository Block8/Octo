<?php

/**
 * Menu base model for table: menu
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Menu Base Model
 */
class MenuBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = [];

    /**
    * @var string
    */
    protected $tableName = 'menu';

    /**
    * @var string
    */
    protected $modelName = 'Menu';

    /**
    * @var array
    */
    protected $data = [
        'id' => null,
        'name' => null,
        'template_tag' => null,
    ];

    /**
    * @var array
    */
    protected $getters = [
        // Direct property getters:
        'id' => 'getId',
        'name' => 'getName',
        'template_tag' => 'getTemplateTag',

        // Foreign key getters:
    ];

    /**
    * @var array
    */
    protected $setters = [
        // Direct property setters:
        'id' => 'setId',
        'name' => 'setName',
        'template_tag' => 'setTemplateTag',

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
        'name' => [
            'type' => 'varchar',
            'length' => 255,
            'nullable' => true,
            'default' => null,
        ],
        'template_tag' => [
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
        'template_tag' => ['unique' => true, 'columns' => 'template_tag'],
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
    * Get the value of Name / name.
    *
    * @return string
    */
    public function getName()
    {
        $rtn = $this->data['name'];

        return $rtn;
    }

    /**
    * Get the value of TemplateTag / template_tag.
    *
    * @return string
    */
    public function getTemplateTag()
    {
        $rtn = $this->data['template_tag'];

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
    * Set the value of Name / name.
    *
    * @param $value string
    */
    public function setName($value)
    {
        $this->validateString('Name', $value);

        if ($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;
        $this->setModified('name');
    }

    /**
    * Set the value of TemplateTag / template_tag.
    *
    * @param $value string
    */
    public function setTemplateTag($value)
    {
        $this->validateString('TemplateTag', $value);

        if ($this->data['template_tag'] === $value) {
            return;
        }

        $this->data['template_tag'] = $value;
        $this->setModified('template_tag');
    }

}
