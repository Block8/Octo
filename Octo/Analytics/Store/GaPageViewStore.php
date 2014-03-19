<?php

/**
 * GaPageView store for table: ga_page_view
 */

namespace Octo\Analytics\Store;

use DateTime;
use b8\Database;
use Octo;
use Octo\Analytics\Model\GaPageView;

/**
 * GaPageView Store
 * @uses Octo\Analytics\Store\Base\GaPageViewStoreBase
 */
class GaPageViewStore extends Octo\Store
{
    use Base\GaPageViewStoreBase;

    /**
     * Get the results for the given metric between the given dates
     *
     * @param $metric
     * @param $start
     * @param $end
     * @return array
     */
    public function getMetricBetween($metric, $start, $end)
    {
        $query = "SELECT * FROM ga_page_view
        WHERE metric = :metric AND `date` BETWEEN :start AND :end
        ORDER BY date ASC";
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':metric', $metric);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new GaPageView($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function getLastMonthTotal($metric)
    {
        $today = (new DateTime())->format('Y-m-d');
        $monthAgo = (new DateTime())->modify('-1 month')->format('Y-m-d');

        $query = "SELECT SUM(value) AS total FROM ga_page_view
        WHERE metric = :metric AND `date` BETWEEN :start AND :end";
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':metric', $metric);
        $stmt->bindParam(':start', $monthAgo);
        $stmt->bindParam(':end', $today);

        if ($stmt->execute()) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $res['total'];
        } else {
            return null;
        }

    }
}
