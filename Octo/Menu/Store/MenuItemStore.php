<?php

/**
 * MenuItem store for table: menu_item
 */

namespace Octo\Menu\Store;

use b8\Database;
use Octo;
use Octo\Menu\Model\MenuItem;

/**
 * MenuItem Store
 * @uses Octo\Menu\Store\Base\MenuItemStoreBase
 */
class MenuItemStore extends Octo\Store
{
    use Base\MenuItemStoreBase;

    public function getForMenu($menuId)
    {
        $query = 'SELECT * FROM `menu_item` WHERE `menu_id` = :menu_id ORDER BY `position` ASC';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':menu_id', $menuId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new MenuItem($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
}
