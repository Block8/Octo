<?php

/**
 * Contact base store for table: contact
 */

namespace Octo\Store\Base;

use PDOException;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
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

    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Contact
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Contact
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\Contact', $useConnection);
        $query->select('*')->from('contact')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Contact by Contact', 0, $ex);
        }
    }
}
