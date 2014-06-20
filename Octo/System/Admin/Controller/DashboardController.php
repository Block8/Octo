<?php

namespace Octo\System\Admin\Controller;

use b8\Config;
use Octo\Admin\Controller;
use Octo\Event;
use Octo\Store;
use Octo\System\Model\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $this->setTitle('Dashboard', $this->config->get('site.name'));
        $this->addBreadcrumb('Dashboard', '/');

        $dashboardOverridden = !Event::trigger('DashboardOverride', $this->view);

        if (!$dashboardOverridden) {
            $this->loadDashboardWidgets();
            $this->loadDashboardStatistics();
        }
    }

    protected function loadDashboardWidgets()
    {
        $widgets = [];

        Event::trigger('DashboardWidgets', $widgets);

        uasort($widgets, function ($item1, $item2) {
            if ($item1['order'] > $item2['order']) {
                return 1;
            }

            if ($item2['order'] < $item1['order']) {
                return -1;
            }

            return 0;
        });

        $this->view->widgets = $widgets;
    }

    protected function loadDashboardStatistics()
    {
        $stats = [];

        Event::trigger('DashboardStatistics', $stats);

        $this->view->statistics = $stats;
    }
}
