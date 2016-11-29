<?php

namespace Octo\System\Controller;

use Octo\Controller;
use Octo\Store;

class SystemJobController extends Controller
{
    /**
     * @var \Octo\System\Store\SystemJobStore
     */
    protected $store;

    public function run()
    {
        $this->store = Store::get('SystemJob');

        $job = $this->store->getNextJob();

        if (!is_null($job)) {
            $runDate = $job->getRunDate();
            $runDate->add(new \DateInterval($job->getFrequency()));

            $job->setRunDate($runDate);
            $this->store->save($job);

            chdir(APP_PATH);
            exec('nohup /usr/bin/php vendor/bin/octocmd ' . $job->getCommand() . ' >/dev/null 2>/dev/null &');
        }

        return $this->raw('OK');
    }
}
