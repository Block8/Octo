<?php

namespace Octo\System\Event;

use Octo\Admin\Template;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Store;
use Octo\System\Model\Setting;

class DashboardWidget extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('DashboardStatistics', array($this, 'getStatistics'));
    }

    public function getStatistics(&$stats)
    {
        $contactStore = Store::get('Contact');
        $stats[] = [
            'title' => 'Contacts',
            'count' => number_format($contactStore->getTotal()),
            'icon' => 'person-stalker',
            'color' => 'aqua',
            'link' => '/contact',
        ];
    }
}
