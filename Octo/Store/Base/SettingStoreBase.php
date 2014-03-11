<?php

/**
 * Setting base store for table: setting
 */

namespace Octo\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use Octo\Store;
use Octo\Model\Setting;

/**
 * Setting Base Store
 */
class SettingStoreBase extends Store
{
    protected $tableName   = 'setting';
    protected $modelName   = '\Octo\Model\Setting';
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

$query = 'SELECT * FROM `setting` WHERE `id` = :id LIMIT 1';
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':id', $value);

if ($stmt->execute()) {
if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
return new Setting($data);
}
}

return null;
}

public function getByKey($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `setting` WHERE `key` = :key' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':key', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `setting` WHERE `key` = :key' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':key', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new Setting($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}

public function getByScope($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `setting` WHERE `scope` = :scope' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':scope', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `setting` WHERE `scope` = :scope' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':scope', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new Setting($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}
}
