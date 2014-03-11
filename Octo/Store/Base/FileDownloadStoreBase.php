<?php

/**
 * FileDownload base store for table: file_download
 */

namespace Octo\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use Octo\Store;
use Octo\Model\FileDownload;

/**
 * FileDownload Base Store
 */
class FileDownloadStoreBase extends Store
{
    protected $tableName   = 'file_download';
    protected $modelName   = '\Octo\Model\FileDownload';
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

$query = 'SELECT * FROM `file_download` WHERE `id` = :id LIMIT 1';
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':id', $value);

if ($stmt->execute()) {
if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
return new FileDownload($data);
}
}

return null;
}

public function getByFileId($value, $limit = null, $useConnection = 'read')
{
if (is_null($value)) {
throw new HttpException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
}

$add = '';

if ($limit) {
$add .= ' LIMIT ' . $limit;
}

$query = 'SELECT COUNT(*) AS cnt FROM `file_download` WHERE `file_id` = :file_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':file_id', $value);

if ($stmt->execute()) {
$res    = $stmt->fetch(\PDO::FETCH_ASSOC);
$count  = (int)$res['cnt'];
} else {
$count = 0;
}

$query = 'SELECT * FROM `file_download` WHERE `file_id` = :file_id' . $add;
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':file_id', $value);

if ($stmt->execute()) {
$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

$map = function ($item) {
return new FileDownload($item);
};
$rtn = array_map($map, $res);

return array('items' => $rtn, 'count' => $count);
} else {
return array('items' => array(), 'count' => 0);
}
}
}
