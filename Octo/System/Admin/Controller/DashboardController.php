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
        $this->setTitle(Config::getInstance()->get('site.name') . ': Dashboard');
        $this->addBreadcrumb('Dashboard', '/');

        $dashboardOverridden = !Event::trigger('DashboardOverride', $this->view);

        if (!$dashboardOverridden) {
            $this->loadDashboardWidgets();
        }

        return;

        
        $moduleManager = Config::getInstance()->get('ModuleManager');


        
        if($moduleManager->isEnabled('Spider')) {

	    }   

        if ($pageStore && $moduleManager->isEnabled('Pages')) {

        }

        if ($moduleManager->isEnabled('Forms')) {
            $contactStore = Store::get('Contact');

            if ($contactStore) {
                $this->view->contacts = $contactStore->getTotal();
            }

            $submissionStore = Store::get('Submission');

            if ($submissionStore) {
                $this->view->submissions = $submissionStore->getTotal();
                $this->view->latestSubmissions = $submissionStore->getAll(0, 5);
            }
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
}
