<?php

namespace Octo\Spider\Event;

use Octo\Admin\Template;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Store;

class DashboardWidget extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('DashboardWidgets', array($this, 'getWidget'));
    }

    public function getWidget(&$widgets)
    {
        $view = Template::getAdminTemplate('Dashboard/widget', 'Spider');
        $view->deadLinks= Store::get('SpiderDeadLink')->getAll();
        $widgets[] = ['order' => 50, 'html' => $view->render()];
    }
}