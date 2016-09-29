<?php
/**
 * @copyright    Copyright 2015, Block 8 Limited.
 */

namespace Octo\System\Command;

use b8\Config;
use Octo\Job\Worker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
* Worker Command - Starts the BuildWorker, which pulls jobs from beanstalkd
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Console
*/
class WorkerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('octo:worker')
            ->setDescription('Runs the Octo job worker.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!OCTO_QUEUE_ENABLED) {
            $error = 'The worker is not configured. You must set a worker host in your site config file.';
            throw new \Exception($error);
        }


        $worker = new Worker(Config::getInstance()->get('Octo.worker.host'));
        $worker->run($input->getOption('verbose'));
    }
}
