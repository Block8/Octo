<?php

/**
 * MenuItem base model for table: menu_item
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * MenuItem Base Model
 */
class MenuItemBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'menu_item';

    /**
    * @var string
    */
    protected $modelName = 'MenuItem';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'menu_id' => null,
        'title' => null,
        'page_id' => null,
        'url' => null,
        'position' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'menu_id' => 'getMenuId',
'title' => 'getTitle',
'page_id' => 'getPageId',
'url' => 'getUrl',
'position' => 'getPosition',

// Foreign key getters:
'Menu' => 'getMenu',
'Page' => 'getPage',
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'menu_id' => 'setMenuId',
'title' => 'setTitle',
'page_id' => 'setPageId',
'url' => 'setUrl',
'position' => 'setPosition',

// Foreign key setters:
'Menu' => 'setMenu',
'Page' => 'setPage',
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
'menu_id' => array(
'type' => 'int',
'length' => 11,
'nullable' => true,
'default' => null,
),
'title' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
'page_id' => array(
'type' => 'char',
'length' => 32,
'nullable' => true,
'default' => null,
),
'url' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
'position' => array(
'type' => 'int',
'length' => 11,
'nullable' => true,
'default' => null,
),
);

/**
* @var array
*/
public $indexes = array(
'PRIMARY' => array('unique' => true, 'columns' => 'id'),
'menu_id' => array('columns' => 'menu_id'),
'page_id' => array('columns' => 'page_id'),
);

/**
* @var array
*/
public $foreignKeys = array(
'menu_item_ibfk_1' => array(
'local_col' => 'menu_id',
'update' => 'CASCADE',
'delete' => 'SET NULL',
'table' => 'menu',
'col' => 'id'
),
'menu_item_ibfk_2' => array(
'local_col' => 'page_id',
'update' => 'CASCADE',
'delete' => 'SET NULL',
'table' => 'page',
'col' => 'id'
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
* Get the value of MenuId / menu_id.
*
* @return int
*/
public function getMenuId()
{
$rtn    = $this->data['menu_id'];

return $rtn;
}

/**
* Get the value of Title / title.
*
* @return string
*/
public function getTitle()
{
$rtn    = $this->data['title'];

return $rtn;
}

/**
* Get the value of PageId / page_id.
*
* @return string
*/
public function getPageId()
{
$rtn    = $this->data['page_id'];

return $rtn;
}

/**
* Get the value of Url / url.
*
* @return string
*/
public function getUrl()
{
$rtn    = $this->data['url'];

return $rtn;
}

/**
* Get the value of Position / position.
*
* @return int
*/
public function getPosition()
{
$rtn    = $this->data['position'];

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
* Set the value of MenuId / menu_id.
*
* @param $value int
*/
public function setMenuId($value)
{
$this->validateInt('MenuId', $value);

if ($this->data['menu_id'] === $value) {
return;
}

$this->data['menu_id'] = $value;

$this->setModified('menu_id');
}

/**
* Set the value of Title / title.
*
* @param $value string
*/
public function setTitle($value)
{
$this->validateString('Title', $value);

if ($this->data['title'] === $value) {
return;
}

$this->data['title'] = $value;

$this->setModified('title');
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
* Set the value of Url / url.
*
* @param $value string
*/
public function setUrl($value)
{
$this->validateString('Url', $value);

if ($this->data['url'] === $value) {
return;
}

$this->data['url'] = $value;

$this->setModified('url');
}

/**
* Set the value of Position / position.
*
* @param $value int
*/
public function setPosition($value)
{
$this->validateInt('Position', $value);

if ($this->data['position'] === $value) {
return;
}

$this->data['position'] = $value;

$this->setModified('position');
}

/**
* Get the Menu model for this MenuItem by Id.
*
* @uses \Octo\Store\MenuStore::getById()
* @uses \Octo\Model\Menu
* @return \Octo\Model\Menu
*/
public function getMenu()
{
$key = $this->getMenuId();

if (empty($key)) {
return null;
}

$cacheKey   = 'Cache.Menu.' . $key;
$rtn        = $this->cache->get($cacheKey, null);

if (empty($rtn)) {
$rtn    = Factory::getStore('Menu', 'Octo')->getById($key);
$this->cache->set($cacheKey, $rtn);
}

return $rtn;
}

/**
* Set Menu - Accepts an ID, an array representing a Menu or a Menu model.
*
* @param $value mixed
*/
public function setMenu($value)
{
// Is this an instance of Menu?
if ($value instanceof \Octo\Model\Menu) {
return $this->setMenuObject($value);
}

// Is this an array representing a Menu item?
if (is_array($value) && !empty($value['id'])) {
return $this->setMenuId($value['id']);
}

// Is this a scalar value representing the ID of this foreign key?
return $this->setMenuId($value);
}

/**
* Set Menu - Accepts a Menu model.
*
* @param $value \Octo\Model\Menu
*/
public function setMenuObject(\Octo\Model\Menu $value)
{
return $this->setMenuId($value->getId());
}

/**
* Get the Page model for this MenuItem by Id.
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

$cacheKey   = 'Cache.Page.' . $key;
$rtn        = $this->cache->get($cacheKey, null);

if (empty($rtn)) {
$rtn    = Factory::getStore('Page', 'Octo')->getById($key);
$this->cache->set($cacheKey, $rtn);
}

return $rtn;
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


public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('MenuItem', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('MenuItem', 'Octo')->getById($value, $useConnection);
}

public static function getByMenuId($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('MenuItem', 'Octo')->getByMenuId($value, $limit, $useConnection);
}

public static function getByPageId($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('MenuItem', 'Octo')->getByPageId($value, $limit, $useConnection);
}


}
