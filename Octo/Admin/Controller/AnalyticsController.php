<?php

namespace Octo\Admin\Controller;

use \DateTime;
use Octo\Admin\Controller;
use Octo\Store;
use Octo\Model\GaPageView;
use Octo\Store\GaPageViewStore;

class AnalyticsController extends Controller
{
    /**
     * @var \Octo\Store\GaTopPageStore
     */
    protected $gaTopPageStore;

    /**
     * @var \Octo\Store\GaPageViewStore
     */
    protected $gaPageViewStore;

    /**
     * @var \Octo\Store\GaSummaryViewStore
     */
    protected $gaSummaryViewStore;

    public function init()
    {
        $this->gaTopPageStore = Store::get('GaTopPage');
        $this->gaPageViewStore = Store::get('GaPageView');
        $this->gaSummaryViewStore = Store::get('GaSummaryView');
    }

    public function responsive()
    {
        $data = $this->gaSummaryViewStore->getResponsiveMetrics();

        $return = [];
        $total = 0;

        foreach ($data['items'] as $item) {
            $return[] = ['percentage' => 0, 'count' => $item->getValue()];
            $total += $item->getValue();
        }

        foreach ($return as &$item) {
            $item['percentage'] = number_format(($item['count'] / $total) * 100, 1);
        }

        print json_encode($return);
        exit;
    }

    public function topPages()
    {
        $pages = $this->gaTopPageStore->getPages(5, 'pageviews');

        $data = [];
        $maxValue = $this->gaPageViewStore->getLastMonthTotal('pageviews');

        foreach ($pages['items'] as $item) {
            $page = $item->getPage();
            if (isset($page)) {
                $pageName = $page->getCurrentVersion()->getTitle();
            } else {
                $pageName = ltrim($item->getUri(), '/');
            }

            $data[] = [
                'percentage' => number_format((100 / $maxValue) * $item->getPageviews(), 1),
                'metric' => $item->getPageviews(),
                'uri' => $item->getUri(),
                'name' => $pageName,
            ];
        }

        print json_encode($data);
        exit;
    }

    public function topUniquePages()
    {
        $pages = $this->gaTopPageStore->getPages(5, 'unique_pageviews');

        $data = [];
        $maxValue = $this->gaPageViewStore->getLastMonthTotal('uniquePageviews');

        foreach ($pages['items'] as $item) {
            $page = $item->getPage();
            if (isset($page)) {
                $pageName = $page->getCurrentVersion()->getTitle();
            } else {
                $pageName = ltrim($item->getUri(), '/');
            }

            $data[] = [
                'percentage' => number_format((100 / $maxValue) * $item->getUniquePageviews(), 1),
                'metric' => $item->getUniquePageviews(),
                'uri' => $item->getUri(),
                'name' => $pageName,
            ];
        }

        print json_encode($data);
        exit;
    }

    public function metric($metric)
    {
        $start = $this->getParam('start_date');
        $end = $this->getParam('end_date');

        if ($end == null) {
            $end = new DateTime();
            $end = $end->format('Y-m-d');
        }
        if ($start == null) {
            $start = new DateTime();
            $start = $start->modify('-30 days')->format('Y-m-d');
        }

        $store = Store::get('GaPageView');
        $data = $store->getMetricBetween($metric, $start, $end);

        $ticks = [];
        $tickCount = 0;
        $maxValue = 0;
        $return = [];
        foreach ($data['items'] as $day) {
            $value = $day->getValue();
            $return[] = ['date' => $day->getDate()->format('d-m-Y'), 'value' => $value];

            // Reset maximum value
            if ($value > $maxValue) {
               $maxValue = $value;
            }

            // Add every third day to the ticks
            if ($tickCount % 3 == 0) {
                $ticks[$tickCount] = $day->getDate()->format('d/m');
            }
            $tickCount++;
        }

        $maxValue = $this->cleverRound($maxValue);

        array_unshift($return, ['max' => $maxValue, 'ticks' => $ticks]);

        print json_encode($return);
        exit;
    }

    protected function cleverRound($maxValue)
    {
        $digits = strlen($maxValue);
        $base = intval(substr($maxValue, 0, 1)) + 1;
        $maxValue = $base . str_repeat('0', $digits - 1);
        return $maxValue;
    }
}
