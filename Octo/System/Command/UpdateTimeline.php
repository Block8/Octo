<?php

namespace Octo\System\Command;

use b8\Config;
use b8\Database;
use Octo\Store;
use Octo\Event;
use Octo\System\Model\Log;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build a search index for the site
 *
 */

class UpdateTimeline extends Command
{
    protected $systemModels;

    /**
     * @var \Octo\System\Store\LogStore
     */
    protected $logStore;

    protected function configure()
    {
        $this
            ->setName('timeline:update')
            ->setDescription('Update the admin timeline.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateUploads('images');
        $this->updateUploads('files');
    }

    protected function updateUploads($scope)
    {
        $logStore = Store::get('Log');
        $fileStore = Store::get('File');

        $last = $logStore->getLastEntry($scope);

        $date = new \DateTime('-1 day');

        if (!is_null($last)) {
            $date = $last->getLogDate();
        }

        $items = $fileStore->getAllForScopeSince($scope, $date);

        $i = 0;
        $message = [];
        $user = null;

        foreach ($items as $item) {
            if (++$i > 3) {
                break;
            }

            if (empty($user)) {
                $user = $item->getUser();
            }

            $message[] = ['title' => $item->getTitle(), 'id' => $item->getId()];
        }

        if (count($message)) {
            $log = Log::create(Log::TYPE_CREATE, $scope, json_encode($message));
            $log->setUser($user);
            $log->save();
        }
    }
}
