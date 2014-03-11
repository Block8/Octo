<?php

/**
 * FileDownload base model for table: file_download
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * FileDownload Base Model
 */
class FileDownloadBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'file_download';

    /**
    * @var string
    */
    protected $modelName = 'FileDownload';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'file_id' => null,
        'downloaded' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'file_id' => 'getFileId',
'downloaded' => 'getDownloaded',

// Foreign key getters:
'File' => 'getFile',
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'file_id' => 'setFileId',
'downloaded' => 'setDownloaded',

// Foreign key setters:
'File' => 'setFile',
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
'file_id' => array(
'type' => 'char',
'length' => 32,
'nullable' => true,
'default' => null,
),
'downloaded' => array(
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
'file_id' => array('columns' => 'file_id'),
);

/**
* @var array
*/
public $foreignKeys = array(
'file_download_ibfk_1' => array(
'local_col' => 'file_id',
'update' => 'CASCADE',
'delete' => 'CASCADE',
'table' => 'file',
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
* Get the value of FileId / file_id.
*
* @return string
*/
public function getFileId()
{
$rtn    = $this->data['file_id'];

return $rtn;
}

/**
* Get the value of Downloaded / downloaded.
*
* @return \DateTime
*/
public function getDownloaded()
{
$rtn    = $this->data['downloaded'];

if (!empty($rtn)) {
$rtn    = new \DateTime($rtn);
}

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
* Set the value of FileId / file_id.
*
* @param $value string
*/
public function setFileId($value)
{
$this->validateString('FileId', $value);

if ($this->data['file_id'] === $value) {
return;
}

$this->data['file_id'] = $value;

$this->setModified('file_id');
}

/**
* Set the value of Downloaded / downloaded.
*
* @param $value \DateTime
*/
public function setDownloaded($value)
{
$this->validateDate('Downloaded', $value);

if ($this->data['downloaded'] === $value) {
return;
}

$this->data['downloaded'] = $value;

$this->setModified('downloaded');
}

/**
* Get the File model for this FileDownload by Id.
*
* @uses \Octo\Store\FileStore::getById()
* @uses \Octo\Model\File
* @return \Octo\Model\File
*/
public function getFile()
{
$key = $this->getFileId();

if (empty($key)) {
return null;
}

$cacheKey   = 'Cache.File.' . $key;
$rtn        = $this->cache->get($cacheKey, null);

if (empty($rtn)) {
$rtn    = Factory::getStore('File', 'Octo')->getById($key);
$this->cache->set($cacheKey, $rtn);
}

return $rtn;
}

/**
* Set File - Accepts an ID, an array representing a File or a File model.
*
* @param $value mixed
*/
public function setFile($value)
{
// Is this an instance of File?
if ($value instanceof \Octo\Model\File) {
return $this->setFileObject($value);
}

// Is this an array representing a File item?
if (is_array($value) && !empty($value['id'])) {
return $this->setFileId($value['id']);
}

// Is this a scalar value representing the ID of this foreign key?
return $this->setFileId($value);
}

/**
* Set File - Accepts a File model.
*
* @param $value \Octo\Model\File
*/
public function setFileObject(\Octo\Model\File $value)
{
return $this->setFileId($value->getId());
}


public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('FileDownload', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('FileDownload', 'Octo')->getById($value, $useConnection);
}

public static function getByFileId($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('FileDownload', 'Octo')->getByFileId($value, $limit, $useConnection);
}


}
