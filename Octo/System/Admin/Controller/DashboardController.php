<?php

namespace Octo\System\Admin\Controller;

use b8\Config;
use Octo\Admin\Controller;
use Octo\Admin\Template;
use Octo\Event;
use Octo\Store;
use Octo\System\Model\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $this->setTitle('Dashboard', $this->config->get('site.name'));
        $this->addBreadcrumb('Dashboard', '/');

        $this->template->timeline = $this->loadTimeline();
        $this->loadDashboardWidgets();
        $this->loadDashboardStatistics();
    }

    protected function loadTimeline()
    {
        $logStore = Store::get('Log');
        $items = $logStore->getTimeline();
        $timeline = [];

        $lastDate = new \DateTime('1970-01-01');

        foreach ($items as $item) {

            if ($item->getLogDate()->format('Y-m-d') != $lastDate->format('Y-m-d')) {
                $date = $item->getLogDate()->format('M j Y');

                $timeline[] = "<li class=\"time-label\">
                                    <span class=\"bg-blue\">
                                        {$date}
                                    </span>
                                </li>";

                $lastDate = $item->getLogDate();
            }

            $keyDate = $item->getLogDate()->format('Y-m-d');
            $key = md5($keyDate .$item->getScope() . '.' . $item->getType() . '.' . $item->getScopeId());

            if (array_key_exists($key, $timeline)) {
                continue;
            }

            $template = 'Dashboard/Timeline/'.$item->getScope();

            if (!Template::exists($template)) {
                $template = 'Dashboard/Timeline/default';
            }

            $template = Template::getAdminTemplate($template);
            $template->icon = 'info';
            $template->item = $item;
            $template->decoded = @json_decode($item->getMessage(), true);

            switch ($item->getType())
            {
                case 2:
                    $template->color = 'green';
                    $template->verb = 'created';
                    break;

                case 4:
                    $template->color = 'red';
                    $template->verb = 'deleted';
                    break;

                case 8:
                    $template->color = 'blue';
                    $template->verb = 'edited';
                    break;

                case 128:
                    $template->color = 'blue';
                    $template->verb = 'published';
                    break;
            }

            switch ($item->getScope())
            {
                case 'user':
                    $template->icon = 'user';
                    break;

                case 'page':
                    $template->icon = 'file-text';
                    break;

                case 'file':
                    $template->icon = 'image';
                    break;

                case 'info':
                    $template->icon = 'user';
                    break;
            }

            $timeline[$key] = $template->render();

            if (count($timeline) >= 20) {
                break;
            }
        }

        return $timeline;
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

        $this->template->widgets = $widgets;
    }

    protected function loadDashboardStatistics()
    {
        $stats = [];

        Event::trigger('DashboardStatistics', $stats);

        $this->template->statistics = $stats;
    }
}
