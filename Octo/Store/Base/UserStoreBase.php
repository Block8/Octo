<?php

/**
 * User base store for table: user
 */

namespace Octo\Store\Base;

use PDOException;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Model\User;

/**
 * User Base Store
 */
class UserStoreBase extends Store
{
    protected $tableName   = 'user';
    protected $modelName   = '\Octo\Model\User';
    protected $primaryKey  = 'id';

    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return User
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return User
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\User', $useConnection);
        $query->select('*')->from('user')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get User by User', 0, $ex);
        }
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return User
    */
    public function getByEmail($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\User', $useConnection);
        $query->select('*')->from('user')->limit(1);
        $query->where('`email` = :email');
        $query->bind(':email', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get User by User', 0, $ex);
        }
    }
}
