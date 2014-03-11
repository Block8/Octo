<?php

/**
 * User store for table: user
 */

namespace Octo\Store;

use b8\Database;
use Octo\Store\Base\UserStoreBase;
use Octo\Model\User;

/**
 * User Store
 * @uses Octo\Store\Base\UserStoreBase
 */
class UserStore extends UserStoreBase
{
    public function getAll($order = 'name ASC')
    {
        $count = null;

        $query = 'SELECT * FROM user WHERE `is_hidden` = 0 ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new User($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        } else {
            return array('items' => array(), 'count' => 0);
        }
    }
    
    public function getNames()
    {
        $query = 'SELECT id, name FROM user WHERE `is_hidden` = 0 ORDER BY name ASC';
        $stmt = Database::getConnection('read')->prepare($query);

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
}
