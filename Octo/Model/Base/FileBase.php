<?php

/**
 * File base model for table: file
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * File Base Model
 */
class FileBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'file';

    /**
    * @var string
    */
    protected $modelName = 'File';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'scope' => null,
        'category_id' => null,
        'filename' => null,
        'title' => null,
        'mime_type' => null,
        'extension' => null,
        'created_date' => null,
        'updated_date' => null,
        'user_id' => null,
        'size' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'scope' => 'getScope',
'category_id' => 'getCategoryId',
'filename' => 'getFilename',
'title' => 'getTitle',
'mime_type' => 'getMimeType',
'extension' => 'getExtension',
'created_date' => 'getCreatedDate',
'updated_date' => 'getUpdatedDate',
'user_id' => 'getUserId',
'size' => 'getSize',

// Foreign key getters:
'Category' => 'getCategory',
'User' => 'getUser',
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'scope' => 'setScope',
'category_id' => 'setCategoryId',
'filename' => 'setFilename',
'title' => 'setTitle',
'mime_type' => 'setMimeType',
'extension' => 'setExtension',
'created_date' => 'setCreatedDate',
'updated_date' => 'setUpdatedDate',
'user_id' => 'setUserId',
'size' => 'setSize',

// Foreign key setters:
'Category' => 'setCategory',
'User' => 'setUser',
);

