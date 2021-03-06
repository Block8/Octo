<?php

namespace Octo\Job;

use Octo\Job\Worker;
use Octo\System\Model\Job;

abstract class Handler
{
    /**
     * @var \Octo\System\Model\Job
     */
    protected $job;

    /**
     * @var Worker
     */
    protected $worker;

    /**
     * @var bool
     */
    protected $verbose;

    public function __construct(Job $job, Worker $worker, $verbose = false)
    {
        $this->job = $job;
        $this->worker = $worker;
        $this->verbose = $verbose;
        $this->verifyJob();
    }

    /**
     * @throws \Exception
     */
    protected function verifyJob()
    {
        return true;
    }

    /**
     * @throws \Exception
     */
    abstract public function run();

    /**
     * Returns a list of job types this handler can handle.
     * @return array
     */
    abstract public static function getJobTypes();

    /**
     * Return a message to add to the job record in the database.
     * @return null|string
     */
    public function getMessage()
    {
        return null;
    }

    protected function debug($message)
    {
        if (!$this->verbose) {
            return;
        }
        
        print date('Y-m-d H:i:s') . ' - ' . $message  . PHP_EOL;
    }
}
