<?php

namespace Octo\System\Event;

use Octo\Event\Listener;
use Octo\Event\Manager;

class Jobs extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('RegisterJobHandlers', function (&$handlers) {
            $handlers[] = 'Octo\System\JobHandler\UpdateTimelineHandler';
            $handlers[] = 'Octo\System\JobHandler\SendEmailHandler';
            $handlers[] = 'Octo\System\JobHandler\SchedulerHandler';
            $handlers[] = 'Octo\System\JobHandler\CleanupHandler';
        });

        $manager->registerListener('Job.Schedule', function (&$handlers) {
            $handlers['Octo.System.UpdateTimeline'] = [
                'frequency' => 900,
            ];

            $handlers['Octo.System.Cleanup'] = [
                'frequency' => 3600,
            ];
        });
    }
}
