<?php

namespace Octo\System\Admin\Controller;

use b8\Config;
use Octo\Admin\Controller;
use Octo\Store;
use Octo\System\Model\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $this->setTitle(Config::getInstance()->get('site.name') . ': Dashboard');
        $this->addBreadcrumb('Dashboard', '/');

        $pageStore = Store::get('Page');

        if ($pageStore) {
            $this->view->pages = $pageStore->getTotal();
            $this->view->latestPages = $pageStore->getLatest(5);
        }

        $contactStore = Store::get('Contact');

        if ($contactStore) {
            $this->view->contacts = $contactStore->getTotal();
        }

        $submissionStore = Store::get('Submission');

        if ($submissionStore) {
            $this->view->submissions = $submissionStore->getTotal();
            $this->view->latestSubmissions = $submissionStore->getAll(0, 5);
        }

        $this->view->showAnalytics = false;
        if(Setting::get('analytics', 'ga_email') != '') {
            $this->view->showAnalytics = true;
        }
    }
}