/**
* @var array
*/
public $columns = array(
'id' => array(
'type' => 'char',
'length' => 32,
'primary_key' => true,
),
'scope' => array(
'type' => 'varchar',
'length' => 50,
'nullable' => true,
'default' => null,
),
'category_id' => array(
'type' => 'int',
'length' => 11,
'nullable' => true,
'default' => null,
),
'filename' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
'title' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
'mime_type' => array(
'type' => 'varchar',
'length' => 50,
'nullable' => true,
'default' => null,
),
'extension' => array(
'type' => 'varchar',
'length' => 10,
'nullable' => true,
'default' => null,
),
'created_date' => array(
'type' => 'datetime',
'nullable' => true,
'default' => null,
),
'updated_date' => array(
'type' => 'datetime',
'nullable' => true,
'default' => null,
),
'user_id' => array(
'type' => 'int',
'length' => 11,
'nullable' => true,
'default' => null,
),
'size' => array(
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
'category_id' => array('columns' => 'category_id'),
'user_id' => array('columns' => 'user_id'),
);

/**
* @var array
*/
public $foreignKeys = array(
'file_ibfk_1' => array(
'local_col' => 'category_id',
'update' => 'CASCADE',
'delete' => 'SET NULL',
'table' => 'category',
'col' => 'id'
),
'file_ibfk_2' => array(
'local_col' => 'user_id',
'update' => 'CASCADE',
'delete' => 'SET NULL',
'table' => 'user',
'col' => 'id'
),
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
* Get the value of CategoryId / category_id.
*
* @return int
*/
public function getCategoryId()
{
$rtn    = $this->data['category_id'];

return $rtn;
}

/**
* Get the value of Filename / filename.
*
* @return string
*/
public function getFilename()
{
$rtn    = $this->data['filename'];

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
* Get the value of MimeType / mime_type.
*
* @return string
*/
public function getMimeType()
{
$rtn    = $this->data['mime_type'];

return $rtn;
}

/**
* Get the value of Extension / extension.
*
* @return string
*/
public function getExtension()
{
$rtn    = $this->data['extension'];

return $rtn;
}

/**
* Get the value of CreatedDate / created_date.
*
* @return \DateTime
*/
public function getCreatedDate()
{
$rtn    = $this->data['created_date'];

if (!empty($rtn)) {
$rtn    = new \DateTime($rtn);
}

return $rtn;
}

/**
* Get the value of UpdatedDate / updated_date.
*
* @return \DateTime
*/
public function getUpdatedDate()
{
$rtn    = $this->data['updated_date'];

if (!empty($rtn)) {
$rtn    = new \DateTime($rtn);
}

return $rtn;
}

/**
* Get the value of UserId / user_id.
*
* @return int
*/
public function getUserId()
{
$rtn    = $this->data['user_id'];

return $rtn;
}

/**
* Get the value of Size / size.
*
* @return int
*/
public function getSize()
{
$rtn    = $this->data['size'];

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
* Set the value of CategoryId / category_id.
*
* @param $value int
*/
public function setCategoryId($value)
{
$this->validateInt('CategoryId', $value);

if ($this->data['category_id'] === $value) {
return;
}

$this->data['category_id'] = $value;

$this->setModified('category_id');
}

/**
* Set the value of Filename / filename.
*
* @param $value string
*/
public function setFilename($value)
{
$this->validateString('Filename', $value);

if ($this->data['filename'] === $value) {
return;
}

$this->data['filename'] = $value;

$this->setModified('filename');
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
* Set the value of MimeType / mime_type.
*
* @param $value string
*/
public function setMimeType($value)
{
$this->validateString('MimeType', $value);

if ($this->data['mime_type'] === $value) {
return;
}

$this->data['mime_type'] = $value;

$this->setModified('mime_type');
}

/**
* Set the value of Extension / extension.
*
* @param $value string
*/
public function setExtension($value)
{
$this->validateString('Extension', $value);

if ($this->data['extension'] === $value) {
return;
}

$this->data['extension'] = $value;

$this->setModified('extension');
}

/**
* Set the value of CreatedDate / created_date.
*
* @param $value \DateTime
*/
public function setCreatedDate($value)
{
$this->validateDate('CreatedDate', $value);

if ($this->data['created_date'] === $value) {
return;
}

$this->data['created_date'] = $value;

$this->setModified('created_date');
}

/**
* Set the value of UpdatedDate / updated_date.
*
* @param $value \DateTime
*/
public function setUpdatedDate($value)
{
$this->validateDate('UpdatedDate', $value);

if ($this->data['updated_date'] === $value) {
return;
}

$this->data['updated_date'] = $value;

$this->setModified('updated_date');
}

/**
* Set the value of UserId / user_id.
*
* @param $value int
*/
public function setUserId($value)
{
$this->validateInt('UserId', $value);

if ($this->data['user_id'] === $value) {
return;
}

$this->data['user_id'] = $value;

$this->setModified('user_id');
}

/**
* Set the value of Size / size.
*
* @param $value int
*/
public function setSize($value)
{
$this->validateInt('Size', $value);

if ($this->data['size'] === $value) {
return;
}

$this->data['size'] = $value;

$this->setModified('size');
}

/**
* Get the Category model for this File by Id.
*
* @uses \Octo\Store\CategoryStore::getById()
* @uses \Octo\Model\Category
* @return \Octo\Model\Category
*/
public function getCategory()
{
$key = $this->getCategoryId();

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
* Set Category - Accepts an ID, an array representing a Category or a Category model.
*
* @param $value mixed
*/
public function setCategory($value)
{
// Is this an instance of Category?
if ($value instanceof \Octo\Model\Category) {
return $this->setCategoryObject($value);
}

// Is this an array representing a Category item?
if (is_array($value) && !empty($value['id'])) {
return $this->setCategoryId($value['id']);
}

// Is this a scalar value representing the ID of this foreign key?
return $this->setCategoryId($value);
}

/**
* Set Category - Accepts a Category model.
*
* @param $value \Octo\Model\Category
*/
public function setCategoryObject(\Octo\Model\Category $value)
{
return $this->setCategoryId($value->getId());
}

/**
* Get the User model for this File by Id.
*
* @uses \Octo\Store\UserStore::getById()
* @uses \Octo\Model\User
* @return \Octo\Model\User
*/
public function getUser()
{
$key = $this->getUserId();

if (empty($key)) {
return null;
}

$cacheKey   = 'Cache.User.' . $key;
$rtn        = $this->cache->get($cacheKey, null);

if (empty($rtn)) {
$rtn    = Factory::getStore('User', 'Octo')->getById($key);
$this->cache->set($cacheKey, $rtn);
}

return $rtn;
}

/**
* Set User - Accepts an ID, an array representing a User or a User model.
*
* @param $value mixed
*/
public function setUser($value)
{
// Is this an instance of User?
if ($value instanceof \Octo\Model\User) {
return $this->setUserObject($value);
}

// Is this an array representing a User item?
if (is_array($value) && !empty($value['id'])) {
return $this->setUserId($value['id']);
}

// Is this a scalar value representing the ID of this foreign key?
return $this->setUserId($value);
}

/**
* Set User - Accepts a User model.
*
* @param $value \Octo\Model\User
*/
public function setUserObject(\Octo\Model\User $value)
{
return $this->setUserId($value->getId());
}


public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('File', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('File', 'Octo')->getById($value, $useConnection);
}

public static function getByCategoryId($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('File', 'Octo')->getByCategoryId($value, $limit, $useConnection);
}

public static function getByUserId($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('File', 'Octo')->getByUserId($value, $limit, $useConnection);
}


}
