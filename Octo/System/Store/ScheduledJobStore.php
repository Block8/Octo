<?php

/**
 * ScheduledJob store for table: scheduled_job */

namespace Octo\System\Store;

use Block8\Database\Query;
use Octo;
use Octo\System\Model\ScheduledJobCollection;
use b8\Exception\StoreException;
use PDOException;

/**
 * ScheduledJob Store
 */
class ScheduledJobStore extends Base\ScheduledJobStoreBase
{
	/**
     * Get jobs due to be scheduled.
     */
    public function getJobsToSchedule()
    {
        return $this->find()->select('scheduled_job.*')
                    ->join('job', 'job.id', 'scheduled_job.current_job_id')
                    ->rawWhere('(job.id IS NULL) OR ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(job.date_updated)) >= scheduled_job.frequency AND job.status > 1)')->get();
    }
}
