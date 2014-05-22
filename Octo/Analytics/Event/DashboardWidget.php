<?php

namespace Octo\Analytics\Event;

use Octo\Admin\Template;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Store;
use Octo\System\Model\Setting;

class DashboardWidget extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('DashboardWidgets', array($this, 'getWidget'));
    }

    public function getWidget(&$widgets)
    {
        if (Setting::get('analytics', 'ga_email') != '') {
            $view = Template::getAdminTemplate('Dashboard/widget', 'Analytics');
            $widgets[] = ['order' => 0, 'html' => $view->render()];
        }
    }
}
