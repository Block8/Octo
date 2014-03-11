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
    public static $sleepable = array();

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
    protected $data = array(
        'id' => null,
        'name' => null,
        'template_tag' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'name' => 'getName',
'template_tag' => 'getTemplateTag',

// Foreign key getters:
'' => 'get',
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'name' => 'setName',
'template_tag' => 'setTemplateTag',

// Foreign key setters:
'' => 'set',
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
'name' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
'template_tag' => array(
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
'template_tag' => array('unique' => true, 'columns' => 'template_tag'),
);

/**
* @var array
*/
public $foreignKeys = array(
'menu_ibfk_1' => array(
'local_col' => 'id',
'update' => 'CASCADE',
'delete' => 'CASCADE',
'table' => 'menu_item',
'col' => 'menu_id'
),
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
* Get the value of TemplateTag / template_tag.
*
* @return string
*/
public function getTemplateTag()
{
$rtn    = $this->data['template_tag'];

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

/**
* Get the MenuItem model for this Menu by MenuId.
*
* @uses \Octo\Store\MenuItemStore::getByMenuId()
* @uses \Octo\Model\MenuItem
* @return \Octo\Model\MenuItem
*/
public function get()
{
$key = $this->getId();

if (empty($key)) {
return null;
}

$cacheKey   = 'Cache.MenuItem.' . $key;
$rtn        = $this->cache->get($cacheKey, null);

if (empty($rtn)) {
$rtn    = Factory::getStore('MenuItem', 'Octo')->getByMenuId($key);
$this->cache->set($cacheKey, $rtn);
}

return $rtn;
}

/**
* Set  - Accepts an ID, an array representing a MenuItem or a MenuItem model.
*
* @param $value mixed
*/
public function set($value)
{
// Is this an instance of MenuItem?
if ($value instanceof \Octo\Model\MenuItem) {
return $this->setObject($value);
}

// Is this an array representing a MenuItem item?
if (is_array($value) && !empty($value['menu_id'])) {
return $this->setId($value['menu_id']);
}

// Is this a scalar value representing the ID of this foreign key?
return $this->setId($value);
}

/**
* Set  - Accepts a MenuItem model.
*
* @param $value \Octo\Model\MenuItem
*/
public function setObject(\Octo\Model\MenuItem $value)
{
return $this->setId($value->getMenuId());
}

/**
* Get MenuItem models by MenuId for this Menu.
*
* @uses \Octo\Store\MenuItemStore::getByMenuId()
* @uses \Octo\Model\MenuItem
* @return \Octo\Model\MenuItem[]
*/
public function getMenuMenuItems()
{
return Factory::getStore('MenuItem', 'Octo')->getByMenuId($this->getId());
}



public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('Menu', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('Menu', 'Octo')->getById($value, $useConnection);
}

public static function getByTemplateTag($value, $useConnection = 'read')
{
return Factory::getStore('Menu', 'Octo')->getByTemplateTag($value, $useConnection);
}


}
