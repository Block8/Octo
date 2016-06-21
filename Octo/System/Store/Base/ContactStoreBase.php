<?php

/**
 * Contact base store for table: contact

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\Contact;
use Octo\System\Model\ContactCollection;

/**
 * Contact Base Store
 */
class ContactStoreBase extends Store
{
    protected $table = 'contact';
    protected $model = 'Octo\System\Model\Contact';
    protected $key = 'id';

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
