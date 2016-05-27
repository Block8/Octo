<?php

/**
 * User store for table: user
 */

namespace Octo\System\Store;

use b8\Database;
use b8\Database\Query;
use Octo;
use Octo\System\Model\User;

/**
 * User Store
 * @uses Octo\System\Store\Base\UserStoreBase
 */
class UserStore extends Octo\Store
{
    use Base\UserStoreBase;

    public function getAll($order = 'name ASC')
    {
        $query = 'SELECT * FROM user WHERE `is_hidden` = 0 ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new User($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function getRecentUsers()
    {
        $query = 'SELECT * FROM `user` WHERE UNIX_TIMESTAMP(date_active) > (UNIX_TIMESTAMP() - 3600) ORDER BY date_active DESC';
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new User($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
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

    public function search($query)
    {
        $query = 'SELECT * FROM `user` WHERE `name` LIKE \'%'.$query.'%\'';

        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new User($item);
            };

            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
}
