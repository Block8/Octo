<?php

/**
 * MenuItem base store for table: menu_item
 */

namespace Octo\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use Octo\Store;
use Octo\Model\MenuItem;

/**
 * MenuItem Base Store
 */
class MenuItemStoreBase extends Store
{
    protected $tableName   = 'menu_item';
    protected $modelName   = '\Octo\Model\MenuItem';
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

$query = 'SELECT * FROM `menu_item` WHERE `id` = :id LIMIT 1';
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':id', $value);

if ($stmt->execute()) {
if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
return new MenuItem($data);
}
}

return null;
}

public function getByMenuId($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `menu_item` WHERE `menu_id` = :menu_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':menu_id', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `menu_item` WHERE `menu_id` = :menu_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':menu_id', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new MenuItem($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}

public function getByPageId($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `menu_item` WHERE `page_id` = :page_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':page_id', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `menu_item` WHERE `page_id` = :page_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':page_id', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new MenuItem($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}
}
