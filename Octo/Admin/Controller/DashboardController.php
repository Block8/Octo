<?php

namespace Octo\Admin\Controller;

use Octo\Admin\Controller;
use Octo\Store;
use b8\Config;

class DashboardController extends Controller
{
    public function index()
    {
        $this->setTitle(Config::getInstance()->get('site.name') . ': Dashboard');
        $this->addBreadcrumb('Dashboard', '/');

        $this->view->pages = Store::get('Page')->getTotal();
        $this->view->contacts = Store::get('Contact')->getTotal();
        $this->view->submissions = Store::get('Submission')->getTotal();

        $this->view->latestSubmissions = Store::get('Submission')->getAll(0, 10);
        $this->view->latestPages = Store::get('Page')->getLatest(10);
    }
}
