<?php

/**
 * File base store for table: file
 */

namespace Octo\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use Octo\Store;
use Octo\Model\File;

/**
 * File Base Store
 */
class FileStoreBase extends Store
{
    protected $tableName   = 'file';
    protected $modelName   = '\Octo\Model\File';
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

$query = 'SELECT * FROM `file` WHERE `id` = :id LIMIT 1';
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':id', $value);

if ($stmt->execute()) {
if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
return new File($data);
}
}

return null;
}

public function getByCategoryId($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `file` WHERE `category_id` = :category_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':category_id', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `file` WHERE `category_id` = :category_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':category_id', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new File($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}

public function getByUserId($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `file` WHERE `user_id` = :user_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':user_id', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `file` WHERE `user_id` = :user_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':user_id', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new File($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}
}
