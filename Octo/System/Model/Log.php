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
class Log extends Octo\Model
{
    use Base\LogBase;

    const TYPE_CREATE = 2;
    const TYPE_DELETE = 4;
    const TYPE_EDIT = 8;
    const TYPE_PERMISSION = 16;
    const TYPE_ERROR = 32;
    const TYPE_WARNING = 64;

    public static function create($type, $scope, $message)
    {
        $rtn = new static();
        $rtn->setType($type);
        $rtn->setScope($scope);
        $rtn->setMessage($message);
        $rtn->setLogDate(new \DateTime());

        return $rtn;
    }

    public function save()
    {
        Store::get('Log')->save($this);
    }
}
