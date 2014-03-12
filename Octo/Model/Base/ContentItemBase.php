<?php

/**
 * ContentItem base model for table: content_item
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * ContentItem Base Model
 */
class ContentItemBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'content_item';

    /**
    * @var string
    */
    protected $modelName = 'ContentItem';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'content' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'content' => 'getContent',

// Foreign key getters:
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'content' => 'setContent',

// Foreign key setters:
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
'content' => array(
'type' => 'longtext',
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
* Get the value of Content / content.
*
* @return string
*/
public function getContent()
{
$rtn    = $this->data['content'];

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
* Set the value of Content / content.
*
* Must not be null.
* @param $value string
*/
public function setContent($value)
{
$this->validateNotNull('Content', $value);
$this->validateString('Content', $value);

if ($this->data['content'] === $value) {
return;
}

$this->data['content'] = $value;

$this->setModified('content');
}

/**
* Get Article models by ContentItemId for this ContentItem.
*
* @uses \Octo\Store\ArticleStore::getByContentItemId()
* @uses \Octo\Model\Article
* @return \Octo\Model\Article[]
*/
public function getContentItemArticles()
{
return Factory::getStore('Article', 'Octo')->getByContentItemId($this->getId());
}

/**
* Get PageVersion models by ContentItemId for this ContentItem.
*
* @uses \Octo\Store\PageVersionStore::getByContentItemId()
* @uses \Octo\Model\PageVersion
* @return \Octo\Model\PageVersion[]
*/
public function getContentItemPageVersions()
{
return Factory::getStore('PageVersion', 'Octo')->getByContentItemId($this->getId());
}



public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('ContentItem', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('ContentItem', 'Octo')->getById($value, $useConnection);
}


}
