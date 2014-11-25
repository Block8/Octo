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
class LogStore extends Octo\Store
{
    use Base\LogStoreBase;

    public function getTimeline()
    {
        $query = new Query($this->getNamespace('Log').'\Model\Log', 'read');
        $query->select('*')->from('log')->limit(25);
        $query->where('type IN (2, 4, 8, 128)');
        $query->order('id', 'DESC');

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Log by Id', 0, $ex);
        }
    }

    public function getLastEntry($scope)
    {
        $query = new Query($this->getNamespace('Log').'\Model\Log', 'read');
        $query->select('*')->from('log')->limit(1);
        $query->where('scope = :scope');
        $query->bind(':scope', $scope);
        $query->order('id', 'DESC');

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Log by Id', 0, $ex);
        }
    }
}
