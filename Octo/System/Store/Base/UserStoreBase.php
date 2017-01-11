<?php

/**
 * User base store for table: user

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\User;
use Octo\System\Model\UserCollection;
use Octo\System\Store\UserStore;

/**
 * User Base Store
 */
class UserStoreBase extends Store
{
    /** @var UserStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'user';

    /** @var string */
    protected $model = 'Octo\System\Model\User';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return UserStore
     */
    public static function load() : UserStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserStore(Connection::get());
        }

        return self::$instance;
    }

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
