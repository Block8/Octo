<?php

/**
 * SystemJob store for table: system_job */

namespace Octo\System\Store;

use b8\Database\Query;
use b8\Database;
use Octo;

/**
 * SystemJob Store
 */
class SystemJobStore extends Octo\Store
{
    use Base\SystemJobStoreBase;

    public function getNextJob()
    {
        $query = new Query('\Octo\System\Model\SystemJob');
        $query->select('*')->from('system_job')->order('run_date', 'ASC')->limit(1);

        if ($query->execute()) {
            $job = $query->fetch();

            if ($job->getRunDate() <= new \DateTime()) {
                return $job;
            }
        }

        return null;
    }
}
