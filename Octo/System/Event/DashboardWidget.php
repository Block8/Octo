<?php

namespace Octo\System\Event;

use Octo\Template;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Store;
use Octo\System\Model\Setting;

class DashboardWidget extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('DashboardStatistics', array($this, 'getStatistics'));
        $manager->registerListener('DashboardWidgets', array($this, 'getWidget'));

    }

    public function getStatistics(&$stats)
    {
        /** @var \Octo\System\Model\User $user */
        $user = $_SESSION['user'];

        if ($user->canAccess('/contact')) {
            $contactStore = Store::get('Contact');
            $total = $contactStore->getTotal();

            if ($total) {
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

    public function getWidget(&$widgets)
    {
        // Recently active users:
        /** @var \Octo\System\Store\UserStore $store */
        $store = Store::get('User');

        $view = new Template('Dashboard/recent-users', 'admin');
        $view->users = $store->getRecentUsers();

        $widgets[] = ['order' => 1, 'html' => $view->render()];
    }
}
