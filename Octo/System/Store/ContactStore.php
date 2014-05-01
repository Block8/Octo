<?php

/**
 * Contact store for table: contact
 */

namespace Octo\System\Store;

use b8\Database;
use Octo;
use Octo\System\Model\Contact;

/**
 * Contact Store
 * @uses Octo\System\Store\Base\ContactStoreBase
 */
class ContactStore extends Octo\Store
{
    use Base\ContactStoreBase;

    /**
     * Get the total number of submissions in the system.
     * @param string $useConnection
     * @return int
     */
    public function getTotal($useConnection = 'read')
    {
        $query = 'SELECT COUNT(*) AS cnt FROM contact';
        $stmt = Database::getConnection($useConnection)->prepare($query);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return $data['cnt'];
            }
        }

        return 0;
    }

    public function findContact(array $details)
    {
        $database = Database::getConnection('read');

        if (isset($details['email'])) {
            $query = 'SELECT * FROM contact WHERE email = :email LIMIT 1';
            $stmt = $database->prepare($query);
            $stmt->bindValue(':email', $details['email']);

            if ($stmt->execute()) {
                $res = $stmt->fetch(Database::FETCH_ASSOC);
                return new Contact($res);
            }
        }

        if (in_array(['first_name', 'last_name', 'phone', 'postcode'], $details)) {
            $query = 'SELECT * FROM contact
                    WHERE first_name = :first AND last_name = :last AND (phone = :phone OR postcode = :postcode)
                    LIMIT 1';

            $stmt = $database->prepare($query);
            $stmt->bindValue(':first', $details['first_name']);
            $stmt->bindValue(':last', $details['last_name']);
            $stmt->bindValue(':phone', $details['phone']);
            $stmt->bindValue(':postcode', $details['postcode']);

            if ($stmt->execute()) {
                $res = $stmt->fetch(Database::FETCH_ASSOC);
                return new Contact($res);
            }
        }

        return null;
    }

    public function search($query)
    {
        $count = null;

        $query = 'SELECT * FROM contact
                    WHERE first_name LIKE \'%'.$query.'%\'
                        OR last_name LIKE \'%'.$query.'%\'
                        OR company LIKE \'%'.$query.'%\' ';

        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Contact($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }
}
