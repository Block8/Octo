<?php

/**
 * GaPageView base model for table: ga_page_view
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * GaPageView Base Model
 */
class GaPageViewBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'ga_page_view';

    /**
    * @var string
    */
    protected $modelName = 'GaPageView';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'date' => null,
        'updated' => null,
        'value' => null,
        'metric' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'date' => 'getDate',
'updated' => 'getUpdated',
'value' => 'getValue',
'metric' => 'getMetric',

// Foreign key getters:
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'date' => 'setDate',
'updated' => 'setUpdated',
'value' => 'setValue',
'metric' => 'setMetric',

// Foreign key setters:
);

/**
* @var array
*/
public $columns = array(
'id' => array(
'type' => 'int',
'length' => 11,
'primary_key' => true,
'auto_increment' => true,
'default' => null,
),
'date' => array(
'type' => 'date',
'nullable' => true,
'default' => null,
),
'updated' => array(
'type' => 'datetime',
'nullable' => true,
'default' => null,
),
'value' => array(
'type' => 'int',
'length' => 11,
'nullable' => true,
'default' => null,
),
'metric' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
);

/**
* @var array
*/
public $indexes = array(
'PRIMARY' => array('unique' => true, 'columns' => 'id'),
'metric' => array('unique' => true, 'columns' => 'metric, date'),
);

/**
* @var array
*/
public $foreignKeys = array(
);

/**
* Get the value of Id / id.
*
* @return int
*/
public function getId()
{
$rtn    = $this->data['id'];

return $rtn;
}

/**
* Get the value of Date / date.
*
* @return \DateTime
*/
public function getDate()
{
$rtn    = $this->data['date'];

if (!empty($rtn)) {
$rtn    = new \DateTime($rtn);
}

return $rtn;
}

/**
* Get the value of Updated / updated.
*
* @return \DateTime
*/
public function getUpdated()
{
$rtn    = $this->data['updated'];

if (!empty($rtn)) {
$rtn    = new \DateTime($rtn);
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
$rtn    = $this->data['value'];

return $rtn;
}

/**
* Get the value of Metric / metric.
*
* @return string
*/
public function getMetric()
{
$rtn    = $this->data['metric'];

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
* Set the value of Date / date.
*
* @param $value \DateTime
*/
public function setDate($value)
{
$this->validateDate('Date', $value);

if ($this->data['date'] === $value) {
return;
}

$this->data['date'] = $value;

$this->setModified('date');
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



public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('GaPageView', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('GaPageView', 'Octo')->getById($value, $useConnection);
}

public static function getByMetric($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('GaPageView', 'Octo')->getByMetric($value, $limit, $useConnection);
}


}
