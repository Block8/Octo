<?php

/**
 * Tweet base model for table: tweet
 */

namespace Octo\Model\Base;

use Octo\Model;
use b8\Store\Factory;

/**
 * Tweet Base Model
 */
class TweetBase extends Model
{
    /**
    * @var array
    */
    public static $sleepable = array();

    /**
    * @var string
    */
    protected $tableName = 'tweet';

    /**
    * @var string
    */
    protected $modelName = 'Tweet';

    /**
    * @var array
    */
    protected $data = array(
        'id' => null,
        'twitter_id' => null,
        'text' => null,
        'html_text' => null,
        'screenname' => null,
        'posted' => null,
        'created_date' => null,
        'scope' => null,
);

/**
* @var array
*/
protected $getters = array(
// Direct property getters:
'id' => 'getId',
'twitter_id' => 'getTwitterId',
'text' => 'getText',
'html_text' => 'getHtmlText',
'screenname' => 'getScreenname',
'posted' => 'getPosted',
'created_date' => 'getCreatedDate',
'scope' => 'getScope',

// Foreign key getters:
);

/**
* @var array
*/
protected $setters = array(
// Direct property setters:
'id' => 'setId',
'twitter_id' => 'setTwitterId',
'text' => 'setText',
'html_text' => 'setHtmlText',
'screenname' => 'setScreenname',
'posted' => 'setPosted',
'created_date' => 'setCreatedDate',
'scope' => 'setScope',

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
'twitter_id' => array(
'type' => 'varchar',
'length' => 50,
'nullable' => true,
'default' => null,
),
'text' => array(
'type' => 'varchar',
'length' => 255,
'nullable' => true,
'default' => null,
),
'html_text' => array(
'type' => 'text',
'nullable' => true,
'default' => null,
),
'screenname' => array(
'type' => 'varchar',
'length' => 50,
'nullable' => true,
'default' => null,
),
'posted' => array(
'type' => 'datetime',
'nullable' => true,
'default' => null,
),
'created_date' => array(
'type' => 'datetime',
'nullable' => true,
'default' => null,
),
'scope' => array(
'type' => 'varchar',
'length' => 50,
'nullable' => true,
'default' => null,
),
);

/**
* @var array
*/
public $indexes = array(
'PRIMARY' => array('unique' => true, 'columns' => 'id'),
'twitter_id' => array('unique' => true, 'columns' => 'twitter_id'),
'screenname' => array('columns' => 'screenname'),
'scope' => array('columns' => 'scope'),
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
* Get the value of TwitterId / twitter_id.
*
* @return string
*/
public function getTwitterId()
{
$rtn    = $this->data['twitter_id'];

return $rtn;
}

/**
* Get the value of Text / text.
*
* @return string
*/
public function getText()
{
$rtn    = $this->data['text'];

return $rtn;
}

/**
* Get the value of HtmlText / html_text.
*
* @return string
*/
public function getHtmlText()
{
$rtn    = $this->data['html_text'];

return $rtn;
}

/**
* Get the value of Screenname / screenname.
*
* @return string
*/
public function getScreenname()
{
$rtn    = $this->data['screenname'];

return $rtn;
}

/**
* Get the value of Posted / posted.
*
* @return \DateTime
*/
public function getPosted()
{
$rtn    = $this->data['posted'];

if (!empty($rtn)) {
$rtn    = new \DateTime($rtn);
}

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
* Set the value of TwitterId / twitter_id.
*
* @param $value string
*/
public function setTwitterId($value)
{
$this->validateString('TwitterId', $value);

if ($this->data['twitter_id'] === $value) {
return;
}

$this->data['twitter_id'] = $value;

$this->setModified('twitter_id');
}

/**
* Set the value of Text / text.
*
* @param $value string
*/
public function setText($value)
{
$this->validateString('Text', $value);

if ($this->data['text'] === $value) {
return;
}

$this->data['text'] = $value;

$this->setModified('text');
}

/**
* Set the value of HtmlText / html_text.
*
* @param $value string
*/
public function setHtmlText($value)
{
$this->validateString('HtmlText', $value);

if ($this->data['html_text'] === $value) {
return;
}

$this->data['html_text'] = $value;

$this->setModified('html_text');
}

/**
* Set the value of Screenname / screenname.
*
* @param $value string
*/
public function setScreenname($value)
{
$this->validateString('Screenname', $value);

if ($this->data['screenname'] === $value) {
return;
}

$this->data['screenname'] = $value;

$this->setModified('screenname');
}

/**
* Set the value of Posted / posted.
*
* @param $value \DateTime
*/
public function setPosted($value)
{
$this->validateDate('Posted', $value);

if ($this->data['posted'] === $value) {
return;
}

$this->data['posted'] = $value;

$this->setModified('posted');
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


public static function getByPrimaryKey($value, $useConnection = 'read')
{
return Factory::getStore('Tweet', 'Octo')->getByPrimaryKey($value, $useConnection);
}


public static function getById($value, $useConnection = 'read')
{
return Factory::getStore('Tweet', 'Octo')->getById($value, $useConnection);
}

public static function getByTwitterId($value, $useConnection = 'read')
{
return Factory::getStore('Tweet', 'Octo')->getByTwitterId($value, $useConnection);
}

public static function getByScreenname($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('Tweet', 'Octo')->getByScreenname($value, $limit, $useConnection);
}

public static function getByScope($value, $limit = null, $useConnection = 'read')
{
return Factory::getStore('Tweet', 'Octo')->getByScope($value, $limit, $useConnection);
}


}
