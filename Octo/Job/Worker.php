<?php

namespace Octo\Job;

use b8\Config;
use Octo\Event;
use Octo\Store;
use Octo\System\Model\Job;
use Octo\System\Model\ScheduledJob;
use Octo\System\Store\ScheduledJobStore;
use Pheanstalk\Pheanstalk;

class Worker
{
    /**
     * If this variable changes to false, the worker will stop after the current build.
     * @var bool
     */
    protected $run = true;

    /**
     * beanstalkd host
     * @var string
     */
    protected $host;

    /**
     * beanstalkd queue to watch
     * @var string
     */
    protected $queue;

    /**
     * @var \Pheanstalk\Pheanstalk
     */
    protected $pheanstalk;

    /**
     * @var array
     */
    protected $handlers;

    /**
     * @param $host
     */
    public function __construct($host)
    {
        $this->host = $host;
        $this->queue = OCTO_QUEUE;
        $this->pheanstalk = new Pheanstalk($this->host);
        $this->handlers = $this->setupHandlers();
        $this->setupScheduledJobs();

        // Create the scheduler on startup:
        $job = new Job();
        $job->setType('Octo.System.Scheduler');

        Manager::create($job, Job::PRIORITY_HIGH);
    }

    /**
     * Start the worker.
     */
    public function run($verbose = false)
    {
        // Set up pheanstalk:
        $this->pheanstalk->watch($this->queue);
        $this->pheanstalk->ignore('default');

        while ($this->run) {
            $beanstalkJob = $this->pheanstalk->reserve(900);
            $jobData = json_decode($beanstalkJob->getData(), true);

            // Delete invalid jobs:
            if (empty($jobData) || !is_array($jobData) || empty($jobData['type'])) {
                $this->pheanstalk->delete($beanstalkJob);
                continue;
            }

            $job = new Job($jobData);

            // Delete jobs we don't have handlers for:
            if (!array_key_exists($job->getType(), $this->handlers)) {
                $this->pheanstalk->delete($beanstalkJob);
                Manager::update($job, Job::STATUS_FAILURE, 'No handler exists for this job type.');
                continue;
            }

            // Try and load the job handler:
            $handlerClass = $this->handlers[$job->getType()]['handler'];

            try {
                $handler = new $handlerClass($job, $this, $verbose);

                if (!($handler instanceof Handler)) {
                    throw new \Exception('Job handler does not extend \Octo\Job\Handler.');
                }

                Manager::update($job, Job::STATUS_RUNNING);

                // Try and run the job:
                $success = $handler->run();
            } catch (\Exception $ex) {
                $this->pheanstalk->delete($beanstalkJob);
                Manager::update($job, Job::STATUS_FAILURE, $ex->getMessage());
                continue;
            }

            Manager::update($job, ($success ? Job::STATUS_SUCCESS : Job::STATUS_FAILURE), $handler->getMessage());

            // Remove the job when we're done:
            $this->pheanstalk->delete($beanstalkJob);
        }
    }

    /**
     * @return array
     */
    protected function setupHandlers()
    {
        // Get handlers:
        $handlers = [];
        Event::trigger('RegisterJobHandlers', $handlers);

        $rtn = [];

        foreach ($handlers as $handler) {
            $func = [$handler, 'getJobTypes'];

            if (class_exists($handler) && method_exists($handler, 'getJobTypes')) {
                foreach (call_user_func($func) as $jobType => $jobName) {
                    $rtn[$jobType] = [
                        'name' => $jobName,
                        'handler' => $handler,
                    ];
                }
            }
        }

        return $rtn;
    }

    protected function setupScheduledJobs()
    {
        $schedule = [];
        Event::trigger('Job.Schedule', $schedule);

        /** @var ScheduledJobStore $store */
        $store = Store::get('ScheduledJob');

        foreach ($schedule as $type => $data) {
            $job = $store->getByType($type);

            if (!$job) {
                $job = new ScheduledJob();
                $job->setType($type);
            }

            if (!empty($data['frequency'])) {
                $job->setFrequency((int)$data['frequency']);
            } else {
                $job->setFrequency(86400);
            }

            if (!empty($data['data'])) {
                $job->setData($data['data']);
            } else {
                $job->setData([]);
            }

            $store->save($job);
        }
    }
}
