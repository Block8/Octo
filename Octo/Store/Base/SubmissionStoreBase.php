<?php

/**
 * Submission base store for table: submission
 */

namespace Octo\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use Octo\Store;
use Octo\Model\Submission;

/**
 * Submission Base Store
 */
class SubmissionStoreBase extends Store
{
    protected $tableName   = 'submission';
    protected $modelName   = '\Octo\Model\Submission';
    protected $primaryKey  = 'id';

    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
}

public function getById($value, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$query = 'SELECT * FROM `submission` WHERE `id` = :id LIMIT 1';
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':id', $value);

if ($stmt->execute()) {
if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
return new Submission($data);
}
}

return null;
}

public function getByFormId($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `submission` WHERE `form_id` = :form_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':form_id', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `submission` WHERE `form_id` = :form_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':form_id', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new Submission($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}

public function getByContactId($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `submission` WHERE `contact_id` = :contact_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':contact_id', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `submission` WHERE `contact_id` = :contact_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':contact_id', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new Submission($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}
}
