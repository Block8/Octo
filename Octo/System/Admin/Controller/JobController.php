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
        Manager::queue($job);

        $job->setDateUpdated(new \DateTime());
        $this->jobStore->save($job);

        $this->successMessage('Job resubmitted to the queue.', true);
        $this->redirect('/job');
    }

    public function delete($jobId)
    {
        try {
            $job = $this->jobStore->getById($jobId);
            Manager::delete($job);

            $this->successMessage('Job deleted.', true);
        } catch (\Exception $ex) {
            $this->errorMessage('Job could not be deleted, it is likely that it is associated with a scheduled job.');
        }

        $this->redirect('/job');
    }
}