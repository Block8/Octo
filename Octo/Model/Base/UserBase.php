<?php

/**
 * User base model for table: user
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * User Base Model
 */
class UserBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'user';

    /**
    * @var string
    */
    protected $modelName = 'User';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'email' => null,
        'hash' => null,
        'name' => null,
        'is_admin' => null,
        'is_hidden' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'email' => 'getEmail',
'hash' => 'getHash',
'name' => 'getName',
'is_admin' => 'getIsAdmin',
'is_hidden' => 'getIsHidden',

// Foreign key getters:
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'email' => 'setEmail',
'hash' => 'setHash',
'name' => 'setName',
'is_admin' => 'setIsAdmin',
'is_hidden' => 'setIsHidden',

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
'email' => array(
'type' => 'varchar',
'length' => 250,
'default' => null,
),
'hash' => array(
'type' => 'varchar',
'length' => 250,
'default' => null,
),
'name' => array(
'type' => 'varchar',
'length' => 250,
'nullable' => true,
'default' => null,
),
'is_admin' => array(
'type' => 'tinyint',
'length' => 1,
),
'is_hidden' => array(
'type' => 'tinyint',
'length' => 1,
),
);

/**
* @var array
*/
public $indexes = array(
'PRIMARY' => array('unique' => true, 'columns' => 'id'),
'idx_email' => array('unique' => true, 'columns' => 'email'),
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
* Get the value of Email / email.
*
* @return string
*/
public function getEmail()
{
$rtn    = $this->data['email'];

return $rtn;
}

/**
* Get the value of Hash / hash.
*
* @return string
*/
public function getHash()
{
$rtn    = $this->data['hash'];

return $rtn;
}

/**
* Get the value of Name / name.
*
* @return string
*/
public function getName()
{
$rtn    = $this->data['name'];

return $rtn;
}

/**
* Get the value of IsAdmin / is_admin.
*
* @return int
*/
public function getIsAdmin()
{
$rtn    = $this->data['is_admin'];

return $rtn;
}

/**
* Get the value of IsHidden / is_hidden.
*
* @return int
*/
public function getIsHidden()
{
$rtn    = $this->data['is_hidden'];

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
* Set the value of Email / email.
*
* Must not be null.
* @param $value string
*/
public function setEmail($value)
{
$this->validateNotNull('Email', $value);
$this->validateString('Email', $value);

if ($this->data['email'] === $value) {
return;
}

$this->data['email'] = $value;

$this->setModified('email');
}

/**
* Set the value of Hash / hash.
*
* Must not be null.
* @param $value string
*/
public function setHash($value)
{
$this->validateNotNull('Hash', $value);
$this->validateString('Hash', $value);

if ($this->data['hash'] === $value) {
return;
}

$this->data['hash'] = $value;

$this->setModified('hash');
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
* Set the value of IsAdmin / is_admin.
*
* Must not be null.
* @param $value int
*/
public function setIsAdmin($value)
{
$this->validateNotNull('IsAdmin', $value);
$this->validateInt('IsAdmin', $value);

if ($this->data['is_admin'] === $value) {
return;
}

$this->data['is_admin'] = $value;

$this->setModified('is_admin');
}

/**
* Set the value of IsHidden / is_hidden.
*
* Must not be null.
* @param $value int
*/
public function setIsHidden($value)
{
$this->validateNotNull('IsHidden', $value);
$this->validateInt('IsHidden', $value);

if ($this->data['is_hidden'] === $value) {
return;
}

$this->data['is_hidden'] = $value;

$this->setModified('is_hidden');
}


public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('User', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('User', 'Octo')->getById($value, $useConnection);
}

public static function getByEmail($value, $useConnection = 'read')
{
return Factory::getStore('User', 'Octo')->getByEmail($value, $useConnection);
}


}
