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
        /** @var \Octo\System\Model\User $user */
        $user = $_SESSION['user'];

        if ($user->canAccess('/contact')) {
            $contactStore = Store::get('Contact');

            $total = $contactStore->getTotal();
            $stats[] = [
                'title' => $total == 1 ? 'Contact' : 'Contacts',
                'count' => number_format($total),
                'icon' => 'person-stalker',
                'color' => 'aqua',
                'link' => '/contact',
                'link_title' => 'View Contacts',
            ];
        }
    }
}
