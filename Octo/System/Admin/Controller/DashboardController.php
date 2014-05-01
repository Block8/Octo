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
        
        $moduleManager = Config::getInstance()->get('ModuleManager');

        $pageStore = Store::get('Page');

        if ($pageStore && $moduleManager->isEnabled('Pages')) {
            $this->view->pages = $pageStore->getTotal();
            $this->view->latestPages = $pageStore->getLatest(5);
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

        $this->view->showAnalytics = false;
        if (Setting::get('analytics', 'ga_email') != '') {
            $this->view->showAnalytics = true;
        }
        
        $instance = clone($this); // Can't pass and return $this to a listener, sadface.
        Event::trigger('dashboardLoad', $instance);
        $this->view = $instance->view;
    }
}
