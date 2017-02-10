<?php

namespace Octo\System\Admin\Controller;

use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Event;
use Octo\Job\Manager;
use Octo\Store;
use Octo\System\Model\Job;
use Octo\System\Model\ScheduledJob;

class JobController extends Controller
{
    /**
     * @var \Octo\System\Store\JobStore
     */
    protected $jobStore;

    /**
     * @var \Octo\System|Store\ScheduledJobStore
     */
    protected $scheduleStore;

    protected $handlers;

    public static function registerMenus(Menu $menu)
    {
        $dev = $menu->getRoot('Developer');

        if (!$dev) {
            $dev = $menu->addRoot('Developer', '/developer', false)->setIcon('cogs');
        }

        $job = new Menu\Item('Jobs', '/job');
        $job->addChild(new Menu\Item('Delete Job', '/job/delete', true));
        $job->addChild(new Menu\Item('Retry Job', '/job/retry', true));

        $dev->addChild($job);
        $dev->addChild(new Menu\Item('Scheduled Jobs', '/job/schedule'));
    }

    public function init()
    {
        parent::init();

        $this->setTitle('Jobs');
        $this->addBreadcrumb('Jobs', '/job');

        $this->jobStore = Store::get('Job');
        $this->scheduleStore = Store::get('ScheduledJob');

        // Get job handlers:
        $registeredHandlers = [];
        Event::trigger('RegisterJobHandlers', $registeredHandlers);

        $this->handlers = [];

        foreach ($registeredHandlers as $handler) {
            $func = [$handler, 'getJobTypes'];

            if (class_exists($handler) && method_exists($handler, 'getJobTypes')) {
                foreach (call_user_func($func) as $jobType => $jobName) {
                    $this->handlers[$jobType] = [
                        'name' => $jobName,
                        'handler' => $handler,
                    ];
                }
            }
        }
    }

    public function index()
    {
        $jobs = $this->jobStore->find()->order('date_updated', 'DESC')->limit(200)->get();
        $this->template->set('jobs', $jobs);
        $this->template->set('handlers', $this->handlers);
    }

    public function schedule()
    {
        $this->setTitle('Scheduled Jobs');

        $handlers = $this->handlers;

        $this->view->addFunction('job_name',  function ($args) use ($handlers) {
            return $handlers[$args['type']]['name'];
        });

        $this->view->schedule = $this->scheduleStore->all();
    }

    public function retry($jobId)
    {
        $job = $this->jobStore->getById($jobId);
        $job->setStatus(0);
        $job->setDateUpdated(new \DateTime());
        Manager::queue($job);
        
        return $this->redirect('/job')->success('Job resubmitted to the queue.');
    }

    public function delete($jobId)
    {
        try {
            $job = $this->jobStore->getById($jobId);
            Manager::delete($job);
            return $this->redirect('/job')->success('Job deleted.');
        } catch (\Exception $ex) {
            return $this->redirect('/job')
                        ->error('Job could not be deleted, it is likely that it is associated with a scheduled job.');
        }
    }

    public function runScheduled(int $scheduleId)
    {
        $scheduled = ScheduledJob::get($scheduleId);
        $scheduled->setCurrentJobId(null);
        $scheduled->save();

        return $this->redirect('/job/schedule')->success('Job scheduled to run immediately.');
    }
}