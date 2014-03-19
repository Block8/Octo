<?php

/**
 * Submission store for table: submission
 */

namespace Octo\Forms\Store;

use b8\Database;
use Octo;
use Octo\Forms\Model\Submission;

/**
 * Submission Store
 * @uses Octo\Forms\Store\Base\SubmissionStoreBase
 */
class SubmissionStore extends Octo\Store
{
    use Base\SubmissionStoreBase;

    /**
     * Get the total number of submissions in the system.
     * @param string $useConnection
     * @return int
     */
    public function getTotal($useConnection = 'read')
    {
        $query = 'SELECT COUNT(*) AS cnt FROM submission';
        $stmt = Database::getConnection($useConnection)->prepare($query);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return $data['cnt'];
            }
        }

        return 0;
    }

    public function getAll($start = 0, $limit = 25)
    {
        $query = 'SELECT * FROM submission ORDER BY id DESC LIMIT :start, :limit';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':start', $start, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Submission($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function getAllForForm($form, $start = 0, $limit = 25)
    {
        $query = 'SELECT * FROM submission WHERE form_id = :form_id ORDER BY id DESC LIMIT :start, :limit';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':form_id', $form->getId(), \PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Submission($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
}
