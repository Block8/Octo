<?php

/**
 * Menu store for table: menu
 */

namespace Octo\Store;

use b8\Database;
use Octo\Model\Menu;
use Octo\Store\Base\MenuStoreBase;

/**
 * Menu Store
 * @uses Octo\Store\Base\MenuStoreBase
 */
class MenuStore extends MenuStoreBase
{
    public function getAll($order = 'name ASC')
    {
        $query = 'SELECT * FROM menu ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Menu($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn);
        } else {
            return array('items' => array());
        }
    }
}
