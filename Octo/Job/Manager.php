<?php

namespace Octo\Job;

use b8\Config;
use Octo\Store;
use Octo\System\Model\Job;
use Octo\System\Store\JobStore;
use Pheanstalk\Pheanstalk;

class Manager
{
    /**
     * @param Job $job
     * @param int $priority
     * @param int $delay (seconds)
     * @return Job
     */
    public static function create(Job $job, $priority = Job::PRIORITY_NORMAL, $delay = 0) {
        $job->setDateCreated(new \DateTime());
        $job->setDateUpdated(new \DateTime());
        $job->setStatus(0);

        $job = self::save($job);
        $job = self::queue($job, $priority, $delay);

        return $job;
    }

    public static function update(Job $job, $status, $message = null)
    {
        $job->setStatus($status);

        if (!is_null($message)) {
            $job->setMessage($message);
        }

        return self::save($job);
    }

    /**
     * @param Job $job
     * @param int $priority
     * @param int $delay (seconds)
     * @return Job
     */
    public static function queue(Job $job, $priority = Job::PRIORITY_NORMAL, $delay = 0)
    {
        if (OCTO_QUEUE_ENABLED) {
            $pheanstalk = new Pheanstalk(Config::getInstance()->get('Octo.worker.host'));
            $pheanstalk->useTube(OCTO_QUEUE);
            $id = $pheanstalk->put($job->toJson(), $priority, $delay);

            $job->setQueueId($id);
            $job = self::save($job);
        }

        return $job;
    }

    public static function delete(Job $job)
    {
        if ($job->getQueueId()) {
            $pheanstalk = new Pheanstalk(Config::getInstance()->get('Octo.worker.host'));

            try {
                $beanstalkJob = $pheanstalk->peek($job->getQueueId());
                $pheanstalk->delete($beanstalkJob);
            } catch (\Exception $ex) {
                // Job is not in beanstalk.
            }
        }

        self::getStore()->delete($job);
    }

    /**
     * @return JobStore
     */
    protected static function getStore()
    {
        return Store::get('Job');
    }

    /**
     * @param Job $job
     * @return Job
     */
    protected static function save(Job $job)
    {
        return self::getStore()->save($job);
    }
}