<?php

namespace Octo\Forms\Event;

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
        $manager->registerListener('DashboardStatistics', array($this, 'getStatistics'));
    }

    public function getStatistics(&$stats)
    {
        //$contactStore = Store::get('Contact');
        $submissionStore = Store::get('Submission');
        /* TODO: Put back when contact management widget is added
        $stats[] = [
            'title' => 'Contacts',
            'count' => number_format($contactStore->getTotal()),
            'icon' => 'person-stalker',
            'color' => 'aqua',
            'link' => '/contact',
        ];
        */
        $stats[] = [
            'title' => 'Submissions',
            'count' => number_format($submissionStore->getTotal()),
            'icon' => 'email',
            'color' => 'green',
            'link' => '/form',
        ];
    }

    public function getWidget(&$widgets)
    {
        $submissionStore = Store::get('Submission');

        $view = Template::getAdminTemplate('Dashboard/widget', 'Forms');
        $view->latestSubmissions = $submissionStore->getAll(0, 5);

        $widgets[] = ['order' => 10, 'html' => $view->render()];
    }
}
