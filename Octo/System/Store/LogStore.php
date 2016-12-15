<?php

/**
 * Log store for table: log
 */

namespace Octo\System\Store;

use Block8\Database\Connection;
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

    public function removeOldLogs() : bool
    {
        $cutoffDate = (new \DateTime())->modify('-90 days');

        $stmt = Connection::get()->prepare('DELETE FROM log WHERE log_date > :cutoff');
        $stmt->bindValue(':cutoff', $cutoffDate->format('Y-m-d H:i'), \PDO::PARAM_STR);

        return $stmt->execute();
    }
}
