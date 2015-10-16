<?php

namespace Octo\System\JobHandler;

use Octo\Job\Handler;
use Octo\Job\Manager;
use Octo\Store;
use Octo\System\Model\Job;

class SchedulerHandler extends Handler
{
    public static function getJobTypes()
    {
        return [
            'Octo.System.Scheduler' => 'System Scheduler',
        ];
    }

    public function run()
    {
        /** @var \Octo\System\Store\JobStore $jobStore */
        $jobStore = Store::get('Job');

        /** @var \Octo\System\Store\ScheduledJobStore $scheduleStore */
        $scheduleStore = Store::get('ScheduledJob');

        // Clean up existing Scheduler jobs from the database:
        $jobs = $jobStore->getByType('Octo.System.Scheduler');

        foreach ($jobs as $job) {
            if ($job->getId() != $this->job->getId()) {
                Manager::delete($job);
            }
        }

        // Create the next Scheduler job:
        Manager::create($this->job, Job::PRIORITY_HIGH, 5);

        // Schedule other jobs:
        $jobs = $scheduleStore->getJobsToSchedule();

        foreach ($jobs as $item) {
            $job = new Job();
            $job->setType($item->getType());

            $data = json_decode($item->getData(), true);

            if (!empty($data) && is_array($data)) {
                $job->setData($data);
            }

            $job = Manager::create($job);
            $item->setCurrentJob($job);

            $scheduleStore->save($item);
        }

        return true;
    }
}
