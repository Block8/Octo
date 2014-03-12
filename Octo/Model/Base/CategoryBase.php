<?php

/**
 * Category base model for table: category
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Category Base Model
 */
class CategoryBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'category';

    /**
    * @var string
    */
    protected $modelName = 'Category';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'name' => null,
        'slug' => null,
        'scope' => null,
        'parent_id' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'name' => 'getName',
'slug' => 'getSlug',
'scope' => 'getScope',
'parent_id' => 'getParentId',

// Foreign key getters:
'Parent' => 'getParent',
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'name' => 'setName',
'slug' => 'setSlug',
'scope' => 'setScope',
'parent_id' => 'setParentId',

// Foreign key setters:
'Parent' => 'setParent',
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
'slug' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
'scope' => array(
'type' => 'varchar',
'length' => 100,
'nullable' => true,
'default' => null,
),
'parent_id' => array(
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
'parent_id' => array('columns' => 'parent_id'),
'scope' => array('columns' => 'scope'),
);

/**
* @var array
*/
public $foreignKeys = array(
'category_ibfk_1' => array(
'local_col' => 'parent_id',
'update' => 'CASCADE',
'delete' => 'SET NULL',
'table' => 'category',
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
* Get the value of Slug / slug.
*
* @return string
*/
public function getSlug()
{
$rtn    = $this->data['slug'];

return $rtn;
}

/**
* Get the value of Scope / scope.
*
* @return string
*/
public function getScope()
{
$rtn    = $this->data['scope'];

return $rtn;
}

/**
* Get the value of ParentId / parent_id.
*
* @return int
*/
public function getParentId()
{
$rtn    = $this->data['parent_id'];

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
* Set the value of Slug / slug.
*
* @param $value string
*/
public function setSlug($value)
{
$this->validateString('Slug', $value);

if ($this->data['slug'] === $value) {
return;
}

$this->data['slug'] = $value;

$this->setModified('slug');
}

/**
* Set the value of Scope / scope.
*
* @param $value string
*/
public function setScope($value)
{
$this->validateString('Scope', $value);

if ($this->data['scope'] === $value) {
return;
}

$this->data['scope'] = $value;

$this->setModified('scope');
}

/**
* Set the value of ParentId / parent_id.
*
* @param $value int
*/
public function setParentId($value)
{
$this->validateInt('ParentId', $value);

if ($this->data['parent_id'] === $value) {
return;
}

$this->data['parent_id'] = $value;

$this->setModified('parent_id');
}

/**
* Get the Category model for this Category by Id.
*
* @uses \Octo\Store\CategoryStore::getById()
* @uses \Octo\Model\Category
* @return \Octo\Model\Category
*/
public function getParent()
{
$key = $this->getParentId();

if (empty($key)) {
return null;
}

$cacheKey   = 'Cache.Category.' . $key;
$rtn        = $this->cache->get($cacheKey, null);

if (empty($rtn)) {
$rtn    = Factory::getStore('Category', 'Octo')->getById($key);
$this->cache->set($cacheKey, $rtn);
}

return $rtn;
}

/**
* Set Parent - Accepts an ID, an array representing a Category or a Category model.
*
* @param $value mixed
*/
public function setParent($value)
{
// Is this an instance of Category?
if ($value instanceof \Octo\Model\Category) {
return $this->setParentObject($value);
}

// Is this an array representing a Category item?
if (is_array($value) && !empty($value['id'])) {
return $this->setParentId($value['id']);
}

// Is this a scalar value representing the ID of this foreign key?
return $this->setParentId($value);
}

/**
* Set Parent - Accepts a Category model.
*
* @param $value \Octo\Model\Category
*/
public function setParentObject(\Octo\Model\Category $value)
{
return $this->setParentId($value->getId());
}

/**
* Get Article models by CategoryId for this Category.
*
* @uses \Octo\Store\ArticleStore::getByCategoryId()
* @uses \Octo\Model\Article
* @return \Octo\Model\Article[]
*/
public function getCategoryArticles()
{
return Factory::getStore('Article', 'Octo')->getByCategoryId($this->getId());
}

/**
* Get Category models by ParentId for this Category.
*
* @uses \Octo\Store\CategoryStore::getByParentId()
* @uses \Octo\Model\Category
* @return \Octo\Model\Category[]
*/
public function getParentCategorys()
{
return Factory::getStore('Category', 'Octo')->getByParentId($this->getId());
}

/**
* Get Event models by Topic for this Category.
*
* @uses \Octo\Store\EventStore::getByTopic()
* @uses \Octo\Model\Event
* @return \Octo\Model\Event[]
*/
public function getEventEvents()
{
return Factory::getStore('Event', 'Octo')->getByTopic($this->getId());
}

/**
* Get Event models by LocationId for this Category.
*
* @uses \Octo\Store\EventStore::getByLocationId()
* @uses \Octo\Model\Event
* @return \Octo\Model\Event[]
*/
public function getLocationEvents()
{
return Factory::getStore('Event', 'Octo')->getByLocationId($this->getId());
}

/**
* Get Faq models by CategoryId for this Category.
*
* @uses \Octo\Store\FaqStore::getByCategoryId()
* @uses \Octo\Model\Faq
* @return \Octo\Model\Faq[]
*/
public function getCategoryFaqs()
{
return Factory::getStore('Faq', 'Octo')->getByCategoryId($this->getId());
}

/**
* Get File models by CategoryId for this Category.
*
* @uses \Octo\Store\FileStore::getByCategoryId()
* @uses \Octo\Model\File
* @return \Octo\Model\File[]
*/
public function getCategoryFiles()
{
return Factory::getStore('File', 'Octo')->getByCategoryId($this->getId());
}

/**
* Get Network models by BusinessTypeId for this Category.
*
* @uses \Octo\Store\NetworkStore::getByBusinessTypeId()
* @uses \Octo\Model\Network
* @return \Octo\Model\Network[]
*/
public function getBusinessTypeNetworks()
{
return Factory::getStore('Network', 'Octo')->getByBusinessTypeId($this->getId());
}

/**
* Get Network models by LocationId for this Category.
*
* @uses \Octo\Store\NetworkStore::getByLocationId()
* @uses \Octo\Model\Network
* @return \Octo\Model\Network[]
*/
public function getLocationNetworks()
{
return Factory::getStore('Network', 'Octo')->getByLocationId($this->getId());
}

/**
* Get NetworkContact models by BusinessTypeId for this Category.
*
* @uses \Octo\Store\NetworkContactStore::getByBusinessTypeId()
* @uses \Octo\Model\NetworkContact
* @return \Octo\Model\NetworkContact[]
*/
public function getBusinessTypeNetworkContacts()
{
return Factory::getStore('NetworkContact', 'Octo')->getByBusinessTypeId($this->getId());
}



public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('Category', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('Category', 'Octo')->getById($value, $useConnection);
}

public static function getByScope($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('Category', 'Octo')->getByScope($value, $limit, $useConnection);
}

public static function getByParentId($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('Category', 'Octo')->getByParentId($value, $limit, $useConnection);
}


}
