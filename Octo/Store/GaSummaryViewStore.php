<?php

/**
 * GaSummaryView store for table: ga_summary_view
 */

namespace Octo\Store;

use b8\Database;
use Octo\Store\Base\GaSummaryViewStoreBase;
use Octo\Model\GaSummaryView;

/**
 * GaSummaryView Store
 * @uses Octo\Store\Base\GaSummaryViewStoreBase
 */
class GaSummaryViewStore extends GaSummaryViewStoreBase
{
    public function getResponsiveMetrics() {
        $query = "SELECT * FROM ga_summary_view
        WHERE metric IN('desktop', 'tablet', 'mobile')";
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new GaSummaryView($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn);
        } else {
            return array('items' => array());
        }
    }
}
