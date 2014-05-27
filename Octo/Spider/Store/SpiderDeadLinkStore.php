<?php

/**
 * DeadLink store for table: spider_dead_link */

namespace Octo\Spider\Store;

use b8\Database;
use Octo;
use Octo\Spider\Model\SpiderDeadLink;

/**
 * ShopBasket Store
 */
class SpiderDeadLinkStore extends Octo\Store
{
    use Base\SpiderDeadLinkStoreBase;

    public function getAll()
    {
        $query = 'SELECT * FROM spider_dead_link';

        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new SpiderDeadLink($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function truncate()
    {
        $query = 'TRUNCATE TABLE spider_dead_link;';
        $stmt = Database::getConnection('write')->prepare($query);
        $stmt->execute();
    }
}
