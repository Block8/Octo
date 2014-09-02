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

    public function getAllForProduct($scope, $slug)
    {
        $query = 'SELECT file.* FROM file
                  LEFT OUTER JOIN item_file
                  ON item_file.file_id = file.id
                  LEFT OUTER JOIN item
                  ON item.id = item_file.item_id
                  WHERE scope = :scope
                  AND item.slug = :slug
         ORDER BY file.title';

        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);
        $stmt->bindParam(':slug', $slug);

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
        $query = 'SELECT * FROM file WHERE scope = :scope AND (title LIKE \'%'.$query.'%\' OR id = \''.$query.'\')';

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
