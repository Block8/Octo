<?php

/**
 * Contact base store for table: contact

 */

namespace Octo\System\Store\Base;

use Block8\Database\Connection;
use Octo\Store;
use Octo\System\Model\Contact;
use Octo\System\Model\ContactCollection;
use Octo\System\Store\ContactStore;

/**
 * Contact Base Store
 */
class ContactStoreBase extends Store
{
    /** @var ContactStore $instance */
    protected static $instance = null;

    /** @var string */
    protected $table = 'contact';

    /** @var string */
    protected $model = 'Octo\System\Model\Contact';

    /** @var string */
    protected $key = 'id';

    /**
     * Return the database store for this model.
     * @return ContactStore
     */
    public static function load() : ContactStore
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContactStore(Connection::get());
        }

        return self::$instance;
    }

    /**
    * @param $value
    * @return Contact|null
    */
    public function getByPrimaryKey($value)
    {
        return $this->getById($value);
    }


    /**
     * Get a Contact object by Id.
     * @param $value
     * @return Contact|null
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
     * Get a Contact object by Email.
     * @param $value
     * @return Contact|null
     */
    public function getByEmail(string $value)
    {
        return $this->where('email', $value)->first();
    }
}
