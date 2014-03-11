<?php

/**
 * User model for table: user
 */

namespace Octo\Model;

use Octo\Model\Base\UserBase;
use Octo\Store;

/**
 * User Model
 * @uses Octo\Model\Base\UserBase
 */
class User extends UserBase
{
    protected $permissionsArray;

    public function canAccess($uri)
    {
        if ($this->getIsAdmin()) {
            return true;
        }

        if (!isset($this->permissionsArray)) {
            $this->permissionsArray = Store::get('Permission')->getPermissionsArray($this);
        }

        if (array_key_exists($uri, $this->permissionsArray) && $this->permissionsArray[$uri]) {
            return true;
        }

        return false;
    }
}
