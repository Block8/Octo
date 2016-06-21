<?php

/**
 * User base store for table: user

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\User;
use Octo\System\Model\UserCollection;

/**
 * User Base Store
 */
class UserStoreBase extends Store
{
    protected $table = 'user';
    protected $model = 'Octo\System\Model\User';
    protected $key = 'id';

    /**
    * @param $value
    * @return User|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a User object by Id.
     * @param $value
     * @return User|null
     */
    public function getById(int $value)
    {
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->cacheGet($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }

        $rtn = $this->where('id', $value)->first();
        $this->cacheSet($value, $rtn);

        return $rtn;
    }

    /**
     * Get a User object by Email.
     * @param $value
     * @return User|null
     */
    public function getByEmail(string $value)
    {
        return $this->where('email', $value)->first();
    }
}
