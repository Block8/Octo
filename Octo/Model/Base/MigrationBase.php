<?php

/**
 * Migration base model for table: migration
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Migration Base Model
 */
class MigrationBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'migration';

    /**
    * @var string
    */
    protected $modelName = 'Migration';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'date_run' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'date_run' => 'getDateRun',

// Foreign key getters:
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'date_run' => 'setDateRun',

// Foreign key setters:
);

/**
* @var array
*/
public $columns = array(
'id' => array(
'type' => 'varchar',
'length' => 50,
'primary_key' => true,
),
'date_run' => array(
'type' => 'datetime',
'nullable' => true,
'default' => null,
),
);

/**
* @var array
*/
public $indexes = array(
'PRIMARY' => array('unique' => true, 'columns' => 'id'),
);

/**
* @var array
*/
public $foreignKeys = array(
);

/**
* Get the value of Id / id.
*
* @return string
*/
public function getId()
{
$rtn    = $this->data['id'];

return $rtn;
}

/**
* Get the value of DateRun / date_run.
*
* @return \DateTime
*/
public function getDateRun()
{
$rtn    = $this->data['date_run'];

if (!empty($rtn)) {
$rtn    = new \DateTime($rtn);
}

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
* Set the value of DateRun / date_run.
*
* @param $value \DateTime
*/
public function setDateRun($value)
{
$this->validateDate('DateRun', $value);

if ($this->data['date_run'] === $value) {
return;
}

$this->data['date_run'] = $value;

$this->setModified('date_run');
}


public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('Migration', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('Migration', 'Octo')->getById($value, $useConnection);
}


}
