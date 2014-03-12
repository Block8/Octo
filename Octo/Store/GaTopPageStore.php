<?php

/**
 * GaTopPage store for table: ga_top_page
 */

namespace Octo\Store;

use b8\Database;
use Octo\Store\Base\GaTopPageStoreBase;
use Octo\Model\GaTopPage;

/**
 * GaTopPage Store
 * @uses Octo\Store\Base\GaTopPageStoreBase
 */
class GaTopPageStore extends GaTopPageStoreBase
{
    public function getPages($limit = 5, $order = 'pageviews') {
        $query = "SELECT * FROM ga_top_page ORDER BY $order DESC LIMIT $limit";
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new GaTopPage($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn);
        } else {
            return array('items' => array());
        }
    }
}
