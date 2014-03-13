<?php

/**
 * File store for table: file
 */

namespace Octo\Store;

use b8\Database;
use Octo;
use Octo\Model\File;

/**
 * File Store
 * @uses Octo\Store\Base\FileStoreBase
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
        $count = null;

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

    /**
     * @param $scope
     * @param string $order
     * @return array
     */
    public function search($scope, $query)
    {
        $count = null;

        $query = 'SELECT * FROM file WHERE scope = :scope AND title LIKE \'%'.$query.'%\'';

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
