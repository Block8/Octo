<?php

namespace Octo\Pages\Event;

use Octo\Admin\Template;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Store;

class DashboardWidget extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('DashboardWidgets', array($this, 'pagesWidget'));
    }

    public function pagesWidget(&$widgets)
    {
        $pageStore = Store::get('Page');

        $view = Template::getAdminTemplate('Dashboard/widget', 'Pages');
        $view->pages = $pageStore->getTotal();
        $view->latestPages = $pageStore->getLatest(5);

        $widgets[] = ['order' => 10, 'html' => $view->render()];
    }
}