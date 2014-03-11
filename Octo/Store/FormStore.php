<?php

/**
 * Form store for table: form
 */

namespace Octo\Store;

use b8\Database;
use Octo\Model\Form;
use Octo\Store\Base\FormStoreBase;

/**
 * Form Store
 * @uses Octo\Store\Base\FormStoreBase
 */
class FormStore extends FormStoreBase
{
    public function getAll($start = 0, $limit = 25)
    {
        $query = 'SELECT * FROM form ORDER BY title ASC LIMIT :start, :limit';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':start', $start, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Form($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn);
        } else {
            return array('items' => array());
        }
    }
}
