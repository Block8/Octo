<?php

/**
 * Category store for table: category
 */

namespace Octo\Categories\Store;

use b8\Database;
use Octo;
use Octo\Categories\Model\Category;
use Octo\Store;

/**
 * Category Store
 * @uses Octo\Categories\Store\Base\CategoryStoreBase
 */
class CategoryStore extends Octo\Store
{
    use Base\CategoryStoreBase;

    /**
     * Return all categories for a particular scope
     *
     * @param string $scope
     * @param string $order
     * @param string $requiresPresence Model to require presence of
     * @return array
     */
    public function getAllForScope($scope, $order = 'name ASC', $requiresPresence = null)
    {
        if ($requiresPresence) {
            $store = Store::get($requiresPresence);
            $table = $store->getTableName();
        }

        $query = 'SELECT c.*, IF(c2.id, true, false) AS has_children FROM category c LEFT JOIN category c2 ON c.id = c2.parent_id ';

        if ($requiresPresence) {
            $query .= "LEFT JOIN $table ON $table.category_id = c.id ";
        }

        $query .= 'WHERE c.scope = :scope AND c.parent_id IS NULL ';
        $query .= 'GROUP BY c.id ';
        if ($requiresPresence) {
            $query .= "HAVING COUNT($table.id) > 0 ";
        }
        $query .= 'ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Category($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
    /**
     * Return all categories for a particular parent
     *
     * @param string $parent
     * @param string $order
     * @return array
     */
    public function getAllForParent($parent, $order = 'name ASC')
    {
        $count = null;

        $query = 'SELECT * FROM category WHERE parent_id = :parent ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':parent', $parent);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Category($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    /**
     * Return the names of all categories in a scope as an associative array
     *
     * @param string $scope
     * @param string $order
     * @return array
     */
    public function getNamesForScope($scope, $order = 'c.name ASC')
    {
        $query = "SELECT c.id, c.name, c2.id AS child_id, CONCAT('- ', c2.name) AS child_name
FROM category c
LEFT JOIN category c2 ON c2.parent_id = c.id
LEFT JOIN category c3 ON c3.id = c.id
WHERE c.scope = :scope AND (CONCAT('- ', c2.name) IS NOT NULL OR c.parent_id IS NULL)
ORDER BY c.parent_id ASC, " . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $data = [];
            foreach ($res as $item) {
                $data[$item['id']] = $item['name'];
                if (isset($item['child_id'])) {
                    $data[$item['child_id']] = $item['child_name'];
                }
            }
            return $data;
        } else {
            return array();
        }
    }

    /**
     * Return the names of all parent categories as an associative array
     *
     * @param string $scope
     * @param string $order
     * @return array
     * @note Only works for one level of parents!
     */
    public function getNamesForParents($scope, $order = 'name ASC')
    {
        $query = 'SELECT id, name FROM category WHERE scope = :scope AND parent_id IS NULL ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $data = [];
            foreach ($res as $item) {
                $data[$item['id']] = $item['name'];
            }
            return $data;
        } else {
            return array();
        }
    }

    public function getByScopeAndSlug($scope, $slug)
    {

        $query = 'SELECT * FROM category WHERE scope = :scope AND slug = :slug LIMIT 1';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);
        $stmt->bindParam(':slug', $slug);

        if ($stmt->execute()) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            return new Category($res);
        } else {
            return null;
        }

    }
}
