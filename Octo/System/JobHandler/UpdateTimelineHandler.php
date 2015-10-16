<?php

namespace Octo\System\JobHandler;

use Octo\Job\Handler;
use Octo\Store;
use Octo\System\Model\Log;

class UpdateTimelineHandler extends Handler
{
    public static function getJobTypes()
    {
        return [
            'Octo.System.UpdateTimeline' => 'Update Timeline',
        ];
    }

    public function run()
    {
        $this->updateUploads('images');
        $this->updateUploads('files');

        return true;
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

        $itemCount = 0;
        $message = [];
        $user = null;

        foreach ($items as $item) {
            if (++$itemCount > 3) {
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