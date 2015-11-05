<?php

/**
 * User model for table: user
 */

namespace Octo\System\Model;

use Octo;
use Octo\Event;
use Octo\Store;

/**
 * User Model
 * @uses Octo\System\Model\Base\UserBaseBase
 */
class User extends Octo\Model
{
    use Base\UserBase;

    protected $permissionsArray;

    public function canAccess($uri)
    {
        $canAccess = null;
        $callbackData = [$this, $uri, $canAccess];
        Event::trigger('canAccess', $callbackData);

        $uri = $callbackData[1];
        $canAccess = $callbackData[2];

        if (!is_null($canAccess)) {
            return $canAccess;
        }

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

    public function getAvatar($size = 100)
    {
        return 'https://www.gravatar.com/avatar/'.md5($this->getEmail()).'?d=mm&s='.$size;
    }
}
