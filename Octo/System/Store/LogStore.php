<?php

/**
 * Log store for table: log
 */

namespace Octo\System\Store;

use Octo;
use b8\Database\Query;

/**
 * Log Store
 * @uses Octo\System\Store\Base\LogStoreBase
 */
class LogStore extends Base\LogStoreBase
{
	public function getTimeline()
    {
        return $this->find()
            ->rawWhere('type IN (2, 4, 8, 128)')
            ->order('id', 'DESC')
            ->limit(100)
            ->get();
    }

    public function getLastEntry($scope)
    {
        return $this->where('scope', $scope)
            ->order('id', 'DESC')
            ->first();
    }
}
