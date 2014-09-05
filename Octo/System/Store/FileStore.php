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
        $query = 'SELECT * FROM file WHERE scope ';

        if ($scope == 'images') {
            $query .= ' <> "files" '; //scopes: images, shop, category
        } else {
            $query .= ' = :scope ';
        }
        $query .= ' ORDER BY ' . $order;

        $stmt = Database::getConnection('read')->prepare($query);
        if ($scope != 'images') {
            $stmt->bindParam(':scope', $scope);
        }

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
                  JOIN item_file
                  ON item_file.file_id = file.id
                  JOIN item
                  ON item.id = item_file.item_id
                  AND item.slug = :slug
                  WHERE scope <> "files"
         ORDER BY file.title';

        $stmt = Database::getConnection('read')->prepare($query);
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
    public function search($scope, $q)
    {
        $query = 'SELECT * FROM file WHERE scope ';

        if ($scope != 'files') {
            $query .= ' <> "files" '; //scopes: images, shop, category
        } else {
            $query .= ' = :scope ';
        }
        $query .= ' AND (title LIKE \'%'.$q.'%\' OR id = \''.$q.'\')';

        $stmt = Database::getConnection('read')->prepare($query);
        if ($scope != 'files') {
            $stmt->bindParam(':scope', $scope);
        }

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
