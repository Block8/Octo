<?php

namespace Octo\System\JobHandler;

use Octo\Job\Handler;
use Octo\Job\Manager;
use Octo\Store;

class CleanupHandler extends Handler
{
    public static function getJobTypes()
    {
        return [
            'Octo.System.Cleanup' => 'System Cleanup',
        ];
    }

    public function run()
    {
        /** @var \Octo\System\Store\JobStore $jobStore */
        $jobStore = Store::get('Job');

        /** @var \Octo\System\Store\LogStore $logStore */
        $logStore = Store::get('Log');

        // Get rid of any existing cleanup jobs:
        $jobs = $jobStore->getByType('Octo.System.Cleanup');

        foreach ($jobs as $job) {
            if ($job->getId() != $this->job->getId()) {
                Manager::delete($job);
            }
        }

        // Remove old jobs:
        $jobStore->removeOldSuccessfulJobs();
        $jobStore->removeOldFailedJobs();

        // Remove old logs:
        $logStore->removeOldLogs();
        
        return true;
    }
}
