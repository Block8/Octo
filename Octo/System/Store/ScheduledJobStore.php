<?php

/**
 * ScheduledJob store for table: scheduled_job */

namespace Octo\System\Store;

use b8\Database\Query;
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
        $query = new Query($this->getNamespace('ScheduledJob').'\Model\ScheduledJob', 'read');
        $query->select('s.*');
        $query->from('scheduled_job', 's');
        $query->join('job', 'j', 's.current_job_id = j.id');
        $query->where('(j.id IS NULL) OR ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(j.date_updated)) >= s.frequency AND j.status > 1)');

        try {
            $query->execute();
            return new ScheduledJobCollection($query->fetchAll());
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ScheduledJob by CurrentJob', 0, $ex);
        }

    }
}
