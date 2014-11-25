<?php

/**
 * File store for table: file
 */

namespace Octo\System\Store;

use b8\Database;
use Octo;
use Octo\System\Model\File;

/**
 * File Store
 * @uses Octo\System\Store\Base\FileStoreBase
 */
class FileStore extends Octo\Store
{
    use Base\FileStoreBase;

    /**
     * @param $scope
     * @param string $order
     * @return array
     */
    public function getAllForScope($scope, $order = 'title ASC')
    {
        $query = 'SELECT * FROM file WHERE scope = :scope ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new File($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function getAllForScopeSince($scope, $date, $order = 'title ASC')
    {
        $query = 'SELECT * FROM file WHERE scope = :scope AND created_date > :date ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);
        $stmt->bindParam(':date', $date->format('Y-m-d H:i:s'));

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new File($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    /**
     * @param $scope
     * @param string $order
     * @return array
     */
    public function search($scope, $query)
    {
        $query = 'SELECT * FROM file
                    WHERE scope = :scope
                        AND (title LIKE \'%'.$query.'%\' OR id = \''.$query.'\')
                    ORDER BY title ASC';

        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new File($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
}
