<?php

/**
 * Job store for table: job */

namespace Octo\System\Store;

use Block8\Database\Connection;
use Octo;

/**
 * Job Store
 */
class JobStore extends Base\JobStoreBase
{
	public function removeOldSuccessfulJobs() : bool
    {
        return $this->removeOldJobs((new \DateTime())->modify('-1 day'), Octo\System\Model\Job::STATUS_SUCCESS);
    }

    public function removeOldFailedJobs() : bool
    {
        return $this->removeOldJobs((new \DateTime())->modify('-14 days'), Octo\System\Model\Job::STATUS_FAILURE);
    }

    public function removeOldJobs(\DateTime $cutoffDate, int $status = Octo\System\Model\Job::STATUS_SUCCESS) : bool
    {
        $stmt = Connection::get()->prepare('DELETE FROM job WHERE status = :status AND date_updated < :cutoff');
        $stmt->bindValue(':status', $status, \PDO::PARAM_INT);
        $stmt->bindValue(':cutoff', $cutoffDate->format('Y-m-d H:i'), \PDO::PARAM_STR);

        return $stmt->execute();
    }
}
