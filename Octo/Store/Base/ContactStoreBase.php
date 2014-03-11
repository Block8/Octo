<?php

/**
 * Contact base store for table: contact
 */

namespace Octo\Store\Base;

use b8\Database;
use b8\Exception\HttpException;
use Octo\Store;
use Octo\Model\Contact;

/**
 * Contact Base Store
 */
class ContactStoreBase extends Store
{
    protected $tableName   = 'contact';
    protected $modelName   = '\Octo\Model\Contact';
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

$query = 'SELECT * FROM `contact` WHERE `id` = :id LIMIT 1';
$stmt = Database::getConnection($useConnection)->prepare($query);
$stmt->bindValue(':id', $value);

if ($stmt->execute()) {
if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
return new Contact($data);
}
}

return null;
}
}
