<?php

/**
 * Log model for table: log
 */

namespace Octo\System\Model;

use Octo;
use Octo\Store;

/**
 * Log Model
 * @uses Octo\System\Model\Base\LogBaseBase
 */
class Log extends Base\LogBase
{
	const TYPE_CREATE = 2;
    const TYPE_DELETE = 4;
    const TYPE_EDIT = 8;
    const TYPE_PERMISSION = 16;
    const TYPE_ERROR = 32;
    const TYPE_WARNING = 64;
    const TYPE_PUBLISH = 128;

    public static function create($type, $scope, $message, $scopeId = null, $link = null)
    {
        $rtn = new static();
        $rtn->setType($type);
        $rtn->setScope($scope);

        if (!empty($scopeId)) {
            $rtn->setScopeId($scopeId);
        }

        $rtn->setMessage($message);

        if (!empty($link)) {
            $rtn->setLink($link);
        }

        $rtn->setLogDate(new \DateTime());

        if (!empty($_SESSION['user'])) {
            $rtn->setUser($_SESSION['user']);
        }

        $rtn = Store::get('Log')->save($rtn);

        return $rtn;
    }
}
