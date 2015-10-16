<?php

namespace Octo\System\Event;

use Octo\Event\Listener;
use Octo\Event\Manager;

class RegisterJobHandlers extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('RegisterJobHandlers', function (&$handlers) {
            $handlers[] = 'Octo\System\JobHandler\UpdateTimelineHandler';
            $handlers[] = 'Octo\System\JobHandler\SendEmailHandler';
            $handlers[] = 'Octo\System\JobHandler\SchedulerHandler';
        });
    }
}
